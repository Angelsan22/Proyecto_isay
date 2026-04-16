@extends('clientes.layout')
@section('title', 'Crear Pedido — Maccuin')

@push('styles')
<style>
    
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

    
    .search-wrap {
        border: 2px solid var(--border-color);
        border-radius: 18px;
        background: var(--bg-body);
        transition: 0.3s;
        overflow: hidden;
    }
    .search-wrap:focus-within {
        border-color: var(--naranja);
        box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.15);
        background: #111 !important; 
    }
    .search-wrap .input-group-text {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding-left: 18px;
    }
    .search-products {
        border: none !important;
        background: transparent !important;
        color: #ffffff !important;
        padding: 12px 15px;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        box-shadow: none !important;
    }
    .search-products:focus {
        background: transparent !important;
        color: #ffffff !important;
    }
    .search-products::placeholder { color: var(--text-muted); opacity: 0.7; }

    
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

    
    .cart-list {
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.9rem;
        min-height: 60px;
    }
    .cart-list .cart-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .cart-list .cart-item:last-child { border-bottom: none; }
    
    .cart-item-img {
        width: 85px;
        height: 85px;
        border-radius: 16px;
        object-fit: contain;
        background: var(--bg-body);
        padding: 8px;
        border: 1px solid var(--border-color);
        flex-shrink: 0;
    }
    
    .cart-item-info { flex: 1; min-width: 0; }
    
    .cart-list .cart-item .item-name {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-main);
        display: block;
        margin-bottom: 5px;
    }
    .cart-list .cart-item .item-price {
        font-weight: 800;
        font-size: 0.9rem;
        color: var(--naranja);
        display: block;
    }

    
    .cart-qty-ctrl {
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 2px 4px;
        border-radius: 8px;
    }
    .cart-qty-btn {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        border: none;
        background: var(--bg-body);
        color: var(--text-main);
        font-size: 0.8rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .cart-qty-btn:hover {
        background: var(--naranja);
        color: white;
    }
    .cart-qty-val {
        font-weight: 800;
        font-size: 0.85rem;
        min-width: 18px;
        text-align: center;
    }
    .btn-trash {
        color: #ef4444;
        opacity: 0.6;
        transition: 0.3s;
        border: none;
        background: none;
        padding: 4px;
    }
    .btn-trash:hover {
        opacity: 1;
        transform: scale(1.1);
        color: #dc2626;
    }

    
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

    
    .catalog-card-container { position: relative; }

    .ribbon-stock {
        position: absolute;
        top: 15px;
        left: 10px;
        z-index: 10;
        padding: 5px 12px;
        font-size: 0.60rem;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: white;
        border-radius: 0 50px 50px 0;
        box-shadow: 3px 3px 10px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        gap: 6px;
        pointer-events: none;
    }

    .ribbon-low-stock {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-left: 3px solid #b45309;
    }

    .ribbon-out-of-stock {
        background: linear-gradient(135deg, #374151 0%, #111827 100%);
        border-left: 3px solid #000;
    }

    .stock-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: white;
        display: inline-block;
        box-shadow: 0 0 6px rgba(255,255,255,1);
        animation: pulse-stock 2s infinite;
    }

    @keyframes pulse-stock {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.4); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }

    .card-out-of-stock {
        filter: grayscale(0.8) opacity(0.7);
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width:850px;">

    
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
                        <div class="col product-col catalog-card-container" data-name="{{ strtolower($ap->nombre) }}">
                            @if($ap->stock_actual <= 0)
                                <div class="ribbon-stock ribbon-out-of-stock">
                                    <span class="stock-dot" style="background:#ef4444; box-shadow:0 0 8px #ef4444;"></span> Agotado
                                </div>
                            @elseif($ap->stock_actual <= ($ap->stock_minimo ?? 5))
                                <div class="ribbon-stock ribbon-low-stock">
                                    <span class="stock-dot"></span> Últimas {{ $ap->stock_actual }}
                                </div>
                            @endif

                            <div class="product-pick {{ $ap->stock_actual <= 0 ? 'card-out-of-stock' : '' }}">
                                <img src="{{ $ap->imagen_url }}" alt="{{ $ap->nombre }}">
                                <div class="name">{{ $ap->nombre }}</div>
                                <div class="price">${{ number_format($ap->precio, 2) }}</div>
                                <button type="button" class="btn-add w-100" onclick="agregarItemDirecto({{ $ap->id }}, '{{ addslashes($ap->nombre) }}', {{ $ap->precio }}, '{{ $ap->imagen_url }}', {{ $ap->stock_actual ?? 0 }})">
                                    <i class="bi bi-plus-lg me-1"></i> Agregar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        
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
                        <td>IVA (16%)</td>
                        <td id="txt-iva">$0.00</td>
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

                <form action="{{ route('cliente.pedidos.checkout') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-pay w-100" id="btn-confirmar"
                        @if(!session()->has('carrito') || count(session('carrito')) == 0) disabled @endif>
                        <i class="bi bi-credit-card me-2"></i> Confirmar y Pagar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>



<input type="hidden" name="_token" value="{{ csrf_token() }}">

@endsection

@push('scripts')
<script>
    let carrito = @json($itemsCarrito);
    const ENVIO = 25;

    function agregarItem(nombre, precio) {
        const existe = carrito.find(i => i.nombre === nombre);
        if (existe) { existe.cantidad++; }
        else { carrito.push({ nombre, precio, cantidad: 1 }); }
        renderCarrito();
    }

    function updateQty(id, newQty) {
        if (newQty < 1) return;
        const item = carrito.find(i => i.id === id);
        if (item) {
            if (newQty > item.stock) {
                alert("¡Has alcanzado el límite de existencias para este producto!");
                return;
            }
            item.cantidad = newQty;
            renderCarrito();
        }
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('autoparte_id', id);
        formData.append('cantidad', newQty);

        fetch("{{ route('cliente.carrito.actualizar') }}", {
            method: "POST",
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
    }

    function removeItem(id) {
        carrito = carrito.filter(i => i.id !== id);
        renderCarrito();

        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('autoparte_id', id);

        fetch("{{ route('cliente.carrito.eliminar') }}", {
            method: "POST",
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
    }

    function agregarItemDirecto(id, nombre, precio, imagen_url, stock_actual) {
        if (stock_actual < 1) {
            alert("Producto agotado.");
            return;
        }

        const item = carrito.find(i => i.id === id);
        if (item) {
            if (item.cantidad >= item.stock) {
                alert("No hay más unidades en existencia.");
                return;
            }
            item.cantidad++;
        } else {
            carrito.push({ id, nombre, precio, cantidad: 1, imagen: imagen_url, stock: stock_actual });
        }
        renderCarrito();

        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('autoparte_id', id);
        formData.append('cantidad', 1);

        fetch("{{ route('cliente.carrito.agregar') }}", {
            method: "POST",
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
    }

    function renderCarrito() {
        const lista    = document.getElementById('lista-carrito');
        const subtotal = carrito.reduce((s, i) => s + i.precio * i.cantidad, 0);
        const iva      = subtotal * 0.16;
        const total    = subtotal + iva + ENVIO;

        if (carrito.length === 0) {
            lista.innerHTML = `<div class="empty-cart"><i class="bi bi-cart-x"></i><span>Sin productos aún</span></div>`;
        } else {
            lista.innerHTML = carrito.map(i =>
                `<div class="cart-item d-flex align-items-center gap-3">
                    <img src="${i.imagen}" class="cart-item-img" alt="${i.nombre}">
                    
                    <div class="cart-item-info flex-1 d-flex flex-column" style="height: 85px; justify-content: space-between;">
                        <div>
                            <span class="item-name m-0" style="white-space:normal; line-height:1.1; font-size:0.9rem; font-weight:800;">${i.nombre}</span>
                            <div class="item-price" style="font-size:0.95rem; font-weight:800; margin-top:4px;">$${(i.precio * i.cantidad).toFixed(2)}</div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div class="cart-qty-ctrl">
                                <button type="button" class="cart-qty-btn" onclick="updateQty(${i.id}, ${i.cantidad - 1})">−</button>
                                <span class="cart-qty-val">${i.cantidad}</span>
                                <button type="button" class="cart-qty-btn" onclick="updateQty(${i.id}, ${i.cantidad + 1})">+</button>
                            </div>
                            <button onclick="removeItem(${i.id})" class="btn-trash p-0" title="Eliminar">
                                <i class="bi bi-trash3-fill" style="font-size:1.1rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>`
            ).join('');
        }

        document.getElementById('txt-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('txt-iva').textContent      = `$${iva.toFixed(2)}`;
        document.getElementById('txt-total').textContent    = `$${total.toFixed(2)}`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCarrito();
    });

    function filtrarProductos(query) {
        const cols = document.querySelectorAll('.product-col');
        const q = query.toLowerCase();
        cols.forEach(col => {
            col.style.display = col.dataset.name.includes(q) ? '' : 'none';
        });
    }
</script>
@endpush
