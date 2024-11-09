<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaPrimaIndirectaCostos extends Model
{
    use HasFactory;
    protected $table = 'materia_prima_indirectas_costos';

    protected $fillable = [
        'materia_indirecta_id',
        'costos_produccion_id',
        'cantidad',
        'articulo_id',
        'articulo_descripcion'
    ];
}
