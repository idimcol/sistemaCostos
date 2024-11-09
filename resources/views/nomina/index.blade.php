@extends('adminlte::page')

@section('title', 'pquetes de nominas')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Paquetes de Nóminas') }}
    </h2>
@stop

@section('content')
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="mb-4">
        <a href="{{ route('gestion-humana') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="flex justify-end mb-4">
        <a href="{{ route('configuraciones.index') }}" class="btn btn-info">ver porcentajes de salud y pencion</a>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="dark:bg-gray-300 overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            <form action="{{ route('nomina.crearPaquete') }}" method="POST" class="max-w-sm mx-auto space-y-4 mb-8">
                @csrf

                <div class="form-group">
                    <label for="mes" class="label-form">Mes</label>
                    <input type="number" name="mes" min="1" max="12" class="form-control" required>
                </div class="form-group">
                
                <div class="form-group">
                    <label for="año" class="label-form">Año</label>
                    <input type="number" name="año" class="form-control" required>
                </div class="form-group">
                
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Crear Paquete de Nóminas
                </button>
            </form>

            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-800 uppercase bg-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">#</th>
                            <th scope="col" class="py-3 px-6">Mes</th>
                            <th scope="col" class="py-3 px-6">Año</th>
                            <th scope="col" class="py-3 px-6">ver nominas</th>
                            <th scope="col" class="py-3 px-6">Editar</th>
                            <th scope="col" class="py-3 px-6">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paquetes as $index => $paquete)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="py-4 px-4">{{ $index + 1 }}</td>
                                <td class="py-4 px-4">{{ $paquete->mes }}</td>
                                <td class="py-4 px-4">{{ $paquete->año }}</td>
                                <td class="py-4 px-4">
                                    <a href="{{ route('nomina.show', $paquete->id) }}" class="btn btn-info">Ver Nóminas</a>
                                </td>
                                <td class="py-4 px-4">
                                    <a href="{{ route('paqueteNomina.edit', $paquete->id) }}" class="btn btn-primary">Editar</a>
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('paquete_nominas.destroy', $paquete->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este paquete?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
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
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        td, th {
            font-size: 15px;
            font-family: "Roboto Condensed", sans-serif;
        }
        .content, .content-header {
            background: #fff !important;
        }
        .content{
            height: 87vh;
        }

        input {
            background: #fff !important;
            color: #000 !important;
        }
    </style>
@stop

@section('js')
<script>
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
</script>
@stop