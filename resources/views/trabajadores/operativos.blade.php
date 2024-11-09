@extends('adminlte::page')

@section('title', 'Empleados Operarios')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Empleados Operarios') }}
    </h2>
@stop

@section('content')
<div class="box">
    <div class="col-12 px-20 mb-4">
        <a href="{{ route('trabajadores.index') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="flex flex-col items-end justify-end px-20 mb-4">
        <a href="{{ route('horas-extras.index') }}" id="exportToExcel" class="btnE btn btn-info">
            Bonos
        </a>
    </div>
    @if (session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="container">
        <div class="mb-4">
            <button data-toggle="modal" data-target="#modalBlue" class="btn btn-primary">nuevo operario</button>
        </div>
        <div class="table_wrapper">
            <table class="">
                <thead>
                    <tr class="">
                        <th class=" cd">#</th>
                        <!-- Información Personal -->
                        <th class=" c-1">codigo</th>
                        <th class=" c-2">Cédula</th>
                        <th class=" c-3">Nombre</th>
                        <th class=" c-4">Apellido</th>
                        <th class=" c-5">Sueldo Base</th>
                        <th class=" v">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operativos as $index => $operario)
                        <tr class="text-gray-700">
                            <td class="cd">{{ $index + 1 }}</td>
                            <td class="c-1">{{ $operario->codigo }}</td>
                            <!-- Información Personal -->
                            <td class="c-2">{{ $operario->trabajador->numero_identificacion }}</td>
                            <td class="c-3">{{ $operario->operario }}</td>
                            <td class="c-4">{{ $operario->trabajador->apellido }}</td>
                            <td class="c-5">{{ $operario->trabajador->sueldos->first()->sueldo ?? 'No tiene sueldo registrado' }}</td>
                            <td class="v">
                                <form action="{{ route('operarios.destroy', $operario->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('seguro que queres eliminar este operario') ">Eliminar</button>
                                </form>
                            </td>
                        </tr>                  
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Themed --}}
<x-adminlte-modal id="modalBlue" title="nuevo operario" theme="blue"
    icon="fas fa-bolt" size='lg' disable-animations>
    <form action="{{ route('operarios.store') }}" method="POST">
        @csrf

        <x-adminlte-select name="trabajador_id">
            @foreach ($trabajadores as $trabajador)
            <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }} {{ $trabajador->apellido }}</option>
            @endforeach
        </x-adminlte-select>
        <x-adminlte-button class="btn-flat" type="submit" label="Guardar" theme="primary" icon="fas fa-lg fa-save"/>
    </form>
</x-adminlte-modal>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>

        * {
            text-transform: capitalize;
            }
        .p-6 {
                padding: auto;
            }
    
        .box {
                padding: 10px;
                max-width: 900rem;
            }

            .content, .content-header {
                background: #fff !important;
            }

            .content {
                height: 90vh;
            }
    
        .container {
                width: 70rem;
                max-height: 500px;
                background: #8a8a8b;
                border-radius: 10px;
                padding: 20px;
            }
    
        .table_wrapper {
                background: #d6d6d6;
                padding: 1px;
                border-radius: 10px;
                max-height: 400px;
                overflow-x: auto;
            }
    
        table {
                width: 100%;
                border: #000000 1px solid;
                border-collapse: collapse;
                padding: 10px;
            }
    
            th, td {
                padding: 10px;
                border: #000000 1px solid;
                text-align: center;
            }
    
    
            thead tr th.cd {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
            table .cd {
                position: sticky;
                left: -10px;
                border:1px solid #000000;
                color: #000000;
            }
            table tbody tr td.cd {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
    
            thead tr th.c-1 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
            table .c-1 {
                position: sticky;
                left: 10px;
                border:1px solid #000000;
                color: #000000;
            }
            table tbody tr td.c-1 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
    
            thead tr th.c-2 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
            table .c-2 {
                position: sticky;
                left: 78px;
                border:1px solid #000000;
                color: #000000;
            }
            table tbody tr td.c-2 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
    
            thead tr th.c-3 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
    
            }
            table .c-3 {
                position: sticky;
                left: 170px;
                border:1px solid #000000;
                color: #000000;
            }
            table tbody tr td.c-3 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
    
            thead tr th.c-4 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
            table .c-4 {
                position: sticky;
                left: 250px;
                border:1px solid #000000;
                color: #000000;
            }
            table tbody tr td.c-4 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }

            thead tr th.c-5 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
            table .c-5 {
                position: sticky;
                left: 350px;
                border:1px solid #000000;
                color: #000000;
            }
            table tbody tr td.c-5 {
                background: #91dcff;
                border:1px solid #000000;
                color: #000000;
            }
    
            table thead tr th.cd, table thead tr th.c-1, table thead tr th.c-2, 
            table thead tr th.c-3, table thead tr th.c-4, table thead tr th.c-5 {
                background: #91dcff;
                border: #000000 1px solid;
                position: sticky;
                top: -1px;
                z-index: 2;
            }
    
            table thead tr th.v {
                background: #2b5fa3;
                color: #000000;
                border: #000000 1px solid;
                position: sticky;
                top: -2px;
    
                text-transform: uppercase;
            }
    
            table .v {
                color: #000000;
                border: #000000 1px solid;
            }

            th{
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