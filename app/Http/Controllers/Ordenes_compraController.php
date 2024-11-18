<?php

namespace App\Http\Controllers;

use App\Models\Ordenes;
use App\Models\Articulos;
use App\Models\Item;
use App\Models\itemsOrdenCompras;
use App\Models\IVA;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ordenes_compraController extends Controller
{
    public function index()
    {
        $ordenesCompra = OrdenCompra::with('proveedor')->get();
        return view('ordenesCompra.index', compact('ordenesCompra'));
    }

    public function BuscarItems(Request $request)
    {
        $query = $request->get('q');
        $items = itemsOrdenCompras::where('descripcion', 'LIKE', "%{$query}%")->get();

        return response()->json($items);
    } 

    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        
        return view('ordenesCompra.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('creacion de nueva orden de compra', $request->all());

            $request->validate([
                'proveedor_id' => 'required|exists:proveedores,nit',
                'fecha_orden' => 'required|date',
                'elaboracion' => 'required|string',
                'autorizacion' => 'required|string',
                'iva' => 'required|numeric',
                'items' => 'required|array',
                'items.*.descripcion' => 'required|string',
                'item.*.cantidad' => 'required|integer|min:1',
                'item.*.precio' => 'required|numeric|min:0'
            ]);
    
            DB::beginTransaction();
    
            $ordenCompra = OrdenCompra::create([
                'proveedor_id' => $request->proveedor_id,
                'fecha_orden' => $request->fecha_orden,
                'elaboracion' => $request->elaboracion,
                'autorizacion' => $request->autorizacion,
                'subtotal' => $request->subtotal,
                'iva' => $request->iva,
                'total' => $request->total
            ]);
    
            foreach ($request->input('items') as $itemData) {
                $item = itemsOrdenCompras::firstOrCreate([
                    'descripcion' => $itemData['descripcion']
                ]);
    
                $ordenCompra->items()->attach($item->id, [
                    'cantidad' => $itemData['cantidad'],
                    'precio' => $itemData['precio']
                ]);
            }
    
            DB::commit();

            return redirect()->route('Ordencompras.index')->with('orden de compra creada exitosamente');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear orden de compra: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al crear la orden de compra. Por favor, inténtelo de nuevo.' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $ordenCompra = OrdenCompra::with('proveedor')->findOrFail($id);

        return view('ordenesCompra.edit', compact('ordenCompra'));
    }

    public function show($id)
    {
        $ordenCompra = OrdenCompra::with('proveedor')->findOrFail($id);

        return view('ordenesCompra.show', compact('ordenCompra'));
    }

    public function update(Request $request, $id)
    {
        $ordenCompra = OrdenCompra::with('proveedor')->findOrFail($id);

        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_orden' => 'required|date',
            'elaboracion' => 'required|string',
            'autorizacion' => 'required|string',
            'subtotal' => 'required|numeric',
            'iva' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $ordenCompra->update([
            'proveedor_id' => $request->proveedor_id,
            'fecha_orden' => $request->fecha_orden,
            'elaboracion' => $request->elaboracion,
            'autorizacion' => $request->autorizacion,
            'subtotal' => $request->subtotal,
            'iva' => $request->iva,
            'total' => $request->total
        ]);

        return redirect()->route('Ordencompras.index')->with('Orden de Compra actualizada axitosamente');
    }

    public function destroy($id)
    {
        $ordenCompra = OrdenCompra::with('proveedor')->findOrFail($id);
        $ordenCompra->delete();

        return redirect()->back()->with('success', 'Orden de compra eliminada exitosamente');
    }
}