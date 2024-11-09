<?php

namespace App\Http\Controllers;

use App\Models\Servicio_esterno;
use Illuminate\Http\Request;

class servicioExternoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:ver servicios externos')->only('index');
        $this->middleware('can:crear servicios externos')->only('create');
        $this->middleware('can:editar servicios externos')->only('edit');
        $this->middleware('can:eliminar servicios externos')->only('destroy');
    }

    public function index()
    {
        $serviciosExternos = Servicio_esterno::all();

        return view('serviciosExternos.index', compact('serviciosExternos'));
    }

    public function create()
    {
        return view('serviciosExternos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'proveedor' => 'required|string',
            'valor_hora' => 'required|string',
        ]);

        $serviciosExternos = new Servicio_esterno([
            'descripcion' => $request->input('descripcion'),
            'proveedor' => $request->input('proveedor'),
            'valor_hora' => $request->input('valor_hora'),
        ]);
        $serviciosExternos->save();

        return redirect()->route('serviciosExternos.index')->with('success', 'servicio externo exitosamente creado');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $serviciosExternos = Servicio_esterno::findOrFail($id);
        return view('serviciosExternos.edit', compact('serviciosExternos'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'proveedor' => 'required|string',
            'valor_hora' => 'required|string',
        ]);

        $serviciosExternos = Servicio_esterno::findOrFail($id);
        $serviciosExternos->update([
            'descripcion' => $request->input('descripcion'),
            'proveedor' => $request->input('proveedor'),
            'valor_hora' => $request->input('valor_hora'),
        ]);

        return redirect()->route('serviciosExternos.index')->with('success', 'servicio externo exitosamente actualizado');
    }

    public function destroy(string $id)
    {
        $serviciosExternos = Servicio_esterno::findOrFail($id);
        $serviciosExternos->delete();

        return redirect()->route('serviciosExternos.index')->with('success', 'servicio externo exitosamente eliminado');
    }
}
