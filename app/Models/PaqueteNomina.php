<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaqueteNomina extends Model
{
    use HasFactory;
    protected $fillable = ['mes', 'año'];

    public function nominas()
    {
        return $this->hasMany(nomina::class, 'paquete_nomina_id');
    }
}
