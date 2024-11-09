@extends('adminlte::page')

@section('title', 'nominas')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Nóminas para') }} {{ $paquete->mes }}/{{ $paquete->año }}
    </h2>
@stop

@section('content')
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div id="success-message" class="alert alert-success" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-row items-start justify-start">
            <button id="guardarCambios" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" 
                data-calculo-dias="{{ json_encode($nominas->map(function($nomina) {
                    return [
                        'id' => $nomina->id,
                        'total_dias_trabajados' => $nomina->dias->dias_trabajados - $nomina->dias->dias_incapacidad - $nomina->dias->dias_vacaciones - $nomina->dias->dias_remunerados
                    ];
                })) }}">
                Guardar cambios
            </button>
        </div>
        <div class="flex flex-row items-end justify-end gap-10">
            <a href="{{ route('nomina.index') }}" id="exportToExcel" class="btnE btn btn-primary">
                volver
            </a>
            <a href="{{ route('nominas.export', $paquete->id) }}"  id="exportButton" class="btnE btn btn-info">Exportar a Excel</a>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Agregar trabajador
        </button>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="w-full">
                <div class="flow-root">
                    <div class="mt-8 overflow-x-auto">
                        <div class="flex">
                            <input type="text" id="searchInput" placeholder="Buscar trabajadores..." class="border rounded p-2 mb-4">
                        </div>
                        <div class="inline-block min-w-full py-2 align-middle container">
                            <table id="nominas-table"  class="display">
                                <thead>
                                    <tr>
                                        <th colspan="30" class="px-4 py-2 border idi text-center">IDIMCOL S.A.S</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        @php
                                            // Crear objetos de fecha para el inicio y el fin del período
                                            $inicio = \Carbon\Carbon::create($paquete->año, $paquete->mes, 1)->format('d/m/Y');
                                            $fin = \Carbon\Carbon::create($paquete->año, $paquete->mes, 1)->addDays(29)->format('d/m/Y');
                                        @endphp
                                        <th colspan="8" class="px-4 py-2 border d text-center">MES</th>
                                        <th colspan="8" class="px-4 py-2 border d text-center">{{ $meses[$paquete->mes] }}</th>
                                        <th colspan="11" class="px-4 py-2 border d text-center"> Del {{ $inicio }} al {{ $fin }} </th>
                                        <th colspan="1" class="px-4 py-2 border d text-center"> año </th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th colspan="1" class="px-4 py-2 border  x  text-center">No</th>
                                        <th colspan="4" class="px-4 py-2 border x  text-center">datos personales</th>
                                        <th colspan="12" class="px-4 py-2 border x  text-center">devengados</th>
                                        <th colspan="1" class="px-4 py-2 border x text-center">total</th>
                                        <th colspan="7" class="px-4 py-2 border x text-center">deducciones</th>
                                        <th colspan="1" class="px-4 py-2 border x text-center">total</th>
                                        <th colspan="1" class="px-4 py-2 border x text-center">total</th>
                                        <th colspan="1" class="px-4 py-2 border x text-center">{{ $paquete->año }}</th>
                                        <th colspan="2" class="px-4 py-2 border x text-center">pago</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border c-1">#</th>

                                        <th class="px-4 py-2 border c-2">cédula</th>
                                        <th class="px-4 py-2 border c-3">apellidos</th>
                                        <th class="px-4 py-2 border c-4">nombres</th>
                                        <th class="px-4 py-2 border c">cargo</th>

                                        <th class="px-4 py-2 border c">salario</th>

                                        <th class="px-4 py-2 border c">dias trabajados</th>
                                        <th class="px-4 py-2 border c">dias incapacidad</th>
                                        <th class="px-4 py-2 border c">dias vacaciones</th>
                                        <th class="px-4 py-2 border c">dias renumerados</th>
                                        <th class="px-4 py-2 border c">dias totales</th>

                                        <th class="px-4 py-2 border c">bonificacion// auxilio de rodamiento</th>
                                        <th class="px-4 py-2 border c">devengados dias trabajados</th>
                                        <th class="px-4 py-2 border c">devengados dias incapacidad</th>
                                        <th class="px-4 py-2 border c">devengados dias vacaciones</th>
                                        <th class="px-4 py-2 border c">devengados dias renumerados</th>
                                        <th class="px-4 py-2 border c">auxilio de transporte</th>
                                        <th class="px-4 py-2 border c">total devengados</th>

                                        <th class="px-4 py-2 border c">salud</th>

                                        <th class="px-4 py-2 border c">pensión</th>
                                        <th class="px-4 py-2 border c">libranza salarial</th>
                                        <th class="px-4 py-2 border c">anticipo</th>
                                        <th class="px-4 py-2 border c">dias no renumerados</th>
                                        <th class="px-4 py-2 border c">suspensión</th>
                                        <th class="px-4 py-2 border c">otro</th>

                                        <th class="px-4 py-2 border c">total deducido</th>

                                        <th class="px-4 py-2 border c">total a pagar</th>

                                        <th class="px-4 py-2 border c">area de trabajo</th>

                                        <th class="px-4 py-2 border c">desde</th>
                                        <th class="px-4 py-2 border c">a</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="trabajadoresTable">
                                    @foreach($nominas as $index => $nomina)
                                        <tr data-nomina-id="{{ $nomina->id }}" class="text-gray-950">
                                            <td class="px-4 py-2 border c-1 ">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2 border c-2 ">{{ $nomina->trabajador->numero_identificacion }}</td>
                                            <td class="px-4 py-2 border c-3 ">{{ $nomina->trabajador->apellido }}</td>
                                            <td class="px-4 py-2 border c-4 ">{{ $nomina->trabajador->nombre }}</td>
                                            <td class="px-4 py-2 border c">{{ $nomina->trabajador->cargo }}</td>
                                            <td class="px-4 py-2 border c">{{ number_format($nomina->trabajador->sueldos->first()->sueldo, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="dias_trabajados" data-nomina-id="{{ $nomina->id }}" >{{ $nomina->dias->dias_trabajados }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" id="dias_incapacidad" data-field="dias_incapacidad" data-nomina-id="{{ $nomina->id }}">{{ $nomina->dias->dias_incapacidad }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" id="dias_vacaciones" data-field="dias_vacaciones" data-nomina-id="{{ $nomina->id }}">{{ $nomina->dias->dias_vacaciones }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" id="dias_remunerados" data-field="dias_remunerados" data-nomina-id="{{ $nomina->id }}">{{ $nomina->dias->dias_remunerados }}</td>
                                            <td class="px-4 py-2 border c">{{ $nomina->total_dias }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="bonificacion_auxilio" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->bonificacion_auxilio, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="devengado_trabajados" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->devengado_trabajados, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="devengado_incapacidad" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->devengado_incapacidad, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="devengado_vacaciones" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->devengado_vacaciones, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="devengado_remunerados" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->devengado_remunerados, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="auxilio_transporte" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->auxilio_transporte, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="total_devengado" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->total_devengado, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="salud" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->salud, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="pension" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->pension, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="celular" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->celular, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="anticipo" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->anticipo, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="dias_no_remunerados" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->dias->dias_no_remunerados, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="suspencion" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->suspencion, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="otro" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->otro, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="total_deducido" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->total_deducido, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c" data-field="total_a_pagar" data-nomina-id="{{ $nomina->id }}">{{ number_format($nomina->total_a_pagar, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 border c">{{ $nomina->trabajador->departamentos }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="desde" data-nomina-id="{{ $nomina->id }}">{{ $nomina->desde }}</td>
                                            <td class="px-4 py-2 border c" contenteditable="true" data-field="a" data-nomina-id="{{ $nomina->id }}">{{ $nomina->a }}</td>     
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cont mb-4">
    <div class="desprendible">
        @foreach($nominas as $nomina)
            <a href="{{ route('nomina.desprendible', $nomina->id) }}" class="px-4 py-2 ad text-white hover:text-blue-400">
                {{ $nomina->trabajador->nombre }}
                {{ $nomina->trabajador->apellido }}
            </a>
        @endforeach
    </div>
</div>
<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="w-full">
                <div class="flow-root">
                    <div class="mt-8 overflow-x-auto">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <table class="totales">
                                <thead>
                                    <tr class="">
                                        <th colspan="22" class="px-4 py-2 border text-center">totales</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="">
                                        <th class="px-4 py-2 border">salario</th>
                                        <th class="px-4 py-2 border">dias trabajados</th>
                                        <th class="px-4 py-2 border">dias incapacidad</th>
                                        <th class="px-4 py-2 border"> dias vacaviones</th>
                                        <th class="px-4 py-2 border">dias remunerados</th>
                                        <th class="px-4 py-2 border">dias totales</th>
                                        <th class="px-4 py-2 border">bonificacion auxilo de rodamiento</th>
                                        <th class="px-4 py-2 border">devengado dias trabajados</th>
                                        <th class="px-4 py-2 border">devengado dias incapacidad</th>
                                        <th class="px-4 py-2 border">devengado dias vacaciones</th>
                                        <th class="px-4 py-2 border">devengado dias remunerados</th>
                                        <th class="px-4 py-2 border">auxilio de transporte</th>
                                        <th class="px-4 py-2 border">total devengados</th>
                                        <th class="px-4 py-2 border">salud</th>
                                        <th class="px-4 py-2 border">pensión</th>
                                        <th class="px-4 py-2 border">libranza salarial</th>
                                        <th class="px-4 py-2 border">anticipo</th>
                                        <th class="px-4 py-2 border">dias no remunerados</th>
                                        <th class="px-4 py-2 border">suspensión</th>
                                        <th class="px-4 py-2 border">otro</th>
                                        <th class="px-4 py-2 border">total deducido</th>
                                        <th class="px-4 py-2 border">total a pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="px-4 py-2 border">{{ number_format($totalSueldo, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ $total_dias_trabajados }}</td>
                                        <td class="px-4 py-2 border">{{  $total_dias_incapacidad }}</td>
                                        <td class="px-4 py-2 border">{{ $total_dias_vacaciones }}</td>
                                        <td class="px-4 py-2 border">{{ $total_dias_remunerados }}</td>
                                        <td class="px-4 py-2 border">{{ $total_dias }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_bonificacion, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_D_dias_trabajados, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_D_dias_incapacidad, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_D_dias_vacaciones, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_D_dias_remunerados, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_auxilio, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_devengado, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_pencion, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_salud, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_celular, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_anticipo, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_dias_no_remunerados, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_suspencion, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_otro, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_deducido, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($total_a_pagar, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    
                                    <tr>
                                        <td class="border border-gray-300 p-2 text-right" colspan="21">TOTAL PCC</td>
                                        <td class="border border-gray-300 p-2 text-right">{{ number_format($total_a_pagar_pcc, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 text-right" colspan="21">TOTAL ADMON</td>
                                        <td class="border border-gray-300 p-2 text-right">{{ number_format($total_a_pagar_admon, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 text-right" colspan="21">TOTAL SOCIOS</td>
                                        <td class="border border-gray-300 p-2 text-right">{{ number_format($total_a_pagar_socios, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-gray-300">
                                        <td class="border border-gray-300 p-2 text-right font-bold" colspan="21">SUBTOTAL</td>
                                        <td class="border border-gray-300 p-2 text-right font-bold">{{ number_format($subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('nomina.addWorker', $paquete->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="trabajador_id" class="form-label">Seleccione Trabajador</label>
                        <select class="form-select" name="trabajador_id" required>
                            @foreach($trabajadoresSinNomina as $trabajador)
                                <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }} {{ $trabajador->apellido }} - {{ $trabajador->numero_identificacion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mes" class="label-form">Mes</label>
                        <input type="number" name="mes" min="1" max="12" value="{{ $paquete->mes }}" class="form-control" required>
                    </div class="form-group">
                    
                    <div class="form-group">
                        <label for="año" class="label-form">Año</label>
                        <input type="number" name="año" value="{{ $paquete->año }}" class="form-control" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Trabajador</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
    <style>
        .container {
            max-height: 500px;
            overflow-y: auto;
            font-family: 'Arial', sans-serif;
        }

        .modal-content{
            background: #8a8a8a;
        }

        table {
            border: #1b9ef5 1px solid;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: #000000 1px solid;
            text-align: left;
        }

        table thead tr th.idi {
            background: #37759e;
            border: #000000 1px solid;
            position: sticky;
            top: -10px;
            z-index: 2;
        }

        table thead tr th.d {
            background: #8ecbf3;
            border: #000000 1px solid;
            position: sticky;
            top: 30px;
            z-index: 2;
        }

        table thead tr th.x {
            background: #a6c6db;
            border: #000000 1px solid;
            position: sticky;
            top: 70px;
            z-index: 2;
        }

        thead tr th.c-1 {
            background: #b8dbeb;
            border:1px solid #000000;
        }
        table .c-1 {
            position: sticky;
            left: -8px;
            border:1px solid #000000;
        }
        table tbody tr td.c-1 {
            background: #b8dbeb;
            border:1px solid #000000;
        }

        thead tr th.c-2 {
            background: #b8dbeb;
            border:1px solid #000000;
        }
        table .c-2 {
            background: #b8dbeb;
            position: sticky;
            left: 50px;
            border:1px solid #000000;
        }
        table tbody tr .c-2 {
            background: #b8dbeb;
            border: 1px solid #000000;
        }

        thead tr th.c-3{
            background: #b8dbeb;
            border:1px solid #000000;
        }
        table .c-3 {
            position: sticky;
            left: 168px;
            border:1px solid #000000;
        }
        table tbody tr .c-3 {
            background: #b8dbeb;
            border:1px solid #000000;
        }

        thead tr th.c-4{
            background: #b8dbeb;
            border:1px solid #000000;
        }
        table .c-4 {
            position: sticky;
            left: 290px;
            border:1px solid #000000;
        }
        table tbody tr .c-4 {
            background: #b8dbeb;
            border:1px solid #000000;
        }

        thead tr th.c-5{
            background: #b8dbeb;
            border:1px solid #000000;
        }
        table .c-5 {
            position: sticky;
            left: 410px;
            border:1px solid #000000;
        }
        table tbody tr .c-5 {
            background: #b8dbeb;
            border:1px solid #000000;
        }

        table thead tr th.c-1, table thead tr th.c-2, table thead tr th.c-3, table thead tr th.c-4, table thead tr th.c-5 {
            background: #b8dbeb;
            border: #000000 1px solid;
            position: sticky;
            top: 110px;
            z-index: 2;
        }

        table thead tr th.c {
            background: #9999b0;
            border: #000000 1px solid;
            position: sticky;
            top: 110px;
        }

        .p {
            padding-left: 250px;
            transform: translateY(-55px);
        }



        .desprendible {
            display: flex;
            flex-direction: row;
        }

        table thead th,  table tbody td  {
            text-transform: uppercase;
        }

        .cont {
            background: #3a88b9;
            border-radius: 10px;
            max-width: 130rem;
            max-height: 100px;
            overflow-x: auto;
            margin: 0 auto;
            
        }

        .ad {
            text-decoration: none;
            text-transform: uppercase;
            transition: all 1000ms;
        }

        .ad:hover {
            color: #5560f8;
        }

        .excel {
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;

            padding-right: 200px;
        }

        .content, .content-header {
            background: #fff !important;
        }

    </style>
@endsection
@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editableCells = document.querySelectorAll('td[contenteditable]');
            const guardarCambiosBtn = document.getElementById('guardarCambios');
            const baseUrl =  '{{ url('/') }}'
            let cambios = {};
            let calculoDias = JSON.parse(guardarCambiosBtn.dataset.calculoDias);

            function formatNumber(num) {
                return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            function validateDays(value) {
                let days = parseInt(value);
                if (isNaN(days) || days < 0) {
                    return 0;
                } else if (days > 30) {
                    return 30;
                }
                return days;
            }

            editableCells.forEach(cell => {
                cell.addEventListener('blur', function() {
                    const field = this.dataset.field;
                    const nominaId = this.dataset.nominaId;
                    let value = this.textContent.trim();

                    if (['dias_incapacidad', 'dias_vacaciones', 'dias_remunerados', 'dias_no_remunerados'].includes(field)) {
                        value = validateDays(value);
                        this.textContent = value; // Actualiza el contenido de la celda con el valor validado
                    } else if (['bonificacion_auxilio', 'celular', 'anticipo', 'otro'].includes(field)) {
                        value = parseFloat(value.replace(/,/g, '')) || 0;
                        this.textContent = formatNumber(value);
                    } else if (['desde', 'a'].includes(field)) {
                        value = value.trim();
                    }

                    if (!cambios[nominaId]) {
                        cambios[nominaId] = {};
                    }
                    cambios[nominaId][field] = value;

                    if (['dias_incapacidad', 'dias_vacaciones', 'dias_remunerados'].includes(field)) {
                        const nominaCalculo = calculoDias.find(n => n.id.toString() === nominaId);
                        if (nominaCalculo) {
                            const diasTrabajadosCell = document.querySelector(`td[data-nomina-id="${nominaId}"][data-field="dias_trabajados"]`);
                            const diasIncapacidad = validateDays(document.querySelector(`td[data-nomina-id="${nominaId}"][data-field="dias_incapacidad"]`).textContent);
                            const diasVacaciones = validateDays(document.querySelector(`td[data-nomina-id="${nominaId}"][data-field="dias_vacaciones"]`).textContent);
                            const diasRemunerados = validateDays(document.querySelector(`td[data-nomina-id="${nominaId}"][data-field="dias_remunerados"]`).textContent);
                            
                            const diasTrabajados = Math.max(0, 30 - diasIncapacidad - diasVacaciones - diasRemunerados);
                            diasTrabajadosCell.textContent = diasTrabajados;
                            cambios[nominaId]['dias_trabajados'] = diasTrabajados;
                        }
                    }

                    this.classList.add('bg-yellow-100');
                });
            });

            guardarCambiosBtn.addEventListener('click', function() {
                if (Object.keys(cambios).length === 0) {
                    alert('No hay cambios para guardar');
                    return;
                }

                guardarCambiosBtn.disabled = true;
                guardarCambiosBtn.textContent = 'Guardando...';

                console.log('Cambios a enviar:', cambios);

                fetch(`${baseUrl}/nominas/update-bulk`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ cambios: cambios })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Cambios guardados y nóminas recalculadas con éxito');
                        Object.keys(data.nominasActualizadas).forEach(nominaId => {
                            const nomina = data.nominasActualizadas[nominaId];
                            Object.keys(nomina).forEach(field => {
                                const cell = document.querySelector(`td[data-nomina-id="${nominaId}"][data-field="${field}"]`);
                                if (cell) {
                                    cell.textContent = nomina[field];
                                    cell.classList.remove('bg-yellow-100');
                                    cell.classList.add('bg-green-100');
                                    setTimeout(() => cell.classList.remove('bg-green-100'), 2000);
                                }
                            });
                        });
                        cambios = {};
                    } else {
                        console.error('Error details:', data);
                        alert('Error al guardar los cambios: ' + (data.message || 'Detalles no disponibles'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al guardar los cambios: ' + error.message);
                })
                .finally(() => {
                    guardarCambiosBtn.disabled = false;
                    guardarCambiosBtn.textContent = 'Guardar Cambios';
                });
            });
        });
    </script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            // Obtener el valor del campo de búsqueda
            var searchValue = this.value.toLowerCase();
            
            // Obtener todas las filas de la tabla
            var rows = document.querySelectorAll('#trabajadoresTable tr');
            
            // Iterar sobre las filas y ocultar las que no coincidan con el término de búsqueda
            rows.forEach(function(row) {
                // Obtener el texto de las celdas que deseas filtrar (por ejemplo: nombre, apellido, identificación)
                var nombre = row.cells[0].innerText.toLowerCase();
                var apellido = row.cells[1].innerText.toLowerCase();
                var numeroIdentificacion = row.cells[2].innerText.toLowerCase();
                
                // Si el término de búsqueda coincide con el nombre, apellido o identificación, mostrar la fila
                if (nombre.includes(searchValue) || apellido.includes(searchValue) || numeroIdentificacion.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    // Si no coincide, ocultar la fila
                    row.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
@stop
