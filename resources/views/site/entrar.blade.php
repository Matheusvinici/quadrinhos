@extends('layouts.site')

@section('content')
    <a href="{{ route('site.welcome') }}" class="back-btn">← Voltar</a>

    <div class="text-center mb-4">
        <div style="font-size: 3rem;">👋</div>
        <h1 class="title" style="font-size: 2.8rem;">Qual é o seu nome?</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('site.login') }}">
            @csrf
            <div class="mb-4">
                <label>Seu nome</label>
                <input type="text" name="nome" class="input-giant" placeholder="Digite seu nome..."
                       required autofocus value="{{ old('nome') }}">
            </div>
            <div class="mb-4">
                <label>Sua escola</label>
                <input type="text" name="escola" class="input-giant" placeholder="Digite o nome da sua escola..."
                       required value="{{ old('escola') }}">
            </div>
            <button type="submit" class="btn-giant btn-orange" style="width: 100%; margin-top: 1rem;">
                ✨ Entrar
            </button>
        </form>
    </div>

    <p class="subtitle" style="margin-top: 1.5rem; font-size: 1.1rem; color: rgba(255,255,255,0.7);">
        Não precisa de senha! Só digite seu nome para começar.
    </p>
@endsection
