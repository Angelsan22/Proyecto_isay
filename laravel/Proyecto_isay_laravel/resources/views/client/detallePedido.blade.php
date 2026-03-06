{{-- resources/views/client/order-detail.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Detalle del Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/macuin.css') }}">
</head>
<body class="dashboard-page">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-wordmark">MAC<span>UIN</span></div>
            <p class="logo-sub">Refacciones Automotrices</p>
        </div>
        <nav class="sidebar-nav">
            <span class="nav-section-label">Menú</span>
            <a href="{{ route('client.index') }}" class="nav-link">
                <i class="fa-solid fa-gauge-high"></i><span>Inicio</span>
            </a>
            <a href="{{ route('client.catalog') }}" class="nav-link">
                <i class="fa-solid fa-layer-group"></i><span>Catálogo</span>
            </a>
            <a href="{{ route('client.orders') }}" class="nav-link active">
                <i class="fa-solid fa-receipt"></i><span>Mis Pedidos</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name ?? 'Mi Cuenta' }}</span>
                    <span class="user-role">Cliente</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i><span>Salir</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">

        <!-- Breadcrumb + Header -->
        <header class="topbar">
            <div class="topbar-title">
                <span class="topbar-tag">
                    <a href="{{ route('client.orders') }}" style="color:inherit;text-decoration:none;">
                        <i class="fa-solid fa-arrow-left" style="margin-right:6px;font-size:.7rem;"></i>Mis Pedidos
                    </a>
                </span>
                <h1 class="topbar-heading">Pedido #2026-007</h1>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('client.orders.tracking', '2026-007') }}" class="btn-outline-action">
                    <i class="fa-solid fa-location-dot"></i> Seguimiento
                </a>
                <a href="{{ route('client.orders.pdf', '2026-007') }}" class="btn-cta" target="_blank">
                    <i class="fa-solid fa-file-arrow-down"></i> Descargar Factura
                </a>
            </div>
        </header>

        <!-- Status Bar -->
        <div class="order-status-bar reveal" style="--delay:.04s">
            <div class="osb-item osb-done">
                <div class="osb-dot"><i class="fa-solid fa-check"></i></div>
                <span>Confirmado</span>
            </div>
            <div class="osb-line osb-line-done"></div>
            <div class="osb-item osb-active">
                <div class="osb-dot"><i class="fa-solid fa-truck"></i></div>
                <span>En Camino</span>
            </div>
            <div class="osb-line"></div>
            <div class="osb-item">
                <div class="osb-dot"><i class="fa-solid fa-house"></i></div>
                <span>Entregado</span>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="detail-grid reveal" style="--delay:.1s">

            <!-- Info Card -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="fa-solid fa-circle-info"></i>
                    <h3>Información del Pedido</h3>
                </div>
                <div class="detail-rows">
                    <div class="detail-row">
                        <span class="dr-label">Número de Pedido</span>
                        <span class="dr-value">#2026-007</span>
                    </div>
                    <div class="detail-row">
                        <span class="dr-label">Fecha</span>
                        <span class="dr-value">01/02/2026</span>
                    </div>
                    <div class="detail-row">
                        <span class="dr-label">Estado</span>
                        <span class="dr-value"><span class="status-badge status-en_camino"><i class="fa-solid fa-truck"></i> En Proceso</span></span>
                    </div>
                    <div class="detail-row">
                        <span class="dr-label">Método de Envío</span>
                        <span class="dr-value">Mensajería Express</span>
                    </div>
                    <div class="detail-row">
                        <span class="dr-label">Entrega Estimada</span>
                        <span class="dr-value highlight">05/02/2026</span>
                    </div>
                    <div class="detail-row detail-row-total">
                        <span class="dr-label">Total del Pedido</span>
                        <span class="dr-value dr-big">$1,250.00</span>
                    </div>
                </div>
            </div>

            <!-- Articles Card -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <h3>Artículos</h3>
                </div>
                <div class="articles-list">

                    @php
                    $articles = [
                        ['icon'=>'fa-circle-dot','name'=>'Pastillas de Freno Delanteras','qty'=>2,'unit'=>60.00],
                        ['icon'=>'fa-oil-can','name'=>'Filtro de Aceite X200','qty'=>1,'unit'=>30.00],
                        ['icon'=>'fa-circle','name'=>'Neumático Michelin Pilot Sport 4S','qty'=>1,'unit'=>1100.00],
                    ];
                    @endphp

                    @foreach($articles as $a)
                    <div class="article-row">
                        <div class="article-icon">
                            <i class="fa-solid {{ $a['icon'] }}"></i>
                        </div>
                        <div class="article-info">
                            <span class="article-name">{{ $a['name'] }}</span>
                            <span class="article-qty">x{{ $a['qty'] }} — ${{ number_format($a['unit'],2) }} c/u</span>
                        </div>
                        <span class="article-total">${{ number_format($a['unit'] * $a['qty'], 2) }}</span>
                    </div>
                    @endforeach

                    <div class="articles-summary">
                        <div class="as-row"><span>Subtotal</span><span>$1,250.00</span></div>
                        <div class="as-row"><span>Envío</span><span>$25.00</span></div>
                        <div class="as-row as-total"><span>Total</span><span>$1,275.00</span></div>
                    </div>
                </div>
            </div>

        </div>

    </main>

</body>
</html>