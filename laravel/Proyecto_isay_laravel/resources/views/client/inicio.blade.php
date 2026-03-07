<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MACUIN | Inicio</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link rel="stylesheet" href="{{ asset('css/inicio.css') }}">

<link rel="stylesheet" href="{{ asset('css/theme.css') }}">
<script src="{{ asset('js/theme.js') }}"></script>


</head>
<body>

<!-- ── SIDEBAR ── -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-wordmark">MAC<span>UIN</span></div>
        <p class="logo-sub">Refacciones Automotrices</p>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-section-label">Menú</span>

        <a href="{{ route('client.index') }}" class="nav-link active">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Inicio</span>
        </a>
        <a href="{{ route('client.catalog') }}" class="nav-link">
            <i class="fa-solid fa-layer-group"></i>
            <span>Catálogo</span>
        </a>
        <!-- Nuevo enlace solicitado -->
        <a href="{{ route('client.cart') }}" class="nav-link">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Carrito</span>
        </a>
        <a href="{{ route('client.orders') }}" class="nav-link">
            <i class="fa-solid fa-receipt"></i>
            <span>Mis Pedidos</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-chip">
            <div class="user-avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="user-info">
                <span class="user-name">Mi Cuenta</span>
                <span class="user-role">Cliente</span>
            </div>
        </div>
        <button class="theme-toggle-btn" id="theme-toggle">
            <i class="fa-solid fa-sun  icon-dark"></i>
            <i class="fa-solid fa-moon icon-light"></i>
            <span class="toggle-label-dark">Modo Claro</span>
            <span class="toggle-label-light">Modo Oscuro</span>
        </button>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Salir</span>
            </button>
        </form>
    </div>
</aside>

<!-- ── MAIN CONTENT ── -->
<main class="main-content">

    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-title">
            <span class="topbar-tag">Panel</span>
            <h1 class="topbar-heading">Inicio</h1>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('client.catalog') }}" class="btn-cta">
                <i class="fa-solid fa-bolt"></i>
                Nuevo Pedido
            </a>
        </div>
    </header>

    <!-- Welcome Banner -->
    <section class="welcome-banner">
        <div class="welcome-text">
            <p class="welcome-eyebrow">Buenos días</p>
            <h2 class="welcome-title">¡Bienvenido de nuevo a <em>MACUIN</em>!</h2>
            <p class="welcome-sub">Gestiona tus pedidos de refacciones y explora nuestro catálogo actualizado.</p>
        </div>
        <div class="welcome-decoration">
            <i class="fa-solid fa-gear welcome-gear gear-1"></i>
            <i class="fa-solid fa-gear welcome-gear gear-2"></i>
            <i class="fa-solid fa-gear welcome-gear gear-3"></i>
        </div>
    </section>

    <!-- Stats Grid -->
    <section class="stats-grid">

        <div class="stat-card stat-card--accent reveal" style="--delay: 0.05s">
            <div class="stat-icon-wrap">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Pedidos Activos</span>
                <span class="stat-value">2</span>
            </div>
            <span class="stat-badge">En proceso</span>
        </div>

        <div class="stat-card reveal" style="--delay: 0.12s">
            <div class="stat-icon-wrap">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Último Pedido</span>
                <span class="stat-value stat-value--sm">Bujías NGK</span>
                <span class="stat-meta">Hace 3 días</span>
            </div>
            <span class="stat-badge stat-badge--muted">Entregado</span>
        </div>

        <div class="stat-card reveal" style="--delay: 0.19s">
            <div class="stat-icon-wrap">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Historial Total</span>
                <span class="stat-value">14</span>
                <span class="stat-meta">pedidos realizados</span>
            </div>
        </div>

    </section>

    <!-- Quick Access -->
    <section class="quick-section reveal" style="--delay: 0.26s">
        <h3 class="section-title">Acceso Rápido</h3>
        <div class="quick-grid">
            <a href="{{ route('client.catalog') }}" class="quick-card">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Buscar Refacción</span>
                <i class="fa-solid fa-arrow-right quick-arrow"></i>
            </a>
            <a href="{{ route('client.orders') }}" class="quick-card">
                <i class="fa-solid fa-list-check"></i>
                <span>Ver Pedidos</span>
                <i class="fa-solid fa-arrow-right quick-arrow"></i>
            </a>
            <a href="{{ route('client.catalog') }}" class="quick-card">
                <i class="fa-solid fa-star"></i>
                <span>Más Vendidos</span>
                <i class="fa-solid fa-arrow-right quick-arrow"></i>
            </a>
        </div>
    </section>

</main>


</body>
</html>