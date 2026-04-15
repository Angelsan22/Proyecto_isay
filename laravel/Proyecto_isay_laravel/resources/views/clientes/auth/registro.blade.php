<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — Maccuin Autopartes</title>
    <meta name="description" content="Regístrate en Maccuin Autopartes para acceder al catálogo de refacciones">

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
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: #f8fafc;
        }

        /* ── Panel Imagen (izquierda) ──────────── */
        .panel-imagen {
            flex: 1.2;
            background: linear-gradient(160deg, rgba(15, 23, 42, 0.95), rgba(232, 103, 27, 0.4)),
                        url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=900') center/cover no-repeat;
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

        /* ── Panel Formulario (derecha) ─────────── */
        .panel-form {
            flex: 0.8;
            min-width: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 4rem;
        }

        .panel-form .inner {
            width: 100%;
            max-width: 440px;
        }

        .welcome-text { margin-bottom: 2rem; }
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

        /* ── Form Controls ── */
        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .form-control-custom {
            width: 100%;
            background: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 0.85rem 1.25rem;
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            background: white;
            border-color: var(--naranja);
            box-shadow: 0 0 0 4px var(--naranja-glow);
            outline: none;
        }

        /* ── Buttons ── */
        .btn-submit {
            background: var(--naranja);
            color: white;
            border: none;
            width: 100%;
            padding: 1.1rem;
            border-radius: 16px;
            font-size: 1.1rem;
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
        }

        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
        }

        .auth-footer a {
            color: var(--naranja);
            font-weight: 700;
            text-decoration: none;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid #fee2e2;
        }

        @media (max-width: 1100px) {
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
    <h2>Únete a la <span>Pasión.</span><br>Calidad en cada Km.</h2>
</div>

<div class="panel-form">
    <div class="inner">
        <div class="welcome-text">
            <h3>Crear Cuenta</h3>
            <p>Regístrate para gestionar tus piezas y pedidos.</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cliente.registro.post') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control-custom" 
                           placeholder="Tu nombre" value="{{ old('nombre') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control-custom" 
                           placeholder="Tus apellidos" value="{{ old('apellidos') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control-custom" 
                       placeholder="tu@correo.com" value="{{ old('email') }}" required>
            </div>

            <div class="row g-3">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control-custom" 
                           placeholder="••••••••" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label">Confirmar</label>
                    <input type="password" name="password_confirmation" class="form-control-custom" 
                           placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-submit">
                Registrarme Ahora
                <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="auth-footer">
            ¿Ya tienes cuenta? <a href="{{ route('cliente.login') }}">Inicia Sesión</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
