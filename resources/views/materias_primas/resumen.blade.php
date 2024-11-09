@extends('adminlte::page')

@section('title', 'Materias primas cargadas')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Materias primas cargadas') }}
</h2>
@stop

@section('content')
<div class="p-12">
    <div class="mb-4">
        <a href="{{ route('lista.sdp.cargar') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th colspan="9">MATERIAS PRIMAS DIRECTAS</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th class="px-1">#</th>
                            <th class="px-1">CODIGO</th>
                            <th class="px-1">DESCRIPCION</th>
                            <th class="px-1">PROVEEDOR</th>
                            <th class="px-1">NUMERO DE FACTURA</th>
                            <th class="px-1">NUMERO DE ORDEN DE COMPRA</th>
                            <th class="px-1">PRECIO UNITARIO</th>
                            <th class="px-1">CANTIDAD</th>
                            <th class="px-1">ARTICULO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materiasPrimasDirectas as $index => $materiaPrimaDirecta)
                            <tr>
                                <td class="px-1">{{ $index + 1 }}</td>
                                <td class="px-1">{{ $materiaPrimaDirecta->codigo }}</td>
                                <td class="px-1">{{ $materiaPrimaDirecta->descripcion }}</td>
                                <td class="px-1">{{ $materiaPrimaDirecta->proveedor }}</td>
                                <td class="px-1">{{ $materiaPrimaDirecta->numero_factura }}</td>
                                <td class="px-1">{{ $materiaPrimaDirecta->numero_orden_compra }}</td>
                                <td class="px-1">{{ $materiaPrimaDirecta->precio_unit }}</td>
                                <td class="px-1">
                                    {{ $materiaPrimaDirecta->pivot->cantidad }}
                                </td>
                                <td class="px-1">{{ $materiaPrimaDirecta->pivot->articulo_descripcion }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <thead>
                        <tr>
                            <th colspan="9">MATERIAS PRIMAS INDIRECTAS</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th class="px-1">#</th>
                            <th class="px-1">CODIGO</th>
                            <th class="px-1">DESCRIPCION</th>
                            <th class="px-1">PROVEEDOR</th>
                            <th class="px-1">NUMERO DE FACTURA</th>
                            <th class="px-1">NUMERO DE ORDEN DE COMPRA</th>
                            <th class="px-1">PRECIO UNITARIO</th>
                            <th class="px-1">CANTIDAD</th>
                            <th class="px-1">ARTICULO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materiasPrimasIndirectas as $index => $materiaPrimaIndirecta)
                            <tr>
                                <td class="px-1">{{ $index + 1 }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->codigo }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->descripcion }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->proveedor }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->numero_factura }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->numero_orden_compra }}</td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->precio_unit }}</td>
                                <td class="px-1">
                                    {{ $materiaPrimaIndirecta->pivot->cantidad }}
                                </td>
                                <td class="px-1">{{ $materiaPrimaIndirecta->pivot->articulo_descripcion }}</td>
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
        .container {
            overflow-y: auto;
            max-height: 600px;
        }
        table {
            width: 100%;
        
        }
        thead tr th {
            background: #cacaca;
            color: #000000 !important;
            text-align: center;
            order: #000000 1px solid;
        }

        tbody tr td {
            background: #c5c5c6;
            color: #000000 !important;
            border: #000000 1px solid;
        }

        th, td {
            border: #000000 1px solid;
        }

        .card {
            background: #55555510;
            border-radius: 10px;
        }

        .card-body {
            background: #9c9a9a !important;
            color: #fff !important;
            border-radius: 8px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 8px;
        }
        
        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 87vh;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop