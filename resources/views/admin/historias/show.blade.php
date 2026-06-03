@extends('admin.layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800;">📖 História #{{ $historia->id }}</h2>
        <a href="{{ route('admin.historias.index') }}" class="btn btn-orange">← Lista</a>
    </div>

    <div class="card">
        <h3 style="font-weight: 800; color: #FF6B35;">👤 Aluno</h3>
        <p><strong>Nome:</strong> {{ $historia->aluno->nome }}</p>
        <p><strong>Escola:</strong> {{ $historia->aluno->escola }}</p>
        <p><strong>Criada em:</strong> {{ $historia->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Status:</strong> {{ $historia->status == 'concluido' ? '✅ Concluída' : '🔄 Rascunho' }}</p>
    </div>

    @foreach($respostasAgrupadas as $etapaNum => $respostas)
        <div class="card">
            <h3 style="font-weight: 800; color: #FF6B35; margin-bottom: 0.5rem;">
                {{ $etapas[$etapaNum] ?? "Etapa $etapaNum" }}
            </h3>
            @foreach($respostas as $resposta)
                <div style="margin-bottom: 0.5rem; padding: 0.5rem; background: #f8f9fa; border-radius: 8px;">
                    <strong style="color: #555;">{{ $resposta->pergunta }}</strong>
                    <p style="margin: 0.2rem 0 0 0;">{{ $resposta->resposta }}</p>
                </div>
            @endforeach
        </div>
    @endforeach

    @if($historia->prompt_gerado)
        <div class="card">
            <h3 style="font-weight: 800; color: #FF6B35;">🤖 Prompt Gerado</h3>
            <pre style="background: #f0f0f0; padding: 1rem; border-radius: 10px; font-size: 0.85rem; white-space: pre-wrap; max-height: 300px; overflow-y: auto;">{{ $historia->prompt_gerado }}</pre>
        </div>
    @endif

    @if($historia->resposta_gemini)
        <div class="card">
            <h3 style="font-weight: 800; color: #FF6B35;">🤖 Resposta da IA</h3>
            <pre style="background: #f0f0f0; padding: 1rem; border-radius: 10px; font-size: 0.85rem; white-space: pre-wrap; max-height: 400px; overflow-y: auto;">{{ json_encode($historia->resposta_gemini, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif

    <div class="d-flex gap-2">
        @if($historia->pdf_path)
            <a href="{{ route('admin.historias.download-pdf', $historia->id) }}" class="btn btn-green">📥 Baixar PDF</a>
        @endif
        <form method="POST" action="{{ route('admin.historias.destroy', $historia->id) }}"
              onsubmit="return confirm('Excluir esta história permanentemente?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-red">🗑 Excluir</button>
        </form>
    </div>
@endsection
