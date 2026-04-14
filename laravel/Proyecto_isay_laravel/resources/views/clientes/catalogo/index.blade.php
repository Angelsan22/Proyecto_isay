@extends('clientes.layout')
@section('title', 'Catálogo de Autopartes — Maccuin')

@push('styles')
<style>
    /* ── Sidebar ──────────────────────────────── */
    .catalog-sidebar {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 0;
        box-shadow: var(--shadow-soft);
        position: sticky;
        top: 20px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .sidebar-header {
        background: linear-gradient(135deg, var(--naranja) 0%, #d45a15 100%);
        padding: 20px 24px;
        color: white;
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sidebar-body { padding: 24px; }

    .filter-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--naranja);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 18px;
        display: block;
        border-bottom: 2px solid rgba(232, 103, 27, 0.1);
        padding-bottom: 6px;
    }

    /* ── Search ───────────────────────────────── */
    .search-bar-modern {
        border-radius: 14px;
        padding: 12px 18px;
        border: 2px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        transition: 0.3s;
    }
    .search-bar-modern:focus {
        border-color: var(--naranja);
        background: var(--bg-card);
        box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.1);
        outline: none;
    }
    .search-bar-modern::placeholder { color: var(--text-muted); opacity: 0.8; }

    /* ── Filter Items ────────────────────────── */
    .filter-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0;
        cursor: pointer;
    }
    .filter-item .form-check-input:checked {
        background-color: var(--naranja);
        border-color: var(--naranja);
    }
    .filter-item span {
        color: var(--text-main);
        font-weight: 500;
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .filter-item span.active-filter {
        color: var(--naranja);
        font-weight: 800;
    }

    /* ── Product Cards ───────────────────────── */
    .catalog-card {
        background: var(--bg-card);
        border-radius: 24px;
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid var(--border-color) !important;
        overflow: hidden;
    }
    .catalog-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
        border-color: var(--naranja) !important;
    }
    .product-img-wrapper {
        background: var(--bg-body);
        padding: 28px;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }
    .badge-category {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 7px 14px;
        border-radius: 10px;
        background: var(--naranja);
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(232, 103, 27, 0.25);
    }
    .price-tag {
        font-size: 1.4rem;
        font-weight: 900;
        color: var(--naranja);
    }

    /* ── Explore Button ──────────────────────── */
    .btn-details {
        background: var(--naranja);
        color: white;
        border: none;
        font-weight: 800;
        padding: 13px 24px;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-transform: uppercase;
        font-size: 0.82rem;
        box-shadow: 0 8px 20px rgba(232, 103, 27, 0.25);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        letter-spacing: 0.5px;
        text-decoration: none;
    }
    .btn-details:hover {
        background: var(--oscuro);
        transform: translateY(-3px) scale(1.02);
        color: white;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }

    /* ── Header Bar ──────────────────────────── */
    .catalog-header {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 22px;
        padding: 24px 30px;
        box-shadow: var(--shadow-soft);
    }

    .btn-clear {
        color: var(--naranja);
        border: 2px solid var(--naranja);
        background: rgba(232, 103, 27, 0.05);
        padding: 12px;
        border-radius: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    .btn-clear:hover {
        background: var(--naranja);
        color: white;
    }

    .cursor-pointer { cursor: pointer; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">

        {{-- Sidebar de Filtros --}}
        <aside class="col-lg-3">
            <div class="catalog-sidebar">
                <div class="sidebar-header">
                    <i class="bi bi-filter-left fs-4"></i> Filtros
                </div>
                <div class="sidebar-body">
                    <form method="GET" action="{{ route('cliente.catalogo.index') }}" id="filterForm">

                        <div class="mb-4">
                            <span class="filter-section-title">Búsqueda</span>
                            <div class="position-relative">
                                <input type="text" name="buscar" class="form-control search-bar-modern w-100"
                                       placeholder="¿Qué pieza buscas?" value="{{ request('buscar') }}">
                                <button type="submit" class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3 text-naranja">
                                    <i class="bi bi-search fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="filter-section-title">Categorías</span>
                            <label class="filter-item cursor-pointer">
                                <input class="form-check-input" type="radio" name="categoria" value=""
                                    {{ !request('categoria') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="{{ !request('categoria') ? 'active-filter' : '' }}">Todas las piezas</span>
                            </label>
                            @foreach($categorias as $cat)
                                <label class="filter-item cursor-pointer">
                                    <input class="form-check-input" type="radio" name="categoria"
                                           value="{{ $cat->id }}"
                                        {{ request('categoria') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="{{ request('categoria') == $cat->id ? 'active-filter' : '' }}">{{ $cat->nombre }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <span class="filter-section-title">Marcas</span>
                            @foreach($marcas as $m)
                                <label class="filter-item cursor-pointer">
                                    <input class="form-check-input" type="radio" name="marca"
                                           value="{{ $m->id }}"
                                        {{ request('marca') == $m->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="{{ request('marca') == $m->id ? 'active-filter' : '' }}">{{ $m->nombre }}</span>
                                </label>
                            @endforeach
                        </div>

                        <a href="{{ route('cliente.catalogo.index') }}" class="btn-clear mt-3">
                            <i class="bi bi-arrow-counterclockwise me-2"></i> Limpiar Filtros
                        </a>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Listado de Productos --}}
        <div class="col-lg-9">
            <div class="catalog-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="fw-bold mb-1" style="font-size:1.6rem; letter-spacing:-1px; color:var(--text-main);">Catálogo de Autopartes</h1>
                    <p class="mb-0 fw-medium" style="color:var(--text-muted); font-size:0.9rem;">Refacciones de alta precisión seleccionadas para ti</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <div class="h3 fw-black text-naranja mb-0">{{ count($autopartes) }}</div>
                    <div class="small fw-bold text-uppercase" style="color:var(--text-muted); letter-spacing:1px; font-size:0.7rem;">Piezas</div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse($autopartes as $ap)
                    <div class="col">
                        <div class="card catalog-card h-100 border-0">
                            <div class="product-img-wrapper text-center position-relative">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge-category">{{ $ap->categoria->nombre ?? 'Refacción' }}</span>
                                </div>
                                <img src="{{ $ap->imagen_url ?? 'https://placehold.co/200x200?text=Sin+imagen' }}"
                                     class="img-fluid" style="height:180px; width:100%; object-fit:contain;" alt="{{ $ap->nombre }}">
                            </div>
                            <div class="card-body px-4 pt-3 pb-4 d-flex flex-column">
                                <div class="mb-3">
                                    <h5 class="fw-bold mb-1 text-truncate" title="{{ $ap->nombre }}" style="color:var(--text-main); font-size:1rem;">{{ $ap->nombre }}</h5>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge fw-bold px-2 py-1" style="background:var(--naranja-soft, rgba(232,103,27,0.1)); color:var(--naranja); border:1px solid rgba(232,103,27,0.2); font-size:0.68rem; border-radius:8px;">{{ $ap->sku }}</span>
                                        <span class="text-naranja small fw-bold">{{ $ap->marca->nombre ?? 'Genérica' }}</span>
                                    </div>
                                </div>
                                <div class="mt-auto pt-3" style="border-top:1px solid var(--border-color);">
                                    <div class="d-flex flex-column mb-3">
                                        <span class="small fw-bold" style="color:var(--text-muted); font-size:0.68rem; text-transform:uppercase; letter-spacing:0.5px;">Precio</span>
                                        <span class="price-tag">${{ number_format($ap->precio, 2) }} <small class="fw-normal" style="color:var(--text-muted); font-size:0.8rem;">MXN</small></span>
                                    </div>
                                    <a href="{{ route('cliente.catalogo.show', $ap->id) }}" class="btn-details w-100">
                                        EXPLORAR <i class="bi bi-arrow-right fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5">
                        <div class="text-center p-5 card-macuin">
                            <div class="display-1 mb-4" style="color:var(--border-color);">
                                <i class="bi bi-search"></i>
                            </div>
                            <h3 class="fw-bold" style="color:var(--text-main);">No encontramos coincidencias</h3>
                            <p class="fw-medium mx-auto" style="max-width:400px; color:var(--text-muted);">Prueba ajustando tus filtros o escribiendo un término diferente.</p>
                            <a href="{{ route('cliente.catalogo.index') }}" class="btn btn-naranja px-5 py-3 rounded-pill mt-3 fw-bold">Volver al inicio</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
