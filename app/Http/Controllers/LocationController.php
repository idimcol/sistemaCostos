<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDepartamentos()
    {
        $departamentos = Departamento::all();
        return response()->json($departamentos);
    }
}