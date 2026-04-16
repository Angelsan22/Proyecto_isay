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

    
    public function updateDatos(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
        ]);

        $user = auth()->user();
        if ($user->fastapi_id) {
            $nombreCompleto = $request->name . ' ' . $request->apellidos;
            $exito = $this->apiService->actualizarDatosEnApi($user->fastapi_id, $nombreCompleto, $request->email);
            
            if (!$exito) {
                return back()->withErrors(['email' => 'El servidor central no permitió actualizar tus datos.']);
            }
        }
        $user->update([
            'name'      => $request->name,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
        ]);

        return back()->with('success', 'Tus datos han sido actualizados y sincronizados con éxito.');
    }

    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
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
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Tu contraseña ha sido actualizada en todos los sistemas.');
    }
}
