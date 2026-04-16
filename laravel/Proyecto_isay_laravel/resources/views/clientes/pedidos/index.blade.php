@extends('clientes.layout')
@section('title', 'Mis Pedidos — Maccuin')

@push('styles')
<style>
    .order-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color) !important;
        border-radius: 28px;
        padding: 32px;
        margin-bottom: 28px;
        box-shadow: var(--shadow-soft);
        transition: box-shadow 0.3s ease;
    }
    .order-card:hover { box-shadow: var(--shadow-hover); }

    .order-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 18px;
        margin-bottom: 22px;
    }
    .order-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 4px;
    }
    .order-value {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
    }

    .product-img-box {
        width: 90px;
        height: 90px;
        border-radius: 18px;
        border: 2px solid var(--border-color);
        background: var(--bg-body);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }
    .product-img-box img { width: 70px; height: 70px; object-fit: contain; }

    .order-item-row {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 16px 0;
    }
    .order-item-row + .order-item-row { border-top: 1px solid var(--border-color); }

    .item-info { flex: 1; }
    .item-name { font-weight: 800; font-size: 1rem; color: var(--text-main); margin-bottom: 6px; }
    .item-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    .qty-pill {
        background: var(--naranja-soft, rgba(232,103,27,0.1));
        color: var(--naranja);
        border: 1px solid rgba(232,103,27,0.2);
        padding: 4px 14px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.75rem;
    }
    .subtotal-text { font-weight: 700; font-size: 0.88rem; color: var(--text-muted); }
    .subtotal-amount { color: var(--text-main); font-weight: 800; }

    .order-footer {
        border-top: 1px solid var(--border-color);
        margin-top: 20px;
        padding-top: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .total-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        display: block;
    }
    .total-amount {
        font-size: 1.7rem;
        font-weight: 950;
        color: var(--naranja);
        line-height: 1;
    }
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width:960px;">

    <div class="d-flex align-items-center gap-4 mb-5">
        <div style="width:60px; height:60px; background:var(--naranja); border-radius:20px; display:flex; align-items:center; justify-content:center; box-shadow:0 10px 25px rgba(232,103,27,0.3);">
            <i class="bi bi-box-seam fs-2 text-white"></i>
        </div>
        <div>
            <h1 class="fw-bold mb-1" style="letter-spacing:-1px; color:var(--text-main); font-size:1.6rem;">Mis Pedidos</h1>
            <p class="mb-0 fw-medium" style="color:var(--text-muted); font-size:0.9rem;">Gestiona y rastrea tus compras de autopartes</p>
        </div>
    </div>

    @forelse($pedidos as $pedido)
        <div class="order-card">

            <div class="order-header d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <span class="order-label">Número de Pedido</span>
                    <p class="order-value">#{{ $pedido->id }}</p>
                </div>
                <div class="text-end">
                    <span class="order-label">Fecha de Compra</span>
                    <p class="order-value text-naranja">{{ $pedido->created_at->format('d M, Y') }}</p>
                </div>
            </div>

            @foreach($pedido->detalles as $detalle)
                <div class="order-item-row">
                    <div class="product-img-box">
                        <img src="{{ $detalle->autoparte->imagen_url ?? 'https://placehold.co/80x80/e8671b/ffffff?text=' }}"
                             alt="{{ $detalle->autoparte->nombre ?? 'Pieza' }}">
                    </div>
                    <div class="item-info">
                        <p class="item-name">{{ $detalle->autoparte->nombre ?? 'Autoparte' }}</p>
                        <div class="item-meta">
                            <span class="qty-pill">× {{ $detalle->cantidad ?? 1 }} unidades</span>
                            <span class="subtotal-text">Subtotal: <span class="subtotal-amount">${{ number_format($detalle->subtotal, 2) }} MXN</span></span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        @php
                            $statusStyle = match($pedido->estado) {
                                'entregado' => 'background:#10b981; color:white;',
                                'en_camino' => 'background:#f59e0b; color:#1a1a1a;',
                                'confirmado' => 'background:#3b82f6; color:white;',
                                'cancelado'  => 'background:#ef4444; color:white;',
                                default      => 'background:#64748b; color:white;',
                            };
                            $statusLabel = match($pedido->estado) {
                                'entregado' => ' ENTREGADO',
                                'en_camino' => ' EN CAMINO',
                                'confirmado'=> '● CONFIRMADO',
                                'cancelado' => ' CANCELADO',
                                default     => '○ PENDIENTE',
                            };
                        @endphp
                        <span class="px-4 py-2 rounded-pill fw-black" style="{{ $statusStyle }} font-size:0.75rem; letter-spacing:0.5px;">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            @endforeach

            <div class="order-footer">
                <div>
                    <span class="total-label">Total del Pedido</span>
                    <div class="total-amount">${{ number_format($pedido->detalles->sum('subtotal'), 2) }} <span style="font-size:0.9rem; font-weight:700; opacity:0.5;">MXN</span></div>
                </div>
                <a href="{{ route('cliente.pedidos.show', $pedido->id) }}"
                   class="btn btn-naranja px-5 rounded-pill fw-bold" style="padding:12px 30px;">
                    <i class="bi bi-eye me-2"></i> Ver Detalles
                </a>
            </div>

        </div>
    @empty
        <div class="text-center py-5 card-macuin" style="border-radius:28px; padding:3rem;">
            <div style="width:90px; height:90px; background:var(--naranja-soft, rgba(232,103,27,0.1)); border-radius:24px; margin:0 auto 25px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-cart-x" style="font-size:2.2rem; color:var(--naranja);"></i>
            </div>
            <h3 class="fw-bold" style="color:var(--text-main);">Aún no has realizado pedidos</h3>
            <p class="fw-medium mb-4" style="color:var(--text-muted);">Explora nuestro catálogo y encuentra las mejores piezas para tu vehículo.</p>
            <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-naranja px-5 py-3 rounded-pill fw-bold">Ir al catálogo</a>
        </div>
    @endforelse

</div>
@endsection
