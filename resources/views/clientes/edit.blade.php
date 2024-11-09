@extends('adminlte::page')

@section('title', 'editar cliente')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Editar Cliente') }}
</h2>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente->nit) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="comerciales_id" class="block mb-2 text-sm font-medium text-gray-900">R.comercial</label>
                    <select name="comerciales_id" id="comerciales_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="">Seleccione un comercial</option>
                        @foreach($comerciales as $comercial)
                            <option value="{{ $comercial->id }}" {{ $cliente->comerciales_id == $comercial->id ? 'selected' : '' }}>
                                {{ $comercial->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="nombre">Nombre / Razon Social</label>
                    <input type="text" name="nombre" id="nombre" value="{{ $cliente->nombre }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="nit">Nit / Cedula</label>
                    <input type="text" name="nit" id="nit" value="{{ $cliente->nit }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>


                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="direccion">direccion</label>
                    <input type="text" name="direccion" id="direccion"  value="{{ $cliente->direccion }}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="departamento">Departamento</label>
                    <select name="departamento" id="departamento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" {{ $cliente->departamento == $departamento->id ? 'selected' : '' }}>{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="ciudad">Ciudad / Municipio</label>
                    <select name="ciudad" id="ciudad" class="select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Seleccione una ciudad/municipio</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}" {{ $cliente->ciudad == $municipio->id ? 'selected' : '' }}>{{ $municipio->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono"  value="{{ $cliente->telefono }}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="contacto">contacto</label>
                    <input type="text" name="contacto" id="contacto"  value="{{ $cliente->contacto }}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="correo">Correo</label>
                    <input type="email" name="correo" id="correo"  value="{{ $cliente->correo }}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">Actualizar</button>
                    <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .card, .card-body {
            background-color: #a8a4a4 !important;
            color: #000 !important;
        }

        .content, .content-header {
            background: #fff !important;
        }

        input, select {
            background: #fff !important;
            color: #000 !important;
        }

        h2 {
            font-size: 18px;
            text-transform: uppercase;
        }

        container {
            padding: 10px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const baseUrl = '{{ url('/') }}';
            const departamentoSelect = document.getElementById('departamento');
            const municipioSelect = document.getElementById('ciudad');

        departamentoSelect.addEventListener('change', function () {
            const departamentoId = this.value;

            if (departamentoId) {
                fetch(`${baseUrl}/api/municipios/${departamentoId}`)
                    .then(response => response.json())
                    .then(data => {
                        municipioSelect.innerHTML = '<option value="">Seleccione una ciudad/municipio</option>';
                        data.forEach(municipio => {
                            municipioSelect.innerHTML += `<option value="${municipio.id}">${municipio.nombre}</option>`;
                        });
                    });
            } else {
                municipioSelect.innerHTML = '<option value="">Seleccione una ciudad/municipio</option>';
            }
        });
    });
    </script>
@stop








