# AGENTS.md

## Jua Literária Juazeiro
Sistema interativo para criação de histórias em quadrinhos personalizadas. Alunos preenchem um wizard de 4 etapas sobre sua identidade, e uma IA local (Ollama + DeepSeek R1 1.5B) gera automaticamente uma HQ com texto personalizado. O resultado é exibido em HTML estilo gibi com QR Code para compartilhamento.

## Acesso

| Tipo | URL | Credenciais |
|------|-----|-------------|
| **Aluno** (touch/TV) | `/` | Nome + Série (sem senha) |
| **Mediador** (admin) | `/login` | `admin@admin.com` / `123456` |

## Fluxo do Aluno

1. Tela inicial "Jua Literária Juazeiro" → **Nova História**
2. Digita **nome** e **série** (cadastro automático)
3. **Etapa 1** — Quem é você? (nome, idade, aparência)
4. **Etapa 2** — Onde você vive? (bairro, lugares, escola)
5. **Etapa 3** — Quem está com você? (família, amigos)
6. **Etapa 4** — O que te move? (sonhos, brincadeiras, desafios)
7. **Revisão** — resumo de tudo
8. **Geração** — chama Ollama local → QR Code → HTML para impressão
9. **Resultado** — QR Code na tela + botão "Ver e Imprimir HQ" + botão "Gerar com IA" (se conteúdo for fallback)

## Comandos

| Comando | Função |
|---------|--------|
| `composer dev` | Servidor + Vite |
| `php artisan migrate` | Rodar migrations |
| `php artisan migrate:fresh` | Resetar banco |
| `php artisan db:seed` | Criar admin padrão |
| `php artisan storage:link` | Link do storage público |
| `npm run build` / `npm run dev` | Assets Vite |
| `/tmp/ollama_extract/bin/ollama serve` | Iniciar Ollama (porta 11434) |

## Estrutura

### Rotas
- **`routes/web.php`** — Rotas do site (alunos) + admin (mediador)
- **SiteController** — Welcome, entrar, biblioteca
- **CriarHistoriaController** — Wizard 4 etapas, Ollama, QR Code, HTML print

### Modelos
- **Aluno** — `id, nome, serie`
- **Historia** — `id, aluno_id, status, prompt_gerado, resposta_gemini, pdf_path, slug, qr_code_path`
- **RespostaAluno** — `id, historia_id, etapa, pergunta, resposta`

### Admin
- **Dashboard** — Stats (alunos, histórias, hoje)
- **Histórias** — Listar, ver detalhes, excluir
- **Alunos** — Listar, ver histórias de cada um

## Integração IA Local (Ollama)

- **Modelo**: `deepseek-r1:1.5b` (rodando localmente via Ollama)
- **Endpoint**: `http://127.0.0.1:11434/api/generate`
- **Instalação**: binary em `/tmp/ollama_extract/bin/ollama` (compilado manualmente)
- **Iniciar**: `OLLAMA_HOST=127.0.0.1 /tmp/ollama_extract/bin/ollama serve`
- **Sem dependência externa**: não precisa de chave de API, roda 100% local
- **Prompt**: montado automaticamente com dados das 4 etapas
- **Fallback**: se a IA local falhar, mostra textos padrão com botão "Gerar com IA" para tentar novamente
- **Regeneração**: qualquer história com conteúdo fallback pode ser regenerada clicando no botão na página de resultado

## Setup

1. `php artisan migrate`
2. `php artisan db:seed`
3. `php artisan storage:link`
4. Iniciar Ollama: `OLLAMA_HOST=127.0.0.1 /tmp/ollama_extract/bin/ollama serve`
5. `composer dev`

## Interface
- TV / touch screen (1920x1080)
- Nunito font, cores vibrantes, botões grandes
- Fundo gradiente animado com estrelas flutuantes
- Impressão: HTML + CSS Grid com `@media print` e `window.print()`
