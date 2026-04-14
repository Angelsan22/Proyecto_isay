<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Maccuin Autopartes</title>
    <meta name="description" content="Inicia sesión en Maccuin Autopartes para explorar nuestro catálogo de refacciones">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --naranja: #E8671B;
            --naranja-hover: #c95510;
            --naranja-glow: rgba(232, 103, 27, 0.25);
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Panel Imagen (izquierda) ──────────── */
        .panel-imagen {
            flex: 1;
            background: linear-gradient(160deg, rgba(17, 17, 17, 0.85), rgba(232, 103, 27, 0.25)),
                        url('https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=900') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-end;
            padding: 3.5rem;
            position: relative;
        }
        .panel-imagen::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%);
        }
        .panel-imagen > * { position: relative; z-index: 1; }

        .panel-imagen .brand-top {
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 2;
            font-weight: 900;
            font-size: 1.3rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .panel-imagen .brand-top i { color: var(--naranja); font-size: 1.5rem; }

        .panel-imagen h2 {
            color: #fff;
            font-size: 2.6rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -1px;
        }
        .panel-imagen h2 span { color: var(--naranja); }
        .panel-imagen .tagline {
            color: rgba(255, 255, 255, 0.55);
            font-size: 1rem;
            font-weight: 500;
            margin-top: 12px;
        }

        /* ── Panel Formulario (derecha) ─────────── */
        .panel-form {
            width: 500px;
            min-width: 380px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 3rem 2.5rem;
        }
        .panel-form .inner {
            width: 100%;
            max-width: 380px;
        }

        .panel-form .logo-mini {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 0.9rem;
            color: var(--naranja);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .panel-form h3 {
            font-weight: 900;
            font-size: 1.8rem;
            color: #111;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .panel-form .subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        /* ── Form Controls ─────────────────────── */
        .form-control {
            border-radius: 14px;
            padding: 0.85rem 1rem;
            border: 2px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            transition: 0.3s;
            color: #1e293b;
        }
        .form-control:focus {
            border-color: var(--naranja);
            box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.1);
            outline: none;
        }
        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-group-text {
            background: #fff;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 14px 0 0 14px;
            color: #94a3b8;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 14px 14px 0;
        }
        .input-group:focus-within .input-group-text {
            border-color: var(--naranja);
        }

        .form-check-input:checked {
            background-color: var(--naranja);
            border-color: var(--naranja);
        }

        /* ── Buttons ───────────────────────────── */
        .btn-naranja {
            background: var(--naranja);
            color: #fff;
            border: none;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 800;
            border-radius: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px var(--naranja-glow);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-naranja:hover {
            background: var(--naranja-hover);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px var(--naranja-glow);
        }

        a { color: var(--naranja); text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }

        /* ── Alerts ─────────────────────────────── */
        .alert {
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
        }

        /* ── Responsive ────────────────────────── */
        @media (max-width: 768px) {
            .panel-imagen { display: none; }
            .panel-form { width: 100%; min-width: auto; }
        }

        /* ── Entrance Animation ────────────────── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .panel-form .inner {
            animation: slideUp 0.5s ease forwards;
        }
    </style>
</head>
<body>

<div class="panel-imagen">
    <div class="brand-top">
        <i class="bi bi-car-front-fill"></i> Maccuin
    </div>
    <h2>Pasión por el <span>Motor.</span><br>Precisión en cada Pieza.</h2>
    <p class="tagline">Tu plataforma de autopartes de confianza</p>
</div>

<div class="panel-form">
    <div class="inner">
        <div class="logo-mini">
            <i class="bi bi-car-front-fill"></i> Maccuin Autopartes
        </div>
        <h3>Tu Garaje Personal</h3>
        <p class="subtitle">Ingresa tus datos para continuar</p>

        @if(session('success'))
            <div class="alert alert-success" style="background:#d1fae5; color:#065f46;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('cliente.login.post') }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control"
                       placeholder="Correo Electrónico" value="{{ old('email') }}" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control"
                       placeholder="Contraseña" required>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted" for="remember" style="font-size:0.9rem;">Recordar sesión</label>
            </div>
            <button type="submit" class="btn btn-naranja w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i> Acceder al Taller
            </button>
        </form>

        <div class="text-center mt-4 d-flex flex-column gap-2">
            <a href="{{ route('cliente.recuperar') }}" style="font-size:0.9rem;">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('cliente.registro') }}" style="font-size:0.9rem;">Crear una cuenta</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
