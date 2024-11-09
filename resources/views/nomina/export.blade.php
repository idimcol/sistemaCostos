@extends('adminlte::page')

@section('title', 'nominas')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Nóminas para') }} {{ $paquete->mes }}/{{ $paquete->año }}
    </h2>
@stop

@section('content')
<div class="flex flex-col items-end justify-end mb-4">
    <a href="{{ route('nomina.show', $paquete->id) }}" class="btn btn-primary">volver</a>
</div>
<div class="card">
    <div class="card-body">
        <div id="export-button-container"></div>
        <div class="">
            <table id="nominas-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>cedula</th>
                        <th>apellidos</th>
                        <th>nombres</th>
                        <th>cargo</th>
                        <th>salario</th>
                        <th>días trabajados</th>
                        <th>días incapacidad</th>
                        <th>días vacaciones</th>
                        <th>días renumerados</th>
                        <th>días totales</th>
                        <th>bonificación// auxilio de rodamiento</th>
                        <th>devengados días trabajados</th>
                        <th>devengados días incapacidad</th>
                        <th>devengados días vacaciones</th>
                        <th>devengados días renumerados</th>
                        <th>auxilio de transporte</th>
                        <th>total devengados</th>
                        <th>salud</th>
                        <th>pensión</th>
                        <th>celular</th>
                        <th>anticipo</th>
                        <th>días no renumerados</th>
                        <th>suspensión</th>
                        <th>otro</th>
                        <th>total deducido</th>
                        <th>total a pagar</th>
                        <th>área de trabajo</th>
                        <th>desde</th>
                        <th>a</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nominas as $index => $nomina)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $nomina->trabajador->numero_identificacion }}</td>
                            <td>{{ $nomina->trabajador->apellido }}</td>
                            <td>{{ $nomina->trabajador->nombre }}</td>
                            <td>{{ $nomina->trabajador->cargo }}</td>
                            <td>{{ number_format($nomina->trabajador->sueldos->first()->sueldo, 2, '.', ',') }}</td>
                            <td>{{ $nomina->dias->dias_trabajados }}</td>
                            <td>{{ $nomina->dias->dias_incapacidad }}</td>
                            <td>{{ $nomina->dias->dias_vacaciones }}</td>
                            <td>{{ $nomina->dias->dias_remunerados }}</td>
                            <td>{{ $nomina->total_dias }}</td>
                            <td>{{ number_format($nomina->bonificacion_auxilio, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->devengado_trabajados, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->devengado_incapacidad, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->devengado_vacaciones, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->devengado_remunerados, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->auxilio_transporte, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->total_devengado, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->salud, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->pension, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->celular, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->anticipo, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->dias->dias_no_remunerados, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->suspencion, 2, '.', ',') }}</td>
                            <td></td>
                            <td>{{ number_format($nomina->total_deducido, 2, '.', ',') }}</td>
                            <td>{{ number_format($nomina->total_a_pagar, 2, '.', ',') }}</td>
                            <td>{{ $nomina->trabajador->departamentos }}</td>
                            <td>{{ $nomina->desde }}</td>
                            <td>{{ $nomina->a }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="totales" class="table table-striped">
            <thead>
                <tr>
                    <th>salario</th>
                    <th>dias trabajados</th>
                    <th>dias incapacidad</th>
                    <th> dias vacaviones</th>
                    <th>dias remunerados</th>
                    <th>dias totales</th>
                    <th>bonificacion auxilo de rodamiento</th>
                    <th>devengado dias trabajados</th>
                    <th>devengado dias incapacidad</th>
                    <th>devengado dias vacaciones</th>
                    <th>devengado dias remunerados</th>
                    <th>auxilio de transporte</th>
                    <th>total devengados</th>
                    <th>salud</th>
                    <th>pencion</th>
                    <th>celular</th>
                    <th>anticipo</th>
                    <th>dias no remunerados</th>
                    <th>suspencion</th>
                    <th>otro</th>
                    <th>total deducido</th>
                    <th>total a pagar</th>
                </tr>
            </thead>
            <tbody>
                <tr class="">
                    <td>{{ number_format($totalSueldo, 2, ',', '.') }}</td>
                    <td>{{ $total_dias_trabajados }}</td>
                    <td>{{  $total_dias_incapacidad }}</td>
                    <td>{{ $total_dias_vacaciones }}</td>
                    <td>{{ $total_dias_remunerados }}</td>
                    <td>{{ $total_dias }}</td>
                    <td>{{ number_format($total_bonificacion, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_D_dias_trabajados, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_D_dias_incapacidad, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_D_dias_vacaciones, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_D_dias_remunerados, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_auxilio, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_devengado, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_pencion, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_salud, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_celular, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_anticipo, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_dias_no_remunerados, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_suspencion, 2, '.', ',') }}</td>
                    <td>{{ number_format($total_otro, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_deducido, 2, ',', '.') }}</td>
                    <td>{{ number_format($total_a_pagar, 2, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                
                <tr>
                    <td colspan="11">TOTAL PCC</td>
                    <td>{{ number_format($total_a_pagar_pcc, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="11">TOTAL ADMON</td>
                    <td>{{ number_format($total_a_pagar_admon, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="11">TOTAL SOCIOS</td>
                    <td>{{ number_format($total_a_pagar_socios, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="11">SUBTOTAL</td>
                    <td>{{ number_format($subtotal, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css">

    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.templates.min.js"></script>

    <style>
        .table{
            border: #fff solid 1px;
        }

        .table thead tr th, .table tbody tr td{
            border: #fff solid 1px;
            text-transform: uppercase;
        }
        .content, .content-header {
            background: #fff !important;
        }

        h2 {
            font-size: 18px;
            text-transform: uppercase;
        }

        .card, .card-body {
            background: #fff !important;
            color: #000 !important;
        }

        input {
            background: #fff !important;
            color: #000 !important;
        }

        .export-button-container {
            background: #34a54b !important;
            color: #000 !important;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.bootstrap5.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>

    {{-- buttons --}}
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table1 = $('#nominas-table').DataTable({
                paging: false,
                scrollCollapse: true,
                scrollX: true,
                scrollY: '50vh',
            });
    
            var table2 = $('#totales').DataTable({
                paging: false,
                scrollCollapse: true,
                scrollX: true,
                scrollY: '50vh',
            });
    
            // Configuración para exportar ambas tablas en un solo archivo de Excel
            var exportTables = new $.fn.dataTable.Buttons(table1, {
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel',
                        className: 'bg-green-600 hover:bg-green-800 px-3 py-2 rounded',
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
    
                            // Añadir una fila en blanco entre las tablas
                            $('row', sheet).last().after('<row><c t="inlineStr"><is><t></t></is></c></row>');
    
                            // Exportar el encabezado (thead) de la tabla totales
                            $('#totales thead tr').each(function() {
                                var newRow = '<row>';
                                $(this).find('th').each(function() {
                                    var cellValue = $(this).text();
                                    newRow += '<c t="inlineStr"><is><t>' + cellValue + '</t></is></c>';
                                });
                                newRow += '</row>';
                                $('row', sheet).last().after(newRow);
                            });
    
                            // Exportar todas las filas y columnas de la tabla totales
                            $('#totales').DataTable().rows().every(function(rowIdx, tableLoop, rowLoop) {
                                var row = this.data();
                                var newRow = '<row>';
                                for (var i = 0; i < row.length; i++) {
                                    newRow += '<c t="inlineStr"><is><t>' + row[i] + '</t></is></c>';
                                }
                                newRow += '</row>';
                                $('row', sheet).last().after(newRow);
                            });
    
                            // Exportar el footer de la tabla totales si existe
                            $('#totales tfoot tr').each(function() {
                                var newRow = '<row>';
                                $(this).find('td').each(function() {
                                    var cellValue = $(this).text();
                                    newRow += '<c t="inlineStr"><is><t>' + cellValue + '</t></is></c>';
                                });
                                newRow += '</row>';
                                $('row', sheet).last().after(newRow);
                            });
    
                            // Ajustar el ancho de las celdas automáticamente
                            var colWidths = [];
                            $('#totales thead tr th').each(function() {
                                var colWidth = $(this).text().length;
                                colWidths.push({ wch: colWidth });
                            });
    
                            xlsx.xl.worksheets['sheet1.xml'].cols = colWidths;
                        }
                    }
                ]
            }).container().appendTo($('#export-button-container'));
    
            // También puedes añadir el botón al contenedor de `totales` si lo prefieres
        });
    </script>
@stop