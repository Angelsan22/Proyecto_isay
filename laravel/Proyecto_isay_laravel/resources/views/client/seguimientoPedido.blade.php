{{-- resources/views/client/order-tracking.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Seguimiento de Pedido</title>
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

        <header class="topbar">
            <div class="topbar-title">
                <span class="topbar-tag">
                    <a href="{{ route('client.orders.show', 'FLSK-2026-9876') }}" style="color:inherit;text-decoration:none;">
                        <i class="fa-solid fa-arrow-left" style="margin-right:6px;font-size:.7rem;"></i>Detalle del Pedido
                    </a>
                </span>
                <h1 class="topbar-heading">Seguimiento</h1>
            </div>
            <div class="topbar-actions">
                <span class="status-badge status-en_camino" style="font-size:.8rem;padding:8px 16px;">
                    <i class="fa-solid fa-truck"></i> En Camino
                </span>
            </div>
        </header>

        <div class="tracking-layout reveal" style="--delay:.06s">

            <!-- Timeline Column -->
            <div class="tracking-card">
                <div class="tracking-card-header">
                    <i class="fa-solid fa-route"></i>
                    <h3>Estatus del Envío</h3>
                </div>

                <div class="order-ref-chip">
                    <span class="orc-label">Pedido</span>
                    <span class="orc-value">#FLSK-2026-9876</span>
                </div>

                <div class="timeline">

                    <div class="tl-item tl-done">
                        <div class="tl-icon"><i class="fa-solid fa-check"></i></div>
                        <div class="tl-connector tl-connector-done"></div>
                        <div class="tl-content">
                            <span class="tl-title">Pedido Confirmado</span>
                            <span class="tl-date">01/02/2026 — 10:32 AM</span>
                            <span class="tl-desc">Tu pedido fue recibido y está siendo preparado.</span>
                        </div>
                    </div>

                    <div class="tl-item tl-active">
                        <div class="tl-icon tl-icon-active"><i class="fa-solid fa-truck"></i></div>
                        <div class="tl-connector"></div>
                        <div class="tl-content">
                            <span class="tl-title">En Camino</span>
                            <span class="tl-date tl-date-active">Entrega estimada: 05/02/2026</span>
                            <span class="tl-desc">Tu paquete está en ruta con Mensajería Express.</span>
                            <div class="tl-eta-chip">
                                <i class="fa-solid fa-clock"></i> Llega en ~2 días
                            </div>
                        </div>
                    </div>

                    <div class="tl-item tl-pending">
                        <div class="tl-icon tl-icon-pending"><i class="fa-solid fa-house"></i></div>
                        <div class="tl-connector" style="display:none"></div>
                        <div class="tl-content">
                            <span class="tl-title">Entregado</span>
                            <span class="tl-date">Pendiente</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Details Column -->
            <div class="tracking-card">
                <div class="tracking-card-header">
                    <i class="fa-solid fa-box-open"></i>
                    <h3>Detalles del Pedido</h3>
                </div>

                <div class="tracking-meta">
                    <div class="tm-row"><span>Número de Pedido</span><strong>#FLSK-2026-9876</strong></div>
                    <div class="tm-row"><span>Fecha de Compra</span><strong>01/02/2026</strong></div>
                    <div class="tm-row"><span>Método de Envío</span><strong>Mensajería Express</strong></div>
                </div>

                <div class="tracking-items">
                    <span class="tracking-items-label">Artículos en este pedido</span>

                    @php
                    $trackingItems = [
                        ['icon'=>'fa-circle-dot','name'=>'Pastillas de Freno Delanteras','qty'=>1],
                        ['icon'=>'fa-oil-can','name'=>'Filtro de Aceite Sintético','qty'=>2],
                        ['icon'=>'fa-circle','name'=>'Llantas Deportivas Michelin','qty'=>4],
                    ];
                    @endphp

                    @foreach($trackingItems as $i => $item)
                    <div class="ti-row">
                        <span class="ti-num">{{ $i + 1 }}</span>
                        <div class="ti-icon"><i class="fa-solid {{ $item['icon'] }}"></i></div>
                        <span class="ti-name">{{ $item['name'] }}</span>
                        <span class="ti-qty">x{{ $item['qty'] }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="tracking-actions">
                    <a href="{{ route('client.orders.show', 'FLSK-2026-9876') }}" class="btn-cta" style="text-align:center;justify-content:center;">
                        <i class="fa-solid fa-list-ul"></i> Ver Detalle Completo
                    </a>
                    <a href="#" class="btn-outline-action" style="text-align:center;justify-content:center;">
                        <i class="fa-solid fa-headset"></i> ¿Necesitas ayuda? Contáctanos
                    </a>
                </div>
            </div>

        </div>

    </main>

</body>
</html>