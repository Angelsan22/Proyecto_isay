<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    public function agregarAlCarrito(Request $request, \App\Services\ApiProductoService $productoService)
    {
        $request->validate([
            'autoparte_id' => 'required',
            'cantidad'     => 'required|integer|min:1'
        ]);

        $id  = $request->autoparte_id;
        $qty = (int) $request->cantidad;
        $producto = $productoService->obtenerPorId((int)$id);
        if (!$producto || $producto->stock <= 0) {
            return back()->with('error', 'Lo sentimos, este producto ya no cuenta con existencias.');
        }

        if ($qty > $producto->stock) {
            return back()->with('error', "Solo contamos con {$producto->stock} unidades disponibles.");
        }

        $carrito = session()->get('carrito', []);
        
        if (isset($carrito[$id])) {
            $nuevaCantidad = $carrito[$id] + $qty;
            if ($nuevaCantidad > $producto->stock) {
                return back()->with('error', "No puedes añadir más unidades. El stock máximo es de {$producto->stock}.");
            }
            $carrito[$id] = $nuevaCantidad;
        } else {
            $carrito[$id] = $qty;
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto añadido al carrito correctamente.');
    }

    public function actualizarCantidad(Request $request, \App\Services\ApiProductoService $productoService)
    {
        $request->validate([
            'autoparte_id' => 'required',
            'cantidad'     => 'required|integer|min:1'
        ]);

        $carrito = session()->get('carrito', []);
        $id      = $request->autoparte_id;
        $qty     = (int) $request->cantidad;
        
        if (isset($carrito[$id])) {
            $producto = $productoService->obtenerPorId((int)$id);
            if ($producto && $qty > $producto->stock) {
                $qty = $producto->stock;
            }
            $carrito[$id] = $qty;
            session()->put('carrito', $carrito);
        }

        return response()->json(['success' => true]);
    }

    public function checkout(\App\Services\ApiProductoService $productoService)
    {
        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('cliente.catalogo.index')->with('error', 'Tu carrito está vacío.');
        }

        $productosApi = $productoService->obtenerTodos();
        $items = [];
        $subtotal = 0;

        foreach ($carrito as $id => $qty) {
            $prod = $productosApi->firstWhere('id', (int) $id);
            if ($prod) {
                $items[] = [
                    'id' => $prod->id,
                    'nombre' => $prod->nombre,
                    'precio' => $prod->precio,
                    'cantidad' => $qty,
                    'imagen' => $prod->imagen_url
                ];
                $subtotal += $prod->precio * $qty;
            }
        }

        $iva = $subtotal * 0.16;
        $total = $subtotal + $iva + 25;

        return view('clientes.pedidos.checkout', compact('items', 'subtotal', 'iva', 'total'));
    }

    public function store(
        Request $request, 
        \App\Services\ApiPedidoService $pedidoService, 
        \App\Services\ApiProductoService $productoService
    ) {
        if ($request->has('num_tarjeta')) {
            $request->merge([
                'num_tarjeta' => str_replace(' ', '', $request->num_tarjeta)
            ]);
        }
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'direccion'       => 'required|string|max:255',
            'ciudad'          => 'required|string|max:100',
            'cp'              => 'required|string|max:10',
            'telefono'        => 'required|string|max:20',
            'num_tarjeta'     => 'required|string|size:16',
            'expiracion'      => 'required|string', 
            'cvv'             => 'required|string|size:3',
        ]);

        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('cliente.catalogo.index')->with('error', 'El carrito está vacío.');
        }
        $autopartes = $productoService->obtenerTodos();
        $itemsApi = [];
        $subtotal = 0;

        foreach ($carrito as $id => $qty) {
            $producto = $autopartes->firstWhere('id', (int) $id);
            if ($producto) {
                $subtotal += $producto->precio * $qty;
                $itemsApi[] = [
                    'producto_id'    => (int) $producto->id,
                    'cantidad'       => (int) $qty,
                    'precio_unitario' => (int) $producto->precio
                ];
            }
        }

        $iva = $subtotal * 0.16;
        $totalFinal = $subtotal + $iva + 25;
        $user = auth()->user();
        $payload = [
            'cliente_id'      => $user->fastapi_id,
            'total'           => (int) $totalFinal,
            'estatus'         => 'En Proceso',
            'direccion_envio' => $request->direccion,
            'ciudad'          => $request->ciudad,
            'codigo_postal'   => $request->cp,
            'telefono'        => $request->telefono,
            'metodo_pago'     => 'Tarjeta',
            'items'           => $itemsApi
        ];
        try {
            $exito = $pedidoService->crearPedido($payload);

            if ($exito) {
                \Illuminate\Support\Facades\Cache::forget('api_productos_todos');
                session()->forget('carrito');

                return redirect()->route('cliente.pedidos.index')
                                 ->with('success', '¡Compra realizada con éxito! Tu pedido está en camino.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error en proceso de pago: ' . $e->getMessage());
        }

        return back()->withInput()->with('error', 'Hubo un problema al procesar tu pedido. Por favor, verifica tus datos e intenta de nuevo.');
    }

    public function eliminarDelCarrito(Request $request)
    {
        $request->validate([
            'autoparte_id' => 'required'
        ]);

        $carrito = session()->get('carrito', []);
        $id      = $request->autoparte_id;

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function crear(\App\Services\ApiProductoService $apiService)
    {
        $autopartes = $apiService->obtenerTodos();
        $carritoSesion = session()->get('carrito', []);
        $itemsCarrito = [];
        
        foreach ($carritoSesion as $id => $qty) {
            $producto = $autopartes->firstWhere('id', (int) $id);
            if ($producto) {
                $itemsCarrito[] = [
                    'id'       => $producto->id,
                    'nombre'   => $producto->nombre,
                    'precio'   => $producto->precio,
                    'cantidad' => $qty,
                    'stock'    => $producto->stock_actual,
                    'imagen'   => $producto->imagen_url ?? 'https://placehold.co/100x100?text=Auto'
                ];
            }
        }

        return view('clientes.pedidos.crear', compact('autopartes', 'itemsCarrito'));
    }

    public function index(\App\Services\ApiPedidoService $pedidoService)
    {
        $user = auth()->user();
        $rawPedidos = $pedidoService->obtenerMisPedidos($user->fastapi_id);
        $pedidos = collect($rawPedidos)->map(function($p) {
            $apiStatus = strtolower(trim($p['estatus'] ?? 'en proceso'));
            $viewStatus = match($apiStatus) {
                'entregado'   => 'entregado',
                'enviado'     => 'en_camino',
                'en proceso'  => 'confirmado',
                'cancelado'   => 'cancelado',
                default       => 'pendiente',
            };

            return (object)[
                'id'         => $p['id'],
                'created_at' => \Carbon\Carbon::parse($p['fecha'] ?? now()),
                'estado'     => $viewStatus,
                'total'      => $p['total'] ?? 0,
                'detalles'   => collect($p['detalles'] ?? [])->map(function($d) {
                    return (object)[
                        'cantidad'  => $d['cantidad'],
                        'subtotal'  => $d['cantidad'] * $d['precio_unitario'],
                        'autoparte' => (object)[
                            'nombre'     => $d['producto']['nombre'] ?? 'Producto',
                            'imagen_url' => !empty($d['producto']['imagen']) ? 'http://localhost:5000/static/' . ltrim($d['producto']['imagen'], '/') : 'https://placehold.co/80x80/e8671b/ffffff?text=' . urlencode($d['producto']['nombre'] ?? '')
                        ]
                    ];
                })
            ];
        })->sortByDesc('created_at');

        return view('clientes.pedidos.index', compact('pedidos'));
    }

    public function show($id, \App\Services\ApiPedidoService $pedidoService)
    {
        $rawPedido = $pedidoService->obtenerDetalle((int)$id);
        if (!$rawPedido) abort(404);

        $apiStatus = strtolower(trim($rawPedido['estatus'] ?? 'en proceso'));
        $viewStatus = match($apiStatus) {
            'entregado'   => 'entregado',
            'enviado'     => 'en_camino',
            'en proceso'  => 'confirmado',
            'cancelado'   => 'cancelado',
            default       => 'pendiente',
        };
        $pedido = (object)[
            'id'              => $rawPedido['id'],
            'created_at'      => \Carbon\Carbon::parse($rawPedido['fecha'] ?? now()),
            'estado'          => $viewStatus,
            'total'           => $rawPedido['total'] ?? 0,
            'direccion_envio' => $rawPedido['direccion_envio'] ?? 'N/A',
            'ciudad'          => $rawPedido['ciudad'] ?? '',
            'cp'              => $rawPedido['codigo_postal'] ?? '',
            'telefono'        => $rawPedido['telefono'] ?? '',
            'metodo_pago'     => $rawPedido['metodo_pago'] ?? 'Tarjeta',
            'metodo_envio'    => 'Mensajería Express',
            'detalles'        => collect($rawPedido['detalles'] ?? [])->map(function($d) {
                return (object)[
                    'cantidad'        => $d['cantidad'],
                    'precio_unitario' => $d['precio_unitario'],
                    'subtotal'        => $d['cantidad'] * $d['precio_unitario'],
                    'autoparte'      => (object)[
                        'nombre'     => $d['producto']['nombre'] ?? 'Producto',
                        'imagen_url' => !empty($d['producto']['imagen']) ? 'http://localhost:5000/static/' . ltrim($d['producto']['imagen'], '/') : 'https://placehold.co/100x100/e8671b/ffffff?text=' . urlencode($d['producto']['nombre'] ?? '')
                    ]
                ];
            })
        ];

        return view('clientes.pedidos.show', compact('pedido'));
    }

    public function seguimiento($id, \App\Services\ApiPedidoService $pedidoService)
    {
        $rawPedido = $pedidoService->obtenerDetalle((int)$id);
        if (!$rawPedido) abort(404);

        $apiStatus = strtolower(trim($rawPedido['estatus'] ?? 'en proceso'));
        $viewStatus = match($apiStatus) {
            'entregado'   => 'entregado',
            'enviado'     => 'en_camino',
            'en proceso'  => 'confirmado',
            'cancelado'   => 'cancelado',
            default       => 'pendiente',
        };

        $pedido = (object)[
            'id'                     => $rawPedido['id'],
            'created_at'             => \Carbon\Carbon::parse($rawPedido['fecha'] ?? now()),
            'estado'                 => $viewStatus,
            'metodo_envio'           => 'Mensajería Express',
            'fecha_entrega_estimada' => now()->addDays(2),
            'detalles'               => collect($rawPedido['detalles'] ?? [])->map(function($d) {
                return (object)[
                    'cantidad'  => $d['cantidad'],
                    'autoparte' => (object)[
                        'nombre' => $d['producto']['nombre'] ?? 'Producto'
                    ]
                ];
            })
        ];

        $estados      = ['pendiente', 'confirmado', 'en_camino', 'entregado'];
        $ordenEstados = ['pendiente' => -1, 'confirmado' => 0, 'en_camino' => 1, 'entregado' => 2, 'cancelado' => -2];
        $indiceActual = $ordenEstados[$pedido->estado] ?? -1;

        return view('clientes.pedidos.seguimiento', compact('pedido', 'estados', 'indiceActual'));
    }

    public function factura($id, \App\Services\ApiPedidoService $pedidoService)
    {
        $rawPedido = $pedidoService->obtenerDetalle((int)$id);
        if (!$rawPedido) abort(404);

        $apiStatus = strtolower(trim($rawPedido['estatus'] ?? 'en proceso'));
        $viewStatus = match($apiStatus) {
            'entregado'   => 'entregado',
            'enviado'     => 'en_camino',
            'en proceso'  => 'confirmado',
            'cancelado'   => 'cancelado',
            default       => 'pendiente',
        };

        $pedido = (object)[
            'id'              => $rawPedido['id'],
            'created_at'      => \Carbon\Carbon::parse($rawPedido['fecha'] ?? now()),
            'estado'          => $viewStatus,
            'total'           => $rawPedido['total'] ?? 0,
            'direccion_envio' => $rawPedido['direccion_envio'] ?? 'N/A',
            'ciudad'          => $rawPedido['ciudad'] ?? '',
            'telefono'        => $rawPedido['telefono'] ?? '',
            'metodo_pago'     => $rawPedido['metodo_pago'] ?? 'Tarjeta',
            'metodo_envio'    => 'Mensajería Express',
            'detalles'        => collect($rawPedido['detalles'] ?? [])->map(function($d) {
                return (object)[
                    'cantidad'        => $d['cantidad'],
                    'precio_unitario' => $d['precio_unitario'],
                    'subtotal'        => $d['cantidad'] * $d['precio_unitario'],
                    'autoparte'       => (object)['nombre' => $d['producto']['nombre'] ?? 'Producto']
                ];
            })
        ];

        $pdf = Pdf::loadView('clientes.pedidos.factura_pdf', compact('pedido'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Factura_Pedido_' . $pedido->id . '.pdf');
    }

    public function cancelar($id, \App\Services\ApiPedidoService $pedidoService)
    {
        if ($pedidoService->cancelarPedido((int)$id)) {
            return redirect()->route('cliente.pedidos.show', $id)
                           ->with('success', '¡Pedido cancelado exitosamente!');
        }

        return redirect()->route('cliente.pedidos.show', $id)
                       ->with('error', 'No se pudo cancelar el pedido. Intenta más tarde.');
    }
}
