@extends('adminlte::page')

@section('title', 'empleados')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Empleados inactivos') }}
    </h2>
@stop

@section('content')
<div class="box">
    <div class="col-12 px-20 mb-4">
        <a href="{{ route('trabajador.butons') }}" class="btn btn-primary">volver</a>
    </div>
    <div class="container">
        <div class="flex">
            <input type="text" id="searchInput" placeholder="Buscar trabajadores..." class="border rounded p-2 mb-4">
        </div>
        <div class="table_wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr class=" ">
                        <!-- Sección de Información Personal -->
                        <th colspan="14" class=" d ">Información Personal</th>
                        <!-- Sección de Información Médica -->
                        <!-- Sección de Acciones -->
                        <th colspan="3" class=" d ">Acciones</th>
                    </tr>
                    <tr class=" ">
                        <th class="cd">#</th>
                        <!-- Información Personal -->
                        <th class="c-1">Cédula</th>
                        <th class="c-2">Nombre</th>
                        <th class="c-3">Apellido</th>
                        <th class="c-4">Sueldo Base</th>
                        
                        <th class="v">Celular</th>
                        
                        <th class="v">Area</th>
                        <th class="v">Edad</th>
                        
                        <th class="v">Cargo</th>
                        <th class="v">Fecha de Nacimiento</th>
                        <th class="v">Fecha de Ingreso</th>
                        
        
                        <!-- Información Médica -->
                        
                        <th class="v">EPS</th>
                        
        
                        <!-- Información Familiar -->
                        
                        <th class="v">Cuenta Bancaria</th>
                        
                        <th class="  v">Banco</th>
                            <!-- Acciones -->
                            {{-- <th class=" v">Eliminar</th> --}}
                            <th class="v">Habilitar/Deshabilitar</th>
                    </tr>
                </thead>
                <tbody id="trabajadoresTable">
                    @foreach ($trabajadores as $index => $trabajador)
                            <tr class="">
                                <td class="cd">{{ $index + 1 }}</td>
                                <!-- Información Personal -->
                                <td class="c-1">{{ $trabajador->numero_identificacion }}</td>
                                <td class="c-2">{{ $trabajador->nombre }}</td>
                                <td class="c-3">{{ $trabajador->apellido }}</td>
                                <td class="c-4">{{ $trabajador->sueldos->first()->sueldo ?? 'No tiene sueldo registrado' }}</td>
                                <td class="v">{{ $trabajador->celular }}</td>
                                <td class="v">{{ $trabajador->departamentos }}</td>
                                <td class="v">{{ $trabajador->edad }}</td>
                                <td class="v">{{ $trabajador->cargo }}</td>
                                <td class="v">{{ $trabajador->fecha_nacimiento }}</td>
                                <td class="v">{{ $trabajador->fecha_ingreso }}</td>
        
                                <!-- Información Médica -->
                                <td class="v">{{ $trabajador->Eps }}</td>
        
                                <!-- Información Familiar -->
                                <td class="v">{{ $trabajador->cuenta_bancaria }}</td>
                                <td class="v">{{ $trabajador->banco }}</td>
                                <td class="v">
                                    @if ($trabajador->estado === 'activo')
                                        <form action="{{ route('trabajadores.disable', $trabajador->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">Deshabilitar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('trabajadores.enable', $trabajador->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Habilitar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
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
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    <style>

    * {
    
    }
    .p-6 {
        padding: auto;
    }

    .box {
        padding: 5px;
        max-width: 1000rem;
    }

    .container {
        width: 70rem;
        max-height: 900px;
        background: #a9a9a9;
        border-radius: 10px;
        padding: 20px;
    }

    .content, .content-header{
        background: #fff !important;
    }

    .content {
        height: 87vh;
    }

    .table_wrapper {
        background: #d6d6d6;
        border-radius: 10px;
        max-height: 500px;
        overflow-x: auto;
    }

    table {
            border:1px solid #000000 !important;
            border-collapse: collapse;
            padding: 1px;
            /* height: 200px; */
        }

        th, td {
            padding: 10px;
            border:1px solid #000000 !important;
            text-align: center;
        }

        table thead tr th.d {
            background: #37759e;
            border:1px solid #000000;
            position: sticky;
            top: -12px;
            z-index: 2;
        }

        thead tr th.cd {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }
        table .cd {
            position: sticky;
            left: -5px;
            border:1px solid #000000;
            color: #000000;
        }
        table tbody tr td.cd {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }

        thead tr th.c-1 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }
        table .c-1 {
            position: sticky;
            left: 20px;
            border:1px solid #000000;
            color: #000000;
        }
        table tbody tr td.c-1 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }

        thead tr th.c-2 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }
        table .c-2 {
            position: sticky;
            left: 115px;
            border:1px solid #000000;
            color: #000000;
        }
        table tbody tr td.c-2 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }

        thead tr th.c-3 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;

        }
        table .c-3 {
            position: sticky;
            left: 185px;
            border:1px solid #000000;
            color: #000000;
        }
        table tbody tr td.c-3 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }

        thead tr th.c-4 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }
        table .c-4 {
            position: sticky;
            left: 265px;
            border:1px solid #000000;
            color: #000000;
        }
        table tbody tr td.c-4 {
            background: #91dcff;
            border:1px solid #000000;
            color: #000000;
        }

        table thead tr th.cd, table thead tr th.c-1, table thead tr th.c-2, 
        table thead tr th.c-3, table thead tr th.c-4 {
            background: #91dcff;
            border:1px solid #000000;
            position: sticky;
            top: 32px;
            z-index: 2;
        }

        table thead tr th.v {
            background: #2b5fa3;
            color: #000000;
            border:1px solid #000000;
            position: sticky;
            top: 32px;

            text-transform: uppercase;
        }

        table .v {
            color: #000000;
            border:1px solid #000000;
        }
        th {
            text-transform: uppercase;
        }
        td{
            text-transform: capitalize;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
<script>
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
</script>
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
@stop
