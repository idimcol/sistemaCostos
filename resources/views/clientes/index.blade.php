@extends('adminlte::page')

@section('title', 'CLIENTES')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Lista de Clientes') }}
    </h2>
@stop

@section('content')
<div class=" flex items-end justify-end mb-4 px-20">
    <a href="{{ route('clientes-comerciales') }}" class="btn btn-primary">volver</a>
</div>
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="card">
        <div class="card-body"> 
            <div class="mb-4">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">Crear</a>
            </div>
            <table class="table" id="clientes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIT</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Ciudad/Municpio</th>
                        <th>Departamento</th>
                        <th>Teléfono</th>
                        <th>contacto</th>
                        <th>Correo</th>
                        <th>Comercial</th>
                        <th>Actualizar</th>
                        <th>eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $index => $cliente)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $cliente->nit }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->direccion }}</td>
                        <td>{{ $cliente->municipios->nombre }}</td>
                        <td>{{ $cliente->departamentos->nombre }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->contacto }}</td>
                        <td>{{ $cliente->correo }}</td>
                        <td>{{ $cliente->vendedores->nombre }}</td>
                        <td>
                            <a href="{{ route('clientes.edit', $cliente->nit) }}" class="btn btn-info">Editar</a>
                        </td>
                        <td>
                            
                            <form action="{{ route('clientes.destroy', $cliente->nit) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar</button>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    
    <style>
        table.table {
            max-height: 700px;
            overflow: auto;
        }
        .content, .content-header {
            background: #fff !important;
        }

        .card, .card-body {
            background: #acacac !important;
            color: #000 !important;
        }

        .content {
            height: 87vh;
        }

        input {
            background: #fff !important;
            color: #000 !important;
        }
        th {
            text-transform: uppercase;
        }
    </style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.6/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
    <script>
        new DataTable('#clientes', {
            paging: false,
            scrollCollapse: true,
            scrollX: true,
            scrollY: '50vh',
        });
    </script>
@stop








