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
                <h1 class="text-center">RESUMEN DE COSTOS DE PRODUCCIÓN DE LA SDP {{ $sdp->numero_sdp }}</h1>
                <h2>ANÁLISIS DE COSTOS</h2>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="card p-4 border rounded">
                        <h3>* Valor de venta</h3>
                        <strong>Artículos</strong>
                        @foreach ($articulosTiemposConSubtotales as $index => $articulo)
                            <p>
                                <strong>| {{ $index + 1 }} |</strong><br>
                                <strong>Descripción del artículo:</strong> {{ $articulo->descripcion }}<br>
                                <strong>Cantidad del artículo:</strong> {{ $articulo->pivot->cantidad }}<br>
                                <strong>Valor del artículo:</strong> {{ number_format($articulo->subtotal, 2, ',', '.') }}<br>
                            </p>
                        @endforeach
                        <strong>TOTAL:</strong> {{ number_format($total, 2, ',', '.') }}
                    </div>

                    <div class="card p-4 border rounded">
                        <h3>* Mano de obra directa</h3>
                        @foreach ($operariosConTiempos as $index => $item)
                            <p>
                                <strong>| {{ $index + 1 }} |</strong><br>
                                <strong>Nombre del operario:</strong> {{ $item['nombre'] }}<br>
                                <strong>Horas trabajadas:</strong> {{ $item['horas'] }}<br>
                                <strong>Valor de la mano de obra:</strong> {{ number_format($item['mano_obra_servicio'], 2, ',', '.') }}<br>
                            </p>
                        @endforeach
                        <strong>TOTAL:</strong> {{ number_format($totalManoObra, 2, ',', '.') }}
                    </div>
                    <div class="card p-4 border rounded">
                        <h3>* Materias primas</h3>
                        <strong>Directas</strong>
                        @foreach ($materiasPrimasDirectas as $index => $materiaDirecta)
                            <p>
                                <strong>| {{ $index + 1 }} |</strong><br>
                                <strong>Descripción:</strong> {{ $materiaDirecta->descripcion }}<br>
                                <strong>Cantidad:</strong> {{ $materiaDirecta->pivot->cantidad }}<br>
                                <strong>Precio unitario:</strong> {{ number_format($materiaDirecta->precio_unit, 2, ',', '.') }}<br>
                                <strong>Total:</strong> {{ number_format($totalDirectas, 2, ',', '.') }}<br>
                            </p>
                        @endforeach
                        <strong>Indirectas</strong>
                        @foreach ($materiasPrimasIndirectas as $index => $materiaIndirecta)
                            <p>
                                <strong>| {{ $index + 1 }} |</strong><br>
                                <strong>Descripción:</strong> {{ $materiaIndirecta->descripcion }}<br>
                                <strong>Cantidad:</strong> {{ $materiaIndirecta->pivot->cantidad }}<br>
                                <strong>Precio unitario:</strong> {{ number_format($materiaIndirecta->precio_unit, 2, ',', '.') }}<br>
                                <strong>Total:</strong> {{ number_format($totalIndirectas, 2, ',', '.') }}<br>
                            </p>
                        @endforeach
                    </div>

                    <div class="card p-4 border rounded">
                        <h3>* Costos indirectos de fábrica (CIF)</h3>
                        <strong>Gasto Operativo Indirecto (GOI):</strong> {{ number_format($GOI, 2, ',', '.') }}<br>
                        <strong>Mano de Obra Indirecta (MOI):</strong> {{ number_format($MOI, 2, ',', '.') }}<br>
                        <strong>Otros costos indirectos (CMI):</strong> {{ number_format($OCI, 2, ',', '.') }}<br>
                        <h3>* Total de horas de los operarios</h3>
                        horas: {{ number_format($totalHoras, 2, ',', '.') }}<br>
                        <h3>* Total CIF</h3>
                        CIF: {{ number_format($totalCIF, 2, ',', '.') }}<br>
                        <h2>Utilidad bruta</h2>
                        valor: {{ number_format($utilidadBruta, 2, ',', '.') }}<br>
                        <h2>Margen bruto</h2>
                        valor: {{ number_format($margenBruto, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
@stop