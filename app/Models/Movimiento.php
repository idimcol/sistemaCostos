<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id', 
        'tipo', 
        'cantidad', 
        'precio_unitario', 
        'fecha_movimiento',
        'descripcion'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
