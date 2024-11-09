<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'codigo', 
        'descripcion', 
        'precio', 
        'categoria_id', 
        'proveedor_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function nivelStock()
    {
        return $this->hasOne(NivelesStock::class);
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }
}
