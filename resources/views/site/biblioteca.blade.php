@extends('layouts.site')

@section('content')
    <a href="{{ route('site.welcome') }}" class="back-btn">← Início</a>

    <div class="text-center mb-4">
        <h1 class="title" style="font-size: 2.5rem;">📖 Olá, {{ session('aluno_nome') }}!</h1>
        <p class="subtitle" style="font-size: 1.2rem;">Suas histórias</p>
    </div>

    <div class="card card-wide" style="max-height: 70vh; overflow-y: auto;">
        <div class="text-center mb-4">
            <a href="{{ route('site.criar.iniciar') }}" class="btn-giant btn-orange btn-sm">
                ✨ Criar Nova História
            </a>
        </div>

        @if($historiasEmAndamento->isNotEmpty())
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #E67E22; margin-bottom: 1rem;">🔄 Em andamento</h2>
            <div class="row g-3 mb-4">
                @foreach($historiasEmAndamento as $historia)
                    <div class="col-md-6">
                        <div class="historia-card border">
                            <div style="font-size: 1.1rem; font-weight: 700;">História #{{ $historia->id }}</div>
                            <div style="color: #888; font-size: 0.9rem;">Criada em {{ $historia->created_at->format('d/m/Y H:i') }}</div>
                            <a href="{{ route('site.criar.etapa', ['etapa' => 1]) }}?retomar={{ $historia->id }}" class="btn-giant btn-yellow btn-sm mt-2" style="min-width: auto; font-size: 1rem; min-height: auto; padding: 0.5rem 1.5rem;">
                                ▶ Continuar
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($historiasConcluidas->isNotEmpty())
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #27AE60; margin-bottom: 1rem;">✅ Concluídas</h2>
            <div class="row g-3">
                @foreach($historiasConcluidas as $historia)
                    <div class="col-md-6">
                        <div class="historia-card border">
                            <div style="font-size: 1.1rem; font-weight: 700;">História #{{ $historia->id }}</div>
                            <div style="color: #888; font-size: 0.9rem;">{{ $historia->created_at->format('d/m/Y') }}</div>
                            <div class="mt-2 d-flex gap-2">
                                <a href="{{ route('site.criar.resultado', ['slug' => $historia->slug]) }}" class="btn-giant btn-blue btn-sm" style="min-width: auto; font-size: 1rem; min-height: auto; padding: 0.5rem 1.5rem;">
                                    👁 Ver
                                </a>
                                <a href="{{ route('site.criar.imprimir', ['slug' => $historia->slug]) }}" target="_blank" class="btn-giant btn-green btn-sm" style="min-width: auto; font-size: 1rem; min-height: auto; padding: 0.5rem 1.5rem;">
                                    📥 PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($historiasEmAndamento->isEmpty() && $historiasConcluidas->isEmpty())
            <div class="text-center py-5">
                <div style="font-size: 4rem;">🎨</div>
                <p style="font-size: 1.3rem; color: #888;">Você ainda não tem histórias!</p>
                <p style="color: #aaa;">Clique em "Criar Nova História" para começar.</p>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('site.sair') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn-giant btn-outline btn-sm" style="min-width: auto; font-size: 1rem; min-height: auto; padding: 0.5rem 2rem;">
            🚪 Sair
        </button>
    </form>
@endsection
