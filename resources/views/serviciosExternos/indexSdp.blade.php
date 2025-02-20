@extends('adminlte::page')

@section('title', 'home')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
    {{ __('lista de SDP para subir servicios') }}
</h2>
@stop

@section('content')
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('materias_primas.index') }}" class="btn btn-primary">volver</a>
        </div>
        @if (session('success'))
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-600 text-gray-200">
                                <th class="px-6 py-3 border-b" >Numero de SDP</th>
                                <th class="px-6 py-3 border-b" >Subir Servicio</th>
                                <th class="px-6 py-3 border-b" >Lista de Servicios subidos</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach ($sdps as $sdp)
                                <tr class="dark:bg-gray-300 text-gray-700">
                                    <td class="px-6 py-4 border-b">{{ $sdp->numero_sdp }}</td>
                                    <td class="px-6 py-4 border-b">
                                        <a href="{{ route('subirServicios.form', $sdp->numero_sdp) }}" class="btn btn-primary">subir servicio</a>
                                    </td>
                                    <td class="px-6 py-4 border-b">
                                        <a href="#" class="btn btn-info">ver lista</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
@stop