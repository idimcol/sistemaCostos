<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelesStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'stock_minimo',
        'stock_maximo',
        'punto_reorden'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
