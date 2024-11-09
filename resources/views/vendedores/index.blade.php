@extends('adminlte::page')

@section('title', 'vendedores')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Lista de comerciales') }}
</h2>
@stop

@section('content')
<div class=" flex items-end justify-end mb-4 px-20">
    <a href="{{ route('ADD_C_S') }}" class="btn btn-primary">volver</a>
</div>
@if (session('success'))
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <a href="{{ route('vendedor.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear</a>
            </div>
            <div class="table">
                <table class="min-w-full bg-gray-300 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-400 text-gray-700">
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">nombre</th>
                            <th class="px-4 py-2 border">correo</th>
                            <th class="px-4 py-2 border">editar</th>
                            <th class="px-4 py-2 border">eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendedores as $index => $vendedor)
                        <tr class=" text-gray-700">
                            <td class="px-4 py-2 border">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-2 border">{{ $vendedor->nombre }}</td>
                            <td class="px-4 py-2 border">{{ $vendedor->correo }}</td>
                            <td class="px-4 py-2 border">
                                <a href="{{ route('vendedor.edit', $vendedor->id) }}" class="text-yellow-700 hover:text-yellow-400">editar</a>
                            </td>
                            <td class="px-4 py-2 border">
                                <form action="{{ route('vendedor.destroy', $vendedor->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-400" onclick="return confirm('seguro que desea eliminar este comercial')">eliminar</button>
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
    <style>
        .content, .content-header {
            background: #fff !important;
        }
        .content {
            height: 87vh;
        }

        .card, .card-body {
            background: #b1b1b1 !important;
            color: #000 !important;
        }
        .table {
            width: 100% !important;
            max-height: 400px !important;
            overflow-y: auto !important;
        }

        table {
            width: 100% !important;
            height: 100px !important;
        }

        th {
            text-transform: capitalize;
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
        }, 5000);
    </script>
@stop