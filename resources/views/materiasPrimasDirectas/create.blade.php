@extends('adminlte::page')

@section('title', 'crear materia prima directa')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Formulario de la nueva materia prima directa') }}
</h2>
@stop

@section('content')
    <div class="p-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('materiasPrimasDirectas.store') }}"  method="POST" class="max-w-sm mx-auto space-y-4">
    
                        @csrf
    
                        <div>
                            <label class="form-label" for="nombre">Descripcion</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label" for="nombre">proveedor</label>
                            <select name="proveedor_id" id="" class="form-select">
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{$proveedor->nit}}">{{$proveedor->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div>
                            <label class="form-label" for="nombre">proveedor</label>
                            <input type="text" name="proveedor" id="proveedor" class="form-control" required>
                        </div>
        
                        <div>
                            <label class="form-label" for="nombre">Numero de Factura</label>
                            <input type="text" name="numero_factura" id="numero_factura" class="form-control" required>
                        </div>
        
                        <div>
                            <label class="form-label" for="nombre">Numero de orden de compra</label>
                            <input type="text" name="numero_orden_compra" id="numero_orden_compra" class="form-control" required>
                        </div>
                        
                        <div>
                            <label class="form-label" for="precio_unitario">Precio Unitario</label>
                            <input type="number" name="precio_unit" id="precio_unit" class="form-control" required>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">Guardar</button>
                            <a href="{{ route('materias_primas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
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
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <style>
        .card {
            padding: 10px;
            background: #a3a1a1 !important;
        }
        .card-body {
            padding: 8px;
            background: #a3a1a1 !important;
            color: #000 !important;
        }

        .content, .content-header {
            background-color: #fff !important;
        }

        h2 {
            text-transform: uppercase;
            font-size: 18px;
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
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"
    ></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"
    ></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selecciona los elementos del DOM
            const cantidadInput = document.getElementById('cantidad');
            const precioUnitarioInput = document.getElementById('precio_unit');
            const valorInput = document.getElementById('valor');
    
            // Función para calcular el valor
            function calcularValor() {
                // Obtén los valores de cantidad y precio unitario
                const cantidad = parseFloat(cantidadInput.value) || 0;
                const precioUnitario = parseFloat(precioUnitarioInput.value) || 0;
    
                // Calcula el valor total
                const valorTotal = cantidad * precioUnitario;
    
                // Actualiza el campo "valor" con el resultado
                valorInput.value = valorTotal.toFixed(2); // Usar toFixed para mostrar dos decimales
            }
    
            // Agregar eventos de escucha a los campos de entrada para recalcular el valor al cambiar
            cantidadInput.addEventListener('input', calcularValor);
            precioUnitarioInput.addEventListener('input', calcularValor);
        });
    </script>
@stop