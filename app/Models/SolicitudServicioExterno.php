<?php

namespace App\Models;

use App\Enums\Departamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudServicioExterno extends Model
{
    use HasFactory;

    protected $table = 'solicitud_servicio_externos';

    protected $primaryKey = 'numero_ste';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'numero_ste',
        'observaciones',
        'despacho',
        'recibido',
        'departamento',
        'fecha_salida_planta'
    ];

    protected $casts = [
        'departamento' => Departamento::class
    ];

    public function itemSTE()
    {
        return $this->belongsToMany(ItemSTE::class, 'items_ste_cantidad', 'item_ste_id', 'solicitud_servicio_externo_id', 'numero_ste', 'id')
                    ->withPivot('cantidad', 'id')
                    ->withTimestamps();
    }

    public function nuevoNumero_ste()
    {
        $ultimoSTE = SolicitudServicioExterno::latest('numero_ste')->first();
        $nuevonumero_ste = $ultimoSTE ? $ultimoSTE->numero_ste + 1 : 1;
        return $nuevonumero_ste;
    }
}
