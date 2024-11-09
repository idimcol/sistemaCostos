@extends('adminlte::page')

@section('title', 'editar articulo')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Formulario del nuevo editar articulo') }}
    </h2>
@stop

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('articulos.update', $articulo->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" value="{{ $articulo->descripcion }}" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="material" class="form-label">Material</label>
                        <input type="text" name="material" id="material" value="{{ $articulo->material }}" class="form-control">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="plano" class="form-label">Plano</label>
                        <input type="text" name="plano" id="plano" value="{{ $articulo->plano }}" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Actualizar Artículo</button>
                        <a href="{{ route('articulos.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .container {
            padding: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 100vh;
        }

        .card, .card-body {
            background: #b2b1b1 !important;
            color: #fff !important;
        }

        input {
            background: #fff !important;
            color: #000 !important;
        }

        label {
            color: #000 !important;
        }

    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
@stop