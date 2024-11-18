@extends('adminlte::page')

@section('title', 'Formato Orden Compra')

@section('content_header')
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <img class="logo" src="{{ asset('images/logo.png') }}" alt="IDIMCOL">
                    </div>
                    <div class="col-4">
                        <h1><b>ORDEN DE COMPRA</b></h1><br>
                    </div>
                    <div class="col-4">
                        <h1><b>Numero:</b> </h1>
                        <h1><b>Fecha de entrega:</b> </h1>
                    </div>
                </div>
                <div class="col-12">
                    <h1><b>Proveedor:</b></h1>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total $</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <h1><b>Elaborado por: </b></h1>
                        </div>
                        <div class="col-4">
                            <h1><b>Autorizado por: </b></h1>
                        </div>
                        <div class="col-4">
                            <h1><b>SUB-TOTAL: </b></h1>
                            <h1><b>IVA: </b></h1>
                            <h1><b>TOTAL: </b>$</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />

    <style>
        .logo {
            width: 60px;
            height: 60px;
        }
    </style>
@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
@stop