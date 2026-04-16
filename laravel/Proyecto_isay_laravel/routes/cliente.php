<?php

use App\Http\Controllers\Cliente\AuthClienteController;
use App\Http\Controllers\Cliente\CatalogoController;
use App\Http\Controllers\Cliente\PedidoController;
use App\Http\Controllers\Cliente\PerfilController;
use Illuminate\Support\Facades\Route;


Route::get('/login',     [AuthClienteController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthClienteController::class, 'login'])->name('login.post');
Route::get('/registro',  [AuthClienteController::class, 'showRegistro'])->name('registro');
Route::post('/registro', [AuthClienteController::class, 'registro'])->name('registro.post');
Route::get('/recuperar', [AuthClienteController::class, 'showRecuperar'])->name('recuperar');
Route::post('/recuperar',[AuthClienteController::class, 'recuperar'])->name('recuperar.post');
Route::get('/logout',    [AuthClienteController::class, 'logout'])->name('logout');
Route::get('/catalogo',      [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('catalogo.show');
Route::post('/carrito/agregar',         [PedidoController::class, 'agregarAlCarrito'])->name('carrito.agregar');
Route::post('/carrito/actualizar',      [PedidoController::class, 'actualizarCantidad'])->name('carrito.actualizar');
Route::post('/carrito/eliminar',        [PedidoController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');

Route::middleware('auth')->group(function () {
    Route::post('/pedidos',                 [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/checkout',                 [PedidoController::class, 'checkout'])->name('pedidos.checkout');
    Route::get('/pedidos/crear',            [PedidoController::class, 'crear'])->name('pedidos.crear');
    Route::get('/pedidos',                  [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{id}',             [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/pedidos/{id}/seguimiento', [PedidoController::class, 'seguimiento'])->name('pedidos.seguimiento');
    Route::get('/pedidos/{id}/factura',     [PedidoController::class, 'factura'])->name('pedidos.factura');
    Route::patch('/pedidos/{id}/cancelar',   [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');

    Route::get('/perfil',                   [PerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil/datos',             [PerfilController::class, 'updateDatos'])->name('perfil.datos');
    Route::put('/perfil/password',          [PerfilController::class, 'updatePassword'])->name('perfil.password');
});
