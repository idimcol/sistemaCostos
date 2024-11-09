<?php

namespace App\Traits;

trait codigoAlf
{
    protected static function bootCodigoAlfanumerico()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCode();
        });
    }
    public static function generateUniqueCode()
    {
        $latestServicio = static::latest('codigo')->first();
        
        if (!$latestServicio) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestServicio->codigo;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'S-P' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'S-P' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
