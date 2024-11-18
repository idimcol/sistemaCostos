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
        // Get the latest record based on 'numero'.
        $latestOrdenCompra = static::latest('numero')->first();

        // Calculate the next number based on the latest 'numero'.
        $nextNumber = $latestOrdenCompra
            ? intval(substr($latestOrdenCompra->numero, 3)) + 1
            : 1;

        // Create the new code with padding.
        do {
            $newCode = 'OC_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::where('numero', $newCode)->exists());

        return $newCode;
    }
}
