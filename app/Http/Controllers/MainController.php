<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pantalla Prinsipal'
        ];
        return view('home.main', $data);
    }

}
