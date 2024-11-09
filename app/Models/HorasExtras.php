<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorasExtras extends Model
{
    use HasFactory;

    protected $table = 'horas__extras';

    protected $fillable = [
        'operario_cod',
        'valor_bono',
        'horas_diurnas',
        'horas_nocturnas',
        'horas_festivos',
        'horas_recargo_nocturno',
    ];

    
    public function operarios()
    {
        return $this->belongsTo(Operativo::class, 'operario_cod', 'codigo');
    }

    public function calcular_horas()
    {
        $trabajador = $this->operarios->trabajador;
        $tiempo_produccion = $this->operarios->Tiempos_Produccion()->first();

        if ($tiempo_produccion){

            $cif = $tiempo_produccion->Cif;
            
            $sueldo_base = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->sueldo ?? 0;
            $horasMes = $cif->NMH ?? 0;
            
            $horas_diurnas = ($sueldo_base/$horasMes)*1.25;
            $horas_nocturnas = ($sueldo_base/$horasMes)*1.75;
            $horas_festivos = ($sueldo_base/$horasMes)*1.75;
            $horas_recargo_nocturno = ($sueldo_base/$horasMes)*0.3;

            $this->update([
                'horas_diurnas' => $horas_diurnas,
                'horas_nocturnas' => $horas_nocturnas,
                'horas_festivos' => $horas_festivos,
                'horas_recargo_nocturno' => $horas_recargo_nocturno
            ]);
        }
    }
}
