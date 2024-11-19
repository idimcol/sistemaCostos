@extends('adminlte::page')

@section('title', 'Formato Orden Compra')

@section('content_header')
@stop

@section('content')
    <div class="py-12">
        <div class="container">
            <div class=" no-print">
                <a href="{{ route('Ordencompras.index') }}" class="btn btn-primary mt-4">volver</a>
            </div>
            <div class="flex items-end justify-end no-print">
                <button id="printButton" class="no-print btn btn-info mt-4">
                    <i class="fa-solid fa-print"></i> Imprimir
                </button>
            </div>
            <div class="OrdenCompra">
                <div class="card">
                    <div class="card-body">
                        <div class="row gap">
                            <div class="col-4 border  text-center flex items-center justify-center">
                                <img class="logo" src="{{ asset('images/logo.png') }}" alt="IDIMCOL">
                            </div>
                            <div class="col-4 border  text-center flex items-center justify-center">
                                <h1 class="text-center"><b>ORDEN DE COMPRA</b></h1><br>
                            </div>
                            <div class="col-4 border p-2">
                                <h1><b>Numero:</b> {{ $ordenCompra->numero }}</h1>
                                <br>
                                <h1><b>Fecha de entrega:</b> {{ $ordenCompra->fecha_orden }}</h1>
                            </div>
                        </div>
                        <div class="col-12 mt-4 border p-2">
                            <h1 class="uppercase"><b>Proveedor:</b> {{ $ordenCompra->proveedor->nombre }}</h1>
                        </div> 
                        <div class="card-body">
                            <table class="table table-striped ">
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
                                    @foreach ($itemsConSubtotales as $item)
                                        <tr>
                                            <td>{{ $item->codigo }}</td>
                                            <td>{{ $item->descripcion }}</td>
                                            <td>{{ $item->pivot->cantidad }}</td>
                                            <td>{{ number_format($item->pivot->precio, 2, ',', '.') }}</td>
                                            <td>{{ number_format($total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <div class="row mt-4 p-2 gap">
                                <div class="col-4 border text-justify flex items-center">
                                    <h1 class=" text-center"><b>Elaborado por: </b>{{ $ordenCompra->elaboracion }}</h1>
                                </div>
                                <div class="col-4 border text-justify flex items-center">
                                    <h1><b>Autorizado por: </b>{{ $ordenCompra->autorizacion }}</h1>
                                </div>
                                <div class="col-4 border">
                                    <h1><b>SUB-TOTAL: </b>{{ number_format($total, 2, ',', '.') }}</h1>
                                    <h1><b>IVA: </b>{{ number_format($iva, 2, ',', '.') }}</h1>
                                    <h1><b>TOTAL: </b>$ {{ number_format($Total_pagar, 2, ',', '.') }}</h1>
                                </div>
                            </div>
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

        @media print {

            @page {
                size: landscape !important; /* Establecer la orientación en CSS también */
                margin: 0 !important;
            }
            
            /* Target specific elements to hide */
            .no-print {
                display: none ;
            }

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
    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            // Ocultar el botón de impresión antes de generar el PDF
    
            const SdpContent = document.querySelector(".OrdenCompra");
    
            const opt = {
                margin:       0,
                filename:     'OrdenCompra.pdf',
                image:        { type: 'jpeg', quality: 5 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'vertical' }
            };
    
            window.print();
        });
    </script>
@stop