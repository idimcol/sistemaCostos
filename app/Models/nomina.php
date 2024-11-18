<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class nomina extends Model
{
    use HasFactory;

    protected $fillable = [
        'trabajador_id', 
        'año', 
        'mes', 
        'periodo_pago', 
        'devengado_trabajados',
        'devengado_incapacidad', 
        'devengado_vacaciones', 
        'devengado_remunerados',
        'pension', 
        'salud', 
        'bono',
        'otro',
        'total_devengado', 
        'total_deducido', 
        'total_a_pagar',
        'suspencion', 
        'bonificacion_auxilio', 
        'celular', 
        'anticipo',
        'auxilio_transporte',
        'desde',
        'a',
        'paquete_nomina_id',
    ];
    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
    
    public function dias()
    {
        return $this->hasOne(Dia::class, 'nomina_id');
    }


    public function calcularNomina()
    {
        
    
        $trabajador = $this->trabajador;
        $dias = $this->dias;

        Log::info('Iniciando cálculo de nómina', [
            'nomina_id' => $this->id,
            'trabajador_id' => $this->trabajador_id,
            'sueldo_base' => $this->trabajador->sueldos()->orderBy('created_at', 'desc')->first()->sueldo ?? 'No encontrado'
        ]);
    
        if ($dias) {
            $sueldo_base = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->sueldo ?? 0;
            $auxilio_transporte_base = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->auxilio_transporte ?? 0;
            $bonificacion_auxilio = (float) $this->bonificacion_auxilio;
            $bonificacion_auxilio_sueldo = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->bonificacion_auxilio ?? 0;

            $dias_trabajados = $dias->orderBy('created_at', 'desc')->first()->dias_trabajados ?? 0;
            $dias_no_renumerados = $dias->orderBy('created_at', 'desc')->first()->dias_no_remunerados ?? 0;
    
            // Convertir a decimal para cálculos precisos

            $sueldo_base_decimal = number_format($sueldo_base, 2, '.', '');
            $bonificacion_auxilio_decimal = number_format($bonificacion_auxilio_sueldo, 2, '.', '');

            $porcentaleSalud = Configuracion::obtenerValor('salud') / 100;
            $porcentalePension = Configuracion::obtenerValor('pension') / 100;

            $esAprendizSENA = $trabajador->cargo == 'APRENDIZ SENA';

            // Cálculo de pension y salud
            $pension = $esAprendizSENA ? 0 : bcmul($sueldo_base, $porcentaleSalud, 2);
            $salud = $esAprendizSENA ? 0 : bcmul($sueldo_base, $porcentalePension, 2);
    
            // Cálculo de devengados
            $devengado_trabajados = bcmul(bcdiv($sueldo_base_decimal, '30', 2), $dias->dias_trabajados, 2);
            $devengado_incapacidad = bcmul(bcdiv($sueldo_base_decimal, '30', 2), $dias->dias_incapacidad, 2);
            $devengado_vacaciones = bcmul(bcdiv($sueldo_base_decimal, '30', 2), $dias->dias_vacaciones, 2);
            $devengado_remunerados = bcmul(bcdiv($sueldo_base_decimal, '30', 2), $dias->dias_remunerados, 2);
            // Cálculo de suspensión
            $suspencion = bcmul(bcdiv($sueldo_base_decimal, '30', 2), $dias->dias_no_remunerados, 2);

            // Cálculo de auxilio de transporte
            $auxilio_transporte = ($auxilio_transporte_base/30)*($dias->dias_trabajados-$dias->dias_no_remunerados);
    
            // Cálculo de totales
            $total_devengado = bcadd($bonificacion_auxilio_decimal, bcadd($devengado_trabajados, bcadd($devengado_incapacidad, bcadd($devengado_vacaciones, bcadd($devengado_remunerados, $auxilio_transporte, 2), 2), 2), 2), 2);
            $total_deducido = bcadd($pension, bcadd($salud, bcadd($this->celular, bcadd($this->anticipo, $suspencion, 2), 2) + $this->otro, 2), 2);
            $total_a_pagar = bcsub($total_devengado, $total_deducido, 2);
    
            // Actualizar atributos de la nómina
            $this->update([
                'pension' => $pension,
                'salud' => $salud,
                'devengado_trabajados' => $devengado_trabajados,
                'devengado_incapacidad' => $devengado_incapacidad,
                'devengado_vacaciones' => $devengado_vacaciones,
                'devengado_remunerados' => $devengado_remunerados,
                'auxilio_transporte' => $auxilio_transporte,
                'bonificacion_auxilio' => $bonificacion_auxilio_sueldo,
                'suspencion' => $suspencion,
                'total_devengado' => $total_devengado,
                'total_deducido' => $total_deducido,
                'total_a_pagar' => $total_a_pagar,
            ]);
    
            Log::info('Finalizado cálculo de nómina', [
                'nomina_id' => $this->id,
                'devengado_trabajados' => $devengado_trabajados,
                'total_devengado' => $total_devengado,
                'pension' => $pension,
                'salud' => $salud,
            ]);
        }
    }

    public function calcularDiasTrabajados($dias)
    {
        return 30 - $dias->dias_incapacidad - $dias->dias_vacaciones - $dias->dias_remunerados;
    }


    public function paqueteNomina()
    {
        return $this->belongsTo(PaqueteNomina::class, 'paquete_nomina_id');
    }
}
