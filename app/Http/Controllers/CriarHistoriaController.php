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
            'idade' => 'Quantos anos você tem?',
            'pele' => 'Qual é o tom da sua pele?',
        ],
        2 => [
            'bairro' => 'Em qual região de Juazeiro você mora?',
        ],
        3 => [
            'mora_com' => 'Com quem você mora?',
        ],
        4 => [
            'sonho' => 'Qual é o seu maior sonho?',
        ],
    ];

    private $alternativas = [
        1 => [
            'idade' => ['6 anos', '7 anos', '8 anos', '9 anos', '10 anos', '11 anos', '12 anos'],
            'pele' => ['Branca', 'Parda', 'Morena', 'Negra'],
        ],
        2 => [
            'bairro' => ['Centro', 'Jardim Primavera', 'João Paulo II', 'São Geraldo', 'Tabuleiro', 'Outro'],
        ],
        3 => [
            'mora_com' => ['Mãe e pai', 'Só minha mãe', 'Só meu pai', 'Meus avós', 'Outros familiares'],
        ],
        4 => [
            'sonho' => ['Ser professor(a)', 'Ser médico(a)', 'Ser artista', 'Ser esportista', 'Viajar o mundo', 'Cuidar da minha família'],
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
        $alternativas = $this->alternativas[$num];
        $respostas = $historia->respostas->where('etapa', $num);

        return view('site.criar.etapa' . $num, [
            'etapa' => $num,
            'totalEtapas' => 4,
            'tituloEtapa' => $this->etapas[$num],
            'perguntas' => $perguntas,
            'alternativas' => $alternativas,
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

        $data = $this->chamarOpenRouter($prompt, $historia);

        $panelTexts = $data['panel_texts'] ?? [];
        $panelImages = [];

        if (!empty($panelTexts)) {
            $imagemPath = $this->gerarImagemAluno($historia);
            if ($imagemPath) {
                $panelImages = [$imagemPath];
            } else {
                $panelImages = $this->sortearImagens();
            }
        }

        $respostaGemini = [
            'panel_texts' => $panelTexts,
            'panel_images' => $panelImages,
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
        $dataUri = $qrcode->render($url);

        if (str_starts_with($dataUri, 'data:image/png;base64,')) {
            $imageData = base64_decode(substr($dataUri, strlen('data:image/png;base64,')));
        } else {
            $imageData = $dataUri;
        }

        $path = "hqs/{$historia->slug}/qrcode.png";
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    private function gerarImagemAluno($historia): ?string
    {
        try {
            $apiKey = config('services.openrouter.key');
            if (!$apiKey) {
                return null;
            }

            $aluno = $historia->aluno;
            $respostas = $historia->respostas->pluck('resposta', 'pergunta');

            $idade = $respostas->get('Quantos anos você tem?', '');
            $pele = $respostas->get('Qual é o tom da sua pele?', '');
            $bairro = $respostas->get('Em qual região de Juazeiro você mora?', '');
            $moraCom = $respostas->get('Com quem você mora?', '');
            $sonho = $respostas->get('Qual é o seu maior sonho?', '');

            $fundos = [
                "com a Ponte Presidente Dutra e o Rio Sao Francisco ao fundo",
                "na Orla de Juazeiro, com o Rio Sao Francisco ao fundo",
                "em frente a escola, com arvores e o ceu azul",
                "numa praca arborizada de Juazeiro, com criancas brincando",
                "no bairro {$bairro}, com casas coloridas e o Rio Sao Francisco ao longe",
                "caminhando por uma rua arborizada de Juazeiro, com flores e passaros",
            ];

            $fundo = $fundos[array_rand($fundos)];

            $promptImagem = "Ilustracao infantil colorida estilo HQ brasileira. ";
            $promptImagem .= "Um(a) aluno(a) chamado(a) {$aluno->nome}";
            if ($idade) $promptImagem .= ", {$idade} anos";
            if ($pele) $promptImagem .= ", pele {$pele}";
            $promptImagem .= ", sorrindo, em Juazeiro-BA";
            $promptImagem .= ", {$fundo}";
            $promptImagem .= ", ceu azul, estilo cartoon brasileiro infantil, cores vibrantes, tracos simples e alegres.";
            $promptImagem .= " Nao coloque texto na imagem.";

            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'http://localhost'),
                'X-Title' => 'Jua Literaria Juazeiro',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemini-2.5-flash-image',
                'messages' => [
                    ['role' => 'user', 'content' => $promptImagem],
                ],
                'max_tokens' => 2000,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $images = $data['choices'][0]['message']['images'] ?? [];

            if (empty($images)) {
                return null;
            }

            $base64 = $images[0]['image_url']['url'] ?? '';
            if (!$base64 || !str_starts_with($base64, 'data:image/png;base64,')) {
                return null;
            }

            $imageData = base64_decode(substr($base64, strlen('data:image/png;base64,')));
            if (!$imageData) {
                return null;
            }

            $slug = $historia->slug;
            $storagePath = "hqs/{$slug}";
            Storage::disk('public')->makeDirectory($storagePath);
            $imagePath = "{$storagePath}/capa_hq.png";
            Storage::disk('public')->put($imagePath, $imageData);

            return Storage::url($imagePath);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function sortearImagens(): array
    {
        $dir = public_path('images/quadrinhos');
        if (!is_dir($dir)) {
            return [];
        }
        $arquivos = glob($dir . '/{*.png,*.jpg,*.jpeg,*.gif,*.webp}', GLOB_BRACE);
        if (empty($arquivos)) {
            return [];
        }
        shuffle($arquivos);
        $selecionados = array_slice($arquivos, 0, 1);
        return array_map(function ($path) {
            return asset('images/quadrinhos/' . basename($path));
        }, $selecionados);
    }

    private function montarPrompt($historia)
    {
        $respostas = $historia->respostas->groupBy('etapa');
        $aluno = $historia->aluno;

        $cenarios = [
            'Juazeiro-BA, cidade banhada pelo Rio Sao Francisco',
            'Juazeiro-BA, na beira do Rio Sao Francisco',
            'Juazeiro-BA, com suas ruas arborizadas e o Rio Sao Francisco',
        ];

        $locais = [
            'na orla de Juazeiro',
            'no bairro onde mora',
            'na praca perto de casa',
            'no caminho da escola',
            'no parque da cidade',
        ];

        $cenario = $cenarios[array_rand($cenarios)];
        $local = $locais[array_rand($locais)];

        $texto = "Crie uma história curta em formato de poema ou narrativa infantil sobre um aluno chamado {$aluno->nome}.\n\n";
        $texto .= "Informacoes do aluno:\n";
        foreach ($respostas as $etapa => $itens) {
            foreach ($itens as $resposta) {
                $texto .= "- {$resposta->resposta}\n";
            }
        }
        $texto .= "\nEscreva 5 a 6 frases curtas contando a historia do aluno. Separe cada frase com 3 tracos (---).\n";
        $texto .= "Use linguagem infantil, maximo 15 palavras por frase.\n";
        $texto .= "Ambientada em {$cenario}. A historia se passa {$local}.\n";
        $texto .= "VARIE os lugares e pontos turisticos de Juazeiro citados em cada historia (nao repita sempre a Ponte).\n";
        $texto .= "Responda APENAS as frases separadas por ---, sem introducao ou conclusao.\n";
        $texto .= "\nExemplo:\n";
        $texto .= "Eu sou {$aluno->nome} e moro em Juazeiro!\n---\n";
        $texto .= "Minha casa fica perto do Rio Sao Francisco.\n---\n";
        $texto .= "Eu adoro brincar na praca do meu bairro.\n---\n";
        $texto .= "Meu maior sonho e ser feliz!\n";
        return $texto;
    }

    private function chamarOpenRouter($prompt, $historia = null)
    {
        try {
            $apiKey = config('services.openrouter.key');
            if (!$apiKey) {
                throw new \Exception('OpenRouter API key not configured');
            }

            $model = config('services.openrouter.model', 'deepseek/deepseek-v4-flash');

            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'http://localhost'),
                'X-Title' => 'Jua Literaria Juazeiro',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Voce e um assistente que cria historias infantis. Responda SEMPRE em portugues brasileiro. Gere 5 ou 6 frases curtas separadas por ---. Nao use numeracao. Nao inclua introducao ou conclusao. Apenas as frases separadas por ---.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 1536,
                'temperature' => 0.8,
            ]);

            if (!$response->successful()) {
                throw new \Exception('OpenRouter API error: ' . $response->body());
            }

            $text = $response->json('choices.0.message.content', '');

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
                $t = preg_replace('/^(Frase|Quadro|Painel|Linha)\s*\d+[\s:]*/i', '', $t);
                $t = preg_replace('/^[*-]\s*/', '', $t);
                return trim($t);
            }, $panelTexts);

            $panelTexts = array_values(array_filter($panelTexts));

            if (count($panelTexts) > 7) {
                $panelTexts = array_slice($panelTexts, 0, 7);
            }
            if (empty($panelTexts)) {
                $nome = $historia && $historia->aluno ? $historia->aluno->nome : 'Aluno';
                $panelTexts = ["Ola! Eu sou {$nome} e essa e minha historia em Juazeiro!"];
            }

            return [
                'panel_texts' => $panelTexts,
                'panel_images' => [],
            ];
        } catch (\Exception $e) {
            $nome = $historia && $historia->aluno ? $historia->aluno->nome : 'Aluno';
            return [
                'panel_texts' => [
                    "Ola! Eu sou {$nome} e essa e minha historia em Juazeiro!",
                    "Aqui ao lado do Rio Sao Francisco, tudo e mais bonito.",
                    "Eu cruzo a Ponte Presidente Dutra todos os dias.",
                    "Minha familia e meu lugar favorito no mundo.",
                    "E meu maior sonho eu vou realizar!"
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
            'Ola! Eu sou',
            'esse e minha historia em Juazeiro!',
            'Aqui ao lado do Rio Sao Francisco',
            'precisa configurar a chave',
            'Pedir ajuda ao seu professor',
            'desenhar sua própria história',
            'Infelizmente a IA',
            'Tente novamente mais',
            'nao conseguiu gerar sua HQ',
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
