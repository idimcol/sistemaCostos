@extends('adminlte::page')

@section('title', 'permisos')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Lista de permisos') }}
</h2>

@stop

@section('content')
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="container">
        <div class="col-12 p">
            <a href="{{route('home')}}" class="btn btn-primary">volver</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <x-adminlte-button label="nuevo" data-toggle="modal" data-target="#modalPurple" theme="primary" icon="fas fa-key"/>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700">
                            <th class="px-4 py-2 border">id</th>
                            <th class="px-4 py-2 border">nombre</th>
                            <th class="px-4 py-2 border">eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permisos as  $permission)
                            <tr class="bg-gray-50 text-gray-700">
                                <td class="px-4 py-2 border">{{ $permission->id }}</td>
                                <td class="px-4 py-2 border">{{$permission->name}}</td>
                                <td class="px-4 py-2 border">
                                    <form action="{{route('permisos.destroy', $permission)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('seguro que quieres eliminar este permiso')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Themed --}}
<x-adminlte-modal id="modalPurple" title="Nuevo permiso" theme="primary"
    icon="fas fa-bolt" size='lg' disable-animations>
    <form action="{{ route('permisos.store') }}" method="POST">
        @csrf
        <div class="row">
            <x-adminlte-input name="name" label="nombre" placeholder="permiso..."
                fgroup-class="col-md-6" disable-feedback/>
        </div>
        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
    </form>
</x-adminlte-modal>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .p {
            padding: 20px;
        }
        .card-body{
            background: #8a8a8a;
            max-height: 400px;
            overflow-y: auto;
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
    }, 10000);
    </script>
@stop