<?php

namespace App\Security;


class SessionGuard
{
    
    public static function check(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    
    public static function logueado(): bool
    {
        return self::check();
    }

    
    public static function nombre(): string
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user ? $user->name : 'Usuario';
    }

    
    public static function login($user): void
    {
        \Illuminate\Support\Facades\Auth::login($user);
    }

    
    public static function logout(): void
    {
        \Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
