<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\Remicion;
use App\Models\RemisionIngreso;
use App\Models\SDP;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemicionesController extends Controller
{

    public function __construct()
    {
        // remisiones despacho
        $this->middleware('can:ver remisiones despacho')->only('Despacho');
        $this->middleware('can:crear remisiones despacho')->only('createDespacho');
        $this->middleware('can:editar remisiones despacho')->only('editDespacho');
        $this->middleware('can:eliminar remisiones despacho')->only('destroyDespacho');
        $this->middleware('can:ver formato remisiones despacho')->only('showDespacho');

        // remisiones ingreso
        $this->middleware('can:ver remisiones ingreso')->only('Ingreso');
        $this->middleware('can:crear remisiones ingreso')->only('createIngreso');
        $this->middleware('can:editar remisiones ingreso')->only('editIngreso');
        $this->middleware('can:eliminar remisiones ingreso')->only('destroyIngreso');
        $this->middleware('can:ver formato remision ingreso')->only('showIngreso');
    }

    public function index()
    {
        return view('remiciones.index');
    }

    public function Despacho()
    {
        $remicionesDespacho = Remicion::with('items', 'cliente', 'sdp')->get();
        
        return view('remiciones.despacho', compact('remicionesDespacho'));
    }

    public function getSdpsByCliente($clienteId)
    {
        // Obtener SDPs del cliente seleccionado
        $sdps = SDP::where('cliente_nit', $clienteId)->with('clientes')->get();

        // Retornar los datos en formato JSON
        return response()->json($sdps);
    }

    public function createDespacho(Request $request)
    {
        $clientes = Cliente::has('SDP')->get();
        $sdps = SDP::with('clientes', 'articulos')->get();

        return view('remiciones.createDespacho', compact('clientes', 'sdps'));
    }
    
    public function storeDespacho(Request $request)
    {
        Log::info('Creación de la nueva remicion de despacho', $request->all());

        $request->validate([
            'cliente_id' => 'required|exists:clientes,nit',
            'fecha_despacho' => 'required|date',
            'sdp_id' => 'required|exists:sdps,numero_sdp',
            'observaciones' => 'nullable|string',
            'despacho' => 'nullable|string',
            'departamento' => 'required|string',
            'recibido' => 'nullable|string',
            'items' => 'required|array',
            'items.*.cantidad' => 'required|numeric',
            'items.*.descripcion' => 'required|string',
        ]);

        DB::beginTransaction();

        $remiciones = Remicion::create([
            'cliente_id' => $request->cliente_id,
            'fecha_despacho' => $request->fecha_despacho,
            'sdp_id' => $request->sdp_id,
            'observaciones' => $request->observaciones,
            'despacho' => $request->despacho,
            'departamento' => $request->departamento,
            'recibido' => $request->recibido,
        ]);

        foreach ($request->input('items') as $itemsData){
            $item = Item::where('descripcion', $itemsData['descripcion'])->first();

            if (!$item) {
                $item = Item::create(['descripcion' => $itemsData['descripcion']]);
            }

            $remiciones->items()->attach($item->id, [
                'cantidad' => $itemsData['cantidad']
            ]);
        }

        DB::commit();

        return redirect()->route('remision.despacho')->with('success', 'Remicion de despacho creada con éxito');
    }

    public function showDespacho($id)
    {
        $remisionDespacho = Remicion::with('cliente')->find($id);

        $items = $remisionDespacho->items;

        return view('remiciones.showDespacho', compact('remisionDespacho', 'items'));
    }

    public function editDespacho($id)
    {
        $remisionDespacho = Remicion::findOrFail($id);

        return view('remiciones.editDespacho', compact('remisionDespacho'));
    }

    public function updateDespacho(Request $request, $id)
    {

        try {
            $remiciones = Remicion::findOrFail($id);

            $request->validate([
                'cliente_id' => 'required|string|exists:clientes,nit',
                'fecha_despacho' => 'required|date',
                'sdp_id' => 'required|integer|exists:sdps,numero_sdp',
                'observaciones' => 'nullable|string',
                'despacho' => 'nullable|string',
                'departamento' => 'required|string',
                'recibido' => 'nullable|string',
                'items' => 'required|array',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.descripcion' => 'required|string',
            ]);

            DB::beginTransaction();

            $remiciones->update([
                'cliente_id' => $request->cliente_id,
                'fecha_despacho' => $request->fecha_despacho,
                'sdp_id' => $request->sdp_id,
                'observaciones' => $request->observaciones,
                'despacho' => $request->despacho,
                'departamento' => $request->departamento,
                'recibido' => $request->recibido,
            ]);

            $items_esnviados = $request->input('items');

            $items_ids = [];

            foreach ($items_esnviados as $itemsData){
                $item = Item::where('descripcion', $itemsData['descripcion'])->first();

                if (!$item) {
                    $item = Item::create([
                        'descripcion' => $itemsData['descripcion']
                    ]);
                }

                $items_ids[$item->id] = [
                    'cantidad' => $itemsData['cantidad'],
                ];
            }

            $remiciones->items()->syncWithoutDetaching($items_ids);

            DB::commit();

            return redirect()->route('remision.despacho')->with('success', 'Remision de despacho se ha actualizado con éxito');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear la remision de despacho: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->withErrors('Hubo un error al actualizar la remisión de despacho.' . $e->getMessage());
        }
    }

    public function destroyDespacho($id)
    {
        $remiciones = Remicion::findOrFail($id);
        $remiciones->delete();

        return redirect()->route('remision.despacho')->with('success', 'Remision de despacho se ha eliminado con éxito');
    }

    public function Ingreso()
    {
        $remisionesIngreso = RemisionIngreso::with('proveedor')->get();
        return view('remiciones.ingreso', compact('remisionesIngreso'));
    }

    public function createIngreso(Request $request)
    {
        $proveedores = Proveedor::all();
        $clientes = Cliente::has('SDP')->get();
        $sdps = SDP::with('clientes')->get();
        $items = Item::all();

        return view('remiciones.createIngreso', compact('proveedores', 'items', 'sdps', 'clientes'));
    }

    public function storeIngreso(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'cliente_nit' => 'nullable|exists:clientes,nit',
            'sdp_id' => 'required|exists:sdps,numero_sdp',
            'fecha_ingreso' => 'required|date',
            'observaciones' => 'nullable|string',
            'despacho' => 'nullable|string',
            'departamento' => 'required|in:Administracion,Produccion',
            'recibido' => 'nullable|string',
            'items' => 'required|array',
            'items.*.cantidad' => 'required|numeric',
            'items.*.descripcion' => 'required|string',
        ]);

        DB::beginTransaction();

        $remisionesIngreso = RemisionIngreso::create([
            'proveedor_id' => $request->proveedor_id,
            'cliente_nit' => $request->cliente_nit,
            'sdp_id' => $request->sdp_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'observaciones' => $request->observaciones,
            'despacho' => $request->despacho,
            'departamento' => $request->departamento,
            'recibido' => $request->recibido,
        ]);

        foreach ($request->input('items') as $itemsData){
            $item = Item::where('descripcion', $itemsData['descripcion'])->first();

            if (!$item) {
                $item = Item::create(['descripcion' => $itemsData['descripcion']]);
            }

            $remisionesIngreso->items()->attach($item->id, [
                'cantidad' => $itemsData['cantidad']
            ]);
        }

        DB::commit();

        return redirect()->route('remision.ingreso')->with('success', 'remision de ingreso creada exitosamente');
    }

    public function showIngreso($id)
    {
        $remisionIngreso = RemisionIngreso::with('proveedor')->find($id);

        $items = $remisionIngreso->items;

        return view('remiciones.showIngreso', compact('remisionIngreso', 'items'));
    }

    public function editIngreso($id)
    {
        $remisionIngreso = RemisionIngreso::with('proveedor', 'cliente', 'items')->findOrFail($id);

        $items = $remisionIngreso->items;

        return view('remiciones.editIngreso', compact('remisionIngreso', 'items'));
    }

    public function updateIngreso(Request $request, $id)
    {
        try{

            Log::info('actualizar remision ingreso', $request->all());

            $remisionIngreso = RemisionIngreso::findOrFail($id);

            $request->validate([
                'proveedor_id' => 'nullable|exists:proveedores,id',
                'cliente_nit' => 'nullable|exists:clientes,nit',
                'sdp_id' => 'required|exists:sdps,numero_sdp',
                'fecha_ingreso' => 'required|date',
                'observaciones' => 'nullable|string',
                'despacho' => 'nullable|string',
                'departamento' => 'required|in:Administracion,Produccion',
                'recibido' => 'nullable|string',
                'items' => 'required|array',
                'items.*.cantidad' => 'required|numeric',
                'items.*.descripcion' => 'required|exists:items,descripcion',
            ]);

        
            DB::beginTransaction();

            $remisionIngreso->update([
                'proveedor_id' => $request->proveedor_id,
                'cliente_nit' => $request->cliente_nit,
                'sdp_id' => $request->sdp_id,
                'fecha_ingreso' => $request->fecha_ingreso,
                'observaciones' => $request->observaciones,
                'despacho' => $request->despacho,
                'departamento' => $request->departamento,
                'recibido' => $request->recibido,
            ]);

            $items_esnviados = $request->input('items');

            $items_ids = [];

            foreach ($items_esnviados as $itemsData){
                $item = Item::where('descripcion', $itemsData['descripcion'])->first();

                if (!$item) {
                    $item = Item::create([
                        'descripcion' => $itemsData['descripcion']
                    ]);
                }

                $items_ids[$item->id] = [
                    'cantidad' => $itemsData['cantidad'],
                ];
            }

            $remisionIngreso->items()->syncWithoutDetaching($items_ids);

            DB::commit();

            return redirect()->route('remision.ingreso')->with('success', 'remision de ingreso creada exitosamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear la remision de ingreso: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->withErrors('Hubo un error al actualizar la remisión de ingreso.' . $e->getMessage());
        }
    }

    public function destroyIngreso($id)
    {
        $remisionIngreso = RemisionIngreso::findOrFail($id);
        $remisionIngreso->delete();

        return redirect()->route('remision.ingreso')->with('success', 'remision de ingreso correctamente eliminada');
    }
}
