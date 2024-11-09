@extends('adminlte::page')

@section('title', 'editar remision de ingreso')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('editar remision de ingreso') }}
</h2>
@stop

@section('content')
<div class="container">
    @if (session('error'))
        <div id="success-message" class="alert alert-danger success-message" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('remision.ingreso.update', $remisionIngreso->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf
                @method('PUT')

                <div class="group-form">
                    <label for="proveedor_id" class="form-label">Proveedor / cliente</label>
                    <input type="text" class="form-control mb-4" value="{{ $remisionIngreso->proveedor->nombre ?? '' }}" readonly>
                    <input type="text" name="proveedor_id" class="form-control" value="{{ $remisionIngreso->proveedor_id ?? '' }}" readonly>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control mb-4" value="{{ $remisionIngreso->cliente->nombre ?? '' }}" readonly>
                    <input type="text" name="cliente_nit" class="form-control" value="{{ $remisionIngreso->cliente_nit ?? '' }}" readonly>
                </div>

                <div class="group-form">
                    <label for="sdp_id" class="form-label">SDP</label>
                    <input type="text" name="sdp_id" class="form-control" value="{{ $remisionIngreso->sdp->numero_sdp }}">
                </div>

                <div class="items-container group-form mb-4" id="items-container">
                    <h1><b>Items</b></h1>
                    @foreach ($remisionIngreso->items as $item)
                            <div class="form-group item">

                                <div class="mb-4">
                                    <label for="item_{{ $loop->index }}_descripcion">Descripcion</label>
                                    <input type="text" class="form-control" name="items[{{ $loop->index }}][descripcion]" id="item_{{ $loop->index }}_descripcion" value="{{ old('items.' .$loop->index . '.descripcion', $item->descripcion) }}">
                                </div>

                                <div class="mb-4">
                                    <label for="item_{{ $loop->index }}_cantidad">Cantidad</label>
                                    <input type="text" class="form-control" name="items[{{ $loop->index }}][cantidad]" id="item_{{ $loop->index }}_cantidad" value="{{ old('items.' .$loop->index . '.cantidad', $item->pivot->cantidad) }}">
                                </div>

                                <div class="mb-4">
                                    <button type="button" class="btn btn-danger btn-remove-item mb-4">Eliminar</button>
                                </div>

                            </div>
                        @endforeach
                    <button id="agregar_item" type="button" class="btn btn-primary mb-4">Agregar item </button>
                </div>

                <div class="group-form">
                    <label for="fecha_ingreso" class="form-label">Fecha de ingreso a planta</label>
                    <input type="date" name="fecha_ingreso" class="form-control" value="{{ $remisionIngreso->fecha_ingreso }}" required>
                </div>

                <div class="group-form">
                    <label for="" class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control" value="{{ $remisionIngreso->observaciones }}">
                </div>

                <div class="group-form">
                    <label for="despacho" class="form-label">Entregado por</label>
                    <input type="text" name="despacho" class="form-control" value="{{ $remisionIngreso->despacho }}">
                </div>

                <div class="group-form">
                    <label for="" class="form-label">Departamento</label>
                    <select name="departamento" id="" class="form-control">
                        <option selected disabled >Seleccione el departamento</option>
                        @foreach (App\Enums\Departamento::cases() as $departamento)
                            <option value="{{ $departamento->value }}"
                                {{ $remisionIngreso->departamento === $departamento ? 'selected': ''}}
                                >{{ $departamento->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="group-form">
                    <label for="" class="form-label">Recibido por</label>
                    <input type="text" name="recibido" class="form-control" value="{{ $remisionIngreso->recibido }}">
                </div>

                <div class="group-form">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('remision.ingreso') }}" class="btn btn-secondary">cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="crearItemModal" tabindex="-1" aria-labelledby="crearItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="crearItemModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="crearitemForm">

                    @csrf

                    <div class="mb-4">
                        <label for="" class="form-label">Descripcion</label>
                        <input type="text" class="form-control" name="descripcion">
                    </div>

                    <button type="submit" class="btn btn-primary">guardar</button>

                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .modal-content {
            background: #afaeae;
        }
        .card-body {
            background: #afaeae;
        }
        .suggestions-list {
            list-style-type: none; /* Elimina las viñetas de la lista */
            padding: 0;
            margin: 0;
            border: 1px solid #ddd;
            max-height: 150px; /* Altura máxima de la lista */
            overflow-y: auto; /* Muestra una barra de desplazamiento si es necesario */
            background-color: #fff;
            position: absolute; /* Posicionamiento absoluto para la lista */
            width: 500px; /* Asegura que la lista ocupe todo el ancho del input */
            z-index: 1000; /* Asegura que la lista aparezca sobre otros elementos */
            color: #000;
        }

        .suggestions-list li {
            padding: 8px; /* Espaciado interno de cada ítem */
            cursor: pointer;
        }

        .suggestions-list li:hover {
            background-color: #f0f0f0; /* Color de fondo al pasar el ratón */
            color: #000;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = '{{ Url('/') }}'
            let itemIndex = 0;

            function addItemRow() {
                const container = document.getElementById('items-container');
                const row = document.createElement('div');
                row.classList.add('row', 'item-row', 'mb-4');
                row.innerHTML = `

                    <div>
                        <div class="group-form mb-4">

                            <div class="mb-4">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearItemModal">
                                    Crear item
                                </button>
                            </div>

                            <input type="text" class="form-control searchItem" id="searchItem" placeholder="Buscar item creado...">
                            <div class="suggestions-container"></div>
                        </div>

                        <div class=group-form mb-4">
                            <input type="text" class="form-control item-descripcion" name="items[${itemIndex}][descripcion]" placeholder="Descripción" readonly>
                        </div>

                        <div class="group-form mb-4">
                            <input type="number" class="form-control item-cantidad mb-4" name="items[${itemIndex}][cantidad]" placeholder="Cantidad">
                            <input type="hidden" name="items[${itemIndex}][id]" class="item-id mb-4">
                        </div>

                        <div class="group-form mb-4">
                            <button type="button" class="btn btn-danger btn-remove-item mb-4">Eliminar</button>
                        </div>
                    </div>
                `;
                container.appendChild(row);
                itemIndex++;

                const newItemSelect = row.querySelector('.item-select');
                newItemSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const descripcion = selectedOption.dataset.descripcion;
                    const cantidad = selectedOption.dataset.cantidad;

                    // Rellenar los campos de descripción y cantidad
                    row.querySelector('.item-descripcion').value = descripcion;
                    row.querySelector('.item-cantidad').value = cantidad;
                });
            }

            document.getElementById('agregar_item').addEventListener('click', addItemRow);

            document.getElementById('items-container').addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-remove-item')) {
                    event.target.closest('.item-row').remove();
                }
            });

            document.getElementById('items-container').addEventListener('input', function(event) {
                if (event.target.classList.contains('searchItem')) {
                    let query = event.target.value;
                    if (query.length > 2) {
                        fetch(`${baseUrl}/api/buscar-items?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                let suggestionsContainer = event.target.nextElementSibling;
                                
                                // Limpiar cualquier lista de sugerencias anterior
                                suggestionsContainer.innerHTML = '';

                                if (data.length > 0) {
                                    // Crear una lista de sugerencias
                                    let ul = document.createElement('ul');
                                    ul.classList.add('suggestions-list');

                                    data.forEach(item => {
                                        let li = document.createElement('li');
                                        li.textContent = item.descripcion;
                                        li.addEventListener('click', function() {
                                            let row = event.target.closest('.item-row');

                                            row.querySelector('input[name$="[descripcion]"]').value = item.descripcion;
                                            row.querySelector('input[name$="[id]"]').value = item.id;

                                            suggestionsContainer.innerHTML = '';
                                        });

                                        ul.appendChild(li);
                                    });

                                    // Añadir la lista de sugerencias al contenedor correspondiente
                                    suggestionsContainer.appendChild(ul);
                                }
                            });
                    } else {
                        // Limpiar la lista de sugerencias si el query es menor de 3 caracteres
                        let suggestionsContainer = event.target.nextElementSibling;
                        suggestionsContainer.innerHTML = '';
                    }
                }
            });

            document.getElementById('crearitemForm').addEventListener('submit', function(event) {
                event.preventDefault();
    
                let formData = new FormData(event.target);
    
                fetch('{{ route("items.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#crearItemModal').modal('hide');
                        event.target.reset();
                        alert('Item creado exitosamente.');
                    }else if (data.errors) {
                        // Si hay errores de validación, podrías mostrarlos en la interfaz de usuario
                        alert('Errores de validación: ' + JSON.stringify(data.errors));
                    }else {
                        alert('Error al crear el Item: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al procesar la solicitud.');
                });
            });
        });
    </script>
@stop