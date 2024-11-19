@extends('adminlte::page')

@section('title', 'Editar orden de compra')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Editar orden de compra') }}
</h2>
@stop

@section('content')
    <div class="container">
        @if (session('error'))
            <div id="error-message" class="alert alert-danger success-message" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="{{ route('Ordencompras.update', $ordenCompra->numero) }}" method="POST" class="max-w-sm mx-auto space-y-4 mb-4">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="proveedor_id" class="form-label">proveedor</label>
                        <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                            <option>Seleccione un proveedor</option>
                            @foreach ( $proveedores as $proveedor )
                                <option value="{{ $proveedor->nit }}"
                                    {{ $proveedor->nit == old('proveedor_id', $ordenCompra->proveedor_id) ? 'selected' : '' }}
                                    >{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_orden" class="form-label">Fecha de entrega</label>
                        <input type="date" name="fecha_orden" value="{{ $ordenCompra->fecha_orden }}" class="form-control" required>
                    </div>

                    <div class="">
                        <h1 class="text-center">Items</h1>
                        <div class="items_container" id="items_container">
                            @foreach ($itemsOrden as $item)
                                <div class= 'item'>                
                                    <div class="mb-4"> 
                                        <label for="item_{{ $loop->index }}_descripcion">Descripción</label>
                                        <input type="text" class="form-control"  name="items[{{ $loop->index }}][descripcion]" id="item_{{ $loop->index }}_descripcion" placeholder="Descripción" value="{{ old('items.' . $loop->index . '.descripcion', $item->descripcion) }}" readonly>
                                    </div>
                
                                    <div class="mb-4">
                                        <label for="item_{{ $loop->index }}_cantidad">Cantidad</label>
                                        <input type="number" class="form-control" name="items[{{ $loop->index }}][cantidad]" placeholder="Cantidad" id="item_{{ $loop->index }}_cantidad" value="{{ old('items.' . $loop->index . '.cantidad', $item->pivot->cantidad) }}" min="1" required>
                                    </div>
                
                                    <div class="mb-4">
                                        <label for="item_{{ $loop->index }}_precio">precio</label>
                                        <input type="number" class="form-control" name="items[{{ $loop->index }}][precio]" placeholder="Precio" min="0" step="0.01" id="item_{{ $loop->index }}_precio" value="{{ old('items.' . $loop->index . '.precio', $item->pivot->precio) }}" required>
                                    </div>
                
                                    <div class="mb-4 px-10">
                                        <button type="button" class="btn btn-danger btn-remove-item mb-4">Eliminar</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="btn-add-item" class="btn btn-primary mt-2 add-item">Agregar item</button>
                    </div>

                    <div class="form-group">
                        <label for="elaboracion" class="form-label">Elaborado por</label>
                        <input type="text" name="elaboracion" value="{{ $ordenCompra->elaboracion }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="autorizacion" class="form-label">Autorizado por</label>
                        <input type="text" name="autorizacion" value="{{ $ordenCompra->autorizacion }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="iva" class="form-label">IVA (porcentaje en decimal)</label>
                        <input type="namber" name="iva" value="{{ $ordenCompra->iva }}" placeholder="iva" class="form-control" required>
                    </div>

                    <div class="">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('Ordencompras.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="crearitemModal" tabindex="-1" aria-labelledby="crearitemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crearitemModalLabel">Crear item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearitemForm">
                        <div class="form-group">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" name="codigo" id="limited-input" class="form-control" maxlength="3" placeholder="Máx. 3 caracteres" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" name="descripcion" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
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
        .card-body{
            background: #969595;
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
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = document.querySelectorAll('.item').length;
            const baseUrl = '{{ url('/') }}';
    
            // Añadir una fila de item
            document.getElementById('btn-add-item').addEventListener('click', function () {
                const container = document.getElementById('items_container');
                
                const newItemHtml = `
                    <div class="item">
                        <div class="mb-4">
                            <div>
                                <input type="text" class="form-control item-buscar mb-4" id="item-buscar" placeholder="Buscar item...">
                                <div class="suggestions-container"></div>
                            </div>
                            
                            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#crearitemModal">
                                Crear Nuevo item
                            </button>
                        </div>

                        <div class="mb-4"> 
                            <label for="item_${itemIndex}_descripcion">Descripción</label>
                            <input type="text" class="form-control item-descripcion" id="item_${itemIndex}_descripcion"  name="items[${itemIndex}][descripcion]" placeholder="Descripción" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="item_${itemIndex}_cantidad">Cantidad</label>
                            <input type="number" class="form-control item-cantidad" id="item_${itemIndex}_cantidad" name="items[${itemIndex}][cantidad]" placeholder="Cantidad" min="1">
                        </div>

                        <div class="mb-4">
                            <label for="item_${itemIndex}_precio">precio</label>
                            <input type="number" class="form-control item-precio" id="item_${itemIndex}_precio" name="items[${itemIndex}][precio]" placeholder="Precio" min="0" step="0.01">
                        </div>

                        <div class="mb-4 px-10">
                            <button type="button" class="btn btn-danger btn-remove-item mb-4">Eliminar</button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', newItemHtml);
                itemIndex++;
            });
    
            // Manejar la eliminación de una fila de item
            document.getElementById('items_container').addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-remove-item')) {
                    event.target.closest('.item').remove();
                }
            });
    
            // Lógica para buscar y autocompletar items
            document.getElementById('items_container').addEventListener('input', function(event) {
                if (event.target.classList.contains('item-buscar')) {
                    let query = event.target.value;
                    if (query.length > 2) {
                        fetch(`${baseUrl}/api/buscar-items-orden-compra?q=${encodeURIComponent(query)}`)
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
                                            let row = event.target.closest('.item');

                                            row.querySelector('input[name$="[descripcion]"]').value = item.descripcion; 

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

            document.getElementById('crearitemForm').addEventListener('submit', function(event) {
                event.preventDefault();
    
                let formData = new FormData(event.target);
    
                fetch('{{ route("itemOrden.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('item creado exitosamente.');
                        $('#crearitemModal').modal('hide');
                        event.target.reset();
                    }else if (data.errors) {
                        // Si hay errores de validación, podrías mostrarlos en la interfaz de usuario
                        alert('Errores de validación: ' + JSON.stringify(data.errors));
                    }else {
                        alert('Error al crear el item: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al procesar la solicitud.');
                })
                .finally(() => {
                    $('#crearitemModal').modal('hide');
                });
            });
        });
    </script>

    <script>
        document.getElementById('limited-input').addEventListener('input', function (event) {
            const input = event.target;
            if (input.value.length > 3) {
                alert('Se ha excedido el límite de caracteres para este input');
                input.value = input.value.slice(0, 3); // Elimina caracteres excedentes
            }
        });
    </script>
@stop