{{-- resources/views/client/crearPedido.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Nuevo Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/macAut.css') }}">
    <script>
            (function(){
                if(localStorage.getItem('macuin_theme')==='light')
                document.documentElement.setAttribute('data-theme','light');
            })();
    </script>
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
            <a href="{{ route('client.index') }}" class="nav-link">
                <i class="fa-solid fa-gauge-high"></i><span>Inicio</span>
            </a>
            <a href="{{ route('client.catalog') }}" class="nav-link active">
                <i class="fa-solid fa-layer-group"></i><span>Catálogo</span>
            </a>
            <!-- Nuevo enlace solicitado -->
            <a href="{{ route('client.cart') }}" class="nav-link">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Carrito</span>
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

    <!-- ── MAIN ── -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <span class="topbar-tag">Catálogo</span>
                <h1 class="topbar-heading">Agregar Productos</h1>
            </div>
        </header>

        <!-- Search -->
        <div class="search-wrap reveal" style="--delay:.04s">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="search-input" placeholder="Buscar por nombre o SKU…" autocomplete="off">
            </div>
        </div>

        <!-- ── PRODUCT GRID ── -->
        <section class="catalog-section reveal" style="--delay:.08s">
            <div class="catalog-header">
                <span class="catalog-count" id="product-count">
                    {{-- Se actualiza con JS --}}
                    {{ count($productos ?? []) }} productos
                </span>
                <div class="catalog-filters">
                    <button class="cat-filter active" data-cat="all">Todos</button>
                    <button class="cat-filter" data-cat="Filtros">Filtros</button>
                    <button class="cat-filter" data-cat="Frenos">Frenos</button>
                    <button class="cat-filter" data-cat="Llantas">Llantas</button>
                    <button class="cat-filter" data-cat="Motor">Motor</button>
                    <button class="cat-filter" data-cat="Lubricantes">Lubricantes</button>
                </div>
            </div>

            <div class="product-grid" id="product-grid">

                @php
                /**
                 * En producción estos vendrán de $productos (API FastAPI).
                 * Aquí usamos datos de ejemplo para la vista estática.
                 * Reemplaza el @php con: @foreach($productos as $p)
                 */
                $productos_demo = [
                    ['id'=>1,'sku'=>'FLT-001','name'=>'Filtro de Aceite X200',        'price'=>180.00,  'category'=>'Filtros',    'img'=>'https://imgs.search.brave.com/9jilBVRWgjrXTUY_yIZB7wjHdSqHpgDqu3BPL2Q1wc0/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9odHRw/Mi5tbHN0YXRpYy5j/b20vRF9OUV9OUF83/MTQ1NDMtTUxNNzI5/NjQ0MTQ5OTJfMTEy/MDIzLU8ud2VicA'],
                    ['id'=>2,'sku'=>'FRN-045','name'=>'Pastillas de Freno Delanteras', 'price'=>650.00,  'category'=>'Frenos',     'img'=>'https://imgs.search.brave.com/NWy6J1s9ECuViecIODR_NNt7QNOHk4RZWqYxS6Etg2E/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9pLmVi/YXlpbWcuY29tL2lt/YWdlcy9nL0s4OEFB/T1N3SC1Ob0FmMC0v/cy1sOTYwLndlYnA'],
                    ['id'=>3,'sku'=>'LLN-R18','name'=>'Neumático Deportivo R18',       'price'=>2200.00, 'category'=>'Llantas',    'img'=>'https://imgs.search.brave.com/hioDQLv9XTslxySEMvEBIJekNtVnF8gasPsVEhskUKM/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9hZS1w/aWMtYTEuYWxpZXhw/cmVzcy1tZWRpYS5j/b20va2YvUzZhNDY0/NTdjOTc2ZTQ2ZTI5/ZWI0YjI3YzkyYjFi/NGY3MC5qcGc'],
                    ['id'=>4,'sku'=>'BUJ-NGK','name'=>'Bujía de Iridio NGK',           'price'=>150.00,  'category'=>'Motor',      'img'=>'https://imgs.search.brave.com/uAGNLRKueA1UUTSinY7NlwfgV5Rfm8gGK0SIG8bTQWQ/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9jb250/ZW50aW5mby5hdXRv/em9uZS5jb20vem5l/dGNzL3Byb2R1Y3Qt/aW5mby9lcy9NWC9u/Z2svMzc2NC9pbWFn/ZS84Lw'],
                    ['id'=>5,'sku'=>'ACT-5W30','name'=>'Aceite Sintético 5W-30',       'price'=>450.00,  'category'=>'Lubricantes','img'=>'https://imgs.search.brave.com/11gmkBedlKGhEkVz9Go0HvldEuT0PBZEiWJAvftO_Z8/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9tYWxw/YS5jb20ubXgvY2Ru/L3Nob3AvcHJvZHVj/dHMvWDEwOVNQLTVX/MzAtRi01MDBweC5w/bmc_dj0xNjUxMTAx/NTQ3JndpZHRoPTE0/NDU'],
                    ['id'=>6,'sku'=>'FLT-AIR','name'=>'Filtro de Aire Mann-Filter',    'price'=>280.00,  'category'=>'Filtros',    'img'=>'https://imgs.search.brave.com/pR6jEGk7PRRqMOg5iAEa4pe-YTiYenS7P9pCeMpfuJM/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NDFpMGpLNmkwTkwu/anBn'],
                    ['id'=>7,'sku'=>'SUS-AMT','name'=>'Amortiguador Delantero KYB',    'price'=>1200.00, 'category'=>'Suspensión', 'img'=>'https://imgs.search.brave.com/A-W89YlpIXRcmZeQYDFPH7xQVV6scp6UXqQr-E5M2IU/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9odHRw/Mi5tbHN0YXRpYy5j/b20vRF9RX05QXzJY/XzcyMDM0OS1NTE0z/MTIzNDcxMzUwMl8w/NjIwMTktVi0yLWFt/b3J0aWd1YWRvcmVz/LXRveW90YS15YXJp/cy0yMDE0LTIwMTYt/a3liLWRlbGFudGVy/b3Mud2VicA'],
                    ['id'=>8,'sku'=>'BAT-12V','name'=>'Batería 12V 60Ah Bosch',        'price'=>1800.00, 'category'=>'Eléctrico',  'img'=>'https://imgs.search.brave.com/gl2cTY98gek9QMOnOB5DWPJb--yIbAcuSASZvOOX-uI/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9zdGF0/aWMubHZlbmdpbmUu/bmV0L3RleG9sZW8v/dGh1bWIvJnc9NTg4/Jmg9NTg4JnE9OTAm/c3JjPS9JbWdzL3By/b2R1dG9zL3Byb2R1/Y3RfMTUwMzMzLzAw/OTJzNDAwNDBwaDAx/d2hjbzAwMDAuanBn'],
                ];
                @endphp

                @foreach($productos_demo as $p)
                <div class="product-card"
                     data-id="{{ $p['id'] }}"
                     data-name="{{ $p['name'] }}"
                     data-price="{{ $p['price'] }}"
                     data-search="{{ strtolower($p['name'] . ' ' . $p['sku']) }}"
                     data-cat="{{ $p['category'] }}">

                    <div class="product-img-wrap">
                        <img
                            src="{{ $p['img'] }}"
                            alt="{{ $p['name'] }}"
                            class="product-img"
                            loading="lazy"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="product-img-fallback" style="display:none">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <span class="product-cat-badge">{{ $p['category'] }}</span>
                    </div>

                    <div class="product-body">
                        <p class="product-sku">{{ $p['sku'] }}</p>
                        <h4 class="product-name">{{ $p['name'] }}</h4>
                        <div class="product-footer">
                            <span class="product-price">${{ number_format($p['price'], 2) }}</span>
                            <button class="btn-add" data-id="{{ $p['id'] }}" data-name="{{ $p['name'] }}" data-price="{{ $p['price'] }}">
                                <i class="fa-solid fa-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </section>

        <!-- ── CONFIRMAR PEDIDO ── -->
        <section class="confirm-section reveal" id="confirm-section" style="--delay:.12s; display:none">

            <div class="confirm-header">
                <i class="fa-solid fa-cart-shopping"></i>
                <h2 class="confirm-title">Confirmar Pedido</h2>
            </div>

            <div class="confirm-body">

                <!-- Lista del carrito -->
                <div class="cart-col">
                    <h3 class="cart-col-label">Tu Carrito</h3>
                    <ul class="cart-lines" id="cart-lines"></ul>
                </div>

                <!-- Resumen y pago -->
                <div class="summary-col">
                    <div class="summary-rows">
                        <div class="srow">
                            <span>Subtotal</span>
                            <span id="summary-subtotal">$0.00</span>
                        </div>
                        <div class="srow">
                            <span>Envío Estimado</span>
                            <span>$25.00</span>
                        </div>
                        <div class="srow srow-total">
                            <span>Total</span>
                            <span id="summary-total">$0.00</span>
                        </div>
                    </div>

                    <form action="{{ route('client.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_data" id="cart-data-input">
                        <button type="submit" class="btn-confirm" id="btn-confirm">
                            <i class="fa-solid fa-lock"></i>
                            Confirmar y Pagar
                        </button>
                    </form>

                    <button class="btn-clear" id="btn-clear-cart">
                        <i class="fa-solid fa-trash"></i> Vaciar carrito
                    </button>
                </div>

            </div>
        </section>

    </main>
<script src="{{ asset('js/macuin.js') }}"></script>
<script src="{{ asset('js/crearPedido.js') }}"></script>
</body>
</html>