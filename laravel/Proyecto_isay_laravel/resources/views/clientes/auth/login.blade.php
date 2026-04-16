<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Maccuin Autopartes</title>
    <meta name="description" content="Inicia sesión en Maccuin Autopartes para explorar nuestro catálogo de refacciones">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --naranja: #E8671B;
            --naranja-hover: #c95510;
            --naranja-glow: rgba(232, 103, 27, 0.25);
            --dark-bg: #0f172a;
            --card-glass: rgba(255, 255, 255, 0.95);
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: #f8fafc;
        }

        
        .panel-imagen {
            flex: 1.2;
            background: linear-gradient(160deg, rgba(15, 23, 42, 0.95), rgba(232, 103, 27, 0.4)),
                        url('https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=900') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-end;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .panel-imagen::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(232, 103, 27, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .panel-imagen > * { position: relative; z-index: 2; }

        .brand-top {
            position: absolute;
            top: 3rem;
            left: 3rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }
        .brand-top i { 
            background: var(--naranja);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px var(--naranja-glow);
        }

        .panel-imagen h2 {
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            max-width: 500px;
        }
        .panel-imagen h2 span { color: var(--naranja); }
        
        .panel-imagen .stats-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1.5rem;
            border-radius: 100px;
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        
        .panel-form {
            flex: 0.8;
            min-width: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 4rem;
            position: relative;
        }

        .panel-form .inner {
            width: 100%;
            max-width: 400px;
        }

        .welcome-text { margin-bottom: 2.5rem; }
        .welcome-text h3 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            color: #0f172a;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }
        .welcome-text p {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control-custom {
            width: 100%;
            background: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 1rem 1.25rem 1rem 3.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control-custom:focus {
            background: white;
            border-color: var(--naranja);
            box-shadow: 0 0 0 4px var(--naranja-glow);
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            z-index: 5;
        }

        .input-group-custom:focus-within .input-icon {
            color: var(--naranja);
        }

        
        .btn-submit {
            background: var(--naranja);
            color: white;
            border: none;
            width: 100%;
            padding: 1.1rem;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px var(--naranja-glow);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: var(--naranja-hover);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px var(--naranja-glow);
            color: white;
        }

        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            margin-top: 2.5rem;
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
        }

        .auth-footer a {
            color: var(--naranja);
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .auth-footer a:hover {
            color: var(--naranja-hover);
            text-decoration: underline;
        }

        
        .alert-custom {
            padding: 1rem 1.25rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #d1fae5;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        
        @media (max-width: 1024px) {
            .panel-imagen { display: none; }
            .panel-form { flex: 1; min-width: auto; padding: 2.5rem; }
        }

    </style>
</head>
<body>

<div class="panel-imagen">
    <div class="brand-top">
        <i class="bi bi-car-front-fill"></i>
        Maccuin
    </div>
    <h2>Potencia tu <span>Camino.</span><br>Autenticidad Garantizada.</h2>
    <div class="stats-pill">
        <i class="bi bi-star-fill text-warning"></i>
        <span>Más de 50k Refacciones Disponibles</span>
    </div>
</div>

<div class="panel-form">
    <div class="inner">
        <div class="welcome-text">
            <h3>Bienvenido</h3>
            <p>Ingresa tus credenciales para acceder a tu taller personalizado.</p>
        </div>

        @if(session('success'))
            <div class="alert-custom alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-custom alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('cliente.login.post') }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label class="form-label">Correo Electrónico</label>
                <div class="input-group-custom">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control-custom" 
                           placeholder="ejemplo@maccuin.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Contraseña</label>
                    <a href="{{ route('cliente.recuperar') }}" class="text-muted" style="font-size: 0.85rem; font-weight: 500;">¿La olvidaste?</a>
                </div>
                <div class="input-group-custom">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control-custom" 
                           placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" style="cursor: pointer;">
                <label class="form-check-label" for="remember" style="font-size: 0.9rem; color: #64748b; font-weight: 500; cursor: pointer;">
                    Mantener sesión iniciada
                </label>
            </div>

            <button type="submit" class="btn btn-submit">
                Entrar al Taller
                <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="auth-footer">
            ¿No tienes una cuenta? <a href="{{ route('cliente.registro') }}">Regístrate ahora</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
