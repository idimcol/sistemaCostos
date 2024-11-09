@extends('adminlte::page')

@section('title', 'grupos de tiempos de produccion')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('grupos de tiempos de procucción por operario') }}
</h2>
@stop

@section('content')
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="dark:bg-gray-500 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="mb-4">
                <a href="{{ route('tiempos-produccion.create') }}" class="bg-blue-600 hover:bg-blue-900 text-white px-3 py-2 rounded">
                    crear tiempo de produccion
                </a>
            </div>
            <div class="inline-block min-w-full py-2 align-middle container">
                <table class="table">
                    <thead>
                        <tr class="bg-gray-700 text-gray-200">
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">codigo del operario</th>
                            <th class="px-4 py-2 border">nombre del operario</th>
                            <th class="px-4 py-2 border">numero de tiempos de produccion</th>
                            <th class="px-4 py-2 border">ver lista</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tiempos_produccion as $operativo_id => $tiempo)
                            <tr class="bg-gray-300 text-gray-800 border border-black">
                                <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border">{{ $tiempo->first()->operativo_id }}</td>
                                <td class="px-4 py-2 border">{{ $tiempo->first()->nombre_operario}}</td>
                                <td class="px-4 py-2 border">{{ $tiempo->count() }}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('tiempos-produccion.operario', $operativo_id) }}" class="bg-blue-600 hover:bg-blue-900 text-white px-3 py-2 rounded">
                                        Ver Lista
                                    </a>
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
        .content{
            height: 87vh;
        }
        .container {
            max-height: 500px !important;
            overflow-y: auto !important;
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