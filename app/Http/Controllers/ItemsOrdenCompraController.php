<?php

namespace App\Http\Controllers;

use App\Models\itemsOrdenCompras;
use Illuminate\Http\Request;

class ItemsOrdenCompraController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string|unique:articulos,descripcion',
        ]);

        $codigo = $request->input('codigo');

        try {

            $maxConsecutivo = itemsOrdenCompras::where('codigo', 'like', '{$codigo}_%')
                ->selectRaw("CAST(SUBSTRING_INDEX(codigo, '_', -1) AS UNSIGNED) as consecutivo")
                ->orderByDesc('consecutivo')
                ->value('consecutivo');

            $nuevoConsecutivo = str_pad(($maxConsecutivo ? $maxConsecutivo + 1 : 1), 2, '0', STR_PAD_LEFT);

            $nuevoCodigo = "{$codigo}_{$nuevoConsecutivo}";

            $validated['codigo'] = $nuevoCodigo;
            // Creación del artículo
            $item = itemsOrdenCompras::create($validated);

            return response()->json(['success' => true, 'item' => $codigo], 201); // Devuelve también el artículo creado
        } catch (\Throwable $e) { // Usar Throwable para capturar cualquier tipo de error
            return response()->json(['success' => false, 'message' => 'Error al crear el item: ' . $e->getMessage()], 500);
        }
    }
}
