<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 *  Servicio encargado de consumir la API de productos (FastAPI).
 *  Centraliza toda la lógica de transformación de datos del catálogo.
 */
class ApiProductoService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.fastapi.url', 'http://127.0.0.1:8000'), '/');
    }

    /**
     * Obtener todos los productos de la API y transformarlos.
     * Se cachean 60 segundos para evitar llamadas repetidas.
     */
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

    /**
     * Obtener un producto específico por ID (usa endpoint dedicado de la API).
     */
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

    /**
     * Obtener un producto con datos extendidos para la vista de detalle.
     * Usa el endpoint GET /productos/{id} de FastAPI (no descarga toda la lista).
     */
    public function obtenerDetalle(int $id): ?object
    {
        return $this->obtenerPorId($id);
    }

    /**
     * Extraer categorías únicas de la colección.
     */
    public function extraerCategorias(Collection $productos): Collection
    {
        return $productos->pluck('categoria.nombre')->filter()->unique()->values()
            ->map(fn($c) => (object)['id' => strtolower($c), 'nombre' => $c]);
    }

    /**
     * Extraer marcas únicas de la colección.
     */
    public function extraerMarcas(Collection $productos): Collection
    {
        return $productos->pluck('marca.nombre')->filter()->unique()->values()
            ->map(fn($m) => (object)['id' => strtolower($m), 'nombre' => $m]);
    }

    /**
     * Transformar datos crudos de la API en objetos estructurados (para listado).
     */
    private function transformar(array $rawProductos): Collection
    {
        return collect($rawProductos)->map(function ($p) {
            $nombre      = $p['nombre'] ?? 'Sin Nombre';
            $marcaNombre = $this->extraerMarca($nombre, $p);
            $nombre      = $this->limpiarNombre($nombre);

            return (object)[
                'id'         => $p['id'] ?? uniqid(),
                'nombre'     => $nombre,
                'sku'        => $p['sku'] ?? ('SKU-' . str_pad($p['id'] ?? mt_rand(100, 999), 3, '0', STR_PAD_LEFT)),
                'precio'     => $p['precio'] ?? 0,
                'imagen_url' => 'https://placehold.co/200x200?text=' . urlencode($nombre),
                'categoria'  => (object)['id' => strtolower($p['categoria'] ?? 'N/A'), 'nombre' => $p['categoria'] ?? 'N/A'],
                'marca'      => (object)['id' => strtolower($marcaNombre), 'nombre' => $marcaNombre],
            ];
        });
    }

    /**
     * Transformar un producto individual para la vista de detalle.
     */
    private function transformarDetalle(array $producto): object
    {
        $nombre      = $producto['nombre'] ?? 'Sin Nombre';
        $marcaNombre = $this->extraerMarca($nombre, $producto);
        $nombre      = $this->limpiarNombre($nombre);

        return (object)[
            'id'               => $producto['id'] ?? uniqid(),
            'nombre'           => $nombre,
            'sku'              => $producto['sku'] ?? ('SKU-' . str_pad($producto['id'] ?? rand(1, 99), 3, '0', STR_PAD_LEFT)),
            'numero_parte'     => $producto['sku'] ?? ('SKU-' . ($producto['id'] ?? '...')),
            'precio'           => $producto['precio'] ?? 0,
            'stock'            => $producto['stock_actual'] ?? 0,
            'imagen_url'       => 'https://placehold.co/300x300?text=' . urlencode($nombre),
            'descripcion'      => $producto['descripcion'] ?? 'Producto obtenido desde la API',
            'especificaciones' => 'Categoría: ' . ($producto['categoria'] ?? 'N/A'),
            'imagenes'         => collect([]),
            'marca'            => (object)['nombre' => $marcaNombre],
            'categoria'        => (object)['nombre' => $producto['categoria'] ?? 'N/A'],
        ];
    }

    /**
     * Extraer el nombre de la marca: de la propiedad directa o del nombre entre paréntesis.
     */
    private function extraerMarca(string $nombre, array $producto): string
    {
        if (empty($producto['marca']) && preg_match('/ \((.+?)\)$/', $nombre, $matches)) {
            return $matches[1];
        }
        return $producto['marca'] ?? 'Genérica';
    }

    /**
     * Limpiar el nombre del producto eliminando la marca entre paréntesis.
     */
    private function limpiarNombre(string $nombre): string
    {
        return preg_replace('/ \((.+?)\)$/', '', $nombre);
    }
}
