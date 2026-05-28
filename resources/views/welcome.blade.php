@php use App\Models\Configuracao; @endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Configuracao::get('nome_barbearia', 'Barbearia') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center py-5">
        <h1>{{ Configuracao::get('nome_barbearia', 'Barbearia') }}</h1>
        <p class="lead">Sistema de gerenciamento</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Acessar</a>
        <a href="{{ route('barbeiro.login') }}" class="btn btn-secondary">Área do Barbeiro</a>
    </div>
</body>
</html>
