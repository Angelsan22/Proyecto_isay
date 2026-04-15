<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\ApiUsuarioService;

class PerfilController extends Controller
{
    private ApiUsuarioService $apiService;

    public function __construct(ApiUsuarioService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $user = auth()->user();
        return view('clientes.perfil', compact('user'));
    }

    /**
     * Actualizar datos personales delegando primero a FastAPI.
     */
    public function updateDatos(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
        ]);

        $user = auth()->user();

        // 1. Delegar actualización a la API (Fuente de verdad)
        if ($user->fastapi_id) {
            $nombreCompleto = $request->name . ' ' . $request->apellidos;
            $exito = $this->apiService->actualizarDatosEnApi($user->fastapi_id, $nombreCompleto, $request->email);
            
            if (!$exito) {
                return back()->withErrors(['email' => 'El servidor central no permitió actualizar tus datos.']);
            }
        }

        // 2. Sincronizar espejo local
        $user->update([
            'name'      => $request->name,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
        ]);

        return back()->with('success', 'Tus datos han sido actualizados y sincronizados con éxito.');
    }

    /**
     * Actualizar contraseña delegando primero la validación y cambio a FastAPI.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // 1. Intentar actualizar en la API Central
        if ($user->fastapi_id) {
            $exito = $this->apiService->actualizarPasswordEnApi(
                $user->fastapi_id, 
                $request->current_password, 
                $request->password
            );

            if (!$exito) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta o el servidor central rechazó el cambio.']);
            }
        }

        // 2. Sincronizar espejo local para que la sesión de Laravel siga funcionando
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Tu contraseña ha sido actualizada en todos los sistemas.');
    }
}
