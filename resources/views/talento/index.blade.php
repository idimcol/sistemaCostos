@extends('adminlte::page')

@section('title', 'gestion_humana')

@section('content_header')
    
@stop

@section('content')
<div class="navbar navbar-expand-lg bg-primary mb-4">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link link" aria-current="page" href="{{route('trabajador.butons')}}">empleados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link" href="{{route('nomina.index')}}">Nómina</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link" href="{{route('tiempos-produccion.create')}}">Tiempos de producción</a>
                </li>
            </ul>
        </div>
    </div>
</div>

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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
@stop








