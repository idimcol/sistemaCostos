<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Cif;
use App\Models\Cliente;
use App\Models\CostosProduccion;
use App\Models\CostosSdpProduccion;
use App\Models\idimcol;
use App\Models\MateriaPrimaDirecta;
use App\Models\MateriaPrimaIndirecta;
use App\Models\Operativo;
use App\Models\SDP;
use App\Models\Tiempos_produccion;
use App\Models\TiemposProduccion;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Costos_produccionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:ver sdp costos')->only('index');
        $this->middleware('can:ver costos sdp')->only('show');
        $this->middleware('can:ver resumen costos sdp')->only('resumen');
    }
    public function index()
    {
        // Obtener todas las SDPs con relaciones necesarias
        $sdps = SDP::with('vendedores', 'clientes')->where('estado', 'abierto')->get();
        $tiempos_produccion = TiemposProduccion::with('operativo')->get();

        return view('costos_produccion.index', compact('sdps', 'tiempos_produccion'));
    }

    public function obtenerArticulosConIndices($id)
    {
        // Obtener la SDP por su ID
        $sdp = Sdp::with('articulos')->find($id);

        if (!$sdp) {
            // Manejar el caso donde la SDP no existe
            return [];
        }

        // Crear una colección para almacenar los artículos con sus índices
        $articulosConIndices = [];

        foreach ($sdp->articulos as $index => $articulo) {
            $articulosConIndices[] = [
                'articulo' => $articulo->descripcion,
                'index_articulo' => $index // Captura del índice
            ];
        }

        return $articulosConIndices;
    }

    public function show($id)
    {
        $sdp = SDP::where('numero_sdp', $id)->findOrFail($id);

        $total = 0;

        $articulosConSubtotales = $sdp->articulos->map(function ($articulo) use (&$total) {
            // Calcular el subtotal del artículo
            $subtotal = $articulo->pivot->cantidad * $articulo->pivot->precio;
            // Acumulando el subtotal al total general
            $total += $subtotal;
            // Agregar el subtotal al artículo
            return $articulo->setAttribute('subtotal', $subtotal);
        });

        $costosProduccion = $sdp->costosProduccion->groupBy(function ($item) {
            return $item->operario . '-' . $item->articulo . '-' . $item->servicio;  // Agrupar por operario, articulo y servicio
        })->map(function ($group) {
            return [
                'horas' => $group->sum('horas'), // Sumar las horas
                'mano_obra_directa' => $group->sum('mano_obra_directa') // Sumar la mano de obra directa
            ];
        });

        $totalManoObra = $sdp->costosProduccion->sum('mano_obra_directa');
        $totalCif = $sdp->costosProduccion->sum('costos_indirectos_fabrica');

        return view('costos_produccion.show', compact('sdp', 'articulosConSubtotales', 'total', 'costosProduccion', 'totalManoObra', 'totalCif'));
    }

    public function resumen($id) {}
}
