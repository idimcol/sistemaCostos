<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemsOrdenCompras extends Model
{
    use HasFactory;

    protected $table = 'items_orden_compras';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public function OrdenCompra()
    {
        return $this->belongsToMany(OrdenCompra::class, 'items_orden_compras_cantidads', 'item_codigo', 'numero_orden_compra',  'codigo', 'numero')
                ->withPivot('cantidad', 'precio')
                ->withTimestamps();
    }
}
