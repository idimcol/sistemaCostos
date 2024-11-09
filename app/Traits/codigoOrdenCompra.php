<?php

namespace App\Traits;

trait codigoOrdenCompra
{
    protected static function bootcodigoOrdenCompra()
    {
        static::creating(function ($model) {
            $model->numero = self::generateUniquecodigoOrdenCompra();
        });
    }
    public static function generateUniquecodigoOrdenCompra()
    {
        $latestOrdenCompra = static::latest('numero')->first();
            
        if (!$latestOrdenCompra) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestOrdenCompra->numero;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'OC_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('numero', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'OC_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
