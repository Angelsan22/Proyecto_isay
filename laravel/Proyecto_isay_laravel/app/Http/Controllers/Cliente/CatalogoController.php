<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CatalogoController extends Controller
{
    private string $apiUrl = 'http://localhost:8000';

    public function index(Request $request)
    {
        $response  = Http::get("{$this->apiUrl}/productos");
        $productos = collect($response->json());

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar    = strtolower($request->buscar);
            $productos = $productos->filter(
                fn($p) => str_contains(strtolower($p['producto']), $buscar) ||
                    str_contains(strtolower($p['sku']), $buscar)
            );
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $productos = $productos->filter(
                fn($p) => strtolower($p['categoria']) === strtolower($request->categoria)
            );
        }

        // Filtro por marca
        if ($request->filled('marca')) {
            $productos = $productos->filter(
                fn($p) => strtolower($p['marca']) === strtolower($request->marca)
            );
        }

        $autopartes = $productos->map(fn($p) => (object)[
            'id'         => $p['id'],
            'nombre'     => $p['producto'],
            'sku'        => $p['sku'],
            'precio'     => $p['precio'],
            'imagen_url' => 'https://placehold.co/200x200?text=' . urlencode($p['producto']),
            'categoria'  => (object)['id' => $p['categoria'], 'nombre' => $p['categoria']],
            'marca'      => (object)['id' => $p['marca'],     'nombre' => $p['marca']],
        ]);

        // Categorías y marcas únicas para los filtros
        $categorias = collect($response->json())
            ->pluck('categoria')
            ->unique()
            ->values()
            ->map(fn($c) => (object)['id' => $c, 'nombre' => $c]);

        $marcas = collect($response->json())
            ->pluck('marca')
            ->unique()
            ->values()
            ->map(fn($m) => (object)['id' => $m, 'nombre' => $m]);

        return view('clientes.catalogo.index', compact('autopartes', 'categorias', 'marcas'));
    }

    public function show($id)
    {
        $response  = Http::get("{$this->apiUrl}/productos");
        $productos = collect($response->json());

        $producto = $productos->firstWhere('id', (int)$id);

        if (!$producto) abort(404);

        $autoparte = (object)[
            'id'               => $producto['id'],
            'nombre'           => $producto['producto'],
            'sku'              => $producto['sku'],
            'numero_parte'     => $producto['sku'],
            'precio'           => $producto['precio'],
            'stock'            => 10,
            'imagen_url'       => 'https://placehold.co/300x300?text=' . urlencode($producto['producto']),
            'descripcion'      => "Producto obtenido desde la API\nDisponible para entrega inmediata",
            'especificaciones' => "Categoría: {$producto['categoria']}\nMarca: {$producto['marca']}",
            'imagenes'         => collect([]),
            'marca'            => (object)['nombre' => $producto['marca']],
        ];

        return view('clientes.catalogo.show', compact('autoparte'));
    }
}
