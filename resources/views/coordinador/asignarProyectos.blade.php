@extends('layouts.coordinador')

@section('content')
    <div class="container">
        <h2>Asignar Proyecto</h2>
        <form method="POST" action="{{ route('coordinador.guardarAsignacion') }}">
            @csrf

            <div class="form-group">
    <label for="proyecto_id">Proyecto Disponible:</label>
    <select name="proyecto_id" id="proyecto_id" class="form-control">
        @foreach($proyectosDisponibles as $proyecto)
            @if ($proyecto->cupos > 0)
                <option value="{{ $proyecto->ProyectoID }}">
                    {{ $proyecto->ApellidoProfesor }} {{ $proyecto->NombreProfesor }} - Cupos disponibles: {{ $proyecto->cupos }} - {{ $proyecto->DepartamentoTutor }} - {{ $proyecto->NombreProyecto }}
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
                <input type="date" name="fecha_asignacion" id="fecha_asignacion" class="form-control" value="{{ now()->toDateString() }}">
            </div>

            <button type="submit" class="btn btn-primary">Asignar Proyecto</button>
        </form>
    </div>
@endsection
