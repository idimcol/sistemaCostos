<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInventarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'cantidad_anterior',
        'antidad_nuevo',
        'fecha_movimiento',
        'descripcion'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
