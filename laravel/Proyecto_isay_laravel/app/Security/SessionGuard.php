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
        return (bool) session('cliente_logueado', false);
    }

    /**
     * Obtener el nombre del cliente logueado.
     */
    public static function nombre(): string
    {
        return session('cliente_nombre', 'Usuario');
    }

    /**
     * Iniciar sesión (guardar en session).
     */
    public static function login(string $nombre): void
    {
        session(['cliente_logueado' => true, 'cliente_nombre' => $nombre]);
    }

    /**
     * Cerrar sesión.
     */
    public static function logout(): void
    {
        session()->forget(['cliente_logueado', 'cliente_nombre']);
    }
}
