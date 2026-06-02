<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Historia;
use App\Models\RespostaAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;

class CriarHistoriaController extends Controller
{
    private $etapas = [
        1 => 'Quem é você?',
        2 => 'Onde você vive?',
        3 => 'Quem está com você?',
        4 => 'O que te move?',
    ];

    private $perguntas = [
        1 => [
            'nome' => 'Qual é o seu nome?',
            'idade' => 'Quantos anos você tem?',
            'pele' => 'Qual é o tom da sua pele?',
        ],
        2 => [
            'bairro' => 'Em qual bairro ou comunidade você mora?',
            'clima' => 'Como é o clima onde você vive? (quente, frio, chuvoso?)',
            'escola' => 'Qual é o nome da sua escola?',
        ],
        3 => [
            'mora_com' => 'Com quem você mora?',
            'mae' => 'Conte um pouco sobre sua mãe (ou quem cuida de você)',
            'irmaos' => 'Você tem irmãos? Quantos e como são?',
        ],
        4 => [
            'brincadeira' => 'Qual é sua brincadeira ou atividade favorita?',
            'sonho' => 'Qual é o seu maior sonho?',
            'feliz' => 'O que te faz mais feliz?',
        ],
    ];

    public function iniciar()
    {
        if (!session('aluno_id')) {
            return redirect()->route('site.entrar');
        }

        $aluno = Aluno::find(session('aluno_id'));

        $historia = Historia::create([
            'aluno_id' => $aluno->id,
            'status' => 'rascunho',
            'slug' => Str::random(12),
        ]);

        session(['historia_id' => $historia->id]);

        return redirect()->route('site.criar.etapa', ['etapa' => 1]);
    }

    public function etapa($num)
    {
        if (!session('aluno_id') || !session('historia_id')) {
            return redirect()->route('site.welcome');
        }

        $num = (int) $num;
        if ($num < 1 || $num > 4) {
            return redirect()->route('site.criar.etapa', ['etapa' => 1]);
        }

        $historia = Historia::with('respostas')->find(session('historia_id'));
        $perguntas = $this->perguntas[$num];
        $respostas = $historia->respostas->where('etapa', $num);

        return view('site.criar.etapa' . $num, [
            'etapa' => $num,
            'totalEtapas' => 4,
            'tituloEtapa' => $this->etapas[$num],
            'perguntas' => $perguntas,
            'respostas' => $respostas,
            'historia' => $historia,
        ]);
    }

    public function salvarEtapa(Request $request, $num)
    {
        if (!session('historia_id')) {
            return redirect()->route('site.welcome');
        }

        $num = (int) $num;
        $historia = Historia::find(session('historia_id'));

        $perguntas = $this->perguntas[$num];

        $rules = [];
        foreach ($perguntas as $key => $pergunta) {
            $rules[$key] = 'required|string|max:1000';
        }

        $request->validate($rules);

        RespostaAluno::where('historia_id', $historia->id)
            ->where('etapa', $num)
            ->delete();

        foreach ($perguntas as $key => $pergunta) {
            RespostaAluno::create([
                'historia_id' => $historia->id,
                'etapa' => $num,
                'pergunta' => $pergunta,
                'resposta' => $request->$key,
            ]);
        }

        if ($num < 4) {
            return redirect()->route('site.criar.etapa', ['etapa' => $num + 1]);
        }

        return redirect()->route('site.criar.revisar');
    }

    public function revisar()
    {
        if (!session('historia_id')) {
            return redirect()->route('site.welcome');
        }

        $historia = Historia::with(['aluno', 'respostas'])->find(session('historia_id'));
        $respostasAgrupadas = $historia->respostas->groupBy('etapa');

        return view('site.criar.revisar', [
            'historia' => $historia,
            'respostasAgrupadas' => $respostasAgrupadas,
            'etapas' => $this->etapas,
        ]);
    }

    public function gerar()
    {
        if (!session('historia_id')) {
            return redirect()->route('site.welcome');
        }

        $historia = Historia::with(['aluno', 'respostas'])->find(session('historia_id'));

        return $this->processarGeracao($historia);
    }

    public function regenerar($slug)
    {
        $historia = Historia::with(['aluno', 'respostas'])->where('slug', $slug)->firstOrFail();

        if (session('aluno_id') != $historia->aluno_id) {
            return redirect()->route('site.welcome');
        }

        session(['historia_id' => $historia->id]);

        return $this->processarGeracao($historia);
    }

    private function processarGeracao($historia)
    {
        $prompt = $this->montarPrompt($historia);
        $historia->update(['prompt_gerado' => $prompt]);

        $slug = $historia->slug;

        $data = $this->chamarOllama($prompt, $historia);

        $panelTexts = $data['panel_texts'] ?? [];
        $panelImages = $data['panel_images'] ?? [];

        $respostaGemini = [
            'panel_texts' => $panelTexts,
            'panel_images' => $panelImages,
            'model' => 'deepseek-r1:1.5b',
        ];

        $historia->update([
            'status' => 'concluido',
            'resposta_gemini' => $respostaGemini,
        ]);

        $qrCodePath = $this->gerarQRCode($historia);

        $historia->update([
            'qr_code_path' => $qrCodePath,
        ]);

        session()->forget('historia_id');

        return redirect()->route('site.criar.resultado', ['slug' => $slug]);
    }

    public function resultado($slug)
    {
        $historia = Historia::with('aluno')->where('slug', $slug)->firstOrFail();

        $isFallback = $this->isFallbackContent($historia);

        return view('site.resultado', compact('historia', 'isFallback'));
    }

    public function imprimir($slug)
    {
        $historia = Historia::with('aluno')->where('slug', $slug)->firstOrFail();

        $panelTexts = [];
        $panelImages = [];

        $resposta = $historia->resposta_gemini;
        if ($resposta && isset($resposta['panel_texts'])) {
            $panelTexts = $resposta['panel_texts'];
        }
        if ($resposta && isset($resposta['panel_images'])) {
            $panelImages = $resposta['panel_images'];
        }

        if (empty($panelTexts)) {
            $panelTexts = [
                "Olá! Eu sou {$historia->aluno->nome} e essa é minha história!",
                "Esta história foi criada com as informações que você compartilhou.",
                "Compartilhe com seus amigos e familiares!",
                "Obrigado por fazer parte da Jua Literária Juazeiro!"
            ];
        }

        $aluno = $historia->aluno;

        return view('hq.imprimir', compact('historia', 'aluno', 'panelImages', 'panelTexts'));
    }

    private function gerarQRCode($historia)
    {
        $url = route('site.criar.imprimir', ['slug' => $historia->slug]);

        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'scale' => 10,
        ]);

        $qrcode = new QRCode($options);
        $imageData = $qrcode->render($url);

        $path = "hqs/{$historia->slug}/qrcode.png";
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    private function montarPrompt($historia)
    {
        $respostas = $historia->respostas->groupBy('etapa');
        $aluno = $historia->aluno;

        $texto = "Crie uma história infantil curta com 4 partes para um aluno chamado {$aluno->nome}.\n\n";
        $texto .= "Informacoes do aluno:\n";

        foreach ($respostas as $etapa => $itens) {
            foreach ($itens as $resposta) {
                $texto .= "- {$resposta->resposta}\n";
            }
        }

        $texto .= "\nEscreva 4 frases curtas (uma para cada parte da historia). Separe cada frase com 3 tracos (---).\n";
        $texto .= "Use linguagem infantil, maximo 15 palavras por frase.\n";
        $texto .= "Responda APENAS as 4 frases separadas por ---, sem introducao ou conclusao.\n";
        $texto .= "\nExemplo:\n";
        $texto .= "Eu sou {$aluno->nome} e vou contar minha historia!\n---\n";
        $texto .= "Minha casa fica em um lugar muito legal.\n---\n";
        $texto .= "Minha familia e muito especial para mim.\n---\n";
        $texto .= "Meu maior sonho e ser feliz!\n";

        return $texto;
    }

    private function chamarOllama($prompt, $historia = null)
    {
        try {
            $response = Http::timeout(120)->post('http://127.0.0.1:11434/api/generate', [
                'model' => 'deepseek-r1:1.5b',
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'num_predict' => 600,
                    'temperature' => 0.7,
                ],
            ]);

            if (!$response->successful()) {
                throw new \Exception('Ollama API error: ' . $response->body());
            }

            $text = $response->json('response', '');

            if (preg_match('/<think>.*?<\/think>/s', $text)) {
                $text = preg_replace('/<think>.*?<\/think>/s', '', $text);
            }

            $text = trim(preg_replace('/[\x00-\x1F\x7F]/', '', $text));

            $parts = preg_split('/---+/', $text);
            $panelTexts = [];
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part !== '') {
                    $panelTexts[] = $part;
                }
            }

            if (empty($panelTexts)) {
                $panelTexts = array_values(array_filter(explode("\n", $text), function($l) {
                    return trim($l) !== '';
                }));
            }

            $panelTexts = array_map(function($t) {
                $t = preg_replace('/^\d+[\.\)]\s*/', '', trim($t));
                $t = preg_replace('/^(Frase|Quadro|Painel)\s*\d+[\s:]*/i', '', $t);
                $t = preg_replace('/^[*-]\s*/', '', $t);
                return trim($t);
            }, $panelTexts);

            $panelTexts = array_values(array_filter($panelTexts));

            if (count($panelTexts) > 4) {
                $panelTexts = array_slice($panelTexts, 0, 4);
            }
            while (count($panelTexts) < 4) {
                $panelTexts[] = "Que aventura incrivel!";
            }

            return [
                'panel_texts' => $panelTexts,
                'panel_images' => [],
            ];
        } catch (\Exception $e) {
            $nome = $historia && $historia->aluno ? $historia->aluno->nome : 'Aluno';
            return [
                'panel_texts' => [
                    "Ola! Eu sou {$nome} e essa e minha historia!",
                    "Infelizmente a IA local nao conseguiu gerar sua HQ agora.",
                    "Tente novamente mais clicando no botao 'Gerar com IA'.",
                    "Enquanto isso, que tal desenhar sua historia no papel?"
                ],
                'panel_images' => [],
            ];
        }
    }

    public function isFallbackContent($historia)
    {
        $resposta = $historia->resposta_gemini;
        if (!$resposta || !isset($resposta['panel_texts'])) {
            return true;
        }
        $texts = $resposta['panel_texts'];

        $fallbackMarkers = [
            'precisa configurar a chave',
            'Pedir ajuda ao seu professor',
            'desenhar sua própria história',
            'Infelizmente a IA local',
            'Tente novamente mais',
            'nao conseguiu gerar sua HQ',
            'desenhar sua historia no papel',
        ];

        foreach ($texts as $text) {
            foreach ($fallbackMarkers as $marker) {
                if (mb_strpos($text, $marker) !== false) {
                    return true;
                }
            }
        }

        return false;
    }
}
