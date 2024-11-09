<?php

namespace App\Http\Controllers;

use App\Models\ItemSTE;
use Illuminate\Http\Request;

class ItemSTEController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'servicio_requerido' => 'nullable|string',
            'dureza_HRC' => 'nullable|string',
        ]);

        try {
            $item_ste = ItemSTE::create([
                'descripcion' => $request->descripcion,
                'servicio_requerido' => $request->servicio_requerido,
                'dureza_HRC' => $request->dureza_HRC,
            ]);
            return response()->json(['success' => true, 'item' => $item_ste ], 201);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error al crear el item: ' . $e->getMessage()], 500);
        }
    }

    public function searchIem(Request $request)
    {
        $query = $request->get('q');
        $items_ste = ItemSTE::where('descripcion', 'LIKE', "%{$query}%")->get();

        return response()->json($items_ste);
    }
}
