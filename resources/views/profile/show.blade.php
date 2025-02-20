@extends('adminlte::page')

@section('title', 'perfil')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('Perfil') }}
</h2>
@stop

@section('content')
<div class="py-12">
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div id="success-message" class="alert alert-success" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <span class="alert alert-dnger">{{ $error }}</span>
                @endforeach
            @endif

            @if (session('repeatPassword'))
                <div class="alert alert-danger" role="alert">
                    {{ session('repeatPassword') }}
                </div>
            @endif

            @if (session('notification'))
                <div class="alert alert-success" role="alert">
                    {{ session('notification') }}
                </div>
            @endif

            <div class="">
                <p><strong>Nombre:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Creado el:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
            
            <div class="col-12 mt-4">
                <h1 class="text-center"><b>ACTUALIZAR DATOS</b></h1>
            </div>
            <form action="{{ route('user.update.password') }}" method="POST" class="max-w-sm mx-auto space-y-4">
                @csrf

                <div class="group-form">
                    <label for="">Contraseña Actual</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="group-form">
                    <label for="">Nueva Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="group-form">
                    <label for="">Comfirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="group-form mt-4">
                    <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                </div>
            </form>
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
@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
    
    <script>
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 10000);
    </script>
@stop
