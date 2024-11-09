@extends('adminlte::page')

@section('title', 'crear SSE')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('crear  Solicitud de Servicio Externo') }}
</h2>
@stop

@section('content')
    <div class="container">
        @if (session('error'))
            <div id="success-message" class="alert alert-danger" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="{{ route('SSE.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf

                    <div class="form-group">
                        <label for="numero_ste" class="form-label">numero SSE</label>
                        <input type="text" name="numero_ste" value="{{ $nuevoNumeroSTE }}" class="form-control" readonly>
                    </div>

                    <div class="form-group"><label for=""></label>
                        <select name="proveedor_id" id="" class="form-select">
                            <option selected="false" disabled>Seleccione el proveedor</option>
                            @foreach ( $proveedores as $proveedor )
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group"><label for=""></label>
                        <select name="ordenCompra_id" id="" class="form-select">
                            <option selected="false" disabled>Seleccione la orden de compra</option>
                            @foreach ($ordenesCompra as $orden)
                                <option value="{{ $orden->numero }}">{{ $orden->numero }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">Direccion</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">Nit</label>
                        <input type="text" name="nit" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">Contacto</label>
                        <input type="text" name="contacto" class="form-control">
                    </div>

                    <div class="items-container group-form mb-4" id="items-container">
                        <h1><b>Items</b></h1>
                        <button id="agregar_item" type="button" class="btn btn-primary mb-4">Agregar item </button>
                    </div>

                    <div class="form-group">
                        <label for="">observaciones</label>
                        <input type="text" name="observaciones" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="despacho">despachado por</label>
                        <input type="text" name="despacho" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="departamento">Departamento</label>
                        <select name="departamento" id="departamento" class="form-select" required>
                            @foreach (App\Enums\Departamento::cases() as $departamento)
                                <option value="{{ $departamento->value }}">{{ $departamento->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_salida_planta" class="form-label">Fecha de salida de planta</label>
                        <input type="date" name="fecha_salida_planta" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="recibido">recibido por</label>
                        <input type="text" name="recibido" class="form-control">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">guardar</button>
                        <a href="{{ route('SSE.index') }}" class="btn btn-secondary">cancelar</a>
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

                        <div class="form-group">
                            <label for="">Servicio requerido</label>
                            <input type="text" class="form-control" name="servicio_requerido">
                        </div>
                        
                        <div class="form-group">
                            <label for="">Dureza HRC</label>
                            <input type="text" class="form-control" name="dureza_HRC">
                        </div>

                        <button type="submit" class="btn btn-primary">guardar</button>

                    </form>
                </div>
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
        .card-body, .card, .modal-content{
            background: #909090 !important;
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
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = '{{ Url('/') }}'
            let itemIndex = 0;

            function addItemRow() {
                const container = document.getElementById('items-container');
                const row = document.createElement('div');
                row.classList.add('row', 'item-row', 'mb-2');
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
                        
                        <div class="form-group">
                            <label for="">Servicio requerido</label>
                            <input type="text" class="form-control item-servicio_requerido" name="items[${itemIndex}][servicio_requerido]" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">Dureza HRC</label>
                            <input type="text" class="form-control item-dureza_HRC" name="items[${itemIndex}][dureza_HRC]" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">Descripcion</label>
                            <input type="text" class="form-control item-descripcion" name="items[${itemIndex}][descripcion]" placeholder="Descripción" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">Cantidad</label>
                            <input type="number" class="form-control item-cantidad" name="items[${itemIndex}][cantidad]" placeholder="Cantidad">
                            <input type="hidden" name="items[${itemIndex}][id]" class="item-id">
                        </div>

                        <div class="form-group">
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
                    const servicio_requerido = selectedOption.dataset.servicio_requerido;
                    const dureza_HRC = selectedOption.dataset.dureza_HRC;
                    const cantidad = selectedOption.dataset.cantidad;

                    
                    row.querySelector('.item-descripcion').value = descripcion;
                    row.querySelector('.item-servicio_requerido').value = servicio_requerido;
                    row.querySelector('.item-dureza_HRC').value = dureza_HRC;
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
                        fetch(`${baseUrl}/api/buscar-item-ste?q=${encodeURIComponent(query)}`)
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
                                            row.querySelector('input[name$="[servicio_requerido]"]').value = item.servicio_requerido;
                                            row.querySelector('input[name$="[dureza_HRC]"]').value = item.dureza_HRC;
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
    
                fetch('{{ route("item-ste.store") }}', {
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