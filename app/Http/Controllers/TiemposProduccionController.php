<?php

namespace App\Http\Controllers;

use App\Enums\Departamento;
use App\Models\Articulo;
use App\Models\ArticuloTiempoProduccion;
use App\Models\CostosProduccion;
use App\Models\Operativo;
use App\Models\Operativos;
use App\Models\SDP;
use App\Models\Servicio;
use App\Models\ServicioCostos;
use App\Models\Tiempos_produccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

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
        $tiempos_produccion = Tiempos_produccion::where('operativo_id', $codigoOperario)
            ->with('operativo', 'servicio', 'sdp', 'cif')
            ->get();

        return view('tiemposproduccion.lista', compact('tiempos_produccion'));
    }

    public function print($id)
    {
        $tiempos_produccion = Tiempos_produccion::where('operativo_id', $id)
            ->with('operativo', 'servicio', 'sdp', 'cif')
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
        $tiempos_produccion = Tiempos_produccion::with('operativo', 'servicio', 'sdp')
            ->get()->groupBy('operativo_id');

        $tiempos = Tiempos_produccion::with('operativo', 'servicio', 'sdp')->get();
    
        return view('tiemposproduccion.index', compact('tiempos_produccion', 'tiempos'));
    }

    public function getArticulos($sdpId)
    {
        // Asegúrate de que el $sdpId sea el id de la tabla sdps, no el numero_sdp
        $sdp = Sdp::with('articulos')->findOrFail($sdpId);

        // Obtener los artículos relacionados
        $articulos = $sdp->articulos;

        // Verificar si los artículos se están recuperando correctamente
        foreach ($articulos as $articulo) {
            Log::info('Artículo relacionado:', [
                'articulo_id' => $articulo->id,
                'cantidad' => $articulo->pivot->cantidad,
                'precio' => $articulo->pivot->precio
            ]);
        }

        $articulosSeleccionadosIds = ArticuloTiempoProduccion::pluck('articulo_id')->toArray();

        $articulos = $sdp->articulos->map(function ($articulo) use ($articulosSeleccionadosIds) {
            $articulo->ya_seleccionado = in_array($articulo->id, $articulosSeleccionadosIds);
            return $articulo;
        });

        // Devolver los artículos como respuesta JSON
        return response()->json($articulos);
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

        // Verificar que el SDP está seleccionado
        if ($operarioId) {
            // Obtener la SDP seleccionada con sus artículos

            $tiemposProduccion = Tiempos_produccion::with('articulos')
            ->where('operativo_id', $operarioId)
            ->get();

            $articulosSeleccionados = collect();
            foreach ($tiemposProduccion as $tiempo) {
                $articulosSeleccionados = $articulosSeleccionados->merge($tiempo->articulos);
            }

            Log::info('Artículos seleccionados para el operario:', ['operario_id' => $operarioId, 'articulos' => $articulosSeleccionados]);

            // Depurar artículos con pivot
            foreach ($articulosSeleccionados as $articulo) {
                Log::info('Artículo relacionado con tiempo de producción:', [
                    'articulo_id' => $articulo->id,
                    'cantidad' => $articulo->pivot->sdp_id, // Suponiendo que tienes campos en la tabla pivote
                    'created_at' => $articulo->pivot->created_at
                ]);
            }
        } else {
            // Si no hay SDP seleccionada, inicializar un array vacío
            $articulosSeleccionados = collect();
        }

        $articulosSeleccionadosIds = ArticuloTiempoProduccion::pluck('articulo_id')->toArray();

        return view('tiemposproduccion.create', compact(
            'operativos',
            'servicios',
            'sdps',
            'articulosSeleccionados',
            'articulosSeleccionadosIds',
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
            'nombre_operario' => 'required|string|max:255',
            'nombre_servicio' => 'required|string|max:255',
            'articulos.articulo_id.*' => 'required|exists:articulos,id',
        ]);

        DB::beginTransaction();

        try {


            Log::info('Iniciando creación de tiempo de producción', $request->all());

            // Crear el registro de Tiempos_produccion
            $tiempoProduccion = Tiempos_produccion::create([
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
                'cif_id' => 1,
                'valor_total_horas' => 0,
                'horas' => 0
            ]);

            Log::info('Tiempo de producción creado exitosamente', ['tiempos_produccion_id' => $tiempoProduccion->id]);

            // Calcular el tiempo valor total para el registro creado
            $total_horas = $tiempoProduccion->Calcularvalor_total_horas();
            $horas = $tiempoProduccion->Calculartotalhoras();

            Log::info('Cálculos realizados', ['total_horas' => $total_horas, 'horas' => $horas]);

            if ($total_horas === null && $horas === null) {
                Log::warning('Error al calcular valor total de horas o total de horas');
                return redirect()->back()->withErrors('Error al calcular el tiempo valor total.');
            }

            $costoProduccion = new CostosProduccion();
            $costoProduccion->tiempo_produccion_id = $tiempoProduccion->id;
            $costoProduccion->mano_obra_directa = 0;
            $costoProduccion->sdp_id = $tiempoProduccion->sdp_id;
            $costoProduccion->cif_id = 1;
            $costoProduccion->save();

            Log::info('Costo de producción guardado', ['costo_produccion_id' => $costoProduccion->id]);

            // Procesar los artículos, si existen
            if ($request->has('articulos')) {
                Log::info('Procesando artículos asociados');
                
                $articulosData = [];
                foreach ($request->articulos['articulo_id'] as $articuloId) {
                    $articulosData[] = [
                        'tiempos_produccion_id' => $tiempoProduccion->id,
                        'articulo_id' => $articuloId,
                        'sdp_id'=> $tiempoProduccion->sdp_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                if (!empty($articulosData)) {
                    $tiempoProduccion->articulos()->attach($articulosData);
                    Log::info('Artículos asociados correctamente', ['articulos' => $articulosData]);
            
                     // Calcular la mano de obra directa solo para los artículos asociados
                $costoProduccion->calcularManoObraDirecta();
            }
        }

        DB::commit();

            // Redireccionar con éxito
            Log::info('Tiempo de producción creado exitosamente, redirigiendo');
            return redirect()->route('tiempos.group')->with([
                'success' => 'Tiempo de producción creado exitosamente.',
                'valor_total_horas' => 'Valor total de horas: ' . $total_horas,
                'total_horas' => 'Total de horas: ' . $horas,
            ]);

        } catch (\Exception $e) {
            // Manejo de excepciones y registro de errores
            Log::error('Error al crear tiempo de producción: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al crear tiempo de producción. Por favor, intente de nuevo.');
        }
    }

    public function edit(string $id)
    {
        $tiempo_produccion = Tiempos_produccion::with('articulos', 'sdp')->findOrFail($id);
        $servicios = Servicio::all();
        $sdps = SDP::with('clientes', 'articulos')->get();
        $operarioId = session('operativo_id');

        return view('tiemposproduccion.edit', compact('tiempo_produccion', 'servicios', 'sdps'));
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
            'articulos.articulo_id.*' => 'required|exists:articulos,id',
        ]);
    
        DB::beginTransaction();
    
        try {
            // Buscar el registro existente por ID
            $tiempoProduccion = Tiempos_produccion::findOrFail($id);
    
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
            $total_horas = $tiempoProduccion->Calcularvalor_total_horas();
            $horas = $tiempoProduccion->Calculartotalhoras();
    
            if ($total_horas === null || $horas === null) {
                throw new \Exception('Error al calcular valor total de horas o total de horas');
            }
    
            // Buscar o crear el costo de producción relacionado
            $costoProduccion = CostosProduccion::where('tiempo_produccion_id', $tiempoProduccion->id)->first();
            if (!$costoProduccion) {
                $costoProduccion = new CostosProduccion();
                $costoProduccion->tiempo_produccion_id = $tiempoProduccion->id;
                $costoProduccion->sdp_id = $tiempoProduccion->sdp_id;
                $costoProduccion->cif_id = 1;
            }
            $costoProduccion->mano_obra_directa = 0;  // Aquí puedes calcular el valor si es necesario
            $costoProduccion->save();
    
            Log::info('Costo de producción actualizado', ['costo_produccion_id' => $costoProduccion->id]);
            
    
            // Procesar los artículos asociados, si existen
            if ($request->has('articulos')) {
                Log::info('Procesando artículos asociados');
    
                $articulosData = [];
                foreach ($request->articulos['articulo_id'] as $articuloId) {
                    $articulosData[] = [
                        'tiempos_produccion_id' => $tiempoProduccion->id,
                        'articulo_id' => $articuloId,
                        'sdp_id'=> $tiempoProduccion->sdp_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
    
                // Sincronizar los artículos actualizados
                if (!empty($articulosData)) {
                    $tiempoProduccion->articulos()->sync($articulosData);
                    Log::info('Artículos actualizados correctamente', ['articulos' => $articulosData]);
    
                    // Calcular mano de obra directa de los artículos
                    $manoObraDirecta = $costoProduccion->calcularManoObraDirecta($articulosData);
                    if ($manoObraDirecta === null) {
                        throw new \Exception('Error al calcular mano de obra directa');
                    }
    
                    $costoProduccion->mano_obra_directa = $manoObraDirecta;
                    $costoProduccion->save();
                }
            }
    
            DB::commit();
    
            // Redireccionar con un mensaje de éxito
            return redirect()->route('tiempos-produccion.operario', $tiempoProduccion->operativo_id)->with([
                'success' => 'Tiempo de producción actualizado exitosamente.',
                'valor_total_horas' => 'Valor total de horas: ' . $total_horas,
                'total_horas' => 'Total de horas: ' . $horas,
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar tiempo de producción: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al actualizar tiempo de producción. Por favor, intente de nuevo.');
        }
    }

    public function destroy(string $id)
    {
        $tiempo_produccion = Tiempos_produccion::findOrFail($id);
        $tiempo_produccion->delete();

        return redirect()->route('tiempos.group')->with('success', 'Tiempo de producción actualizado exitosamente');
    }
}