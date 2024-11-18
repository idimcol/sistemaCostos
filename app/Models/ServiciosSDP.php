<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiciosSDP extends Model
{
    use HasFactory;

    protected $table = 'servicios_s_d_p_s';

    protected $fillable = [
        'servicio_id',
        'sdp_id',
        'valor_servicio'
    ];

    public function sdp()
    {
        return $this->belongsTo(SDP::class, 'sdp_id', 'numero_sdp');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'srevicio_id', 'codigo');
    }
}
