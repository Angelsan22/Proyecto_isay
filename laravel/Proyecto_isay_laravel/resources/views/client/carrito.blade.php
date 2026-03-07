<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Carrito de Compras</title>

    <!-- Fonts e Iconos -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/macAut.css') }}">
    <!-- Reutilizamos theme.css para el modo oscuro si está disponible -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script src="{{ asset('js/theme.js') }}"></script>
</head>

<body class="dashboard-page">

@php
    $carrito = [
        [
            'name' => 'Bujía de Iridio NGK',
            'sku' => 'MAC-9912',
            'price' => 150.00,
            'cantidad' => 4,
            'img' => 'https://imgs.search.brave.com/nXLKfizbHpJL1Nj0V7bYazxZMMWY3fWzKuuSugNTVJY/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly93d3cu/bWlyZWZhY2Npb24u/Y29tLm14L2Nkbi9z/aG9wL3Byb2R1Y3Rz/L2lsemthcjhqOHN5/X25na19sYXJnZV8z/ZmQ3ZmZmMy00OWI2/LTQwNmYtYTgxNi04/NDc2MTY5MjQ0ZWRf/NTMweEAyeC5wbmc_/dj0xNzEwNjA4MDM1'
        ],
        [
            'name' => 'Filtro de Aceite',
            'sku' => 'MAC-4451',
            'price' => 180.00,
            'cantidad' => 2,
            'img' => 'https://imgs.search.brave.com/9jilBVRWgjrXTUY_yIZB7wjHdSqHpgDqu3BPL2Q1wc0/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9odHRw/Mi5tbHN0YXRpYy5j/b20vRF9OUV9OUF83/MTQ1NDMtTUxNNzI5/NjQ0MTQ5OTJfMTEy/MDIzLU8ud2VicA'
        ],
        [
            'name' => 'Pastillas de Freno',
            'sku' => 'MAC-8821',
            'price' => 650.00,
            'cantidad' => 1,
            'img' => 'https://imgs.search.brave.com/NWy6J1s9ECuViecIODR_NNt7QNOHk4RZWqYxS6Etg2E/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9pLmVi/YXlpbWcuY29tL2lt/YWdlcy9nL0s4OEFB/T1N3SC1Ob0FmMC0v/cy1sOTYwLndlYnA'
        ]
    ];

    $subtotal = 0;
    foreach($carrito as $item){
        $subtotal += $item['price'] * $item['cantidad'];
    }

    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;
@endphp

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-wordmark">MAC<span>UIN</span></div>
            <p class="logo-sub">Refacciones Automotrices</p>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Menú</span>

            <a href="{{ route('client.index') }}" class="nav-link">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Inicio</span>
            </a>
            <a href="{{ route('client.catalog') }}" class="nav-link">
                <i class="fa-solid fa-layer-group"></i>
                <span>Catálogo</span>
            </a>
            <a href="{{ route('client.cart') }}" class="nav-link active">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Carrito</span>
            </a>
            <a href="{{ route('client.orders') }}" class="nav-link">
                <i class="fa-solid fa-receipt"></i>
                <span>Mis Pedidos</span>
            </a>
        </nav>

        <!-- Footer del Sidebar: Usuario, Tema y Salir -->
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
                <i class="fa-solid fa-sun icon-dark"></i>
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

    <!-- Contenido principal -->
    <main class="main-content">

        <!-- Header -->
        <header class="topbar">
            <div class="topbar-title">
                <span class="topbar-tag">Finalizar Compra</span>
                <h1 class="topbar-heading">Tu Carrito</h1>
            </div>

            <div class="topbar-actions">
                <a href="{{ route('client.catalog') }}" class="btn-cta" style="background: transparent; border: 1px solid var(--border); color: var(--text-muted);">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al Catálogo
                </a>
            </div>
        </header>

        <div class="cart-container">
            <!-- Lista de productos -->
            <section class="cart-items-section">
                <div class="cart-table-card">
                    <div class="card-header">
                        <i class="fa-solid fa-cart-flatbed"></i>
                        <h3>Productos en el carrito</h3>
                    </div>

                    <div class="cart-list">
                        @foreach($carrito as $item)
                            <div class="cart-item">
                                <div class="cart-item-img">
                                    <img src="{{ asset($item['img']) }}" width="60" alt="{{ $item['name'] }}">
                                </div>

                                <div class="cart-item-info">
                                    <h4 class="product-name">{{ $item['name'] }}</h4>
                                    <span class="product-sku">SKU: {{ $item['sku'] }}</span>
                                </div>

                                <div class="cart-item-qty">
                                    <div class="qty-control">
                                        <button class="qty-btn" type="button"><i class="fa-solid fa-minus"></i></button>
                                        <input type="number" value="{{ $item['cantidad'] }}" class="qty-input" readonly>
                                        <button class="qty-btn" type="button"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="cart-item-price">
                                    <span class="price-unit">${{ number_format($item['price'], 2) }}</span>
                                </div>

                                <div class="cart-item-remove">
                                    <button class="btn-remove" type="button">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Resumen -->
            <aside class="cart-summary-section">
                <div class="summary-card">
                    <div class="card-header">
                        <h3>Resumen del Pedido</h3>
                    </div>

                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Envío</span>
                            <span style="color: #10b981;">Gratis</span>
                        </div>
                        <div class="summary-row">
                            <span>IVA (16%)</span>
                            <span>${{ number_format($iva, 2) }}</span>
                        </div>
                        <hr style="border: 0; border-top: 1px solid var(--border); margin: 15px 0;">
                        <div class="summary-row total-row" style="font-weight: 800; font-size: 1.25rem;">
                            <span>Total</span>
                            <span class="total-amount">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('client.orders.store') }}" method="POST" class="checkout-form">
                        @csrf
                        <button type="submit" class="btn-cta" style="width: 100%; justify-content: center; margin-top: 20px;">
                            <i class="fa-solid fa-credit-card"></i>
                            Confirmar Pedido
                        </button>
                    </form>
                </div>

                <div class="secure-badge" style="display: flex; align-items: center; gap: 10px; margin-top: 20px; color: var(--text-muted); font-size: 0.85rem; justify-content: center;">
                    <i class="fa-solid fa-shield-halved" style="color: #38bdf8;"></i>
                    <span>Compra protegida por MACUIN</span>
                </div>
            </aside>
        </div>
    </main>

</body>
</html>