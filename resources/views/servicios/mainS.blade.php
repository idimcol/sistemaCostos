@extends('adminlte::page')

@section('title', 'main_servicios')

@section('content_header')
    
@stop

@section('content')
<nav class="navbar navbar-expand-lg bg-primary mb-4">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link link" aria-current="page" href="{{ route('servicios.index') }}">servicios</a>
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
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="./output.css" rel="stylesheet">
    <style>
        .link {
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