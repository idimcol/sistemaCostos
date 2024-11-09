@extends('adminlte::page')

@section('title', 'lista de tiempos')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('lista tiempos de producción del operario') }}
</h2>
@stop

@section('content')
<div class="container">
    <button id="printButton" class="no-print btn btn-info mb-4">
        <i class="fa-solid fa-print"></i> Imprimir Lista
    </button>
    <div class="card">
        <div class="card-body">
            <div class="mt-4" id="totales">
                <h2 class="text-center mb-4"><b>Totales por Servicio</b></h2>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Total Horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($totalesPorServicio as $servicio)
                            <tr>
                                <td>{{ $servicio->nombre_servicio }}</td>
                                <td>{{ $servicio->total_horas_servicio }} horas</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <h2>Total General del Operario</h2>
                <p>Total de horas trabajadas: {{ $totalGeneral }} horas</p>
            </div>
            <div class="border p-5 mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>numero de sdp</th>
                                <th class="text-left">nombre de sdp</th>
                                <th>horas trabajadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($totalesPorSdp as $sdp)
                                <tr>
                                    <td class="text-left">{{ $sdp->sdp_id }}</td>
                                    <td class="text-left">{{ $sdp->sdp_nombre }}</td>
                                    <td class="text-left">{{ $sdp->total_horas_formateado }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr class="table-primary uppercase text-center">
                        <th>#</th>
                        <th>Operario</th>
                        <th>Día</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Servicio</th>
                        <th>Horas</th>
                        <th>Total Horas</th>
                        <th>SDP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tiempos_produccion as $key => $group)
                        <tr>
                            <td colspan="11" class="font-bold">SDP {{ $key }} | Total de Horas: {{ $group['total_horas'] }}</td>
                        </tr>
                        @foreach ($group['items'] as $index => $tiempo)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tiempo->operativo->codigo }}</td>
                                <td>{{ $tiempo->dia }}</td>
                                <td>{{ $tiempo->mes }}</td>
                                <td>{{ $tiempo->año }}</td>
                                <td>{{ $tiempo->hora_inicio }}</td>
                                <td>{{ $tiempo->hora_fin }}</td>
                                <td>{{ $tiempo->servicio->nombre }}</td>
                                <td>{{ $tiempo->horas }}</td>
                                <td>{{ number_format($tiempo->valor_total_horas, 2, ',', '.') }}</td>
                                <td>{{ $tiempo->sdp->numero_sdp }}</td>
                            </tr>
                        @endforeach
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <style>
        @media print {
            
            * {
                background: none ;
                color: black ;
                box-shadow: none ;
            }

            body{
                margin: 0;
                padding: 0;
                background: #fff;
                background-color: white;
            }

            .container, .row, .col, .content_header {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            @page {
                size: landscape !important; /* Establecer la orientación en CSS también */
                margin: 0 !important;
            }
            
            /* Target specific elements to hide */
            .no-print {
                display: none ;
            }
        }
        th, td {
            text-align: center;
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
            
                    const SdpContent = document.querySelector(".sdp");
            
                    const opt = {
                        margin:       0.5,
                        filename:     'lista de tiempos.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2 },
                        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
                    };
            
                    window.print();
                });
            </script>
@stop