<?php

namespace App\Http\Controllers;

use App\Models\Ordenes;
use App\Models\Articulos;
use App\Models\IVA;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class Ordenes_compraController extends Controller
{
    public function index()
    {
        $ordenesCompra = OrdenCompra::with('proveedor')->get();
        return view('ordenesCompra.index', compact('ordenesCompra'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        return view('ordenesCompra.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,nit',
            'fecha_orden' => 'required|date',
            'subtotal' => 'required|numeric',
            'iva' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $ordenCompra = OrdenCompra::create([
            'proveedor_id' => $request->proveedor_id,
            'fecha_orden' => $request->fecha_orden,
            'subtotal' => $request->subtotal,
            'iva' => $request->iva,
            'total' => $request->total
        ]);

        return redirect()->route('Ordencompras.index')->with('orden de compra creada exitosamente');
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}