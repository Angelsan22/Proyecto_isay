@extends('clientes.layout')
@section('title', 'Mis pedidos')

@section('content')
    <div class="container py-5" style="max-width:900px;">

        <div class="d-flex align-items-center gap-3 mb-5">
            <div class="bg-naranja p-3 rounded-4 shadow-sm">
                <i class="bi bi-box-seam fs-2 text-white"></i>
            </div>
            <div>
                <h1 class="fw-bold mb-0" style="letter-spacing: -1px;">Mis Pedidos</h1>
                <p class="text-muted mb-0">Gestiona y rastrea tus compras de autopartes</p>
            </div>
        </div>

        @forelse($pedidos as $pedido)
            <div class="card border-0 shadow-lg rounded-5 p-4 mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <span class="text-muted small text-uppercase fw-bold letter-spacing-1">ID de Pedido</span>
                        <h5 class="fw-bold mb-0">#{{ $pedido->id }}</h5>
                    </div>
                    <div class="text-end">
                        <span class="text-muted small text-uppercase fw-bold letter-spacing-1">Fecha de Compra</span>
                        <p class="fw-bold mb-0 text-naranja">{{ $pedido->created_at->format('d M, Y') }}</p>
                    </div>
                </div>

                @foreach($pedido->detalles as $detalle)
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <div class="bg-light p-2 rounded-4 border" style="background: rgba(232, 103, 27, 0.05) !important;">
                                <img src="{{ $detalle->autoparte->imagen_url ?? 'https://placehold.co/80x80?text=Item' }}"
                                     class="rounded-3" style="width:80px; height:80px; object-fit:contain;" alt="">
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="fw-bold mb-1" style="color: var(--text-main);">{{ $detalle->autoparte->nombre }}</h6>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-naranja px-2 py-1 small" style="font-size: 0.7rem;">CANTIDAD: {{ $detalle->cantidad ?? 1 }}</span>
                                <span class="text-muted small fw-bold">Subtotal: <span class="text-dark" style="color: var(--text-main) !important;">${{ number_format($detalle->subtotal, 2) }} MXN</span></span>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            @php
                                $statusClass = match($pedido->estado) {
                                    'entregado' => 'bg-success',
                                    'en_camino' => 'bg-warning text-dark',
                                    'confirmado' => 'bg-info text-dark',
                                    default => 'bg-secondary'
                                };
                                $statusLabel = match($pedido->estado) {
                                    'entregado' => 'ENTREGADO',
                                    'en_camino' => 'EN CAMINO',
                                    'confirmado' => 'CONFIRMADO',
                                    default => 'PENDIENTE'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} px-4 py-2 rounded-pill fw-bold" style="font-size: 0.8rem;">{{ $statusLabel }}</span>
                        </div>
                    </div>
                    @if(!$loop->last)<hr class="my-3 opacity-25">@endif
                @endforeach

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="h5 fw-bold mb-0">
                        Total de Orden: <span class="text-naranja">${{ number_format($pedido->detalles->sum('subtotal'), 2) }} MXN</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('cliente.pedidos.show', $pedido->id) }}"
                           class="btn btn-naranja px-4 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-eye me-1"></i> Detalles
                        </a>
                        <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-bold"
                                onclick="alert('Funcionalidad próximamente')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Factura
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 bg-card rounded-5 shadow-sm border" style="background: var(--bg-card);">
                <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                <h3 class="fw-bold mt-4">Aún no has realizado pedidos</h3>
                <p class="text-muted">Explora nuestro catálogo y encuentra las mejores piezas para tu vehículo.</p>
                <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-naranja px-5 py-3 rounded-pill mt-3 fw-bold">Ir al catálogo</a>
            </div>
        @endforelse

    </div>
@endsection
