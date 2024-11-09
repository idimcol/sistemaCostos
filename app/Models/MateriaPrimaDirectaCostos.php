<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaPrimaDirectaCostos extends Model
{
    use HasFactory;
    protected $table = 'materia_prima_directas_costos';

    protected $fillable = [
        'materia_prima_directa_id',
        'costos_produccion_id',
        'cantidad',
        'articulo_id',
        'articulo_descripcion'
    ];
}
