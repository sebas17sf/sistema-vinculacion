@extends('layouts.participante')

@section('content')
<div class="container mt-5">
    <h4 class="text-center mb-4">Generar Reportes</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre del Reporte</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Generar Evaluación de Estudiantes</td>
                    <td>
                        <form action="{{ route('ParticipanteVinculacion.generarEvaluacionEstudiante') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-light btn-block">
                                <i class="fas fa-file-excel"></i> Generar
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Generar Número de Horas de Docentes</td>
                    <td>
                        <form action="{{ route('ParticipanteVinculacion.generarHorasDocente') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-light btn-block">
                                <i class="fas fa-file-excel"></i> Generar
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Registro de Estudiantes</td>
                    <td>
                        <form action="{{ route('ParticipanteVinculacion.generarAsistencia') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="fecha">Fecha de asistencia:</label>
                                <input type="date" id="fecha" name="fecha" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="lugar">Lugar de la actividad:</label>
                                <input type="text" id="lugar" name="lugar" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="actividades">Actividades a realizar:</label>
                                <textarea id="actividades" name="actividades" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-light btn-block">
                                <i class="fas fa-save"></i> Generar
                            </button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mt-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-4">
        {{ session('error') }}
    </div>
@endif

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Estilos CSS personalizados */
    .table {
        background-color: #ffffff; /* Color de fondo de la tabla */
    }
    .table thead th {
        background-color: #f8f9fa; /* Color de fondo de las celdas de encabezado */
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa; /* Color de fondo de las filas impares */
    }
    .btn-light {
        background-color: #e9ecef; /* Color de fondo de los botones */
    }
    .btn-light:hover {
        background-color: #d9d9d9; /* Color de fondo al pasar el mouse sobre los botones */
    }
</style>
@endsection
