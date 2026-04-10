@extends('clientes.layout')
@section('title', 'Detalle del Pedido #{{ $pedido->id }}')

@push('styles')
<style>
    .detail-hero {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 36px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0,0,0,0.08);
    }

    /* Top banner with order ID */
    .detail-banner {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d1a0a 100%);
        padding: 40px 50px;
        position: relative;
        overflow: hidden;
    }
    .detail-banner::after {
        content: '#{{ $pedido->id }}';
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 6rem;
        font-weight: 950;
        opacity: 0.06;
        color: white;
        letter-spacing: -5px;
        pointer-events: none;
    }
    .detail-banner h1 {
        color: white;
        font-size: 1.9rem;
        font-weight: 900;
        letter-spacing: -1px;
        margin: 0 0 6px;
    }
    .detail-banner p {
        margin: 0;
        color: rgba(255,255,255,0.55);
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Info grid */
    .detail-body { padding: 45px 50px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr 1fr; }
        .detail-body { padding: 30px 25px; }
        .detail-banner { padding: 30px 25px; }
    }
    .info-tile {
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        padding: 22px;
    }
    .info-tile-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 8px;
    }
    .info-tile-value {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
    }

    /* Status pill */
    .status-entregado { background:#10b981; color:white; }
    .status-en_camino  { background:#f59e0b; color:#1a1a1a; }
    .status-confirmado { background:#3b82f6; color:white; }
    .status-pendiente  { background:#64748b; color:white; }

    /* Items table */
    .items-section-title {
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--naranja);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .items-section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: rgba(232,103,27,0.15);
        border-radius: 2px;
    }

    .item-row {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 18px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .item-row:last-child { border-bottom: none; }

    .item-img {
        width: 90px;
        height: 90px;
        flex-shrink: 0;
        border-radius: 18px;
        border: 2px solid var(--border-color);
        background: var(--bg-body);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .item-img img { width: 70px; height: 70px; object-fit: contain; }

    .item-details { flex: 1; }
    .item-name {
        font-weight: 800;
        font-size: 1rem;
        color: var(--text-main);
        margin-bottom: 6px;
    }
    .item-meta-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .item-qty {
        background: rgba(232,103,27,0.1);
        color: var(--naranja);
        border: 1px solid rgba(232,103,27,0.2);
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 800;
    }
    .item-sub {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-muted);
    }
    .item-sub span { color: var(--text-main); }

    .item-price {
        text-align: right;
        flex-shrink: 0;
    }
    .item-price .amount {
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--naranja);
    }
    .item-price .currency {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 700;
    }

    /* Total bar */
    .total-bar {
        background: linear-gradient(135deg, rgba(232,103,27,0.08), rgba(232,103,27,0.03));
        border: 1px solid rgba(232,103,27,0.2);
        border-radius: 22px;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 35px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .total-bar-label {
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        display: block;
    }
    .total-bar-amount {
        font-size: 2.2rem;
        font-weight: 950;
        color: var(--naranja);
        line-height: 1;
    }

    /* Action buttons */
    .action-row {
        display: flex;
        gap: 15px;
        margin-top: 35px;
        flex-wrap: wrap;
    }
    .btn-tracking {
        flex: 1;
        min-width: 200px;
        background: var(--naranja);
        color: white;
        border: none;
        padding: 16px 30px;
        border-radius: 18px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 10px 25px rgba(232,103,27,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-tracking:hover {
        background: #c95510;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(232,103,27,0.35);
        color: white;
    }
    .btn-invoice {
        background: var(--bg-body);
        color: var(--text-main);
        border: 2px solid var(--border-color);
        padding: 16px 30px;
        border-radius: 18px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-invoice:hover {
        border-color: var(--naranja);
        color: var(--naranja);
        background: rgba(232,103,27,0.05);
    }
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width: 900px;">

    <!-- Back link -->
    <a href="{{ route('cliente.pedidos.index') }}" class="d-inline-flex align-items-center gap-2 mb-4 fw-bold text-decoration-none" style="color: var(--text-muted); transition: 0.2s;" onmouseover="this.style.color='var(--naranja)'" onmouseout="this.style.color='var(--text-muted)'">
        <i class="bi bi-arrow-left fs-5"></i> Volver a Mis Pedidos
    </a>

    <div class="detail-hero">

        <!-- Banner Header -->
        <div class="detail-banner">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1>Detalle del Pedido</h1>
                    <p>Pedido #{{ $pedido->id }} · {{ $pedido->created_at->format('d \d\e F, Y') }}</p>
                </div>
                @php
                    $statusClass = match($pedido->estado) {
                        'entregado' => 'status-entregado',
                        'en_camino' => 'status-en_camino',
                        'confirmado'=> 'status-confirmado',
                        default     => 'status-pendiente',
                    };
                    $statusLabel = match($pedido->estado) {
                        'entregado' => '✓ Entregado',
                        'en_camino' => '⚡ En Camino',
                        'confirmado'=> '● Confirmado',
                        default     => '○ Pendiente',
                    };
                @endphp
                <span class="px-5 py-3 rounded-pill fw-black {{ $statusClass }}" style="font-size:0.9rem; letter-spacing:0.5px; align-self:center;">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        <div class="detail-body">

            <!-- Info Tiles -->
            <div class="info-grid">
                <div class="info-tile">
                    <span class="info-tile-label"><i class="bi bi-hash me-1"></i> ID del Pedido</span>
                    <p class="info-tile-value">#{{ $pedido->id }}</p>
                </div>
                <div class="info-tile">
                    <span class="info-tile-label"><i class="bi bi-calendar3 me-1"></i> Fecha de Compra</span>
                    <p class="info-tile-value">{{ $pedido->created_at->format('d M, Y') }}</p>
                </div>
                <div class="info-tile">
                    <span class="info-tile-label"><i class="bi bi-boxes me-1"></i> Total de Artículos</span>
                    <p class="info-tile-value">{{ $pedido->detalles->count() }} piezas</p>
                </div>
            </div>

            <!-- Articles Section -->
            <div class="items-section-title">
                <i class="bi bi-bag-check-fill text-naranja"></i> Artículos del Pedido
            </div>

            @foreach($pedido->detalles as $detalle)
                <div class="item-row">
                    <div class="item-img">
                        <img src="{{ $detalle->autoparte->imagen_url ?? 'https://placehold.co/70x70/e8671b/ffffff?text=⚙' }}"
                             alt="{{ $detalle->autoparte->nombre ?? 'Autoparte' }}">
                    </div>
                    <div class="item-details">
                        <p class="item-name">{{ $detalle->autoparte->nombre ?? 'Autoparte' }}</p>
                        <div class="item-meta-row">
                            <span class="item-qty">× {{ $detalle->cantidad ?? 1 }} unidades</span>
                            <span class="item-sub">Código: <span>{{ $detalle->autoparte->sku ?? 'N/A' }}</span></span>
                            @if(isset($detalle->autoparte->marca) && $detalle->autoparte->marca)
                                <span class="item-sub">Marca: <span>{{ is_object($detalle->autoparte->marca) ? $detalle->autoparte->marca->nombre : $detalle->autoparte->marca }}</span></span>
                            @endif
                        </div>
                    </div>
                    <div class="item-price">
                        <div class="amount">${{ number_format($detalle->subtotal, 2) }}</div>
                        <div class="currency">MXN</div>
                    </div>
                </div>
            @endforeach

            <!-- Total Bar -->
            <div class="total-bar">
                <div>
                    <span class="total-bar-label">Total del Pedido</span>
                    <div class="total-bar-amount">
                        ${{ number_format($pedido->detalles->sum('subtotal'), 2) }}
                        <span style="font-size:1rem; font-weight:700; opacity:0.6;"> MXN</span>
                    </div>
                </div>
                <div class="text-end" style="color: var(--text-muted);">
                    <div class="small fw-bold">INCL. IMPUESTOS</div>
                    <div class="small">Venta & Refacciones Maccuin</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-row">
                <a href="{{ route('cliente.pedidos.seguimiento', $pedido->id) }}" class="btn-tracking">
                    <i class="bi bi-geo-alt-fill fs-5"></i> Seguimiento del Envío
                </a>
                <a href="{{ route('cliente.pedidos.factura', $pedido->id) }}" class="btn-invoice text-decoration-none">
                    <i class="bi bi-file-earmark-pdf fs-5"></i> Descargar Factura
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
