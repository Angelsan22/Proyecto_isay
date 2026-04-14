<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Services\ApiProductoService;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function __construct(
        private ApiProductoService $productoService
    ) {}

    public function index(Request $request)
    {
        $autopartes  = $this->productoService->obtenerTodos();
        $categorias  = $this->productoService->extraerCategorias($autopartes);
        $marcas      = $this->productoService->extraerMarcas($autopartes);

        // Aplicar filtros
        if ($request->filled('buscar')) {
            $buscar     = strtolower($request->buscar);
            $autopartes = $autopartes->filter(
                fn($p) => str_contains(strtolower($p->nombre), $buscar) || str_contains(strtolower($p->sku), $buscar)
            );
        }

        if ($request->filled('categoria')) {
            $autopartes = $autopartes->filter(
                fn($p) => $p->categoria->id === strtolower($request->categoria)
            );
        }

        if ($request->filled('marca')) {
            $autopartes = $autopartes->filter(
                fn($p) => $p->marca->id === strtolower($request->marca)
            );
        }

        return view('clientes.catalogo.index', compact('autopartes', 'categorias', 'marcas'));
    }

    public function show($id)
    {
        $autoparte = $this->productoService->obtenerDetalle((int) $id);

        if (!$autoparte) {
            abort(404, 'Producto no encontrado');
        }

        return view('clientes.catalogo.show', compact('autoparte'));
    }
}
