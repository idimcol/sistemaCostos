<?php

namespace App\Http\Controllers;

use App\Enums\Departamento;
use App\Models\Articulo;
use App\Models\ArticuloTiempoProduccion;
use App\Models\CostosProduccion;
use App\Models\CostosSdpProduccion;
use App\Models\Operativo;
use App\Models\Operativos;
use App\Models\SDP;
use App\Models\Servicio;
use App\Models\ServicioCostos;
use App\Models\Tiempos_produccion;
use App\Models\TiemposProduccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TiemposProduccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver tiempos de produccion')->only('groupByOperario');
        $this->middleware('can:crear tiempos de produccion')->only('create');
        $this->middleware('can:editar tiempos de produccion')->only('edit');
        $this->middleware('can:eliminar tiempos de produccion')->only('destroy');
        $this->middleware('can:listar tiempos del operario')->only('index');
        $this->middleware('can:imprimir tiempos del operario')->only('print');
    }

    public function index($codigoOperario)
    {
        $tiempos_produccion = TiemposProduccion::where('operativo_id', $codigoOperario)
            ->with('operativo', 'servicio', 'sdp')
            ->get();

        return view('tiemposproduccion.lista', compact('tiempos_produccion'));
    }

    public function print($id)
    {
        $tiempos_produccion = TiemposProduccion::where('operativo_id', $id)
            ->with('operativo', 'servicio', 'sdp')
            ->get()->groupBy(function ($item) {
                return $item->sdp_id . '-' . $item->proseso_id;
            })->map(function ($group){
                $totalHoras = $group->sum(function($item) {
                    return (strtotime($item->hora_fin) - strtotime($item->hora_inicio)) / 3600;
                });
                $horas = floor($totalHoras);
                $minutos = ($totalHoras - $horas) * 60;
                return [
                    'items' => $group,
                    'total_horas' => sprintf('%02d:%02d', $horas, round($minutos))
                ];
            });

        $totalesPorOperario = DB::table('tiempos_produccions')
            ->select(
                'operativo_id',
                DB::raw('SUM(TIMESTAMPDIFF(HOUR, hora_inicio, hora_fin)) as total_horas_general_operario')
            )
            ->where('operativo_id', $id) // Filtro por operario específico
            ->groupBy('operativo_id')
            ->get();
    
        $totalesPorServicio = DB::table('tiempos_produccions')
            ->select(
                'nombre_servicio',
                DB::raw('SUM(TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fin)) as total_horas_servicio')
        )
            ->where('operativo_id', $id) // Filtro por operario específico
            ->groupBy('nombre_servicio')
            ->get();
        
        foreach ($totalesPorServicio as $servicio) {
            $horas = floor($servicio->total_horas_servicio / 60);
            $minutos = $servicio->total_horas_servicio % 60;
            $servicio->total_horas_servicio = sprintf('%02d:%02d', $horas, $minutos);
        }

        $totalesPorSdp = DB::table('tiempos_produccions')
            ->select(
                'sdp_id',
                DB::raw('SUM(TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fin)) as total_minutos_sdp')
            )->where('operativo_id', $id)->groupBy('sdp_id')->get();
        
        $sdps = SDP::whereIn('numero_sdp', $totalesPorSdp->pluck('sdp_id'))->get()->keyBy('numero_sdp');
        
        $totalesPorSdp->transform(function ($item) use ($sdps) {
            $horas = floor($item->total_minutos_sdp / 60);
            $minutos = $item->total_minutos_sdp % 60;
            $item->sdp_nombre = $sdps[$item->sdp_id]->nombre ?? 'Nombre no disponible';
            $item->total_horas_formateado = sprintf('%02d:%02d', $horas, $minutos);
            return $item;
        });

        $totalGeneralMinutos = DB::table('tiempos_produccions')
            ->where('operativo_id', $id)
            ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fin)) as total_minutos_general'))
            ->value('total_minutos_general');
        
            $horas = floor($totalGeneralMinutos / 60);
            $minutos = $totalGeneralMinutos % 60;
            $totalGeneral = sprintf('%02d:%02d', $horas, $minutos);

        return view('tiemposproduccion.print', compact('tiempos_produccion', 'totalesPorServicio', 'totalGeneral', 'totalesPorOperario', 'totalesPorSdp'));
    }

    public function groupByOperario()
    {
        $tiempos_produccion = TiemposProduccion::with('operativo', 'servicio', 'sdp')
            ->get()->groupBy('operativo_id');
    
        return view('tiemposproduccion.index', compact('tiempos_produccion'));
    }

    public function getArticulos($sdpId)
    {
        // Asegúrate de que el $sdpId sea el id de la tabla sdps, no el numero_sdp
        $sdp = Sdp::with('articulos')->findOrFail($sdpId);

        // Obtener los artículos relacionados
        $articulos = $sdp->articulos;

        // Devolver los artículos como respuesta JSON
        return response()->json($articulos);
    }

    public function getServiciosSdp($numeroSdp)
    {
        $sdp = SDP::with('servicios')->findOrFail($numeroSdp);
        $servicios = $sdp->servicios;

        return response()->json($servicios);
    }

    public function create()
    {
        // Obtener operativos asociados a trabajadores en el departamento de producción
        $operativos = Operativo::with('trabajador')->orderBy('codigo')->get();

        // Obtener todos los servicios y SDPs
        $servicios = Servicio::all();
        $sdps = SDP::with('clientes', 'articulos')->where('estado', 'abierto')->get();
        
        // Obtener el ID de la SDP desde la sesión
        $operarioId = session('operario_id');


        return view('tiemposproduccion.create', compact(
            'operativos',
            'servicios',
            'sdps',
            'operarioId'
        ));
    }


    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'dia' => 'required|integer|min:1|max:31',
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:1900|max:' . date('Y'),
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'operativo_id' => 'required|exists:operativos,codigo',
            'proseso_id' => 'required|exists:servicios,codigo',
            'sdp_id' => 'required|exists:sdps,numero_sdp',
            'nombre_operario' => 'required|string|max:191',
            'nombre_servicio' => 'required|string|max:191',
            'articulo_id' => 'required|exists:articulos,id',
        ]);

        DB::beginTransaction();

        try {
            Log::info('Iniciando creación de tiempo de producción', $request->all());

            // Crear el registro de Tiempos_produccion
            $tiempoProduccion = TiemposProduccion::create([
                'dia' => $request->dia,
                'mes' => $request->mes,
                'año' => $request->año,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'operativo_id' => $request->operativo_id,
                'proseso_id' => $request->proseso_id,
                'sdp_id' => $request->sdp_id,
                'nombre_operario' => $request->nombre_operario,
                'nombre_servicio' => $request->nombre_servicio,
                'valor_total_horas' => 0,
                'horas' => 0
            ]);

            Log::info('Tiempo de producción creado exitosamente', ['tiempos_produccion_id' => $tiempoProduccion->id]);

            // Calcular el tiempo valor total para el registro creado
            $valor_servicio = $request->input('valorServicio');
            $total_horas = $tiempoProduccion->Calcularvalor_total_horas($valor_servicio);
            $horasTotales = $tiempoProduccion->Calculartotalhoras();

            Log::info('Cálculos realizados', ['total_horas' => $total_horas, 'horas' => $horasTotales]);

            if ($total_horas === null && $horasTotales === null) {
                Log::warning('Error al calcular valor total de horas o total de horas');
                return redirect()->back()->withErrors('Error al calcular el tiempo valor total.');
            }

            $costosProduccion = CostosSdpProduccion::create(
                [
                'sdp_id' => $tiempoProduccion->sdp_id,
                'operario' => $tiempoProduccion->nombre_operario,
                'articulo' => $request->articulo_id,
                'servicio' => $tiempoProduccion->nombre_servicio,
                'tiempos_id' => $tiempoProduccion->id,
                'cif_id' => 1,
                'valor_sdp' => 0,
                'horas' => 0,
                'mano_obra_directa' => 0,
                'materias_primas_indirectas' => 0,
                'materias_primas_directas' => 0,
                'costos_indirectos_fabrica' => 0,
                'utilidad_bruta' => 0,
                'margen_bruto' => 0
            ]);

            $costosProduccion->calcularCostos($total_horas, $horasTotales);

        DB::commit();

            // Redireccionar con éxito
            Log::info('Tiempo de producción creado exitosamente, redirigiendo');
            return redirect()->route('tiempos.group')->with([
                'success' => 'Tiempo de producción creado exitosamente.',
                'valor_total_horas' => 'Valor total de horas: ' . $total_horas,
                'total_horas' => 'Total de horas: ' . $horasTotales,
            ]);

        } catch (\Exception $e) {
            // Manejo de excepciones y registro de errores
            Log::error('Error al crear tiempo de producción: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al crear tiempo de producción. Por favor, intente de nuevo.');
        }
    }

    public function edit(string $id)
    {
        $tiempo_produccion = TiemposProduccion::with('sdp.articulos', 'costosProduccion')->findOrFail($id);
        $servicios = Servicio::all();
        $sdps = SDP::with('clientes', 'articulos')->get();
        $operarioId = session('operativo_id');

        $valorServicio = $tiempo_produccion->servicio ? $tiempo_produccion->servicio->valor_hora : null;

        $costosProduccion = $tiempo_produccion->costosProduccion;


        $articuloSeleccionado = session('articulo_id');

        // Si existe un artículo seleccionado en la sesión, obtener el artículo completo
        $articulo = $tiempo_produccion->sdp->articulos ?? collect();

        $articuloSelect = $costosProduccion->articulo;

    // Si hay costos de producción y artículo relacionado

        return view('tiemposproduccion.edit', compact('tiempo_produccion', 'articuloSeleccionado', 'articuloSelect', 'articulo', 'costosProduccion', 'servicios', 'sdps', 'operarioId', 'valorServicio'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos de entrada
        $request->validate([
            'dia' => 'required|integer|min:1|max:31',
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:1900|max:' . date('Y'),
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'operativo_id' => 'required|exists:operativos,codigo',
            'proseso_id' => 'required|exists:servicios,codigo',
            'sdp_id' => 'required|exists:sdps,numero_sdp',
            'nombre_operario' => 'required|string|max:255',
            'nombre_servicio' => 'required|string|max:255',
            'articulo_id' => 'required'
        ]);
    
        DB::beginTransaction();
    
        try {
            // Buscar el registro existente por ID
            $tiempoProduccion = TiemposProduccion::findOrFail($id);
    
            Log::info('Iniciando actualización de tiempo de producción', $request->all());
    
            // Actualizar los campos de tiempo de producción
            $tiempoProduccion->update([
                'dia' => $request->dia,
                'mes' => $request->mes,
                'año' => $request->año,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'operativo_id' => $request->operativo_id,
                'proseso_id' => $request->proseso_id,
                'sdp_id' => $request->sdp_id,
                'nombre_operario' => $request->nombre_operario,
                'nombre_servicio' => $request->nombre_servicio,
            ]);
    
            Log::info('Tiempo de producción actualizado exitosamente', ['tiempos_produccion_id' => $tiempoProduccion->id]);
    
            // Calcular el valor total de horas y las horas
            $valor_servicio = $request->input('valorServicio');
            $total_horas = $tiempoProduccion->Calcularvalor_total_horas($valor_servicio);
            $horasTotales = $tiempoProduccion->Calculartotalhoras();
    
            if ($total_horas === null || $horasTotales === null) {
                throw new \Exception('Error al calcular valor total de horas o total de horas');
            }

            $costosProduccion = CostosSdpProduccion::updateOrCreate(
                [ 'tiempos_id' => $tiempoProduccion->id,],
                [
                'sdp_id' => $tiempoProduccion->sdp_id,
                'operario' => $tiempoProduccion->nombre_operario,
                'articulo' => $request->articulo_id,
                'servicio' => $tiempoProduccion->nombre_servicio,
                'cif_id' => 1,
                'valor_sdp' => 0,
                'horas' => 0,
                'mano_obra_directa' => 0,
                'materias_primas_indirectas' => 0,
                'materias_primas_directas' => 0,
                'costos_indirectos_fabrica' => 0,
                'utilidad_bruta' => 0,
                'margen_bruto' => 0
                ]
            );

            $costosProduccion->calcularCostos($total_horas, $horasTotales);
    
            DB::commit();
    
            // Redireccionar con un mensaje de éxito
            return redirect()->route('tiempos-produccion.operario', $tiempoProduccion->operativo_id)->with([
                'success' => 'Tiempo de producción actualizado exitosamente.',
                'valor_total_horas' => 'Valor total de horas: ' . $total_horas,
                'total_horas' => 'Total de horas: ' . $horasTotales,
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar tiempo de producción: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al actualizar tiempo de producción. Por favor, intente de nuevo.');
        }
    }

    public function destroy(string $id)
    {
        $tiempo_produccion = TiemposProduccion::findOrFail($id);
        $tiempo_produccion->delete();

        return redirect()->route('tiempos.group')->with('success', 'Tiempo de producción actualizado exitosamente');
    }
}