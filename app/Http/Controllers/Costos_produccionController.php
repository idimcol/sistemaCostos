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

    private function calcularUtilidadMargen($sdp)
    {
        $total = $sdp->articulos->sum(function ($articulo) {
            return $articulo->pivot->cantidad * $articulo->pivot->precio;
        });

        $totalDirectas = $sdp->costosProduccion->sum(function ($costo) {
            return $costo->materiasPrimasDirectas->sum(function ($mp) {
                return $mp->pivot->cantidad * $mp->precio_unit;
            });
        });

        $totalIndirectas = $sdp->costosProduccion->sum(function ($costo) {
            return $costo->materiasPrimasIndirectas->sum(function ($mp) {
                return $mp->pivot->cantidad * $mp->precio_unit;
            });
        });

        $totalManoObra = $sdp->costosProduccion->sum('mano_obra_directa');
        $totalHoras = $sdp->costosProduccion->sum('horas');

        $cifs = Cif::first();
        $sumaCif = $cifs->MOI + $cifs->GOI + $cifs->OCI;
        $totalCif = $sumaCif * $totalHoras;

        $utilidadBruta = $total - $totalDirectas - $totalIndirectas - $totalManoObra - $totalCif;
        $margenBruto = $utilidadBruta / $total; // Asegúrate de evitar la división por cero.

        return compact('utilidadBruta', 'margenBruto', 'total', 'totalDirectas', 'totalIndirectas', 'totalManoObra', 'totalCif');
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

        $costoProduccion = $sdp->costosProduccion()->first();

        $totalDirectas = 0;
        $totalIndirectas = 0;

        // Obtener las materias primas directas asociadas a la SDP
        $materiasPrimasDirectas = $costoProduccion->materiasPrimasDirectas()
            ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_prima_directa_id', 'costos_produccion_id',)
            ->get();

        // Obtener las materias primas indirectas asociadas a la SDP
        $materiasPrimasIndirectas = $costoProduccion->materiasPrimasIndirectas()
            ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_indirecta_id', 'costos_produccion_id')
            ->get();

        $materiasPrimasDirectasSubtotales = $materiasPrimasDirectas->map(function ($matriaPrimaDirecta) use (&$totalDirectas) {
            $subtotal = $matriaPrimaDirecta->pivot->cantidad * $matriaPrimaDirecta->precio_unit;
            $totalDirectas += $subtotal;

            return $matriaPrimaDirecta->setAttribute('subtotal', $subtotal);
        });
        
        $materiasPrimasIndirectasSubtotales = $materiasPrimasIndirectas->map(function ($materiaPrimaIndirecta) use (&$totalIndirectas) {
            $subtotal = $materiaPrimaIndirecta->pivot->cantidad * $materiaPrimaIndirecta->precio_unit;
            $totalIndirectas += $subtotal;

            return $materiaPrimaIndirecta->setAttribute('subtotal', $subtotal);
        });

        $totalManoObra = $sdp->costosProduccion->sum('mano_obra_directa');
        // $totalCif = $sdp->costosProduccion->sum('costos_indirectos_fabrica');
        $totalHoras = $sdp->costosProduccion->sum('horas');

        $cifs = Cif::first();

        $moi = $cifs->MOI;
        $goi = $cifs->GOI;
        $oci = $cifs->OCI;

        $sumaCif = $moi + $goi + $oci;

        $totalCif = $sumaCif * $totalHoras;

        $utilidadBruta = $total - $totalDirectas - $totalIndirectas - $totalManoObra - $totalCif;
        $margenBruto = ($utilidadBruta / $total) * 1;


        return view('costos_produccion.show', compact('sdp', 'articulosConSubtotales', 'total', 
                                                        'costosProduccion', 'totalManoObra', 'totalCif',
                                                        'materiasPrimasDirectasSubtotales', 'totalDirectas',
                                                        'materiasPrimasIndirectasSubtotales', 'totalIndirectas',
                                                        'utilidadBruta', 'margenBruto', 'sumaCif','totalHoras',
                                                        'moi', 'goi', 'oci'));
    }

    public function resumen($id) {

        $sdp = SDP::where('numero_sdp', $id)->findOrFail($id);


        $costosProduccion = $sdp->costosProduccion->groupBy(function ($item) {
            return "{$item->servicio}-{$item->operario}-{$item->articulo}"; // Agrupar por servicio, operario y artículo
        })->map(function ($group) {
            return [
                'horas' => $group->sum('horas'), // Sumar las horas
                'mano_obra_directa' => $group->sum('mano_obra_directa') // Sumar la mano de obra directa
            ];
        })->sortBy(function ($item, $key) {
            $parts = explode('-', $key); // Dividir la clave
            return $parts[0]; // Ordenar por servicio
        });

        $costoProduccion = $sdp->costosProduccion()->first();

        $totalDirectas = 0;
        $totalIndirectas = 0;

        $materiasPrimasDirectas = $costoProduccion->materiasPrimasDirectas()
            ->withPivot('cantidad', 'proveedor', 'fecha_compra', 'articulo_id', 'articulo_descripcion', 'materia_prima_directa_id', 'costos_produccion_id',)
            ->get();

        // Obtener las materias primas indirectas asociadas a la SDP
        $materiasPrimasIndirectas = $costoProduccion->materiasPrimasIndirectas()
            ->withPivot('cantidad', 'proveedor', 'fecha_compra', 'articulo_id', 'articulo_descripcion', 'materia_indirecta_id', 'costos_produccion_id')
            ->get();

        $materiasPrimasDirectasSubtotales = $materiasPrimasDirectas->map(function ($matriaPrimaDirecta) use (&$totalDirectas) {
            $subtotal = $matriaPrimaDirecta->pivot->cantidad * $matriaPrimaDirecta->precio_unit;
            $totalDirectas += $subtotal;

            return $matriaPrimaDirecta->setAttribute('subtotal', $subtotal);
        });
        
        $materiasPrimasIndirectasSubtotales = $materiasPrimasIndirectas->map(function ($materiaPrimaIndirecta) use (&$totalIndirectas) {
            $subtotal = $materiaPrimaIndirecta->pivot->cantidad * $materiaPrimaIndirecta->precio_unit;
            $totalIndirectas += $subtotal;

            return $materiaPrimaIndirecta->setAttribute('subtotal', $subtotal);
        });

        $calculos = $this->calcularUtilidadMargen($sdp);

        return view('costos_produccion.resumen', array_merge([
            'sdp' => $sdp, 'costosProduccion' => $costosProduccion,
            'materiasPrimasDirectasSubtotales' => $materiasPrimasDirectasSubtotales,
            'materiasPrimasIndirectasSubtotales' => $materiasPrimasIndirectasSubtotales,
            'totalDirectas' => $totalDirectas, 'totaIndirectas' => $totalIndirectas,], $calculos));
    }
}
