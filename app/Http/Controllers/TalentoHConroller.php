<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TalentoHConroller extends Controller
{
    public function __construct()
    {
        $this->middleware('can:gestion-humana')->only('index');
    }
    public function index()
    {
        $data = [
            'gestion_humana' => 'gestion humana'
        ];
        return view('talento.index', $data);
    }
}
