<?php

namespace App\Http\Controllers;

use App\Eports\ExportNomina;
use App\Models\PaqueteNomina;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:exportar nominas')->only('export');
    }
    public function export(PaqueteNomina $paquete)
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
    
        return view('nomina.export', compact('paquete', 'nominas', 'meses', 'totalSueldo', 
                                            'total_dias_trabajados', 'total_dias_incapacidad', 
                                            'total_dias_vacaciones', 'total_dias_remunerados', 
                                            'total_dias_no_remunerados', 'total_D_dias_trabajados', 
                                            'total_D_dias_incapacidad', 'total_D_dias_vacaciones', 
                                            'total_D_dias_remunerados', 'total_bonificacion', 
                                            'total_auxilio', 'total_devengado', 'total_pencion', 
                                            'total_salud', 'total_celular', 'total_anticipo', 
                                            'total_suspencion', 'total_deducido', 'total_a_pagar', 
                                            'total_dias', 'total_a_pagar_pcc', 'total_a_pagar_admon', 
                                            'total_a_pagar_socios', 'subtotal', 'total_otro'));
    }
}