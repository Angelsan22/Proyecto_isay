@extends('clientes.layout')
@section('title', 'Seguimiento del Pedido #{{ $pedido->id }}')

@push('styles')
<style>
    .tracking-hero {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 36px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0,0,0,0.08);
    }

    /* Banner */
    .tracking-banner {
        background: linear-gradient(135deg, #1a1a1a 0%, #0f1f0a 100%);
        padding: 40px 50px;
    }
    .tracking-banner h1 {
        color: white;
        font-size: 1.9rem;
        font-weight: 900;
        letter-spacing: -1px;
        margin: 0 0 6px;
    }
    .tracking-banner p {
        margin: 0;
        color: rgba(255,255,255,0.5);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .tracking-body {
        padding: 45px 50px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
    }
    @media (max-width: 768px) {
        .tracking-body { grid-template-columns: 1fr; gap: 30px; padding: 30px 25px; }
        .tracking-banner { padding: 30px 25px; }
    }

    /* ---- Timeline ---- */
    .section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--naranja);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: rgba(232,103,27,0.15);
        border-radius: 2px;
    }

    .timeline { display: flex; flex-direction: column; }

    .timeline-step {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    .timeline-connector {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }
    .step-dot {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        transition: 0.3s;
    }
    .step-dot.active {
        background: var(--naranja);
        color: white;
        box-shadow: 0 8px 20px rgba(232,103,27,0.35);
    }
    .step-dot.inactive {
        background: var(--bg-body);
        color: var(--text-muted);
        border: 2px solid var(--border-color);
    }
    .step-dot.current {
        background: var(--naranja);
        color: white;
        box-shadow: 0 0 0 6px rgba(232,103,27,0.2), 0 8px 20px rgba(232,103,27,0.3);
        transform: scale(1.1);
    }
    .step-line {
        width: 3px;
        height: 40px;
        border-radius: 3px;
        margin: 6px 0;
    }
    .step-line.active   { background: var(--naranja); }
    .step-line.inactive { background: var(--border-color); }

    .step-info { padding-top: 10px; padding-bottom: 20px; }
    .step-label {
        font-weight: 800;
        font-size: 1rem;
        margin-bottom: 4px;
    }
    .step-label.active   { color: var(--naranja); }
    .step-label.inactive { color: var(--text-muted); }
    .step-label.current  { color: var(--naranja); }

    .step-sublabel {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    /* ---- Info Side ---- */
    .info-tile-sm {
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 18px 22px;
        margin-bottom: 14px;
    }
    .info-label-sm {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 4px;
    }
    .info-value-sm {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
    }

    .item-chip {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--bg-body);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 10px;
    }
    .item-chip-icon {
        width: 40px; height: 40px;
        background: rgba(232,103,27,0.1);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--naranja);
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .item-chip-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-main);
        flex: 1;
    }
    .item-chip-qty {
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--text-muted);
    }

    .btn-detail-full {
        display: block;
        width: 100%;
        background: var(--naranja);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 18px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 10px 25px rgba(232,103,27,0.25);
        margin-top: 25px;
    }
    .btn-detail-full:hover {
        background: #c95510;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(232,103,27,0.35);
        color: white;
    }
    .help-link {
        display: block;
        text-align: center;
        margin-top: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
    }
    .help-link a { color: var(--naranja); text-decoration: none; }
    .help-link a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width: 950px;">

    <!-- Back link -->
    <a href="{{ route('cliente.pedidos.show', $pedido->id) }}"
       class="d-inline-flex align-items-center gap-2 mb-4 fw-bold text-decoration-none"
       style="color: var(--text-muted); transition: 0.2s;"
       onmouseover="this.style.color='var(--naranja)'"
       onmouseout="this.style.color='var(--text-muted)'">
        <i class="bi bi-arrow-left fs-5"></i> Volver al Detalle
    </a>

    <div class="tracking-hero">

        <!-- Banner -->
        <div class="tracking-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-geo-alt-fill me-3" style="color: var(--naranja);"></i>Seguimiento del Envío</h1>
                <p>Pedido #{{ $pedido->id }} · {{ $pedido->created_at->format('d \d\e F, Y') }}</p>
            </div>
            @php
                $statusStyle = match($pedido->estado) {
                    'entregado' => 'background:#10b981; color:white;',
                    'en_camino' => 'background:#f59e0b; color:#1a1a1a;',
                    'confirmado'=> 'background:#3b82f6; color:white;',
                    default     => 'background:#64748b; color:white;',
                };
                $statusLabel = match($pedido->estado) {
                    'entregado' => '✓ Entregado',
                    'en_camino' => '⚡ En Camino',
                    'confirmado'=> '● Confirmado',
                    default     => '○ Pendiente',
                };
            @endphp
            <span class="px-5 py-3 rounded-pill fw-black" style="{{ $statusStyle }} font-size:0.9rem; letter-spacing:0.5px; align-self:center;">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="tracking-body">

            <!-- LEFT: Timeline -->
            <div>
                <div class="section-title">
                    <i class="bi bi-arrow-up-circle-fill"></i> Estado del Envío
                </div>

                @php
                    $pasos = [
                        ['key' => 'confirmado', 'label' => 'Pedido Confirmado',  'icon' => 'bi-bag-check-fill',  'sub' => 'Tu orden fue recibida y verificada'],
                        ['key' => 'en_camino',  'label' => 'En Camino',          'icon' => 'bi-truck',           'sub' => 'Tu paquete está en ruta'],
                        ['key' => 'entregado',  'label' => 'Entregado',          'icon' => 'bi-house-door-fill', 'sub' => 'Entrega completada exitosamente'],
                    ];
                    $orden  = ['pendiente' => -1, 'confirmado' => 0, 'en_camino' => 1, 'entregado' => 2];
                    $actual = $orden[$pedido->estado] ?? -1;
                @endphp

                <div class="timeline">
                    @foreach($pasos as $paso)
                        @php
                            $pasoIndice = $orden[$paso['key']] ?? -1;
                            $isActive   = $pasoIndice <= $actual;
                            $isCurrent  = $paso['key'] === $pedido->estado;
                            $dotClass   = $isCurrent ? 'current' : ($isActive ? 'active' : 'inactive');
                            $lineClass  = $isActive ? 'active' : 'inactive';
                        @endphp
                        <div class="timeline-step">
                            <div class="timeline-connector">
                                <div class="step-dot {{ $dotClass }}">
                                    <i class="bi {{ $paso['icon'] }}"></i>
                                </div>
                                @if(!$loop->last)
                                    <div class="step-line {{ $lineClass }}"></div>
                                @endif
                            </div>
                            <div class="step-info">
                                <p class="step-label {{ $dotClass }}">{{ $paso['label'] }}</p>
                                <p class="step-sublabel">
                                    {{ $paso['sub'] }}
                                    @if($isCurrent && isset($pedido->fecha_entrega_estimada))
                                        <br><span class="text-naranja fw-bold">Entrega estimada: {{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d M, Y') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RIGHT: Pedido Info + Items -->
            <div>
                <div class="section-title">
                    <i class="bi bi-info-circle-fill"></i> Información del Pedido
                </div>

                <div class="info-tile-sm">
                    <span class="info-label-sm"><i class="bi bi-hash me-1"></i>Número de Pedido</span>
                    <p class="info-value-sm">#{{ $pedido->id }}</p>
                </div>
                <div class="info-tile-sm">
                    <span class="info-label-sm"><i class="bi bi-calendar3 me-1"></i>Fecha de Compra</span>
                    <p class="info-value-sm">{{ $pedido->created_at->format('d M, Y') }}</p>
                </div>
                <div class="info-tile-sm">
                    <span class="info-label-sm"><i class="bi bi-truck me-1"></i>Método de Envío</span>
                    <p class="info-value-sm">{{ $pedido->metodo_envio ?? 'Mensajería Express' }}</p>
                </div>

                <div class="section-title mt-4">
                    <i class="bi bi-boxes"></i> Artículos
                </div>

                @foreach($pedido->detalles as $detalle)
                    <div class="item-chip">
                        <div class="item-chip-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <span class="item-chip-name">{{ $detalle->autoparte->nombre ?? 'Autoparte' }}</span>
                        <span class="item-chip-qty">×{{ $detalle->cantidad ?? 1 }}</span>
                    </div>
                @endforeach

                <a href="{{ route('cliente.pedidos.show', $pedido->id) }}" class="btn-detail-full">
                    <i class="bi bi-eye me-2"></i> Ver Detalle Completo
                </a>
                <span class="help-link">¿Necesitas ayuda? <a href="#">Contáctanos</a></span>
            </div>

        </div>
    </div>
</div>
@endsection
