<?php

namespace App\Models;

use App\Traits\codigoMPI;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaPrima_indirecta extends Model
{
    use HasFactory;

    use codigoMPI;

    protected $table = 'materia_prima_indirectas';

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
        self::bootCodigoMPI();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'nit');
    }

    public function OrdenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'numero_orden_compra', 'numero');
    }
}
