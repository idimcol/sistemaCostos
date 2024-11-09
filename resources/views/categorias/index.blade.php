@extends('adminlte::page')

@section('title', 'categorias')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Todas las Categorias y sus Productos') }}
</h2>
@stop

@section('content')
<div class="flex justify-end">
    <a href="{{ route('almacen') }}" class="btn btn-warning">Volver</a>
</div>
<div class="flex justify-start">
    <a href="{{ route('categorias.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>
        Agregar Categoria
    </a>
</div>
@if (session('success'))
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
<div class="p-12">
    <div class="container">
        @foreach ($categorias as $categoria)
            <div class="targets">
                <div class="card" data-categoria-id="{{ $categoria->id }}">
                    <div class="px-4 py-2 card-header" id="titulo-{{ $categoria->id }}">
                        <h2 class="text-lg font-semibold text-gray-700">{{ $categoria->nombre }}</h2>
                    </div>
                    <div class="p-4" style="background-color: {{ $categoria->colorfondo }};">
                        <p class="text-gray-600 mb-4">{{ $categoria->descripcion }}</p>
                        <h3 class="font-semibold text-gray-700 mb-2">Productos:</h3>
                        <ul class="space-y-2">
                            @forelse ($categoria->productos as $producto)
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-700">{{ $producto->nombre }}</span>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        Stock: {{ $producto->inventario_sum_stock ?? 0 }}
                                    </span>
                                </li>
                            @empty
                                <li class="text-gray-500 italic">No hay productos en esta categoría.</li>
                            @endforelse
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning">Editar</a>
                            <div class="mt-2">
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?')">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-2">
                    <button type="button" class="btn btn-primary btn-edit-color" data-categoria-id="{{ $categoria->id }}">
                        Cambiar color de fondo
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="modal fade" id="colorModal" tabindex="-1" aria-labelledby="colorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="colorModalLabel">Cambiar color de fondo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cardd">
                    <div class="card-header">
                        Seleccionar colores de fondo
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="colorPickerTitulo" class="form-label">Color de fondo del título</label>
                            <input type="color" name="colorPickerTitulo" id="colorPickerTitulo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="colorPickerFondo" class="form-label">Color de fondo del contenido</label>
                            <input type="color" name="colorPickerFondo" id="colorPickerFondo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarColor">Guardar</button>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .container {
            
        }
        .col {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        .targets {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            gap: 20px;
        }
        .card {
            background-color: #f0f0f0 !important;
            max-width: 700px !important;
            max-height: 400px !important;
            color: #000 !important;
        }

    .card-body {
        background: #bbbbbb !important;
        color: #000 !important;
        padding: 5px;
    }

    .content, .content-header {
        background: #fff !important;
    }

    .content {
        height: 87vh;
    }

    input {
        background: #fff !important;
        color: #000 !important;
    }

    .modal-header, .modal-body, .modal-footer {
        background: #fff !important;
    }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
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
            const colorPickerTitulo = document.getElementById('colorPickerTitulo');
            const colorPickerFondo = document.getElementById('colorPickerFondo');
            const modalGuardarBtn = document.getElementById('guardarColor');
            let categoriaActualId = null;

            // Abrir modal y capturar la categoría seleccionada
            document.querySelectorAll('.btn-edit-color').forEach(btn => {
                btn.addEventListener('click', function() {
                    categoriaActualId = this.getAttribute('data-categoria-id');
                    const card = document.querySelector(`.card[data-categoria-id="${categoriaActualId}"]`);
                    const cardHeader = card.querySelector('.card-header');
                    const content = card.querySelector('.p-4');

                    // Establecer los colores actuales en el modal
                    colorPickerTitulo.value = rgbToHex(window.getComputedStyle(cardHeader).backgroundColor);
                    colorPickerFondo.value = rgbToHex(window.getComputedStyle(content).backgroundColor);

                    const modal = new bootstrap.Modal(document.getElementById('colorModal'));
                    modal.show();
                });
            });

            // Función para convertir rgb a hex
            function rgbToHex(rgb) {
                let r = 0, g = 0, b = 0;
                if (rgb.match(/^rgb\(/)) {
                    rgb = rgb.replace(/^rgb\(/, '').replace(/\)$/, '');
                    rgb = rgb.split(',');
                    r = parseInt(rgb[0], 10);
                    g = parseInt(rgb[1], 10);
                    b = parseInt(rgb[2], 10);
                } else if (rgb.match(/^#[0-9A-Fa-f]{6}$/)) {
                    r = parseInt(rgb.slice(1, 3), 16);
                    g = parseInt(rgb.slice(3, 5), 16);
                    b = parseInt(rgb.slice(5, 7), 16);
                }
                return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
            }

            // Guardar los colores seleccionados
            modalGuardarBtn.addEventListener('click', function() {
                const colorTitulo = colorPickerTitulo.value;
                const colorFondo = colorPickerFondo.value;

                if (categoriaActualId) {
                    const card = document.querySelector(`.card[data-categoria-id="${categoriaActualId}"]`);
                    const cardHeader = card.querySelector('.card-header');
                    const content = card.querySelector('.p-4');

                    cardHeader.style.backgroundColor = colorTitulo;
                    content.style.backgroundColor = colorFondo;

                    // Guardar los colores en localStorage
                    localStorage.setItem(`categoriaColorTitulo_${categoriaActualId}`, colorTitulo);
                    localStorage.setItem(`categoriaColorFondo_${categoriaActualId}`, colorFondo);
                }
                // Cerrar el modal
                bootstrap.Modal.getInstance(document.getElementById('colorModal')).hide();
            });

            // Cargar los colores guardados desde localStorage
            function cargarColoresGuardados() {
                document.querySelectorAll('.card').forEach(card => {
                    const categoriaId = card.getAttribute('data-categoria-id');
                    const colorTituloGuardado = localStorage.getItem(`categoriaColorTitulo_${categoriaId}`);
                    const colorFondoGuardado = localStorage.getItem(`categoriaColorFondo_${categoriaId}`);
                    
                    if (colorTituloGuardado) {
                        const cardHeader = card.querySelector('.card-header');
                        cardHeader.style.backgroundColor = colorTituloGuardado;
                    }
                    if (colorFondoGuardado) {
                        const content = card.querySelector('.p-4');
                        content.style.backgroundColor = colorFondoGuardado;
                    }
                });
            }

            // Cargar los colores al iniciar la página
            cargarColoresGuardados();
        });
    </script>
@stop
