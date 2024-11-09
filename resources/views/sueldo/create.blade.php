@extends('adminlte::page')

@section('title', 'home')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
        {{ __('creacion de sueldo') }}
    </h2>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sueldo.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf

                <input type="hidden"  name="trabajador_id" value="{{ $trabajador->id }}">

                <div>
                    <label for="trabajador_id" class="form-label">Trabajador</label>
                    <input type="text" value="{{ $trabajador->id }} - {{ $trabajador->nombre }} - {{ $trabajador->apellido }}" disabled class="form-control">
                </div>

                <div class="">
                    <label for="sueldo" class="form-label">sueldo</label>
                    <input type="number" id="sueldo" name="sueldo" class="form-control" required>
                </div>

                <div class="">
                    <label for="auxilio_transporte" class="form-label">auxilio de transporte</label>
                    <input type="number" id="auxilio_transporte" name="auxilio_transporte" value="162000" class="form-control" required>
                </div>
                
                <div class="">
                    <label for="" class="form-label">bonificacion de auxilio de rodamiento</label>
                    <input type="number" id="bonificacion_auxilio" name="bonificacion_auxilio" value="0" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                    <select name="tipo_pago" id="tipo_pago" class="form-select" required>
                        @foreach (App\Enums\TipoPago::cases() as $tipoPago)
                            <option value="{{ $tipoPago->value }}">{{ $tipoPago->name }}</option>
                        @endforeach
                    </select>
                </div>
                

                <div>
                    <button type="submit" class="btn btn-primary">guardar</button>
                    <a href="{{ route('trabajadores.index') }}" class="btn btn-secondary">cancelar</a>
                </div>
            </form>
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
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        .card-body {
            background: #9a9898;
        }
        label{
            text-transform: capitalize;
        }
    </style>
@stop

@section('js')
{{-- Add here extra scripts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
@stop