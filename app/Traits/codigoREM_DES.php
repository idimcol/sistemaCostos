<?php

namespace App\Traits;

trait codigoREM_DES
{
    protected static function bootCodigoREM_DES()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCodigoREM_DES();
        });
    }
    public static function generateUniqueCodigoREM_DES()
    {
        $latestremisionDespacho = static::latest('codigo')->first();
            
        if (!$latestremisionDespacho) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestremisionDespacho->codigo;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'REM_DES_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'REM_DES_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
