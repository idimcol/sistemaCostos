<?php

namespace App\Http\Controllers;

use App\Models\BonificacionAuxilio;
use App\Models\Dias;
use App\Models\Nominas;
use App\Models\PaqueteNomina;
use App\Models\Sueldo;
use Illuminate\Http\Request;
use App\Models\Trabajador;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\DB;

class NominaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:paquetes nomina')->only('index');
        $this->middleware('can:crear paquetes nominas')->only('crearPaquete');
        $this->middleware('can:editar paquetes nomnias')->only('editNomina');
        $this->middleware('can:eliminar paquetes nomina')->only('destroy');
        $this->middleware('can:ver nominas')->only('show');
        $this->middleware('can:editar nominas')->only('updateBulk');
        $this->middleware('can:ver desprendible')->only('mostrarDesprendible');
        $this->middleware('can:agregar trabajador nomina')->only('addWorker');
    }
    public function index()
    {
        $paquetes = PaqueteNomina::orderBy('año', 'desc')->orderBy('mes', 'desc')->get();
        return view('nomina.index', compact('paquetes'));
    }

    public function addWorker(Request $request, $paqueteId)
    {
        $paquete = PaqueteNomina::findOrFail($paqueteId);
        $trabajadorId = $request->input('trabajador_id');
        $trabajador = Trabajador::findOrFail($trabajadorId);

        // Verificar si el trabajador ya está en el paquete
        if ($paquete->nominas()->where('trabajador_id', $trabajadorId)->exists()) {
            return redirect()->back()->with('error', 'El trabajador ya está en este paquete de nóminas.');
        }

        if ($trabajador->estado == 'inactivo') {
            Log::info('Trabajador inactivo, nómina no creada', ['trabajador_id' => $trabajador->id]);
            return redirect()->back()->with('error', 'El trabajador está inactivo y no se puede agregar a la nómina.');
        }

        $esAprendizSENA = $trabajador->cargo == 'APRENDIZ SENA';

        // Crear la nómina para el trabajador en el paquete actual
        $nomina = $paquete->nominas()->create([
            'trabajador_id' => $trabajadorId,
            'mes' => $request->mes,
            'año' => $request->año,
            'periodo_pago' => $request->mes . '/' . $request->año,
            'paquete_nomina_id' => $paquete->id,
            'devengado_trabajados' => 0,
            'devengado_incapacidad' => 0,
            'devengado_vacaciones' => 0,
            'devengado_remunerados' => 0,
            'pension' => $esAprendizSENA ? 0 : 0.04, // Si es Aprendiz SENA, la pensión es 0
            'salud' => $esAprendizSENA ? 0 : 0.04, 
            'total_deducido' => 0,
            'total_devengado' => 0,
            'total_a_pagar' => 0,
            'suspencion' => 0,
            'bonificacion_auxilio' => 0,
            'celular' => 0,
            'anticipo' => 0,
            'otro' => 0,
            'auxilio_transporte' => 0,
        ]);

        $dias = new Dias([
            'nomina_id' => $nomina->id,
            'trabajador_id' => $trabajador->id,
            'dias_trabajados' => 30,
            'dias_incapacidad' => 0,
            'dias_vacaciones' => 0,
            'dias_remunerados' => 0,
            'dias_no_remunerados' => 0,
        ]);
        $dias->save();

        $nomina->calcularNomina();

        return redirect()->back()->with('success', 'Trabajador agregado al paquete de nómina exitosamente.');
    }

    public function editNomina($id)
    {
        $paqueteNomina = PaqueteNomina::findOrFail($id);
        return view('nomina.edit', compact('paqueteNomina'));
    }

    public function updateNomina(Request $request, $id)
    {
        $paqueteNomina = PaqueteNomina::findOrFail($id);

        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer',
        ]);

        $paqueteNomina->update([
            'mes' => $request->input('mes'),
            'año' => $request->input('año'),
        ]);

        return redirect()->route('nomina.index')->with('Pagete de nomina actualizado');
    }

    public function crearPaquete(Request $request)
    {
        Log::info('Iniciando creación de paquete', $request->all());

    try {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer',
        ]);

        Log::info('Validación pasada');

        DB::beginTransaction();

        Log::info('Verificando paquete existente');
        $paqueteExistente = PaqueteNomina::where('mes', $request->mes)
            ->where('año', $request->año)
            ->first();

        if ($paqueteExistente) {
            Log::info('Paquete ya existe', ['id' => $paqueteExistente->id]);
            return back()->with('error', 'Ya existe un paquete de nóminas para este mes y año.');
        }

        Log::info('Intentando crear paquete');
        $paquete = PaqueteNomina::create([
            'mes' => $request->mes,
            'año' => $request->año,
        ]);

        if (!$paquete) {
            throw new \Exception('No se pudo crear el paquete de nóminas.');
        }

        Log::info('Paquete creado', ['id' => $paquete->id]);

        $trabajadores = Trabajador::all();
        Log::info('Creando nóminas para trabajadores', ['count' => $trabajadores->count()]);

        foreach ($trabajadores as $trabajador) {
            // Verificar si el trabajador está inactivo
            if ($trabajador->estado == 'inactivo') {
                Log::info('Trabajador inactivo, nómina no creada', ['trabajador_id' => $trabajador->id]);
                continue;
            }

            // Obtener el sueldo más reciente
            $sueldoReciente = $trabajador->sueldos()->latest()->first();

            Log::info('Datos del trabajador antes de crear nómina', [
                'id' => $trabajador->id,
                'nombre' => $trabajador->nombre,
                'sueldo_base' => $sueldoReciente->sueldo ?? 'No encontrado'
            ]);

            $esAprendizSENA = $trabajador->cargo == 'APRENDIZ SENA';

            $nomina = new Nominas([
                'trabajador_id' => $trabajador->id,
                'mes' => $request->mes,
                'año' => $request->año,
                'periodo_pago' => $request->mes . '/' . $request->año,
                'paquete_nomina_id' => $paquete->id,
                'devengado_trabajados' => 0,
                'devengado_incapacidad' => 0,
                'devengado_vacaciones' => 0,
                'devengado_remunerados' => 0,
                'pension' => $esAprendizSENA ? 0 : 0.04, // Si es Aprendiz SENA, la pensión es 0
                'salud' => $esAprendizSENA ? 0 : 0.04, 
                'total_deducido' => 0,
                'total_devengado' => 0,
                'total_a_pagar' => 0,
                'suspencion' => 0,
                'bonificacion_auxilio' => 0,
                'celular' => 0,
                'anticipo' => 0,
                'otro' => 0,
                'auxilio_transporte' => 0,
            ]);

            $nomina->save();

            // Crear el registro de días asociado
            $dias = new Dias([
                'nomina_id' => $nomina->id,
                'trabajador_id' => $trabajador->id,
                'dias_trabajados' => 30,
                'dias_incapacidad' => 0,
                'dias_vacaciones' => 0,
                'dias_remunerados' => 0,
                'dias_no_remunerados' => 0,
            ]);
            $dias->save();

            Log::info('Nómina creada, antes de calcular', [
                'nomina_id' => $nomina->id,
                'trabajador_id' => $nomina->trabajador_id,
                'dias_trabajados' => $dias->dias_trabajados
            ]);

            $nomina->calcularNomina();

            Log::info('Nómina calculada', [
                'nomina_id' => $nomina->id,
                'devengado_trabajados' => $nomina->devengado_trabajados,
                'total_devengado' => $nomina->total_devengado,
                'auxilio_rodamiento' => $request->bonificacion_auxilio
            ]);
            Log::info('Nómina creada para trabajador', ['trabajador_id' => $trabajador->id, 'nomina_id' => $nomina->id]);
        }

        DB::commit();
        Log::info('Transacción completada');

        return redirect()->route('nomina.index')->with('success', 'Paquete de nóminas creado exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear paquete de nóminas', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Error al crear el paquete de nóminas: ' . $e->getMessage());
    }
}

    public function obtenerNominasEspecificas(PaqueteNomina $paquete)
    {
        $trabajadoresIds = [11, 13, 12, 15, 8, 6, 5, 17];

        $nominas = $paquete->nominas()
            ->whereIn('trabajador_id', $trabajadoresIds)
            ->get();

        $total_a_pagar_pcc = $nominas->sum('total_a_pagar');

        return view('nomina.show', compact('paquete', 'nominas', 'total_a_pagar_pcc'));
    }

    public function obtenerNominas(PaqueteNomina $paquete)
    {
        $trabajadoresId = [4, 18, 10, 9, 1];

        $nominas = $paquete->nominas()
            ->whereIn('trabajador_id', $trabajadoresId)
            ->get();

        $total_a_pagar_Admon = $nominas->sum('total_a_pagar');

        return view('nomina.show', compact('paquete', 'nominas', 'total_a_pagar_Admon'));
    }

    public function obtener(PaqueteNomina $paquete)
    {
        $trabajadoresIdz = [3, 19, 16, 7];

        $nominas = $paquete->nominas()
            ->whereIn('trabajador_id', $trabajadoresIdz)
            ->get();

        $total_a_pagar_socios = $nominas->sum('total_a_pagar');

        return view('nomina.show', compact('paquete', 'nominas', 'total_a_pagar_Admon'));
    }


    public function show(PaqueteNomina $paquete)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        $trabajadoresSinNomina = Trabajador::doesntHave('nominas')->where('estado', 'activo')->get();
    
        $trabajadorIds = Trabajador::where('estado', 'activo')->pluck('id');
    
        // $nominas = $paquete->nominas()
        //     ->whereIn('trabajador_id', $trabajadorIds)
        //     ->with(['trabajador.sueldos', 'dias'])
        //     ->get();

        $nominas = $paquete->nominas()
        ->whereIn('trabajador_id', $trabajadorIds)
        ->with(['trabajador.sueldos', 'dias'])
        ->join('trabajadors', 'nominas.trabajador_id', '=', 'trabajadors.id')
        ->orderBy('trabajadors.apellido')
        ->select('nominas.*')
        ->get();
        
        $totalSueldo = $nominas->sum(function ($nomina) {
            // Asegúrate de que el sueldo más reciente se obtiene correctamente
            $latestSueldo = $nomina->trabajador->sueldos()->latest()->first();
            return $latestSueldo ? $latestSueldo->sueldo : 0;
        });
    
        $total_dias_trabajados = 0;
        $total_dias_incapacidad = 0;
        $total_dias_vacaciones = 0;
        $total_dias_remunerados = 0;
        $total_bonificacion = 0;
        $total_dias_no_remunerados = 0;
        foreach ($nominas as $nomina){
            $diasTrabajados = $nomina->dias->dias_trabajados;
            $diasIncapacidad = $nomina->dias->dias_incapacidad;
            $diasVacaciones = $nomina->dias->dias_vacaciones;
            $diasRemunerados = $nomina->dias->dias_remunerados;
            $diasNoRemunerados = $nomina->dias->dias_no_remunerados;

            $total_dias_trabajados += $diasTrabajados;
            $total_dias_incapacidad += $diasIncapacidad;
            $total_dias_vacaciones += $diasVacaciones;
            $total_dias_remunerados += $diasRemunerados;
            $total_dias_no_remunerados += $diasNoRemunerados; 
        }
    
        $total_D_dias_trabajados = $nominas->sum('devengado_trabajados');
        $total_D_dias_incapacidad = $nominas->sum('devengado_incapacidad');
        $total_D_dias_vacaciones = $nominas->sum('devengado_vacaciones');
        $total_D_dias_remunerados = $nominas->sum('devengado_remunerados');
        $total_bonificacion = $nominas->sum('bonificacion_auxilio');
    
        $total_auxilio = $nominas->sum('auxilio_transporte');
        $total_otro = $nominas->sum('otro');
        $total_devengado = $nominas->sum('total_devengado');
        $total_pencion = $nominas->sum('pension');
        $total_salud = $nominas->sum('salud');
        $total_celular = $nominas->sum('celular');
        $total_anticipo = $nominas->sum('anticipo');
        $total_suspencion = $nominas->sum('suspencion');
        $total_deducido = $nominas->sum('total_deducido');
        $total_a_pagar = $nominas->sum('total_a_pagar');
    
        $total_dias = 0;
        foreach ($nominas as $nomina) {
            $nomina->total_dias = $nomina->dias->dias_trabajados 
                + $nomina->dias->dias_incapacidad 
                + $nomina->dias->dias_vacaciones 
                + $nomina->dias->dias_remunerados;
            $total_dias += $nomina->total_dias;
        }
    
        $trabajadoresIdsPCC = [11, 13, 12, 15, 8, 6, 5, 17];
        $total_a_pagar_pcc = $paquete->nominas()
            ->whereIn('trabajador_id', $trabajadoresIdsPCC)
            ->sum('total_a_pagar');
    
        $trabajadoresIdsAdmon = [4, 18, 10, 9, 1];
        $total_a_pagar_admon = $paquete->nominas()
            ->whereIn('trabajador_id', $trabajadoresIdsAdmon)
            ->sum('total_a_pagar');
    
        $trabajadoresIdsSocios = [3, 19, 16, 9];
        $total_a_pagar_socios = $paquete->nominas()
            ->whereIn('trabajador_id', $trabajadoresIdsSocios)
            ->sum('total_a_pagar');
    
        $subtotal = $total_a_pagar_pcc + $total_a_pagar_admon + $total_a_pagar_socios;
    
        return view('nomina.show', compact('paquete', 'nominas', 'meses', 'totalSueldo', 
                                            'total_dias_trabajados', 'total_dias_incapacidad', 
                                            'total_dias_vacaciones', 'total_dias_remunerados', 
                                            'total_dias_no_remunerados', 'total_D_dias_trabajados', 
                                            'total_D_dias_incapacidad', 'total_D_dias_vacaciones', 
                                            'total_D_dias_remunerados', 'total_bonificacion', 
                                            'total_auxilio', 'total_devengado', 'total_pencion', 
                                            'total_salud', 'total_celular', 'total_anticipo', 
                                            'total_suspencion', 'total_deducido', 'total_a_pagar', 
                                            'total_dias', 'total_a_pagar_pcc', 'total_a_pagar_admon', 
                                            'total_a_pagar_socios', 'subtotal', 'total_otro', 'trabajadoresSinNomina'));
    }

    public function updateBulk(Request $request)
    {
        $cambios = $request->input('cambios');
        $nominasActualizadas = [];

        DB::beginTransaction();

        try {
            foreach ($cambios as $nominaId => $campos) {
                $nomina = Nominas::findOrFail($nominaId);
                $diasCampos = ['dias_incapacidad', 'dias_vacaciones', 'dias_remunerados', 'dias_trabajados', 'dias_no_remunerados'];
                $numerosCampos = ['bonificacion_auxilio','celular', 'anticipo', 'otro'];
                $stringCampos = ['desde', 'a'];

                foreach ($campos as $campo => $valor) {
                    if (in_array($campo, $diasCampos)) {
                        $nomina->dias->$campo = is_numeric($valor) ? floatval($valor) : $valor;
                    } elseif (in_array($campo, $numerosCampos)) {
                        $nomina->$campo = is_numeric($valor) ? floatval($valor) : $valor;
                    } elseif (in_array($campo, $stringCampos)) {
                        $nomina->$campo = trim($valor); // Aseguramos que no haya espacios en blanco al inicio o final
                    }
                }

                if (isset($campos['bonificacion_auxilio'])) {
                    $bonificacionAuxilio = Sueldo::where('trabajador_id', $nomina->trabajador_id)->first();
                    if ($bonificacionAuxilio) {
                        $bonificacionAuxilio->bonificacion_auxilio = $campos['bonificacion_auxilio'];
                        $bonificacionAuxilio->save();
                    } else {
                        // Opcional: Crear una nueva entrada si no existe
                        Sueldo::create([
                            'trabajador_id' => $nomina->trabajador_id,
                            'bono_auxilio' => $campos['bonificacion_auxilio'],
                        ]);
                    }
                }

                $nomina->dias->save();
                $nomina->calcularNomina();
                $nomina->save();

                // Actualiza $nominasActualizadas con los nuevos valores
                $nominasActualizadas[$nominaId] = [
                    'dias_trabajados' => $nomina->dias->dias_trabajados,
                    'devengado_trabajados' => number_format($nomina->devengado_trabajados, 2, '.', ','),
                    'devengado_incapacidad' => number_format($nomina->devengado_incapacidad, 2, '.', ','),
                    'devengado_vacaciones' => number_format($nomina->devengado_vacaciones, 2, '.', ','),
                    'devengado_remunerados' => number_format($nomina->devengado_remunerados, 2, '.', ','),
                    'pension' => number_format($nomina->pension, 2, '.', ','),
                    'salud' => number_format($nomina->salud, 2, '.', ','),
                    'auxilio_transporte' => number_format($nomina->auxilio_transporte, 2, '.', ','),
                    'bono' => number_format($nomina->bono, 2, '.', ','),
                    'suspencion' => number_format($nomina->suspencion, 2, '.', ','),
                    'total_devengado' => number_format($nomina->total_devengado, 2, '.', ','),
                    'total_deducido' => number_format($nomina->total_deducido, 2, '.', ','),
                    'total_a_pagar' => number_format($nomina->total_a_pagar, 2, '.', ','),
                    'desde' => $nomina->desde,
                    'a' => $nomina->a,
                    'bonificacion_auxilio' => number_format($nomina->bonificacion_auxilio, 2, '.', ','),
                    'celular' => number_format($nomina->celular, 2, '.', ','),
                    'anticipo' => number_format($nomina->anticipo, 2, '.', ','),
                    'otro' => number_format($nomina->otro, 2, '.', ','),
                    'dias_no_remunerados' => $nomina->dias->dias_no_remunerados,
                ];
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'nominasActualizadas' => $nominasActualizadas
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error("Nómina no encontrada: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Nómina no encontrada'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar nóminas: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar nóminas: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        $paquete = PaqueteNomina::find($id);

        if (!$paquete) {
            return redirect()->route('nomina.index')->with('error', 'Paquete de nóminas no encontrado.');
        }

        $paquete->delete();

        return redirect()->route('nomina.index')->with('success', 'Paquete de nóminas eliminado correctamente.');
    }

    public function mostrarDesprendible($id)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        $nomina = Nominas::with(['trabajador.sueldos', 'dias', 'paqueteNomina'])->findOrFail($id);
        
        return view('nomina.nomina', compact('nomina', 'meses'));
    }

}
