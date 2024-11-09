<?php

namespace App\Http\Controllers;

use App\Enums\Departamento;
use App\Models\Operativo;
use App\Models\operativos;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperativoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver operarios')->only('listarOperativos');
        $this->middleware('can:crear operarios')->only('store');
        $this->middleware('can:eliminar operarios')->only('destroy');
    }
    private function generateUniqueCode()
    {
        $ultimoOperativo = Operativo::latest('codigo')->first();
        
        // Si no existe un operativo previo, empieza desde 1
        if (!$ultimoOperativo) {
            $siguienteNumero = 1;
        } else {
            // Extraer el número a partir del código, asumiendo que el código es algo como 'OP001'
            $ultimoCodigo = $ultimoOperativo->codigo;
            $ultimoNumero = intval(substr($ultimoCodigo, 2)); // Obtener el número después de 'OP'
            $siguienteNumero = $ultimoNumero + 1;
        }

        // Retornar el nuevo código con el formato 'OPXXX', donde XXX es el número con 3 dígitos
        return 'OP' . str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);
    }


    public function store(Request $request)
    {
        // Validar que el trabajador_id está presente y existe
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadors,id',
        ]);

        // Obtener el trabajador seleccionado del formulario
        $trabajador = Trabajador::find($request->input('trabajador_id'));

        // Crear un nuevo operativo para el trabajador seleccionado
        $operario = new Operativo([
            'trabajador_id' => $trabajador->id,
            'operario' => $trabajador->nombre,
        ]);

        // Generar el código único para el operativo
        $operario->codigo = $this->generateUniqueCode();

        // Guardar el operativo
        $operario->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('listar.operarios')->with('success', 'Operario asignado con éxito.');
    }


    public function listarOperativos()
    {
        
        $operativos = Operativo::with('trabajador')->orderBy('codigo')->get();
        $trabajadores = Trabajador::orderBy('nombre')->get();

        return view('trabajadores.operativos', compact('operativos', 'trabajadores'));
    }

    public function destroy(string $id)
    {
        $operarios = Operativo::findOrFail($id);
        $operarios->delete();

        return redirect()->route('listar.operarios')->with('success', 'operario eliminado');
    }
}