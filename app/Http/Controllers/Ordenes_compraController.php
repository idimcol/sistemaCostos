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

        foreach ($ordenesCompra as $orden) 
        {
            $orden->totalItems = $orden->items->count();
        }

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
        $proveedores = Proveedor::all();        
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
                'items.*.id' => 'required|exists:items_orden_compras,codigo',
                'items.*.descripcion' => 'required|string',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.precio' => 'required|numeric|min:0'
            ]);
    
            DB::beginTransaction();
    
            $ordenCompra = OrdenCompra::create([
                'proveedor_id' => $request->proveedor_id,
                'fecha_orden' => $request->fecha_orden,
                'elaboracion' => $request->elaboracion,
                'autorizacion' => $request->autorizacion,
                'iva' => $request->iva,
            ]);
    
            foreach ($request->items as $itemData) {
                $item = ItemsOrdenCompras::firstOrCreate([
                    'descripcion' => $itemData['descripcion']
                ]);
    
                $ordenCompra->items()->attach($item->codigo, [
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
        $ordenCompra = OrdenCompra::where('numero', $id)->firstOrFail();
        $proveedores = Proveedor::all();
        $itemsOrden = $ordenCompra->items;
        return view('ordenesCompra.edit', compact('ordenCompra', 'proveedores', 'itemsOrden'));
    }


    public function show($id)
    {
        $ordenCompra = OrdenCompra::where('numero', $id)->firstOrFail();

        $total = 0;

        $itemsConSubtotales = $ordenCompra->items->map(function ($item) use (&$total) {
            // Calcular el subtotal del artículo
            $subtotal = $item->pivot->cantidad * $item->pivot->precio;
            // Acumulando el subtotal al total general
            $total += $subtotal;
            // Agregar el subtotal al artículo
            return $item->setAttribute('subtotal', $subtotal);
        });

        $iva = $total * $ordenCompra->iva;
        $Total_pagar = $iva + $total;

        return view('ordenesCompra.show', compact('ordenCompra', 'itemsConSubtotales', 'total', 'iva', 'Total_pagar'));
    }

    public function update(Request $request, $id)
    {
        try {
            $ordenCompra = OrdenCompra::where('numero', $id)->firstOrFail();

            $request->validate([
                'proveedor_id' => 'required|exists:proveedores,nit',
                'fecha_orden' => 'required|date',
                'elaboracion' => 'required|string',
                'autorizacion' => 'required|string',
                'iva' => 'required|numeric',
                'items' => 'required|array',
                'items.*.descripcion' => 'required|string',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.precio' => 'required|numeric|min:0'
            ]);

            DB::beginTransaction();

            $ordenCompra->update([
                'proveedor_id' => $request->proveedor_id,
                'fecha_orden' => $request->fecha_orden,
                'elaboracion' => $request->elaboracion,
                'autorizacion' => $request->autorizacion,
                'iva' => $request->iva,
            ]);

            $itemsEnviados = $request->items;

            $items_Ids=[];

            foreach ($itemsEnviados as $itemData) {
                
                $item = itemsOrdenCompras::where('descripcion', $itemData['descripcion'])->first();

                if ($item) {
                    $items_Ids[$item->codigo] = [
                        'cantidad' => $itemData['cantidad'],
                        'precio' => $itemData['precio']
                    ];
                }
            }

            $ordenCompra->items()->syncWithoutDetaching($items_Ids);

            DB::commit();

            return redirect()->route('Ordencompras.index')->with('success', 'Orden de Compra actualizada axitosamente');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al actualizar orden de compra: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al actualizar la orden de compra. Por favor, inténtelo de nuevo.' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $ordenCompra = OrdenCompra::where('numero', $id)->firstOrFail();
        $ordenCompra->delete();

        return redirect()->back()->with('success', 'Orden de compra eliminada exitosamente');
    }
}