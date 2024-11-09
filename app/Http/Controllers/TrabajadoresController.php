<?php

namespace App\Http\Controllers;

use App\Enums\Departamento;
use App\Models\Hijo;
use Illuminate\Http\Request;
use App\Models\Trabajador;
use App\Models\Nomina;
use App\Models\Operativos;
use App\Models\Sueldo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TrabajadoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Crear trabajador')->only('create');
        $this->middleware('can:Editar trabajador')->only('edit');
        $this->middleware('can:Desabilitar trabajador')->only('disable');
        $this->middleware('can:abilitar trabajador')->only('enable');
        $this->middleware('can:trabajador-butons')->only('butons');
        $this->middleware('can:trabajadores activos')->only('activos');
        $this->middleware('can:trabajadores inactivos')->only('inactivos');
        $this->middleware('can:Ver trabajadores')->only('index');
        $this->middleware('can:generar-lista-opciones')->only('generatePrintList');
    }

    public function index()
    {
        $trabajadores = Trabajador::with(['sueldos' => function($query) {$query->latest();}])->orderBy('apellido')->get();
        
        $campos = ['nombre', 'apellido', 'cargo', 'celular', 'correo','direccion',
        'celular',
        'Eps']; 
        return view('trabajadores.index', compact('trabajadores', 'campos'));
    }

    public function activos() 
    {
        $trabajadores = Trabajador::where('estado', 'activo')->with(['sueldos' => function($query) {$query->latest();}])->orderBy('apellido')->get();
        
        return view('trabajadores.activos', compact('trabajadores'));
    }

    public function inactivos()
    {
        $trabajadores = Trabajador::where('estado', 'inactivo')->with(['sueldos' => function($query) {$query->latest();}])->orderBy('apellido')->get();
        
        return view('trabajadores.inactivos', compact('trabajadores'));
    }

    public function butons()
    {
        return view('trabajadores.butons');
    }

    public function generatePrintList(Request $request)
    {
        $campos = $request->input('campos', []);

        if ($request->input('seleccion_trabajadores') === 'todos') {
            // Obtener todos los trabajadores en orden alfabético por nombre
            $trabajadores = Trabajador::orderBy('nombre', 'asc')
                ->select('id', 'nombre', 'apellido', 'cargo')
                ->get();
        } else {
            $trabajadoresSeleccionados = $request->input('trabajadores_seleccionados', []);

            if (!empty($trabajadoresSeleccionados)) {
                // Obtener los trabajadores seleccionados en orden alfabético por nombre
                $trabajadores = Trabajador::whereIn('id', $trabajadoresSeleccionados)
                    ->orderBy('nombre', 'asc')
                    ->select('id', 'nombre', 'apellido', 'cargo')
                    ->get();
            } else {
                $trabajadores = collect(); // Devolver colección vacía si no hay seleccionados
            }
        }

        return view('trabajadores.print', compact('trabajadores', 'campos'));
    }

    public function create()
    {
        return view('trabajadores.create');
    }

    public function store(Request $request)
{
    $trabajador = new Trabajador(); 
    $trabajador->numero_identificacion = $request->input('numero_identificacion');
    $trabajador->nombre = $request->input('nombre');
    $trabajador->apellido = $request->input('apellido');
    $trabajador->edad = $request->input('edad');
    $trabajador->cargo = $request->input('cargo');
    $trabajador->fecha_nacimiento = $request->input('fecha_nacimiento');
    $trabajador->fecha_ingreso = $request->input('fecha_ingreso');
    $trabajador->tipo_pago = $request->input('tipo_pago');
    $trabajador->departamentos = $request->input('departamentos');
    $trabajador->Eps = $request->input('Eps');
    $trabajador->celular = $request->input('celular');
    $trabajador->cuenta_bancaria = $request->input('cuenta_bancaria');
    $trabajador->banco = $request->input('banco');
    $trabajador->contrato = $request->input('contrato');
    $trabajador->save();

    return redirect()->route('trabajadores.index')->with('success', 'trabajador creado exitosamente');
    
}

    public function edit($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        return view('trabajadores.edit', compact('trabajador'));
    }

    public function update(Request $request, $id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->numero_identificacion = $request->input('numero_identificacion');
        $trabajador->nombre = $request->input('nombre');
        $trabajador->apellido = $request->input('apellido');
        $trabajador->edad = $request->input('edad');
        $trabajador->cargo = $request->input('cargo');
        $trabajador->fecha_nacimiento = $request->input('fecha_nacimiento');
        $trabajador->fecha_ingreso = $request->input('fecha_ingreso');
        $trabajador->tipo_pago = $request->input('tipo_pago');
        $trabajador->departamentos = $request->input('departamentos');
        $trabajador->Eps = $request->input('Eps');
        $trabajador->celular = $request->input('celular');
        $trabajador->cuenta_bancaria = $request->input('cuenta_bancaria');
        $trabajador->banco = $request->input('banco');
        $trabajador->contrato = $request->input('contrato');
        $trabajador->save();

        return redirect()->route('trabajadores.index')->with('success', 'trabajador creado exitosamente');
    }


    // public function destroy($id)
    // {
    //     $trabajador = Trabajador::findOrFail($id);
    //     $trabajador->delete();

    //     return redirect()->route('trabajadores.index')->with('success', 'Trabajador eliminado correctamente');
    // }

    public function disable($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->estado = 'inactivo';
        $trabajador->save();

        return redirect()->route('trabajadores.index')->with('success', 'Trabajador deshabilitado exitosamente.');
    }

    public function enable($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->estado = 'activo';
        $trabajador->save();

        return redirect()->route('trabajadores.index')->with('success', 'Trabajador habilitado exitosamente.');
    }

    
}