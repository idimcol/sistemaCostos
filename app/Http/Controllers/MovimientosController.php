<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientosController extends Controller
{
    public function create()
    {
        $productos = Producto::all();
        return view('movimientos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|string|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
        ]);

        $producto = Producto::find($request->producto_id);
        $movimiento = new Movimiento([
            'producto_id' => $request->producto_id,
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'descripcion' => $request->motivo,
            'fecha' => now(),
        ]);

        $movimiento->save();

        // Actualizar el inventario basado en el tipo de movimiento
        $inventario = $producto->inventario()->first();
        if ($request->tipo == 'entrada') {
            $inventario->cantidad += $request->cantidad;
        } elseif ($request->tipo == 'salida') {
            $inventario->cantidad -= $request->cantidad;
        }
        $inventario->save();

        return redirect()->route('inventario.index')->with('success', 'Movimiento registrado correctamente.');
    }
}
