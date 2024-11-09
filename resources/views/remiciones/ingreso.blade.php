@extends('adminlte::page')

@section('title', 'remisionesIngreso')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('remisiones de ingreso') }}
</h2>
@stop

@section('content')
<div class="py-12">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('remiciones.index') }}" class="btn btn-primary">volver</a>
        </div>
        @if (session('success'))
            <div id="success-message" class="alert alert-success" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class=" mb-4">
                    <a href="{{ route('remision.ingreso.create') }}" class="btn btn-primary">crear</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr class="table-primary">
                            <th>código</th>
                            <th>proveedor / cliente</th>
                            <th>fecha de ingreso</th>
                            <th>observaciones</th>
                            <th>despacho</th>
                            <th>departamento</th>
                            <th>recibido</th>
                            <th>editar</th>
                            <th>ver remision</th>
                            <th>eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($remisionesIngreso as $remisionIngreso)
                        <tr class="table-secondary">
                            <td>{{ $remisionIngreso->codigo }}</td>
                            <td>
                                {{ $remisionIngreso->proveedor->nombre ?? '' }}
                                {{ $remisionIngreso->cliente->nombre ?? '' }}
                            </td>
                            <td>{{ $remisionIngreso->fecha_ingreso }}</td>
                            <td>{{ $remisionIngreso->observaciones }}</td>
                            <td>{{ $remisionIngreso->despacho }}</td>
                            <td>{{ $remisionIngreso->departamento }}</td>
                            <td>{{ $remisionIngreso->recibido }}</td>
                            <td>
                                <a href="{{ route('remision.ingreso.edit', $remisionIngreso->id) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('remision.ingreso.show', $remisionIngreso->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('remision.ingreso.destroy' , $remisionIngreso->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que quiere eliminar esta remision de ingreso?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .card-body {
            max-height: 400px;
            overflow-y: auto;
        }

        th {
            text-align: center;
            text-transform: uppercase;
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