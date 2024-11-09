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
            <table class=" border-gray-900 ">
                <thead>
                    <tr class=" border-gray-900 ">
                        <!-- Sección de Información Personal -->
                        <th colspan="14" class=" d border-gray-900 ">Información Personal</th>
                        <!-- Sección de Información Médica -->
                        <!-- Sección de Acciones -->
                        <th colspan="3" class=" d border-gray-900 ">Acciones</th>
                    </tr>
                    <tr class=" border-gray-900 ">
                        <th class="  border-gray-900  cd">#</th>
                        <!-- Información Personal -->
                        <th class="  border-gray-900  c-1">Cédula</th>
                        <th class=" border-gray-900   c-2">Nombre</th>
                        <th class=" border-gray-900   c-3">Apellido</th>
                        <th class="  border-gray-900 c-4">Sueldo Base</th>
                        
                        <th class="  border-gray-900 v">Celular</th>
                        
                        <th class="  border-gray-900 v">Area</th>
                        <th class="  border-gray-900  v">Edad</th>
                        
                        <th class="  border-gray-900  v">Cargo</th>
                        <th class=" border-gray-900  v">Fecha de Nacimiento</th>
                        <th class="  border-gray-900 v">Fecha de Ingreso</th>
                        
        
                        <!-- Información Médica -->
                        
                        <th class="  border-gray-900 v">EPS</th>
                        
        
                        <!-- Información Familiar -->
                        
                        <th class=" border-gray-900  v">Cuenta Bancaria</th>
                        
                        <th class=" border-gray-900  v">Banco</th>
                            <!-- Acciones -->
                            {{-- <th class=" v">Eliminar</th> --}}
                            <th class=" border-gray-900  v">Habilitar/Deshabilitar</th>
                    </tr>
                </thead>
                <tbody id="trabajadoresTable">
                    @foreach ($trabajadores as $index => $trabajador)
                            <tr class="text-gray-700">
                                <td class=" border-gray-900  cd">{{ $index + 1 }}</td>
                                <!-- Información Personal -->
                                <td class=" border-gray-900  c-1">{{ $trabajador->numero_identificacion }}</td>
                                <td class=" border-gray-900  c-2">{{ $trabajador->nombre }}</td>
                                <td class=" border-gray-900  c-3">{{ $trabajador->apellido }}</td>
                                <td class=" border-gray-900  c-4">{{ $trabajador->sueldos->first()->sueldo ?? 'No tiene sueldo registrado' }}</td>
                                <td class=" border-gray-900  v">{{ $trabajador->celular }}</td>
                                <td class=" border-gray-900  v">{{ $trabajador->departamentos }}</td>
                                <td class=" border-gray-900  v">{{ $trabajador->edad }}</td>
                                <td class=" border-gray-900  v">{{ $trabajador->cargo }}</td>
                                <td class=" border-gray-900  v">{{ $trabajador->fecha_nacimiento }}</td>
                                <td class=" border-gray-900  v">{{ $trabajador->fecha_ingreso }}</td>
        
                                <!-- Información Médica -->
                                <td class=" border-gray-900  v">{{ $trabajador->Eps }}</td>
        
                                <!-- Información Familiar -->
                                <td class="  border-gray-900 v">{{ $trabajador->cuenta_bancaria }}</td>
                                <td class="  border-gray-900 v">{{ $trabajador->banco }}</td>
                                <td class="  border-gray-900  v">
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
    <style>
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
            border:1px solid #000000;
            border-collapse: collapse;
            padding: 1px;
            /* height: 200px; */
        }

        th, td {
            padding: 10px;
            border:1px solid #000000;
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
            left: 112px;
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
            left: 199px;
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
            left: 300px;
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
        td {
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
