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

        <h2 class="etapa-title">👨‍👩‍👧‍👦 {{ $tituloEtapa }}</h2>
        <p style="text-align: center; font-size: 1.1rem; color: #666; margin-bottom: 1.5rem;">
            Conte sobre sua família!
        </p>

        <form method="POST" action="{{ route('site.criar.salvar-etapa', ['etapa' => $etapa]) }}">
            @csrf
            @foreach($perguntas as $key => $pergunta)
                <div class="question-item">
                    <label>{{ $pergunta }}</label>
                    @php
                        $respostaAnterior = $respostas->where('pergunta', $pergunta)->first();
                        $valorAnterior = old($key, $respostaAnterior->resposta ?? '');
                    @endphp
                    <div class="options-grid">
                        @foreach($alternativas[$key] as $opcao)
                            <button type="button"
                                    class="option-btn {{ $valorAnterior === $opcao ? 'selected' : '' }}"
                                    data-key="{{ $key }}"
                                    data-value="{{ $opcao }}">
                                {{ $opcao }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="{{ $key }}" id="input-{{ $key }}" value="{{ $valorAnterior }}">
                </div>
            @endforeach

            <div class="form-actions">
                <a href="{{ route('site.criar.etapa', ['etapa' => $etapa - 1]) }}"
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

@push('scripts')
<script>
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const key = this.dataset.key;
            document.querySelectorAll(`.option-btn[data-key="${key}"]`).forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('input-' + key).value = this.dataset.value;
        });
    });
</script>
<style>
    .options-grid { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 0.5rem; }
    .option-btn {
        font-family: 'Nunito', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        padding: 0.8rem 1.8rem;
        border: 3px solid #ddd;
        border-radius: 16px;
        background: #fff;
        color: #555;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 100px;
        text-align: center;
    }
    .option-btn:hover { border-color: #E8874A; color: #E8874A; transform: scale(1.03); }
    .option-btn.selected { border-color: #E8874A; background: #E8874A; color: #fff; }
</style>
@endpush
