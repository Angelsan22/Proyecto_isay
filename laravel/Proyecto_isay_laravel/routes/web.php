<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes - MACUIN (Modo Desarrollo Visual)
|--------------------------------------------------------------------------
|
| Nota: Se han extraído las rutas del middleware 'auth' para permitir
| la visualización de las interfaces sin necesidad de inicio de sesión real.
|
*/

// Redirección inicial al Login
Route::get('/', function () {
    return redirect()->route('login');
});

/**
 * RUTAS DE INTERFACES (Libres para visualización)
 */

// 1. Iniciar Sesión
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// 2. Registro de nuevos Clientes
Route::get('/registro', [RegisterController::class, 'show'])->name('register');
Route::post('/registro', [RegisterController::class, 'store'])->name('register.store');

// 3. Recuperación de Contraseña
Route::get('/recuperar-contrasena', [PasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/recuperar-contrasena', [PasswordController::class, 'update'])->name('password.update');

// 4. Panel de Cliente (Acceso libre para pruebas visuales)
Route::get('/inicio', [ClientController::class, 'index'])->name('client.index');
Route::get('/catalogo', [ClientController::class, 'catalog'])->name('client.catalog');
Route::get('/mis-pedidos', [ClientController::class, 'orders'])->name('client.orders');

// Cerrar Sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');