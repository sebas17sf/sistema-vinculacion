@extends('layouts.admin')

@section('title', 'Proyectos')

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
        <h4>Listado de Proyectos</h4>

        <a href="{{ route('admin.agregarProyecto') }}" class="btn btn-outline-secondary btn-sm">
            <i class="material-icons">add</i> Proyecto
        </a>

        <br><br>

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
                    <th>Acciones</th>
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
                        <td>
                            <a href="{{ route('admin.editarProyecto', ['ProyectoID' => $proyecto->ProyectoID]) }}"
                                class="btn btn-outline-secondary btn-block">
                                <i class="material-icons">edit</i>
                            </a>
                            <form
                                action="{{ route('admin.deleteProyecto', ['ProyectoID' => $proyecto->ProyectoID]) }}"
                                method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-block"
                                    onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form method="POST" action="{{ route('coordinador.reportesProyectos') }}">
    @csrf
    <button type="submit" class="btn btn-sm btn-secondary">
    <i class="fas fa-file-excel"></i> Generar Reporte
    </button>
</form>
    </div>

    

    
    <hr>

    <div class="container">
    <button id="toggleFormBtn" class="btn btn-outline-secondary btn-block">Asignar estudiante</button>
    <div id="asignarEstudiante" style="display: none;">

            <HR>
        <h4>Asignar Proyecto</h4>
        <form method="POST" action="{{ route('admin.guardarAsignacion') }}">
            @csrf

            <div class="form-group">
                <label for="proyecto_id">Proyecto Disponible:</label>
                <select name="proyecto_id" id="proyecto_id" class="form-control">
                    @foreach($proyectosDisponibles as $proyecto)
                    @if ($proyecto->cupos > 0)
                    <option value="{{ $proyecto->ProyectoID }}">
                        <div>{{ $proyecto->ApellidoProfesor }} {{ $proyecto->NombreProfesor }} - Cupos disponibles: {{
                            $proyecto->cupos }} - {{ $proyecto->DepartamentoTutor }}</div>
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="estudiante_id">Estudiante Aprobado:</label>
                <select name="estudiante_id" id="estudiante_id" class="form-control">
                    @foreach($estudiantesAprobados as $estudiante)
                    <option value="{{ $estudiante->EstudianteID }}">
                        {{ $estudiante->Nombres }} {{ $estudiante->Apellidos }} - {{ $estudiante->Departamento }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_asignacion">Fecha de Asignación:</label>
                <input type="date" name="fecha_asignacion" id="fecha_asignacion" class="form-control"
                    value="{{ now()->toDateString() }}">
            </div>

            <button type="submit" class="btn btn-secondary">Asignar Proyecto</button>
        </form>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.css">
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        // Manejar el clic en el botón para mostrar/ocultar el formulario
        $("#toggleFormBtn").click(function () {
            $("#asignarEstudiante").toggle();
            // Cambiar el texto del botón según si el formulario está visible u oculto
            if ($("#asignarEstudiante").is(":visible")) {
                $(this).text("Ocultar Asignar Estudiante");
            } else {
                $(this).text("Asignar estudiante");
            }
        });
    });
</script>

@endsection



<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        font-size: 14px;
    }

    th,
    td {
        padding: 8px 12px;
        text-align: left;
        border: 1px solid #ddd;
        white-space: nowrap;
        /* Evita el salto de línea */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Agrega puntos suspensivos si el contenido es demasiado largo */
    }

    th {
        background-color: #f2f2f2;
    }

    body,
    input,
    select,
    th,
    td,
    label,
    button,
    table {
        background-color: #F5F5F5;
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.5;

    }
</style>
