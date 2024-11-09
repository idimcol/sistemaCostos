<?php

namespace App\Models;

use App\Enums\TipoPago;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sueldo extends Model
{
    use HasFactory;

    protected $table = 'sueldos';

    protected $fillable = [
        'sueldo',
        'trabajador_id',
        'tipo_pago',
        'auxilio_transporte',
        'bonificacion_auxilio'
    ];

    protected $casts = [
        'tipo_pago' => TipoPago::class,
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }
}
