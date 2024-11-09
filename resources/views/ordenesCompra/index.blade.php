@extends('adminlte::page')

@section('title', 'ORDEN DE COMPRA')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('lista de ordenes de compra') }}
</h2>
@stop

@section('content')
    <div class="py-12">
        <div class="container">
            <div class="col-12 mb-4">
                <a href="{{ route('AdministraciónInventario') }}" class="btn btn-primary">volver</a>
            </div>
            @if (session('success'))
                <div id="success-message" class="alert alert-success success-message" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="col-12 mb-4">
                        <a href="{{ route('Ordencompras.create') }}" class="btn btn-primary">Crear</a>
                    </div>
                    <table class="table table-striped table-bordered table-hover" id="table">
                        <thead>
                            <tr class="table-primary">
                                <th>código</th>
                                <th>fecha de la orden</th>
                                <th>proveedor</th>
                                <th>cantidad de items</th>
                                <th>ver orden</th>
                                <th>editar</th>
                                <th>eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenesCompra as $orden)
                                <tr class="table-secondary">
                                    <td>{{ $orden->numero }}</td>
                                    <td>{{ $orden->fecha_orden }}</td>
                                    <td>{{ $orden->proveedor->nombre ?? '' }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
    <style>
        .card, .card-body {
            background-color: #d2d0d0;
        }
        th {
            text-transform: uppercase;
            text-align: center;
        }

        thead tr th, tbody tr td {
            border: #000 1px solid !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 10000);
    </script>
@stop