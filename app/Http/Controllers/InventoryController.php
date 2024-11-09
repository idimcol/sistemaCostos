<?php

namespace App\Http\Controllers;

use App\Models\HistorialInventario;
use App\Models\Inventario;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with('producto', 'ubicacion')->get();
    
        return view('inventario.actual', compact('inventarios'));
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::find($id);
        $request->validate([
            'cantidad' => 'required|integer|min:0',
        ]);

        // Historial de cambios
        HistorialInventario::create([
            'producto_id' => $inventario->producto_id,
            'cantidad_anterior' => $inventario->cantidad,
            'cantidad_nueva' => $request->cantidad,
            'fecha_cambio' => now(),
            'motivo' => $request->motivo,
        ]);

        // Actualizar el inventario
        $inventario->cantidad = $request->cantidad;
        $inventario->fecha_actualizacion = now();
        $inventario->save();

        return redirect()->route('inventario.index')->with('success', 'Inventario actualizado correctamente.');
    }
}
