@extends('adminlte::page')

@section('title', 'Editar Item')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Editar item de remisiones') }}; {{ $item->descripcion }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('items.update', $item->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="group-form">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <input type="text" name="descripcion" class="form-control" value="{{ $item->descripcion }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">cancelar</a>
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
        .card-body{
            background: #b1b1b1;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop