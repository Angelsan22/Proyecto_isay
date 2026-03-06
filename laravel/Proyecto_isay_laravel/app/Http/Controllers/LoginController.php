<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Maneja la autenticación.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Aquí iría la lógica de Auth::attempt
        return back()->withErrors(['email' => 'Las credenciales no coinciden.']);
    }
}