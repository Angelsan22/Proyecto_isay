@extends('clientes.layout')
@section('title', $autoparte->nombre)

@section('content')
    <div class="container py-4" style="max-width:900px;">
        <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            ← Volver al Catálogo
        </a>

        <div class="card border-0 shadow-sm p-4">
            <div class="row g-4">
                {{-- Imagen --}}
                <div class="col-md-5 text-center">
                    <img src="{{ $autoparte->imagen_url ?? 'https://placehold.co/300x300?text=Sin+imagen' }}"
                         class="img-fluid rounded" style="max-height:280px; object-fit:contain;" alt="{{ $autoparte->nombre }}">
                </div>

                {{-- Info --}}
                <div class="col-md-7">
                    <h4 class="fw-bold">{{ $autoparte->nombre }}</h4>
                    <p class="text-muted small mb-1">Número de parte: {{ $autoparte->numero_parte ?? $autoparte->sku }}</p>
                    <p class="text-muted small mb-3">Marca: <strong>{{ $autoparte->marca->nombre ?? '—' }}</strong></p>

                    <h3 class="fw-bold">${{ number_format($autoparte->precio, 2) }}
                        <small class="fs-6 text-muted fw-normal">USD</small>
                    </h3>
                    <p class="text-muted small">IVA incluido</p>

                    @if($autoparte->stock > 0)
                        <span class="badge bg-success mb-3 px-3 py-2">EN STOCK</span>
                    @else
                        <span class="badge bg-danger mb-3 px-3 py-2">AGOTADO</span>
                    @endif

                    {{-- Cantidad --}}
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="input-group" style="width:130px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="cambiar(-1)">−</button>
                            <input type="number" id="cantidad" value="1" min="1" class="form-control text-center">
                            <button class="btn btn-outline-secondary" type="button" onclick="cambiar(1)">+</button>
                        </div>
                        <button class="btn fw-bold px-4 text-white"
                                style="background:#E8671B; border:none;" type="button">
                            <i class="bi bi-cart-plus"></i> Agregar al Carrito
                        </button>
                    </div>

                    {{-- Descripción --}}
                    <div class="card bg-light border-0 p-3 mb-2">
                        <strong class="small">Descripción del Producto</strong>
                        <ul class="small mt-2 mb-0">
                            @foreach(explode("\n", $autoparte->descripcion ?? '') as $linea)
                                @if(trim($linea))<li>{{ trim($linea) }}</li>@endif
                            @endforeach
                        </ul>
                    </div>

                    {{-- Especificaciones --}}
                    @if($autoparte->especificaciones)
                        <div class="card bg-light border-0 p-3 mb-2">
                            <strong class="small">Especificaciones Técnicas</strong>
                            <ul class="small mt-2 mb-0">
                                @foreach(explode("\n", $autoparte->especificaciones) as $linea)
                                    @if(trim($linea))<li>{{ trim($linea) }}</li>@endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Compatibilidad --}}
                    <div class="card bg-light border-0 p-3">
                        <strong class="small">Compatibilidad con Vehículos</strong>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Selecciona tu vehículo">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 text-muted small">
            <a href="#" class="mx-2 text-muted">Sobre Nosotros</a>
            <a href="#" class="mx-2 text-muted">Contacto</a>
            <a href="#" class="mx-2 text-muted">Política de Privacidad</a>
            <a href="#" class="mx-2 text-muted">Términos de Servicio</a>
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
