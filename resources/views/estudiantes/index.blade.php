@extends('layouts.app')

@section('title', 'Información del Estudiante')

@section('content')
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: '{{ session("success") }}',
        confirmButtonText: 'Ok'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session("error") }}',
        confirmButtonText: 'Ok'
    });
</script>
@endif


<div class="container mt-5">
    <h4 class="mb-4">Información proceso de Admisión a Vinculación</h4>
    <form action="{{ route('estudiantes.certificadoMatricula') }}" method="get">
    <button type="submit" class="btn btn-sm btn-secondary">
        <i class="material-icons">cloud_download</i> Crear Certificado Matrícula
    </button>
</form>

    <div class="table-responsive">
        <table class="table custom-table">
            <tbody>
                <tr>
                    <th><i class="material-icons">person</i> Nombres:</th>
                    <td>{{ strtoupper($estudiante->Nombres) }}</td>
                </tr>
                <tr>
                    <th><i class="material-icons">person</i> Apellidos:</th>
                    <td>{{ strtoupper($estudiante->Apellidos) }}</td>
                </tr>
                <tr>
                    <th><i class="material-icons">info</i> ESPE ID:</th>
                    <td>{{ strtoupper($estudiante->espe_id) }}</td>
                </tr>
                <tr>
                    <th><i class="material-icons">phone</i> Celular:</th>
                    <td>{{ strtoupper($estudiante->celular) }}</td>
                </tr>
                <tr>
                    <th><i class="material-icons">credit_card</i> Cédula:</th>
                    <td>{{ strtoupper($estudiante->cedula) }}</td>
                </tr>
                <tr>
                    <th><i class="material-icons">calendar_today</i> Cohorte:</th>
                    <td>{{ strtoupper($estudiante->Cohorte) }}</td>
                </tr>
                <tr>
                    <th><i class="material-icons">school</i> Departamento:</th>
                    <td>{{ strtoupper($estudiante->Departamento) }}</td>
                </tr>
                <!-- Agrega aquí más campos con íconos si es necesario -->
            </tbody>
        </table>
    </div>

    <!-- Botón de edición con ícono -->
    <div class="text-center">
    <a href="{{ route('estudiantes.edit', ['estudiante' => $estudiante->EstudianteID]) }}" class="btn btn-sm btn-secondary">
        <i class="material-icons">edit</i> Editar Información
    </a>
</div>


    <!-- Estado y botón de reenvío de información con ícono -->
    <div class="mt-4">
        <h4><i class="material-icons">check_circle</i> Estado-Aprobación</h4>
        <table class="table custom-table">
            <tbody>
                <tr>
                    <th>Verificación</th>
                    <td>
    @if ($estudiante->Estado == 'Aprobado')
        {{ strtoupper('Vinculacion') }}
    @elseif ($estudiante->Estado == 'Aprobado-practicas')
        {{ strtoupper('Practicas') }}
    @else
        {{ strtoupper($estudiante->Estado) }}
    @endif
</td>
                </tr>
            </tbody>
        </table>

        <form method="POST" action="{{ route('estudiantes.resend', ['estudiante' => $estudiante->EstudianteID]) }}">
    @csrf
    <div class="text-center">
        <button type="submit" class="btn btn-sm btn-secondary">
            <i class="material-icons">send</i> Reenviar Información
        </button>
    </div>
</form>


    </div>

    <!-- Sección para mostrar la información del proyecto asignado -->
    <div class="mt-4">
        <h4><i class="material-icons">assignment</i> Proyecto Asignado</h4>
        @if ($asignacionProyecto)
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>Nombre del Proyecto</th>
                    <th>Profesor Tutor</th>
                    <th>Profesor Asignado</th>
                    <th>Descripción del Proyecto</th>
                    <th>Fecha de Asignación</th>
                    <th>Fecha de Inicio</th>
                    <th>Estado actual del Proyecto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ strtoupper($asignacionProyecto->proyecto->NombreProyecto) }}</td>
                    <td>{{ strtoupper($asignacionProyecto->proyecto->NombreProfesor . ' ' .
                        $asignacionProyecto->proyecto->ApellidoProfesor) }}</td>
                    <td>{{ strtoupper($asignacionProyecto->proyecto->NombreAsignado . ' ' .
                        $asignacionProyecto->proyecto->ApellidoAsignado) }}</td>
                    <td>{{ strtoupper($asignacionProyecto->proyecto->DescripcionProyecto) }}</td>
                    <td>{{ $asignacionProyecto->FechaAsignacion }}</td>
                    <td>{{ $asignacionProyecto->proyecto->FechaInicio }}</td>
                    <td>{{ $asignacionProyecto->proyecto->Estado }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <p>Aun no está asignado un Proyecto. Estar pendiente de su asignación.</p>
        @endif
    </div>

    



</div>





@endsection

<style>
    .table th {
        width: 20%;
        border: 1px solid #70a1ff;
        background-color: #eaf5ff;
    }

    .custom-table {
        border-collapse: collapse;
        width: 100%;
    }

    .custom-table td,
    .custom-table tr {
        border: 1px solid #70a1ff;
        padding: 8px;
    }

    body,
    input,
    select,
    th,
    td,
    label,
    button {
        background-color: #F5F5F5;
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.5;

    }
</style>