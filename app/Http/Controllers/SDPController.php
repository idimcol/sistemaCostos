<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\idimcol;
use App\Models\SDP;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class SDPController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver sdp')->only('indexPaquetes');
        $this->middleware('can:crear sdp')->only('create');
        $this->middleware('can:editar sdp')->only('edit');
        $this->middleware('can:eliminar sdp')->only('destroy');
        $this->middleware('can:abrir sdp')->only('abrir');
        $this->middleware('can:cerrar sdp')->only('cerrar');
        $this->middleware('can:ver sdp formato')->only('ver');
    }
    public function indexPaquetes()
    {
        $sdps = SDP::with('clientes')->get();
        $totalSdp = count($sdps);
        $sdpAbiertas = SDP::where('estado', 'abierto')->count();
        $sdpCerradas = SDP::where('estado', 'cerrado')->count();
        return view('SDP.paquetes', compact('sdps', 'totalSdp', 'sdpAbiertas', 'sdpCerradas'));
    }


    public function ver($id)
    {
        $sdp = SDP::with('clientes', 'articulos', 'vendedores')
        ->where('numero_sdp', $id) // Busca el SDP por su número
        ->firstOrFail();
        
        $total = 0;
        
        $articulosConSubtotales = $sdp->articulos->map(function ($articulo) use (&$total) {
            // Calcular el subtotal del artículo
            $subtotal = $articulo->pivot->cantidad * $articulo->pivot->precio;
            // Acumulando el subtotal al total general
            $total += $subtotal;
            // Agregar el subtotal al artículo
            return $articulo->setAttribute('subtotal', $subtotal);
        });

        $idimcols = idimcol::all();

        return view('SDP.ver', compact('sdp','total', 'idimcols'));
    }

    public function abrir($id)
    {
        $sdp = Sdp::findOrFail($id);

        if ($sdp->estado === 'cerrado') {
            $sdp->estado = 'abierto';
            $sdp->save();

            return redirect()->route('sdp.paquetes')->with('success', 'El SDP ha sido abierto correctamente.');
        }

        return redirect()->back()->withErrors('El SDP ya está abierto.');
    }

    public function cerrar($id)
    {
        $sdp = Sdp::findOrFail($id);

        if ($sdp->estado === 'abierto') {
            $sdp->estado = 'cerrado';
            $sdp->save();

            return redirect()->route('sdp.paquetes')->with('success', 'El SDP ha sido cerrado correctamente.');
        }

        return redirect()->back()->withErrors('El SDP ya está cerrado.');
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre', 'asc')->get(['nit', 'nombre']);
        $vendedores = Vendedor::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        
        // Obtener el último número de SDP
        $nuevoNumeroSDP = $this->numero_sdp();

        $clientesPorVendedor = [];
        foreach ($vendedores as $vendedor) {
            $clientesPorVendedor[$vendedor->id] = $vendedor->clientes()->get(['nit', 'nombre'])->toArray();
        }

        return view('SDP.create', compact('clientes', 'vendedores', 'nuevoNumeroSDP', 'clientesPorVendedor'));
    }

    public function numero_sdp()
    {
        // Bloquear la fila del último SDP para evitar condiciones de carrera
        $ultimoSDP = SDP::lockForUpdate()->latest('id')->first();
        $nuevoNumeroSDP = $ultimoSDP ? $ultimoSDP->numero_sdp + 1 : 1;
        return $nuevoNumeroSDP;
    }

    public function store(Request $request)
    {
        try {
            Log::info('Creación de un nuevo SDP', $request->all());

            // Validaciones
            $request->validate([
                'numero_sdp' => 'required|unique:sdps,numero_sdp',
                'nombre' => 'nullable|string',
                'cliente_nit' => 'required|exists:clientes,nit',
                'vendedor_id' => 'required|exists:vendedores,id',
                'fecha_despacho_comercial' => 'required|date',
                'fecha_despacho_produccion' => 'required|date',
                'observaciones' => 'nullable|string',
                'orden_compra' => 'nullable|string',
                'memoria_calculo' => 'nullable|string',
                'requisitos_cliente' => 'nullable|string',
                'articulos' => 'required|array',
                'articulos.*.descripcion' => 'required|string',
                'articulos.*.cantidad' => 'required|integer|min:1',
                'articulos.*.precio' => 'required|numeric|min:0',
            ]);

            // Iniciar una transacción
            DB::beginTransaction();

            $nuevoNumeroSDP = $this->numero_sdp();

            $cliente = Cliente::findOrFail($request->cliente_nit);
            $nombreSDP = $request->nombre ?: $cliente->nombre;

            // Crear el nuevo SDP
            $sdp = Sdp::create([
                'numero_sdp' => $nuevoNumeroSDP,
                'nombre' => $nombreSDP,
                'cliente_nit' => $request->cliente_nit,
                'vendedor_id' => $request->vendedor_id,
                'fecha_despacho_comercial' => $request->fecha_despacho_comercial,
                'fecha_despacho_produccion' => $request->fecha_despacho_produccion,
                'observaciones' => $request->observaciones,
                'orden_compra' => $request->orden_compra,
                'memoria_calculo' => $request->memoria_calculo,
                'requisitos_cliente' => $request->requisitos_cliente,
            ]);

            // Asociar los artículos con el SDP y guardar la cantidad y precio en la tabla pivote
            foreach ($request->input('articulos') as $articuloData) {
                // Buscar o crear el artículo por su descripción
                $articulo = Articulo::firstOrCreate(
                    ['descripcion' => $articuloData['descripcion']],
                    [
                        'material' => $articuloData['material'] ?? null,
                        'plano' => $articuloData['plano'] ?? null
                    ]
                );

                // Asociar el artículo con el SDP en la tabla pivote 'articulo_sdp'
                $sdp->articulos()->attach($articulo->id, [
                    'cantidad' => $articuloData['cantidad'],
                    'precio' => $articuloData['precio']
                ]);
            }

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('sdp.ver', $sdp->numero_sdp)->with('success', 'SDP creado exitosamente');
            
        } catch (\Throwable $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            Log::error('Error al crear SDP: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al crear la solicitud. Por favor, inténtelo de nuevo.']);
        }
    }

    public function edit(string $id)
    {
        $sdp = SDP::with('clientes', 'articulos', 'vendedores')->findOrFail($id);
        $clientes = Cliente::orderBy('nombre', 'asc')->get(['nit', 'nombre']);
        $vendedores = Vendedor::orderBy('nombre', 'asc')->get(['id', 'nombre']);
        return view('SDP.edit', compact('sdp', 'clientes', 'vendedores'));
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Llegó la solicitud de actualización', $request->all());

            $sdp = Sdp::findOrFail($id);
            // Validaciones
            $request->validate([
                'numero_sdp' => 'required|unique:sdps,numero_sdp,' . $sdp->id,
                'cliente_nit' => 'required|exists:clientes,nit',
                'nombre' => 'nullable|string',
                'vendedor_id' => 'required|exists:vendedores,id',
                'fecha_despacho_comercial' => 'required|date',
                'fecha_despacho_produccion' => 'required|date',
                'observaciones' => 'nullable|string',
                'orden_compra' => 'nullable|string',
                'memoria_calculo' => 'nullable|string',
                'requisitos_cliente' => 'nullable|string',
                'articulos' => 'required|array',
                'articulos.*.descripcion' => 'required|string',
                'articulos.*.cantidad' => 'required|integer|min:1',
                'articulos.*.precio' => 'required|numeric|min:0',
            ]);
            // Iniciar una transacción
            DB::beginTransaction();

            // Encontrar el SDP existente

            // Actualizar los campos del SDP
            $sdp->update([
                'numero_sdp' => $request->numero_sdp,
                'cliente_nit' => $request->cliente_nit,
                'nombre' => $request->nombre,
                'vendedor_id' => $request->vendedor_id,
                'fecha_despacho_comercial' => $request->fecha_despacho_comercial,
                'fecha_despacho_produccion' => $request->fecha_despacho_produccion,
                'observaciones' => $request->observaciones,
                'orden_compra' => $request->orden_compra,
                'memoria_calculo' => $request->memoria_calculo,
                'requisitos_cliente' => $request->requisitos_cliente,
            ]);

            // Obtener los artículos enviados en la solicitud
            $articulos_enviados = $request->input('articulos');

            // Actualizar la relación entre SDP y artículos, incluyendo la cantidad y el precio
            $articulo_ids = [];
            foreach ($articulos_enviados as $articuloData) {
                // Buscar el artículo por su descripción
                $articulo = Articulo::where('descripcion', $articuloData['descripcion'])->first();

                if ($articulo) {
                    // Almacenar la relación con cantidad y precio en la tabla pivote
                    $articulo_ids[$articulo->id] = [
                        'cantidad' => $articuloData['cantidad'],
                        'precio' => $articuloData['precio'] // Precio específico de este artículo en el SDP
                    ];
                }
            }

            // Sincronizar los artículos con el SDP, incluyendo la cantidad y el precio en la tabla pivote
            $sdp->articulos()->sync($articulo_ids);

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('sdp.ver', $sdp->numero_sdp)->with('success', 'SDP actualizado exitosamente');
            
        } catch (\Throwable $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            Log::error('Error al actualizar SDP: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al actualizar la solicitud. Por favor, inténtelo de nuevo.']);
        }
    }

    public function destroy($id)
    {
        $sdp = SDP::findOrFail($id);
        $sdp->delete();

        return redirect()->back()->with('success', 'SDP eliminado exitosamente');
    }
}