<?php

namespace App\Models;

use App\Enums\Departamento;
use App\Traits\codigoREM_DES;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemisionesDespacho extends Model
{
    use HasFactory;
    use codigoREM_DES;

    protected $fillable = [
        'codigo',
        'cliente_id',
        'fecha_despacho',
        'sdp_id',
        'observaciones',
        'despacho',
        'departamento',
        'recibido'
    ];

    protected $casts = [
        'departamento' => Departamento::class
    ];

    protected static function boot()
    {
        parent::boot();
        self::bootCodigoREM_DES();
    }
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'nit');
    }

    public function sdp()
    {
        return $this->belongsTo(SDP::class, 'sdp_id', 'numero_sdp');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'items_despacho', 'remision_despacho_id', 'item_id', 'codigo', 'codigo')
                    ->withPivot('cantidad');
    }
}
