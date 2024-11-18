<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TiemposProduccion extends Model
{
    use HasFactory;

    protected $table = 'tiempos_produccions';

    protected $fillable = [
        'dia',
        'mes',
        'año',
        'hora_inicio',
        'hora_fin',
        'operativo_id',
        'proseso_id',
        'sdp_id',
        'nombre_operario',
        'nombre_servicio',
        'cif_id',
        'valor_total_horas',
        'horas'
    ];

    public function operativo()
    {
        return $this->belongsTo(Operativo::class, 'operativo_id', 'codigo');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'proseso_id', 'codigo');
    }

    public function sdp()
    {
        return $this->belongsTo(SDP::class, 'sdp_id', 'numero_sdp');
    }

    public function costosProduccion()
    {
        return $this->hasMany(CostosSdpProduccion::class, 'tiempos_id');
    }

    public function trabajador()
    {
        return $this->hasOneThrough(Trabajador::class, Operativo::class);
    } 

    public function valorServicio($valor_servicio)
    {
        Log::info('Valor del servicio obtenido', ['valor_servicio' => $valor_servicio]);
        return $valor_servicio;
    }

    public function Calcularvalor_total_horas($valor_servicio)
    {
        try {
            // Validar que las horas de inicio y fin no sean nulas y tengan el formato correcto
            if (empty($this->hora_inicio) || empty($this->hora_fin)) {
                throw new Exception('Las horas de inicio o fin están vacías.');
            }

            // Crear instancias de Carbon usando el formato correcto
            try {
                $inicio = Carbon::createFromFormat('H:i:s', $this->hora_inicio);
                $fin = Carbon::createFromFormat('H:i:s', $this->hora_fin);
            } catch (\Exception $e) {
                throw new Exception('Formato de hora incorrecto: ' . $e->getMessage());
            }
            // Calcular las horas trabajadas
            $horas = $inicio->diffInHours($fin);

            // Calcular el total de horas
            $total_horas = $valor_servicio * $horas;

            Log::info('Cálculo realizado con éxito.', [
                'valor_servicio' => $valor_servicio,
                'horas' => $horas,
                'valor_total_horas' => $total_horas,
            ]);

            // Actualizar el campo 'valor_total_horas'
            $this->update([
                'valor_total_horas' => $total_horas,
            ]);

            return $total_horas;

        } catch (\Exception $e) {
            // Registrar el error
            Log::error('Error al calcular tiempo valor total horas: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return null;
        }
    }
    public function Calculartotalhoras()
    {
        try {
            // Validar que los campos no sean nulos
            if (is_null($this->hora_inicio) || is_null($this->hora_fin)) {
                throw new \Exception('Las horas de inicio o fin no pueden ser nulas.');
            }

            // Crear instancias de Carbon para hora_inicio y hora_fin
            $inicio = Carbon::createFromFormat('H:i:s', $this->hora_inicio);
            $fin = Carbon::createFromFormat('H:i:s', $this->hora_fin);

            // Calcular la diferencia en horas
            $horas = $inicio->diffInHours($fin);

            // Actualizar el modelo actual con el valor calculado

            $this->horas = $horas;
            $this->save();

            return $horas;
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al calcular total horas: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return null;
        }
    }
}
