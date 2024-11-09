@extends('adminlte::page')

@section('title', 'sdp')

@section('content_header')
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Costos de producción de la sdp')}} {{ $sdp->numero_sdp }}
    </h2>
@stop

@section('content')
<div class="flex items-end justify-end mb-4 gap-5">
    <a href="{{ route('resumen.costos', $sdp->numero_sdp) }}" class="btn btn-info">ver resumen</a>
    <a href="{{ route('costos_produccion.index') }}" class="btn btn-primary">volver</a>
</div>
{{-- @if (!$tiemposProduccionCargados)
    <div class="alert alert-warning">
        Esta SDP no tiene tiempos de producción cargados.
    </div>
@endif --}}
<div class="p-12">
    <div class="sdp-content">
        <div class="sdp max-w-4x1 mx-auto bg-white p-8 shadow-lg mb-8" id="SdpContent">
            <!-- Encabezado -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsVpDdEZpcQyL21RirrUW88r-ATjStR6UG7X4GjWd2PQ&s" alt="IDIMCOL Logo" class="w-32 mb-2">
                    @foreach ($idimcols as $idimcol)
                        <p class="text-sm p"><b class="">{{ $idimcol->nombre }}</b></p>
                        <p class="text-sm p"><b>{{ $idimcol->direccion }}</b></p>
                        <p class="text-sm p">NIT: <b>{{ $idimcol->nit }}</b></p>
                        <p class="text-sm p">TEL: <b>{{ $idimcol->telefono }}</b></p>
                    @endforeach    
                </div>
                <div class="center">
                    <h1 class="text-2xl font-bold mb-2">COSTOS DE PRODUCCION</h1>
                    <p class="text-sm p"><b>Nro. S.D.P: {{ $sdp->numero_sdp }}</b></p>
                </div>
                <div class=" contentt text-center border border-black rounded p-3">
                    <p class="text-sm p">Fecha: {{ $sdp->created_at->format('d-m-y') }}</p>
                    
                    <p class="text-sm p">Hora: {{ $sdp->created_at->timezone('America/Bogota')->format('H:i:s A') }}</p>
                </div>
            </div>
        
            <!-- Información del cliente y vendedor -->
            <div class="border border-gray-800 p-4 mb-6 rounded cont">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Columna 1 -->
                    <div>
                        <p><span class="font-semibold">NIT/CI: </span> {{ $sdp->clientes->nit }}</p>
                        <p><span class="font-semibold">Cliente: </span> {{ $sdp->clientes->nombre }}</p>
                    </div>
                    <!-- Columna 2 -->
                    <div>
                        <p><span class="font-semibold">Vendedor: </span> {{ $sdp->vendedores->nombre }}</p>
                        <p><span class="font-semibold">Nombre: </span> {{ $sdp->nombre }}</p>
                    </div>
                    <!-- Columna 3 -->
                    <div>
                        <p><span class="font-semibold">O.C: </span> {{ $sdp->orden_compra }}</p>
                        <p><span class="font-semibold">M.C: </span>{{ $sdp->memoria_calculo }}</p>
                    </div>
                    <!-- Columna 4 -->
                    <div>
                        <p><span class="font-semibold">Fecha S.D.P: </span>{{ $sdp->created_at->format('d-m-y') }}</p>
                    </div>
                </div>
            </div>
            <!-- Tabla de productos -->
            <table id="sdp-table" class="w-full mb-6 border border-black rounded">
                <thead>
                    <tr class="bg-gray-400 border border-black">
                        <th class="border p-4">item</th>
                        <th class="border p-2">Código</th>
                        <th class="border p-1">Descripción</th>
                        <th class="border p-1">Material</th>
                        <th class="border p-1">Fecha Despacho Comercial</th>
                        <th class="border p-1">Fecha Despacho Producción</th>
                        <th class="border p-1">Plano</th>
                        <th class="border p-1">Cantidad</th>
                        <th class="border p-1">Precio</th>
                        <th class="border p-1">Sub-Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articulosConSubtotales as $index => $articulo)
                        <tr class="border border-black odd:bg-white even:bg-gray-200" data-index="{{ $index + 1 }}">
                            <td class="border p-1 text-center">{{ $index + 1 }}</td>
                            <td class="border p-1 text-center">{{ $articulo->codigo }}</td>
                            <td class="border p-1 text-left">{{ $articulo->descripcion }}</td>
                            <td class="border p-1 text-left">{{ $articulo->material }}</td>
                            <td class="border p-1 text-right">{{ $sdp->fecha_despacho_comercial }}</td>
                            <td class="border p-1 text-left">{{ $sdp->fecha_despacho_produccion }}</td>
                            <td class="border p-1 text-left">{{ $articulo->plano }}</td>
                            <td class="border p-1 text-center">{{ $articulo->pivot->cantidad }}</td>
                            <td class="border p-1 text-right">{{ number_format($articulo->pivot->precio, 2, ',', '.') }}</td>
                            <td class="border p-1 text-right">{{ number_format($articulo->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 border border-black">
                        <td colspan="9" class="border p-1 text-right font-semibold">Total: </td>
                        <td class="border p-1 text-right font-semibold">
                            {{ number_format($total, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        <!-- Tabla de análisis de costos -->
        <div class="border border-gray-300 p-4 mb-6">
                <table class="w-full mb-6 border border-black rounded">
                    <thead>
                        <tr class="bg-gray-400 border border-black">
                            <th class="border p-1" colspan="6">ANÁLISIS DE COSTOS</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="bg-gray-400 border border-black">
                            <th class="border p-1">Valor de Venta</th>
                            <th class="border p-1">Mano de Obra Directa (MOI)</th>
                            <th class="border p-1">Nomina</th>
                            <th class="border p-1">Materias Primas Directas (MPD)</th>
                            <th class="border p-1">Materias Primas Indirectas (MPI)</th>
                            <th class="border p-1">Costos Indirectos de Fábrica (CIF)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border border-black odd:bg-white even:bg-gray-200">

                            <td class="border px-1">
                                {{ number_format($totalTiempos, 2, ',', '.') }}
                                <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#articuloTiempos">
                                    Detalles
                                </button>
                            </td>
                            
                            <td class="border px-1">
                                {{ number_format($totalManoObraServicio, 2, ',', '.') }}
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mano_obra" data-index="{{ $index + 1 }}">Detalles</button>
                            </td>
                            <td class="border px-1">
                                {{ number_format($totalManoObra, 2, ',', '.') }}
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#operariosModal">
                                    Detalles
                                </button>
                            </td>
                            <td class="border px-1">
                                {{ number_format($totaldirectas, 2, ',', '.') }}
                                <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#materias_primas">
                                    Detalles
                                </button>
                            </td>
                            <td class="border px-1">
                                {{ number_format($totalIndeirectas, 2, ',', '.') }}
                                <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#materias_primas_indirectas">
                                    Detalles
                                </button>
                            </td>
                            <td class="border px-1">
                                {{ number_format($totalCIF, 2, ',', '.') }}
                                <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#CIF">
                                    Detalles
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    
                    <tfoot>
                        <tr class="bg-gray-400 border border-gray-400">
                            <td class="border p-1">Utilidad Bruta</td>
                            <td class="border p-1 text-right">{{ number_format($utilidadBruta, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-gray-400 border border-gray-400">
                            <td class="border p-1">MARGEN BRUTO</td>
                            <td class="border p-1 text-right">
                                {{ number_format($margenBruto, 2, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="articuloTiempos" tabindex="-1"  aria-labelledby="articuloTiemposLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="articuloTiemposLabel">ARTICULOS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Articulo</th>
                                    <th>subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articulosTiemposConSubtotales as $articuloTiempo)
                                    <tr>
                                        <td>{{ $articuloTiempo->descripcion }}</td>
                                        <td>{{ number_format($articuloTiempo->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>total</td>
                                    <td>{{ number_format($totalTiempos, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mano_obra" tabindex="-1"  aria-labelledby="mano_obraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content op">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="mano_obraLabel">MANO DE OBRA DIRECTA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Operario</th>
                                    <th>Horas Trabajadas</th>
                                    <th>Articulo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($operariosConTiempos as $operario)
                                    <tr>
                                        <td>{{ $operario['nombre'] }}</td>
                                        <td>{{ $operario['total_horas'] }}</td>
                                        <td>{{ $operario['articulo'] }}</td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="">total de mano de obra</td>
                                    <td>{{ number_format($totalManoObraServicio, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="materias_primas" tabindex="-1" aria-labelledby="materias_primasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="materias_primasLabel">MATERIAS PRIMAS DIRECTAS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>codigo</th>
                            <th>descripcion</th>
                            <th>cantidad</th>
                            <th>valor unitario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulo->materiasPrimasDirectas as $materiaDirecta)
                            <tr>
                                <td>{{ $materiaDirecta->codigo }}</td>
                                <td>{{ $materiaDirecta->descripcion }}</td>
                                <td>{{ $materiaDirecta->pivot->cantidad }}</td>
                                <td>{{ number_format($materiaDirecta->precio_unit, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">total</td>
                            <td>
                                
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="operariosModal" tabindex="-1" aria-labelledby="operariosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="operariosModalLabel">Operarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Operario</th>
                                    <th>Sueldo</th>
                                    <th>Horas Trabajadas</th>
                                    <th>Horas Mes</th>
                                    <th>Mano de Obra Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($operariosConTiempos as $operario)
                                    <tr>
                                        <td>{{ $operario['nombre'] }}</td>
                                        <td>{{ $operario['sueldo'] }}</td>
                                        
                                        
                                        <td>{{ number_format($operario['mano_obra_directa'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">total de mano de obra nomina</td>
                                    <td>{{ number_format($totalManoObra, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="materias_primas_indirectas" tabindex="-1" aria-labelledby="materias_primas_indirectasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="materias_primas_indirectasLabel">MATERIAS PRIMAS INDIRECTAS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>codigo</th>
                            <th>descripcion</th>
                            <th>cantidad</th>
                            <th>valor unitario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulo->materiasPrimasIndirectas as $materiaIndirecta)
                            <tr>
                                <td>{{ $materiaIndirecta->codigo }}</td>
                                <td>{{ $materiaIndirecta->descripcion }}</td>
                                <td>{{ $materiaIndirecta->pivot->cantidad }}</td>
                                <td>{{ number_format($materiaIndirecta->precio_unit, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">total</td>
                            <td>
                                
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CIF" tabindex="-1" aria-labelledby="CIFLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="CIFLabel">COSTOS INDIRECTOS DE FABRICA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>costos</th>
                            <th>total horas</th>
                            <th>total valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <ol>
                                    <li>
                                        MOI: {{ number_format($MOI, 2, ',', '.') }}
                                    </li>
                                    <li>
                                        GOI: {{ number_format($GOI, 2, ',', '.') }}
                                    </li>
                                    <li>
                                        OCI: {{ number_format($OCI, 2, ',', '.') }}
                                    </li>
                                </ol>
                            </td>
                            <td>
                                {{ $totalHoras }}
                            </td>
                            <td>
                                {{ number_format($totalCIF, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        table, thead, tr, th, td {
            border: 1px #000 solid;
        }

        th {
            text-align: center;
        }

        p b {
            font-size: 18px;
        }

        p.p {
            font-size: 18px;
        }

        .modal-content {
            width: 700px !important;
        }

        .op {
            width: 900px !important;
            position: relative;
        }
        
        .contentt {
            border: #000 1px solid;
            color: #000;
        }

        .cont {
            border: #000 1px solid;
            color: #000;
        }

        .cont div.row {
            max-height: 50px;
            display: flex;
            flex-direction: row;
            gap: 200px;
        }

        .print {
            margin-top: 20px;
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            justify-content: end; 
            gap: 10px;
        }

        input.form-control {
            border: #000 1px solid;
            text-align: center;
        }

        .print button {
            width: 70px;
            height: 70px;
            text-align: center
        }

        .print button i {
            font-size: 25px;
        }
        .center{
            display: flex;
            flex-direction: column;
            text-align: center;
            transform: translateX(-150px);
        }

        .content, .content-header {
            background: #fff !important;
        }

        .modal-header, .modal-body, .modal-footer {
            background: #dcdbdb !important;
        }

        .card-body {
            width: 100%;
            max-height: 300px !important;
            overflow-y: auto !important;
        }
    </style>
@stop

@section('js')
    <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
    ></script>

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
    ></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Escuchar el evento cuando se abre el modal
            var manoObraModal = document.getElementById('mano_obra');
            manoObraModal.addEventListener('show.bs.modal', function (event) {
                // Obtener el botón que activó el modal
                var button = event.relatedTarget; 
                // Extraer información de los atributos de datos
                var index = button.getAttribute('data-index'); 
                
                // Aquí puedes buscar el índice correcto basado en el artículo
                var articleInput = manoObraModal.querySelector('input[id="index_' + (index - 1) + '"]');
                if (articleInput) {
                    articleInput.value = index; // Asignar el índice al campo de entrada
                }
            });
        });
    </script>
@stop
