@extends('adminlte::page')

@section('title', 'home')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('lista de materias primas indirectas') }}
</h2>
@stop

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('materias_primas.index') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="8">MATERIAS PRIMAS INDIRECTAS</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th class="px-1">CODIGO</th>
                            <th class="px-1">DESCRIPCION</th>
                            <th class="px-1">PROVEEDOR</th>
                            <th class="px-1">NUMERO DE FACTURA</th>
                            <th class="px-1">NUMERO DE ORDEN DE COMPRA</th>
                            <th class="px-1">PRECIO UNITARIO</th>
                            <th class="px-1">EDITAR</th>
                            <th class="px-1">ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materiasPrimasIndirectas as $materiaPrimaIndirecta)
                            <tr>
                                <td class="px-1">{{ $materiaPrimaIndirecta->codigo }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->descripcion }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->proveedor }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->numero_factura }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->numero_orden_compra }}</td>
                                <td class="px-1">{{ number_format($materiaPrimaIndirecta->precio_unit, 2, ',', '.') }}</td>
                                <td class="px-1">
                                    <a href="{{ route('materiasPrimasIndirectas.edit', $materiaPrimaIndirecta->id) }}" class="text-yellow-600 hover:text-yellow-400">EDITAR</a>
                                </td>
                                <td class="px-1">
                                    <form action="{{ route('materiasPrimasIndirectas.destroy', $materiaPrimaIndirecta->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
            
                                        <button class="text-red-600 hover:text-red-400" type="submit"  onclick="return confirm('¿Estás seguro de que deseas eliminar esta materia prima indirecta?');">ELIMINAR</button>
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
    <style>
        .card-body {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop