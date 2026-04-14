<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Venta & Refacciones Maccuin')</title>
    <meta name="description" content="Plataforma de autopartes y refacciones automotrices de alta calidad — Maccuin">

    {{-- Google Fonts — Inter para toda la app --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN SYSTEM - MACCUIN AUTOPARTES
           Paleta unificada, tipografía Inter
        ═══════════════════════════════════════════ */
        :root {
            --naranja: #E8671B;
            --naranja-hover: #c95510;
            --naranja-soft: rgba(232, 103, 27, 0.10);
            --naranja-glow: rgba(232, 103, 27, 0.30);
            --oscuro: #111111;

            /* Light mode (default) */
            --bg-body: #f4f6fa;
            --bg-card: #ffffff;
            --bg-card-hover: #fafbfd;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: rgba(0, 0, 0, 0.07);
            --nav-bg: #0d0d0d;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        body.dark-mode {
            --bg-body: #0c1222;
            --bg-card: #1a2540;
            --bg-card-hover: #1e2d4a;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --nav-bg: #060a14;
            --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.25);
            --shadow-hover: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        /* ── Global ──────────────────────────────── */
        * { box-sizing: border-box; }

        body {
            background: var(--bg-body) !important;
            color: var(--text-main);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 1rem;
            transition: background 0.4s ease, color 0.4s ease;
            -webkit-font-smoothing: antialiased;
        }

        ::selection {
            background: var(--naranja);
            color: white;
        }

        /* ── Navbar ──────────────────────────────── */
        .navbar-macuin {
            background: var(--nav-bg) !important;
            border-bottom: 2px solid var(--naranja);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.35);
            padding: 1rem 0 !important;
            backdrop-filter: blur(12px);
        }

        .navbar-macuin .navbar-brand {
            font-size: 1.45rem !important;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        .navbar-macuin .navbar-brand:hover { opacity: 0.85; }
        .navbar-macuin .navbar-brand .brand-icon {
            color: var(--naranja);
            font-size: 1.6rem;
        }

        .navbar-macuin .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.6rem 1.1rem !important;
            border-radius: 12px;
            transition: all 0.25s ease;
            position: relative;
        }
        .navbar-macuin .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.06);
        }
        .navbar-macuin .nav-link.active-link {
            color: var(--naranja) !important;
            font-weight: 800;
            background: rgba(232, 103, 27, 0.08);
        }
        .navbar-macuin .nav-link.active-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background: var(--naranja);
            border-radius: 3px;
        }

        /* ── Theme Toggle ────────────────────────── */
        .theme-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .theme-btn:hover {
            background: var(--naranja);
            border-color: var(--naranja);
            transform: rotate(15deg) scale(1.1);
            box-shadow: 0 5px 20px var(--naranja-glow);
        }

        /* ── Buttons ─────────────────────────────── */
        .btn-naranja {
            background: var(--naranja);
            color: #fff;
            border: none;
            font-weight: 700;
            border-radius: 14px;
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--naranja-glow);
        }
        .btn-naranja:hover {
            background: var(--naranja-hover);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--naranja-glow);
        }

        /* ── Utilities ───────────────────────────── */
        .text-naranja { color: var(--naranja) !important; }
        .bg-naranja-soft { background: var(--naranja-soft) !important; }

        /* ── Cards ────────────────────────────────── */
        .card-macuin {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .card-macuin:hover {
            box-shadow: var(--shadow-hover);
        }

        /* ── Alerts ───────────────────────────────── */
        .alert {
            border-radius: 16px;
            font-weight: 600;
            border: none;
        }

        /* ── Footer ──────────────────────────────── */
        .footer-macuin {
            background: var(--nav-bg);
            border-top: 1px solid var(--border-color);
            padding: 55px 0 40px;
            margin-top: 80px;
        }
        .footer-macuin .footer-brand {
            font-size: 1.25rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .footer-macuin .footer-links a {
            color: rgba(255, 255, 255, 0.35);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: 0.3s;
        }
        .footer-macuin .footer-links a:hover {
            color: var(--naranja);
        }
        .footer-macuin .footer-copy {
            color: rgba(255, 255, 255, 0.25);
            font-size: 0.8rem;
        }

        /* ── Scrollbar ───────────────────────────── */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); }
        ::-webkit-scrollbar-thumb {
            background: var(--naranja);
            border-radius: 10px;
        }

        /* ── Animations ──────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 768px) {
            .navbar-macuin .navbar-brand { font-size: 1.2rem !important; }
        }
    </style>

    <script>
        // Apply theme immediately to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.addEventListener('DOMContentLoaded', () => document.body.classList.add('dark-mode'));
            }
        })();
    </script>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-macuin px-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('cliente.catalogo.index') }}">
            <i class="bi bi-car-front-fill brand-icon"></i>
            Maccuin <span style="font-weight:400; opacity:0.5; font-size:0.85rem;">Autopartes</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto ms-lg-4 gap-1">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('cliente.catalogo.*')) active-link @endif"
                       href="{{ route('cliente.catalogo.index') }}">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Catálogo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('cliente.pedidos.*')) active-link @endif"
                       href="{{ route('cliente.pedidos.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Mis Pedidos
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cliente.pedidos.crear') }}" title="Carrito">
                        <i class="bi bi-cart3 fs-5"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <button class="theme-btn" id="navThemeToggle" onclick="toggleTheme()" title="Cambiar Tema">
                        <i class="bi bi-moon-stars" id="navThemeIcon"></i>
                    </button>
                </li>
                @if(session('cliente_logueado'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown" style="color:rgba(255,255,255,0.8) !important;">
                            <div style="width:32px; height:32px; background:var(--naranja); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-person-fill text-white" style="font-size:0.9rem;"></i>
                            </div>
                            {{ session('cliente_nombre', 'Usuario') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius:16px; overflow:hidden;">
                            <li>
                                <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('cliente.logout') }}">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-naranja btn-sm px-4 py-2" href="{{ route('cliente.login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main class="py-4 fade-in-up">
    @if(session('success'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="background: #d1fae5; color: #065f46;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @yield('content')
</main>

<footer class="footer-macuin">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="footer-brand">
                    <i class="bi bi-car-front-fill text-naranja me-2"></i>Maccuin
                </div>
                <p class="footer-copy mt-2 mb-0">Autopartes & Refacciones de precisión</p>
            </div>
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <div class="footer-links d-flex justify-content-center gap-4">
                    <a href="#">Nosotros</a>
                    <a href="#">Contacto</a>
                    <a href="#">Privacidad</a>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="footer-copy mb-0">© {{ date('Y') }} Maccuin — Todos los derechos reservados</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleTheme() {
        const body  = document.body;
        const isDark = body.classList.toggle('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcons(isDark);
    }

    function updateThemeIcons(isDark) {
        const navIcon  = document.getElementById('navThemeIcon');
        if (navIcon)  navIcon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
        const pageIcon = document.getElementById('themeIcon');
        if (pageIcon) pageIcon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.body.classList.contains('dark-mode');
        updateThemeIcons(isDark);
    });
</script>
@stack('scripts')
</body>
</html>
