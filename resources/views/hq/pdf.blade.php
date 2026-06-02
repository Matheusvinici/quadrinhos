<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>HQ - {{ $aluno->nome }}</title>
    <style>
        @page { margin: 15px; }
        body {
            font-family: 'Nunito', 'DejaVu Sans', sans-serif;
            background: #FFF8E7;
            font-size: 12px;
            line-height: 1.4;
        }
        .cover-page {
            width: 100%;
            text-align: center;
            padding: 60px 30px;
            background-color: #FF6B35;
            color: #fff;
            page-break-after: always;
        }
        .cover-title {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 10px;
        }
        .cover-subtitle {
            font-size: 22px;
            opacity: 0.9;
        }
        .cover-student {
            font-size: 32px;
            font-weight: 700;
            margin: 30px 0;
            padding: 15px 40px;
            background: rgba(255,255,255,0.2);
            display: inline-block;
            border-radius: 15px;
        }
        .cover-info {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 20px;
        }
        .comic-table {
            width: 100%;
            border-collapse: collapse;
            page-break-after: always;
        }
        .comic-table td {
            width: 50%;
            height: 220px;
            border: 3px solid #2D2D2D;
            padding: 0;
            vertical-align: top;
            position: relative;
        }
        .panel-wrapper {
            width: 100%;
            height: 100%;
        }
        .panel-img {
            width: 100%;
            height: 140px;
            display: block;
        }
        .panel-text-box {
            padding: 6px 8px;
            height: 80px;
            overflow: hidden;
            font-size: 11px;
            background: #fff;
        }
        .panel-number-badge {
            position: absolute;
            top: 3px;
            right: 5px;
            background: #FF6B35;
            color: #fff;
            width: 24px;
            height: 24px;
            border-radius: 12px;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
            font-weight: 900;
        }
        .panel-empty {
            background: #f5f5f5;
            text-align: center;
            color: #ccc;
            font-size: 24px;
            padding-top: 80px;
        }
        .no-image-box {
            width: 100%;
            height: 140px;
            background: #FFEAA7;
            text-align: center;
            line-height: 140px;
            font-size: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="cover-page">
        <div class="cover-title">Jua Literária Juazeiro</div>
        <div class="cover-subtitle">História em Quadrinhos</div>
        <div class="cover-student">{{ $aluno->nome }}</div>
        <div class="cover-info">Turma: {{ $aluno->serie }}</div>
        <div style="margin-top: 50px; font-size: 14px; opacity: 0.6;">
            Uma história criada especialmente para você!
        </div>
    </div>

    @php
        $totalPanels = count($panelTexts);
        $chunks = array_chunk(range(0, max($totalPanels - 1, 0)), 4);
    @endphp

    @foreach($chunks as $panelIndices)
        <table class="comic-table">
            <tr>
                @for($i = 0; $i < 2; $i++)
                    @php $idx = $panelIndices[$i] ?? null; @endphp
                    <td>
                        @if($idx !== null)
                            <div class="panel-wrapper">
                                <div class="panel-number-badge">{{ $idx + 1 }}</div>
                                @if(isset($panelImages[$idx]))
                                    <img src="{{ $panelImages[$idx] }}" class="panel-img" alt="">
                                @else
                                    <div class="no-image-box">&#127912;</div>
                                @endif
                                <div class="panel-text-box">
                                    {{ Str::limit($panelTexts[$idx] ?? '', 250) }}
                                </div>
                            </div>
                        @else
                            <div class="panel-empty">&#10024;</div>
                        @endif
                    </td>
                @endfor
            </tr>
            <tr>
                @for($i = 2; $i < 4; $i++)
                    @php $idx = $panelIndices[$i] ?? null; @endphp
                    <td>
                        @if($idx !== null)
                            <div class="panel-wrapper">
                                <div class="panel-number-badge">{{ $idx + 1 }}</div>
                                @if(isset($panelImages[$idx]))
                                    <img src="{{ $panelImages[$idx] }}" class="panel-img" alt="">
                                @else
                                    <div class="no-image-box">&#127912;</div>
                                @endif
                                <div class="panel-text-box">
                                    {{ Str::limit($panelTexts[$idx] ?? '', 250) }}
                                </div>
                            </div>
                        @else
                            <div class="panel-empty">&#10024;</div>
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
    @endforeach
</body>
</html>
