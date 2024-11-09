@extends('adminlte::page')

@section('title', 'Editar remision|Despacho')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('editar remision de despacho') }}
</h2>
@stop

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('remision.despacho.update', $remisionDespacho->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    @method('PUT')
    
                    <div class="">
                        <label for="" class="form-label">Cliente</label>
                        <input type="text" class="form-control mb-4" value="{{ $remisionDespacho->cliente->nombre }}" readonly>
                        <input type="text" class="form-control" name="cliente_id" value="{{ $remisionDespacho->cliente->nit }}" readonly>
                    </div>

                    <div class="">
                        <label for="" class="form-label">Sdp</label>
                        <input type="text" class="form-control" name="sdp_id" id="sdp_id" value="{{ $remisionDespacho->sdp->numero_sdp }}" readonly>
                    </div>

                    <div class="items-container" id="items-container">
                        <h1 class="text-center"><b>Items</b></h1>
                        @foreach ($remisionDespacho->items as $item)
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

                    <div class="">
                        <label for="fecha_despacho" class="form-label">Fecha de despacho</label>
                        <input type="date" name="fecha_despacho" value="{{ $remisionDespacho->fecha_despacho }}" class="form-control">
                    </div>

                    <div>
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <input type="text" name="observaciones" value="{{ $remisionDespacho->observaciones }}" class="form-control">
                    </div>

                    <div class="">
                        <label for="despacho" class="form-label">Despachado por</label>
                        <input type="text" name="despacho" value="{{ $remisionDespacho->despacho }}" class="form-control">
                    </div>

                    <div class="">
                        <select name="departamento" id="departamento" class="form-control">
                            <option selected disabled >Seleccione un departamento</option>
                            @foreach (App\Enums\Departamento::cases() as $departamento)
                                <option value="{{ $departamento->value }}"
                                    {{ $remisionDespacho->departamento === $departamento ? 'selected': ''}}
                                    >
                                    {{ $departamento->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="">
                        <label for="recibido" class="form-label">Recibido por</label>
                        <input type="text" name="recibido" value="{{ $remisionDespacho->recibido }}" class="form-control">
                    </div>
    
                    <div class="flex items-center justify-between mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('remision.despacho') }}" class="btn btn-secondary">Cancelar</a>
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
    <style>
        .card-body {
            background: #939393;
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
                row.classList.add('row', 'item-row', 'mb-2');
                row.innerHTML = `
                    <div class="form-group item">
                        <div class="mb-4">
                            <select name="items[${itemIndex}][articulo_id]" class="form-control item-select" id="item-select-${itemIndex}">
                                <option value="" selected disabled>Seleccione uno de los item que pertenece a la SDP</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <input type="text" class="form-control item-descripcion" name="items[${itemIndex}][descripcion]" placeholder="Descripción" readonly>
                        </div>

                        <div class="mb-4">
                            <input type="number" class="form-control item-cantidad" name="items[${itemIndex}][cantidad]" placeholder="Cantidad">
                            <input type="hidden" name="items[${itemIndex}][id]" class="item-id">
                        </div>

                        <div class="mb-4">
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

                populateArticuloSelect(newItemSelect);
            }

            function populateArticuloSelect(selectElement) {
                const sdpId = document.getElementById('sdp_id').value;

                if (sdpId) {
                    fetch(`${baseUrl}/api/getArticulos/${sdpId}`)
                        .then(response => response.json())
                        .then(articulos => {
                            articulos.forEach(articulo => {
                                const option = new Option(`${articulo.codigo} - ${articulo.descripcion}`, articulo.id);
                                option.dataset.descripcion = articulo.descripcion;
                                option.dataset.cantidad = articulo.cantidad;
                                selectElement.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching articles:', error));
                }
            }

            document.getElementById('agregar_item').addEventListener('click', addItemRow);

            document.getElementById('items-container').addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-remove-item')) {
                    event.target.closest('.item-row').remove();
                }
            });

            const itemSelects = document.querySelectorAll('.item-select');
                itemSelects.forEach(select => {
                    populateArticuloSelect(select);
                });
        });
    </script>
@stop