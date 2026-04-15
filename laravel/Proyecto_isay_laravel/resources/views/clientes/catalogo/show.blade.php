@extends('clientes.layout')
@section('title', $autoparte->nombre . ' — Maccuin')

@push('styles')
<style>
    .product-detail-container {
        background: var(--bg-card);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-color);
    }
    .img-container {
        background: var(--bg-body);
        padding: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 480px;
        border-right: 1px solid var(--border-color);
    }
    .product-info-panel { padding: 50px; }

    .breadcrumb-custom {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--naranja);
        margin-bottom: 15px;
        display: block;
    }
    .product-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--text-main);
        line-height: 1.05;
        margin-bottom: 25px;
        letter-spacing: -1.5px;
    }
    .sku-badge {
        background: var(--nav-bg);
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 0.85rem;
        color: white;
        font-weight: 700;
    }
    .price-large {
        font-size: 3.2rem;
        font-weight: 950;
        color: var(--naranja);
        margin: 30px 0;
        line-height: 1;
    }
    .price-currency {
        font-size: 1.1rem;
        font-weight: 700;
        vertical-align: super;
        margin-left: 8px;
        opacity: 0.5;
    }
    .spec-card {
        background: var(--bg-body);
        border-radius: 20px;
        padding: 28px;
        margin-top: 30px;
        border: 1px solid var(--border-color);
    }

    /* Quantity Selector */
    .qty-selector {
        background: var(--bg-body);
        border-radius: 16px;
        padding: 6px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        border: 2px solid var(--border-color);
    }
    .qty-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--bg-card);
        color: var(--text-main);
        font-weight: 900;
        transition: 0.3s;
        font-size: 1.1rem;
        cursor: pointer;
    }
    .qty-btn:hover { background: var(--naranja); color: white; transform: scale(1.08); border-color: var(--naranja); }

    .btn-buy {
        background: var(--naranja);
        color: white;
        border: none;
        padding: 18px 40px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: 0.3s;
        box-shadow: 0 12px 30px rgba(232, 103, 27, 0.3);
        font-size: 1rem;
        text-decoration: none;
    }
    .btn-buy:hover {
        background: var(--oscuro);
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        color: white;
    }

    .back-link {
        color: var(--text-muted);
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .back-link:hover { color: var(--naranja); }

    @media (max-width: 992px) {
        .img-container { min-height: 300px; border-right: none; border-bottom: 1px solid var(--border-color); }
        .product-info-panel { padding: 30px; }
        .product-title { font-size: 2rem; }
        .price-large { font-size: 2.5rem; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('cliente.catalogo.index') }}" class="back-link">
            <i class="bi bi-arrow-left text-naranja"></i> VOLVER AL CATÁLOGO
        </a>
    </div>

    <div class="product-detail-container">
        <div class="row g-0">
            {{-- Columna Imagen --}}
            <div class="col-lg-6">
                <div class="img-container">
                    <img src="{{ $autoparte->imagen_url ?? 'https://placehold.co/500x500?text=Sin+imagen' }}"
                         class="img-fluid" style="max-height:450px; object-fit:contain;" alt="{{ $autoparte->nombre }}">
                </div>
            </div>

            {{-- Columna Info --}}
            <div class="col-lg-6">
                <div class="product-info-panel">
                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                        <span class="breadcrumb-custom">{{ $autoparte->categoria->nombre ?? 'Refacción General' }}</span>
                        <span class="sku-badge">SKU: {{ $autoparte->sku }}</span>
                    </div>

                    <h1 class="product-title">{{ $autoparte->nombre }}</h1>

                    <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                        <span class="badge fw-bold px-3 py-2 rounded-pill" style="background:var(--naranja-soft, rgba(232,103,27,0.1)); color:var(--naranja); border:1px solid rgba(232,103,27,0.2);">
                            PIEZA OFICIAL MACCUIN
                        </span>
                        <span class="fw-bold" style="color:var(--text-muted);">Marca: <span class="text-naranja">{{ $autoparte->marca->nombre ?? 'Genérica' }}</span></span>
                    </div>

                    <div class="price-large">
                        ${{ number_format($autoparte->precio, 2) }}<span class="price-currency">MXN</span>
                    </div>

                    <div class="mb-4">
                        @if(($autoparte->stock ?? 0) <= 0)
                            <div class="d-inline-flex align-items-center bg-danger bg-opacity-10 text-danger fw-black small px-4 py-2 rounded-pill border border-danger" style="font-size:0.8rem;">
                                <i class="bi bi-x-circle me-2 fs-5"></i> PRODUCTO AGOTADO
                            </div>
                        @elseif(($autoparte->stock ?? 0) <= ($autoparte->stock_minimo ?? 5))
                            <div class="d-inline-flex align-items-center bg-warning bg-opacity-10 text-warning fw-black small px-4 py-2 rounded-pill border border-warning" style="font-size:0.8rem; color:#d97706 !important; border-color:#d97706 !important;">
                                <i class="bi bi-exclamation-triangle me-2 fs-5"></i> ÚLTIMAS {{ $autoparte->stock }} PIEZAS DISPONIBLES
                            </div>
                        @else
                            <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success fw-black small px-4 py-2 rounded-pill border border-success" style="font-size:0.8rem;">
                                <i class="bi bi-check2-circle me-2 fs-5"></i> EN STOCK: {{ $autoparte->stock }} UNIDADES
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('cliente.carrito.agregar') }}" method="POST" class="d-flex flex-wrap align-items-center gap-4 mt-4">
                        @csrf
                        <input type="hidden" name="autoparte_id" value="{{ $autoparte->id }}">
                        
                        <div class="qty-selector shadow-sm {{ ($autoparte->stock ?? 0) <= 0 ? 'opacity-50' : '' }}" style="{{ ($autoparte->stock ?? 0) <= 0 ? 'pointer-events:none;' : '' }}">
                            <button class="qty-btn" type="button" onclick="cambiar(-1)">−</button>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="{{ $autoparte->stock ?? 99 }}" class="border-0 bg-transparent text-center fw-black fs-5" style="width:45px; color:var(--text-main);" readonly>
                            <button class="qty-btn" type="button" onclick="cambiar(1)">+</button>
                        </div>
                        
                        <button type="submit" class="btn-buy flex-grow-1 text-center {{ ($autoparte->stock ?? 0) <= 0 ? 'opacity-50' : '' }}" {{ ($autoparte->stock ?? 0) <= 0 ? 'disabled' : '' }}>
                            @if(($autoparte->stock ?? 0) <= 0)
                                <i class="bi bi-slash-circle me-2"></i> No Disponible
                            @else
                                <i class="bi bi-cart-plus-fill me-2"></i> Añadir al Carrito
                            @endif
                        </button>
                    </form>

                    <div class="spec-card">
                        <div class="d-flex align-items-center gap-2 mb-3 text-naranja">
                            <i class="bi bi-shield-check fs-4"></i>
                            <h5 class="fw-bold mb-0">Descripción del Producto</h5>
                        </div>
                        <p class="mb-0 fw-medium" style="line-height:1.8; font-size:0.95rem; color:var(--text-muted);">
                            {{ $autoparte->descripcion ?? "Esta autoparte ha sido verificada bajo los más estrictos controles de calidad automotriz, asegurando una compatibilidad total y vida útil extendida bajo condiciones de uso rudo." }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cambiar(delta) {
        const input = document.getElementById('cantidad');
        const nuevo = parseInt(input.value) + delta;
        if (nuevo >= 1) input.value = nuevo;
    }
</script>
@endpush
