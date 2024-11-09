<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrimaDirecta;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class MateriaPrimaDirectaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:crear materias primas directas')->only('create');
        $this->middleware('can:editar materias primas directas')->only('edit');
        $this->middleware('can:eliminar materias primas directas')->only('destroy');
        $this->middleware('can:ver materias primas directas')->only('indexDirectas');
    }
    public function indexDirectas()
    {
        $materiasPrimasDirectas = MateriaPrimaDirecta::all();
        return view('materiasPrimasDirectas.index', compact('materiasPrimasDirectas'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('materiasPrimasDirectas.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'proveedor_id' => 'required|exists:proveedores,nit',
            'proveedor' => 'required|string',
            'numero_factura' => 'required|string',
            'numero_orden_compra' => 'required|exists:orden__compras,numero',
            'precio_unit' => 'required|numeric',
        ]);

        $materia_Prima_directa = new MateriaPrimaDirecta([
            'descripcion' => $request->input('descripcion'),
            'proveedor' => $request->input('proveedor'),
            'numero_factura' => $request->input('numero_factura'),
            'numero_orden_compra' => $request->input('numero_orden_compra'),
            'precio_unit' => $request->input('precio_unit'),
            'valor' => 0
        ]);
        $materia_Prima_directa->save();

        return redirect()->route('materias_primas.index')->with('success', 'la materia prima directa se ha creada exitosamente');
    }

    public function edit( $id)
    {
        $materia_Prima_directa = MateriaPrimaDirecta::findOrFail($id);

        return view('materiasPrimasDirectas.edit', compact('materia_Prima_directa'));
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

        $materia_Prima_directa = MateriaPrimaDirecta::findOrFail($id);

        $materia_Prima_directa->update([
            'descripcion' => $request->input('descripcion'),
            'proveedor' => $request->input('proveedor'),
            'numero_factura' => $request->input('numero_factura'),
            'numero_orden_compra' => $request->input('numero_orden_compra'),
            'precio_unit' => $request->input('precio_unit'),
            'valor' => 0
        ]);

        return redirect()->route('materias_primas.index')->with('success', 'la materia prima directa actualizada exitosamente');
    }

    public function destroy($id)
    {
        $materia_Prima_directa = MateriaPrimaDirecta::findOrFail($id);
        $materia_Prima_directa->delete();

        return redirect()->route('materias_primas.index')->with('success', 'la materia prima directa eliminada exitosamente');
    }
}
