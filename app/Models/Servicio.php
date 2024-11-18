<?php

namespace App\Models;

use App\Traits\codigoAlf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    use codigoAlf;

    protected $fillable = [
        'codigo',
        'nombre',
        'valor_hora'
    ];


    public function Tiempos_Produccion()
    {
        return $this->hasMany(TiemposProduccion::class, 'proseso_id', 'codigo');
    }

    public function sdps()
    {
        return $this->belongsToMany(SDP::class, 'servicios_s_d_p_s', 'servicio_id', 'sdp_id','codigo', 'numero_sdp')
                    ->withPivot('valor_servicio')
                    ->withTimestamps();
    }

}
