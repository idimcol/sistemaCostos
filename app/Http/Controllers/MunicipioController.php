<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{

    public function getMunicipios($departamentoId)
    {
        $municipios = Municipio::where('departamento_id', $departamentoId)->get();
        return response()->json($municipios);
    }
    
    public function store(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'nombre_municipio' => 'required|string',
            'nombre_departamento' => 'required|string',
        ]);

        $nombreMunicipio = $validated['nombre_municipio'];
        $nombreDepartamento = $validated['nombre_departamento'];

        // Verificar si el departamento existe
        $departamento = Departamento::firstOrCreate(
            ['nombre' => $nombreDepartamento]
        );

        // Verificar si el municipio existe
        $municipio = Municipio::firstOrCreate(
            ['nombre' => $nombreMunicipio, 'departamento_id' => $departamento->id]
        );

        return response()->json([
            'message' => 'Municipio y departamento agregados correctamente.',
            'municipio' => $municipio,
            'departamento' => $departamento
        ]);
    }

    public function addMunicipio(Request $request, $departamentoId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $municipio = Municipio::create([
            'nombre' => $request->input('nombre'),
            'departamento_id' => $departamentoId,
        ]);

        return response()->json($municipio);
    }
}