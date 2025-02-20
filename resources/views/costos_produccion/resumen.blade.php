@extends('adminlte::page')

@section('title', 'Resumen de Costos de Producción')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Resumen de costos de producción de la SDP')}} {{ $sdp->numero_sdp }}
    </h2>
@stop

@section('content')
<div class="p-12">
    <div class="flex items-end justify-end mb-4 gap-5">
        <button id="printButton" class="no-print btn btn-success">
            <i class="fa-solid fa-print"></i>
        </button>
        <a href="{{ route('costos_produccion.show', $sdp->numero_sdp) }}" class="no-print btn btn-primary">Volver</a>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Operario</th>
                            <th>Horas</th>
                            <th>Mano de Obra Inirecta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($costosProduccion as $key => $detalle)
                            @php
                                [$servicio, $operario, $articulo] = explode('-', $key);
                            @endphp
                            <tr>
                                <td>{{ $servicio }}</td>
                                <td>{{ $operario }}</td>
                                <td>{{ $detalle['horas'] }}</td>
                                <td>{{ $detalle['mano_obra_directa'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <hr>
                    <tfoot>
                        <tr>
                            <th>Materias primas directas</th>
                            <th>Materias primas indirectas</th>
                            <th>Cif</th>
                            <th>Utilidad Bruta</th>
                            <th>Margen Bruto</th>
                        </tr>
                        <tr>
                            <td>
                                @foreach ($materiasPrimasDirectasSubtotales as $materiaPrimaDirecta)
                                    <p>
                                        <b>Codigo: </b>{{ $materiaPrimaDirecta->codigo }},
                                        <b>Descripcion: </b>{{ $materiaPrimaDirecta->descripcion }},
                                        <b>Proveedor: </b>{{ $materiaPrimaDirecta->pivot->proveedor }}, <br>
                                        <b>Fecha de compra: </b>{{ $materiaPrimaDirecta->pivot->fecha_compra }},
                                        <b>Cantidad: </b>{{ $materiaPrimaDirecta->pivot->cantidad }}, <br>
                                        <b>Precio Unitario</b>{{ number_format($materiaPrimaDirecta->precio_unit, 2, ',', '.') }}
                                    </p>
                                    <hr class="border border-primary border-3 opacity-75">
                                @endforeach
                                <hr class="border border-success border-3 opacity-50">
                                <b>Total</b> {{ number_format($totalDirectas, 2, ',', '.') }}
                            </td>
                            <td>
                                @foreach ($materiasPrimasIndirectasSubtotales as $materiaPrimaIndirecta)
                                    <p>
                                        <b>Codigo: </b>{{ $materiaPrimaIndirecta->codigo }},
                                        <b>Descripcion: </b>{{ $materiaPrimaIndirecta->descripcion }},
                                        <b>Proveedor: </b>{{ $materiaPrimaIndirecta->pivot->proveedor }}, <br>
                                        <b>Fecha de Compra: </b>{{ $materiaPrimaIndirecta->pivot->fecha_compra }},
                                        <b>Cantidad: </b>{{ $materiaPrimaIndirecta->pivot->cantidad }}, <br>
                                        <b>Precio unitario</b>{{ number_format($materiaPrimaIndirecta->precio_unit, 2, ',', '.') }}
                                    </p>
                                    <hr class="border border-primary border-3 opacity-75">
                                @endforeach
                                <hr class="border border-success border-3 opacity-50">
                                <b>Total</b> {{ number_format($totalIndirectas, 2, ',', '.') }}
                            </td>
                            <td>{{ number_format($totalCif, 2, ',', '.') }}</td>
                            <td>{{ number_format($utilidadBruta, 2, ',', '.') }}</td>
                            <td>{{ number_format($margenBruto, 2, ',', '.') }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .card {
            background: #fff !important;
            color: #000 !important;
        }
        h1{
            text-align: center;
        }

        .cont {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cards {
            display: flex;
            flex-direction: row;

            gap: 10px;
            padding: 8px;
        }
        .card-1, .card-2, .card-3, .card-4 {
            padding: 8px;
            border: #000 solid 1px;
            border-radius: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 90vh;
        }

        @media print {
            @page {
                size: landscape !important; /* Establecer la orientación en CSS también */
                margin: 0 !important;
            }
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
        document.getElementById('printButton').addEventListener('click', function () {
            // Ocultar el botón de impresión antes de generar el PDF
    
            const SdpContent = document.querySelector(".sdp");
    
            const opt = {
                margin:       0.5,
                filename:     'costos_sdp.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
    
            window.print();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
@stop