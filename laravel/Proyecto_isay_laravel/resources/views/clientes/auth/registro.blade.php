<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --naranja: #E8671B; }
        body { background: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card-form { width: 100%; max-width: 480px; padding: 2.5rem; }
        .btn-naranja { background: var(--naranja); color: #fff; border: none; padding: .75rem; border-radius: 2rem; }
        .btn-naranja:hover { background: #c95510; color: #fff; }
        .form-control { border-radius: 2rem; padding: .6rem 1.2rem; }
        a { color: var(--naranja); text-decoration: none; }
        nav { position: fixed; top: 0; left: 0; right: 0; padding: .8rem 2rem;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid #eee; background: #fff; z-index: 10; }
        .brand { font-weight: 800; font-size: 1.1rem; text-transform: uppercase; }
    </style>
</head>
<body>
<nav>
    <span class="brand">🚗 MACUIN</span>
    <a href="{{ route('cliente.login') }}" class="fw-bold" style="color:#000">INICIAR SESIÓN</a>
</nav>

<div class="card-form mt-5">
    <h2 class="fw-bold mb-4">Crear Cuenta</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('cliente.registro.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label text-muted small">Nombre</label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre de usuario"
                   value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-muted small">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" placeholder="Apellidos"
                   value="{{ old('apellidos') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-muted small">Correo</label>
            <input type="email" name="email" class="form-control" placeholder="hello@correo.com"
                   value="{{ old('email') }}" required>
        </div>
        <div class="row g-3 mb-4">
            <div class="col">
                <label class="form-label text-muted small">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="col">
                <label class="form-label text-muted small">Confirmar</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
            </div>
        </div>
        <button type="submit" class="btn btn-naranja w-100 fw-bold">Crear cuenta</button>
    </form>

    <p class="text-center mt-3 text-muted small">
        ¿Ya tienes cuenta? <a href="{{ route('cliente.login') }}">Iniciar Sesión</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
