@extends('adminlte::page')

@section('title', 'editar bonos')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('editar Bono') }}
    </h2>
@stop

@section('content')
    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('horas-extras.update', $horas_extras->id) }}" class="max-w-sm mx-auto space-y-4" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="operario_cod" class="form-label">Operarios</label>
                            <select name="operario_cod" id="operario_cod" class="form-control" aria-placeholder="operario">
                                @foreach ($operarios as $operario)
                                    <option value="{{ $operario->codigo }}"{{ $operario->codigo == old('operario_cod', $horas_extras->operario_cod) ? 'selected' : ''}}
                                    >
                                        {{ $operario->operario }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        
                        <div class="mb-4">
                            <label for="" class="form-label">valor del bono</label>
                            <input type="number" name="valor_bono" id="" value="{{ $horas_extras->valor_bono }}" class="form-control" placeholder="valor">
                        </div>
        
                        <div class="mb-4">
                            <button type="submit" class="btn btn-info mr-3">guardar</button>
                            <a href="{{ route('horas-extras.index') }}" class="btn btn-default">cancelar</a>
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
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
