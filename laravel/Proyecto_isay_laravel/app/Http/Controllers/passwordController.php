<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Muestra el formulario de recuperación de contraseña.
     */
    public function showResetForm()
    {
        return view('auth.Password');
    }

    /**
     * Procesa el cambio de contraseña.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|min:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Aquí iría la lógica para verificar el código y actualizar en BD
        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida correctamente.');
    }
}