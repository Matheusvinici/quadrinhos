<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jua Literária Juazeiro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #0D5E8A 0%, #1A7CB8 30%, #2E9EE0 60%, #4FB3E8 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: #2D2D2D;
            -webkit-user-select: none;
            user-select: none;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .site-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        .logos-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 0.8rem 2rem;
            z-index: 100;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
        }
        .logos-bar img { height: 45px; width: auto; }
        .logos-bar .logo-placeholder {
            height: 45px;
            padding: 0 1rem;
            background: rgba(255,255,255,0.15);
            border: 2px dashed rgba(255,255,255,0.4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }
        .btn-giant {
            font-family: 'Nunito', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            padding: 1.2rem 3rem;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 320px;
            min-height: 90px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        .btn-giant:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        .btn-giant:active { transform: scale(0.98); }
        .btn-orange { background: #E8874A; color: #fff; }
        .btn-green { background: #3DA87C; color: #fff; }
        .btn-blue { background: #3B8FC2; color: #fff; }
        .btn-purple { background: #9B59B6; color: #fff; }
        .btn-yellow { background: #F1C40F; color: #2D2D2D; }
        .btn-white { background: #fff; color: #E8874A; }
        .btn-outline { background: transparent; color: #fff; border: 3px solid #fff; }
        .btn-sm {
            font-size: 1.3rem;
            padding: 0.8rem 2rem;
            min-width: 200px;
            min-height: 60px;
            border-radius: 20px;
        }
        .card {
            background: rgba(255,255,255,0.95);
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            max-width: 900px;
            width: 100%;
        }
        .card-wide { max-width: 1100px; }
        .title {
            font-size: 3.5rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 3px 3px 0 rgba(0,0,0,0.2);
            text-align: center;
            line-height: 1.2;
        }
        .title-lg { font-size: 4.5rem; }
        .subtitle {
            font-size: 1.5rem;
            color: rgba(255,255,255,0.9);
            text-align: center;
            font-weight: 600;
        }
        .input-giant {
            font-family: 'Nunito', sans-serif;
            font-size: 1.8rem;
            padding: 1rem 1.5rem;
            border: 3px solid #ddd;
            border-radius: 20px;
            width: 100%;
            outline: none;
            transition: border-color 0.3s;
        }
        .input-giant:focus { border-color: #E8874A; }
        label { font-size: 1.4rem; font-weight: 700; display: block; margin-bottom: 0.5rem; color: #2D2D2D; }
        .progress-bar {
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            margin-bottom: 2rem;
        }
        .step-dot {
            width: 50px; height: 50px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff;
            transition: all 0.3s;
        }
        .step-dot.active { background: #E8874A; transform: scale(1.1); }
        .step-dot.done { background: #2ECC71; }
        .step-dot-label {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
            text-align: center;
            margin-top: 0.3rem;
            font-weight: 600;
        }
        .etapa-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1A7CB8;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .question-item {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 1.2rem;
            margin-bottom: 1rem;
        }
        .question-item label { font-size: 1.2rem; }
        .question-item .input-giant { font-size: 1.3rem; padding: 0.8rem 1.2rem; }
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            gap: 1rem;
        }
        .floating-stars {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }
        .star {
            position: absolute;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        .content { position: relative; z-index: 1; width: 100%; display: flex; flex-direction: column; align-items: center; }
        .historia-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s;
            cursor: pointer;
        }
        .historia-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .qr-code-img { max-width: 300px; height: auto; }
        .back-btn {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            z-index: 100;
            text-decoration: none;
            font-family: 'Nunito', sans-serif;
            transition: all 0.3s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.3); }
        .bg-soft { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); }
        .text-white-important { color: #fff !important; }
        @media (max-width: 768px) {
            .title { font-size: 2.2rem; }
            .title-lg { font-size: 2.8rem; }
            .btn-giant { font-size: 1.4rem; min-width: 250px; min-height: 70px; padding: 1rem 2rem; }
            .card { padding: 1.5rem; margin: 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="logos-bar">
        <img src="{{ asset('images/logo-prefeitura.png') }}" alt="Prefeitura de Juazeiro">
        <div class="logo-placeholder">Juá Literária</div>
        <div class="logo-placeholder">Educa Juá</div>
    </div>
    <div class="floating-stars" id="stars"></div>
    <div class="site-container">
        <div class="content">
            @yield('content')
        </div>
    </div>
    <script>
        // Floating stars background
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 30; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            const size = Math.random() * 8 + 4;
            star.style.width = size + 'px';
            star.style.height = size + 'px';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.animationDelay = Math.random() * 6 + 's';
            star.style.animationDuration = (Math.random() * 4 + 4) + 's';
            starsContainer.appendChild(star);
        }
    </script>
    @stack('scripts')
</body>
</html>
