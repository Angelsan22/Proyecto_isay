@extends('clientes.layout')
@section('title', 'Mis pedidos')

@section('content')
    <div class="container py-4" style="max-width:800px;">

        <h2 class="fw-bold mb-4">Mis Pedidos</h2>
        <hr>

        @foreach($pedidos as $pedido)
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">

                @foreach($pedido->detalles as $detalle)
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <img src="{{ $detalle->autoparte->imagen_url ?? 'https://placehold.co/60x60?text=Item' }}"
                                 class="rounded" style="width:60px; height:60px; object-fit:contain;" alt="">
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-0">
                                Fecha: {{ $pedido->created_at->format('d F, Y') }}
                                <span class="float-end">Pedido #{{ $pedido->id }}</span>
                            </p>
                            <p class="fw-semibold mb-0">{{ $detalle->autoparte->nombre }}</p>
                            <p class="text-muted small mb-0">
                                Cantidad: {{ $detalle->cantidad ?? 1 }} &nbsp;|&nbsp;
                                Total: ${{ number_format($detalle->subtotal, 2) }} USD
                            </p>
                        </div>
                        <div class="col-auto">
                            @if($pedido->estado === 'entregado')
                                <span class="badge bg-success px-3 py-2">Entregado</span>
                            @elseif($pedido->estado === 'en_camino')
                                <span class="badge bg-warning text-dark px-3 py-2">En Camino</span>
                            @elseif($pedido->estado === 'confirmado')
                                <span class="badge bg-info text-dark px-3 py-2">Confirmado</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">Pendiente</span>
                            @endif
                            <i class="bi bi-chevron-right ms-1 text-muted"></i>
                        </div>
                    </div>
                    @if(!$loop->last)<hr class="my-2">@endif
                @endforeach

                <div class="d-flex gap-2 mt-2">
                    <a href="{{ route('cliente.pedidos.show', $pedido->id) }}"
                       class="btn text-white fw-bold" style="background:#E8671B; border:none;">
                        Ver Detalles
                    </a>
                    <button type="button" class="btn btn-outline-secondary fw-bold"
                            onclick="alert('Funcionalidad próximamente')">
                        Factura en PDF
                    </button>
                </div>
            </div>
        @endforeach

    </div>
@endsection
