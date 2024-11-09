@extends('adminlte::page')

@section('title', 'Bonos')

@section('content_header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Bonos') }}
    </h2>
@stop

@section('content')
<div class="py-12">
    <div class="container">
        @if (session('success'))
            <div id="success-message" class="alert alert-success" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="flex flex-col items-end justify-end col-12 mb-4">
            <a href="{{ route('listar.operarios') }}" class="btn btn-primary">volver</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-12 mb-4">
                    <a href="{{ route('horas-extras.create') }}" class="btn btn-info">crear bono</a>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="table table-primary">
                            <th>#</th>
                            <th>Código</th>
                            <th>operarios</th>
                            <th>valor del bono</th>
                            <th>hora extras diurna</th>
                            <th>hora extra nocturna</th>
                            <th>hora extra festiva / dominical</th>
                            <th>hora extra de recargo nocturno</th>
                            <th>editar</th>
                            <th>eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horas_extras as $index => $horas)
                            <tr class="table table-secondary">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $horas->operarios->codigo }}</td>
                                <td>{{ $horas->operarios->operario }}</td>
                                <td>{{ number_format($horas->valor_bono, 2, ',', '.') }}</td>
                                <td>{{ number_format($horas->horas_diurnas, 2, ',', '.') }}</td>
                                <td>{{ number_format($horas->horas_nocturnas, 2, ',', '.') }}</td>
                                <td>{{ number_format($horas->horas_festivos, 2, ',', '.') }}</td>
                                <td>{{ number_format($horas->horas_recargo_nocturno, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('horas-extras.edit', $horas->id) }}" class="btn btn-warning">Editar</a>
                                </td>
                                <td>
                                    <form action="{{ route('horas-extras.destroy', $horas->id ) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" >eliminar</button>
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
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <style>
        .content, .content-header{
            background: #fff !important;
        }

        .content {
            height: 87vh;
        }

        h2 {
            font-size: 18px;
            text-transform: uppercase;
        }

        thead tr th, tbody tr td {
            border: #000 1px solid !important;
        }

        th {
            text-align: center;
            text-transform: uppercase;
        }
        
        .card, .card-body {
            background: #aeaeae !important;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script>
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);
</script>
@stop