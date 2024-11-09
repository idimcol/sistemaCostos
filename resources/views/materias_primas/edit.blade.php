@extends('adminlte::page')

@section('title', 'cargar materia prima')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Cargar materias primas') }}
</h2>
@stop

@section('content')
    <div class="p-12">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('materias.update', ['numero_sdp' => $sdp->numero_sdp]) }}" method="POST" class="form space-y-4">

                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <div class="materias-container mb-4">
                                        <div class="mb-4">
                                            <label for="codigo" class="form-label">codigo/materia</label>
                                            <input type="text" id="codigo" name="codigo"  class=" form-control px-3 py-2">
                                        </div>
                                        <div class="mb-4">
                                            <label for="descripcion" class="form-label">descripcion</label>
                                            <input type="text" id="descripcion" name="descripcion" class=" form-control px-3 py-2">
                                        </div>
                                        <div class="mb-4">
                                            <label for="precio_unit" class="form-label">precio unitario</label>
                                            <input type="text" id="precio_unit" name="precio_unit" class=" form-control px-3 py-2">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="cantidad" class="form-label">cantidad</label>
                                        <input type="text" id="cantidad" name="cantidad" class=" form-control px-3 py-2">
                                    </div>
                                    <div class="mb-4">
                                        <label for="valor" class="form-label">valor</label>
                                        <input type="text" id="valor" name="valor" class=" form-control px-3 py-2" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <select name="articulo_id" id="articulo_id" class="form-control">
                                            @foreach ($articulos as $articulo)
                                                <option value="{{ $articulo->id }}">{{ $articulo->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary">cargar</button>
                                    <a href="{{ route('lista.sdp.cargar') }}" class="btn btn-secondary">cancelar</a>
                                </div>
                            </div>
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
        .form {
            max-width: 30rem;
            margin-left: auto;
            margin-right: auto;
        }

        .space-y-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(1rem /* 16px */ * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(1rem /* 16px */ * var(--tw-space-y-reverse));
        }

        label {
            display: block;
            color: #000000;
            text-transform: capitalize;
        }

        .suggestions-list {
            list-style-type: none; /* Elimina las viñetas de la lista */
            padding: 0;
            margin: 0;
            border: 1px solid #ddd;
            max-height: 150px; /* Altura máxima de la lista */
            overflow-y: auto; /* Muestra una barra de desplazamiento si es necesario */
            background-color: #fff;
            position: absolute; /* Posicionamiento absoluto para la lista */
            width: 500px; /* Asegura que la lista ocupe todo el ancho del input */
            z-index: 1000; /* Asegura que la lista aparezca sobre otros elementos */
            color: #000;
        }

        .suggestions-list li {
            padding: 8px; /* Espaciado interno de cada ítem */
            cursor: pointer;
        }

        .suggestions-list li:hover {
            background-color: #f0f0f0; /* Color de fondo al pasar el ratón */
            color: #000;
        }

        .card-body {
            background: #bbbbbb !important;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 87vh;
        }

        input {
            background-color: #fff !important;
            color: #000 !important;
        }

    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        // Obtener los elementos del DOM
        const precioUnitInput = document.getElementById('precio_unit');
        const cantidadInput = document.getElementById('cantidad');
        const valorInput = document.getElementById('valor');
    
        // Escuchar el cambio en el campo de cantidad
        cantidadInput.addEventListener('input', function() {
            // Obtener los valores actuales
            const precioUnitario = parseFloat(precioUnitInput.value) || 0; // Asegurarse de que sea un número
            const cantidad = parseFloat(cantidadInput.value) || 0; // Asegurarse de que sea un número
    
            // Calcular el valor
            const valor = precioUnitario * cantidad;
    
            // Asignar el valor al campo de resultado
            valorInput.value = valor.toFixed(2); // Mostrar solo 2 decimales
        });
    </script>
@stop