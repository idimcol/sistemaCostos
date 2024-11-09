@extends('adminlte::page')

@section('title', 'crear categoria')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Crear Categoria') }}
</h2>
@stop

@section('content')
    <div class="p-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('categorias.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="descripcion" class="form-label">Descripción</label> 
                            <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="btn btn-primary">
                                Crear Categoria
                            </button>
                            <a href="{{ route('categorias.index') }}" class="btn btn-default">
                                Cancelar
                            </a>
                        </div>
                    </form> 
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
        background: #bbbbbb !important;
        color: #000 !important;
    }

    .content, .content-header {
        background: #fff !important;
    }

    .content {
        height: 87vh;
    }

    input, textarea {
        background: #fff !important;
        color: #000 !important;
    }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop