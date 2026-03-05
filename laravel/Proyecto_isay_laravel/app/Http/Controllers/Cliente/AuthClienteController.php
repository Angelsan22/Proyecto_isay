<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthClienteController extends Controller
{
    public function showLogin()
    {
        return view('clientes.auth.login');
    }

    // Login sin verificar — cualquier email/password da acceso
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Guardamos usuario ficticio en sesión
        session(['cliente_logueado' => true, 'cliente_nombre' => explode('@', $request->email)[0]]);

        return redirect()->route('cliente.catalogo.index');
    }

    public function showRegistro()
    {
        return view('clientes.auth.registro');
    }

    // Registro sin base de datos — redirige directo al catálogo
    public function registro(Request $request)
    {
        $request->validate([
            'nombre'   => ['required'],
            'apellidos'=> ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        session(['cliente_logueado' => true, 'cliente_nombre' => $request->nombre]);

        return redirect()->route('cliente.catalogo.index');
    }

    public function showRecuperar()
    {
        return view('clientes.auth.recuperar');
    }

    public function recuperar(Request $request)
    {
        // Solo redirige al login con mensaje
        return redirect()->route('cliente.login')->with('success', 'Contraseña actualizada correctamente.');
    }

    public function logout()
    {
        session()->forget(['cliente_logueado', 'cliente_nombre']);
        return redirect()->route('cliente.login');
    }
}
