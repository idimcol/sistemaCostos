@extends('adminlte::page')

@section('title', 'ALMACEN')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase mb-10">
    {{ __('Almacen') }}
</h2>
@stop

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <div class="search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" placeholder="Buscar productos" class="pl-8 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <a href="{{ route('productos.create') }}" class="text-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600">
            <i class="fa-solid fa-box"></i> Agregar Producto
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Inventario</h2>
                <p class="text-sm text-gray-500">Resumen de productos en stock</p>
            </div>
            <div class="p-4">
                <p class="text-3xl font-bold">1,234</p>
            </div>
            <div class="p-4 border-t border-gray-200">
                <a href="{{ route('inventario.index') }}" class="text-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:text-gray-800">
                    <i class="fa-solid fa-box"></i> Ver Inventario
                </a>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Categorias</h2>
                <p class="text-sm text-gray-500">Categorias de productos</p>
            </div>
            <div class="p-4">
                <p class="text-3xl font-bold">42</p>
            </div>
            <div class="p-4 border-t border-gray-200">
                <a href="{{ route('categorias.index') }}" class="tex-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:text-gray-800">
                    <i class="fa-solid fa-layer-group"></i> Ver Categorias
                </a>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Movimientos</h2>
                <p class="text-sm text-gray-500">Movimientos programados para hoy</p>
            </div>
            <div class="p-4">
                <p class="text-3xl font-bold">7</p>
            </div>
            <div class="p-4 border-t border-gray-200">
                <a href="" class=" text-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:text-gray-800">
                    <i class="fa-solid fa-boxes-packing"></i> Ver Movimientos
                </a>
            </div>
        </div>
    </div>
    <div class="mt-10">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Productos</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>codigo</th>
                            <th>producto</th>
                            <th>descripcion</th>
                            <th>stock</th>
                            <th>precio unitario</th>
                            <th>ubicacion</th>
                            <th>proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
    <div class="flex items-end justify-end p-10">
        <a href="{{ route('AdministraciónInventario') }}" class="bg-yellow-600 hover:bg-yellow-400 px-3 py-2 rounded">volver</a>    
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/w3-css/4.1.0/w3.css" integrity="sha512-Ef5r/bdKQ7JAmVBbTgivSgg3RM+SLRjwU0cAgySwTSv4+jYcVeDukMp+9lZGWT78T4vCUxgT3g+E8t7uabwRuw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/lucide-static@0.244.0/font/lucide.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .search {
            background: #cbcaca;
            color: #000 !important;
            padding: 5px;
            border-radius: 5px;
            height: 40px;
            backdrop-filter: blur(0.4rem);
            -webkit-backdrop-filter: blur(0.4rem);
        }

        .search input {
            border: none !important;
            height: 30px;
            background: #fafafa2d;
            color: #000 !important;
            backdrop-filter: blur(0.4rem);
            -webkit-backdrop-filter: blur(0.4rem);
        }

        .content, .content-header {
            width: 100%;
            background: #fff !important;;
            color: #000;
        }

        .content {
            height: 120vh;
        }

        .card {
            background: #919191 !important;
            backdrop-filter: blur(0.4rem) !important;
            -webkit-backdrop-filter: blur(0.4rem) !important;
            color: #fff !important;
        }

        .card-header {
            background: #9b9b9b !important;
            backdrop-filter: blur(0.4rem) !important;
            -webkit-backdrop-filter: blur(0.4rem) !important;
            color: #413e3e !important;
        }

        .card-body {
            background: #9a9a9a !important;
            backdrop-filter: blur(0.4rem) !important;
            -webkit-backdrop-filter: blur(0.4rem) !important;
            color: #302d2d !important;
        }

        table thead tr th , table tbody tr td {
            background: #d2d1d1;
            backdrop-filter: blur(0.4rem);
            -webkit-backdrop-filter: blur(0.4rem);
            color: #292828 !important;
            border: 1px solid #fafafa10 !important;
        }
    </style>
@stop

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/SVG-Morpheus/0.3.2/svg-morpheus.js" integrity="sha512-iyriEH7X9+KePLXu2Yv+HDo9UPIr+OTiPvxG/HwUyLVHGE2yPAyg+eyq7xlodMPbEEfGyhpBzBEMznbQ0o1NjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop