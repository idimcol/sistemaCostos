<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operativo extends Model
{
    use HasFactory;

    protected $table = 'operativos';

    protected $fillable = [
        'codigo',
        'trabajador_id',
        'operario',
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }

    public function Tiempos_Produccion()
    {
        return $this->hasMany(TiemposProduccion::class, 'operativo_id');
    }

    public function horasExtras()
    {
        return $this->hasMany(HorasExtras::class, 'operario_cod', 'codigo');
    }
}
