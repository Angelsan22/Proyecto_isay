<?php

use Illuminate\Support\Facades\Route;


Route::get('/', fn() => redirect()->route('cliente.login'));
Route::prefix('cliente')->name('cliente.')->group(
    base_path('routes/cliente.php')
);
