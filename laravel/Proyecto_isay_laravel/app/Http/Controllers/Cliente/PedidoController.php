<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Data\PedidoData;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    public function crear()
    {
        $autopartes = PedidoData::productosDisponibles();
        return view('clientes.pedidos.crear', compact('autopartes'));
    }

    public function index()
    {
        $pedidos = PedidoData::all();
        return view('clientes.pedidos.index', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = PedidoData::find((int) $id);
        if (!$pedido) abort(404);

        $pedido->metodo_envio = 'Mensajería Express';

        return view('clientes.pedidos.show', compact('pedido'));
    }

    public function seguimiento($id)
    {
        $pedido = PedidoData::find((int) $id);
        if (!$pedido) abort(404);

        $pedido->metodo_envio           = 'Mensajería Express';
        $pedido->fecha_entrega_estimada = now()->addDays(2);

        $estados      = ['pendiente', 'confirmado', 'en_camino', 'entregado'];
        $ordenEstados = ['pendiente' => -1, 'confirmado' => 0, 'en_camino' => 1, 'entregado' => 2];
        $indiceActual = $ordenEstados[$pedido->estado] ?? -1;

        return view('clientes.pedidos.seguimiento', compact('pedido', 'estados', 'indiceActual'));
    }

    public function factura($id)
    {
        $pedido = PedidoData::find((int) $id);
        if (!$pedido) abort(404);

        $pdf = Pdf::loadView('clientes.pedidos.factura_pdf', compact('pedido'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Factura_Pedido_' . $pedido->id . '.pdf');
    }
}
