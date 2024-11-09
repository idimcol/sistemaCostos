@extends('adminlte::page')

@section('title', 'Editar porcentaje')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Editar porcentaje de ') }} {{ $configuracion->clave }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('configuraciones.update', $configuracion->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="group-form">
                        <input type="text" name="clave" value="{{ $configuracion->clave }}" class="form-control" readonly>
                    </div>

                    <div class="group-form">
                        <label for="">Porcentaje (0.00)</label>
                        <input type="number" name="valor" value="{{ $configuracion->valor }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('configuraciones.index') }}" class="btn btn-secondary">Cancelar</a>
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
@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
@stop