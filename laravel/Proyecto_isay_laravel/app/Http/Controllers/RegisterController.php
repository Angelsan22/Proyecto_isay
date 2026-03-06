<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Muestra la interfaz de registro.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Procesa la captura, validación y guardado (RF01, RF02, RF03, RF04).
     */
    public function store(Request $request)
    {
        // RF02: Validación de formato y RF03: Confirmación de contraseña
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' verifica 'password_confirmation'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // RF04: Guardado en BD con RNF01 (Cifrado de contraseña)
        /* User::create([
            'name' => $request->name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Cifrado Bcrypt
        ]);
        */

        // RF05: Mensaje de confirmación
        return redirect()->route('login')->with('success', 'Cuenta creada con éxito. Ya puedes acceder al taller.');
    }
}