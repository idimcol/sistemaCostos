@extends('adminlte::page')

@section('title', 'ADMINISTRACION_INVENTARIO')

@section('content_header')
    
@stop

@section('content')
<nav class="navl navbar navbar-expand-lg bg-primary mb-4">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link link" href="{{ route('materias_primas.index') }}">CARGUE DE MATERIAS PRIMAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link" href="{{ route('Ordencompras.index') }}">ORDENES DE COMPRA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link" href="{{ route('almacen') }}">ALMACEN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link" href="{{ route('proveedor.index') }}">PROVEEDORES</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card">
        <div class="card-body">
            <img src="{{ asset('images/idimcolLogo.png') }}" alt="IDIMCOL">
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        body {
            height: 100vh;
        }
        .link {
            font-size: 20px;
            color: #000;
            font-size: 20px;
            text-transform: uppercase;
        }

        .link:hover {
            color: #5be03a;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    
        .card, .card-body {
            width: 400px !important;
            height: 400px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 100% !important;
            box-shadow: 1px 10px 1px #000  !important;
            border: #979595 1px solid;
        }
    </style>
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop