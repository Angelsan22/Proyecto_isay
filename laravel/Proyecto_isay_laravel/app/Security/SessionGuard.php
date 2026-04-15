<?php

namespace App\Security;

/**
 *  Manejo de la sesión del cliente.
 *  Abstrae la lógica de autenticación ficticia basada en sesión.
 */
class SessionGuard
{
    /**
     * Verificar si el cliente está logueado.
     */
    public static function check(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    /**
     * Alias para check() usado en las vistas.
     */
    public static function logueado(): bool
    {
        return self::check();
    }

    /**
     * Obtener el nombre del cliente logueado.
     */
    public static function nombre(): string
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user ? $user->name : 'Usuario';
    }

    /**
     * Iniciar sesión (para compatibilidad si se requiere login manual).
     */
    public static function login($user): void
    {
        \Illuminate\Support\Facades\Auth::login($user);
    }

    /**
     * Cerrar sesión.
     */
    public static function logout(): void
    {
        \Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
