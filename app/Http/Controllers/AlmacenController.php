<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index ()
    {
        return view('almacen.index');
    }
}
