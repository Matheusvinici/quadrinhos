<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>HQ - {{ $aluno->nome }}</title>
    <style>
        @page { margin: 10mm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12pt;
            color: #2D2D2D;
        }
        .cover {
            text-align: center;
            padding: 60mm 20mm;
            background-color: #FF6B35;
            color: #fff;
            page-break-after: always;
        }
        .cover h1 { font-size: 48pt; margin-bottom: 10mm; }
        .cover h2 { font-size: 22pt; margin-bottom: 15mm; }
        .cover .name { font-size: 36pt; font-weight: bold; padding: 10mm 20mm; background: rgba(255,255,255,0.2); display: inline-block; border-radius: 10mm; margin: 10mm 0; }
        .cover .info { font-size: 16pt; margin-top: 10mm; }
        .cover .footer { font-size: 14pt; margin-top: 40mm; opacity: 0.6; }

        .page {
            page-break-after: always;
        }
        table.panels {
            width: 100%;
            border-collapse: collapse;
        }
        table.panels td {
            width: 50%;
            border: 3px solid #2D2D2D;
            padding: 0;
            vertical-align: top;
            background: #fff;
        }
        table.panels tr { page-break-inside: avoid; }
        .panel-img {
            width: 100%;
            display: block;
            background: #f0f0f0;
        }
        .no-img {
            width: 100%;
            height: 60mm;
            background: #FFEAA7;
            text-align: center;
            padding-top: 15mm;
            font-size: 36pt;
            color: #999;
        }
        .panel-text {
            padding: 3mm 4mm;
            font-size: 10pt;
            line-height: 1.4;
        }
        .panel-num {
            float: right;
            background: #FF6B35;
            color: #fff;
            width: 8mm;
            height: 8mm;
            border-radius: 4mm;
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            line-height: 8mm;
            margin: 2mm;
        }
        .empty-cell {
            background: #f5f5f5;
            text-align: center;
            color: #ccc;
            font-size: 36pt;
        }
    </style>
</head>
<body>
    <div class="cover">
        <h1>Jua Literária Juazeiro</h1>
        <h2>História em Quadrinhos</h2>
        <div class="name">{{ $aluno->nome }}</div>
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
        <div class="page">
            <table class="panels">
                <tr>
                    @for($c = 0; $c < 2; $c++)
                        @php $idx = $panelIndices[$c] ?? -1; @endphp
                        <td>
                            @if($idx >= 0)
                                <div class="panel-num">{{ $idx + 1 }}</div>
                                @if(isset($panelImages[$idx]) && $panelImages[$idx])
                                    <img src="{{ $panelImages[$idx] }}" class="panel-img">
                                @else
                                    <div class="no-img">&#127912;</div>
                                @endif
                                <div class="panel-text">{{ Str::limit($panelTexts[$idx] ?? '', 300) }}</div>
                            @else
                                <div class="empty-cell">&#10024;</div>
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    @for($c = 2; $c < 4; $c++)
                        @php $idx = $panelIndices[$c] ?? -1; @endphp
                        <td>
                            @if($idx >= 0)
                                <div class="panel-num">{{ $idx + 1 }}</div>
                                @if(isset($panelImages[$idx]) && $panelImages[$idx])
                                    <img src="{{ $panelImages[$idx] }}" class="panel-img">
                                @else
                                    <div class="no-img">&#127912;</div>
                                @endif
                                <div class="panel-text">{{ Str::limit($panelTexts[$idx] ?? '', 300) }}</div>
                            @else
                                <div class="empty-cell">&#10024;</div>
                            @endif
                        </td>
                    @endfor
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
