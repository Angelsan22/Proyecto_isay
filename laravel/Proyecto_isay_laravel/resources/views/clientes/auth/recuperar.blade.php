<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — Maccuin Autopartes</title>
    <meta name="description" content="Restablece tu contraseña de Maccuin Autopartes">

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
            margin: 0;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card-recuperar {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 32px;
            padding: 3.5rem;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .icon-header {
            width: 80px;
            height: 80px;
            background: #fff7ed;
            color: var(--naranja);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(232, 103, 27, 0.08);
        }

        .card-recuperar h2 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: #0f172a;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .card-recuperar .subtitle {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 2.5rem;
            text-align: center;
            line-height: 1.5;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .form-control-custom {
            width: 100%;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.85rem 1.25rem;
            font-size: 1rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .form-control-custom:focus {
            background: white;
            border-color: var(--naranja);
            box-shadow: 0 0 0 4px var(--naranja-glow);
            outline: none;
        }

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

        .back-link {
            margin-top: 2rem;
            text-align: center;
        }

        .back-link a {
            color: #64748b;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }

        .back-link a:hover { color: var(--naranja); }

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

    </style>
</head>
<body>

<div class="card-recuperar">
    <div class="icon-header">
        <i class="bi bi-shield-lock-fill"></i>
    </div>

    <h2>Recuperar Cuenta</h2>
    <p class="subtitle">Ingresa tu correo asociado y establece tu nueva contraseña de acceso.</p>

    @if($errors->any())
        <div class="alert-error">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cliente.recuperar.post') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control-custom" 
                   placeholder="tu@correo.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva Contraseña</label>
            <input type="password" name="password" class="form-control-custom" 
                   placeholder="••••••••" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control-custom" 
                   placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-submit">
            Restablecer Ahora
            <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('cliente.login') }}">
            <i class="bi bi-arrow-left"></i>
            Regresar al inicio
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
