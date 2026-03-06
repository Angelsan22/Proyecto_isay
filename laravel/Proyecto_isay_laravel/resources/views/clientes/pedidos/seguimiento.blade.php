@extends('clientes.layout')
@section('title', 'Seguimiento del Pedido')

@section('content')
    <div class="container py-4" style="max-width:900px;">
        <a href="{{ route('cliente.pedidos.show', $pedido->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
            ← Detalle del Pedido
        </a>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="row g-4">

                {{-- Timeline --}}
                <div class="col-md-5 border-end">
                    <h4 class="fw-bold text-uppercase mb-4">Estatus de Tu Pedido</h4>

                    @php
                        $pasos = [
                            ['key' => 'confirmado', 'label' => 'Pedido Confirmado', 'icon' => 'bi-car-front'],
                            ['key' => 'en_camino',  'label' => 'En Camino',         'icon' => 'bi-truck'],
                            ['key' => 'entregado',  'label' => 'Entregado',         'icon' => 'bi-house-door'],
                        ];
                        $orden = ['pendiente' => -1, 'confirmado' => 0, 'en_camino' => 1, 'entregado' => 2];
                        $actual = $orden[$pedido->estado] ?? -1;
                    @endphp

                    @foreach($pasos as $paso)
                        @php $activo = ($orden[$paso['key']] ?? -1) <= $actual; $current = $paso['key'] === $pedido->estado; @endphp
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-flex flex-column align-items-center" style="width:44px;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                     style="width:44px; height:44px; background:{{ $activo ? '#E8671B' : '#dee2e6' }}; flex-shrink:0;">
                                    <i class="bi {{ $paso['icon'] }}"></i>
                                </div>
                                @if(!$loop->last)
                                    <div style="width:3px; height:36px; background:{{ $activo ? '#E8671B' : '#dee2e6' }};"></div>
                                @endif
                            </div>
                            <div class="pb-3 pt-1">
                                <p class="fw-semibold mb-0" style="{{ $current ? 'color:#E8671B' : '' }}">
                                    {{ $paso['label'] }}
                                </p>
                                @if($current && isset($pedido->fecha_entrega_estimada))
                                    <p class="text-muted small mb-0">
                                        ENTREGA ESTIMADA: {{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Detalles --}}
                <div class="col-md-7">
                    <h4 class="fw-bold text-uppercase mb-4">Detalle del Pedido</h4>

                    <p class="small text-muted mb-1">Número de Pedido: <strong>#{{ $pedido->id }}</strong></p>
                    <p class="small text-muted mb-1">Fecha de Compra: <strong>{{ $pedido->created_at->format('d/m/Y') }}</strong></p>
                    <p class="small text-muted mb-3">Método de Envío: <strong>{{ $pedido->metodo_envio ?? 'Mensajería Express' }}</strong></p>

                    @foreach($pedido->detalles as $detalle)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-tools text-muted"></i>
                            <span class="small">{{ $detalle->autoparte->nombre }} (x{{ $detalle->cantidad ?? 1 }})</span>
                        </div>
                    @endforeach

                    <hr class="my-3">

                    <a href="{{ route('cliente.catalogo.index') }}"
                       class="btn text-white fw-bold w-100 text-uppercase mb-2"
                       style="background:#E8671B; border:none;">
                        Ver Detalle Completo
                    </a>
                    <p class="text-center text-muted small">
                        ¿Necesitas ayuda? <a href="#" style="color:#E8671B;">Contáctanos</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
@endsection
