@extends('clientes.layout')
@section('title', 'Detalle del Pedido #' . $pedido->id . ' — Maccuin')

@push('styles')
<style>
    .detail-hero {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
    }

    .detail-banner {
        background: linear-gradient(135deg, #111111 0%, #2d1a0a 100%);
        padding: 36px 45px;
        position: relative;
        overflow: hidden;
    }
    .detail-banner::after {
        content: '#{{ $pedido->id }}';
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 5.5rem;
        font-weight: 950;
        opacity: 0.05;
        color: white;
        letter-spacing: -4px;
        pointer-events: none;
    }
    .detail-banner h1 { color: white; font-size: 1.7rem; font-weight: 900; letter-spacing: -0.5px; margin: 0 0 5px; }
    .detail-banner p { margin: 0; color: rgba(255,255,255,0.45); font-size: 0.88rem; font-weight: 600; }

    .detail-body { padding: 40px 45px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 36px;
    }
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr 1fr; }
        .detail-body { padding: 28px 22px; }
        .detail-banner { padding: 28px 22px; }
    }
    .info-tile {
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 20px;
    }
    .info-tile-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 6px;
    }
    .info-tile-value { font-size: 1rem; font-weight: 800; color: var(--text-main); margin: 0; }

    .status-entregado { background:#10b981; color:white; }
    .status-en_camino  { background:#f59e0b; color:#1a1a1a; }
    .status-confirmado { background:#3b82f6; color:white; }
    .status-pendiente  { background:#64748b; color:white; }

    .items-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--naranja);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .items-section-title::after { content:''; flex:1; height:2px; background:rgba(232,103,27,0.12); border-radius:2px; }

    .item-row { display:flex; align-items:center; gap:18px; padding:16px 0; border-bottom:1px solid var(--border-color); }
    .item-row:last-child { border-bottom:none; }
    .item-img {
        width:80px; height:80px; flex-shrink:0; border-radius:16px;
        border:2px solid var(--border-color); background:var(--bg-body);
        display:flex; align-items:center; justify-content:center; overflow:hidden;
    }
    .item-img img { width:60px; height:60px; object-fit:contain; }
    .item-details { flex:1; }
    .item-name { font-weight:800; font-size:0.95rem; color:var(--text-main); margin-bottom:5px; }
    .item-meta-row { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .item-qty {
        background:var(--naranja-soft, rgba(232,103,27,0.1)); color:var(--naranja);
        border:1px solid rgba(232,103,27,0.2); padding:4px 14px; border-radius:50px;
        font-size:0.75rem; font-weight:800;
    }
    .item-sub { font-size:0.85rem; font-weight:700; color:var(--text-muted); }
    .item-sub span { color:var(--text-main); }
    .item-price .amount { font-size:1.2rem; font-weight:900; color:var(--naranja); }
    .item-price .currency { font-size:0.72rem; color:var(--text-muted); font-weight:700; }

    .total-bar {
        background:linear-gradient(135deg, rgba(232,103,27,0.06), rgba(232,103,27,0.02));
        border:1px solid rgba(232,103,27,0.15);
        border-radius:20px;
        padding:22px 28px;
        display:flex; justify-content:space-between; align-items:center;
        margin-top:30px; flex-wrap:wrap; gap:15px;
    }
    .total-bar-label { font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:1.5px; color:var(--text-muted); display:block; }
    .total-bar-amount { font-size:2rem; font-weight:950; color:var(--naranja); line-height:1; }

    .action-row { display:flex; gap:14px; margin-top:30px; flex-wrap:wrap; }
    .btn-tracking {
        flex:1; min-width:180px; background:var(--naranja); color:white; border:none;
        padding:15px 28px; border-radius:16px; font-weight:800; text-transform:uppercase;
        letter-spacing:1px; text-align:center; text-decoration:none; transition:0.3s;
        box-shadow:0 10px 25px rgba(232,103,27,0.25); display:flex; align-items:center; justify-content:center; gap:10px;
    }
    .btn-tracking:hover { background:var(--naranja-hover, #c95510); transform:translateY(-3px); box-shadow:0 15px 35px rgba(232,103,27,0.35); color:white; }
    .btn-invoice {
        background:var(--bg-body); color:var(--text-main); border:2px solid var(--border-color);
        padding:15px 28px; border-radius:16px; font-weight:800; text-transform:uppercase;
        letter-spacing:1px; cursor:pointer; transition:0.3s; display:flex; align-items:center; gap:10px;
        text-decoration:none;
    }
    .btn-invoice:hover { border-color:var(--naranja); color:var(--naranja); background:rgba(232,103,27,0.03); }

    .back-link { color:var(--text-muted); font-weight:700; text-decoration:none; transition:0.3s; display:inline-flex; align-items:center; gap:8px; }
    .back-link:hover { color:var(--naranja); }
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width:880px;">

    <a href="{{ route('cliente.pedidos.index') }}" class="back-link mb-4 d-inline-flex">
        <i class="bi bi-arrow-left fs-5"></i> Volver a Mis Pedidos
    </a>

    <div class="detail-hero">

        <div class="detail-banner">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1>Detalle del Pedido</h1>
                    <p>Pedido #{{ $pedido->id }} · {{ $pedido->created_at->format('d \d\e F, Y') }}</p>
                </div>
                @php
                    $statusClass = match($pedido->estado) {
                        'entregado' => 'status-entregado', 'en_camino' => 'status-en_camino',
                        'confirmado'=> 'status-confirmado', default => 'status-pendiente',
                    };
                    $statusLabel = match($pedido->estado) {
                        'entregado' => '✓ Entregado', 'en_camino' => '⚡ En Camino',
                        'confirmado'=> '● Confirmado', default => '○ Pendiente',
                    };
                @endphp
                <span class="px-5 py-3 rounded-pill fw-black {{ $statusClass }}" style="font-size:0.85rem; letter-spacing:0.5px; align-self:center;">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        <div class="detail-body">
            <div class="info-grid">
                <div class="info-tile">
                    <span class="info-tile-label"><i class="bi bi-hash me-1"></i>ID del Pedido</span>
                    <p class="info-tile-value">#{{ $pedido->id }}</p>
                </div>
                <div class="info-tile">
                    <span class="info-tile-label"><i class="bi bi-calendar3 me-1"></i>Fecha de Compra</span>
                    <p class="info-tile-value">{{ $pedido->created_at->format('d M, Y') }}</p>
                </div>
                <div class="info-tile">
                    <span class="info-tile-label"><i class="bi bi-boxes me-1"></i>Total de Artículos</span>
                    <p class="info-tile-value">{{ $pedido->detalles->count() }} piezas</p>
                </div>
            </div>

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
                    <div class="item-price text-end flex-shrink-0">
                        <div class="amount">${{ number_format($detalle->subtotal, 2) }}</div>
                        <div class="currency">MXN</div>
                    </div>
                </div>
            @endforeach

            <div class="total-bar">
                <div>
                    <span class="total-bar-label">Total del Pedido</span>
                    <div class="total-bar-amount">
                        ${{ number_format($pedido->detalles->sum('subtotal'), 2) }}
                        <span style="font-size:0.9rem; font-weight:700; opacity:0.5;"> MXN</span>
                    </div>
                </div>
                <div class="text-end" style="color:var(--text-muted);">
                    <div class="small fw-bold">INCL. IMPUESTOS</div>
                    <div class="small">Maccuin Autopartes</div>
                </div>
            </div>

            <div class="action-row">
                <a href="{{ route('cliente.pedidos.seguimiento', $pedido->id) }}" class="btn-tracking">
                    <i class="bi bi-geo-alt-fill fs-5"></i> Seguimiento del Envío
                </a>
                <a href="{{ route('cliente.pedidos.factura', $pedido->id) }}" class="btn-invoice">
                    <i class="bi bi-file-earmark-pdf fs-5"></i> Descargar Factura
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
