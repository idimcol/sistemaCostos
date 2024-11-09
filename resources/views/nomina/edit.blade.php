@extends('adminlte::page')

@section('title', 'pquetes de nominas')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Paquetes de Nóminas') }}
    </h2>
@stop

@section('content')
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="mb-4">
        <a href="{{ route('nomina.index') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="dark:bg-gray-300 overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            <form action="{{ route('paquetaNomina.update',$paqueteNomina->id ) }}" method="POST" class="max-w-sm mx-auto space-y-4 mb-8">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="mes" class="label-form">Mes</label>
                    <input type="number" name="mes" min="1" max="12" value="{{ $paqueteNomina->mes }}" class="form-control" required>
                </div class="form-group">
                
                <div class="form-group">
                    <label for="año" class="label-form">Año</label>
                    <input type="number" name="año" value="{{ $paqueteNomina->año }}" class="form-control" required>
                </div>
            
                <div class="">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        td, th {
            font-size: 15px;
            font-family: "Roboto Condensed", sans-serif;
        }
        .content, .content-header {
            background: #fff !important;
        }
        .content{
            height: 87vh;
        }

        input {
            background: #fff !important;
            color: #000 !important;
        }
    </style>
@stop

@section('js')
<script>
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
</script>
@stop