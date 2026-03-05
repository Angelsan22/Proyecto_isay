@extends('clientes.layout')
@section('title', 'Detalle del Pedido')

@section('content')
    <div class="container py-5" style="max-width:800px;">
        <a href="{{ route('cliente.pedidos.index') }}" class="btn btn-outline-secondary btn-sm mb-3">← Mis Pedidos</a>

        <div class="card border-0 shadow-sm rounded-4 p-5">
            <h2 class="fw-bold text-uppercase text-center mb-1">Detalle del Pedido</h2>
            <p class="text-center text-muted small mb-4">Pedido #{{ $pedido->id }}</p>

            <div class="row g-4">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold text-uppercase mb-3">Información del Pedido</h6>
                    <p class="mb-1"><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
                    <p class="mb-1">
                        <strong>Estado:</strong>
                        @if($pedido->estado === 'entregado')
                            <span class="badge bg-success">ENTREGADO</span>
                        @elseif($pedido->estado === 'en_camino')
                            <span class="badge bg-warning text-dark">EN PROCESO</span>
                        @else
                            <span class="badge bg-secondary">PENDIENTE</span>
                        @endif
                    </p>
                    <p class="mb-1"><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase mb-3">Artículos</h6>
                    @foreach($pedido->detalles as $detalle)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-gear-fill text-muted"></i>
                            <span class="small">{{ $detalle->autoparte->nombre }} – ${{ number_format($detalle->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('cliente.pedidos.seguimiento', $pedido->id) }}"
                   class="btn text-white fw-bold px-4 text-uppercase"
                   style="background:#E8671B; border:none;">
                    Seguimiento del Envío
                </a>
                <button type="button" class="btn btn-dark fw-bold px-4 text-uppercase"
                        onclick="alert('Funcionalidad próximamente')">
                    Descargar Factura
                </button>
            </div>

            <p class="text-center text-muted small mt-4 text-uppercase">Automóviles & Refacciones</p>
        </div>
    </div>
@endsection
