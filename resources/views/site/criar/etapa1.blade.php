@extends('layouts.site')

@section('content')
    <a href="{{ route('site.biblioteca') }}" class="back-btn">← Voltar</a>

    <div class="card card-wide">
        <div class="progress-bar">
            @for($i = 1; $i <= $totalEtapas; $i++)
                <div class="text-center">
                    <div class="step-dot {{ $i == $etapa ? 'active' : ($i < $etapa ? 'done' : '') }}">
                        {{ $i < $etapa ? '✓' : $i }}
                    </div>
                    <div class="step-dot-label">
                        {{ ['Quem é?', 'Onde vive?', 'Família', 'Sonhos'][$i-1] }}
                    </div>
                </div>
            @endfor
        </div>

        <h2 class="etapa-title">👤 {{ $tituloEtapa }}</h2>
        <p style="text-align: center; font-size: 1.1rem; color: #666; margin-bottom: 1.5rem;">
            Conte um pouco sobre você!
        </p>

        <form method="POST" action="{{ route('site.criar.salvar-etapa', ['etapa' => $etapa]) }}">
            @csrf
            @foreach($perguntas as $key => $pergunta)
                <div class="question-item">
                    <label>{{ $pergunta }}</label>
                    @php
                        $respostaAnterior = $respostas->where('pergunta', $pergunta)->first();
                    @endphp
                    <input type="text" name="{{ $key }}" class="input-giant"
                           value="{{ old($key, $respostaAnterior->resposta ?? '') }}"
                           placeholder="Digite aqui..." required>
                </div>
            @endforeach

            <div class="form-actions">
                <a href="{{ $etapa > 1 ? route('site.criar.etapa', ['etapa' => $etapa - 1]) : route('site.biblioteca') }}"
                   class="btn-giant btn-outline btn-sm" style="background: rgba(0,0,0,0.05); color: #666; border-color: #ccc; min-width: auto;">
                    ← Voltar
                </a>
                <button type="submit" class="btn-giant btn-orange btn-sm" style="min-width: auto;">
                    Avançar →
                </button>
            </div>
        </form>
    </div>
@endsection
