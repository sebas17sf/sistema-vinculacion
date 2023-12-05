@extends('layouts.directorVinculacion')

@section('content')
@if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok'
            });
        </script>
    @endif
    
<div class="container" style="overflow-x: auto;">
    <h4>Información del Proyecto</h4>

    @if ($proyecto)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre del proyecto</th>
                <th>Director</th>
                <th>Actividades a realizar</th>
                <th>Nombre del profesor participante</th>
                <th>Correo del tutor</th>
                <th>Correo del profesor participante</th>
                <th>Departamento</th>
                <th>Fecha de inicio</th>
                <th>Fecha fin</th>
                <th>Cupos</th>
                <th>Estado del proyecto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $proyecto->NombreProyecto }}</td>
                <td>{{ strtoupper($proyecto->ApellidoProfesor) }} {{ strtoupper($proyecto->NombreProfesor) }}</td>
                <td>{{ $proyecto->DescripcionProyecto }}</td>
                <td>{{ strtoupper($proyecto->ApellidoAsignado) }} {{ strtoupper($proyecto->NombreAsignado) }}</td> 
                <td>{{ $proyecto->CorreoElectronicoTutor }}</td>
                <td>{{ $proyecto->CorreoProfeAsignado }}</td>
                <td>{{ $proyecto->DepartamentoTutor }}</td>
                <td>{{ $proyecto->FechaInicio }}</td>
                <td>{{ $proyecto->FechaFinalizacion }}</td>
                <td>{{ $proyecto->cupos }}</td>
                <td>{{ $proyecto->Estado }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <p>No estás asignado a ningún proyecto actualmente.</p>
    @endif
</div>
@endsection
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        padding: 4px 8px;

    }

    table, th, td {
        font-size: 14px;
        padding: 4px 8px;
        border: 1px solid #ddd;

    }

    th {
        border: 1px solid #70a1ff;
        background-color: #eaf5ff;
    }

    .wide-cell {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .body, table, tr, td, th{
        font-size: 12px;

    }
</style>