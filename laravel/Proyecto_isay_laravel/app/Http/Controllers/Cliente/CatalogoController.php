<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    private function getAutopartes()
    {
        return collect([
            (object)['id' => 1, 'nombre' => 'Disco de Freno Cerámico',          'sku' => 'ABC12345', 'precio' => 120.50, 'imagen_url' => 'https://placehold.co/200x200?text=Freno',   'categoria' => (object)['id' => 1, 'nombre' => 'Suspensión'],   'marca' => (object)['id' => 1, 'nombre' => 'Brembo']],
            (object)['id' => 2, 'nombre' => 'Bujía de Iridio Alto Rendimiento', 'sku' => 'ABC12346', 'precio' =>  45.00, 'imagen_url' => 'https://placehold.co/200x200?text=Bujia',   'categoria' => (object)['id' => 2, 'nombre' => 'Motor'],        'marca' => (object)['id' => 2, 'nombre' => 'NGK']],
            (object)['id' => 3, 'nombre' => 'Filtro de Aire Deportivo',         'sku' => 'ABC12347', 'precio' =>  35.00, 'imagen_url' => 'https://placehold.co/200x200?text=Filtro',  'categoria' => (object)['id' => 2, 'nombre' => 'Motor'],        'marca' => (object)['id' => 3, 'nombre' => 'K&N']],
            (object)['id' => 4, 'nombre' => 'Faro Denalter LED',                'sku' => 'ABC12348', 'precio' => 120.50, 'imagen_url' => 'https://placehold.co/200x200?text=Faro',    'categoria' => (object)['id' => 3, 'nombre' => 'Electricidad'], 'marca' => (object)['id' => 4, 'nombre' => 'Philips']],
            (object)['id' => 5, 'nombre' => 'Batería de Gel',                   'sku' => 'ABC12350', 'precio' => 200.00, 'imagen_url' => 'https://placehold.co/200x200?text=Bateria', 'categoria' => (object)['id' => 3, 'nombre' => 'Electricidad'], 'marca' => (object)['id' => 5, 'nombre' => 'Bosch']],
        ]);
    }

    public function index(Request $request)
    {
        $autopartes = $this->getAutopartes();

        if ($request->filled('categoria')) {
            $autopartes = $autopartes->filter(fn($a) => $a->categoria->id == $request->categoria);
        }
        if ($request->filled('marca')) {
            $autopartes = $autopartes->filter(fn($a) => $a->marca->id == $request->marca);
        }
        if ($request->filled('buscar')) {
            $buscar = strtolower($request->buscar);
            $autopartes = $autopartes->filter(fn($a) => str_contains(strtolower($a->nombre), $buscar) || str_contains(strtolower($a->sku), $buscar));
        }

        $categorias = collect([
            (object)['id' => 1, 'nombre' => 'Suspensión'],
            (object)['id' => 2, 'nombre' => 'Motor'],
            (object)['id' => 3, 'nombre' => 'Electricidad'],
        ]);

        $marcas = collect([
            (object)['id' => 1, 'nombre' => 'Brembo'],
            (object)['id' => 2, 'nombre' => 'NGK'],
            (object)['id' => 3, 'nombre' => 'K&N'],
            (object)['id' => 4, 'nombre' => 'Philips'],
            (object)['id' => 5, 'nombre' => 'Bosch'],
        ]);

        return view('clientes.catalogo.index', compact('autopartes', 'categorias', 'marcas'));
    }

    public function show($id)
    {
        $autoparte = $this->getAutopartes()->firstWhere('id', (int)$id);

        if (!$autoparte) abort(404);

        $autoparte->stock            = 10;
        $autoparte->numero_parte     = $autoparte->sku;
        $autoparte->descripcion      = "Material de alta calidad\nCompatible con múltiples vehículos\nGarantía de 1 año";
        $autoparte->especificaciones = "Resistente a altas temperaturas\nFácil instalación\nCertificado ISO 9001";
        $autoparte->imagenes         = collect([]);

        return view('clientes.catalogo.show', compact('autoparte'));
    }
}
