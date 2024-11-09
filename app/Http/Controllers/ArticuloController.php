<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver articulos de sdp')->only('index');
        $this->middleware('can:editar articulos de sdp')->only('edit');
        $this->middleware('can:eliminar articulos de sdp')->only('destroy');
    }
    public function index()
    {
        $articulos = Articulo::all();
        return view('articulos.index', compact('articulos'));
    }    
    
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'descripcion' => 'required|string|unique:articulos,descripcion',
            'material' => 'required|string',
            'plano' => 'required|string',
        ]);

        try {
            // Creación del artículo
            $articulo = Articulo::create($validated);

            return response()->json(['success' => true, 'articulo' => $articulo], 201); // Devuelve también el artículo creado
        } catch (\Throwable $e) { // Usar Throwable para capturar cualquier tipo de error
            return response()->json(['success' => false, 'message' => 'Error al crear el artículo: ' . $e->getMessage()], 500);
        }
    }
    
    public function buscarArticulos(Request $request)
    {
        $query = $request->get('q');
        $articulos = Articulo::where('descripcion', 'LIKE', "%{$query}%")->get();
    
        return response()->json($articulos);
    }

    public function getPrecioArticuloSdp(Request $request)
    {
        $articuloId = $request->input('id');
        $sdpId = $request->input('sdp_id');

        // Obtener el precio desde la tabla pivot
        $precio = DB::table('articulo_sdp')
            ->where('articulo_id', $articuloId)
            ->where('sdp_id', $sdpId)
            ->value('precio');

        return response()->json(['precio' => $precio]);
    }

    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);

        return view('articulos.edit', compact('articulo'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos recibidos
            $validated = $request->validate([
                'descripcion' => 'required|string',
                'material' => 'nullable|string',
                'plano' => 'nullable|string',
            ]);
    
            // Buscar y actualizar el artículo
            $articulo = Articulo::findOrFail($id);
            $articulo->update($validated);
    
            // Retornar respuesta de éxito
            return redirect()->route('articulos.index')->with('success', 'el articulo se ha actualizado');
    
        } catch (\Exception $e) {
            // En caso de error, retornar una respuesta con el mensaje de error
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $articulo = Articulo::findOrFaild($id);
        $articulo->delete();

        return redirect()->route('articulos.index')->with('success', 'el articulo se ha eliminado');
    }
}