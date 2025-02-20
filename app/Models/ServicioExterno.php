<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioExterno extends Model
{
    use HasFactory;

    protected $table='servicio_externos';

    protected $fillable = [
        'nombre',
        'proveedor',
        'valor_hora'
    ];
}
