@extends('layouts.director')

@section('title', 'Panel de Director')

@section('content')
    <div class="container">
 
        <h4>Listado de Proyectos</h4>
        <div class="d-flex">
        <form method="GET" action="{{ route('director.indexProyectos') }}" class="mr-3">
            <label for="elementosPorPagina">Estudiantes a visualizar:</label>
            <select name="elementosPorPagina" id="elementosPorPagina" onchange="this.form.submit()">
                <option value="10" @if(request('elementosPorPagina', $elementosPorPagina)==10) selected @endif>10
                </option>
                <option value="20" @if(request('elementosPorPagina', $elementosPorPagina)==20) selected @endif>20
                </option>
                <option value="50" @if(request('elementosPorPagina', $elementosPorPagina)==50) selected @endif>50
                </option>
                <option value="100" @if(request('elementosPorPagina', $elementosPorPagina)==100) selected @endif>100
                </option>
            </select>
        </form>

        <form method="GET" action="{{ route('director.indexProyectos') }}">
    @csrf
    <div class="form-group">
        <label for="buscar"></label>
        <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" placeholder="Buscar proyectos">
        <button type="submit" >Buscar</button>
    </div>
</form>
</div>



        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tutor</th>
                        <th>Nombre del profesor participante</th>
                        <th>Nombre del proyecto</th>
                        <th>Descripción</th>
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
                    @foreach ($proyectos as $proyecto)
                        <tr>
                            <td>{{ strtoupper($proyecto->ApellidoProfesor) }} {{ strtoupper($proyecto->NombreProfesor) }}</td>
                            <td>{{ strtoupper($proyecto->ApellidoAsignado) }} {{ strtoupper($proyecto->NombreAsignado) }}</td>
                            <td>{{ strtoupper($proyecto->NombreProyecto) }}</td>
                            <td>{{ strtoupper($proyecto->DescripcionProyecto) }}</td>
                            <td>{{ strtoupper($proyecto->CorreoElectronicoTutor) }}</td>
                            <td>{{ strtoupper($proyecto->CorreoProfeAsignado) }}</td>
                            <td>{{ strtoupper($proyecto->DepartamentoTutor) }}</td>
                            <td>{{ strtoupper($proyecto->FechaInicio) }}</td>
                            <td>{{ strtoupper($proyecto->FechaFinalizacion) }}</td>
                            <td>{{ strtoupper($proyecto->cupos) }}</td>
                            <td>{{ strtoupper($proyecto->Estado) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <form method="POST" action="{{ route('coordinador.reportesProyectos') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fas fa-file-excel"></i> Generar Reporte
        </button></form>


            <div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($proyectos->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">Anterior</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $proyectos->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
        </li>
        @endif

        @foreach ($proyectos->getUrlRange(1, $proyectos->lastPage()) as $page => $url)
        @if ($page == $proyectos->currentPage())
        <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach

        @if ($proyectos->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $proyectos->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Siguiente</span>
        </li>
        @endif
    </ul>
</div>
        </div>
    </div>
@endsection

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap; /* Evita el desbordamiento de texto */
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
