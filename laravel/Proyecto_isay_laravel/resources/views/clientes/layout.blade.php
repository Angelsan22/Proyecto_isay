<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Venta & Refacciones')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --naranja: #E8671B; --oscuro: #1a1a1a; }
        body { background: #f5f5f5; }
        .navbar-dark-custom { background: var(--oscuro) !important; }
        .btn-naranja  { background: var(--naranja); color: #fff; border: none; }
        .btn-naranja:hover { background: #c95510; color: #fff; }
        .text-naranja { color: var(--naranja) !important; }
        .nav-link-active { color: var(--naranja) !important; border-bottom: 2px solid var(--naranja); }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark-custom px-4 py-2">
    <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2"
       href="{{ route('cliente.catalogo.index') }}">
        <i class="bi bi-car-front-fill" style="color:var(--naranja)"></i> Venta & Refacciones
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto gap-2">
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('cliente.catalogo.*')) nav-link-active @endif"
                   href="{{ route('cliente.catalogo.index') }}">Catálogo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white @if(request()->routeIs('cliente.pedidos.*')) nav-link-active @endif"
                   href="{{ route('cliente.pedidos.index') }}">Mis Pedidos</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto align-items-center gap-2">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('cliente.pedidos.crear') }}">
                    <i class="bi bi-cart3 fs-5"></i>
                </a>
            </li>
            @if(session('cliente_logueado'))
                <li class="nav-item dropdown">
                    <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ session('cliente_nombre', 'Usuario') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('cliente.logout') }}">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a class="btn btn-naranja btn-sm px-3" href="{{ route('cliente.login') }}">Iniciar Sesión</a>
                </li>
            @endif
        </ul>
    </div>
</nav>

<main class="py-4">
    @if(session('success'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @yield('content')
</main>

<footer class="text-center py-3 text-muted small border-top mt-4">
    © {{ date('Y') }} Venta & Refacciones — Automóviles & Refacciones
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
