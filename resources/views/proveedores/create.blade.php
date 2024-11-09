@extends('adminlte::page')

@section('title', 'Crear proveedor')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Formulario del nuevo proveedor') }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('proveedor.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf

                    <div>
                        <label class="form-label" for="nit">NIT</label>
                        <input type="text" name="nit" id="nit" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label" for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label" for="persona_contacto">Contacto</label>
                        <input type="text" name="persona_contacto" id="persona_contacto" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label" for="email">Correo</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label" for="telefono">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" required>
                    </div>

                    <div>
                        <label for="direccion">Direccion</label>
                        <input type="text" name="direccion" class="form-control" required>
                    </div>

                    <div>
                        <label for="ciudad">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control" required>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('proveedor.index') }}" class="btn btn-secondary">Cancelar</a>
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
    <style>
        .card-body{
            background: #8a8a8a;
        }
    </style>
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