@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Formulario de la editar producto') }}
</h2>
@stop

@section('content')
    <form action="{{ route('productos.update', $producto->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $producto->nombre }}">  
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input class="form-control" id="descripcion" name="descripcion">{{ $producto->descripcion }}</input>
        </div>
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" value="{{ $producto->precio }}">    
        </div>
        <div class="form-group">
            <label for="categoria_id">Categoría</label>
            <select class="form-control" id="categoria_id" name="categoria_id"> 
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
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

@stop