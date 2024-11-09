@extends('adminlte::page')

@section('title', 'home')

@section('content_header')
    
@stop

@section('content')
<div class="bg-white shadow-md rounded my-6">
    <table class="min-w-max w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Producto</th>
                <th class="py-3 px-6 text-left">Categoría</th>
                <th class="py-3 px-6 text-center">Stock Actual</th>
                <th class="py-3 px-6 text-center">Stock Mínimo</th>
                <th class="py-3 px-6 text-center">Proveedor</th>
                <th class="py-3 px-6 text-center">Acción</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach ($productosBajoMinimos as $producto)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $producto->producto_nombre }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span>{{ $producto->categoria }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">{{ $producto->stock_actual }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span>{{ $producto->stock_minimo }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span>{{ $producto->proveedor }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Reordenar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .card, .card-body {
        background: #bbbbbb !important;
        color: #000 !important;
    }

    .content, .content-header {
        background: #fff !important;
    }

    .content {
        height: 87vh;
    }

    input {
        background: #fff !important;
        color: #000 !important;
    }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop