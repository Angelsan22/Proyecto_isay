<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirigir raíz al login
Route::get('/', fn() => redirect()->route('cliente.login'));

// Cargar rutas del módulo cliente
Route::prefix('cliente')->name('cliente.')->group(
    base_path('routes/cliente.php')
);
