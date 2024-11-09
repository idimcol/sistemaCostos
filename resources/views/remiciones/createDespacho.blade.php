@extends('adminlte::page')

@section('title', 'Crear remisión|Despacho')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Crear remisión de despacho') }}
</h2>
@stop

@section('content')
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('remision.despacho.store') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf

                    <div class="">
                        <select name="cliente_id" id="cliente_id" class="form-control">
                            <option value="" id="search">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->nit }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="">
                        <select name="sdp_id" id="sdp_id" class="form-control">
                            <option value="" selected disabled id="search">Seleccione una sdp</option>
                        </select>
                    </div>

                    <div class="items-container" id="items-container">
                        <h1><b>Items</b></h1>
                        <button id="agregar_item" type="button" class="btn btn-primary mb-4">Agregar item </button>
                    </div>

                    <div class="">
                        <label for="fecha_despacho" class="form-label">Fecha de despacho</label>
                        <input type="date" name="fecha_despacho" class="form-control">
                    </div>

                    <div>
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <input type="text" name="observaciones" class="form-control">
                    </div>

                    <div class="">
                        <label for="despacho" class="form-label">Despachado por</label>
                        <input type="text" name="despacho" class="form-control">
                    </div>

                    <div class="">
                        <select name="departamento" id="departamento" class="form-control">
                            <option selected disabled >Seleccione un departamento</option>
                            @foreach (App\Enums\Departamento::cases() as $departamento)
                                <option value="{{ $departamento->value }}">{{ $departamento->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="">
                        <label for="recibido" class="form-label">Recibido por</label>
                        <input type="text" name="recibido" class="form-control">
                    </div>

                    <div class="">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('remision.despacho') }}" class="btn btn-secondary">cancelar</a>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        .card-body {
            background: #afaeae;
        }
        select{
            height: 38px !important;
            padding: 5px !important;
        }
        h1 {
            text-align: center;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = '{{ Url('/') }}'
            const clienteSelect = document.getElementById('cliente_id');
            const sdpSelect = document.getElementById('sdp_id');

            clienteSelect.addEventListener('change', function() {
                const clienteId = this.value;

                
                sdpSelect.innerHTML = '<option value="">Seleccione o busque una SDP</option>';

                if (clienteId) {
                    
                    fetch(`${baseUrl}/cliente/${clienteId}/sdps`)
                        .then(response => response.json())
                        .then(sdps => {
                            console.log(sdps);
                            
                            sdps.forEach(sdp => {
                                const option = document.createElement('option');
                                option.value = sdp.numero_sdp;
                                option.text = `${sdp.numero_sdp} - ${sdp.clientes.nombre}`;
                                sdpSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error al obtener las SDPs:', error));
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = '{{ Url('/') }}'
            let itemIndex = 0;

            function addItemRow() {
                const container = document.getElementById('items-container');
                const row = document.createElement('div');
                row.classList.add('row', 'item-row', 'mb-2');
                row.innerHTML = `
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

                    <div class="mb-4 px-10">
                        <button type="button" class="btn btn-danger btn-remove-item mb-4">Eliminar</button>
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
        });
    </script>
@stop