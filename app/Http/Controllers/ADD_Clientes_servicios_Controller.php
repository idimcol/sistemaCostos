<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ADD_Clientes_servicios_Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('can:gestion comercial')->only('index');
    }
    public function index()
    {
        $data = [
            'ADD' => 'ADD' 
        ];
        return view('ADD_C_S.index', $data);
    }
}
