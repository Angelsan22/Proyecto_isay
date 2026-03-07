<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientOrderController;

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

==========================================================

RUTAS DE AUTENTICACIÓN

==========================================================
*/
// Iniciar Sesión
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// Registro de nuevos Clientes
Route::get('/registro', [RegisterController::class, 'show'])->name('register');
Route::post('/registro', [RegisterController::class, 'store'])->name('register.store');

// Recuperación de Contraseña
Route::get('/recuperar-contrasena', [PasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/recuperar-contrasena', [PasswordController::class, 'update'])->name('password.update');

// Cerrar Sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/**

==========================================================

RUTAS GENERALES DEL CLIENTE

==========================================================
*/
// Panel de Inicio (Dashboard)
Route::get('/inicio', [ClientController::class, 'index'])->name('client.index');

/**

==========================================================

RUTAS DE PEDIDOS Y CARRITO (ClientOrderController)

==========================================================
*/

// Catálogo para explorar productos
Route::get('/catalogo', [ClientOrderController::class, 'catalog'])->name('client.catalog');

// Vista del Carrito de Compras
Route::get('/carrito', [ClientOrderController::class, 'cart'])->name('client.cart');

// Procesar y Guardar el pedido final desde el carrito
Route::post('/pedido/confirmar', [ClientOrderController::class, 'store'])->name('client.orders.store');

// Historial general de pedidos realizados
Route::get('/mis-pedidos', [ClientOrderController::class, 'index'])->name('client.orders');

// Ver detalle completo de un pedido específico
Route::get('/pedido/{id}', [ClientOrderController::class, 'show'])->name('client.orders.show');

// Pantalla de seguimiento (timeline)
Route::get('/pedido/{id}/seguimiento', [ClientOrderController::class, 'tracking'])->name('client.orders.tracking');

// Recibo / Factura
Route::get('/pedido/{id}/recibo', [ClientOrderController::class, 'downloadReceipt'])->name('client.orders.pdf');