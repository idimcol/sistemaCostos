@extends('adminlte::page')

@section('title', 'crear sdp')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Formulario del nuevo sdp') }}
</h2>
@stop

@section('content')
<div class="container">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sdp.store') }}" method="POST"  class="max-w-sm mx-auto space-y-4">
                @csrf
            
                <div class="form-group">
                    <label for="numero_sdp" class="form-label">Número de SDP</label>
                    <input type="text" name="numero_sdp" id="numero_sdp" value="{{ $nuevoNumeroSDP }}" class="form-control" readonly>
                </div>

                <div class="">
                    <label for="vendedor" class="form-label">Vendedor</label>
                    <div class="">
                        <select name="vendedor_id" id="vendedor_id" class="form-select" required>
                            <option value="">Seleccione un vendedor</option>
                            @foreach ($vendedores as $vendedor)
                                <option value="{{ $vendedor->id }}">{{ $vendedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="">
                    <label for="cliente" class="form-label">Cliente</label>
                    <div class="">
                        <select name="cliente_nit" id="cliente_nit" class="form-select" required>
                            <option value="">Seleccione un cliente</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control">
                </div>

                <div class="form-group ">
                    <h1>Artículos</h1>
                    <div id="articulos-container"></div>
                    <button type="button" id="btn-add-articulo" class="btn btn-primary mt-2">Agregar Artículo</button>
                </div>
            
                <div class="form-group">
                    <label for="fecha_despacho_comercial" class="form-label">Fecha de facturacion</label>
                    <input type="date" name="fecha_despacho_comercial" id="fecha_despacho_comercial" class="form-control" required>
                </div>
            
                <div class="form-group">
                    <label for="fecha_despacho_produccion" class="form-label">Fecha Despacho Producción</label>
                    <input type="date" name="fecha_despacho_produccion" id="fecha_despacho_produccion" class="form-control" required>
                </div>
            
                <div class="form-group">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" id="observaciones" class="form-control">
                </div>
            
                <div class="form-group">
                    <label for="orden_compra" class="form-label">Orden de Compra</label>
                    <input type="text" name="orden_compra" id="orden_compra" class="form-control">
                </div>
            
                <div class="form-group">
                    <label for="memoria_calculo" class="form-label">Memoria de Cálculo</label>
                    <input type="text" name="memoria_calculo" id="memoria_calculo" class="form-control">
                </div>
            
                <div class="form-group">
                    <label for="requisitos_cliente" class="form-label">Requisitos Cliente</label>
                    <input type="text" name="requisitos_cliente" id="requisitos_cliente" class="form-control">
                </div>
            
                <div class="buttons">
                    <button type="submit"  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                    <a href="{{ route('sdp.paquetes') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="crearArticuloModal" tabindex="-1" aria-labelledby="crearArticuloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearArticuloModalLabel">Crear Artículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="crearArticuloForm">
                    @csrf
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="material">Material</label>
                        <input type="text" name="material" id="material" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="plano">Plano</label>
                        <input type="text" name="plano" id="plano" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Artículo</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    <style>
        .modal-body, .modal-header {
            background: #b1b1b1 !important;
        }
        .flext {
            display: flex;
        }

        .relatives {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .relatives input {
            width: 300px;
            height: 40px;

        }

        .relatives button {
            width: 100px;
            height: 60px;
            text-align: center;
            padding: 10px;
        }

        .buttons {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .py-12 {
            padding-top: 3rem /* 48px */;
            padding-bottom: 3rem /* 48px */;
        }

        form {
            max-width: 24rem;
            margin-left: auto;
            margin-right: auto;
        }

        .space-y-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(1rem /* 16px */ * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(1rem /* 16px */ * var(--tw-space-y-reverse));
        }

        input.form-control::placeholder {
            color: #6b6a6a;
        }

        input {
            color: #fff;
        }

        .card-body label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem /* 14px */;
            line-height: 1.25rem /* 20px */;
            font-weight: 500;
            --tw-text-opacity: 1;
            color: rgb(243 244 246 / var(--tw-text-opacity)) /* #f3f4f6 */;
        }

        .card-body input, .card-body select  {
            --tw-bg-opacity: 1;
            background-color: rgb(17 24 39 / var(--tw-bg-opacity)) /* #111827 */;
            border-width: 1px;
            --tw-border-opacity: 1;
            border-color: rgb(209 213 219 / var(--tw-border-opacity)) /* #d1d5db */;
            --tw-text-opacity: 1;
            color: rgb(243 244 246 / var(--tw-text-opacity)) /* #f3f4f6 */;
            font-size: 0.875rem /* 14px */;
            line-height: 1.25rem /* 20px */;
            border-radius: 0.5rem /* 8px */;
            width: 100%;
        }

        .card-body input.focus\:ring-blue-500:focus, .card-body select.focus\:ring-blue-500:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(59 130 246 / var(--tw-ring-opacity)) /* #3b82f6 */;
        }
        .card-body input.p-2\.5, .card-body select.p-2\.5 {
            padding: 0.625rem /* 10px */;
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

        .content, .content-header {
            background: #fff !important;
        }

        h2 {
            text-transform: uppercase;
            font-size: 18px;
        }

        h1 {
            text-transform: uppercase;
            text-align: center;
        }

        .card, .card-body {
            background: #b1b1b1 !important;
            color: #000 !important;
        }

        input, select {
            background: #fff !important;
            color: #000 !important;
        }

        label {
            color: #000 !important;
        }

    </style>
@stop

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
            let articuloIndex = 0;
            const baseUrl = '{{ url('/') }}';
    
            // Añadir una fila de artículo
            function addArticuloRow() {
                const container = document.getElementById('articulos-container');
                const row = document.createElement('div');
                row.classList.add('row', 'articulo-row', 'mb-2');
                row.innerHTML = `
                    <div class="mb-4">
                        <div>
                            <input type="text" class="form-control articulo-buscar mb-4" placeholder="Buscar artículo...">
                            <div class="suggestions-container"></div>
                        </div>
                        
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#crearArticuloModal">
                            Crear Nuevo Artículo
                        </button>
                    </div>

                    <div class="mb-4">
                        <input type="text" class="form-control articulo-descripcion"  name="articulos[${articuloIndex}][descripcion]" placeholder="Descripción" readonly>
                    </div>

                    <div class="mb-4">
                        <input type="text" class="form-control articulo-material" name="articulos[${articuloIndex}][material]" placeholder="Material" readonly>
                    </div>

                    <div class="mb-4">
                        <input type="text" class="form-control articulo-plano" placeholder="Plano" name="articulos[${articuloIndex}][plano]" readonly>
                    </div>

                    <div class="mb-4">
                        <input type="number" class="form-control articulo-cantidad" name="articulos[${articuloIndex}][cantidad]" placeholder="Cantidad" min="1">
                        <input type="hidden" name="articulos[${articuloIndex}][id]" class="articulo-id">
                    </div>

                    <div class="mb-4">
                        <input type="number" class="form-control articulo-precio" name="articulos[${articuloIndex}][precio]" placeholder="Precio" min="0" step="0.01">
                    </div>

                    <div class="mb-4 px-10">
                        <button type="button" class="btn btn-danger btn-remove-articulo mb-4">Eliminar</button>
                    </div>
                `;
                container.appendChild(row);
                articuloIndex++;
            }
    
            // Manejar el clic en el botón para agregar un artículo
            document.getElementById('btn-add-articulo').addEventListener('click', addArticuloRow);
    
            // Manejar la eliminación de una fila de artículo
            document.getElementById('articulos-container').addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-remove-articulo')) {
                    event.target.closest('.articulo-row').remove();
                }
            });
    
            // Lógica para buscar y autocompletar artículos
            document.getElementById('articulos-container').addEventListener('input', function(event) {
                if (event.target.classList.contains('articulo-buscar')) {
                    let query = event.target.value;
                    if (query.length > 2) {
                        fetch(`${baseUrl}/api/buscar-articulos?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                let suggestionsContainer = event.target.nextElementSibling;
                                
                                // Limpiar cualquier lista de sugerencias anterior
                                suggestionsContainer.innerHTML = '';

                                if (data.length > 0) {
                                    // Crear una lista de sugerencias
                                    let ul = document.createElement('ul');
                                    ul.classList.add('suggestions-list');

                                    data.forEach(articulo => {
                                        let li = document.createElement('li');
                                        li.textContent = articulo.descripcion; // Mostrar la descripción del artículo
                                        li.addEventListener('click', function() {
                                            let row = event.target.closest('.articulo-row');

                                            // Completar los campos del artículo con los datos seleccionados
                                            row.querySelector('input[name$="[descripcion]"]').value = articulo.descripcion;
                                            row.querySelector('input[name$="[material]"]').value = articulo.material;
                                            row.querySelector('input[name$="[plano]"]').value = articulo.plano;
                                            row.querySelector('input[name$="[precio]"]').value = articulo.precio;
                                            row.querySelector('input[name$="[id]"]').value = articulo.id; // Asignar ID del artículo

                                            // Limpiar la lista de sugerencias después de seleccionar un artículo
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

            // Función para obtener el ID del SDP actual
            function getCurrentSdpId() {
                // Implementa la lógica para obtener el ID del SDP actual
                // Por ejemplo, si está en un campo oculto en el formulario:
                return document.getElementById('sdp_id').value;
            }
    
            // Enviar el formulario para crear un artículo
            document.getElementById('crearArticuloForm').addEventListener('submit', function(event) {
                event.preventDefault();
    
                let formData = new FormData(event.target);
    
                fetch('{{ route("articulos.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#crearArticuloModal').modal('hide');
                        event.target.reset();
                        alert('Artículo creado exitosamente.');
                    }else if (data.errors) {
                        // Si hay errores de validación, podrías mostrarlos en la interfaz de usuario
                        alert('Errores de validación: ' + JSON.stringify(data.errors));
                    }else {
                        alert('Error al crear el artículo: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al procesar la solicitud.');
                });
            });
        });
    </script>
    <script>
        // Esta variable contiene los clientes por vendedor
        const clientesPorVendedor = @json($clientesPorVendedor);
    
        // Seleccionamos los elementos del DOM
        const vendedorSelect = document.getElementById('vendedor_id');
        const clienteSelect = document.getElementById('cliente_nit');
    
        // Evento cuando se selecciona un vendedor
        vendedorSelect.addEventListener('change', function() {
            const vendedorId = this.value;
    
            // Limpiar las opciones del cliente
            clienteSelect.innerHTML = '<option value="">Seleccione un cliente</option>';
    
            // Si se seleccionó un vendedor
            if (vendedorId && clientesPorVendedor[vendedorId]) {

                const clientesOrdenados = clientesPorVendedor[vendedorId].sort((a, b) => 
                    a.nombre.localeCompare(b.nombre)
                );
                // Agregar las opciones de clientes asociados al vendedor
                clientesPorVendedor[vendedorId].forEach(function(cliente) {
                    const option = document.createElement('option');
                    option.value = cliente.nit;
                    option.textContent = cliente.nombre;
                    clienteSelect.appendChild(option);
                });
            }
        });
    </script>
@stop
