<?php

namespace App\Http\Controllers;

use App\Models\SDP;
use App\Models\ServicioExterno;
use Illuminate\Http\Request;

class ServiciosExternosSdpController extends Controller
{
    public function index()
    {
        $sdps = SDP::all();
        return view('serviciosExternos.indexSdp', compact('sdps'));
    }
    public function create($numero_sdp)
    {
        $sdp = SDP::where('numero_sdp', $numero_sdp)->findOrFail($numero_sdp);
        $serviciosExternos = ServicioExterno::all();

        return view('serviciosExternos.subirSdp', compact('sdp'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicio_externos,id',
            'articuo_id' => 'required|exists:articulos,codigo'
        ]);
    }
}
