<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $fillable = [
        'nombre',
        'correo'
    ];

    public function sdp()
    {
        return $this->hasMany(SDP::class, 'vendedor_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'comerciales_id', 'id');
    }
}
