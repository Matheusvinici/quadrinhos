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
            transition: transform 0.2s;
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
            transition: transform 0.2s;
        }
        .no-print a:hover { transform: scale(1.05); }

        .cover-page {
            width: 210mm;
            height: 297mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1A7CB8, #3DA87C, #0D5E8A);
            color: #fff;
            text-align: center;
            padding: 30mm;
            page-break-after: always;
            margin: 0 auto;
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

        .comic-page {
            width: 210mm;
            height: 297mm;
            padding: 8mm;
            page-break-after: always;
            margin: 0 auto;
            background: #FFF8E7;
        }
        .comic-grid {
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 6mm;
        }
        .panel {
            border: 3px solid #2D2D2D;
            border-radius: 4px;
            background: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 2px 2px 0 rgba(0,0,0,0.08);
        }
        .panel-number {
            position: absolute;
            top: 5px;
            right: 8px;
            background: #E8874A;
            color: #fff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13pt;
            font-weight: 900;
            border: 2px solid #fff;
            z-index: 2;
        }
        .panel-img {
            width: 100%;
            height: 65%;
            object-fit: cover;
            display: block;
            background: #f0f0f0;
        }
        .no-img {
            width: 100%;
            height: 65%;
            background: linear-gradient(135deg, #A8D8EA, #C9E4DE);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40pt;
            color: #999;
        }
        .panel-text {
            padding: 6mm 8mm;
            height: 35%;
            font-size: 11pt;
            line-height: 1.4;
            background: #fff;
            overflow: hidden;
        }
        .panel-empty {
            border: 3px dashed #ddd;
            border-radius: 4px;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            font-size: 30pt;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .cover-page, .comic-page { margin: 0; width: 100%; height: 100vh; }
            @page { margin: 0; size: A4; }
        }

        @media screen {
            body { padding: 20px; }
            .cover-page, .comic-page {
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                margin-bottom: 30px;
                border-radius: 8px;
                overflow: hidden;
            }
            .comic-page { height: auto; min-height: 297mm; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
        <a href="#" onclick="window.close()">✕ Fechar</a>
    </div>

    <div class="cover-page">
        <div style="display: flex; align-items: center; justify-content: center; gap: 2rem; margin-bottom: 15mm; flex-wrap: wrap;">
            <img src="{{ asset('images/logo-prefeitura.png') }}" alt="Prefeitura de Juazeiro" style="height: 50px; width: auto; filter: brightness(10); opacity: 0.8;">
            <img src="{{ asset('images/educajua_v.svg') }}" alt="Educa Juá" style="height: 50px; width: auto; filter: brightness(10); opacity: 0.8;">
        </div>
        <h1>Jua Literária Juazeiro</h1>
        <h2>História em Quadrinhos</h2>
        <div class="student-name">{{ $aluno->nome }}</div>
        <div class="info">Turma: {{ $aluno->serie }}</div>
        <div class="footer">Uma história criada especialmente para você!</div>
    </div>

    @php
        $total = count($panelTexts);
        if ($total === 0) { $total = 1; $panelTexts = ['...']; }
        $indices = range(0, $total - 1);
        $chunks = array_chunk($indices, 4);
    @endphp

    @foreach($chunks as $panelIndices)
        <div class="comic-page">
            <div class="comic-grid">
                @for($i = 0; $i < 4; $i++)
                    @php $idx = $panelIndices[$i] ?? null; @endphp
                    @if($idx !== null)
                        <div class="panel">
                            <div class="panel-number">{{ $idx + 1 }}</div>
                            @if(isset($panelImages[$idx]) && $panelImages[$idx])
                                <img src="{{ $panelImages[$idx] }}" class="panel-img" alt="">
                            @else
                                <div class="no-img">🎨</div>
                            @endif
                            <div class="panel-text">{{ $panelTexts[$idx] ?? '' }}</div>
                        </div>
                    @else
                        <div class="panel-empty">✨</div>
                    @endif
                @endfor
            </div>
        </div>
    @endforeach
</body>
</html>
