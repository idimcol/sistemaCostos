@extends('adminlte::page')

@section('title', 'users')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Lista de usuarios') }}
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
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="mb-4">
                <a href="{{route('users.create')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear nuevo usuario</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700">
                            <th class="px-4 py-2 border">id</th>
                            <th class="px-4 py-2 border">nombre</th>
                            <th class="px-4 py-2 border">correo</th>
                            <th class="px-4 py-2 border">role</th>
                            <th class="px-4 py-2 border">editar</th>
                            <th class="px-4 py-2 border">eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="bg-gray-50 text-gray-700">
                            <td class="px-4 py-2 border">{{$user->id}}</td>
                            <td class="px-4 py-2 border">{{$user->name}}</td>
                            <td class="px-4 py-2 border">{{$user->email}}</td>
                            <td class="px-4 py-2 border">
                                @if($user->getRoleNames()->isNotEmpty())
                                    {{ $user->getRoleNames()->implode(', ') }} <!-- Lista los roles separados por comas -->
                                @else
                                    <span>Sin rol asignado</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">
                                <a href="{{route('users.edit', $user->id)}}" class="btn btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <td class="px-4 py-2 border">
                                <form action="{{route('users.destroy', $user->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿seguro que quieres eliminar este usuario?')">
                                        <i class="fas fa-trash-alt"></i>
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
    <div class="col-12 p">
        <a href="{{route('home')}}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">volver</a>
    </div>
</div>
@stop

@section('css')
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