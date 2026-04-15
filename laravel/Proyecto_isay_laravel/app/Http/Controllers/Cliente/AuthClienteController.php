<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Services\ApiUsuarioService;

class AuthClienteController extends Controller
{
    private ApiUsuarioService $apiService;

    public function __construct(ApiUsuarioService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function showLogin()
    {
        return view('clientes.auth.login');
    }

    /**
     * Procesar inicio de sesión delegando la validación a FastAPI.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. Validar contra la API Central (FastAPI)
        $apiUser = $this->apiService->loginEnApi($request->email, $request->password);

        if ($apiUser) {
            // 2. Buscar/Sincronizar el usuario localmente en Laravel para la sesión
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                // Si existe en API pero no en Laravel (ej. migración), creamos el espejo local
                $user = User::create([
                    'name'       => $apiUser['nombre'],
                    'email'      => $apiUser['correo'],
                    'fastapi_id' => $apiUser['id'],
                    'password'   => Hash::make($request->password), // Sincronizamos para que Auth funcione
                ]);
            } else {
                // Actualizamos por si acaso cambió el nombre o password en la API
                $user->update([
                    'name'       => $apiUser['nombre'],
                    'password'   => Hash::make($request->password),
                    'fastapi_id' => $apiUser['id']
                ]);
            }

            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();
            
            return redirect()->intended(route('cliente.catalogo.index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas en el sistema central.',
        ])->onlyInput('email');
    }

    public function showRegistro()
    {
        return view('clientes.auth.registro');
    }

    /**
     * Procesar registro enviando primero la información a FastAPI.
     */
    public function registro(Request $request)
    {
        $request->validate([
            'nombre'    => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $nombreCompleto = $request->nombre . ' ' . $request->apellidos;

        // 1. Registrar en la API de FastAPI (Fuente de verdad)
        $fastapi_id = $this->apiService->registrarEnApi(
            $nombreCompleto, 
            $request->email,
            $request->password
        );

        if (!$fastapi_id) {
            return back()->withErrors(['email' => 'No se pudo sincronizar el registro con el servidor central.'])->withInput();
        }

        // 2. Crear espejo local en Laravel
        $user = User::create([
            'name'       => $request->nombre,
            'apellidos'  => $request->apellidos,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'fastapi_id' => $fastapi_id,
        ]);

        Auth::login($user);

        return redirect()->route('cliente.catalogo.index')
            ->with('success', '¡Cuenta creada exitosamente! Bienvenido ' . $user->name);
    }

    public function showRecuperar()
    {
        return view('clientes.auth.recuperar');
    }

    public function recuperar(Request $request)
    {
        // En una implementación real, esto también debería notificar a la API
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Aquí deberíamos tener un endpoint en FastAPI para resetear password sin la anterior
        // Por simplicidad de la demo educativa, lo hacemos local y notificamos la intención si fuera necesario
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('cliente.login')
            ->with('success', 'Tu contraseña ha sido restablecida localmente. Intenta iniciar sesión.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cliente.login');
    }
}
