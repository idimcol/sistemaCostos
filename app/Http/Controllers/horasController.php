<?php

namespace App\Http\Controllers;

use App\Models\horas_Extras;
use App\Models\horasExtras;
use App\Models\Nominas;
use App\Models\Operativo;
use App\Models\Operativos;
use App\Models\Trabajador;
use Illuminate\Http\Request;

class horasController extends Controller
{
    public function index()
    {
        $horas_extras = horasExtras::with('operarios')->get();
        
        return view('horas_extras.index', compact('horas_extras'));
    }

    public function create()
    {
        $operarios = Operativo::orderBy('operario', 'asc')->get();
        // dd($operarios);
        return view('horas_extras.create', compact('operarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operario_cod' => 'required|exists:operativos,codigo',
            'valor_bono'=> 'required|numeric'
        ]);

        $horas_extras = new horasExtras([
            'operario_cod' => $request->operario_cod,
            'valor_bono' => $request->valor_bono,
            'horas_diurnas' => 0,
            'horas_nocturnas' => 0,
            'horas_festivos' => 0,
            'horas_recargo_nocturno' => 0
        ]);

        $horas_extras->save();

        $horas_extras->calcular_horas();

        return redirect()->route('horas-extras.index')->with('success', 'el bono se a creado exitosamente');
    }

    public function edit($id)
    {
        $horas_extras = horasExtras::findOrFail($id);

        $operarios = Operativo::all();

        return view('horas_extras.edit', compact('horas_extras', 'operarios'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'operario_cod' => 'required|exists:operativos,codigo',
            'valor_bono'=> 'required|numeric'
        ]);

        $horas_extras = horasExtras::findOrFail($id);

        $horas_extras->update([
            'operario_cod' => $request->operario_cod,
            'valor_bono' => $request->valor_bono,
            'horas_diurnas' => 0,
            'horas_nocturnas' => 0,
            'horas_festivos' => 0,
            'horas_recargo_nocturno' => 0
        ]);

        $horas_extras->save();

        $horas_extras->calcular_horas();

        return redirect()->route('horas-extras.index')->with('success', 'el bono se a actualizado exitosamente');
    }

    public function destroy($id)
    {
        $horas_extras = horasExtras::findOrFail($id);
        $horas_extras->delete();

        return redirect()->route('horas-extras.index')->with('success', 'el bono se a eliminado exitosamente');
    }
}