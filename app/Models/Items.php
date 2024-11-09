<?php

namespace App\Models;

use App\Traits\codigoItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    use codigoItem;

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public static function boot()
    {
        parent::boot();
        self::bootCodigoItem();
    }

    public function remicionesDespacho()
    {
        return $this->belongsToMany(RemisionesDespacho::class, 'items_despacho', 'remision_despacho_id', 'item_id')
                    ->withPivot('cantidad');
    }

    public function remisionesIngreso()
    {
        return $this->belongsToMany(RemisionesIngreso::class, 'items_ingreso', '	remision_ingreso_id', 'item_id')
                    ->withPivot('cantidad');        
    }
}
