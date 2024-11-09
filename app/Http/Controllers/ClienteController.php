<?php

namespace App\Http\Controllers;

use App\Enums\EstadoTarea;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\SDP;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:clientes-buttons')->only('buttons');
        $this->middleware('can:clientes willian')->only('william');
        $this->middleware('can:clientes ochoa')->only('ochoa');
        $this->middleware('can:clientes fabian')->only('fabian');
        $this->middleware('can:ver clientes')->only('index');
        $this->middleware('can:crear clientes')->only('create');
        $this->middleware('can:editar clientes')->only('edit');
        $this->middleware('can:eliminar clientes')->only('destroy');
    }
    public function index()
    {
        $clientes = Cliente::with(['departamentos', 'vendedores', 'municipios'])->get();
        return view('clientes.index', compact('clientes'));
    }

    public function buttons()
    {
        return view('clientes.buttons');
    }

    public function william()
    {
        $clientes = Cliente::whereHas('vendedores', function($query) {
            $query->where('nombre', 'WILLIAN MORENO');
        })->with(['departamentos', 'municipios'])->get();

        return view('clientes.clientes_william', compact('clientes'));
    }

    public function fabian()
    {
        $clientes = Cliente::whereHas('vendedores', function($query) {
            $query->where('nombre', 'FABIAN MORENO');
        })->with(['departamentos', 'municipios'])->get();

        return view('clientes.clientes_fabian', compact('clientes'));
    }

    public function ochoa()
    {
        $clientes = Cliente::whereHas('vendedores', function($query) {
            $query->where('nombre', 'HERNANDO OCHOA VALERO');
        })->with(['departamentos', 'municipios'])->get();
        
        return view('clientes.clientes_ochoa', compact('clientes'));
    }

    public function create()
    {
        $comerciales = Vendedor::orderBy('nombre', 'asc')->get();
        $departamentos = Departamento::orderBy('nombre', 'asc')->get();
        $municipios = Municipio::orderBy('nombre', 'asc')->get();
        return view('clientes.create', compact('comerciales', 'departamentos', 'municipios'));
    }

    public function store(Request $request)
    {
        $cliente = new Cliente();
        $cliente->nit = $request->input('nit');
        $cliente->nombre = $request->input('nombre');
        $cliente->direccion = $request->input('direccion');
        $cliente->telefono = $request->input('telefono');
        $cliente->contacto = $request->input('contacto');
        $cliente->correo = $request->input('correo');
        $cliente->comerciales_id = $request->input('comerciales_id');
        $cliente->ciudad = $request->input('ciudad');
        $cliente->departamento = $request->input('departamento');
        $cliente->save();

        $comercial = $request->input('comerciales_id');

        if ($comercial == 1) {
            // Redirigir a la vista de Hernando Ochoa Valero
            return redirect()->route('clientes-ochoa')->with('success', 'Cliente creado exitosamente para Hernando Ochoa Valero');
        } elseif ($comercial == 2) {
            // Redirigir a la vista de Fabian Moreno
            return redirect()->route('clientes-fabian')->with('success', 'Cliente creado exitosamente para Fabian Moreno');
        } elseif ($comercial == 3) {
            // Redirigir a la vista de William Moreno
            return redirect()->route('clientes-william')->with('success', 'Cliente creado exitosamente para William Moreno');
        } else {
            // Redirigir a la vista general si no coincide con ningún comercial específico
            return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente');
        }
    }

    public function edit($id)
    {
        $cliente = Cliente::with(['departamentos', 'municipios', 'vendedores'])->findOrFail($id);
        $departamentos = Departamento::orderBy('nombre', 'asc')->get();
        $municipios = Municipio::orderBy('nombre', 'asc')->get();
        $comerciales = Vendedor::orderBy('nombre', 'asc')->get();
        return view('clientes.edit', compact('cliente', 'departamentos', 'municipios', 'comerciales'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->nit = $request->input('nit');
        $cliente->nombre = $request->input('nombre');
        $cliente->direccion = $request->input('direccion');
        $cliente->telefono = $request->input('telefono');
        $cliente->contacto = $request->input('contacto');
        $cliente->correo = $request->input('correo');
        $cliente->comerciales_id = $request->input('comerciales_id');
        $cliente->ciudad = $request->input('ciudad');
        $cliente->departamento = $request->input('departamento');
        $cliente->save();

        $comercial = $request->input('comerciales_id');

        if ($comercial == 1) {
            // Redirigir a la vista de Hernando Ochoa Valero
            return redirect()->route('clientes-ochoa')->with('success', 'Cliente actualizado exitosamente para Hernando Ochoa Valero');
        } elseif ($comercial == 2) {
            // Redirigir a la vista de Fabian Moreno
            return redirect()->route('clientes-fabian')->with('success', 'Cliente actualizado exitosamente para Fabian Moreno');
        } elseif ($comercial == 3) {
            // Redirigir a la vista de William Moreno
            return redirect()->route('clientes-william')->with('success', 'Cliente actualizado exitosamente para William Moreno');
        } else {
            // Redirigir a la vista general si no coincide con ningún comercial específico
            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente');
        }
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'cliente eliminado con éxito');
    }
}