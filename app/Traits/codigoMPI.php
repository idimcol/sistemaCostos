<?php

namespace App\Traits;

trait codigoMPI
{
    protected static function bootCodigoMPI()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCodeMPI();
        });
    }
    public static function generateUniqueCodeMPI()
    {
        $latestMateriPrimaIndirecta = static::latest('codigo')->first();
            
        if (!$latestMateriPrimaIndirecta) {
            $nextNumber = 1;
        } else {
            $lastCode = $latestMateriPrimaIndirecta->codigo;
            $lastNumber = intval(substr($lastCode, 2));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = 'MPI' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = 'MPI' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
