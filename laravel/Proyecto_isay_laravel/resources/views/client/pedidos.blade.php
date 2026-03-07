{{-- resources/views/client/pedidos.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Mis Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/macAut.css') }}">
    <script>
        (function(){
            if(localStorage.getItem('macuin_theme')==='light')
            document.documentElement.setAttribute('data-theme','light');
        })();
    </script>
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
            <!-- Nuevo enlace solicitado -->
            <a href="{{ route('client.cart') }}" class="nav-link">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Carrito</span>
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
            <button class="theme-toggle-btn" id="theme-toggle">
                <i class="fa-solid fa-sun  icon-dark"></i>
                <i class="fa-solid fa-moon icon-light"></i>
                <span class="toggle-label-dark">Modo Claro</span>
                <span class="toggle-label-light">Modo Oscuro</span>
            </button>
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
                <span class="topbar-tag">Historial</span>
                <h1 class="topbar-heading">Mis Pedidos</h1>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('client.catalog') }}" class="btn-cta">
                    <i class="fa-solid fa-plus"></i> Nuevo Pedido
                </a>
            </div>
        </header>

        <!-- Filter Tabs -->
        <div class="filter-tabs reveal" style="--delay:.04s">
            <button class="filter-tab active" data-filter="all">Todos</button>
            <button class="filter-tab" data-filter="en_proceso">En Proceso</button>
            <button class="filter-tab" data-filter="en_camino">En Camino</button>
            <button class="filter-tab" data-filter="entregado">Entregados</button>
        </div>

        <!-- Orders List -->
        <section class="orders-list reveal" style="--delay:.08s">

            @php
            $orders = [
                ['id'=>'2026-00345','date'=>'1 Febrero, 2026','status'=>'en_camino','status_label'=>'En Camino','items'=>[['name'=>'Pinza de Freno Alto Rendimiento','qty'=>2,'price'=>175.00],['name'=>'Llanta Deport Aleación X-5','qty'=>2,'price'=>175.00]],'total'=>350.00,'icon'=>'fa-circle-dot'],
                ['id'=>'2026-00312','date'=>'25 Enero, 2026','status'=>'entregado','status_label'=>'Entregado','items'=>[['name'=>'Filtro de Aceite X200','qty'=>1,'price'=>14.99],['name'=>'Bujía de Iridio NGK','qty'=>4,'price'=>8.99]],'total'=>50.95,'icon'=>'fa-oil-can'],
                ['id'=>'2026-00289','date'=>'18 Enero, 2026','status'=>'en_proceso','status_label'=>'En Proceso','items'=>[['name'=>'Neumático Deportivo R18','qty'=>4,'price'=>120.75]],'total'=>483.00,'icon'=>'fa-circle'],
            ];
            @endphp

            @foreach($orders as $order)
            <div class="order-card reveal" style="--delay:{{ 0.06 * $loop->iteration }}s" data-status="{{ $order['status'] }}">
                <div class="order-card-header">
                    <div class="order-meta">
                        <span class="order-date"><i class="fa-regular fa-calendar"></i> {{ $order['date'] }}</span>
                        <span class="order-id">Pedido #{{ $order['id'] }}</span>
                    </div>
                    <span class="status-badge status-{{ $order['status'] }}">
                        @if($order['status'] === 'entregado') <i class="fa-solid fa-check"></i>
                        @elseif($order['status'] === 'en_camino') <i class="fa-solid fa-truck"></i>
                        @else <i class="fa-solid fa-clock"></i> @endif
                        {{ $order['status_label'] }}
                    </span>
                </div>

                <div class="order-card-body">
                    @foreach($order['items'] as $item)
                    <div class="order-item-row">
                        <div class="order-item-icon">
                            <i class="fa-solid {{ $order['icon'] }}"></i>
                        </div>
                        <div class="order-item-info">
                            <span class="order-item-name">{{ $item['name'] }}</span>
                            <span class="order-item-qty">Cantidad: {{ $item['qty'] }}</span>
                        </div>
                        <span class="order-item-price">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="order-card-footer">
                    <div class="order-total-wrap">
                        <span class="order-total-label">Total</span>
                        <span class="order-total-value">${{ number_format($order['total'], 2) }} USD</span>
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('client.orders.show', $order['id']) }}" class="btn-order-detail">
                            <i class="fa-solid fa-eye"></i> Ver Detalles
                        </a>
                        <a href="{{ route('client.orders.pdf', $order['id']) }}" class="btn-order-pdf" target="_blank">
                            <i class="fa-solid fa-file-pdf"></i> Factura PDF
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        </section>

        <!-- Pagination -->
        <div class="pagination-wrap reveal" style="--delay:.3s">
            <button class="page-btn" disabled><i class="fa-solid fa-chevron-left"></i> Anterior</button>
            <div class="page-numbers">
                <button class="page-num active">1</button>
                <button class="page-num">2</button>
                <button class="page-num">3</button>
            </div>
            <button class="page-btn">Siguiente <i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- Footer -->
        <footer class="page-footer">
            <a href="#">Términos y Condiciones</a>
            <a href="#">Política de Privacidad</a>
        </footer>

    </main>
<script src="{{ asset('js/macuin.js') }}"></script>
<script src="{{ asset('js/pedido.js') }}"></script>
</body>
</html>