@extends('layouts.admin')

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
        <h4>Estudiantes a realizar Prácticas</h4>
        <hr>
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
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estudiantesConPracticaI as $practicaI)
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
                    <td>
                        <form action="{{ route('admin.actualizarEstadoEstudiante', ['id' => $practicaI->estudiante->EstudianteID]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="nuevoEstado">
                                <option value="En ejecucion">Aprobado</option>
                                <option value="Negado">Negar</option>
                            </select>
                            <button type="submit">Enviar</button>
                        </form>

                        <form action="{{ route('admin.editarNombreEmpresa', ['id' => $practicaI->estudiante->EstudianteID]) }}" method="GET">
                            <button type="submit">Cambiar Empresa</button>
                        </form>
                    </td>
                </tr>
            @endif
        @endforeach

        @foreach ($estudiantesConPracticaII as $practicaII)
            @if ($practicaII->estudiante)
                <tr>
                    <td>{{ strtoupper($practicaII->estudiante->Apellidos) }} {{ strtoupper($practicaII->estudiante->Nombres) }}</td>
                    <td>{{ strtoupper($practicaII->Practicas) }}</td>
                    <td>{{ strtoupper($practicaII->DocenteTutor) }}</td>
                    <td>{{ strtoupper($practicaII->NombreTutorEmpresarial) }}</td>
                    <td>{{ strtoupper($practicaII->Empresa) }}</td>
                    <td>{{ strtoupper($practicaII->Nivel) }}</td>
                    <td>{{ strtoupper($practicaII->FechaInicio) }}</td>
                    <td>{{ strtoupper($practicaII->FechaFinalizacion) }}</td>
                    <td>{{ strtoupper($practicaII->HorasPlanificadas) }}</td>
                    <td>{{ $practicaII->Estado }}</td>
                    <td>
                        <form action="{{ route('admin.actualizarEstadoEstudiante2', ['id' => $practicaII->estudiante->EstudianteID]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="nuevoEstado">
                                <option value="En ejecucion">Aprobado</option>
                                <option value="Negado">Negar</option>
                            </select>
                            <button type="submit">Enviar</button>
                        </form>

                        <form action="{{ route('admin.editarNombreEmpresa', ['id' => $practicaII->estudiante->EstudianteID]) }}" method="get">
                            <button type="submit">Cambiar</button>
                        </form>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

    </div>


    <hr>

    <div class="container" style="overflow-x: auto;">
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
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estudiantesPracticas as $practicaI)
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
                    <td>
                        <form
                            action="{{ route('admin.actualizarEstadoEstudiante', ['id' => $practicaI->estudiante->EstudianteID]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <select name="nuevoEstado">
                                <option value="Terminado">Terminado</option>
                                <option value="En ejecucion">Ejecucion</option>
                            </select>
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
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

    </div>

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
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estudiantesPracticasII as $practicaI)
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
                    <td>
                        <form
                            action="{{ route('admin.actualizarEstadoEstudiante2', ['id' => $practicaI->estudiante->EstudianteID]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <select name="nuevoEstado">
                                <option value="Terminado">Terminado</option>
                                <option value="En ejecucion">Ejecucion</option>
                            </select>
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
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
