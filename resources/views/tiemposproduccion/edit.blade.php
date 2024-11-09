@extends('adminlte::page')

@section('title', 'editar tiempo de produccion')

@section('content_header')
@stop

@section('content')
<div class="py-12">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('tiempos.group') }}" class="btn btn-primary">volver</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tiempos-produccion.update', $tiempo_produccion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
        
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="codigo_operario">Código del Operario</label>
                            <input type="text" id="codigo_operario" name="operativo_id"  value="{{ old('operativo_id', $tiempo_produccion->operativo_id) }}" readonly placeholder="Código del operario">
                        </div>
        
                        <div class="mb-4">
                            <label for="nombre_operario">Nombre del Operario</label>
                            <input type="text" id="nombre_operario" name="nombre_operario" value="{{ old('nombre_operario', $tiempo_produccion->nombre_operario) }}" readonly placeholder="Nombre del operario">
                        </div>
                    </div>
        
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="dia">Día</label>
                            <select name="dia" id="dia" required>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ $tiempo_produccion->dia == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
        
                        <div class="mb-4">
                            <label for="mes">Mes</label>
                            <select name="mes" id="mes" required>
                                @foreach ([
                                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                                    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                ] as $numero => $nombre)
                                    <option value="{{ $numero }}" {{ $tiempo_produccion->mes == $numero ? 'selected' : '' }}
                                    >
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="mb-4">
                            <label for="año">Año</label>
                            <select name="año" id="año" required>
                                @for ($i = date('Y'); $i >= 1900; $i--)
                                    <option value="{{ $i }}" {{ $tiempo_produccion->año == $i ? 'selected' : '' }}
                                    >
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
        
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="hora_inicio">Hora Inicio</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', $tiempo_produccion->hora_inicio) }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="hora_fin">Hora Fin</label>
                            <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', $tiempo_produccion->hora_fin) }}" required>
                        </div>

                        
                    </div>
        
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="nombre_servicio">Proceso/Servicio</label>
                            <div class="">
                                <button id="abrirModalServicios" type="button" class="btn btn-info">ver servicios</button>
                                <input type="text" id="nombre_servicio" name="nombre_servicio" value="{{ old('nombre_servicio', $tiempo_produccion->nombre_servicio) }}" readonly placeholder="Nombre del servicio">
                            </div>
                        </div>
        
                        <div class="mb-4">
                            <label for="proseso_id">Código Servicio</label>
                            <input type="text" id="proseso_id" name="proseso_id" value="{{ old('proseso_id', $tiempo_produccion->proseso_id) }}" readonly placeholder="Código del servicio">
                        </div>
        
                        <div class="mb-4">
                            <label for="sdp_id">SDP</label>
                            <div class="">
                                <button id="abrirModalSDP" type="button" class="btn btn-info">ver SDP</button>
                            <input type="text" id="sdp_id" name="sdp_id" value="{{ old('sdp_id', $tiempo_produccion->sdp_id) }}" required placeholder="Número del SDP">
                            </div>
                            
                            <label for="articulos_sdp">Items de SDP</label>
                            <div id="articulosContainer">
                                <select name="articulos[articulo_id][]" id="articulos_sdp"  class="form-select" multiple>
                                    @foreach ($tiempo_produccion->articulos as $articulo)
                                        <option value="{{ $articulo->id }}" selected>
                                            {{ $articulo->codigo }} - {{ $articulo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
        
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modalServicios" class="modal" style="display: none;">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('lista de servicios') }}
        </h2>
        <div class="containerz">
            <div class="mb-4">
                <input type="text" id="searchServicios" placeholder="Buscar..." class="p-2 border rounded w-full">
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>select</th>
                            <th>codigo</th>
                            <th>nombre</th>
                        </tr>
                    </thead>
                    <tbody id="serviciosTableBody">
                        @foreach ($servicios as $servicio)
                        <tr>
                            <td>
                                <input type="radio" name="proseso_select" id="proseso_select" 
                                value="{{ $servicio->codigo }}" data-codigo="{{ $servicio->codigo }}" 
                                data-nombre="{{ $servicio->nombre }}">
                            </td>
                            <td>{{ $servicio->codigo }}</td>
                            <td>{{ $servicio->nombre }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="buton mb-4">
                <button type="submit" id="selectServicio" class="btn btn-primary">seleccionar servicio</button>
                <button class="btn btn-secondary cerrarModal">cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="modalSDP" class="modal" style="display: none;">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('lista de SDP') }}
        </h2>
        <div class="containerz">
            <div class="mb-4">
                <input type="text" id="searchSDP" placeholder="Buscar..." class="p-2 border rounded w-full">
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>select</th>
                            <th>codigo</th>
                            <th>descripcion</th>
                            <th>cliente</th>
                        </tr>
                    </thead>
                    <tbody id="sdpTableBody">
                        @foreach ($sdps as $sdp)
                        <tr>
                            <td>
                                <input type="radio" name="SDP_select" id="SDP_select" 
                                value="{{ $sdp->numero_sdp }}" data-codigo="{{ $sdp->numero_sdp }}" >
                            </td>
                            <td>{{ $sdp->numero_sdp }}</td>
                            <td>{{ $sdp->articulos->first()->descripcion }}</td>
                            <td>{{ $sdp->clientes->nombre }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="buton mb-4">
                <button type="submit" id="selectSDP" class="btn btn-primary">seleccionar SDP</button>
                <button class="btn btn-secondary cerrarModal">cancelar</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 20rem;
            height: 300px;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-contenido {
            background-color: #3a7280;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50rem;
            max-width: 60rem;
            border-radius: 10px;
        }

        .cerrar {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .cerrar:hover,
        .cerrar:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .containerx {
            padding: 20px;
            background: #cccdce;
            border-radius: 10px;
        }

        .table {
            background: #cccdce;
            max-height: 300px;
            overflow-y: auto; 
            padding: 10px;
            border-radius: 10px;
            display: grid;
            justify-content: center;
        }

        table {
            border: 1px #000 solid;
            width: 600px;
            border-collapse: collapse;
            border-radius: 20px;
        }
        
        table thead tr th, table tbody tr td {
            border: 1px #000 solid;
            text-align: center;
            text-transform: capitalize;
        }

        table thead tr th {
            background: #4f4b5f;
            color: #fff;
        }

        table tbody tr td {
            background: #b2c8e6;
            color: #000;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .box {
            padding: 10px;
        }

        .container {
            
        }

        form {
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 10px;
        }

        label {
            display:block;
        }

        input, select {
            padding: 10px;
            /* width: 400px; */
            display:inline-block;
            border-radius: 8px;
            background: #d0d7de !important;
            color: #000 !important;
        }

        input:focus, select:focus {
            border-color: rgb(24, 160, 160) !important;
        }


        .card, .card-body {
            border-radius: 10px;
            background: #d6d6d2 !important;
            color: #000 !important;
        }

        .ver {
            display: flex;
            flex-direction: col;
            align-items: flex-end;
            justify-content: flex-end;
        }

        .btn {
            color: #fff !important;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content{
            height: 86vh;
        }

        .operario {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 10px;

            
        }

        .servicio, .sdp {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 10px;
            
        }

        .tiempo-restar-container {
            display: flex;
            align-items: center;
        }

        .tiempo-restar-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .tiempo-restar-container button {
            padding: 5px 10px;
            margin: 0 5px;
        }

        select#articulos_sdp {
            color: #000 !important; /* Color del texto */
            background: #fff !important; /* Fondo del select */
        }

        .select2-container--default .select2-selection--multiple {
            background-color: #fff; /* Fondo del select múltiple */
            border: 1px solid #ccc; /* Borde */
            border-radius: 4px; /* Bordes redondeados */
            padding: 5px; /* Espaciado interno */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff; /* Color de fondo de las opciones seleccionadas */
            color: #fff; /* Color del texto de las opciones seleccionadas */
            padding: 0 5px; /* Espaciado interno */
            border-radius: 3px; /* Bordes redondeados */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff; /* Color del botón de eliminar */
            margin-left: 5px; /* Espaciado a la izquierda del botón de eliminar */
        }
</style>
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
    // modal operarios
    document.addEventListener('DOMContentLoaded', function() {
        const modalOperarios = document.getElementById('modalOperarios');
        const btnSeleccionarOperario = document.getElementById('seleccionarOperario');
        const btnCerrarModal = modalOperarios.querySelector('.cerrarModal');
        const inputCodigoOperario = document.getElementById('codigo_operario');
        const inputNombreOperario = document.getElementById('nombre_operario');

        btnSeleccionarOperario.addEventListener('click', function() {
            const selectedOperario = document.querySelector('input[name="operativo_id"]:checked');
            if (selectedOperario) {
                const codigo = selectedOperario.dataset.codigo;
                const nombre = selectedOperario.dataset.nombre;
                
                inputCodigoOperario.value = codigo;
                inputNombreOperario.value = nombre;
                
                modalOperarios.style.display = 'none';
            } else {
                alert('Por favor, seleccione un operario.');
            }
        });

        btnCerrarModal.addEventListener('click', function() {
            modalOperarios.style.display = 'none';
        });
    });
</script>
<script>
    // modal servicios
    document.addEventListener('DOMContentLoaded', function() {
        const modalServicios = document.getElementById('modalServicios');
        const btnSelectServicio = document.getElementById('selectServicio');
        const btnAbrirModal = document.getElementById('abrirModalServicios');
        const btnCerrarModal = modalServicios.querySelector('.cerrarModal');
        const inputNombre_servicio = document.getElementById('nombre_servicio');
        const inputProseso_id = document.getElementById('proseso_id');

        btnSelectServicio.addEventListener('click', function() {
            const selectServicio = document.querySelector('input[name="proseso_select"]:checked');
            if (selectServicio) {
                const codigo = selectServicio.dataset.codigo;
                const nombre = selectServicio.dataset.nombre;
                
                inputProseso_id.value = codigo;
                inputNombre_servicio.value = nombre;
                
                modalServicios.style.display = 'none';
            } else {
                alert('Por favor, seleccione un servicio.');
            }
        });

        btnAbrirModal.addEventListener('click', function() {
            modalServicios.style.display = 'block';
        });

        btnCerrarModal.addEventListener('click', function() {
            modalServicios.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == modalServicios) {
                modalServicios.style.display = 'none';
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        const baseUrl = '{{ url('/') }}';
        const modalSdp = $('#modalSDP');
        const btnAbrirModalSDP = $('#abrirModalSDP');
        const btnSelectSDP = $('#selectSDP');
        const btnCerrarModal = modalSdp.find('.cerrarModal');
        const inputNumero_sdp = $('#sdp_id');
        const articulosSelect = $('#articulos_sdp');

        // Inicializar Select2 para selección múltiple
        articulosSelect.select2({
            placeholder: "Seleccione un artículo",
            allowClear: true,
            tags: true
        });

        btnAbrirModalSDP.on('click', function() {
            modalSdp.show();
        });

        btnSelectSDP.on('click', function() {
            const SDPSelect = $('input[name="SDP_select"]:checked');
            if (SDPSelect.length) {
                const codigo = SDPSelect.data('codigo');
                inputNumero_sdp.val(codigo);
                cargarArticulosSDP(SDPSelect.val());
                modalSdp.hide();
            } else {
                alert('Por favor, seleccione un SDP.');
            }
        });

        btnCerrarModal.on('click', function() {
            modalSdp.hide();
        });

        function cargarArticulosSDP(sdpId) {
            articulosSelect.empty().append(new Option("Cargando artículos...", "", false, false));
            articulosSelect.prop('disabled', true);

            fetch(`${baseUrl}/api/getArticulos/${sdpId}`)
                .then(response => response.json())
                .then(articulos => {
                    articulosSelect.empty().append(new Option("Seleccione un artículo", "", false, false));

                    if (articulos.length === 0) {
                        articulosSelect.append(new Option("No hay artículos disponibles", "", false, false));
                    } else {
                        articulos.forEach(articulo => {
                            const isSelected = {!! json_encode($tiempo_produccion->articulos->pluck('id')->toArray()) !!}.includes(articulo.id);
                            const option = new Option(`${articulo.codigo} - ${articulo.descripcion}`, articulo.id, isSelected, isSelected);
                            option.dataset.material = articulo.material;
                            option.dataset.plano = articulo.plano;
                            option.dataset.cantidad = articulo.pivot.cantidad;
                            option.dataset.precio = articulo.pivot.precio;
                            articulosSelect.append(option);
                        });
                    }
                    articulosSelect.prop('disabled', false);
                    articulosSelect.trigger('change');
                })
                .catch(error => {
                    console.error('Error al cargar artículos:', error);
                    articulosSelect.empty().append(new Option("Error al cargar artículos", "", false, false));
                });
        }

        $(window).on('click', function(event) {
            if ($(event.target).is(modalSdp)) {
                modalSdp.hide();
            }
        });

        // Inicializar la lista de artículos seleccionados si ya hay una SDP cargada
        if (inputNumero_sdp.val()) {
            cargarArticulosSDP(inputNumero_sdp.val());
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para manejar la apertura y cierre de modales
        function setupModal(modalId, btnId) {
            var modal = document.getElementById(modalId);
            var btn = document.getElementById(btnId);
            var span = modal.getElementsByClassName("cerrar")[0];

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        // Configurar modales
        setupModal("modalOperarios", "abrirModalOperarios");
        setupModal("modalServicios", "abrirModalServicios");
        setupModal("modalSDP", "abrirModalSDP");
    });
</script>
<script>
    const btnAbrirModalOperarios = document.getElementById('abrirModalOperarios');
    btnAbrirModalOperarios.addEventListener('click', function() {
        modalOperarios.style.display = 'block';
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para filtrar una tabla
        function filterTable(inputId, tableBodyId) {
            const searchInput = document.getElementById(inputId);
            const tableBody = document.getElementById(tableBodyId);

            searchInput.addEventListener('input', function () {
                const searchValue = searchInput.value.toLowerCase();
                const rows = tableBody.getElementsByTagName('tr');

                Array.from(rows).forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    let rowContainsSearchValue = false;

                    Array.from(cells).forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchValue)) {
                            rowContainsSearchValue = true;
                        }
                    });

                    if (rowContainsSearchValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Inicializar búsqueda para cada tabla
        filterTable('searchOperarios', 'operariosTableBody');
        filterTable('searchServicios', 'serviciosTableBody');
        filterTable('searchSDP', 'sdpTableBody');
    });
</script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicialización de flatpickr para hora de inicio y fin
            flatpickr("#hora_inicio", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i:S",
                time_24hr: true,
                enableSeconds: true,
                minuteIncrement: 1,
            });
            flatpickr("#hora_fin", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i:S",
                time_24hr: true,
                enableSeconds: true,
                minuteIncrement: 1,
            });
    
            const horaFinInput = document.getElementById('hora_fin');
            const laboralDescanso = document.getElementById('laboral_descanso');
            const decrementarBtn = document.getElementById('decrementar');
            const incrementarBtn = document.getElementById('incrementar');
            const minutosRestadosSpan = document.getElementById('minutos_restados');
    
            let tiempoOriginal = null;
            let minutosRestados = 0;
    
            // Guardar la hora original de fin
            function guardarTiempoOriginal() {
                if (!tiempoOriginal && horaFinInput.value) {
                    tiempoOriginal = horaFinInput.value;
                }
            }
    
            // Función para ajustar el tiempo
            function ajustarTiempo(incremento) {
                if (!laboralDescanso.checked) return;  // Solo ajustar si el checkbox está marcado
    
                guardarTiempoOriginal();
    
                minutosRestados += incremento;
                minutosRestados = Math.max(minutosRestados, 0);  // No permitir valores negativos
                minutosRestadosSpan.textContent = minutosRestados;
    
                if (!tiempoOriginal) return;
    
                let [horas, minutos, segundos] = tiempoOriginal.split(':').map(Number);
                let totalSegundos = (horas * 3600) + (minutos * 60) + segundos - (minutosRestados * 60);
    
                totalSegundos = Math.max(totalSegundos, 0);  // Evitar valores negativos
    
                let nuevasHoras = Math.floor(totalSegundos / 3600) % 24;
                let nuevosMinutos = Math.floor((totalSegundos % 3600) / 60);
                let nuevosSegundos = totalSegundos % 60;
    
                let nuevaHoraFin = `${nuevasHoras.toString().padStart(2, '0')}:${nuevosMinutos.toString().padStart(2, '0')}:${nuevosSegundos.toString().padStart(2, '0')}`;
                horaFinInput._flatpickr.setDate(nuevaHoraFin);
            }
    
            // Restaurar la hora original al desmarcar el checkbox
            laboralDescanso.addEventListener('change', function() {
                if (!this.checked) {
                    if (tiempoOriginal) {
                        horaFinInput._flatpickr.setDate(tiempoOriginal);
                    }
                    minutosRestados = 0;
                    minutosRestadosSpan.textContent = '0';
                }
            });
    
            // Listeners para los botones de incrementar y decrementar
            decrementarBtn.addEventListener('click', function() {
                ajustarTiempo(1);
            });
            
            incrementarBtn.addEventListener('click', function() {
                if (minutosRestados > 0) {
                    ajustarTiempo(-1);
                }
            });
        });
    </script>
@stop