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

        $productos = collect($rawProductos);

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar    = strtolower($request->buscar);
            $productos = $productos->filter(
                fn($p) => str_contains(strtolower($p['nombre'] ?? ''), $buscar) ||
                    str_contains(strtolower($p['sku'] ?? $p['id'] ?? ''), $buscar)
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
            'nombre'     => $p['nombre'] ?? 'Sin Nombre',
            'sku'        => $p['sku'] ?? ('SKU-' . $p['id']),
            'precio'     => $p['precio'] ?? 0,
            'imagen_url' => 'https://placehold.co/200x200?text=' . urlencode($p['nombre'] ?? 'Producto'),
            'categoria'  => (object)['id' => $p['categoria'] ?? 'N/A', 'nombre' => $p['categoria'] ?? 'N/A'],
            'marca'      => (object)['id' => $p['marca'] ?? 'Generico',     'nombre' => $p['marca'] ?? 'Genérico'],
        ]);

        // Categorías y marcas únicas para los filtros
        $categorias = collect($rawProductos)
            ->pluck('categoria')
            ->filter()
            ->unique()
            ->values()
            ->map(fn($c) => (object)['id' => $c, 'nombre' => $c]);

        $marcas = collect($rawProductos)
            ->pluck('marca')
            ->filter()
            ->unique()
            ->values()
            ->map(fn($m) => (object)['id' => $m, 'nombre' => $m]);

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

        $autoparte = (object)[
            'id'               => $producto['id'],
            'nombre'           => $producto['nombre'] ?? 'Sin Nombre',
            'sku'              => $producto['sku'] ?? ('SKU-' . $producto['id']),
            'numero_parte'     => $producto['sku'] ?? ('SKU-' . $producto['id']),
            'precio'           => $producto['precio'] ?? 0,
            'stock'            => $producto['stock_actual'] ?? 0,
            'imagen_url'       => 'https://placehold.co/300x300?text=' . urlencode($producto['nombre'] ?? 'Producto'),
            'descripcion'      => $producto['descripcion'] ?? "Producto obtenido desde la API",
            'especificaciones' => "Categoría: " . ($producto['categoria'] ?? 'N/A'),
            'imagenes'         => collect([]),
            'marca'            => (object)['nombre' => $producto['marca'] ?? 'Genérico'],
        ];

        return view('clientes.catalogo.show', compact('autoparte'));
    }
}
