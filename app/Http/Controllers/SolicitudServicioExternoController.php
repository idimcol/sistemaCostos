<?php

namespace App\Http\Controllers;

use App\Models\ItemSTE;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\SDP;
use App\Models\SolicitudServicioExterno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use function Laravel\Prompts\error;

class SolicitudServicioExternoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver solicitudes servicios')->only('index');
        $this->middleware('can:crear solicitudes servicios')->only('create');
        $this->middleware('can:editar solicitudes servicios')->only('edit');
        $this->middleware('can:eliminar solicitudes servicio')->only('destroy');
        $this->middleware('can:ver formato solicitudes servicios')->only('show');
    }
    public function index()
    {
        $solicitudesServicioExterno = SolicitudServicioExterno::with('itemSTE')->get();
        return view('solicitudServicioExterno.index', compact('solicitudesServicioExterno'));
    }

    public function create()
    {
        $nuevoNumeroSTE = $this->nuevoNumero_ste();
        $proveedores = Proveedor::all();
        $ordenesCompra = OrdenCompra::all();
        return view('solicitudServicioExterno.create', compact('nuevoNumeroSTE', 'proveedores', 'ordenesCompra'));
    }

    public function nuevoNumero_ste()
    {
        $ultimoSTE = SolicitudServicioExterno::latest('numero_ste')->first();
        $nuevonumero_ste = $ultimoSTE ? $ultimoSTE->numero_ste + 1 : 1;
        return $nuevonumero_ste;
    }

    public function store(Request $request)
    {
        try {

            Log::info('Creacion del nuevo ste', $request->all());

            $request->validate([
                'numero_ste' => 'required|unique:solicitud_servicio_externos,numero_ste',
                'proveedor_id' => 'required|exists:proveedores,nit',
                'ordenCompra_id' => 'required|exists:orden__compras,numero',
                'direccion' => 'nullable|string',
                'observaciones' => 'nullable|string',
                'despacho' => 'nullable|string',
                'recibido' => 'nullable|string',
                'departamento' => 'required|in:Administracion,Produccion',
                'fecha_salida_planta' => 'required|date',
                'items' => 'required|array',
                'items.*.descripcion' => 'required|string',
                'items.*.servicio_requerido' => 'required|string',
                'items.*.dureza_HRC' => 'required|string',
                'items.*.cantidad' => 'required|integer|min:1'
            ]);
    
            DB::beginTransaction();
    
            $nuevoNumeroSTE = $this->nuevoNumero_ste();
    
            $solicitudesServicioExterno = SolicitudServicioExterno::create([
                'numero_ste' => $nuevoNumeroSTE,
                'observaciones' => $request->observaciones,
                'despacho' => $request->despacho,
                'recibido' => $request->recibido,
                'departamento' => $request->departamento,
                'fecha_salida_planta' => $request->fecha_salida_planta
            ]);
    
            foreach ($request->input('items') as $itemsData){
                $item = ItemSTE::where('descripcion', $itemsData['descripcion'])->first();

                if (!$item) {
                    $item = ItemSTE::create([
                        'descripcion' => $itemsData['descripcion'],
                        'servicio_requerido' => $itemsData['servicio_requerido'],
                        'dureza_HRC' => $itemsData['dureza_HRC']
                    ]);
                }
    
                $solicitudesServicioExterno->itemSTE()->attach($item->id, [
                    'cantidad' => $itemsData['cantidad']
                ]);
            }
    
            DB::commit();
    
            return redirect()->route('SSE.index')->with('success', 'Solicitud de servicio externo creada exitosamente');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear ste: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Ha ocurrido un error al crear la ste, porfavor, intentalo de nuevo: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $solicitudServicioExterno = SolicitudServicioExterno::with('itemSTE')->findOrFail($id);

        $itemsSte = $solicitudServicioExterno->itemSTE;

        return view('solicitudServicioExterno.edit', compact('solicitudServicioExterno', 'itemsSte'));
    }

    public function update(Request $request, $id)
    {
        try {

            Log::info('Actualizacion ste', $request->validated());

            $solicitudServicioExterno = SolicitudServicioExterno::findOrFail($id);

            $request->validate([
                'numero_ste' => ['required',Rule::unique('solicitud_servicio_externos', 'numero_ste')->ignore($solicitudServicioExterno->id)],
                'observaciones' => 'nullable|string|max:191',
                'despacho' => 'nullable|string|max:191',
                'recibido' => 'nullable|string|max:191',
                'departamento' => 'required|in:Administracion,Produccion',
                'fecha_salida_planta' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.descripcion' => 'required|string',
                'items.*.servicio_requerido' => 'required|string|max:191',
                'items.*.dureza_HRC' => 'required|string|max:191',
                'items.*.cantidad' => 'required|integer|min:1'
            ]);
    
            DB::beginTransaction();

    
            $solicitudServicioExterno->update([
                'numero_ste' => $request->numero_ste,
                'observaciones' => $request->observaciones,
                'despacho' => $request->despacho,
                'recibido' => $request->recibido,
                'departamento' => $request->departamento,
                'fecha_salida_planta' => $request->fecha_salida_planta
            ]);

            $itemEnviados = $request->input('items');

            $items_Ids = [];
            foreach ($itemEnviados as $itemsData) {
                $item = ItemSTE::where('descripcion', $itemsData['descripcion'])->first();

                if (!$item) {
                    $item = ItemSTE::create([
                        'descripcion' => $itemsData['descripcion'],
                        'servicio_requerido' => $itemsData['servicio_requerido'],
                        'dureza_HRC' => $itemsData['dureza_HRC'],
                    ]);
                }

                if ($item) {
                    $items_Ids[$item->id] = [
                        'cantidad' => $itemsData['cantidad']
                    ];
                }
            }

            $solicitudServicioExterno->itemSTE()->syncWithoutDetaching($items_Ids);
    
            DB::commit();
    
            return redirect()->route('SSE.index')->with('success', 'Solicitud de servicio externo actualizada exitosamente');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear ste: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Ha ocurrido un error al actualizar la ste, porfavor, intentalo de nuevo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $solicitudServicioExterno = SolicitudServicioExterno::with('itemSTE')->findOrFail($id);

        $items = $solicitudServicioExterno->itemSTE;

        return view('solicitudServicioExterno.show', compact('solicitudServicioExterno', 'items'));
    }

    public function destroy($id)
    {
        $solicitudServicioExterno = SolicitudServicioExterno::with('itemSTE')->findOrFail($id);
        $solicitudServicioExterno->delete();

        return redirect()->route('SSE.index')->with('success', 'Solicitud de servicios externos se elimino correctamente');
    }
}
