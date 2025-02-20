<?php

namespace App\Http\Controllers;

use App\Models\CostosProduccion;
use App\Models\SDP;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\ServicioCostos;
use Illuminate\Support\Facades\Log;
use App\Traits\codigoAlf;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:gestion operacional')->only('mainS');
        $this->middleware('can:ver servicios')->only('index');
        $this->middleware('can:crear servicios')->only('create');
        $this->middleware('can:editar servicios')->only('edit');
        $this->middleware('can:eliminar servicios')->only('destroy');
        $this->middleware('can:ver sdp servicios')->only('indexSdp');
    }
    public function mainS()
    {
        $data = [
            'servicio' => 'servicios'
        ];

        return view('servicios.mainS', $data);
    }

    public function index()
    {
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    public function indexSdp()
    {
        $sdps = SDP::where('estado', 'abierto')->get(); // Obtener todas las SDPs
        return view('servicios.indexSdp', compact('sdps')); 
    }

    public function create()
    {
        $sdps = SDP::where('estado', 'abierto')->get();
        return view('servicios.create', compact('sdps'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'valor_hora' => 'required|numeric|min:0',
        ]);

        $validatedData['codigo'] = Servicio::generateUniqueCode();

        $servicio = Servicio::create($validatedData);
        

        Log::info('Nuevo servicio creado: ' . $servicio->codigo);

        return redirect()->route('servicios.index')
                        ->with('success', 'Servicio creado con éxito. Código: ' . $servicio->codigo);
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);

        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        // Validación de datos
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'valor_hora' => 'required|numeric|min:0'
            ]);
    
            $servicio = Servicio::findOrFail($id);
            $servicio->update($request->all());
    
            return redirect()->route('servicios.index')
                        ->with('success', 'Servicio actualizado con éxito');
        } catch (\Throwable $e) {
            Log::error('Error al actualizar servicio: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar servicio');
        }
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio -> delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado con éxito');
    }
}
