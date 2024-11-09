@extends('adminlte::page')

@section('title', 'Editar servicio externo')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Formulario de edición de servicio externo') }}
</h2>
@stop

@section('content')
<div class="py-12">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('serviciosExternos.update', $serviciosExternos->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control" required value="{{ $serviciosExternos->descripcion }}">
                </div>
                <div class="form-group">
                    <label for="proveedor">Proveedor</label>
                    <input type="text" name="proveedor" id="proveedor" class="form-control" required value="{{ $serviciosExternos->proveedor }}">
                </div>
                <div class="form-group">
                    <label for="valor_hora">Valor por hora</label>
                    <input type="number" name="valor_hora" id="valor_hora" class="form-control" required value="{{ $serviciosExternos->valor_hora }}">
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('serviciosExternos.index') }}" class="btn btn-secondary">Cancelar</a>
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

        input {
            background: #fff !important;
            color: #000 !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop