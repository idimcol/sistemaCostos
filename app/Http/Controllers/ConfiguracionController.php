<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver porcentajes')->only('index');
        $this->middleware('can:editar porcentajes')->only('edit');
    }
    public function index()
    {
        $configuraciones = Configuracion::all();
        return view('configuracion.index', compact('configuraciones'));
    }

    public function edit($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        return view('configuracion.edit', compact('configuracion'));
    }

    public function update(Request $request, $id)
    {
        $configuracion = Configuracion::findOrFail($id);

        $request->validate([
            'clave' => 'required|string|unique:configuraciones,clave,' . $configuracion->id,
            'valor' => 'required|numeric',
        ]);

        $configuracion->update([
            'clave' => $request->clave,
            'valor' => $request->valor
        ]);

        return redirect()->route('configuraciones.index')->with('success', 'Porcentaje actualizado exitosamente');
    }
}
