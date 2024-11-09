@extends('adminlte::page')

@section('title', 'COMERCIALES/CLIENTES')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('SECCION DE CLIENTES DE CADA COMERCIAL') }}
</h2>
@stop

@section('content')
    <div class="cont">
        <a href="{{ route('ADD_C_S') }}" class="button btn btn-primary">volver</a>
        <div class="container">
            <div class="center">
                <a href="{{ route('clientes-william') }}" class="btn btn-info">clientes_William</a>
                <a href="{{ route('clientes-fabian') }}" class="btn btn-info">clientes_Fabian</a>
                <a href="{{ route('clientes-ochoa') }}" class="btn btn-info">clientes-Hernando</a>
                <a href="{{ route('clientes.index') }}" class="btn btn-info">todos_los_clientes</a>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}

    <style>

        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #343a40; /* Color de fondo gris oscuro */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; /* Espacio entre botones */
            background-color: #a1a2a4; /* Color de fondo de la barra azul */
            padding: 10px;
            border-radius: 5px;
            width: 800px;
            height: 200px;
        }


        .cont {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .content, .content-header {
            background: #fff !important;
        }
    </style>
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.tailwindcss.com"></script>
@stop