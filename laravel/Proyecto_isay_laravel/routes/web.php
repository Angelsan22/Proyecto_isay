<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\AuthClienteController;
use App\Http\Controllers\Cliente\CatalogoController;
use App\Http\Controllers\Cliente\PedidoController;

// Redirigir raíz al login
Route::get('/', fn() => redirect()->route('cliente.login'));

Route::prefix('cliente')->name('cliente.')->group(function () {

    //  Autenticación (públicas)
    Route::get('/login',    [AuthClienteController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthClienteController::class, 'login'])->name('login.post');
    Route::get('/registro', [AuthClienteController::class, 'showRegistro'])->name('registro');
    Route::post('/registro',[AuthClienteController::class, 'registro'])->name('registro.post');
    Route::get('/recuperar',[AuthClienteController::class, 'showRecuperar'])->name('recuperar');
    Route::post('/recuperar',[AuthClienteController::class, 'recuperar'])->name('recuperar.post');
    Route::get('/logout',   [AuthClienteController::class, 'logout'])->name('logout');

    //  Catálogo (público)
    Route::get('/catalogo',      [CatalogoController::class, 'index'])->name('catalogo.index');
    Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('catalogo.show');

    // ── Pedidos ───────────────────────────────────────────────────────────
    Route::get('/pedidos/crear',            [PedidoController::class, 'crear'])->name('pedidos.crear');
    Route::get('/pedidos',                  [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}',             [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/pedidos/{id}/seguimiento', [PedidoController::class, 'seguimiento'])->name('pedidos.seguimiento');
    Route::get('/pedidos/{id}/factura',     [PedidoController::class, 'factura'])->name('pedidos.factura');
});
