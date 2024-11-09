<?php

namespace App\Models;

use App\Enums\TipoPago;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_identificacion',
        'nombre',
        'apellido',
        'edad',
        'cargo',
        'estado_civil',
        'sexo',
        'fecha_nacimiento',
        'fecha_ingreso',
        'celular',
        'Eps',
        'cuenta_bancaria',
        'banco',
        'tipo_pago',
        'departamentos',
        'contrato',
        'estado'
    ];

    protected $casts = [
        'departamentos' => Departamento::class,
        'tipo_pago' => TipoPago::class,
    ];

    public function sueldos()
    {
        return $this->hasMany(Sueldo::class, 'trabajador_id');
    }

    public function nominas()
    {
        return $this->hasMany(nomina::class);
    }

    public function dias()
    {
        return $this->hasMany(Dia::class);
    }

    public function operativos()
    {
        return $this->hasMany(Operativo::class);
    }
}
