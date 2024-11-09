@extends('adminlte::page')

@section('title', 'costos_produccion')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Lista de SDP') }}
</h2>
@stop

@section('content')
    <div class="p-12">
        <div class="container">
            <div class="flex items-end justify-end mb-4">
                <a href="{{ route('cif.index') }}" class="btn btn-primary">volver</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="costos" class="table">
                        <thead>
                            <tr>
                                <th>Numero sdp</th>
                                <th>Comercial</th>
                                <th>cliente</th>
                                <th>Fecha despacho comercial</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sdps as $sdp)
                                <tr>
                                    <td>{{ $sdp->numero_sdp }}</td>
                                    <td>{{ $sdp->vendedores->nombre }}</td>
                                    <td>{{ $sdp->clientes->nombre }}</td>
                                    <td>{{ $sdp->fecha_despacho_comercial }}</td>
                                    <td>
                                        <a href="{{ route('costos_produccion.show', $sdp->numero_sdp) }}" class="btn btn-info">Ver Detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <style>
        .container {
            padding: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 100vh;
        }

        .card, .card-body {
            background: #b2b1b1 !important;
            color: #fff !important;
        }

        input {
            background: #fff !important;
            color: #000 !important;
        }

        label {
            color: #000 !important;
        }

        th {
            text-transform: uppercase;
        }

        td {
            text-transform: capitalize;
        }
    </style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        new DataTable('#costos', {
            paging: false,
            scrollCollapse: true,
            scrollX: true,
            scrollY: '50vh',
        });
    </script>
@stop