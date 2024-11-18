<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CostosSdpProduccion extends Model
{
    use HasFactory;

    protected $table = 'costos_sdp_produccions';

    protected $fillable = [
        'sdp_id',
        'operario',
        'articulo',
        'servicio',
        'horas',
        'cif_id',
        'tiempos_id',
        'mano_obra_directa',
        'valor_sdp',
        'materias_primas_indirectas',
        'materias_primas_directas',
        'costos_indirectos_fabrica',
        'utilidad_bruta',
        'margen_bruto'
    ];

    public function tiemposProduccion()
    {
        return $this->belongsTo(TiemposProduccion::class, 'tiempos_id');
    }

    public function sdp()
    {
        return $this->belongsTo(SDP::class, 'sdp_id', 'numero_sdp');
    }

    public function cif()
    {
        return $this->belongsTo(Cif::class, 'cif_id');
    }

    public function materiasPrimasDirectas()
    {
        return $this->belongsToMany(MateriaPrimaDirecta::class, 'materia_prima_directa_costos', 'costos_produccion_id', 'materia_prima_directa_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function materiasPrimasIndirectas()
    {
        return $this->belongsToMany(MateriaPrimaIndirecta::class, 'materia_prima_indirecta_costos', 'costos_produccion_id', 'materia_indirecta_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function calcularCostos($total_horas, $horasTotales)
    {
        // Verificar que el registro de tiempos de producción esté disponible
        $tiemposProduccion = $this->tiemposProduccion;
        if (!$tiemposProduccion) {
            Log::warning('No se encontró el registro de tiempos de producción');
            return 0;
        }

        // Verificar que el operario esté disponible
        $operario = $tiemposProduccion->operativo;
        if (!$operario) {
            Log::warning('No se encontró el registro del operario asociado');
            return 0;
        }

        // Verificar que el trabajador esté disponible
        $trabajador = $operario->trabajador;
        if (!$trabajador) {
            Log::warning('No se encontró el registro del trabajador asociado');
            return 0;
        }

        // Obtener el sueldo más reciente del trabajador, o 0 si no existe
        $sueldo = $trabajador->sueldos()->orderBy('created_at', 'desc')->first()->sueldo ?? 0;

        $cif = $this->cif;

        $moi = $cif->MOI;
        $goi = $cif->GOI;
        $oci = $cif->OCI;

        // Calcular mano de obra directa sumando el sueldo y el valor del servicio

        $manoObraDirecta = $sueldo + $total_horas;
        $horasTotal = $horasTotales;

        $sumatoriaCif = $moi + $goi + $oci;

        $CifTotal = $sumatoriaCif * $horasTotal;

        // Actualizar el registro actual con el valor de mano de obra directa calculado
        $this->update([
            'mano_obra_directa' => $manoObraDirecta,
            'horas' => $horasTotal,
            'costos_indirectos_fabrica' => $CifTotal
        ]);
    }
}
