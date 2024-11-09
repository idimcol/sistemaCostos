<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministraciónInventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:gestion logistica')->only('index');
    }
    public function index()
    {
        return view('AdministraciónInventario.index');
    }
}
