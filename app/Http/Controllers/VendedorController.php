<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:ver comerciales')->only('index');
        $this->middleware('can:crear comerciales')->only('create');
        $this->middleware('can:editar comerciales')->only('edit');
        $this->middleware('can:eliminar comerciales')->only('destroy');
    }

    public function index()
    {
        $vendedores = Vendedor::all();
        return view('vendedores.index', compact('vendedores'));
    }

    public function create()
    {
        return view('vendedores.create');
    }

    public function store(Request $request)
    {
        $vendedor = new Vendedor();
        $vendedor->nombre = $request->input('nombre');
        $vendedor->correo = $request->input('correo');
        $vendedor->save();

        return redirect()->route('vendedor.index')->with('success', 'vendedor creado con exito');
    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        $vendedor = Vendedor::find($id);
        return view('vendedores.edit', compact('vendedor'));
    }

    public function update(Request $request, string $id)
    {
        $vendedor = Vendedor::find($id);
        $vendedor->nombre = $request->input('nombre');
        $vendedor->correo = $request->input('correo');
        $vendedor->save();

        return redirect()->route('vendedor.index')->with('success', 'vendedor actualizado con exito');
    }

    public function destroy(string $id)
    {
        $vendedor = Vendedor::find($id);
        $vendedor->delete();

        return redirect()->route('vendedor.index')->with('success', 'vendedor eliminado con exito');
    }
}
