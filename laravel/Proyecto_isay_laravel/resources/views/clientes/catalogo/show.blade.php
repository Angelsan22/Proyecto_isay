@extends('clientes.layout')
@section('title', $autoparte->nombre)

@push('styles')
<style>
    .product-detail-container {
        background: var(--bg-card);
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
    }
    .img-container {
        background: rgba(232, 103, 27, 0.05);
        padding: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 500px;
        border-right: 1px solid var(--border-color);
    }
    .product-info-panel {
        padding: 60px;
    }
    .breadcrumb-custom {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--naranja);
        margin-bottom: 20px;
        display: block;
    }
    .product-title {
        font-size: 3rem;
        font-weight: 900;
        color: var(--text-main);
        line-height: 1;
        margin-bottom: 30px;
        letter-spacing: -2px;
    }
    .sku-badge {
        background: var(--nav-bg);
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        color: white;
        font-weight: 700;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .price-large {
        font-size: 4rem;
        font-weight: 950;
        color: var(--naranja);
        margin: 35px 0;
        line-height: 1;
    }
    .price-currency {
        font-size: 1.2rem;
        font-weight: 700;
        vertical-align: super;
        margin-left: 10px;
        opacity: 0.6;
    }
    .spec-card {
        background: rgba(0,0,0,0.02);
        border-radius: 20px;
        padding: 30px;
        margin-top: 40px;
        border: 1px solid var(--border-color);
    }
    .qty-selector {
        background: var(--bg-body);
        border-radius: 18px;
        padding: 8px;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        border: 2px solid var(--border-color);
    }
    .qty-btn {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        font-weight: 900;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        font-size: 1.2rem;
    }
    .qty-btn:hover { background: var(--naranja); color: white; transform: scale(1.1); }
    
    .btn-buy {
        background: var(--naranja);
        color: white;
        border: none;
        padding: 20px 50px;
        border-radius: 20px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
        box-shadow: 0 15px 30px rgba(232, 103, 27, 0.3);
        font-size: 1.1rem;
    }
    .btn-buy:hover {
        background: var(--oscuro);
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-link text-decoration-none p-0 fw-bold fs-5" style="color: var(--text-muted); transition: 0.3s;" onmouseover="this.style.color='var(--naranja)'" onmouseout="this.style.color='var(--text-muted)'">
            <i class="bi bi-arrow-left text-naranja me-2"></i> VOLVER AL CATÁLOGO
        </a>
    </div>

    <div class="product-detail-container pb-0">
        <div class="row g-0">
            <!-- Columna Imagen -->
            <div class="col-lg-6">
                <div class="img-container">
                    <img src="{{ $autoparte->imagen_url ?? 'https://placehold.co/500x500?text=Sin+imagen' }}"
                         class="img-fluid" style="max-height: 550px; object-fit: contain;" alt="{{ $autoparte->nombre }}">
                </div>
            </div>

            <!-- Columna Info -->
            <div class="col-lg-6">
                <div class="product-info-panel">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="breadcrumb-custom">{{ $autoparte->categoria->nombre ?? 'Refacción General' }}</span>
                        <span class="sku-badge">SKU: {{ $autoparte->sku }}</span>
                    </div>
                    
                    <h1 class="product-title">{{ $autoparte->nombre }}</h1>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="badge bg-naranja-soft text-naranja fw-bold px-3 py-2 rounded-pill border border-naranja" style="background: rgba(232, 103, 27, 0.1);">
                            PIEZA OFICIAL MACUIN
                        </span>
                        <span class="fw-bold" style="color: var(--text-muted);">Marca: <span class="text-naranja">{{ $autoparte->marca->nombre ?? 'Genérica' }}</span></span>
                    </div>

                    <div class="price-large">
                        ${{ number_format($autoparte->precio, 2) }}<span class="price-currency">MXN</span>
                    </div>

                    <div class="mb-5">
                        @if(($autoparte->stock ?? 0) > 0)
                            <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success fw-black small px-4 py-2 rounded-pill border border-success">
                                <i class="bi bi-check2-circle me-2 fs-5"></i> EN STOCK: {{ $autoparte->stock }} UNIDADES
                            </div>
                        @else
                            <div class="d-inline-flex align-items-center bg-danger bg-opacity-10 text-danger fw-black small px-4 py-2 rounded-pill border border-danger">
                                <i class="bi bi-x-circle me-2 fs-5"></i> PRODUCTO AGOTADO
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-4 mt-5">
                        <div class="qty-selector shadow-sm">
                            <button class="qty-btn" type="button" onclick="cambiar(-1)">−</button>
                            <input type="number" id="cantidad" value="1" min="1" class="border-0 bg-transparent text-center fw-black fs-4" style="width: 50px; color: var(--text-main);" readonly>
                            <button class="qty-btn" type="button" onclick="cambiar(1)">+</button>
                        </div>
                        <a href="{{ route('cliente.pedidos.crear') }}" class="btn-buy flex-grow-1 text-center text-decoration-none">
                            <i class="bi bi-cart-plus-fill me-2"></i> Añadir al Carrito
                        </a>
                    </div>

                    <div class="spec-card mt-5">
                        <div class="d-flex align-items-center gap-2 mb-3 text-naranja">
                            <i class="bi bi-shield-check fs-4"></i>
                            <h5 class="fw-bold mb-0">Descripción del Producto</h5>
                        </div>
                        <p class="mb-0 fw-medium" style="line-height: 1.8; font-size: 1rem; color: var(--text-muted);">
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
