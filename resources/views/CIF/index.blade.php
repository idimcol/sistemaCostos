@extends('adminlte::page')

@section('title', 'CIF_HOME')

@section('content_header')
    
@stop

@section('content')
<nav class="navbar navbar-expand-lg bg-primary mb-4">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    @if($cif)
                        <a class="nav-link link" href="{{ route('cif.edit', $cif->id) }}">CIF</a>
                    @else
                        <p>No hay datos de CIF disponibles.</p>
                    @endif
                </li>
                <li class="nav-item">
                    <a class="nav-link link" href="{{ route('costos_produccion.index') }}">costos de producción</a>
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
        a.link {
            color: #030303;
            font-size: 22px;
            text-transform: uppercase;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card{
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
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