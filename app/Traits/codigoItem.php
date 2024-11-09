<?php

namespace App\Traits;

trait codigoItem
{
    public static function bootCodigoItem()
    {
        static::creating(function ($model) {
            $model->codigo = self::generateUniqueCodigoItem();
        });
    }

    public static function generateUniqueCodigoItem()
    {
        $currentYear = date('Y');
        $latesItem = static::where('codigo', 'like', "$currentYear%")->latest('codigo')->first();

        if (!$latesItem) {
            $nextNumber = 1;
        } else {
            $lastCode = $latesItem->codigo;
            $lastNumber = intval(substr($lastCode, 5));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = $currentYear .'-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Asegurarse de que el código sea único
        while (static::where('codigo', $newCode)->exists()) {
            $nextNumber++;
            $newCode = $currentYear . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newCode;
    }
}
