@extends('clientes.layout')
@section('title', 'Catálogo de Autopartes')

@section('content')
    <div class="container py-4">

        <h1 class="fw-bold text-uppercase text-center mb-4">Catálogo de Autopartes</h1>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('cliente.catalogo.index') }}" class="mb-4">
            <div class="row g-3 align-items-start">
                <div class="col-md-2">
                    <p class="text-muted text-uppercase small fw-bold mb-1">Categoría</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoria" id="cat-all" value=""
                            {{ !request('categoria') ? 'checked' : '' }}>
                        <label class="form-check-label" for="cat-all">Todas</label>
                    </div>
                    @foreach($categorias as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="categoria"
                                   id="cat-{{ $cat->id }}" value="{{ $cat->id }}"
                                {{ request('categoria') == $cat->id ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat-{{ $cat->id }}">{{ $cat->nombre }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="col-md-2">
                    <p class="text-muted text-uppercase small fw-bold mb-1">Marca</p>
                    @foreach($marcas as $m)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="marca"
                                   id="marca-{{ $m->id }}" value="{{ $m->id }}"
                                {{ request('marca') == $m->id ? 'checked' : '' }}>
                            <label class="form-check-label" for="marca-{{ $m->id }}">{{ $m->nombre }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="col-md-8 d-flex align-items-end gap-2 mt-4">
                    <input type="text" name="buscar" class="form-control"
                           placeholder="Buscar por nombre..." value="{{ request('buscar') }}">
                    <button type="submit" class="btn btn-dark px-4 fw-bold text-nowrap">Aplicar filtros</button>
                    <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-outline-secondary text-nowrap">Limpiar</a>
                </div>
            </div>
        </form>

        {{-- Grid de productos --}}
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($autopartes as $ap)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="{{ $ap->imagen_url ?? 'https://placehold.co/200x200?text=Sin+imagen' }}"
                             class="card-img-top p-3" style="height:200px; object-fit:contain;" alt="{{ $ap->nombre }}">
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $ap->nombre }}</h6>
                            <p class="text-muted small mb-1">SKU: {{ $ap->sku }}</p>
                            <p class="fw-semibold mb-0">${{ number_format($ap->precio, 2) }} USD</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <a href="{{ route('cliente.catalogo.show', $ap->id) }}"
                               class="btn btn-naranja w-100 text-white fw-bold text-uppercase"
                               style="background:#E8671B; border:none;">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-search fs-1"></i>
                    <p class="mt-2">No se encontraron autopartes.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
