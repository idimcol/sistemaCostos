@extends('adminlte::page')

@section('title', 'CREAR CLIENTE')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Formulario del nuevo cliente') }}
    </h2>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf

                <div class="form-group">    
                    <label for="comerciales_id" class="form-label">R.comercial</label>
                    <select name="comerciales_id" id="comerciales_id" class="form-control" required>
                        <option value="">Seleccione un comercial</option>
                        @foreach($comerciales as $comercial)
                            <option value="{{ $comercial->id }}">{{ $comercial->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">    
                    <label class="form-label" for="nombre">Nombre / Razon Social</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray100" for="nit">Nit / Cedula</label>
                    <input type="text" name="nit" id="nit" class="form-control" required>
                </div>

                <div class="form-group">    
                    <label class="form-label" for="direccion">direccion</label>
                    <input type="text" name="direccion" id="" class="form-control" required>
                </div>

                <div class="form-group">    
                    <label class="form-label" for="departamento">Departamento / pais</label>
                    <select name="departamento" id="departamento" class="form-control" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ciudad">Ciudad / Municipio</label>
                    <div class="relatives">
                        <select name="ciudad" id="ciudad" class="select form-control" required>
                            <option value="ciudad">Seleccione una ciudad/municipio</option>
                        </select>
                        <button type="button" id="addMunicipioBtn" class="btn btn-success text-center">Agregar Municipio</button>
                    </div>
                </div>

                <div class="form-group">    
                    <label class="form-label" for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" required>
                </div>

                <div class="form-group">    
                    <label class="form-label" for="contacto">contacto</label>
                    <input type="text" name="contacto" id="contacto" class="form-control" required>
                </div>

                <div class="form-group">    
                    <label class="form-label" for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" required>
                </div>

                <div class=" mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="addMunicipioModal" class="fixed inset-0 flext items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Agregar Nuevo Municipio</h3>
        <form id="addMunicipioForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="newMunicipio" class="block text-sm font-medium text-gray-900">Nombre del Municipio</label>
                <input type="text" id="newMunicipio" name="newMunicipio" class="form-control" required>
            </div>
            <div class="flex items-center justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar</button>
                <button type="button" id="closeModalBtn" class="ml-4 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancelar</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>

        .container {
            padding: 10px;
        }

        .flext {
            display: flex;
        }
        .relatives {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .relatives select.select {
            width: 500rem;
            height: 40px;
            background: #fff !important;
            color: #000 !important;

        }

        .relatives button {
            width: 300px;
            height: 60px;
            text-align: center;
            padding: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .card, .card-body {
            background: #acacac !important;
            color: #000 !important;
        }

        .content {
            height: 90vh;
        }

        input, select {
            background: #fff !important;
            color: #000 !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = '{{ url('/') }}';
            const departamentoSelect = document.getElementById('departamento');
            const ciudadSelect = document.getElementById('ciudad');
            const addMunicipioBtn = document.getElementById('addMunicipioBtn');
            const addMunicipioModal = document.getElementById('addMunicipioModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const addMunicipioForm = document.getElementById('addMunicipioForm');

            departamentoSelect.addEventListener('change', function() {
                const departamentoId = this.value;
                ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad/municipio</option>'; // Limpiar opciones previas

                if (departamentoId) {
                    fetch(`${baseUrl}/municipios/${departamentoId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(municipio => {
                                const option = document.createElement('option');
                                option.value = municipio.id;
                                option.textContent = municipio.nombre;
                                ciudadSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching municipios:', error));
                }
            });

            addMunicipioBtn.addEventListener('click', function() {
                addMunicipioModal.classList.remove('hidden');
            });

            closeModalBtn.addEventListener('click', function() {
                addMunicipioModal.classList.add('hidden');
            });

            addMunicipioForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const nuevoMunicipio = document.getElementById('newMunicipio').value;
                const departamentoId = departamentoSelect.value;

                if (nuevoMunicipio && departamentoId) {
                    fetch(`${baseUrl}/municipios/${departamentoId}/add`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ nombre: nuevoMunicipio })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const option = document.createElement('option');
                        option.value = data.id;
                        option.textContent = data.nombre;
                        ciudadSelect.appendChild(option);
                        addMunicipioModal.classList.add('hidden');
                        addMunicipioForm.reset();
                    })
                    .catch(error => {
                        console.error('Error adding municipio:', error);
                        alert('Ocurrió un error al cargar los municipios.');
                    });
                }
            });
        });
    </script>
@stop








