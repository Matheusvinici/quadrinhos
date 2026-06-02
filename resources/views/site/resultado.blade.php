@extends('layouts.site')

@section('content')
    <a href="{{ route('site.biblioteca') }}" class="back-btn">← Minhas Histórias</a>

    <div class="text-center mb-3">
        <div style="font-size: 3rem;">🎉</div>
        <h1 class="title" style="font-size: 2.2rem;">Sua HQ está pronta!</h1>
        <p class="subtitle" style="font-size: 1.1rem; color: rgba(255,255,255,0.8);">
            História de {{ $historia->aluno->nome }}
        </p>
    </div>

    <div class="card" style="max-width: 700px;">
        <div class="text-center">
            @if($historia->qr_code_path)
                <div style="background: #fff; border-radius: 20px; padding: 1.5rem; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <img src="{{ Storage::url($historia->qr_code_path) }}"
                         alt="QR Code da HQ"
                         style="width: 280px; height: 280px; image-rendering: pixelated;">
                </div>
                <p style="font-size: 1.1rem; color: #666; margin-top: 0.8rem;">
                    📱 Aponte a câmera do celular para ver sua HQ!
                </p>
            @endif

            <div class="d-flex flex-column align-items-center gap-3 mt-4">
                <a href="{{ route('site.criar.imprimir', ['slug' => $historia->slug]) }}"
                   target="_blank"
                   class="btn-giant btn-green" style="min-width: 350px;">
                    📖 Ver e Imprimir HQ
                </a>

                @if($isFallback ?? false)
                    <form action="{{ route('site.criar.regenerar', ['slug' => $historia->slug]) }}"
                          method="POST" style="width: 100%;">
                        @csrf
                        <button type="submit"
                                class="btn-giant btn-blue" style="min-width: 350px;">
                            🤖 Gerar HQ com IA
                        </button>
                    </form>
                @endif

                <a href="{{ route('site.criar.iniciar') }}"
                   class="btn-giant btn-orange btn-sm" style="min-width: 250px;">
                    ✨ Criar Nova História
                </a>
            </div>
        </div>
    </div>

    <div style="margin-top: 1rem; color: rgba(255,255,255,0.4); font-size: 0.85rem; text-align: center; max-width: 500px;">
        A história foi criada por inteligência artificial com as informações que você forneceu.
    </div>
@endsection
