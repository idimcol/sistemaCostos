@extends('adminlte::page')

@section('title', 'sdp')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('SDP')}}
    </h2>
@stop
@section('content')
<div class="buttons">
    <a href="{{ route('sdp.paquetes') }}" id="volver" class="no-print btn btn-primary mb-4">volver</a>
    <button id="printButton" class="no-print bg-blue-700 hover:bg-blue-900 text-white  py-2 px-4 rounded mb-4">
        <i class="fa-solid fa-print"></i>
    </button>
</div>
<div class="">
    <div class="">
        <div class="">
            <div class="sdp max-w-4x1 mx-auto bg-white p-8  mb-8" id="SdpContent">
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
                        <h1 class="text-2xl font-bold mb-2">SOLICITUD DE PRODUCCIÓN</h1>
                        <p class="text-sm p"><b>Nro. S.D.P: {{ $sdp->numero_sdp }}</b></p>
                    </div>
                    <div class=" contentt text-center border border-black rounded p-3">
                        <p class="text-sm p">Fecha: {{ $sdp->created_at->format('d-m-y') }}</p>
                        
                        <p class="text-sm p">Hora: {{ $sdp->created_at->timezone('America/Bogota')->format('h:i A') }}</p>
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
                            <th class="border p-2">Código</th>
                            <th class="border p-2">Descripción</th>
                            <th class="border p-2">Material</th>
                            <th class="border p-2">Fecha de Facturacion</th>
                            <th class="border p-2">Fecha de Despacho Producción</th>
                            <th class="border p-2">Plano</th>
                            <th class="border p-2">Cantidad</th>
                            <th class="border p-2">Precio</th>
                            <th class="border p-2">Sub-Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sdp->articulos as $articulo)
                            <tr class="border border-black odd:bg-white even:bg-gray-200">
                                <td class="border p-1 text-center">{{ $articulo->codigo }}</td>
                                <td class="border p-1 text-left">{{ $articulo->descripcion }}</td>
                                <td class="border p-1 text-left" text-right">{{ $articulo->material }}</td>
                                <td class="border p-1 text-left">{{ $sdp->fecha_despacho_comercial }}</td>
                                <td class="border p-1 text-left">{{ $sdp->fecha_despacho_produccion }}</td>
                                <td class="border p-1 text-left">{{ $articulo->plano }}</td>
                                <td class="border p-1  text-center">{{ $articulo->pivot->cantidad }}</td>
                                <td class="border p-1  text-right">{{ number_format($articulo->pivot->precio, 2, ',', '.') }}</td>
                                <td class="border p-1  text-right">{{ number_format($articulo->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 border border-black">
                            <td colspan="8" class="border p-1 text-right font-semibold">Total: </td>
                            <td class="border p-1 text-right font-semibold">
                                {{ number_format($total, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            
                <!-- Observaciones -->
                <div class="border border-gray-300 p-4 mb-6">
                    <h3 class="font-semibold mb-2">OBSERVACIONES:</h3>
                    <p class="break-words">{{ $sdp->observaciones }}</p> <!-- Se añade break-words para ajustar contenido largo -->
                    <h3 class="font-semibold mt-2 mb-2">REQUISITOS DEL CLIENTE:</h3>
                    <p class="break-words">{{ $sdp->requisitos_cliente }}</p> <!-- Igual para requisitos -->
                </div>
            
                <!-- Firmas -->
                <div class="flex flex-col md:flex-row justify-between mt-12 space-y-4 md:space-y-0">
                    <div class="text-center">
                        <div class="border-t border-gray-400 w-48 mx-auto"></div>
                        <p class="mt-1">D.COMERCIAL</p>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-400 w-48 mx-auto"></div>
                        <p class="mt-1">D.INGENIERIA</p>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-400 w-48 mx-auto"></div>
                        <p class="mt-1">D.PRODUCCION</p>
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
        table, thead, tr, th, td {
            border: 1px #000 solid;
        }

        .buttons {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            gap: 10px;
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

        .print button {
            width: 70px;
            height: 70px;
            text-align: center
        }

        .print button i {
            font-size: 25px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 90vh;
        }

        .container {
            padding: 10px;
        }

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
            .fondo-negro, .header, .sidebar, .footer, .no-print {
                display: none ;
            }

            .sdp{
                display: block !important;
            }
        }

        .center{
            display: flex;
            flex-direction: column;
            text-align: center;
            transform: translateX(-150px);
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
                filename:     'sdp.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
    
            window.print();
        });
    </script>
@stop