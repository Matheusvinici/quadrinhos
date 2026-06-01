# AGENTS.md

## Jua Literária Juazeiro
Sistema interativo para criação de histórias em quadrinhos personalizadas. Alunos preenchem um wizard de 4 etapas sobre sua identidade, e o Google Gemini gera automaticamente uma HQ com texto e imagens. O resultado é exportado em PDF estilo gibi com QR Code para compartilhamento.

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
8. **Geração** — chama Gemini API → PDF → QR Code
9. **Resultado** — QR Code na tela + botão de download PDF

## Comandos

| Comando | Função |
|---------|--------|
| `composer dev` | Servidor + Vite |
| `php artisan migrate` | Rodar migrations |
| `php artisan migrate:fresh` | Resetar banco |
| `php artisan db:seed` | Criar admin padrão |
| `php artisan storage:link` | Link do storage público |
| `npm run build` / `npm run dev` | Assets Vite |

## Estrutura

### Rotas
- **`routes/web.php`** — Rotas do site (alunos) + admin (mediador)
- **SiteController** — Welcome, entrar, biblioteca
- **CriarHistoriaController** — Wizard 4 etapas, Gemini, PDF, QR Code

### Modelos
- **Aluno** — `id, nome, serie`
- **Historia** — `id, aluno_id, status, prompt_gerado, resposta_gemini, pdf_path, slug, qr_code_path`
- **RespostaAluno** — `id, historia_id, etapa, pergunta, resposta`

### Admin
- **Dashboard** — Stats (alunos, histórias, hoje)
- **Histórias** — Listar, ver detalhes, baixar PDF, excluir
- **Alunos** — Listar, ver histórias de cada um

## Integração Gemini

- **Modelo**: `gemini-2.0-flash-exp` (com suporte a imagens)
- **Fallback**: `gemini-2.0-flash` (texto apenas)
- **Chave**: configurar `GEMINI_API_KEY` no `.env`
- **Prompt**: montado automaticamente com dados das 4 etapas

## Setup

1. `cp .env.example .env` (ou editar `.env` com `GEMINI_API_KEY`)
2. `php artisan migrate`
3. `php artisan db:seed`
4. `php artisan storage:link`
5. `composer dev`

## Interface
- TV / touch screen (1920x1080)
- Nunito font, cores vibrantes, botões grandes
- Fundo gradiente animado com estrelas flutuantes
