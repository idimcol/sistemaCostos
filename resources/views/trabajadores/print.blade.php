@extends('adminlte::page')

@section('title', 'print')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Empleados') }}
    </h2>
@stop

@section('content')
<body>
    <button onclick="window.print()" class="btn btn-primary no-print mt-3">Imprimir esta página</button>
    <div class="container mt-5 bg-white text-black">
        <table class="table table-bordered">
            <thead>
                <tr>
                    @foreach($campos as $campo)
                        <th>{{ ucfirst($campo) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($trabajadores as $trabajador)
                    <tr>
                        @foreach($campos as $campo)
                            <td>{{ $trabajador->$campo }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Trabajadores para Imprimir</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        
    </head>
    <style>

        table thead tr th {
            text-transform: uppercase;
            color: #000;
        }

        table tbody tr td {
            text-transform: uppercase;
            color: #000;
        }

        .container {
            padding: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        body {
            padding: 10px;
        }
    </style>
    </style>
@stop

@section('js')
{{-- Add here extra scripts --}}
<script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
