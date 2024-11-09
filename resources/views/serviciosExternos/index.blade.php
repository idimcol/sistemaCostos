@extends('adminlte::page')

@section('title', 'servicios externos')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Lista de servicios externos') }}
</h2>
@stop

@section('content')
    <div class="p-12">
        <div class="flex items-end justify-end p-10">
            <a href="{{ route('materias_primas.index') }}" class="btn btn-primary">volver</a>
        </div>
        @if (session('success'))
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="flex justify-start mb-4">
                                <a href="{{ route('serviciosExternos.create') }}" class="btn btn-primary">Crear nuevo servicio externo</a>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>Proveedor</th>
                                        <th>Valor por hora</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviciosExternos as $index => $servicioExterno)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $servicioExterno->descripcion }}</td>
                                            <td>{{ $servicioExterno->proveedor }}</td>
                                            <td>{{ $servicioExterno->valor_hora }}</td>
                                            <td>
                                                <a href="{{ route('serviciosExternos.edit', $servicioExterno->id) }}" class="btn btn-primary">Editar</a>
                                                <form action="{{ route('serviciosExternos.destroy', $servicioExterno->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio externo?')">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .card-body {
        background: #bbbbbb !important;
    }

    .content, .content-header {
        background: #fff !important;
    }

    .content {
        height: 87vh;
    }

    table thead tr th, table tbody tr td {
        background-color: #787777;
        color: #000;
        border: #000 1px solid;
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