<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:ver items')->only('index');
        $this->middleware('can:editar items')->only('edit');
    }

    public function index()
    {
        $items = Item::all();

        return view('items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        try {
            $item = Item::create([
                'descripcion' => $request->input('descripcion'),
            ]);
    
            return response()->json(['success' => true, 'item' => $item], 201);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Error al crear el item: ' . $e->getMessage()], 500);
        }
    }

    public function searchItem(Request $request)
    {
        $query = $request->get('q');
        $items = Item::where('descripcion', 'LIKE', "%{$query}%")->get();

        return response()->json($items);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);

        return view('items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        $item = Item::findOrFail($id);
        $item->update([
            'descripcion' => $request->input('descripcion'),
        ]);

        return redirect()->route('items.index')->with('success', 'Item creado exitosamente');
    } 
}
