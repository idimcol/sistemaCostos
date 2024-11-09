@extends('adminlte::page')

@section('title', 'editer categoria')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Editar Categoria') }}
</h2>
@stop

@section('content')
<div class="p-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="max-w-sm mx-auto space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-4">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="{{ $categoria->nombre }}" class="form-control" required value="{{ $categoria->nombre }}">
                    </div>
                    <div class="form-group mb-4">
                        <label for="descripcion" class="form-label">Descripción</label> 
                        <input id="descripcion" name="descripcion" value="{{ $categoria->descripcion }}" class="form-control" cols="30" rows="10" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="btn btn-primary">
                            Actualizar
                        </button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-default">
                            Cancelar
                        </a>
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
        input#descripcion {
            height: 100px;
            text-align: justify !important;
            text-justify: inter-word !important;
            height: auto;
            min-height: 100px;
            max-height: 300px;
            overflow-y: auto;
            resize: vertical;
            background: #fff !important;
            color: #000 !important;
        }

        .card, .card-body {
            background: #bbbbbb !important;
            color: #000 !important;
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

        h2 {
            text-transform: uppercase;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputDescripcion = document.getElementById('descripcion');
            
            // Crear un textarea
            const textareaDescripcion = document.createElement('textarea');
            
            // Copiar atributos del input al textarea
            textareaDescripcion.id = inputDescripcion.id;
            textareaDescripcion.name = inputDescripcion.name;
            textareaDescripcion.className = inputDescripcion.className;
            textareaDescripcion.value = inputDescripcion.value;
            textareaDescripcion.required = true;
            
            // Reemplazar el input por el textarea
            inputDescripcion.replaceWith(textareaDescripcion);
            
            // Ajustar altura dinámicamente
            function ajustarAltura() {
                textareaDescripcion.style.height = 'auto';
                textareaDescripcion.style.height = textareaDescripcion.scrollHeight + 'px';
            }
    
            // Ajustar altura inicialmente
            ajustarAltura();
    
            // Ajustar altura cuando se modifique el contenido
            textareaDescripcion.addEventListener('input', ajustarAltura);
    
            // Aplicar estilo de texto justificado
            textareaDescripcion.style.textAlign = 'justify';
            textareaDescripcion.style.textJustify = 'inter-word';
        });
    </script>
@stop