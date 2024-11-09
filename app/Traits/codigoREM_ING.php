<?php

namespace App\Traits;

trait codigoREM_ING
{
    protected static function bootCodigoREM_ING()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCodigoREM_ING();
        });
    }
    public static function generateUniqueCodigoREM_ING()
    {
        $latestremisionIngreso = static::latest('codigo')->first();
            
        if (!$latestremisionIngreso) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestremisionIngreso->codigo;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'REM_ING_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'REM_ING_' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
