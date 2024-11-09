@extends('adminlte::page')

@section('title', 'editar vendedor')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
        {{ __('Editar el comercial') }} {{ $vendedor->nombre }}
    </h2>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('vendedor.update', $vendedor->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label" for="nombre">nombre</label>
                    <input type="text" name="nombre" value="{{ $vendedor->nombre }}" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="correo">correo electronico</label>
                    <input type="email" name="correo" value="{{ $vendedor->correo }}" class="form-control" required>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">Guardar</button>
                    <a href="{{ route('vendedor.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .container {
            padding: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 87vh;
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

    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop