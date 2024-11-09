@extends('adminlte::page')

@section('title', 'home')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('lista de materias primas directas') }}
</h2>
@stop

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('materias_primas.index') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="8">MATERIAS PRIMAS DIRECTAS</th>
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
                        <th class="px-1">EDTAR</th>
                        <th class="px-1">ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materiasPrimasDirectas as $materiaPrimaDirecta)
                        <tr>
                            <td class="px-1">{{ $materiaPrimaDirecta->codigo }}</td>
                            <td class="px-1">{{ $materiaPrimaDirecta->descripcion }}</td>
                            <td class="px-1">{{ $materiaPrimaDirecta->proveedor }}</td>
                            <td class="px-1">{{ $materiaPrimaDirecta->numero_factura }}</td>
                            <td class="px-1">{{ $materiaPrimaDirecta->numero_orden_compra }}</td>
                            <td class="px-1">{{ $materiaPrimaDirecta->precio_unit }}</td>
                            <td class="px-1">
                                <a href="{{ route('materiasPrimasDirectas.edit', $materiaPrimaDirecta->id) }}" class="text-yellow-600 hover:text-yellow-300">EDITAR</a>
                            </td>
                            <td class="px-1">
                                <form action="{{ route('materiasPrimasDirectas.destroy', $materiaPrimaDirecta->id ) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
        
                                    <button type="submit" class="text-red-600 hover:text-red-400" onclick="return confirm('¿Estás seguro de que deseas eliminar esta materia prima directa?');">ELIMINAR</button>
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
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop