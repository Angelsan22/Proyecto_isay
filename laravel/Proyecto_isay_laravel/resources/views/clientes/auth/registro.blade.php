<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — Maccuin Autopartes</title>
    <meta name="description" content="Regístrate en Maccuin Autopartes para acceder al catálogo de refacciones">

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
            background: #f4f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            padding: 2rem;
        }

        /* ── Top Nav ───────────────────────────── */
        .top-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            z-index: 10;
        }
        .top-nav .brand {
            font-weight: 900;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #111;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .top-nav .brand i { color: var(--naranja); font-size: 1.3rem; }
        .top-nav a.nav-action {
            font-weight: 700;
            color: #111;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.5rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: 0.3s;
        }
        .top-nav a.nav-action:hover {
            border-color: var(--naranja);
            color: var(--naranja);
            background: rgba(232, 103, 27, 0.05);
        }

        /* ── Card Form ─────────────────────────── */
        .card-form {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 28px;
            padding: 2.5rem 2.5rem;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: 4rem;
        }

        .card-form h2 {
            font-weight: 900;
            font-size: 1.8rem;
            color: #111;
            letter-spacing: -0.5px;
            margin-bottom: 0.3rem;
        }
        .card-form .subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        /* ── Form Controls ─────────────────────── */
        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 14px;
            padding: 0.8rem 1.1rem;
            border: 2px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: var(--naranja);
            box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.1);
            outline: none;
        }
        .form-control::placeholder { color: #94a3b8; }

        .btn-naranja {
            background: var(--naranja);
            color: #fff;
            border: none;
            padding: 0.9rem;
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

        .alert {
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-form { animation: slideUp 0.5s ease forwards; }
    </style>
</head>
<body>

<nav class="top-nav">
    <span class="brand"><i class="bi bi-car-front-fill"></i> Maccuin</span>
    <a href="{{ route('cliente.login') }}" class="nav-action">
        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
    </a>
</nav>

<div class="card-form">
    <h2>Crear Cuenta</h2>
    <p class="subtitle">Regístrate para explorar nuestro catálogo de autopartes</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('cliente.registro.post') }}" method="POST">
        @csrf
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Tu nombre"
                       value="{{ old('nombre') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" placeholder="Tus apellidos"
                       value="{{ old('apellidos') }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" placeholder="tu@correo.com"
                   value="{{ old('email') }}" required>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
            </div>
        </div>
        <button type="submit" class="btn btn-naranja w-100">
            <i class="bi bi-person-plus me-2"></i> Crear cuenta
        </button>
    </form>

    <p class="text-center mt-3 text-muted" style="font-size:0.9rem;">
        ¿Ya tienes cuenta? <a href="{{ route('cliente.login') }}">Iniciar Sesión</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
