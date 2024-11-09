@extends('adminlte::page')

@section('title', 'Articulos')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Todos los Articulos') }}
</h2>
@stop

@section('content')
    <div class="p-12">
        <div class=" flex items-end justify-end mb-4 px-20">
            <a href="{{ route('ADD_C_S') }}" class="btn btn-primary">volver</a>
        </div>
        @if (session('success'))
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="card">
            <div class="">
                <div class="card-body">
                    <table id="articulos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>codigo</th>
                                <th>descripcion</th>
                                <th>material</th>
                                <th>plano</th>
                                <th>editar</th>
                                <th>eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articulos as $index => $articulo)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>
                                <td>{{ $articulo->codigo }}</td>
                                <td>{{ $articulo->descripcion }}</td>
                                <td>{{ $articulo->material }}</td>
                                <td>{{ $articulo->plano }}</td>
                                <td>
                                    <a href="{{ route('articulos.edit', $articulo->id) }}" class="btn btn-info">
                                        Editar
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('articulos.destroy', $articulo->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este articulo?');">eliminar</button>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.bootstrap5.css">
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

        th {
            text-transform: uppercase;
        }

        td {
            text-transform: capitalize;
        }

    </style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/dataTables.scroller.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/scroller.bootstrap5.js"></script>
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
        $('#articulos').DataTable({
            "paging": false,
            "info": false,
            "scrollCollapse": true,
            "deferRender": true,
            "scroller": true,
            "scrollY": '50vh'
        });
    </script>
@stop