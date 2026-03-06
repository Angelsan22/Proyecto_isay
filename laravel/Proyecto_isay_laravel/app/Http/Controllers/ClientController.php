<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Muestra la pantalla de inicio después del login.
     */
    public function index()
    {
        return view('client.inicio');
    }

    /**
     * Muestra el catálogo de refacciones.
     */
    public function catalog()
    {
        return view('client.catalogo');
    }

    /**
     * Muestra el historial de pedidos del cliente.
     */
    public function orders()
    {
        return view('client.pedidos');
    }
}