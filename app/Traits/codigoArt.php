<?php

namespace App\Traits;

trait codigoArt
{
    protected static function bootCodigoArt()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCodeArt();
        });
    }
    public static function generateUniqueCodeArt()
    {
        $latestArticulo = static::latest('codigo')->first();
        
        if (!$latestArticulo) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestArticulo->codigo;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'AV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'AV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
