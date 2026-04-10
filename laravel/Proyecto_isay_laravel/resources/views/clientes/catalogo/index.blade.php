@extends('clientes.layout')
@section('title', 'Catálogo de Autopartes')

@push('styles')
<style>
    .catalog-sidebar {
        background: var(--bg-card);
        border-radius: 25px;
        padding: 0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        position: sticky;
        top: 20px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .sidebar-header {
        background: var(--naranja);
        padding: 20px;
        color: white;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sidebar-body {
        padding: 25px;
    }
    .filter-section-title {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--naranja);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 20px;
        display: block;
        border-bottom: 2px solid rgba(232, 103, 27, 0.1);
        padding-bottom: 5px;
    }
    .catalog-card {
        background: var(--bg-card);
        border-radius: 28px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid var(--border-color) !important;
        overflow: hidden;
    }
    .catalog-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        border-color: var(--naranja) !important;
    }
    .price-tag {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--naranja);
    }
    .search-bar-modern {
        border-radius: 16px;
        padding: 12px 18px;
        border: 2px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        transition: 0.3s;
    }
    .search-bar-modern:focus {
        border-color: var(--naranja);
        background: var(--bg-card);
        box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.1);
        outline: none;
    }
    .search-bar-modern::placeholder {
        color: var(--text-muted);
        opacity: 0.8;
    }
    .badge-category {
        font-size: 0.75rem;
        font-weight: 800;
        padding: 8px 16px;
        border-radius: 12px;
        background: var(--naranja);
        color: white;
        text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(232, 103, 27, 0.2);
    }
    .cursor-pointer { cursor: pointer; }
    .text-naranja { color: var(--naranja) !important; }
    
    .btn-details {
        background: var(--naranja);
        color: white;
        border: none;
        font-weight: 800;
        padding: 14px 28px;
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-transform: uppercase;
        font-size: 0.85rem;
        box-shadow: 0 10px 25px rgba(232, 103, 27, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        letter-spacing: 1px;
        text-decoration: none;
    }
    .btn-details:hover {
        background: var(--oscuro);
        transform: translateY(-3px) scale(1.02);
        color: white;
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }

    .theme-toggle-btn {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-card);
        border: 2px solid var(--border-color);
        color: var(--text-main);
        cursor: pointer;
        transition: 0.3s;
        font-size: 1.2rem;
    }
    .theme-toggle-btn:hover {
        background: var(--naranja);
        color: white;
        border-color: var(--naranja);
        transform: rotate(15deg);
    }
    
    .product-img-wrapper {
        background: rgba(232, 103, 27, 0.03);
        padding: 30px;
        border-bottom: 1px solid var(--border-color);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar de Filtros -->
        <aside class="col-lg-3">
            <div class="catalog-sidebar">
                <div class="sidebar-header">
                    <i class="bi bi-filter-left fs-4"></i> Filtros de Búsqueda
                </div>
                <div class="sidebar-body">
                    <form method="GET" action="{{ route('cliente.catalogo.index') }}" id="filterForm">
                        <div class="mb-4">
                            <span class="filter-section-title">Palabra Clave</span>
                            <div class="position-relative">
                                <input type="text" name="buscar" class="form-control search-bar-modern w-100" 
                                       placeholder="¿Qué pieza buscas?" value="{{ request('buscar') }}">
                                <button type="submit" class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3 text-naranja">
                                    <i class="bi bi-search fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-5">
                            <span class="filter-section-title">Categorías</span>
                            <div class="list-group list-group-flush bg-transparent">
                                <label class="list-group-item border-0 p-0 mb-3 bg-transparent cursor-pointer d-flex align-items-center">
                                    <input class="form-check-input me-3" type="radio" name="categoria" id="cat-all" value=""
                                        {{ !request('categoria') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="{{ !request('categoria') ? 'fw-bold text-naranja' : '' }}" style="color: var(--text-main);">Todas las piezas</span>
                                </label>
                                @foreach($categorias as $cat)
                                    <label class="list-group-item border-0 p-0 mb-3 bg-transparent cursor-pointer d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="categoria"
                                               id="cat-{{ Str::slug($cat->id) }}" value="{{ $cat->id }}"
                                            {{ request('categoria') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="{{ request('categoria') == $cat->id ? 'fw-bold text-naranja' : '' }}" style="color: var(--text-main);">{{ $cat->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="filter-section-title">Marcas</span>
                            <div class="list-group list-group-flush bg-transparent">
                                @foreach($marcas as $m)
                                    <label class="list-group-item border-0 p-0 mb-3 bg-transparent cursor-pointer d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="marca"
                                               id="marca-{{ Str::slug($m->id) }}" value="{{ $m->id }}"
                                            {{ request('marca') == $m->id ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="{{ request('marca') == $m->id ? 'fw-bold text-naranja' : '' }}" style="color: var(--text-main);">{{ $m->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <a href="{{ route('cliente.catalogo.index') }}" class="btn rounded-pill fw-bold py-3" style="color: var(--naranja); border: 2px solid var(--naranja); background: rgba(232,103,27,0.07); letter-spacing: 0.5px; transition: 0.3s;" onmouseover="this.style.background='var(--naranja)'; this.style.color='white';" onmouseout="this.style.background='rgba(232,103,27,0.07)'; this.style.color='var(--naranja)';">
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Limpiar Todo
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Listado de Productos -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-5 bg-card p-4 rounded-4 shadow-sm border" style="background: var(--bg-card); border-color: var(--border-color);">
                <div>
                    <h1 class="fw-bold mb-1 display-6" style="letter-spacing: -2px; color: var(--text-main);">Catálogo de Autopartes</h1>
                    <p class="mb-0 fw-medium" style="color: var(--text-muted);">Refacciones de alta precisión seleccionadas para ti</p>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <div class="text-end d-none d-md-block">
                        <div class="h3 fw-black text-naranja mb-0">{{ count($autopartes) }}</div>
                        <div class="small fw-bold text-uppercase" style="color: var(--text-main); letter-spacing: 1px;">Piezas</div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse($autopartes as $ap)
                    <div class="col">
                        <div class="card catalog-card h-100 border-0 shadow-sm">
                            <div class="product-img-wrapper text-center position-relative">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge-category">{{ $ap->categoria->nombre ?? 'Refacción' }}</span>
                                </div>
                                <img src="{{ $ap->imagen_url ?? 'https://placehold.co/200x200?text=Sin+imagen' }}"
                                     class="img-fluid" style="height:200px; width:100%; object-fit:contain;" alt="{{ $ap->nombre }}">
                            </div>
                            <div class="card-body px-4 pt-4 pb-4 d-flex flex-column">
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-1 text-truncate" title="{{ $ap->nombre }}" style="color: var(--text-main);">{{ $ap->nombre }}</h5>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge fw-bold px-2 py-1" style="background: rgba(232,103,27,0.15); color: var(--naranja); border: 1px solid rgba(232,103,27,0.3); font-size: 0.7rem;">Código: {{ $ap->sku }}</span>
                                        <span class="text-naranja small fw-bold">{{ $ap->marca->nombre ?? 'Genérica' }}</span>
                                    </div>
                                </div>
                                <div class="mt-auto d-flex flex-column pt-3 border-top" style="border-top: 1px solid var(--border-color) !important;">
                                    <div class="d-flex flex-column mb-3">
                                        <span class="small fw-bold" style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Precio Lista</span>
                                        <span class="price-tag">${{ number_format($ap->precio, 2) }} <small class="fw-normal" style="color: var(--text-muted); font-size: 0.85rem">MXN</small></span>
                                    </div>
                                    <a href="{{ route('cliente.catalogo.show', $ap->id) }}" class="btn-details shadow-sm w-100">
                                        EXPLORAR <i class="bi bi-arrow-right fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5">
                        <div class="text-center bg-card p-5 rounded-5 shadow-sm border border-dashed" style="background: var(--bg-card); border-color: var(--border-color);">
                            <div class="display-1 text-muted opacity-25 mb-4">
                                <i class="bi bi-search"></i>
                            </div>
                            <h3 class="fw-bold">No encontramos coincidencias</h3>
                            <p class="text-muted mx-auto" style="max-width: 400px;">Prueba ajustando tus filtros o escribiendo un término de búsqueda diferente.</p>
                            <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-naranja px-5 py-3 rounded-pill mt-4 fw-bold">Volver al inicio</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


