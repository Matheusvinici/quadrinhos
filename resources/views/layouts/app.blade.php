@php use App\Models\Configuracao; @endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Configuracao::get('nome_barbearia', 'Barbearia') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @livewireStyles
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }

        .sidebar-custom { background-color: #ffffff !important; border-right: 1px solid #e9ecef; }
        .sidebar-custom .nav-link { color: #495057 !important; border-radius: 6px; margin: 1px 10px; transition: all 0.2s; font-weight: 400; }
        .sidebar-custom .nav-link i { color: #868e96 !important; font-size: 1.1rem; width: 22px; }
        .sidebar-custom .nav-link p { font-size: 0.875rem; }
        .sidebar-custom .nav-item:hover > .nav-link { background-color: #f1f3f5; }
        .sidebar-custom .nav-link.active { background-color: #e9ecef !important; color: #212529 !important; font-weight: 500; }
        .sidebar-custom .nav-link.active i { color: #495057 !important; }
        .sidebar-custom .nav-treeview .nav-link { padding-left: 2.5rem; }
        .sidebar-custom .nav-treeview .nav-link i { font-size: 0.5rem; width: 16px; }

        .brand-area { background-color: #ffffff; padding: 18px 0; border-bottom: 1px solid #e9ecef; }
        .brand-text { font-size: 1.15rem; font-weight: 600; color: #212529 !important; text-align: center; letter-spacing: -0.3px; }
        .brand-link { padding: 0; background: transparent !important; }

        .main-header { background-color: #ffffff !important; border-bottom: 1px solid #e9ecef; box-shadow: none; }
        .main-header .nav-link { color: #495057 !important; }
        .main-header .user-name { color: #212529; font-weight: 500; font-size: 0.875rem; }
        .btn-logout { color: #868e96 !important; }
        .btn-logout:hover { color: #495057 !important; }

        .content-wrapper { background-color: #f8f9fa; }
        .content-header h1 { font-size: 1.5rem; font-weight: 600; color: #212529; letter-spacing: -0.3px; }
        .content-header .breadcrumb { font-size: 0.8rem; }
        .content-header .breadcrumb a { color: #868e96; }

        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06); transition: box-shadow 0.2s; }
        .card:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.04), 0 2px 4px rgba(0,0,0,0.06); }
        .card-header { background-color: #fff; border-bottom: 1px solid #f1f3f5; padding: 1rem 1.25rem; }
        .card-header .card-title { font-size: 1rem; font-weight: 600; color: #212529; }
        .card-body { padding: 1.25rem; }

        .table { font-size: 0.875rem; }
        .table thead th { border-top: none; border-bottom: 2px solid #e9ecef; font-weight: 600; color: #495057; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table tbody td { vertical-align: middle; border-bottom: 1px solid #f1f3f5; }

        .btn { border-radius: 8px; font-weight: 500; font-size: 0.875rem; padding: 0.5rem 1rem; transition: all 0.2s; }
        .btn-primary { background-color: #212529; border-color: #212529; }
        .btn-primary:hover { background-color: #343a40; border-color: #343a40; }
        .btn-outline-primary { color: #495057; border-color: #dee2e6; }
        .btn-outline-primary:hover { background-color: #f8f9fa; border-color: #ced4da; color: #212529; }
        .btn-sm { padding: 0.3rem 0.75rem; font-size: 0.8rem; }
        .btn-block { display: block; width: 100%; }

        .small-box { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: box-shadow 0.2s; }
        .small-box:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.04); }
        .small-box > .inner { padding: 1.5rem; }
        .small-box h3 { font-weight: 700; font-size: 1.75rem; margin: 0 0 0.25rem; letter-spacing: -0.5px; }
        .small-box p { font-size: 0.8rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; }
        .small-box .icon { display: none; }
        .small-box-footer { display: block; padding: 0.6rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px; transition: all 0.2s; }
        .small-box.bg-info { background-color: #e3f2fd !important; color: #1565c0 !important; }
        .small-box.bg-warning { background-color: #fff8e1 !important; color: #f57f17 !important; }
        .small-box.bg-success { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
        .small-box.bg-danger { background-color: #fce4ec !important; color: #c62828 !important; }
        .small-box.bg-info .small-box-footer { background-color: rgba(21,101,192,0.08); color: #1565c0; }
        .small-box.bg-warning .small-box-footer { background-color: rgba(245,127,23,0.08); color: #f57f17; }
        .small-box.bg-success .small-box-footer { background-color: rgba(46,125,50,0.08); color: #2e7d32; }
        .small-box.bg-danger .small-box-footer { background-color: rgba(198,40,40,0.08); color: #c62828; }
        .small-box.bg-info .small-box-footer:hover { background-color: rgba(21,101,192,0.15); }
        .small-box.bg-warning .small-box-footer:hover { background-color: rgba(245,127,23,0.15); }
        .small-box.bg-success .small-box-footer:hover { background-color: rgba(46,125,50,0.15); }
        .small-box.bg-danger .small-box-footer:hover { background-color: rgba(198,40,40,0.15); }

        .badge { font-weight: 500; font-size: 0.75rem; padding: 0.35em 0.65em; border-radius: 6px; }
        .badge.bg-info { background-color: #e3f2fd !important; color: #1565c0 !important; }
        .badge.bg-success { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
        .badge.bg-warning { background-color: #fff8e1 !important; color: #f57f17 !important; }
        .badge.bg-primary { background-color: #e8eaf6 !important; color: #283593 !important; }
        .badge.bg-secondary { background-color: #f1f3f5 !important; color: #495057 !important; }
        .badge.bg-danger { background-color: #fce4ec !important; color: #c62828 !important; }

        .status-pendente { background-color: #fff8e1; color: #f57f17; }
        .status-confirmado { background-color: #e3f2fd; color: #1565c0; }
        .status-realizado { background-color: #e8f5e9; color: #2e7d32; }
        .status-cancelado { background-color: #fce4ec; color: #c62828; }
        .status-ausente { background-color: #f1f3f5; color: #495057; }
        .badge-status { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }

        .notification-badge { position: absolute; top: 0; right: 0; font-size: 0.6rem; }
        .notifications-dropdown { width: 360px; max-height: 420px; overflow-y: auto; border: none; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .notifications-dropdown .dropdown-item { white-space: normal; border-bottom: 1px solid #f1f3f5; padding: 12px 16px; }
        .notifications-dropdown .dropdown-item:hover { background-color: #f8f9fa; }
        .notifications-dropdown .dropdown-item.unread { background-color: #f1f3f5; }
        .text-mark-all { color: #868e96; }
        .text-mark-all:hover { color: #495057; }

        .main-footer { background-color: #fff; border-top: 1px solid #e9ecef; color: #868e96; font-size: 0.8rem; padding: 1rem 1.5rem; }
        .main-footer strong { font-weight: 500; }

        .form-control { border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.875rem; padding: 0.5rem 0.75rem; transition: border-color 0.2s; }
        .form-control:focus { border-color: #212529; box-shadow: 0 0 0 2px rgba(33,37,41,0.08); }
        .form-label { font-size: 0.8rem; font-weight: 600; color: #495057; margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.3px; }
        select.form-control { cursor: pointer; }

        .pagination .page-link { border: none; color: #495057; border-radius: 6px; margin: 0 2px; font-size: 0.8rem; }
        .pagination .page-item.active .page-link { background-color: #212529; color: #fff; }
        .pagination .page-item.disabled .page-link { color: #ced4da; }

        a { color: #495057; text-decoration: none; transition: color 0.2s; }
        a:hover { color: #212529; }

        hr { border-color: #e9ecef; }

        h1, h2, h3, h4, h5, h6 { letter-spacing: -0.3px; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" role="button">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger rounded-pill notification-badge" id="notif-count">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end notifications-dropdown" id="notif-dropdown">
                    <div class="text-center py-2 text-muted small">Nenhuma notificação</div>
                </div>
            </li>
            @endauth

            <li class="nav-item">
                <span class="nav-link user-name">
                    <i class="fas fa-user-circle me-2"></i>
                    @if (Auth::guard('web')->check())
                        {{ Auth::guard('web')->user()->name }}
                    @elseif (Auth::guard('barbeiro')->check())
                        {{ Auth::guard('barbeiro')->user()->nome }}
                    @endif
                </span>
            </li>

            <li class="nav-item">
                <form method="POST" action="{{ Auth::guard('web')->check() ? route('logout') : route('barbeiro.logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="nav-link btn btn-logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Sair
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar elevation-4 sidebar-custom">
        <div class="brand-area">
            <a href="{{ Auth::guard('web')->check() ? route('admin.dashboard') : route('barbeiro.dashboard') }}" class="brand-link text-center">
                <span class="brand-text">{{ Configuracao::get('nome_barbearia', 'Barbearia') }}</span>
            </a>
        </div>
        <div class="sidebar">
            @if (Auth::guard('web')->check())
                @include('layouts.navigation-admin')
            @elseif (Auth::guard('barbeiro')->check())
                @include('layouts.navigation-barbeiro')
            @endif
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">@yield('breadcrumb', '')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @include('layouts.partials.messages')
                @yield('content')
                {{ $slot ?? '' }}
                @livewireScripts
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-end d-none d-sm-inline">{{ Configuracao::get('nome_barbearia', 'Barbearia') }}</div>
        <strong>&copy; {{ date('Y') }} Todos os direitos reservados.</strong>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    function carregarNotificacoes() {
        @auth
        $.get('/notificacoes', function(data) {
            $('#notif-count').text(data.nao_lidas);
            let html = '';
            if (data.notificacoes.length === 0) {
                html = '<div class="text-center py-2 text-muted small">Nenhuma notificação</div>';
            } else {
                data.notificacoes.forEach(function(n) {
                    const unreadClass = n.lida ? '' : 'unread';
                    html += `<a href="${n.url || '#'}" class="dropdown-item ${unreadClass}">
                        <div class="d-flex">
                            <div class="me-3"><i class="${n.icon || 'fas fa-info-circle'}" style="color: ${n.color || '#6c757d'}"></i></div>
                            <div>
                                <strong>${n.title}</strong><br>
                                <small>${n.message}</small><br>
                                <small class="text-muted">${n.ago}</small>
                            </div>
                        </div>
                    </a>`;
                });
                html += '<div class="text-center py-2"><a href="/notificacoes/marcar-todas" class="small text-mark-all">Marcar todas como lidas</a></div>';
            }
            $('#notif-dropdown').html(html);
        });
        @endauth
    }

    carregarNotificacoes();
    setInterval(carregarNotificacoes, 30000);

    $(document).on('click', '.text-mar-all', function(e) {
        e.preventDefault();
        $.post('/notificacoes/marcar-todas', { _token: '{{ csrf_token() }}' }, function() {
            carregarNotificacoes();
        });
    });
});
</script>
@stack('scripts')
</body>
</html>
