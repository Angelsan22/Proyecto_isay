<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    private function getPedidos()
    {
        return collect([
            (object)[
                'id'         => 1,
                'created_at' => now()->subDays(3),
                'estado'     => 'entregado',
                'total'      => 350.00,
                'detalles'   => collect([
                    (object)['autoparte' => (object)['nombre' => 'Pinza de Freno de Alto Rendimiento', 'imagen_url' => 'https://placehold.co/80x80?text=Freno'], 'cantidad' => 2, 'subtotal' => 350.00],
                ]),
            ],
            (object)[
                'id'         => 2,
                'created_at' => now()->subDays(1),
                'estado'     => 'en_camino',
                'total'      => 613.99,
                'detalles'   => collect([
                    (object)['autoparte' => (object)['nombre' => 'Llanta Deport Aleación X-5', 'imagen_url' => 'https://placehold.co/80x80?text=Llanta'], 'cantidad' => 2, 'subtotal' => 613.99],
                ]),
            ],
        ]);
    }

    public function crear()
    {
        $autopartes = collect([
            (object)['id' => 1, 'nombre' => 'Filtro de Aceite X200',        'precio' =>  14.99, 'imagen_url' => 'https://placehold.co/100x100?text=Filtro'],
            (object)['id' => 2, 'nombre' => 'Pastillas de Freno Delanteras','precio' =>  45.50, 'imagen_url' => 'https://placehold.co/100x100?text=Freno'],
            (object)['id' => 3, 'nombre' => 'Neumático Deportivo R18',      'precio' => 120.75, 'imagen_url' => 'https://placehold.co/100x100?text=Neumatico'],
            (object)['id' => 4, 'nombre' => 'Bujía de Iridio',              'precio' =>   8.99, 'imagen_url' => 'https://placehold.co/100x100?text=Bujia'],
        ]);

        return view('clientes.pedidos.crear', compact('autopartes'));
    }

    public function index()
    {
        $pedidos = $this->getPedidos();
        return view('clientes.pedidos.index', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = $this->getPedidos()->firstWhere('id', (int)$id);
        if (!$pedido) abort(404);

        $pedido->metodo_envio = 'Mensajería Express';
        // Los detalles ya vienen del pedido real de getPedidos(), no se sobreescriben

        return view('clientes.pedidos.show', compact('pedido'));
    }

    public function seguimiento($id)
    {
        $pedido = $this->getPedidos()->firstWhere('id', (int)$id);
        if (!$pedido) abort(404);

        $pedido->metodo_envio           = 'Mensajería Express';
        $pedido->fecha_entrega_estimada = now()->addDays(2);
        // Los detalles ya vienen del pedido real de getPedidos(), no se sobreescriben

        $estados      = ['pendiente', 'confirmado', 'en_camino', 'entregado'];
        $ordenEstados = ['pendiente' => -1, 'confirmado' => 0, 'en_camino' => 1, 'entregado' => 2];
        $indiceActual = $ordenEstados[$pedido->estado] ?? -1;

        return view('clientes.pedidos.seguimiento', compact('pedido', 'estados', 'indiceActual'));
    }

    public function factura($id)
    {
        $pedido = $this->getPedidos()->firstWhere('id', (int)$id);
        if (!$pedido) abort(404);

        $pdf = Pdf::loadView('clientes.pedidos.factura_pdf', compact('pedido'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Factura_Pedido_' . $pedido->id . '.pdf');
    }
}
