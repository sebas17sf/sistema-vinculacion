@extends('layouts.admin')

@section('content')
    <div class="container">
        <h4>Cambiar Empresa para el Estudiante</h4>

        <form method="POST" action="{{ route('admin.actualizarNombreEmpresa', ['id' => $estudiante->EstudianteID]) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombreEstudiante">Nombre del Estudiante:</label>
                <input type="text" class="form-control" name="nombreEstudiante" value="{{ $estudiante->Apellidos }} {{ $estudiante->Nombres }}" readonly>
            </div>

            <div class="form-group">
                <label for="nombreEstudiante">Carrera:</label>
                <input type="text" class="form-control" name="nombreEstudiante" value="{{ $estudiante->Carrera }}" readonly>
            </div>

            <div class="form-group">
                <label for="nombreEstudiante">Carrera:</label>
                <input type="text" class="form-control" name="nombreEstudiante" value="{{ $estudiante->Departamento }}" readonly>
            </div>

            <div class="form-group">
                <label for="nombreEstudiante">Periodo:</label>
                <input type="text" class="form-control" name="nombreEstudiante" value="{{ $estudiante->Periodo }}" readonly>
            </div>

            <div class="form-group">
                <label for="nombreEstudiante">Cohorte:</label>
                <input type="text" class="form-control" name="nombreEstudiante" value="{{ $estudiante->Cohorte }}" readonly>
            </div>

            <div class="form-group">
            <label for="nuevoNombreEmpresa">Selecciona una nueva empresa:</label>
            <select name="nuevoNombreEmpresa" class="form-control">
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->nombreEmpresa }}">{{ $empresa->nombreEmpresa }} - Requiere: {{ $empresa->actividadesMacro }}</option>
                @endforeach
            </select>
        </div>

            <button type="submit" class="btn btn-sm btn-secondary">Actualizar Empresa</button>
        </form>
    </div>
@endsection
