@extends('adminlte::page')

@section('title', 'LISTA SSE')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('lista de solicitudes de servicio externo') }}
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
                    <div class="mb-4">
                        <a href="{{ route('SSE.create') }}" class="btn btn-primary">crear</a>
                    </div>
                    <table  class="table table-striped">
                        <thead>
                            <tr class="table-primary">
                                <th>numero_sse</th>
                                <th>fecha de salida de planta</th>
                                <th>observaciones</th>
                                <th>despacho</th>
                                <th>recibido</th>
                                <th>departamento</th>
                                <th>editar</th>
                                <th>ver solicitud</th>
                                <th>eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-secondary">
                                @foreach ($solicitudesServicioExterno as $solicitudServicioExterno)
                                    <td class="text-center">{{ $solicitudServicioExterno->numero_ste }}</td>
                                    <td>{{ $solicitudServicioExterno->fecha_salida_planta }}</td>
                                    <td>{{ $solicitudServicioExterno->observaciones }}</td>
                                    <td>{{ $solicitudServicioExterno->despacho }}</td>
                                    <td>{{ $solicitudServicioExterno->recibido }}</td>
                                    <td>{{ $solicitudServicioExterno->departamento }}</td>
                                    <td>
                                        <a href="{{ route('SSE.edit', $solicitudServicioExterno->numero_ste) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('SSE.show', $solicitudServicioExterno->numero_ste) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('SSE.destroy', $solicitudServicioExterno->numero_ste) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que desea eliminar esta solicitud de servicio externo')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
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
        }, 5000);
    </script>
@stop