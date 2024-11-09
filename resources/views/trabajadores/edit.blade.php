@extends('adminlte::page')

@section('title', 'Editar Empleado')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Editar empleado') }} - {{ $trabajador->nombre }} - {{ $trabajador->apellido }}
    </h2>
@stop

@section('content')
<div class="py-20">
    <div class="container">
        <div class="row">
            <div class="col">
                <form action="{{ route('trabajadores.update', $trabajador->id) }}" method="POST" >
                    @csrf
                    @method('PUT')
    
                    <h1>INFORMACION PERSONAL DEL EMPLEADO</h1>
                    <h2>Documento</h2>
                        <div class="form-1">
                            <label  for="nombre">cedula</label>
                            <input type="text" id="numero_identificacion" name="numero_identificacion" value="{{ $trabajador->numero_identificacion }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
                        </div>
                        <hr>
                        <h2>Personal</h2>
                        <div class="form-2">
                            <label  for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="{{ $trabajador->nombre }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
                        
                            <label  for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" value="{{ $trabajador->apellido }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
                            
                            <label for="fecha_nacimieno" >Fecha de nacimiento</label>
                            <input type="date" id="trabajador-fecha_nacimiento" data-relacion="trabajador" name="fecha_nacimiento" value="{{ $trabajador->fecha_nacimiento }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
                        
                            <label  for="edad">Edad</label>
                            <input type="number" id="trabajador-edad" name="edad" value="{{ $trabajador->edad }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" readonly>

                            
                        </div>
                        <hr>
                        <h2>contacto</h2>
                        <div class="form-3">

                            <label for="celular" >celular</label>
                            <input type="text" id="celular" name="celular" value="{{ $trabajador->celular }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required >

                        </div>
                        <hr>
    
                        <h2>informacion del cargo</h2>
                    <div class="form-4">
                        
    
                        <label  for="cargo">Cargo</label>
                        <input type="text" id="cargo" name="cargo" value="{{ $trabajador->cargo }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
    
                        <label for="fecha_ingreso" >Fecha de ingreaso</label>
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ $trabajador->fecha_ingreso }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5"  required>
                    
                        <label for="tipo_pago" >Tipo de Pago</label>
                        <select name="tipo_pago" id="tipo_pago"  required>
                            @foreach (App\Enums\TipoPago::cases() as $tipoPago)
                                <option 
                                    value="{{ $tipoPago->value }}" 
                                    {{ $trabajador->tipo_pago === $tipoPago ? 'selected' : '' }}
                                >
                                    {{ $tipoPago->name }}
                                </option>
                            @endforeach
                        </select>
                    
                        <label for="departamentos">Departamento / Area / Centro De Trabajo</label>
                        <select name="departamentos" id="departamentos" required>
                            @foreach (App\Enums\Departamento::cases() as $departamento)
                                <option 
                                    value="{{ $departamento->value }}" 
                                    {{ $trabajador->departamentos === $departamento ? 'selected' : '' }}
                                >
                                    {{ $departamento->name }}
                                </option>
                            @endforeach
                        </select>
                    
                        <label for="contrato" >Contrato</label>
                        <input type="text" id="contrato" name="contrato" value="{{ $trabajador->contrato }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required >
                    </div>
                    <hr>
                    
                    <hr>
                    <h1>INFORMACION MEDICA</h1>
                    <div class="form-6">
                        
                        
    
    
                        <label for="Eps" >EPS</label>
                        <input type="text" id="Eps" name="Eps" value="{{ $trabajador->Eps }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required >
                    
    
    
                        
    
                        
                    </div>
                    <hr>
                    
                    <hr>
                    <h1>INFORMACION BANCARIA DEL EMPLEADO</h1>
                    <div class="form-8">
                        
    
                        <label for="banco" class=" capitalize">Banco</label>
                        <input type="text" id="banco" name="banco" value="{{ $trabajador->banco }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required >
    
    
                        <label for="cuenta_bancaria" class=" capitalize">cuenta bancaria</label>
                        <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="{{ $trabajador->cuenta_bancaria }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5" required >
    
                        
                    </div>
                    <hr>
                    
                    <hr>
                    <h1>ESTADO DEL EMPLEADO</h1>
                        <div class="form-10">
                            <label for="estado" class=" capitalize">Estado</label>
                            <select name="estado" id="estado" value="{{ $trabajador->estado }}" required >
                            @foreach(['activo','inactivo'] as $estado)    
                                <option value="{{ $estado }}" 
                                    {{ $trabajador->estado === $estado ? 'selected' : '' }}
                                >
                                    {{ ucfirst($estado) }}
                                </option>
                            @endforeach
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/4.0.0-alpha.1/chunk-GNCUPSHB.mjs">
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
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.tailwindcss.com"></script>
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