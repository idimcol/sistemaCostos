@extends('adminlte::page')

@section('title', 'remisiones')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('tipos de remisiones') }}
</h2>
@stop

@section('content')
    <div class="py-12">
        <div class="contaier">
            <div class="mb-4">
                <a href="{{ route('ADD_C_S') }}" class="btn btn-primary">volver</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <a href="{{ route('remision.despacho') }}" class="btn btn-primary">Remisiones de despacho</a>
                        <a href="{{ route('remision.ingreso') }}" class="btn btn-primary">Remisiones de ingreso</a>
                        <a href="{{ route('SSE.index') }}" class="btn btn-primary">solicitud de servicios externos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .card-body {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop