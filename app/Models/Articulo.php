<?php

namespace App\Models;

use App\Traits\codigoArt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;
    use codigoArt;

    protected $fillable = [
        'codigo',
        'descripcion',
        'material',
        'plano',
    ];

    protected static function boot()
    {
        parent::boot();
        self::bootCodigoArt();
    }

    public function sdps()
    {
        return $this->belongsToMany(SDP::class, 'articulo_sdp', 'sdp_id', 'articulo_id')
                    ->withPivot('cantidad', 'precio');
    }

    public function materiasPrimasDirectas()
    {
        return $this->belongsToMany(MateriaPrimaDirecta::class, 'materia_prima_directas_costos', 'articulo_id', 'materia_prima_directa_id')
                    ->withPivot('cantidad', 'articulo_descripcion', 'costos_produccion_id');
    }

    public function materiasPrimasIndirectas()
    {
        return $this->belongsToMany(MateriaPrimaIndirecta::class, 'materia_prima_indirectas_costos', 'articulo_id', 'materia_indirecta_id')
                    ->withPivot('cantidad', 'articulo_descripcion', 'costos_produccion_id');
    }
}
