@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Formulario de la nuevo producto') }}
</h2>
@stop

@section('content')
<div class="p-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('productos.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio">
                    </div>
                    <div class="form-group mb-4">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select class="form-control" id="categoria_id" name="categoria_id">
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('almacen') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
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

        input, textarea, select {
            background: #fff !important;
            color: #000 !important;
        }

        h2 {
            text-transform: uppercase;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.tailwindcss.com"></script>
@stop
