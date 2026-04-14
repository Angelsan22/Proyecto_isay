@extends('clientes.layout')
@section('title', 'Crear Pedido — Maccuin')

@push('styles')
<style>
    /* ── Page Header ─────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 2rem;
    }
    .page-header .icon-box {
        width: 60px;
        height: 60px;
        background: var(--naranja);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(232, 103, 27, 0.3);
        flex-shrink: 0;
    }
    .page-header .icon-box i { color: white; font-size: 1.5rem; }
    .page-header h1 {
        font-weight: 900;
        font-size: 1.6rem;
        color: var(--text-main);
        letter-spacing: -0.5px;
        margin: 0;
    }
    .page-header p {
        color: var(--text-muted);
        font-weight: 500;
        margin: 0;
        font-size: 0.9rem;
    }

    /* ── Section Cards ───────────────────────── */
    .section-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        padding: 2rem;
        box-shadow: var(--shadow-soft);
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-weight: 800;
        font-size: 1.1rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
    }
    .section-title i { color: var(--naranja); font-size: 1.2rem; }
    .section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: var(--border-color);
        border-radius: 2px;
    }

    /* ── Search Bar ───────────────────────────── */
    .search-products {
        border-radius: 16px;
        padding: 12px 18px;
        border: 2px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        transition: 0.3s;
    }
    .search-products:focus {
        border-color: var(--naranja);
        background: var(--bg-card);
        box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.1);
        outline: none;
    }
    .search-products::placeholder { color: var(--text-muted); }
    .search-wrap .input-group-text {
        background: var(--bg-body);
        border: 2px solid var(--border-color);
        border-right: none;
        border-radius: 16px 0 0 16px;
        color: var(--text-muted);
    }
    .search-wrap .search-products {
        border-left: none;
        border-radius: 0 16px 16px 0;
    }

    /* ── Product Cards ───────────────────────── */
    .product-pick {
        background: var(--bg-body);
        border: 2px solid var(--border-color);
        border-radius: 22px;
        padding: 1.2rem;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .product-pick:hover {
        border-color: var(--naranja);
        box-shadow: 0 8px 25px rgba(232, 103, 27, 0.12);
        transform: translateY(-4px);
    }
    .product-pick img {
        height: 80px;
        object-fit: contain;
        margin-bottom: 10px;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
    }
    .product-pick .name {
        font-weight: 700;
        font-size: 0.82rem;
        color: var(--text-main);
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .product-pick .price {
        font-weight: 900;
        font-size: 1rem;
        color: var(--naranja);
        margin-bottom: 10px;
    }
    .btn-add {
        background: var(--naranja);
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 8px 16px;
        border-radius: 12px;
        transition: 0.3s;
        margin-top: auto;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-add:hover {
        background: var(--naranja-hover, #c95510);
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(232, 103, 27, 0.3);
    }

    /* ── Cart Summary ────────────────────────── */
    .cart-list {
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.9rem;
        min-height: 60px;
    }
    .cart-list .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .cart-list .cart-item:last-child { border-bottom: none; }
    .cart-list .cart-item .item-name {
        font-weight: 600;
        color: var(--text-main);
    }
    .cart-list .cart-item .item-price {
        font-weight: 800;
        color: var(--naranja);
    }

    /* ── Summary Table ───────────────────────── */
    .summary-table {
        width: 100%;
        font-size: 0.95rem;
    }
    .summary-table td {
        padding: 10px 0;
        color: var(--text-muted);
        font-weight: 600;
    }
    .summary-table td:last-child {
        text-align: right;
        font-weight: 800;
        color: var(--text-main);
    }
    .summary-table .total-row td {
        border-top: 2px solid var(--border-color);
        padding-top: 15px;
        font-weight: 900;
        font-size: 1.15rem;
    }
    .summary-table .total-row td:last-child {
        color: var(--naranja);
    }

    .divider {
        border: none;
        border-top: 1px solid var(--border-color);
        margin: 1.5rem 0;
    }

    /* ── Pay Button ──────────────────────────── */
    .btn-pay {
        background: linear-gradient(135deg, var(--naranja) 0%, #d45a15 100%);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 18px;
        font-weight: 900;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(232, 103, 27, 0.35);
    }
    .btn-pay:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(232, 103, 27, 0.45);
    }
    .btn-pay:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ── Empty Cart Icon ─────────────────────── */
    .empty-cart {
        text-align: center;
        padding: 1.5rem 0;
    }
    .empty-cart i {
        font-size: 2rem;
        color: var(--border-color);
        margin-bottom: 8px;
        display: block;
    }
    .empty-cart span {
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width:850px;">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="icon-box">
            <i class="bi bi-cart-plus-fill"></i>
        </div>
        <div>
            <h1>Crear Pedido</h1>
            <p>Selecciona productos y confirma tu compra</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: Products --}}
        <div class="col-lg-7">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-search"></i> Agregar Productos
                </div>

                <div class="input-group search-wrap mb-4">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-products"
                           placeholder="Buscar por nombre o SKU…" id="searchInput"
                           oninput="filtrarProductos(this.value)">
                </div>

                <div class="row row-cols-2 row-cols-md-2 g-3" id="productGrid">
                    @foreach($autopartes as $ap)
                        <div class="col product-col" data-name="{{ strtolower($ap->nombre) }}">
                            <div class="product-pick">
                                <img src="{{ $ap->imagen_url }}" alt="{{ $ap->nombre }}">
                                <div class="name">{{ $ap->nombre }}</div>
                                <div class="price">${{ number_format($ap->precio, 2) }}</div>
                                <button class="btn-add"
                                        onclick="agregarItem('{{ $ap->nombre }}', {{ $ap->precio }})">
                                    <i class="bi bi-plus-lg me-1"></i> Agregar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Cart Summary --}}
        <div class="col-lg-5">
            <div class="section-card" style="position:sticky; top:100px;">
                <div class="section-title">
                    <i class="bi bi-receipt"></i> Resumen del Pedido
                </div>

                <div class="cart-list" id="lista-carrito">
                    <div class="empty-cart">
                        <i class="bi bi-cart-x"></i>
                        <span>Sin productos aún</span>
                    </div>
                </div>

                <hr class="divider">

                <table class="summary-table">
                    <tr>
                        <td>Subtotal</td>
                        <td id="txt-subtotal">$0.00</td>
                    </tr>
                    <tr>
                        <td>Envío Estimado</td>
                        <td>$25.00</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total</td>
                        <td id="txt-total">$25.00</td>
                    </tr>
                </table>

                <hr class="divider">

                <button type="button" class="btn btn-pay w-100" id="btn-confirmar"
                        onclick="alert('Funcionalidad próximamente')">
                    <i class="bi bi-credit-card me-2"></i> Confirmar y Pagar
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let carrito = [];
    const ENVIO = 25;

    function agregarItem(nombre, precio) {
        const existe = carrito.find(i => i.nombre === nombre);
        if (existe) { existe.cantidad++; }
        else { carrito.push({ nombre, precio, cantidad: 1 }); }
        renderCarrito();
    }

    function eliminarItem(nombre) {
        carrito = carrito.filter(i => i.nombre !== nombre);
        renderCarrito();
    }

    function renderCarrito() {
        const lista    = document.getElementById('lista-carrito');
        const subtotal = carrito.reduce((s, i) => s + i.precio * i.cantidad, 0);

        if (carrito.length === 0) {
            lista.innerHTML = `<div class="empty-cart"><i class="bi bi-cart-x"></i><span>Sin productos aún</span></div>`;
        } else {
            lista.innerHTML = carrito.map(i =>
                `<div class="cart-item">
                    <div>
                        <span class="item-name">${i.cantidad}× ${i.nombre}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="item-price">$${(i.precio * i.cantidad).toFixed(2)}</span>
                        <button onclick="eliminarItem('${i.nombre}')" class="btn btn-sm border-0 text-danger" style="padding:2px 6px; font-size:0.8rem;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>`
            ).join('');
        }

        document.getElementById('txt-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('txt-total').textContent    = `$${(subtotal + ENVIO).toFixed(2)}`;
    }

    function filtrarProductos(query) {
        const cols = document.querySelectorAll('.product-col');
        const q = query.toLowerCase();
        cols.forEach(col => {
            col.style.display = col.dataset.name.includes(q) ? '' : 'none';
        });
    }
</script>
@endpush
