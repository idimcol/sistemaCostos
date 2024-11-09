<?php

namespace App\Http\Controllers;


use App\Models\SDP;
use App\Models\Servicio;
use App\Models\servicioSDP;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ServicioSdpController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver servicios sdp')->only('show');
        $this->middleware('can:editar servicios sdp')->only('actualizarPrecioServicio');
    }
    public function show($numero_sdp)
    {
        $sdp = Sdp::where('numero_sdp', $numero_sdp)->with('servicios')->firstOrFail();
        return view('servicios.verServicios', compact('sdp'));
    }

    public function actualizarPrecioServicio(Request $request, Sdp $sdp, $servicioId)
    {
        $precio = $request->input('valor_servicio');

        // Actualizar el precio en la tabla intermedia
        $sdp->servicios()->updateExistingPivot($servicioId, ['valor_servicio' => $precio]);

        return redirect()->back()->with('success', 'Precio actualizado correctamente');
    }
}
