@extends('adminlte::page')

@section('title', 'Crear orden de compra')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Crear orden de compra') }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('Ordencompras.store') }}" method="POST" class="max-w-sm mx-auto space-y-4 mb-4">
                    @csrf

                    <div class="form-group">
                        <label for="proveedor_id" class="form-label">proveedor</label>
                        <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                            <option selected="false" disabled>Seleccione un proveedor</option>
                            @foreach ( $proveedores as $proveedor )
                                <option value="{{ $proveedor->nit }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_orden" class="form-label">Fecha de entrega</label>
                        <input type="date" name="fecha_orden" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="namber" name="subtotal" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="iva" class="form-label">IVA</label>
                        <input type="namber" name="iva" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="total" class="form-label">Total</label>
                        <input type="namber" name="total" class="form-control" required>
                    </div>

                    <div class="">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('Ordencompras.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .card-body{
            background: #969595;
        }
    </style>
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