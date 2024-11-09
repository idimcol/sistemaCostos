@extends('adminlte::page')

@section('title', 'remision de ingreso')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('ver remisión de ingreso') }}
</h2>
@stop

@section('content')
<div class="container">
    <div class="flex justify-end mb-4 gap-5">
        <a href="{{ route('remision.ingreso') }}" class="btn btn-primary no-print">volver</a>
        <button id="printRemision" class="btn btn-primary no-print">
            <i class="fas fa-print"></i>
        </button>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="contenido">
                <div class="cabezera">
                    <div class="row">
                        <div class="col-4 text-center flex items-center justify-center mt-2">
                            <img src="{{ asset('images/logoID.png') }}" alt="IDIMCOL">
                        </div>
                        <div class="col-4 text-center flex items-center justify-center">
                            <h1><b>REMISIÓN DE INGRESO</b></h1>
                        </div>
                        <div class="col-4 text-justify flex  items-center justify-center">
                            <p>
                                <strong>código: </strong> F-PD-003<br>
                                <strong>versión: </strong> 03<br>
                                <strong>fecha de creación: </strong> {{ $remisionIngreso->created_at->format('d-m-y') }}<br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="inform">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>código</td>
                                <td>{{ $remisionIngreso->codigo }}</td>
                            </tr>
                            <tr>
                                <td>cliente / proveedor</td>
                                <td>
                                    {{ $remisionIngreso->proveedor->nombre ?? '' }}
                                    {{ $remisionIngreso->cliente->nombre ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>fecha de ingreso a planta</td>
                                <td>{{ $remisionIngreso->fecha_ingreso }}</td>
                            </tr>
                            <tr>
                                <td>SDP</td>
                                <td>{{ $remisionIngreso->sdp_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="desc">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="table-secondary">
                                <th>ITEM</th>
                                <th>DESCRIPCION</th>
                                <th>CANTIDAD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $items as $index => $item )
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->descripcion }}</td>
                                <td>{{ $item->pivot->cantidad }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-12 border-b mb-4">
                    <p>
                        <strong>OBSERVACIONES: </strong> {{ $remisionIngreso->observaciones }}
                    </p>
                </div>
                <div class="footer">
                    <div class="row">
                        <div class="col-6 border">
                            <p>
                                <strong>ENTREGADO POR: </strong>{{ $remisionIngreso->despacho }}
                            </p>
                        </div>
                        <div class="col-6 border">
                            <p>
                                <strong>RECIBIDO POR: </strong>{{ $remisionIngreso->recibido }} <br>
                                <strong>Departamento: </strong>{{ $remisionIngreso->departamento }}
                            </p>
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
    <style>
        .inform, .desc, .footer {
            width: 100% !important;
        }
        .contenido {
            border: #bebdbd 1px solid;
            padding: 10px;
            width: 100%
        }
        .cabezera {
            background-color: #91d3fae4;
            border: none;
            height: 100px;
            text-align: center;
        }
        th, td, p, h1, h2, strong {
            text-transform: capitalize;
        }
        img {
            width: 90px;
            height: 90px;
        }

        @media print {

            img {
                border: none;
                background: none;
            }

            @page {
                size: auto !important; /* Establecer la orientación en CSS también */
                margin: 0 !important;
            }
            
            /* Target specific elements to hide */
            .no-print {
                display: none ;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.getElementById('printRemision').addEventListener('click', function () {
            // Ocultar el botón de impresión antes de generar el PDF
    
            const SdpContent = document.querySelector(".contenido");
    
            const opt = {
                margin:       0.5,
                filename:     'remision de ingreso.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'vertical' }
            };
    
            window.print();
        });
    </script>
@stop