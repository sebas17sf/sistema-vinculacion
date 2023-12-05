@extends('layouts.director')

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

    <h4>Practicas Pre-Profesionales</h4>

    <hr>

    <div class="d-flex">
    <form method="GET" action="{{ route('director.practicas') }}" class="mr-3" id="elementosPorPaginaForm">
        <label for="elementosPorPagina" class="mr-2">Estudiantes a visualizar:</label>
        <select name="elementosPorPagina" id="elementosPorPagina" onchange="document.getElementById('elementosPorPaginaForm').submit()">
            <option value="10" @if ($elementosPorPagina == 10) selected @endif>10</option>
            <option value="20" @if ($elementosPorPagina == 20) selected @endif>20</option>
            <option value="50" @if ($elementosPorPagina == 50) selected @endif>50</option>
            <option value="100" @if ($elementosPorPagina == 100) selected @endif>100</option>
        </select>
    </form>

    <form method="get" action="{{ route('director.practicas') }}" class="ml-3">
        @csrf
        <input type="text" name="searchInput" id="searchInput" placeholder="Buscar estudiantes...">
        <button type="submit">Buscar</button>
    </form>
</div>





        <h4>Estudiantes Practica I</h4>
        @csrf
        <table class="table table-bordered">
    <thead>
        <tr>
            <th>Estudiante</th>
            <th>Práctica</th>
            <th>Tutor Académico</th>
            <th>Tutor Empresarial</th>
            <th>Empresa</th>
            <th>Nivel</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Horas planificadas</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estudiantesPracticaI as $practicaI)
            @if ($practicaI->estudiante)
                <tr>
                    <td>{{ strtoupper($practicaI->estudiante->Apellidos) }} {{ strtoupper($practicaI->estudiante->Nombres) }}</td>
                    <td>{{ strtoupper($practicaI->Practicas) }}</td>
                    <td>{{ strtoupper($practicaI->DocenteTutor) }}</td>
                    <td>{{ strtoupper($practicaI->NombreTutorEmpresarial) }}</td>
                    <td>{{ strtoupper($practicaI->Empresa) }}</td>
                    <td>{{ strtoupper($practicaI->Nivel) }}</td>
                    <td>{{ strtoupper($practicaI->FechaInicio) }}</td>
                    <td>{{ strtoupper($practicaI->FechaFinalizacion) }}</td>
                    <td>{{ strtoupper($practicaI->HorasPlanificadas) }}</td>
                    <td>{{ $practicaI->Estado }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<form method="POST" action="{{ route('coordinador.reportesPracticaI') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fas fa-file-excel"></i> Generar Reporte
        </button>
    </form>
<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($estudiantesPracticaI->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">Anterior</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $estudiantesPracticaI->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
        </li>
        @endif

        @foreach ($estudiantesPracticaI->getUrlRange(1, $estudiantesPracticaI->lastPage()) as $page => $url)
        @if ($page == $estudiantesPracticaI->currentPage())
        <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach

        @if ($estudiantesPracticaI->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $estudiantesPracticaI->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Siguiente</span>
        </li>
        @endif
    </ul>
</div>

    </div>

    <hr>

    <div class="container" style="overflow-x: auto;">
        <h4>Estudiantes Practica II</h4>
        @csrf
        <table class="table table-bordered">
    <thead>
        <tr>
            <th>Estudiante</th>
            <th>Práctica</th>
            <th>Tutor Académico</th>
            <th>Tutor Empresarial</th>
            <th>Empresa</th>
            <th>Nivel</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Horas planificadas</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estudiantesPracticaII as $practicaI)
            @if ($practicaI->estudiante)
                <tr>
                    <td>{{ strtoupper($practicaI->estudiante->Apellidos) }} {{ strtoupper($practicaI->estudiante->Nombres) }}</td>
                    <td>{{ strtoupper($practicaI->Practicas) }}</td>
                    <td>{{ strtoupper($practicaI->DocenteTutor) }}</td>
                    <td>{{ strtoupper($practicaI->NombreTutorEmpresarial) }}</td>
                    <td>{{ strtoupper($practicaI->Empresa) }}</td>
                    <td>{{ strtoupper($practicaI->Nivel) }}</td>
                    <td>{{ strtoupper($practicaI->FechaInicio) }}</td>
                    <td>{{ strtoupper($practicaI->FechaFinalizacion) }}</td>
                    <td>{{ strtoupper($practicaI->HorasPlanificadas) }}</td>
                    <td>{{ $practicaI->Estado }}</td>
                   
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<form method="POST" action="{{ route('coordinador.reportesPracticaII') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fas fa-file-excel"></i> Generar Reporte
        </button>
    </form>
<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($estudiantesPracticaII->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">Anterior</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $estudiantesPracticaII->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
        </li>
        @endif

        @foreach ($estudiantesPracticaII->getUrlRange(1, $estudiantesPracticaII->lastPage()) as $page => $url)
        @if ($page == $estudiantesPracticaII->currentPage())
        <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach

        @if ($estudiantesPracticaII->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $estudiantesPracticaII->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Siguiente</span>
        </li>
        @endif
    </ul>
</div>


    </div>


@endsection

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    table,
    th,
    td {
        font-size: 0.8rem;
    }

    th,
    td {
        padding: 8px 12px;
        text-align: left;
        border: 1px solid #ddd;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
