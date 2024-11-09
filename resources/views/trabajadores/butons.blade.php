@extends('adminlte::page')

@section('title', 'buttons')

@section('content_header')
    
@stop

@section('content')
    <div class="cont">
        <div class="p-5">
            <a href="{{ route('gestion-humana') }}" class="button btn btn-primary">volver</a>
        </div>
        <div class="container">
            <div class="center">
                <a href="{{ route('trabajadores.activos') }}" class="btn btn-info">empleados_activos</a>
                <a href="{{ route('trabajadores.inactivos') }}" class="btn btn-info">empleados_inactivos</a>
                <a href="{{ route('trabajadores.index') }}" class="btn btn-info">todos_los_empleados</a>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #ffffff; /* Color de fondo gris oscuro */
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
            background-color: #c6c7c8; /* Color de fondo de la barra azul */
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
        
        
    </style>
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"
    ></script>
@stop