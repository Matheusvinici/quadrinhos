<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login') - Barbearia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04); width: 100%; max-width: 400px; overflow: hidden; }
        .login-header { background: #ffffff; padding: 2rem 2rem 1rem; text-align: center; border-bottom: 1px solid #f1f3f5; }
        .login-header h4 { margin: 0; font-weight: 600; color: #212529; letter-spacing: -0.3px; }
        .login-header p { margin: 4px 0 0; color: #868e96; font-size: 0.85rem; }
        .login-header i { color: #212529; }
        .btn-login { background-color: #212529; border: none; border-radius: 8px; padding: 12px; font-weight: 600; font-size: 0.875rem; }
        .btn-login:hover { background-color: #343a40; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-cut mb-3" style="font-size: 3rem;"></i>
            <h4>Barbearia</h4>
            <p>Administração</p>
        </div>
        <div class="card-body p-4 bg-white" style="border-radius: 0 0 15px 15px;">
            @yield('content')
        </div>
        <div class="card-footer text-center py-3 bg-white" style="border-radius: 0 0 15px 15px; border-top: none;">
            <a href="{{ route('barbeiro.login') }}" class="text-muted small"><i class="fas fa-user-tie"></i> Área do Barbeiro</a>
        </div>
    </div>
</body>
</html>
