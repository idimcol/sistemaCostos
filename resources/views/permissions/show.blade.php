@extends('adminlte::page')

@section('title', 'permisos del rol')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('permisos para el rol') }} {{ $role->name }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('roles.index') }}" class="btn btn-primary">Volver</a>
        </div>
        <div class="card">
            <div class="card-body">
                @if($role->permissions->isEmpty())
                    <span>No tiene permisos asignados</span>
                @else
                    <ul>
                        @foreach($role->permissions as $permission)
                            <li><i class="fa-brands fa-first-order-alt"></i>{{ $permission->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .card-body {
            box-shadow: 5px 5px 5px #8a8a8a;
            max-height: 400px;
            overflow-y: auto;
            display: grid;
            align-items: baseline;
        }
        i.fa-brands{
            color: #4283fc;
        }
    </style>
@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
@stop
