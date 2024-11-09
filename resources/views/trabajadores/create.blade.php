@extends('adminlte::page')

@section('title', 'crear_empleado')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Formulario del nuevo empleado') }}
</h2>
@stop

@section('content')
<div class="py-20">
    <div class="container">
        <div class="row">
            <div class="col">
                <form action="{{ route('trabajadores.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    <h1>INFORMACION PERSONAL DEL EMPLEADO</h1>
    
                        <h2>Documento</h2>
    
                    <div class="">
                        <label class="block text-gray-100 text-sm font-bold mb-2" for="nombre">cedula</label>
                        <input type="text" id="numero_identificacion" name="numero_identificacion"  class="bg- border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                    </div>
                        <h2>Personal</h2>
    
                    <div class="">
                        <label class="block text-gray-100 text-sm font-bold mb-2" for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                    </div>
    
                    <div class="">
                        <label class="block text-gray-100 text-sm font-bold mb-2" for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                    </div>
    
                    <div class="">
                        <label for="fecha_nacimieno" >Fecha de nacimiento</label>
                        <input type="date" id="trabajador-fecha_nacimiento" data-relacion="trabajador" name="fecha_nacimiento"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
                    </div>
    
                    <div class="">
                        <label  for="edad">Edad</label>
                        <input type="number" id="trabajador-edad" name="edad"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" readonly>
                    </div>
                        <h2>contacto</h2>
                    <div class="form-group">
                        <label for="celular" class="block mb-2 text-sm font-medium text-gray-100">celular</label>
                        <input type="text" id="celular" name="celular" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5">
                    </div>

                    <h2>informacion del cargo</h2>

                    <div class="">
                        <label class="block text-gray-100 text-sm font-bold mb-2" for="cargo">Cargo</label>
                        <input type="text" id="cargo" name="cargo"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                    </div>
    
                    <div class="form-group">
                        <label for="fecha_ingreso" class="block mb-2 text-sm font-medium text-gray-100">Fecha de ingreaso</label>
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                    </div>
    
                    <div class="form-group">
                        <label for="tipo_pago" class="block mb-2 text-sm font-medium text-gray-100">Tipo de Pago</label>
                        <select name="tipo_pago" id="tipo_pago" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                            @foreach (App\Enums\TipoPago::cases() as $tipoPago)
                                <option value="{{ $tipoPago->value }}">
                                    {{ $tipoPago->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="departamentos" class="block mb-2 text-sm font-medium text-gray-100">Departamento / Area / Centro De Trabajo </label>
                        <select name="departamentos" id="departamentos" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required>
                            @foreach (App\Enums\Departamento::cases() as $departamento)
                                <option value="{{ $departamento->value }}">
                                    {{ $departamento->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="contrato" class="block mb-2 text-sm font-medium text-gray-100">Contrato</label>
                        <input type="text" id="contrato" name="contrato" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5">
                    </div>
    
                    <h2>Direccion Del Empleado</h2>
    
                    
    
                    <h1>INFORMACION MEDICA</h1>
                    
                    <div class="form-group">
                        <label for="Eps" class="block mb-2 text-sm font-medium text-gray-100">EPS</label>
                        <input type="text" id="Eps" name="Eps" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5">
                    </div>

                    <h1>INFORMACION BANCARIA DEL EMPLEADO</h1>
    
                    <div class="form-group">
                        <label for="cuenta_bancaria" class="block mb-2 text-sm font-medium text-gray-100 capitalize">cuenta bancaria</label>
                        <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5">
                    </div>
    
                    <div class="form-group">
                        <label for="banco" class="block mb-2 text-sm font-medium text-gray-100 capitalize">Banco</label>
                        <input type="text" id="banco" name="banco" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5">
                    </div>
    
                    <h1 class="mb-4">INFORMACION FAMILIAR DEL EMPLEADO</h1>
                        <h1>ESTADO DEL EMPLEADO</h1>
    
                        <div class="form-group">
                            <label for="estado" class="block mb-2 text-sm font-medium text-gray-100 capitalize">Estado</label>
                            <select name="estado" id="estado" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5">
                                <option value="activo">activo</option>
                                <option value="inactivo">inactivo</option>
                            </select>
                        </div>
                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                        <a href="{{ route('trabajadores.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2.5 px-4 rounded">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

        h1 {
            text-transform: uppercase;
            color: #0b0b0b;
            margin-bottom: 20px;
            font-size: 20px;
        }

        h2 {
            text-transform: capitalize;
            color: #0b0b0b;
            margin-top: 20px;
            font-size: 20px;
        }

        .container {
            background: #afb5bb;
            max-width: 500rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 20px;
        }

        form {
            width: 900px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            gap: 20px;
        }

        label {
            font-size: 15px;
            text-align: start;
            display: block;
            color: #0a0a0a !important;
        }

        input {
            width: 500px;
            height: 50px;
            background-color: #f7f7f8 !important; /* Para modo claro */
            border-width: 1px;
            color: #0d0d0d !important;
            font-size: 0.875rem; /* 14px */
            border-radius: 0.5rem; /* 8px */
            display: block;
            padding: 0.625rem;
        }

        input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px #3B82F6;
            border-color: #3B82F6;
        }

        input::placeholder {
            color: #ffffff;
        }

        hr {
            width: 700px;
            color: #ffffff;
        }

        select {
            width: 500px;
            height: 50px;
            background: #f7f8f8 !important;
            color: #0a0a0a !important;
            padding: 0.625rem;
            margin: 8px 0;
            border: #131212 0.5px solid;
            border-radius: 5px;
            font-size: 0.875rem ;
            line-height: 1.25rem ;
            text-transform: uppercase;
        }

        .content, .content-header {
            background: #ecf6fa !important;
        }

    </style>
@stop

@section('js')
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
<script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aplica la función de cálculo de edad a los campos de fecha existentes
            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.addEventListener('input', function() {
                    calculateAge(this);
                });
            });

            function calculateAge(input) {
                const fechaNacimiento = new Date(input.value);
                const hoy = new Date();
                let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                const mes = hoy.getMonth() - fechaNacimiento.getMonth();

                // Ajuste si el mes o día de nacimiento aún no ha ocurrido este año
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }

                // Identifica el campo de edad correspondiente
                const idRelacion = input.getAttribute('data-relacion');
                const edadInput = document.getElementById(`${idRelacion}-edad`);

                if (edadInput) {
                    edadInput.value = edad >= 0 ? edad : 0; // Evita edades negativas
                }
            }
        });
    </script>
@stop