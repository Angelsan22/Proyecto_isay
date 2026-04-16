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

    
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
        $apiUser = $this->apiService->loginEnApi($request->email, $request->password);

        if ($apiUser) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $user = User::create([
                    'name'       => $apiUser['nombre'],
                    'email'      => $apiUser['correo'],
                    'fastapi_id' => $apiUser['id'],
                    'password'   => Hash::make($request->password),
                ]);
            } else {
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

    
    public function registro(Request $request)
    {
        $request->validate([
            'nombre'    => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $nombreCompleto = $request->nombre . ' ' . $request->apellidos;
        $fastapi_id = $this->apiService->registrarEnApi(
            $nombreCompleto, 
            $request->email,
            $request->password
        );

        if (!$fastapi_id) {
            return back()->withErrors(['email' => 'No se pudo sincronizar el registro con el servidor central.'])->withInput();
        }
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
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();
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
