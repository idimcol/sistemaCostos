<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrimaIndirecta;
use Illuminate\Http\Request;

class MateriaPrimaIndirectaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver materias primas indirectas')->only('indexIndirectas');
        $this->middleware('can:crear materias primas indirectas')->only('create');
        $this->middleware('can:editar materias primas indirectas')->only('edit');
        $this->middleware('can:eliminar materias primas indirectas')->only('destroy');
    }
    public function indexIndirectas()
    {
        $materiasPrimasIndirectas = MateriaPrimaIndirecta::all();
        return view('materiasPrimasIndirectas.index', compact('materiasPrimasIndirectas'));
    }

    public function create()
    {
        return view('materiasPrimasIndirectas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'proveedor' => 'required|string',
            'numero_factura' => 'required|string',
            'numero_orden_compra' => 'required|string',
            'precio_unit' => 'required|numeric',
        ]);

        $materia_Prima_indirecta = new MateriaPrimaIndirecta([
            'descripcion' => $request->input('descripcion'),
            'proveedor' => $request->input('proveedor'),
            'numero_factura' => $request->input('numero_factura'),
            'numero_orden_compra' => $request->input('numero_orden_compra'),
            'precio_unit' => $request->input('precio_unit'),
            'valor' => 0
        ]);
        $materia_Prima_indirecta->save();

        return redirect()->route('materias_primas.index')->with('success', 'la materia prima indirecta se ha creada exitosamente');
    }

    public function edit($id)
    {
        $materia_Prima_indirecta = MateriaPrimaIndirecta::findOrFail($id);
        
        return view('materiasPrimasIndirectas.edit', compact('materia_Prima_indirecta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'proveedor' => 'required|string',
            'numero_factura' => 'required|string',
            'numero_orden_compra' => 'required|string',
            'precio_unit' => 'required|numeric',
        ]);

        $materia_Prima_indirecta = MateriaPrimaIndirecta::findOrFail($id);

        $materia_Prima_indirecta->update([
            'descripcion' => $request->input('descripcion'),
            'proveedor' => $request->input('proveedor'),
            'numero_factura' => $request->input('numero_factura'),
            'numero_orden_compra' => $request->input('numero_orden_compra'),
            'precio_unit' => $request->input('precio_unit'),
            'valor' => 0
        ]);
        

        return redirect()->route('materias_primas.index')->with('success', 'la materia prima indirecta actualizada exitosamente');
    }

    public function destroy($id)
    {
        $materia_Prima_indirecta = MateriaPrimaIndirecta::findOrFail($id);
        $materia_Prima_indirecta->delete();

        return redirect()->route('materias_primas.index')->with('success', 'la materia prima indirecta eliminada exitosamente');
    }
}
