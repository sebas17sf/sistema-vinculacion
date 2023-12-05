@extends('layouts.coordinador')

@section('content')
<div class="container" style="overflow-x: auto;">
    @php
    $departamentos = ['Ciencias de la Computación', 'Ciencias Exactas', 'Ciencias de la Vida y Agricultura'];
    @endphp

    @foreach ($departamentos as $departamento)
    <h2> {{ $departamento }}</h2>
    <div class="d-flex">
<form method="GET" action="{{ route('coordinador.proyectosEstudiantes') }}" class="mr-3">
        <label for="elementosPorPagina2">Estudiantes a visualizar:</label>
        <select name="elementosPorPagina2" id="elementosPorPagina2" onchange="this.form.submit()">
            <option value="10" @if(request('elementosPorPagina2', $elementosPorPagina2) == 10) selected @endif>10</option>
            <option value="20" @if(request('elementosPorPagina2', $elementosPorPagina2) == 20) selected @endif>20</option>
            <option value="50" @if(request('elementosPorPagina2', $elementosPorPagina2) == 50) selected @endif>50</option>
            <option value="100" @if(request('elementosPorPagina2', $elementosPorPagina2) == 100) selected @endif>100</option>
        </select>
    </form>
</div>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre del Proyecto</th>
                <th>Nombre del Director del Proyecto</th>
                <th>Actividad a realizar</th>
                <th>Docente Asignado</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Estudiantes-Asignados</th>
                <th>Departamento</th>
            </tr>
        </thead>
        <tbody>
            @php
            $proyectoActual = null;
            $estudiantesAsignados = [];
            $departamentoVacio = true;
            @endphp
            @foreach($asignaciones as $asignacion)
            @if ($asignacion->proyecto && $asignacion->proyecto->DepartamentoTutor === $departamento)
            @if ($proyectoActual !== null && $proyectoActual->ProyectoID !== $asignacion->proyecto->ProyectoID)
            <tr>
                <td class="wrap-cell">{{ strtoupper($proyectoActual->NombreProyecto) }}</td>
                <td>{{ strtoupper($proyectoActual->ApellidoProfesor) }} {{ strtoupper($proyectoActual->NombreProfesor) }}</td>
                <td class="wrap-cell">{{ strtoupper($proyectoActual->DescripcionProyecto) }}</td>
                <td>{{ strtoupper($proyectoActual->ApellidoAsignado) }} {{ strtoupper($proyectoActual->NombreAsignado) }}</td>
                <td>{{ strtoupper($proyectoActual->FechaInicio) }}</td>
                <td>{{ strtoupper($proyectoActual->FechaFinalizacion) }}</td>
                <td>
                    {!! nl2br(strtoupper(implode("<br>", $estudiantesAsignados))) !!}
                </td>
                <td>{{ strtoupper($proyectoActual->DepartamentoTutor) }}</td>
            </tr>
            @php
            $estudiantesAsignados = [];
            $departamentoVacio = false;
            @endphp
            @endif
            @php
            $proyectoActual = $asignacion->proyecto;
            $estudiantesAsignados[] = strtoupper($asignacion->estudiante->Apellidos) . ' ' . strtoupper($asignacion->estudiante->Nombres);
            $departamentoVacio = false;
            @endphp
            @endif
            @endforeach
            @if ($proyectoActual !== null && $proyectoActual->DepartamentoTutor === $departamento)
            <tr>
                <td class="wrap-cell">{{ strtoupper($proyectoActual->NombreProyecto) }}</td>
                <td>{{ strtoupper($proyectoActual->ApellidoProfesor) }} {{ strtoupper($proyectoActual->NombreProfesor) }}</td>
                <td class="wrap-cell">{{ strtoupper($proyectoActual->DescripcionProyecto) }}</td>
                <td>{{ strtoupper($proyectoActual->ApellidoAsignado) }} {{ strtoupper($proyectoActual->NombreAsignado) }}</td>
                <td>{{ strtoupper($proyectoActual->FechaInicio) }}</td>
                <td>{{ strtoupper($proyectoActual->FechaFinalizacion) }}</td>
                <td>
                    {!! nl2br(strtoupper(implode("<br>", $estudiantesAsignados))) !!}
                </td>
                <td>{{ strtoupper($proyectoActual->DepartamentoTutor) }}</td>
            </tr>
            @endif
            @if ($departamentoVacio)
            <tr>
                <td colspan="8">No hay procesos de asignación a proyectos</td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($asignaciones->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">Anterior</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $asignaciones->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
        </li>
        @endif

        @foreach ($asignaciones->getUrlRange(1, $asignaciones->lastPage()) as $page => $url)
        @if ($page == $asignaciones->currentPage())
        <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach

        @if ($asignaciones->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $asignaciones->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Siguiente</span>
        </li>
        @endif
    </ul>
</div>

    @endforeach
    <form method="POST" action="{{ route('coordinador.reporteVinculacionProyectos') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-secondary">
    <i class="fas fa-file-excel"></i> Generar Reporte
    </button>
</form>
</div>
@endsection

<style>
    table {
        width: 200%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        font-size: 0.8rem;
        border: 1px solid #ddd;
        padding: 10px 12px;
        white-space: nowrap;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Estilos para las celdas con salto de línea */
    .wrap-cell {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
