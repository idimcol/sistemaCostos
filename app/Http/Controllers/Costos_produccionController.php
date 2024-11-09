<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Cif;
use App\Models\Cliente;
use App\Models\CostosProduccion;
use App\Models\idimcol;
use App\Models\MateriaPrimaDirecta;
use App\Models\MateriaPrimaIndirecta;
use App\Models\Operativo;
use App\Models\SDP;
use App\Models\Tiempos_produccion;
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
        $tiempos_produccion = Tiempos_produccion::with('operativo')->get();

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
        // Obtener el SDP junto con las relaciones necesarias
        $sdp = SDP::with('articulos', 'serviciosCostos.servicio', 'costosProduccion', 'articulos.materiasPrimasDirectas', 'articulos.materiasPrimasIndirectas')
        ->where('numero_sdp', $id)->where('estado', 'abierto')
        ->firstOrFail();
        Log::info('SDP obtenido:', ['sdp' => $sdp]);

        $costoProduccion = $sdp->costosProduccion()->first();

        $operariosConTiempos = collect();

        $tiemposProduccionCargados = false;

        $materiasPrimasDirectas = collect();
        $materiasPrimasIndirectas = collect();

        if ($costoProduccion) {
            $materiasPrimasDirectas = $costoProduccion->materiasPrimasDirectas()
                ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_prima_directa_id', 'costos_produccion_id',)
                ->get();
            $materiasPrimasIndirectas = $costoProduccion->materiasPrimasIndirectas()
                ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_indirecta_id', 'costos_produccion_id')
                ->get();
            
            $totaldirectas = 0;
            
            $subtotalDirectas = $materiasPrimasDirectas->map(function ($directa) use (&$totaldirectas){
                $subtotal = $directa->pivot->cantidad * $directa->precio_unit;
                $totaldirectas += $subtotal;
                
                return $directa->setAttribute('subtotal', $subtotal);
            });

            $totalIndeirectas = 0;

            $subtotalIndirectas = $materiasPrimasIndirectas->map(function ($indirecta) use (&$totalIndeirectas){
                $subtotal = $indirecta->pivot->cantidad * $indirecta->precio_unit;
                $totalIndeirectas += $subtotal;

                return $indirecta->setAttribute('subtotal', $subtotal);
            });
        }

        $total= 0;

        $articulosConSubtotales = $sdp->articulos->map(function ($articulo, $index) use (&$total) {
            // Calcular el subtotal del artículo
            $subtotal = $articulo->pivot->cantidad * $articulo->pivot->precio;
            // Acumulando el subtotal al total general
            $total += $subtotal;
            // Agregar el subtotal al artículo
            return $articulo->setAttribute('subtotal', $subtotal)
                            ->setAttribute('index_articulo', $index + 1);
        });

        $totalTiempos = 0;

        $articulosConTiempos = $sdp->articulos()->whereHas('tiemposProduccion', function ($query) use ($sdp) {
            // Filtrar por la SDP específica
            $query->where('tiempos_produccions.sdp_id', $sdp->numero_sdp);
        })->get();

        $articulosTiemposConSubtotales = $articulosConTiempos->map(function ($articuloTiempo) use (&$totalTiempos) {
            // Calcular el subtotal del artículo
            $subtotal = $articuloTiempo->pivot->cantidad * $articuloTiempo->pivot->precio;
            // Acumulando el subtotal al total general
            $totalTiempos += $subtotal;
            // Agregar el subtotal al artículo
            return $articuloTiempo->setAttribute('subtotal', $subtotal);
        });

        $total = 0;
        $totalTiempos = 0;
        $totalManoObra = 0;
        $totalManoObraServicio = 0;
        $totalHoras = 0;
        $cif = Cif::first();
        $MOI = $cif->MOI;
        $GOI = $cif->GOI;
        $OCI = $cif->OCI;
        $totalCIF = 0;
        $totalHorasPorOperario = [];
        $operariosConTiempos = collect();


        $sdp->articulos->each(function ($articulo) use ($sdp, &$totalHorasPorOperario, &$operariosConTiempos, $MOI, $GOI, $OCI, &$totalCIF, &$totalManoObraServicio, &$totalManoObra, &$totalHoras) {
            $tiemposProduccion = $articulo->tiemposProduccion()->with('operativo.trabajador', 'servicio')->where('articulo_tiempos_produccion.sdp_id', $sdp->numero_sdp)->get();
    
            foreach ($tiemposProduccion as $tiempo) {
                $operario = $tiempo->operativo;
                $trabajador = $operario->trabajador;
                $sueldo = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->sueldo ?? 0;
    
                $horas = $tiempo->horas;
                $totalHoras += $horas;
                
                // Agrupar horas por operario
                $operarioId = $operario->trabajador_id;
                $totalHorasPorOperario[$operarioId] = ($totalHorasPorOperario[$operarioId] ?? 0) + $horas;
    
                $totalCIF = ($MOI * $totalHoras) + ($GOI * $totalHoras) + ($OCI * $totalHoras);
                $manoDeObraDirecta = CostosProduccion::where('tiempo_produccion_id', $tiempo->id)->where('sdp_id', $sdp->numero_sdp)->first()->mano_obra_directa ?? 0;
                $totalManoObra += $manoDeObraDirecta;
    
                // Añadir datos del operario a la colección
                $operariosConTiempos->push([
                    'codigo' => $operario->codigo ?? 'No definido',
                    'nombre' => $operario->operario ?? 'No definido',
                    'sueldo' => $sueldo,
                    'total_horas' => $totalHorasPorOperario[$operarioId],
                    'mano_obra_directa' => $manoDeObraDirecta,
                    'articulo' => $articulo->descripcion,
                    'index_articulo' => $articulo->getAttribute('index_articulo')
                ]);
            }
        });
            $totalTiempos = $totalTiempos ?? 0;
            $totalManoObraServicio = $totalManoObraServicio ?? 0;
            $totaldirectas = $totaldirectas ?? 0;
            $totalIndeirectas = $totalIndeirectas ?? 0;
            $totalCIF = $totalCIF ?? 0;

            $utilidadBruta = $totalTiempos - $totalManoObraServicio- $totaldirectas - $totalIndeirectas - $totalCIF;

            if ($totalTiempos > 0) {
                $margenBruto = ($utilidadBruta / $totalTiempos) * 100;
            } else {
                $margenBruto = 0; // Margen bruto es 0 si no hay tiempos
            }

        // Obtener otros datos adicionales (IDIMCOL en tu caso, si es necesario)
        $idimcols = Idimcol::all();

        // Retornar la vista con los datos calculados
        return view('costos_produccion.show', compact(
            'sdp', 
            'totalManoObra', 
            'operariosConTiempos', 
            'total', 
            'articulosConSubtotales', 
            'idimcols',
            'totalManoObraServicio',
            'materiasPrimasDirectas',
            'materiasPrimasIndirectas',
            'MOI',
            'GOI',
            'OCI',
            'totalCIF',
            'totalHoras',
            'totaldirectas',
            'totalIndeirectas',
            'totalTiempos',
            'articulosTiemposConSubtotales',
            
            'utilidadBruta',
            'margenBruto'
        ));
    }

public function resumen($id)
    {
        
        // Obtener el SDP junto con las relaciones necesarias
        $sdp = SDP::with('articulos', 'serviciosCostos.servicio', 'costosProduccion', 'articulos.materiasPrimasDirectas', 'articulos.materiasPrimasIndirectas')
        ->where('numero_sdp', $id)
        ->firstOrFail();
        Log::info('SDP obtenido:', ['sdp' => $sdp]);


        $costoProduccion = $sdp->costosProduccion()->first();

        $operariosConTiempos = collect();

        $tiemposProduccionCargados = false;

        $materiasPrimasDirectas = collect();
        $materiasPrimasIndirectas = collect();

        if ($costoProduccion) {
            $materiasPrimasDirectas = $costoProduccion->materiasPrimasDirectas()
                ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_prima_directa_id', 'costos_produccion_id',)
                ->get();
            $materiasPrimasIndirectas = $costoProduccion->materiasPrimasIndirectas()
                ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_indirecta_id', 'costos_produccion_id')
                ->get();
            
            $totaldirectas = 0;
            
            $subtotalDirectas = $materiasPrimasDirectas->map(function ($directa) use (&$totaldirectas){
                $subtotal = $directa->pivot->cantidad * $directa->precio_unit;
                $totaldirectas += $subtotal;
                
                return $directa->setAttribute('subtotal', $subtotal);
            });

            $totalIndeirectas = 0;

            $subtotalIndirectas = $materiasPrimasDirectas->map(function ($indirecta) use (&$totalIndeirectas){
                $subtotal = $indirecta->pivot->cantidad * $indirecta->precio_unit;
                $totalIndeirectas += $subtotal;

                return $indirecta->setAttribute('subtotal', $subtotal);
            });
        }

        $total= 0;

        $articulosConSubtotales = $sdp->articulos->map(function ($articulo) use (&$total) {
            // Calcular el subtotal del artículo
            $subtotal = $articulo->pivot->cantidad * $articulo->pivot->precio;
            // Acumulando el subtotal al total general
            $total += $subtotal;
            // Agregar el subtotal al artículo
            return $articulo->setAttribute('subtotal', $subtotal);
        });

        $totalTiempos = 0;

        $articulosConTiempos = $sdp->articulos()->whereHas('tiemposProduccion', function ($query) use ($sdp) {
            // Filtrar por la SDP específica
            $query->where('tiempos_produccions.sdp_id', $sdp->numero_sdp);
        })->get();

        $articulosTiemposConSubtotales = $articulosConTiempos->map(function ($articuloTiempo) use (&$totalTiempos) {
            // Calcular el subtotal del artículo
            $subtotal = $articuloTiempo->pivot->cantidad * $articuloTiempo->pivot->precio;
            // Acumulando el subtotal al total general
            $totalTiempos += $subtotal;
            // Agregar el subtotal al artículo
            return $articuloTiempo->setAttribute('subtotal', $subtotal);
        });

        $totalManoObra = 0;
        $totalManoObraServicio = 0;
        $totalHoras = 0;
        $cif = Cif::first();

        $MOI = $cif->MOI;
        $GOI = $cif->GOI;
        $OCI = $cif->OCI;
        $NMH = $cif->NMH;
        $totalCIF= 0;

        $operariosConTiempos = collect();

        $sdp->articulos->each(function ($articulo) use ($sdp,  &$operariosConTiempos, $MOI, $GOI, $OCI, &$totalCIF, &$totalManoObraServicio, &$totalManoObra, &$totalHoras) {

            $tiemposProduccion = $articulo->tiemposProduccion()->with('operativo.trabajador')->where('articulo_tiempos_produccion.sdp_id', $sdp->numero_sdp)->get();
    
            foreach ($tiemposProduccion as $tiempo) {
                $operario = $tiempo->operativo;

                $trabajador = $operario->trabajador;

                $sueldo = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->sueldo ?? 0;

                $costoProduccion = CostosProduccion::where('tiempo_produccion_id', $tiempo->id, 'servicio')
                    ->where('sdp_id', $sdp->numero_sdp)
                    ->first();

                $horas = $tiempo->horas;
                $totalHoras += $horas;

                $totalCIF = ($MOI*$totalHoras) + ($GOI*$totalHoras) + ($OCI*$totalHoras);

                $manoDeObraDirecta = $costoProduccion->mano_obra_directa ?? 0;
                $totalManoObra += $manoDeObraDirecta;

                $serviciosCostos = $tiempo->servicios_costos;

                foreach ($serviciosCostos as $servicioCosto) {
                    $valor_servicio = $servicioCosto->valor_servicio;
                    $servicio_nombre = $servicioCosto->servicio->nombre ?? 'Sin Nombre';
                    
                    // Asegúrate de que horas es el valor correcto
                    $servicio_horas = $valor_servicio * $horas; // Este debería ser el cálculo correcto
                
                    // Guardar los detalles de cada servicio
                // dd($detallesServicios);

                $manoObraServicio = $manoDeObraDirecta + $valor_servicio + ($MOI*$horas) + ($GOI*$horas) + ($OCI*$horas);
                $totalManoObraServicio += $manoObraServicio;
    
                if ($operario && $operario->trabajador) {
                    $operariosConTiempos->push([
                        'codigo' => $operario->codigo ?? 'No definido',
                        'nombre' => $operario->operario ?? 'No definido',
                        'sueldo' => $sueldo, 
                        'valor_servicio' => $valor_servicio,
                        'servicio_nombre' => $servicio_nombre ?? 'No definido',
                        'articulo' => $articulo->descripcion,
                        'horas' => $horas,
                        'mano_obra_directa' => $manoDeObraDirecta,
                        'mano_obra_servicio' => $manoObraServicio,
                        'servicio_horas' => $servicio_horas
                    ]);
                }
            }
        }
    });

            $totalTiempos = $totalTiempos ?? 0;
            $totalManoObraServicio = $totalManoObraServicio ?? 0;
            $totaldirectas = $totaldirectas ?? 0;
            $totalIndeirectas = $totalIndeirectas ?? 0;
            $totalCIF = $totalCIF ?? 0;

            $utilidadBruta = $totalTiempos - $totalManoObraServicio- $totaldirectas - $totalIndeirectas - $totalCIF;

            if ($totalTiempos > 0) {
                $margenBruto = ($utilidadBruta / $totalTiempos) * 100;
            } else {
                $margenBruto = 0; // Margen bruto es 0 si no hay tiempos
            }

        // Obtener otros datos adicionales (IDIMCOL en tu caso, si es necesario)
        $idimcols = Idimcol::all();

        return view('costos_produccion.resumen', compact(
            'sdp', 
            'totalManoObra', 
            'operariosConTiempos', 
            'total', 
            'articulosConSubtotales', 
            'idimcols',
            'totalManoObraServicio',
            'materiasPrimasDirectas',
            'materiasPrimasIndirectas',
            'MOI',
            'GOI',
            'OCI',
            'totalCIF',
            'totalHoras',
            'totaldirectas',
            'totalIndeirectas',
            'totalTiempos',
            'articulosTiemposConSubtotales',
            'NMH',
            'utilidadBruta',
            'margenBruto'
        ));
    }
}
