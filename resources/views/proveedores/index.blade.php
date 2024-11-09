@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('lista de proveedores') }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="col-12 mb-4">
            <a href="{{ route('AdministraciónInventario') }}" class="btn btn-primary">volver</a>
        </div>
        @if (session('success'))
            <div id="success-message" class="alert alert-success success-message" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <a href="{{ route('proveedor.create') }}" class="btn btn-primary">Crear</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr class="table-primary">
                            <th>Nit</th>
                            <th>NOMBRE COMPLETO</th>
                            <th>PERSONA DE CONTACTO</th>
                            <th>TELEFONO</th>
                            <th>CORREO</th>
                            <th>DIRECCION</th>
                            <th>CIUDAD</th>
                            <th>EDITAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as  $proveedor)
                        <tr class="table-secondary">
                            <td>{{ $proveedor->nit }}</td>
                            <td>{{$proveedor->nombre}}</td>
                            <td>{{$proveedor->persona_contacto}}</td>
                            <td>{{$proveedor->telefono}}</td>
                            <td>{{$proveedor->email}}</td>
                            <td>{{$proveedor->direccion}}</td>
                            <td>{{$proveedor->ciudad}}</td>
                            <td>
                                <a href="{{route('proveedor.edit', $proveedor->id)}}" class="btn btn-info">Editar</a>
                            </td>
                            <td>
                                <form action="{{ route('proveedor.destroy', $proveedor->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .p {
            padding: 20px;
        }
        .card-body {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 10000);
    </script>
@stop