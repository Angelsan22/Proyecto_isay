<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\Facade\Pdf; // Descomentar si usas DomPDF

class ClientOrderController extends Controller
{
    /**
     * 1. LISTADO GENERAL (Historial de Pedidos)
     * Ruta: client.orders
     */
    public function index()
    {
        // Datos simulados para la lista de pedidos del historial
        $orders = [
            [
                'id' => '2026-00345',
                'date' => '1 Febrero, 2026',
                'status' => 'en_camino',
                'status_label' => 'En Camino',
                'items' => [
                    ['name' => 'Pinza de Freno Alto Rendimiento', 'qty' => 2, 'price' => 175.00],
                    ['name' => 'Llanta Deport Aleación X-5', 'qty' => 2, 'price' => 175.00]
                ],
                'total' => 350.00,
                'icon' => 'fa-circle-dot'
            ],
            [
                'id' => '2026-00312',
                'date' => '25 Enero, 2026',
                'status' => 'entregado',
                'status_label' => 'Entregado',
                'items' => [
                    ['name' => 'Filtro de Aceite X200', 'qty' => 1, 'price' => 14.99],
                    ['name' => 'Bujía de Iridio NGK', 'qty' => 4, 'price' => 8.99]
                ],
                'total' => 50.95,
                'icon' => 'fa-oil-can'
            ],
            [
                'id' => '2026-00289',
                'date' => '18 Enero, 2026',
                'status' => 'en_proceso',
                'status_label' => 'En Proceso',
                'items' => [
                    ['name' => 'Neumático Deportivo R18', 'qty' => 4, 'price' => 120.75]
                ],
                'total' => 483.00,
                'icon' => 'fa-circle'
            ],
        ];

        return view('client.pedidos', compact('orders'));
    }

    /**
     * 2. VISTA DEL CARRITO
     * Muestra los productos seleccionados antes de confirmar el pedido.
     */
    public function cart()
    {
        // En un caso real, esto vendría de session('cart') o de la BD
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Bujía de Iridio NGK',
                'sku' => 'MAC-9912',
                'price' => 45.00,
                'qty' => 4,
                'image' => 'fa-gears'
            ]
        ];

        $subtotal = 180.00;
        $iva = $subtotal * 0.16;
        $total = $subtotal + $iva;

        return view('client.carrito', compact('cartItems', 'subtotal', 'iva', 'total'));
    }

    /**
     * 3. PROCESAR EL PEDIDO (Confirmar compra)
     */
    public function store(Request $request)
    {
        // Lógica para guardar el pedido en la base de datos
        // $order = Order::create([...]);

        return redirect()->route('client.orders')
                         ->with('success', '¡Pedido realizado con éxito! Puedes seguir el estado en tu historial.');
    }

    /**
     * 4. DETALLE EXTENDIDO DE UN PEDIDO
     */
    public function show($id)
    {
        $order = (object)[
            'id' => $id,
            'order_number' => $id,
            'date' => '01/02/2026',
            'status' => 'en_camino',
            'shipping_method' => 'Mensajería Express',
            'estimated_delivery' => '05/02/2026',
            'subtotal' => 1250.00,
            'shipping_cost' => 25.00,
            'total' => 1275.00
        ];

        return view('client.detallePedido', compact('order'));
    }

    /**
     * 5. SEGUIMIENTO (TRACKING)
     */
    public function tracking($id)
    {
        return view('client.seguimientoPedido', compact('id'));
    }

    /**
     * 6. GENERAR RECIBO / FACTURA (PDF)
     */
    public function downloadReceipt($id)
    {
        $order = (object)[
            'id' => $id,
            'user' => Auth::user() ?? (object)['name' => 'Juan Pérez', 'email' => 'juan@email.com', 'phone' => '555-1234']
        ];

        return view('client.pedidoRecibo', compact('order'));
    }

    /**
     * 7. VISTA DE CATÁLOGO
     */
    public function catalog()
    {
        return view('client.crearPedido');
    }
}