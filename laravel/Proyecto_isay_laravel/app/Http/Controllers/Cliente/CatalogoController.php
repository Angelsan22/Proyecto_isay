<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CatalogoController extends Controller
{
    private string $apiUrl = 'http://fastapi:8000';

    public function index(Request $request)
    {
        try {
            $response = Http::get("{$this->apiUrl}/productos");
            if ($response->failed()) {
                $rawProductos = [];
            } else {
                $rawProductos = $response->json();
            }
        } catch (\Exception $e) {
            $rawProductos = [];
        }

        // 1. Map raw API data to structured $autopartes, extracting Marca if needed
        $autopartes = collect($rawProductos)->map(function($p) {
            $nombre = $p['nombre'] ?? 'Sin Nombre';
            $marcaNombre = 'Genérica';
            
            // Check if brand is appended in parentheses, e.g. "Balatas (Bosch)"
            if (empty($p['marca']) && preg_match('/ \((.+?)\)$/', $nombre, $matches)) {
                $marcaNombre = $matches[1];
                $nombre = preg_replace('/ \((.+?)\)$/', '', $nombre); // Clean name
            } else {
                $marcaNombre = $p['marca'] ?? 'Genérica';
            }

            return (object)[
                'id'         => $p['id'] ?? uniqid(),
                'nombre'     => $nombre,
                'sku'        => $p['sku'] ?? ('SKU-' . ($p['id'] ?? mt_rand(100,999))),
                'precio'     => $p['precio'] ?? 0,
                'imagen_url' => 'https://placehold.co/200x200?text=' . urlencode($nombre),
                'categoria'  => (object)['id' => strtolower($p['categoria'] ?? 'N/A'), 'nombre' => $p['categoria'] ?? 'N/A'],
                'marca'      => (object)['id' => strtolower($marcaNombre), 'nombre' => $marcaNombre],
            ];
        });

        // 2. Extract options for Sidebar (UNFILTERED)
        $categorias = $autopartes->pluck('categoria.nombre')->filter()->unique()->values()
            ->map(fn($c) => (object)['id' => strtolower($c), 'nombre' => $c]);

        $marcas = $autopartes->pluck('marca.nombre')->filter()->unique()->values()
            ->map(fn($m) => (object)['id' => strtolower($m), 'nombre' => $m]);

        // 3. Apply Filters
        if ($request->filled('buscar')) {
            $buscar = strtolower($request->buscar);
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
        try {
            $response = Http::get("{$this->apiUrl}/productos");
            if ($response->failed()) {
                abort(503, 'Servicio temporalmente no disponible');
            }
            $productos = collect($response->json());
        } catch (\Exception $e) {
            abort(503, 'Error al conectar con el servidor de datos');
        }

        $producto = $productos->firstWhere('id', (int)$id);

        if (!$producto) abort(404, 'Producto no encontrado');

        $nombre = $producto['nombre'] ?? 'Sin Nombre';
        $marcaNombre = 'Genérica';
        if (empty($producto['marca']) && preg_match('/ \((.+?)\)$/', $nombre, $matches)) {
            $marcaNombre = $matches[1];
            $nombre = preg_replace('/ \((.+?)\)$/', '', $nombre);
        } else {
            $marcaNombre = $producto['marca'] ?? 'Genérica';
        }

        $autoparte = (object)[
            'id'               => $producto['id'] ?? uniqid(),
            'nombre'           => $nombre,
            'sku'              => $producto['sku'] ?? ('SKU-' . str_pad($producto['id'] ?? rand(1,99), 3, '0', STR_PAD_LEFT)),
            'numero_parte'     => $producto['sku'] ?? ('SKU-' . ($producto['id'] ?? '...')),
            'precio'           => $producto['precio'] ?? 0,
            'stock'            => $producto['stock_actual'] ?? 0,
            'imagen_url'       => 'https://placehold.co/300x300?text=' . urlencode($nombre),
            'descripcion'      => $producto['descripcion'] ?? "Producto obtenido desde la API",
            'especificaciones' => "Categoría: " . ($producto['categoria'] ?? 'N/A'),
            'imagenes'         => collect([]),
            'marca'            => (object)['nombre' => $marcaNombre],
            'categoria'        => (object)['nombre' => $producto['categoria'] ?? 'N/A'],
        ];

        return view('clientes.catalogo.show', compact('autoparte'));
    }
}
