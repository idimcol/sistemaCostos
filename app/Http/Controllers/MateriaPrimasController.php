<?php

namespace App\Http\Controllers;

use App\Models\Materia_Primas;
use App\Models\MateriaPrimaDirecta;
use App\Models\MateriaPrimaIndirecta;
use Illuminate\Http\Request;
use Livewire\Attributes\Validate;

class MateriaPrimasController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver secciones materias primas')->only('index');
    }
    public function index()
    {
        $materiasPrimasDirectas = MateriaPrimaDirecta::all();
        $materiasPrimasIndirectas = MateriaPrimaIndirecta::all();
        return view('materias_primas.index', compact('materiasPrimasDirectas', 
                                                'materiasPrimasIndirectas'));
    }
}
