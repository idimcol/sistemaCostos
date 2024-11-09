@extends('adminlte::page')

@section('title', 'servicios')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('actualizar servicios para la sdp') }} {{ $sdp->numero_sdp }}
</h2>
@stop

@section('content')
<div class="container">
    <div class="">
        <a href="{{ route('servicio.index') }}" class="btn btn-info mb-4">volver</a>
    </div>
    @if (session('success'))
        <div id="success-message" class="alert alert-success success-message" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="card">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sdp->servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->codigo }}</td>
                        <td>{{ $servicio->nombre }}</td>
                        <td>{{ $servicio->pivot->valor_servicio }}</td>
                        <td>
                            <form action="{{ route('sdp_servicios.actualizar', ['sdp' => $sdp->numero_sdp, 'servicio' => $servicio->codigo ]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="number" name="valor_servicio" value="{{ $servicio->pivot->valor_servicio }}" step="0.01" class="input">
                                <button type="submit" class="btn btn-primary">Actualizar Precio</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">SDP sin servicios asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>            
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .container{
            padding: 20px;
        }
        .card, .card-body {
            background: #c7bfbf;
        }

        .card-body {
            max-height: 400px;
            overflow-y: auto;
        }

        h2 {
            color: #262626;
            text-transform: uppercase;
        }

        input.input {
            width: 300px;
            padding: 2px;
            border-radius: 5px;
            transition: all 1s;
            border: #ebebeb solid 1px !important;
        }

        input.input:hover {
            background-color: #fbefca;
        }

        input.input:active {
            border: #ebebeb solid 1px !important;
            box-shadow: 1px 1px 1px #3caddd !important;
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