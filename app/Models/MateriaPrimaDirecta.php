<?php

namespace App\Models;

use App\Traits\codigoMPD;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaPrimaDirecta extends Model
{
    use HasFactory;
    use codigoMPD;

    protected $table = 'materia_prima_directas';

    protected $fillable = [
        'codigo',
        'descripcion',
        'proveedor',
        'numero_factura',
        'numero_orden_compra',
        'precio_unit',
        'valor',
        'proveedor_id '
    ];

    public static function boot()
    {
        parent::boot();
        self::bootCodigoMPD();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'nit');
    }

    public function OrdenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'numero_orden_compra', 'numero');
    }

    public function costosProduccion()
    {
        return $this->belongsToMany(CostosSdpProduccion::class, 'materia_prima_directas_costos')
                    ->withPivot('id','cantidad', 'materia_prima_directa_id', 'costos_produccion_id',)
                    ->withTimestamps();
    }
}
