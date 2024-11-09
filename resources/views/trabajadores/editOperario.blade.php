@extends('adminlte::page')

@section('title', 'editar operario')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
        {{ __('Todos los Empleados') }}
    </h2>
@stop

@section('content')
    <div class="py-12">
        <div class="ontainer">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{$operario->
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
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop