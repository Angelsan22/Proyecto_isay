<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Venta & Refacciones')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { 
            --naranja: #E8671B; 
            --naranja-hover: #c95510;
            --oscuro: #1a1a1a; 
            
            /* Theme variables (Light default) */
            --bg-body: #f1f4f9;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: rgba(0,0,0,0.06);
            --nav-bg: #111111;
        }

        body.dark-mode {
            /* Sync with Flask Dark Palette */
            --bg-body: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #e2e8f0; /* Mas brillante (Slate-200) para legibilidad extrema */
            --border-color: #334155;
            --nav-bg: #05070a;
        }

        body { 
            background: var(--bg-body) !important; 
            color: var(--text-main);
            transition: background 0.4s ease, color 0.4s ease;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 1.05rem;
        }

        .navbar-dark-custom { 
            background: var(--nav-bg) !important; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-bottom: 2px solid var(--naranja);
            padding-top: 1.2rem !important;
            padding-bottom: 1.2rem !important;
        }

        .navbar-brand { font-size: 1.6rem !important; }
        .nav-link { font-size: 1.1rem; font-weight: 500; transition: 0.3s; }

        .btn-naranja  { background: var(--naranja); color: #fff; border: none; font-weight: 700; border-radius: 12px; transition: 0.3s; }
        .btn-naranja:hover { background: var(--naranja-hover); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 15px rgba(232, 103, 27, 0.3); }
        .text-naranja { color: var(--naranja) !important; }
        .nav-link-active { color: var(--naranja) !important; font-weight: 700; position: relative; }
        .nav-link-active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: var(--naranja); border-radius: 2px; }
        
        /* Footer refinements */
        .main-footer {
            background: var(--nav-bg);
            color: #777;
            padding: 50px 0;
            margin-top: 80px;
            border-top: 1px solid var(--border-color);
        }
    </style>
    <script>
        // Apply theme immediately to prevent flashing
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark-theme-active'); // For non-body styling if needed
                document.addEventListener('DOMContentLoaded', () => document.body.classList.add('dark-mode'));
            }
        })();
    </script>
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

<footer class="main-footer">
    <div class="container text-center">
        <div class="mb-3">
            <h5 class="fw-bold text-white mb-0">Venta & Refacciones <span class="text-naranja">Maccuin</span></h5>
        </div>
        <p class="mb-0 small">© {{ date('Y') }} Venta & Refacciones — Automóviles & Refacciones</p>
        <div class="mt-3 d-flex justify-content-center gap-3">
            <a href="#" class="text-muted text-decoration-none small">Sobre nosotros</a>
            <a href="#" class="text-muted text-decoration-none small">Contacto</a>
            <a href="#" class="text-muted text-decoration-none small">Política de Privacidad</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
