<?php

namespace App\Models;

use App\Traits\codigoOrdenCompra;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;
    use codigoOrdenCompra;

    protected $table = 'orden_compras';

    protected $fillable = [
        'numero',
        'proveedor_id',
        'fecha_orden',
        'iva',
        'elaboracion',
        'autorizacion'
    ];

    protected static function boot()
    {
        parent::boot();
        self::bootcodigoOrdenCompra();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'nit');
    }

    public function materiaPrimaDirecta()
    {
        return $this->hasMany(MateriaPrimaDirecta::class);
    }

    public function materiaPrimaIdirecta()
    {
        return $this->hasMany(MateriaPrimaIndirecta::class);
    }

    public function items()
    {
        return $this->belongsToMany(itemsOrdenCompras::class, 'items_orden_compras_cantidads', 'numero_orden_compra', 'item_codigo', 'numero', 'codigo')
                ->withPivot('cantidad', 'precio')  // Se especifican los campos adicionales en la tabla pivote
                ->withTimestamps(); 
    }
}
