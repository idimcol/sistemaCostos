<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nit',
        'nombre',
        'persona_contacto',
        'email',
        'telefono',
        'direccion',
        'ciudad'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function remisionIngreso()
    {
        return $this->hasMany(RemisionesIngreso::class);
    }

    public function materiaPrimaDirecta()
    {
        return $this->hasMany(MateriaPrimaDirecta::class);
    }

    public function materiaPrimaIndirecta()
    {
        return $this->hasMany(MateriaPrimaDirecta::class);
    }

    public function OrdenCompra()
    {
        return $this->hasMany(OrdenCompra::class, 'proveedor_id', 'nit');
    }
}
