@extends('adminlte::page')

@section('title', 'editar servicio')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Formulario para actualizar servicio') }}
    </h2>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900" for="nombre">nombre</label>
                                <input type="text" name="nombre" id="nombre" value="{{ $servicio->nombre }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
            
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900" for="cantidad">valor por hora</label>
                                <input type="number" name="valor_hora" id="valor_hora" value="{{ $servicio->valor_hora }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
            
                            <div>
                                <label for="sdp_id" class="form-label">SDP</label>
                                <button type="button" id="selectAllBtn" class="btn btn-secondary btn-sm mb-2 mt-2">Seleccionar todos</button>
                                <select name="sdp_id[]" id="sdp_id" class="form-select" multiple>
                                    @foreach ($sdps as $sdp)
                                        <option value="{{ $sdp->numero_sdp }}"
                                            {{ in_array($sdp->numero_sdp, $sdpSelect) ? 'selected' : '' }}
                                            >{{ $sdp->numero_sdp }}-{{ $sdp->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">Guardar</button>
                    <a href="{{ route('servicios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</a>
                </div>
            </form>
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
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
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
    $(document).ready(function() {
        // Inicializar select2
        $('#sdp_id').select2({
            theme: 'bootstrap-5'
        });

        // Manejar el botón de seleccionar/deseleccionar todas las opciones
        $('#selectAllBtn').on('click', function() {
            let allSelected = $('#sdp_id option').length === $('#sdp_id').val().length;
            
            if (allSelected) {
                // Deseleccionar todas si todas ya están seleccionadas
                $('#sdp_id').val(null).trigger('change');
                $(this).text('Seleccionar todos');
            } else {
                // Seleccionar todas las opciones
                $('#sdp_id').val($('#sdp_id option').map(function() {
                    return $(this).val();
                }).get()).trigger('change');
                $(this).text('Deseleccionar todos');
            }
        });
    });
</script>
@stop









