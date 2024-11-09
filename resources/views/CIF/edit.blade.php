@extends('adminlte::page')

@section('title', 'CIF_edit')

@section('content_header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('COSTOS INDIRECTO DE FABRICACIÓN') }}
</h2>
@stop   

@section('content')
    <div class="py-12">
        <div class="container">
            <div class="flex items-end justify-end mb-4">
                <a href="{{ route('cif.index') }}" class="btn btn-primary">volver</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form id="cifForm" class=" form space-y-4">

                    @csrf
                    @method('PUT')

                        <div class="flex flex-row gap-3">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="GOI" class="form-label">Gasto operativo indirecto (GOI)</label>
                                    <input type="text" class="form-control" name="GOI" value="{{ $cif->GOI }}" placeholder="GOI">
                                </div>
                                <div class="mb-3">
                                    <label for="MOI" class="form-label">mano de obra indirecta (MOI)</label>
                                    <input type="text" class="form-control" id="MOI" value="{{ $cif->MOI }}" name="MOI" placeholder="MOI">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="OCI" class="form-label">otros costos indirectos (OCI)</label>
                                    <input type="text" class="form-control" id="" value="{{ $cif->OCI }}" name="OCI" placeholder="OCI">
                                </div>
                                <div class="mb-3">
                                    <label for="NMH" class="form-label">numero de horas al mes</label>
                                    <div class="flex flex-row gap-3">
                                        <input type="text" class="form-control" id="NMH" value="{{ $cif->NMH }}" name="NMH" placeholder="NHM">
                                        <button type="button" class="btn btn-primary" id="updateHoursButton">actualizar horas</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="button">actualizar variables</button>
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
        .form {
            max-width: 30rem;
            margin: 0 auto;
        }
        button#button {
            width: 300px;
        }
        input {
            width: 300px;
            background: #fff !important;
            color: #000 !important;
        }
        .container {
            padding: 10px;
        }

        .content, .content-header {
            background: #fff !important;
        }

        .content {
            height: 100vh;
        }

        .card, .card-body {
            background: #b2b1b1 !important;
            color: #fff !important;
        }

        label {
            color: #000 !important;
        }

    </style>
@stop

@section('js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
<script>
    document.getElementById('cifForm').addEventListener('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch('{{ route('cif.update', $cif->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('CIF actualizado con éxito');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
<script>
    document.getElementById('updateHoursButton').addEventListener('click', function() {
        var nmh = document.getElementById('NMH').value;
        var token = document.querySelector('input[name="_token"]').value;
        var method = document.querySelector('input[name="_method"]').value;

        fetch('{{ route("cif.update", $cif->id) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                NMH: nmh,
                _method: method,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Horas actualizadas correctamente');
            } else {
                alert('Error al actualizar las horas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar las horas');
        });
    });
</script>
@stop