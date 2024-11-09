<?php

namespace App\Traits;

trait codigoMPD
{
    protected static function bootCodigoMPD()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCodeMPD();
        });
    }
    public static function generateUniqueCodeMPD()
    {
        $latestMateriPrimaDirecta = static::latest('codigo')->first();
            
        if (!$latestMateriPrimaDirecta) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestMateriPrimaDirecta->codigo;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'MPD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'MPD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
