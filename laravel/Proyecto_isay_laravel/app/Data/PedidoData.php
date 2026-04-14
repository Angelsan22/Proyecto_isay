<?php

namespace App\Data;

use Carbon\Carbon;

/**
 *  Proveedor estático de pedidos de demostración.
 *  Cuando se conecte a la base de datos real, se sustituirá por queries Eloquent.
 */
class PedidoData
{
    /**
     * Devuelve la colección de pedidos ficticios.
     */
    public static function all(): \Illuminate\Support\Collection
    {
        return collect([
            (object)[
                'id'         => 1,
                'created_at' => Carbon::now()->subDays(3),
                'estado'     => 'entregado',
                'total'      => 350.00,
                'detalles'   => collect([
                    (object)[
                        'autoparte' => (object)[
                            'nombre'    => 'Pinza de Freno de Alto Rendimiento',
                            'imagen_url'=> 'https://placehold.co/80x80?text=Freno',
                        ],
                        'cantidad' => 2,
                        'subtotal' => 350.00,
                    ],
                ]),
            ],
            (object)[
                'id'         => 2,
                'created_at' => Carbon::now()->subDays(1),
                'estado'     => 'en_camino',
                'total'      => 613.99,
                'detalles'   => collect([
                    (object)[
                        'autoparte' => (object)[
                            'nombre'    => 'Llanta Deport Aleación X-5',
                            'imagen_url'=> 'https://placehold.co/80x80?text=Llanta',
                        ],
                        'cantidad' => 2,
                        'subtotal' => 613.99,
                    ],
                ]),
            ],
        ]);
    }

    /**
     * Busca un pedido por su ID.
     */
    public static function find(int $id): ?object
    {
        return static::all()->firstWhere('id', $id);
    }

    /**
     * Productos disponibles para "Crear Pedido".
     */
    public static function productosDisponibles(): \Illuminate\Support\Collection
    {
        return collect([
            (object)['id' => 1, 'nombre' => 'Filtro de Aceite X200',        'precio' =>  14.99, 'imagen_url' => 'https://placehold.co/100x100?text=Filtro'],
            (object)['id' => 2, 'nombre' => 'Pastillas de Freno Delanteras','precio' =>  45.50, 'imagen_url' => 'https://placehold.co/100x100?text=Freno'],
            (object)['id' => 3, 'nombre' => 'Neumático Deportivo R18',      'precio' => 120.75, 'imagen_url' => 'https://placehold.co/100x100?text=Neumatico'],
            (object)['id' => 4, 'nombre' => 'Bujía de Iridio',              'precio' =>   8.99, 'imagen_url' => 'https://placehold.co/100x100?text=Bujia'],
        ]);
    }
}
