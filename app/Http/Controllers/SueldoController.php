<?php

namespace App\Http\Controllers;

use App\Enums\TipoPago;
use App\Models\Nomina;
use App\Models\Sueldo;
use App\Models\Trabajador;
use Illuminate\Http\Request;

class SueldoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:crear sueldo')->only('create');
        $this->middleware('can:editar sueldo')->only('edit');
    }

    public function create($trabajadorId)
    {
        $trabajador = Trabajador::findOrFail($trabajadorId);
        return view('sueldo.create', compact('trabajador'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sueldo' => 'required|numeric',
            'trabajador_id' => 'required|exists:trabajadors,id',
            'tipo_pago' => 'required|in:mensual,quincenal,semanal',
            'auxilio_transporte' => 'nullable|numeric',
            'bonificacion_auxilio' => 'required|numeric',
        ]);
    
        $sueldo = new Sueldo();
        $sueldo->sueldo = $request->input('sueldo');
        $sueldo->trabajador_id = $request->input('trabajador_id');
        $sueldo->tipo_pago = $request->input('tipo_pago');
        $sueldo->auxilio_transporte = $request->input('auxilio_transporte');
        $sueldo->bonificacion_auxilio = $request->input('bonificacion_auxilio');
        $sueldo->save();
    
        return redirect()->route('trabajadores.index')->with('success', 'Sueldo creado exitosamente');
    }

    public function edit($sueldoId)
    {
        $sueldo = Sueldo::findOrFail($sueldoId);
        $trabajador = $sueldo->trabajador;
        return view('sueldo.edit', compact('sueldo', 'trabajador'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sueldo' => 'required|numeric',
            'trabajador_id' => 'required|exists:trabajadors,id',
            'tipo_pago' => 'required|in:mensual,quincenal,semanal',
            'auxilio_transporte' => 'nullable|numeric',
            'bonificacion_auxilio' => 'required|numeric',
        ]);

        $sueldo = Sueldo::findOrFail($id);
        $sueldo->sueldo = $request->input('sueldo');
        $sueldo->trabajador_id = $request->input('trabajador_id');
        $sueldo->tipo_pago = $request->input('tipo_pago');
        $sueldo->auxilio_transporte = $request->input('auxilio_transporte');
        $sueldo->bonificacion_auxilio = $request->input('bonificacion_auxilio');
        $sueldo->save();

        return redirect()->route('trabajadores.index')->with('success', 'Sueldo actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $sueldo = Sueldo::findOrFail($id);
        $sueldo->delete();

        return redirect()->route('sueldo.index')->with('success', 'sueldo eliminado exitosamente');
    }
}
