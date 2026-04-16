<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ApiUsuarioService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.fastapi.url', 'http://127.0.0.1:8000'), '/');
    }

    
    public function registrarEnApi(string $nombre, string $correo, string $password): ?int
    {
        try {
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->post("{$this->apiUrl}/usuarios/", [
                    'nombre'   => $nombre,
                    'correo'   => $correo,
                    'password' => $password
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['id'] ?? null;
            }

            Log::error('API Usuarios: Fallo al registrar usuario', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);
            
            return null;

        } catch (\Exception $e) {
            Log::error('API Usuarios: Error de conexión en registro', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    
    public function loginEnApi(string $correo, string $password): ?array
    {
        try {
            $response = Http::timeout(5)
                ->post("{$this->apiUrl}/usuarios/login", [
                    'correo'   => $correo,
                    'password' => $password
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('API Usuarios: Error de conexión en login', ['error' => $e->getMessage()]);
            return null;
        }
    }

    
    public function actualizarPasswordEnApi(int $fastapiId, string $currentPassword, string $newPassword): bool
    {
        try {
            $response = Http::timeout(5)
                ->patch("{$this->apiUrl}/usuarios/{$fastapiId}/password", [
                    'current_password' => $currentPassword,
                    'new_password'     => $newPassword
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('API Usuarios: Error al actualizar password en API', ['error' => $e->getMessage()]);
            return false;
        }
    }

    
    public function actualizarDatosEnApi(int $fastapiId, string $nombreCompleto, string $correo): bool
    {
        try {
            $response = Http::timeout(5)
                ->put("{$this->apiUrl}/usuarios/{$fastapiId}", [
                    'nombre' => $nombreCompleto,
                    'correo' => $correo
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('API Usuarios: Error al actualizar datos en API', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
