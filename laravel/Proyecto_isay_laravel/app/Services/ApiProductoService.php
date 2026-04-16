<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ApiProductoService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.fastapi.url', 'http://127.0.0.1:8000'), '/');
    }

    
    public function obtenerTodos(): Collection
    {
        return Cache::remember('api_productos_todos', 60, function () {
            try {
                $response = Http::timeout(3)
                    ->connectTimeout(2)
                    ->get("{$this->apiUrl}/productos/");

                if ($response->failed()) {
                    Log::warning('API productos: respuesta fallida', ['status' => $response->status()]);
                    return collect([]);
                }

                return $this->transformar($response->json());
            } catch (\Exception $e) {
                Log::warning('API productos: error de conexión', ['error' => $e->getMessage()]);
                return collect([]);
            }
        });
    }

    
    public function obtenerPorId(int $id): ?object
    {
        return Cache::remember("api_producto_{$id}", 60, function () use ($id) {
            try {
                $response = Http::timeout(3)
                    ->connectTimeout(2)
                    ->get("{$this->apiUrl}/productos/{$id}");

                if ($response->failed()) {
                    return null;
                }

                return $this->transformarDetalle($response->json());
            } catch (\Exception $e) {
                Log::warning("API producto {$id}: error", ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    
    public function obtenerDetalle(int $id): ?object
    {
        return $this->obtenerPorId($id);
    }

    
    public function extraerCategorias(Collection $productos): Collection
    {
        return $productos->pluck('categoria.nombre')->filter()->unique()->values()
            ->map(fn($c) => (object)['id' => strtolower($c), 'nombre' => $c]);
    }

    
    public function extraerMarcas(Collection $productos): Collection
    {
        return $productos->pluck('marca.nombre')->filter()->unique()->values()
            ->map(fn($m) => (object)['id' => strtolower($m), 'nombre' => $m]);
    }

    
    private function transformar(array $rawProductos): Collection
    {
        return collect($rawProductos)->map(function ($p) {
            $nombre      = $p['nombre'] ?? 'Sin Nombre';
            $marcaNombre = $this->extraerMarca($nombre, $p);
            $nombre      = $this->limpiarNombre($nombre);

            $imagen = !empty($p['imagen']) ? 'http://localhost:5000/static/' . ltrim($p['imagen'], '/') : 'https://placehold.co/200x200?text=' . urlencode($nombre);

            return (object)[
                'id'         => $p['id'] ?? uniqid(),
                'nombre'     => $nombre,
                'sku'        => $p['sku'] ?? ('SKU-' . str_pad($p['id'] ?? mt_rand(100, 999), 3, '0', STR_PAD_LEFT)),
                'precio'       => $p['precio'] ?? 0,
                'stock_actual' => $p['stock_actual'] ?? 0,
                'stock_minimo' => $p['stock_minimo'] ?? 5,
                'imagen_url'   => $imagen,
                'categoria'    => (object)['id' => strtolower($p['categoria'] ?? 'N/A'), 'nombre' => $p['categoria'] ?? 'N/A'],
                'marca'        => (object)['id' => strtolower($marcaNombre), 'nombre' => $marcaNombre],
            ];
        });
    }

    
    private function transformarDetalle(array $producto): object
    {
        $nombre      = $producto['nombre'] ?? 'Sin Nombre';
        $marcaNombre = $this->extraerMarca($nombre, $producto);
        $nombre      = $this->limpiarNombre($nombre);

        $imagen = !empty($producto['imagen']) ? 'http://localhost:5000/static/' . ltrim($producto['imagen'], '/') : 'https://placehold.co/300x300?text=' . urlencode($nombre);

        return (object)[
            'id'               => $producto['id'] ?? uniqid(),
            'nombre'           => $nombre,
            'sku'              => $producto['sku'] ?? ('SKU-' . str_pad($producto['id'] ?? rand(1, 99), 3, '0', STR_PAD_LEFT)),
            'numero_parte'     => $producto['sku'] ?? ('SKU-' . ($producto['id'] ?? '...')),
            'precio'           => $producto['precio'] ?? 0,
            'stock'            => $producto['stock_actual'] ?? 0,
            'stock_minimo'     => $producto['stock_minimo'] ?? 5,
            'imagen_url'       => $imagen,
            'descripcion'      => $producto['descripcion'] ?? 'Producto obtenido desde la API',
            'especificaciones' => 'Categoría: ' . ($producto['categoria'] ?? 'N/A'),
            'imagenes'         => collect([]),
            'marca'            => (object)['nombre' => $marcaNombre],
            'categoria'        => (object)['nombre' => $producto['categoria'] ?? 'N/A'],
        ];
    }

    
    private function extraerMarca(string $nombre, array $producto): string
    {
        if (empty($producto['marca']) && preg_match('/ \((.+?)\)$/', $nombre, $matches)) {
            return $matches[1];
        }
        return $producto['marca'] ?? 'Genérica';
    }

    
    private function limpiarNombre(string $nombre): string
    {
        return preg_replace('/ \((.+?)\)$/', '', $nombre);
    }
}
