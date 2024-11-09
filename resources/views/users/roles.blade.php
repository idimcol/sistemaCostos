@extends('adminlte::page')

@section('title', 'roles')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Lista de roles') }}
</h2>

@stop

@section('content')
<div class="py-12">
    @if (session('success'))
        <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="col-12 p">
            <a href="{{route('home')}}" class="btn btn-primary">volver</a>
        </div>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="card-header">
                <x-adminlte-button label="nuevo" data-toggle="modal" data-target="#modalPurple" theme="primary" icon="fas fa-key"/>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700">
                            <th class="px-4 py-2 border">id</th>
                            <th class="px-4 py-2 border">nombre</th>
                            <th class="px-4 py-2 border">permisos</th>
                            <th class="px-4 py-2 border">actualizar</th>
                            <th class="px-4 py-2 border">eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as  $role)
                            <tr class="bg-gray-50 text-gray-700">
                                <td class="px-4 py-2 border">{{ $role->id }}</td>
                                <td class="px-4 py-2 border">{{$role->name}}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('permisos.role', $role->id) }}" class="btn btn-info">ver permisos</a>
                                </td>
                                <td class="px-4 py-2 border">
                                    <a href="{{route('roles.edit', $role->id)}}" class="btn btn-info">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                </td>
                                <td class="px-4 py-2 border">
                                    <form action="{{route('roles.destroy', $role->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('seguro que quieres eliminar este role')">
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
<x-adminlte-modal id="modalPurple" title="Nuevo role" theme="primary"
    icon="fas fa-bolt" size='lg' disable-animations>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="row">
            <x-adminlte-input name="name" label="nombre" placeholder="Role..."
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