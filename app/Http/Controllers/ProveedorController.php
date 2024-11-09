<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    // Método para mostrar la lista de proveedores
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {   
        return view('proveedores.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nit' => 'required|string',
            'nombre' => 'required|string',
            'persona_contacto' => 'required|string',
            'email' => 'required|string|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'ciudad' => 'required|string'
        ]);

        $proveedor = Proveedor::create([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'persona_contacto' => $request->persona_contacto,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad
        ]);

        return redirect()->route('proveedor.index')->with('success', 'Proveedor creado con éxito');
    }
    // Método para mostrar el formulario de edición de un proveedor
    public function edit($id)
    {
        // Buscar el proveedor por su número de identificación
        $proveedor = Proveedor::findOrfail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    // Método para actualizar un proveedor existente en la base de datos
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrfail($id);
        
        $request->validate([
            'nit' => 'required|string',
            'nombre' => 'required|string',
            'persona_contacto' => 'required|string',
            'email' => 'required|string|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'ciudad' => 'required|string'
        ]);

        $proveedor->update([
            'nit' => $request->nit,
            'nombre' => $request->nombre,
            'persona_contacto' => $request->persona_contacto,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad
        ]);
        
        return redirect()->route('proveedor.index')->with('success', 'Proveedor actualizado con éxito');
    }

    public function destroy($id)
    {
        
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedor.index')->with('success', 'Proveedor eliminado con éxito');
    }
}
