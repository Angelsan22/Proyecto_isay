<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 *  Servicio encargado de enviar pedidos a la API (FastAPI).
 */
class ApiPedidoService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.fastapi.url', 'http://127.0.0.1:8000'), '/');
    }

    /**
     * Crear un nuevo pedido completo en FastAPI.
     * 
     * @param array $data (cliente_id, total, items, direccion_envio, etc.)
     * @return bool
     */
    public function crearPedido(array $data): bool
    {
        try {
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->post("{$this->apiUrl}/pedidos/", $data);

            if ($response->successful()) {
                return true;
            }

            Log::error('API Pedidos: Fallo al crear pedido', [
                'status' => $response->status(),
                'body'   => $response->body(),
                'payload' => $data
            ]);
            
            return false;

        } catch (\Exception $e) {
            Log::error('API Pedidos: Error de conexión', [
                'error'  => $e->getMessage(),
                'payload' => $data
            ]);
            return false;
        }
    }

    /**
     * Obtener el historial de pedidos de un cliente específico.
     * 
     * @param int $clienteId
     * @return array
     */
    public function obtenerMisPedidos(int $clienteId): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiUrl}/pedidos/", [
                'cliente_id' => $clienteId
            ]);

            return $response->successful() ? $response->json() : [];

        } catch (\Exception $e) {
            Log::error('API Pedidos: Error al obtener historial', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtener un pedido individual por su ID.
     */
    public function obtenerDetalle(int $id): ?array
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiUrl}/pedidos/{$id}");
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('API Pedidos: Error al obtener detalle', ['id' => $id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Cancelar un pedido (cambiar estatus a Cancelado).
     */
    public function cancelarPedido(int $id): bool
    {
        try {
            $response = Http::timeout(5)->patch("{$this->apiUrl}/pedidos/{$id}/estatus", [
                'estatus' => 'Cancelado'
            ]);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('API Pedidos: Error al cancelar pedido', ['id' => $id, 'error' => $e->getMessage()]);
            return false;
        }
    }
}
