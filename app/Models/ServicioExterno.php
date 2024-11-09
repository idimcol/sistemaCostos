<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioExterno extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'proveedor',
        'valor_hora'
    ];
}
