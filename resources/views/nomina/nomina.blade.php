@extends('adminlte::page')

@section('title', 'desprendible')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('desprendible de nomina') }}
    </h2>
@stop

@section('content')
<div class="containe">
    <a href="{{ route('nomina.show', ['paquete' => $nomina->paqueteNomina->id]) }}" id="volver" class="btn btn-primary">volver</a>
</div>
<div class="flex items-end justify-end mb-4">
    <button  id="savedesprendibleBtn" onclick="window.print()"  class="btn btn-info">
        Imprimir desprendible
    </button>
</div>
    <div class="py-12">
        <body class="desprendibles" id="desprendible">
            <div class="max-w-4xl mx-auto bg-white shadow-lg">
                <div class="flex justify-between items-center  border-gray-200 p-4">
                    <div class="flex items-center">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsVpDdEZpcQyL21RirrUW88r-ATjStR6UG7X4GjWd2PQ&s" alt="IDIMCOL Logo" class="w-16 h-16 mr-4">
                        <div>
                            <h1 class="text-2xl font-bold">IDIMCOL S.A.S.</h1>
                            <p class="text-sm">CARRERA 12 NUMERO 21-35</p>
                            <p class="text-sm">BUCARAMANGA - COLOMBIA</p>
                            <p class="text-sm">NIT. 900.976834-9</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-semibold">DESPRENDIBLE</h2>
                        <p>PAGO DE NÓMINA</p>
                        <p class="font-bold">{{ $meses[$nomina->paqueteNomina->mes] }}/{{ $nomina->paqueteNomina->año }}</p>
                    </div>
                </div>
    <hr>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">NOMINAL GENERAL</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="uppercase"><span class="font-semibold">APELLIDOS:</span> {{ $nomina->trabajador->apellido }}</p>
                            <p class="uppercase"><span class="font-semibold">NOMBRES:</span> {{ $nomina->trabajador->nombre }}</p>
                            <p><span class="font-semibold">IDENTIFICACIÓN:</span> {{ $nomina->trabajador->numero_identificacion }}</p>
                        </div>
                        <div>
                            <p class="uppercase"><span class="font-semibold">CENTRO DE OPERACIÓN:</span> {{ $nomina->trabajador->departamentos }}</p>
                            <p><span class="font-semibold">CARGO:</span> {{ $nomina->trabajador->cargo }}</p>
                            <p><span class="font-semibold">FECHA DE INGRESO:</span> {{ $nomina->trabajador->fecha_ingreso }}</p>
                        </div>
                    </div>
                    <p class="mb-2"><span class="font-semibold">SUELDO BASE:</span> {{ number_format($nomina->trabajador->sueldos->first()->sueldo, 2, ',', '.') }}</p>
                    <p class="mb-2"><span class="font-semibold">PERIODO DE PAGO:</span> {{ $nomina->periodo_pago }}</p>
    
                    <div class="flex justify-between mt-4">
                        <div class="w-1/2 pr-2">
                            <h4 class="font-semibold mb-2">DEVENGADOS</h4>
                            <table class="w-full">
                                <tr class="border-b">
                                    <th class="text-left">CONCEPTO</th>
                                    <th class="text-right">CANT.</th>
                                    <th class="text-right">VALOR</th>
                                </tr>
                                <tr>
                                    <td>Sueldo Básico</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_trabajados, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Auxilio de transporte</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->auxilio_transporte, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>bonificacion-rodamiento</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ number_format($nomina->bonificacion_auxilio, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>incapacidad</td>
                                    <td class="text-right">{{ $nomina->dias->dias_incapacidad }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_incapacidad, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>vacaciones</td>
                                    <td class="text-right">{{ $nomina->dias->dias_vacaciones }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_vacaciones, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>otra remuneracion/Liquidación</td>
                                    <td class="text-right">{{ $nomina->dias->dias_remunerados }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_remunerados, 2, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="w-1/2 pl-2">
                            <h4 class="font-semibold mb-2">DESCUENTOS</h4>
                            <table class="w-full">
                                <tr class="border-b">
                                    <th class="text-left">CONCEPTO</th>
                                    <th class="text-right">CANT.</th>
                                    <th class="text-right">VALOR</th>
                                </tr>
                                <tr>
                                    <td>Salud</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->salud, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Pensión</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->pension, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Libranza salarial</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ number_format($nomina->celular, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>aticipo/prestamo</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ number_format($nomina->anticipo, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>licencia/sanción</td>
                                    <td class="text-right">{{ $nomina->dias->dias_no_remunerados }}</td>
                                    <td class="text-right">{{ number_format($nomina->suspencion, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>otro</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                </tr>
                            </table>
                        </div>
                    </div>
    
                    <div class="flex justify-between mt-4">
                        <p><span class="font-semibold">Total devengados:</span> {{ number_format($nomina->total_devengado, 2, ',', '.') }}</p>
                        <p><span class="font-semibold">Total descuentos:</span> {{ number_format($nomina->total_deducido, 2, ',', '.') }}</p>
                    </div>
    
                    <p class="text-right mt-4 font-bold">TOTAL CANCELADO: {{ number_format($nomina->total_a_pagar, 2, ',', '.') }}</p>
                    <div class="">
                        <div class="">
                            <label for="" class="form-label">Pago en efectivo</label>
                            <input type="checkbox">
                        </div>
                        <div class="">
                            <label for="" class="form-label">Pago por transaccion</label>
                            <input type="checkbox">
                        </div>
                        <div class="">
                            <label for="" class="form-label">Pago por cheque</label>
                            <input type="checkbox">
                        </div>
                    </div>
    
                    <div class="mt-8 pt-8 flex flex-col items-center justify-center">
                        <input type="text" class="firma">
                        <p class="text-center">FIRMA Y C.C. DEL BENEFICIARIO</p>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto bg-white shadow-lg">
                <div class="flex justify-between items-center border-gray-200 p-4">
                    <div class="flex items-center">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsVpDdEZpcQyL21RirrUW88r-ATjStR6UG7X4GjWd2PQ&s" alt="IDIMCOL Logo" class="w-16 h-16 mr-4">
                        <div>
                            <h1 class="text-2xl font-bold">IDIMCOL S.A.S.</h1>
                            <p class="text-sm">CARRERA 12 NUMERO 21-35</p>
                            <p class="text-sm">BUCARAMANGA - COLOMBIA</p>
                            <p class="text-sm">NIT. 900.976834-9</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-semibold">DESPRENDIBLE</h2>
                        <p>PAGO DE NÓMINA</p>
                        <p class="font-bold">{{ $meses[$nomina->paqueteNomina->mes] }}/{{ $nomina->paqueteNomina->año }}</p>
                    </div>
                </div>
    <hr>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">NOMINAL GENERAL</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="uppercase"><span class="font-semibold">APELLIDOS:</span> {{ $nomina->trabajador->apellido }}</p>
                            <p class="uppercase"><span class="font-semibold">NOMBRES:</span> {{ $nomina->trabajador->nombre }}</p>
                            <p><span class="font-semibold">IDENTIFICACIÓN:</span> {{ $nomina->trabajador->numero_identificacion }}</p>
                        </div>
                        <div>
                            <p class="uppercase"><span class="font-semibold">CENTRO DE OPERACIÓN:</span> {{ $nomina->trabajador->departamentos }}</p>
                            <p><span class="font-semibold">CARGO:</span> {{ $nomina->trabajador->cargo }}</p>
                            <p><span class="font-semibold">FECHA DE INGRESO:</span> {{ $nomina->trabajador->fecha_ingreso }}</p>
                        </div>
                    </div>
                    <p class="mb-2"><span class="font-semibold">SUELDO BASE:</span> {{ number_format($nomina->trabajador->sueldos->first()->sueldo, 2, ',', '.') }}</p>
                    <p class="mb-2"><span class="font-semibold">PERIODO DE PAGO:</span> {{ $nomina->periodo_pago }}</p>
    
                    <div class="flex justify-between mt-4">
                        <div class="w-1/2 pr-2">
                            <h4 class="font-semibold mb-2">DEVENGADOS</h4>
                            <table class="w-full">
                                <tr class="border-b">
                                    <th class="text-left">CONCEPTO</th>
                                    <th class="text-right">CANT.</th>
                                    <th class="text-right">VALOR</th>
                                </tr>
                                <tr>
                                    <td>Sueldo Básico</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_trabajados, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Auxilio de transporte</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->auxilio_transporte, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>bonificacion-rodamiento</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ number_format($nomina->bonificacion_auxilio, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>incapacidad</td>
                                    <td class="text-right">{{ $nomina->dias->dias_incapacidad }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_incapacidad, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>vacaciones</td>
                                    <td class="text-right">{{ $nomina->dias->dias_vacaciones }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_vacaciones, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>otra remuneracion</td>
                                    <td class="text-right">{{ $nomina->dias->dias_remunerados }}</td>
                                    <td class="text-right">{{ number_format($nomina->devengado_remunerados, 2, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="w-1/2 pl-2">
                            <h4 class="font-semibold mb-2">DESCUENTOS</h4>
                            <table class="w-full">
                                <tr class="border-b">
                                    <th class="text-left">CONCEPTO</th>
                                    <th class="text-right">CANT.</th>
                                    <th class="text-right">VALOR</th>
                                </tr>
                                <tr>
                                    <td>Salud</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->salud, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Pensión</td>
                                    <td class="text-right">{{ $nomina->dias->dias_trabajados }}</td>
                                    <td class="text-right">{{ number_format($nomina->pension, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Libranza salarial</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ number_format($nomina->celular, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Aticipo/prestamo</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ number_format($nomina->anticipo, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>licencia/sanción</td>
                                    <td class="text-right">{{ $nomina->dias->dias_no_remunerados }}</td>
                                    <td class="text-right">{{ number_format($nomina->suspencion, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>otro</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">0</td>
                                </tr>
                            </table>
                        </div>
                    </div>
    
                    <div class="flex justify-between mt-4">
                        <p><span class="font-semibold">Total devengados:</span> {{ number_format($nomina->total_devengado, 2, ',', '.') }}</p>
                        <p><span class="font-semibold">Total descuentos:</span> {{ number_format($nomina->total_deducido, 2, ',', '.') }}</p>
                    </div>
    
                    <p class="text-right mt-4 font-bold">TOTAL CANCELADO: {{ number_format($nomina->total_a_pagar, 2, ',', '.') }}</p>
                    <div class="">
                        <div class="">
                            <label for="" class="form-label">Pago en efectivo</label>
                            <input type="checkbox" name="" id="">
                        </div>
                        <div class="">
                            <label for="" class="form-label">Pago por transaccion</label>
                            <input type="checkbox" name="" id="">
                        </div>
                        <div class="">
                            <label for="" class="form-label">Pago por cheque</label>
                            <input type="checkbox">
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </div>
@stop

@section('css')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        table tbody tr td {
            text-transform: capitalize;
        }

        .desprendibles {
            border-radius: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        h2 {
            font-size: 18px;
            text-transform: uppercase;
        }

        input.firma{
            border: none;
            border-bottom: 1px solid #000 !important; 
        }

        @media print {
        /* Ajusta el tamaño de la página para que ambos desprendibles quepan en una sola hoja */
            @page {
                size: A4 portrait;
                margin: -28mm;
            }

            /* Asegúrate de que cada desprendible tenga la mitad del tamaño de la página */
            .desprendible {
                page-break-inside: avoid;
                width: 100%;
                margin: 0 auto; /* Centra el contenido */
                padding: 0;
                box-sizing: border-box;/* Espacio entre los desprendibles */
            }

            /* Opcional: Esconde el botón de imprimir y el enlace de volver cuando se imprima */
            #volver, #savedesprendibleBtn {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .flex, .border-b-2, .border-gray-200, .p-4 {
                margin: 0;
                padding: 0;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.getElementById("savedesprendibleBtn").addEventListener("click", function() {
                // Selecciona el contenedor de la remisión
                const desprendibleContent = document.querySelector("#desprendible");

                // Opciones para el PDF
                const opt = {
                    margin:       0,
                    filename:     'desprendible.pdf',
                    image:        { type: 'jpg', quality: 0.98 },
                    html2canvas:  { scale: 1 },
                    jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                };

                window.print();

            });
    </script>
@stop