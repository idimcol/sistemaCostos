@extends('adminlte::page')

@section('title', 'Paquetes de SDP')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Paquetes de SDP') }}
</h2>
@stop

@section('content')
<div class="p">
    <a href="{{ route('ADD_C_S') }}" class="btn btn-primary">volver</a>
</div>
@if (session('success'))
    <div id="success-message" class="alert alert-success" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <a href="{{ route('sdp.create') }}" class="btn btn-info">crear sdp</a>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        Total de SDP <input type="text" value="{{ $totalSdp }}" class="form-control" readonly>
                    </div>
                    <div class="col-4">
                        Total de SDP abiertas <input type="text" value="{{ $sdpAbiertas }}" class="form-control" readonly>
                    </div>
                    <div class="col-4">
                        Total de SDP cerradas <input type="text" value="{{ $sdpCerradas }}" class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="container mt-4">
                <div class="column">
                    <div class="col-6 border mb-4">
                        <label for="abiertas">Filtrar SDP abiertas</label>
                        <input type="checkbox" name="abiertas" id="abiertas" class="form-checkbox" onchange="filtrarSDP()">
                    </div>
                    <div class="col-6 border">
                        <label for="cerradas">Filtrar SDP cerradas</label>
                        <input type="checkbox" name="cerradas" id="cerradas" class="form-checkbox" onchange="filtrarSDP()">
                    </div>
                </div>
            </div>
            <table class="table table-striped" id="sdp" style="width: 100%">
                <thead>
                    <tr>
                        <th>numero_sdp</th>
                        <th>clientes</th>
                        <th>nit</th>
                        <th>nombre</th>
                        <th>fecha de creacion</th>
                        <th>Estado</th>
                        <th>Cerrar o abrir SDP</th>
                        <th>lista sdps</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sdps as $index => $sdp)
                    <tr class="sdp-row" data-estado="{{ $sdp->estado }}">
                        <td>{{ $sdp->numero_sdp }}</td>
                        <td>{{ $sdp->clientes->nombre }}</td>
                        <td>{{ $sdp->clientes->nit }}</td>
                        <td>{{ $sdp->nombre ?? '' }}</td>
                        <td>{{ $sdp->created_at->format('d-m-Y') }}</td>
                        <td>{{ $sdp->estado }}</td>
                        <td>
                            @if($sdp->estado === 'abierto')
                                <form action="{{ route('sdps.cerrar', $sdp->numero_sdp) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">Cerrar_SDP</button>
                                </form>
                            @else
                                <form action="{{ route('sdps.abrir', $sdp->numero_sdp) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Abrir_SDP</button>
                                </form>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sdp.ver', $sdp->numero_sdp) }}" class="btn btn-info">
                                ver_sdp
                            </a>
                        </td>
                        <td>
                            <div class="col-4">
                                <a href="{{ route('sdp.edit', $sdp->numero_sdp) }}" class="btn btn-info">Editar</a>
                            </div>
                        </td>
                        <td>
                            <div class="COL-4">
                                <form action="{{ route('sdp.destroy', $sdp->numero_sdp) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este SDP?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.bootstrap5.css">
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    <style>
        .card-body, .card {
            background: #bdbbbb !important;
            color: #000 !important;
        }

        input {
            background: #dfdede !important;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .p {
            margin-bottom: 1rem /* 16px */;
        }


    </style>
@stop

@section('js')
{{-- Add here extra scripts --}}
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/dataTables.scroller.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/scroller.bootstrap5.js"></script>
    <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
    <script>
        $('#sdp').DataTable({
                "paging": false,
                "info": false,
                "scrollCollapse": true,
                "deferRender": true,
                "scroller": true,
                "scrollY": '50vh',
                "scrollX": true
            });
    </script>
    <script>
        function filtrarSDP() {
            const abiertasCheckbox = document.getElementById('abiertas').checked;
            const cerradasCheckbox = document.getElementById('cerradas').checked;
        
            document.querySelectorAll('.sdp-row').forEach(row => {
                const estado = row.getAttribute('data-estado').toLowerCase();
                row.style.display = 
                    (abiertasCheckbox && estado === 'abierto') || 
                    (cerradasCheckbox && estado === 'cerrado') || 
                    (!abiertasCheckbox && !cerradasCheckbox) ? '' : 'none';
            });
        }
        </script>
@stop









