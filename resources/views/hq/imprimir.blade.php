<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HQ - {{ $aluno->nome }} — Jua Literária Juazeiro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Nunito', 'Comic Neue', cursive, sans-serif;
            background: #FFF8E7;
            color: #2D2D2D;
        }

        .no-print { text-align: center; padding: 20px; background: #3B8FC2; color: #fff; }
        .no-print button {
            font-family: 'Nunito', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            padding: 12px 30px;
            border: none;
            border-radius: 15px;
            background: #fff;
            color: #3B8FC2;
            cursor: pointer;
            margin: 0 8px;
        }
        .no-print button:hover { transform: scale(1.05); }
        .no-print a {
            font-family: 'Nunito', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            padding: 12px 30px;
            border: none;
            border-radius: 15px;
            background: rgba(255,255,255,0.2);
            color: #fff;
            cursor: pointer;
            margin: 0 8px;
            text-decoration: none;
            display: inline-block;
        }
        .no-print a:hover { transform: scale(1.05); }

        .page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            page-break-after: always;
            overflow: hidden;
        }

        .cover-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1A7CB8, #3DA87C, #0D5E8A);
            color: #fff;
            text-align: center;
            padding: 30mm;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .cover-logos {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 15mm;
            flex-wrap: wrap;
        }
        .cover-logos img {
            height: 50px;
            width: auto;
            filter: brightness(10);
            opacity: 0.8;
        }
        .cover-page h1 { font-size: 48pt; font-weight: 900; margin-bottom: 8mm; text-shadow: 3px 3px 0 rgba(0,0,0,0.15); }
        .cover-page h2 { font-size: 22pt; font-weight: 600; opacity: 0.9; margin-bottom: 20mm; }
        .cover-page .student-name {
            font-size: 36pt; font-weight: 900;
            padding: 12mm 25mm;
            background: rgba(255,255,255,0.2);
            border-radius: 15mm;
            display: inline-block;
            margin: 10mm 0;
        }
        .cover-page .info { font-size: 16pt; opacity: 0.8; margin-top: 8mm; }
        .cover-page .footer { font-size: 14pt; opacity: 0.5; margin-top: 40mm; }

        .content-page {
            background: #FFF8E7;
            display: flex;
            flex-direction: column;
            padding: 12mm;
        }

        .text-section {
            flex: 0 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 10mm 12mm;
            margin-bottom: 8mm;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 2px solid #E8E0D0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-section .label {
            font-size: 13pt;
            font-weight: 700;
            color: #E8874A;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4mm;
        }

        .text-section p {
            font-size: 16pt;
            font-weight: 600;
            line-height: 1.7;
            color: #2D2D2D;
            margin-bottom: 0.4em;
        }

        .text-section p:last-child {
            margin-bottom: 0;
        }

        .image-section {
            flex: 1;
            border-radius: 12px;
            overflow: hidden;
            background: #f0f0f0;
            border: 2px solid #E8E0D0;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .image-section .placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #A8D8EA, #C9E4DE);
            font-size: 48pt;
            color: #999;
        }

        .student-info-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-top: 6mm;
            padding-top: 4mm;
            border-top: 1px solid #E8E0D0;
            font-size: 11pt;
            color: #888;
        }
        .student-info-bar img {
            height: 22px;
            width: auto;
            opacity: 0.5;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page { margin: 0; width: 100%; height: 100vh; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 0; size: A4; }
        }

        @media screen {
            body { padding: 20px; }
            .page {
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                margin-bottom: 30px;
                border-radius: 8px;
                overflow: hidden;
            }
            .content-page { height: auto; min-height: 100vh; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
        <a href="#" onclick="window.close()">✕ Fechar</a>
    </div>

    @php
        $temImagem = !empty($panelImages) && !empty($panelImages[0]);
        $bgImage = $temImagem ? $panelImages[0] : null;
        $linhas = is_array($panelTexts) ? $panelTexts : [$panelTexts];
    @endphp

    <div class="page cover-page">
        <div class="cover-logos">
            <img src="{{ asset('images/logo-prefeitura.png') }}" alt="Prefeitura de Juazeiro">
            <img src="{{ asset('images/educajua_v.svg') }}" alt="Educa Juá">
        </div>
        <h1>Jua Literária Juazeiro</h1>
        <h2>História em Quadrinhos</h2>
        <div class="student-name">{{ $aluno->nome }}</div>
        <div class="info">Escola: {{ $aluno->escola }}</div>
        <div class="footer">Uma história criada especialmente para você!</div>
    </div>

    <div class="page content-page">
        <div class="text-section">
            <div class="label">📖 Minha História</div>
            @foreach($linhas as $linha)
                <p>{{ $linha }}</p>
            @endforeach
        </div>

        <div class="image-section">
            @if($bgImage)
                <img src="{{ $bgImage }}" alt="Ilustração HQ">
            @else
                <div class="placeholder">🎨</div>
            @endif
        </div>

        <div class="student-info-bar">
            <img src="{{ asset('images/logo-prefeitura.png') }}" alt="">
            <img src="{{ asset('images/educajua_v.svg') }}" alt="">
            {{ $aluno->nome }} — {{ $aluno->escola }}
        </div>
    </div>
</body>
</html>
