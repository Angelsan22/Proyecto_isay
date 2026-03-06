{{-- resources/views/client/create-order.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Nuevo Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/macuin.css') }}">
</head>
<body class="dashboard-page">

    <!-- SIDEBAR -->
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
            <a href="{{ route('client.catalog') }}" class="nav-link active">
                <i class="fa-solid fa-layer-group"></i><span>Catálogo</span>
            </a>
            <a href="{{ route('client.orders') }}" class="nav-link">
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

    <!-- MAIN -->
    <main class="main-content">

        <header class="topbar">
            <div class="topbar-title">
                <span class="topbar-tag">Catálogo</span>
                <h1 class="topbar-heading">Nuevo Pedido</h1>
            </div>
            <div class="topbar-actions">
                <button class="btn-cta" id="btn-open-cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Ver Carrito
                    <span class="cart-count-badge" id="cart-count">0</span>
                </button>
            </div>
        </header>

        <!-- Search -->
        <div class="search-bar-wrap reveal" style="--delay:.04s">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="search-input" placeholder="Buscar por nombre o SKU…" autocomplete="off">
                <span class="search-shortcut">⌘K</span>
            </div>
        </div>

        <!-- Product Grid -->
        <section class="product-section reveal" style="--delay:.08s">
            <div class="section-header-row">
                <h3 class="section-title">Productos Disponibles</h3>
                <span class="product-count" id="product-count">4 productos</span>
            </div>
            <div class="product-grid" id="product-grid">

                @php
                $products = [
                    ['id'=>1,'sku'=>'FLT-001','name'=>'Filtro de Aceite X200','price'=>14.99,'icon'=>'fa-oil-can','category'=>'Filtros'],
                    ['id'=>2,'sku'=>'FRN-045','name'=>'Pastillas de Freno Delanteras','price'=>45.50,'icon'=>'fa-circle-dot','category'=>'Frenos'],
                    ['id'=>3,'sku'=>'LLN-R18','name'=>'Neumático Deportivo R18','price'=>120.75,'icon'=>'fa-circle','category'=>'Llantas'],
                    ['id'=>4,'sku'=>'BUJ-NGK','name'=>'Bujía de Iridio NGK','price'=>8.99,'icon'=>'fa-bolt','category'=>'Motor'],
                    ['id'=>5,'sku'=>'ACT-5W30','name'=>'Aceite Sintético 5W-30','price'=>32.00,'icon'=>'fa-droplet','category'=>'Lubricantes'],
                    ['id'=>6,'sku'=>'FLT-AIR','name'=>'Filtro de Aire Mann','price'=>18.50,'icon'=>'fa-wind','category'=>'Filtros'],
                ];
                @endphp

                @foreach($products as $p)
                <div class="product-card reveal" style="--delay:{{ 0.05 * $loop->iteration }}s"
                     data-name="{{ strtolower($p['name']) }}" data-sku="{{ strtolower($p['sku']) }}">
                    <div class="product-icon-wrap">
                        <i class="fa-solid {{ $p['icon'] }}"></i>
                    </div>
                    <div class="product-category">{{ $p['category'] }}</div>
                    <h4 class="product-name">{{ $p['name'] }}</h4>
                    <p class="product-sku">SKU: {{ $p['sku'] }}</p>
                    <div class="product-footer">
                        <span class="product-price">${{ number_format($p['price'],2) }}</span>
                        <button class="btn-add" onclick="addToCart({{ $p['id'] }}, '{{ $p['name'] }}', {{ $p['price'] }})">
                            <i class="fa-solid fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>
                @endforeach

            </div>
        </section>

    </main>

    <!-- CART DRAWER -->
    <div class="cart-overlay" id="cart-overlay" onclick="closeCart()"></div>
    <aside class="cart-drawer" id="cart-drawer">
        <div class="cart-header">
            <h2 class="cart-title">Tu Carrito</h2>
            <button class="cart-close" onclick="closeCart()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="cart-body" id="cart-body">
            <div class="cart-empty" id="cart-empty">
                <i class="fa-solid fa-cart-shopping"></i>
                <p>Tu carrito está vacío</p>
            </div>
            <ul class="cart-list" id="cart-list"></ul>
        </div>

        <div class="cart-footer" id="cart-footer" style="display:none">
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">$0.00</span>
                </div>
                <div class="summary-row">
                    <span>Envío estimado</span>
                    <span>$25.00</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span id="cart-total">$0.00</span>
                </div>
            </div>
            <form action="{{ route('client.orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cart-data-input">
                <button type="submit" class="btn-confirm" onclick="prepareSubmit()">
                    <i class="fa-solid fa-lock"></i>
                    Confirmar y Pagar
                </button>
            </form>
        </div>
    </aside>

<script src="{{ asset('js/crearPedido.js') }}"></script>
</body>
</html>