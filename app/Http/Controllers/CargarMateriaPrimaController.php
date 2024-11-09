<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrimaDirecta;
use App\Models\MateriaPrimaIndirecta;
use App\Models\SDP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CargarMateriaPrimaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:cargar materias primas sdp')->only('create');
        $this->middleware('can:ver materias primas cargadas')->only('verMateriasPrimas');
        $this->middleware('can:lista cargar materia sdp')->only('lista');
    }

    public function lista()
    {
        $sdps = SDP::where('estado', 'abierto')->get();

        return view('materias_primas.lista', compact('sdps'));
    }

    public function create(Request $request, $numero_sdp)
    {
        // Obtener todos los SDP
        $sdp = Sdp::where('numero_sdp', $numero_sdp)->firstOrFail();

        // Obtener todas las materias primas directas e indirectas
        $materiasPrimasDirectas = MateriaPrimaDirecta::all();
        $materiasPrimasIndirectas = MateriaPrimaIndirecta::all();

        $articulos = $sdp->articulos;

        // Retornar la vista con los datos necesarios
        return view('materias_primas.cargar', compact('sdp', 'articulos', 'materiasPrimasDirectas', 'materiasPrimasIndirectas', 'numero_sdp'));
    }

    public function store(Request $request, $numero_sdp)
    {
        // Validar el request
        $validatedData = $request->validate([
            'codigo' => 'required|string',
            'cantidad' => 'required|numeric|min:1',
            'articulo_id' => 'required|exists:articulos,id',
            'articulo_descripcion' => 'required|exists:articulos,descripcion'
        ]);

        // Buscar la SDP por su número
        $sdp = Sdp::where('numero_sdp', $numero_sdp)->where('estado', 'abierto')->firstOrFail();

        // Obtener el costo de producción relacionado con la SDP
        $costoProduccion = $sdp->costosProduccion()->first();

        if (!$costoProduccion) {
            return redirect()->back()->withErrors(['sdp' => 'No se encontró el costo de producción para esta SDP.']);
        }

        // Verificar el tipo de materia prima y agregar al costo de producción
        if (str_starts_with($validatedData['codigo'], 'MPD')) {
            // Buscar la materia prima directa por su código
            $materiaPrimaDirecta = MateriaPrimaDirecta::where('codigo', $validatedData['codigo'])->firstOrFail();

            // Agregar materia prima directa al costo de producción
            $costoProduccion->materiasPrimasDirectas()->attach($materiaPrimaDirecta->id,[
                'materia_prima_directa_id' => $materiaPrimaDirecta->id,
                'costos_produccion_id' => $costoProduccion->id,
                'cantidad' => $validatedData['cantidad'],
                'articulo_id' => $validatedData['articulo_id'],
                'articulo_descripcion' => $validatedData['articulo_descripcion']
            ]);
        } elseif (str_starts_with($validatedData['codigo'], 'MPI')) {
            // Buscar la materia prima indirecta por su código
            $materiaIndirecta = MateriaPrimaIndirecta::where('codigo', $validatedData['codigo'])->firstOrFail();

            // Agregar materia prima indirecta al costo de producción
            $costoProduccion->materiasPrimasIndirectas()->attach($materiaIndirecta->id,[
                'materia_indirecta_id' => $materiaIndirecta->id,
                'costos_produccion_id' => $costoProduccion->id,
                'cantidad' => $validatedData['cantidad'],
                'articulo_id' => $validatedData['articulo_id'],
                'articulo_descripcion' => $validatedData['articulo_descripcion']
            ]);
        } else {
            return redirect()->back()->withErrors(['codigo' => 'Código de materia prima no válido.']);
        }

        return redirect()->route('lista.sdp.cargar')->with('success', 'Materia prima agregada exitosamente.');
    }

    public function verMateriasPrimas($numero_sdp)
    {
        // Buscar la SDP por su número
        $sdp = Sdp::where('numero_sdp', $numero_sdp)->firstOrFail();
        $costoProduccion = $sdp->costosProduccion()->first();

        // Obtener las materias primas directas asociadas a la SDP
        $materiasPrimasDirectas = $costoProduccion->materiasPrimasDirectas()
            ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_prima_directa_id', 'costos_produccion_id',)
            ->get();

        // Obtener las materias primas indirectas asociadas a la SDP
        $materiasPrimasIndirectas = $costoProduccion->materiasPrimasIndirectas()
            ->withPivot('cantidad', 'articulo_id', 'articulo_descripcion', 'materia_indirecta_id', 'costos_produccion_id')
            ->get();

        // Pasar los datos a la vista
        return view('materias_primas.resumen', compact('sdp', 'materiasPrimasDirectas', 'materiasPrimasIndirectas'));
    }

    public function buscarMaterias(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['message' => 'No se proporcionó una consulta de búsqueda.'], 400);
        }

        Log::info('Consulta de búsqueda: ' . $query);

        // Búsqueda en ambas tablas
        $materiasPrimasDirectas = MateriaPrimaDirecta::where('descripcion', 'LIKE', "%{$query}%")->get();
        $materiasPrimasIndirectas = MateriaPrimaIndirecta::where('descripcion', 'LIKE', "%{$query}%")->get();

        Log::info('Materias Primas Directas: ' . $materiasPrimasDirectas);
        Log::info('Materias Primas Indirectas: ' . $materiasPrimasIndirectas);

        // Combinar ambas colecciones
        $materias = $materiasPrimasDirectas->merge($materiasPrimasIndirectas);

        return response()->json($materias);
    }
}
