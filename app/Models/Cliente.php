<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $primaryKey = 'nit';
    
    public $incrementing = false;

    protected $keyType = 'number';

    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'telefono',
        'contacto',
        'correo',
        'ciudad',
        'departamento',
        'comerciales_id',
    ];


    public function SDP()
    {
        return $this->hasMany(SDP::class, 'cliente_nit', 'nit');
    }

    public function remiciones()
    {
        return $this->hasMany(RemisionesDespacho::class);
    }

    public function vendedores()
    {
        return $this->belongsTo(Vendedor::class, 'comerciales_id');
    }

    public function departamentos()
    {
        return $this->belongsTo(Departamento::class, 'departamento');
    }

    public function municipios()
    {
        return $this->belongsTo(Municipio::class, 'ciudad');
    }

    public function remisionIngreso()
    {
        return $this->hasMany(RemisionesIngreso::class, 'cliente_nit', 'nit');
    }
}
