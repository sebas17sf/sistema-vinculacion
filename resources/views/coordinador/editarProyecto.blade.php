@extends('layouts.admin')

@section('title', 'Editar Proyecto')

@section('content')
    <div class="container">
        <h2>Editar Proyecto</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('coordinador.updateProyecto', ['ProyectoID' => $proyecto->ProyectoID]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="NombreProfesor">Nombre del Profesor:</label>
                <input type="text" class="form-control" id="NombreProfesor" name="NombreProfesor" value="{{ $proyecto->NombreProfesor }}" required>
            </div>

            <div class="form-group">
                <label for="ApellidoProfesor">Apellido del Profesor:</label>
                <input type="text" class="form-control" id="ApellidoProfesor" name="ApellidoProfesor" value="{{ $proyecto->ApellidoProfesor }}" required>
            </div>

            <div class="form-group">
                <label for="NombreAsignado">Nombre del profesor participante del proyecto:</label>
                <input type="text" class="form-control" id="NombreAsignado" name="NombreAsignado" value="{{ $proyecto->NombreAsignado }}" required>
            </div>

            <div class="form-group">
                <label for="ApellidoAsignado">Apellido del profesor participante del proyecto:</label>
                <input type="text" class="form-control" id="ApellidoAsignado" name="ApellidoAsignado" value="{{ $proyecto->ApellidoAsignado }}" required>
            </div>

            <div class="form-group">
                <label for="CorreoElectronicoTutor">Correo Electrónico del Tutor:</label>
                <input type="email" class="form-control" id="CorreoElectronicoTutor" name="CorreoElectronicoTutor" value="{{ $proyecto->CorreoElectronicoTutor }}" required>
            </div>

            <div class="form-group">
                <label for="CorreoProfeAsignado">Correo del profesor participante del proyecto:</label>
                <input type="email" class="form-control" id="CorreoProfeAsignado" name="CorreoProfeAsignado" value="{{ $proyecto->CorreoProfeAsignado }}" required>
            </div>

            <div class="form-group">
                <label for="NombreProyecto">Nombre del Proyecto:</label>
                <input type="text" class="form-control" id="NombreProyecto" name="NombreProyecto" value="{{ $proyecto->NombreProyecto }}" required>
            </div>

            <div class="form-group">
                <label for="DescripcionProyecto">Descripción del Proyecto:</label>
                <textarea class="form-control" id="DescripcionProyecto" name="DescripcionProyecto" rows="4" required>{{ $proyecto->DescripcionProyecto }}</textarea>
            </div>


            <div class="form-group">
                <label for="DepartamentoTutor">Departamento del Tutor:</label>
                <select class="form-control" id="DepartamentoTutor" name="DepartamentoTutor" required>
                    <option value="Ciencias de la Computación" {{ $proyecto->DepartamentoTutor === 'DCCO' ? 'selected' : '' }}>DCCO - Departamento de Computación</option>
                    <option value="Ciencias Exactas" {{ $proyecto->DepartamentoTutor === 'DCEX' ? 'selected' : '' }}>DCEX - Ciencias Exactas</option>
                    <option value="Ciencias de la Vida y Agricultura" {{ $proyecto->DepartamentoTutor === 'DCVA' ? 'selected' : '' }}>DCVA - Departamento de Ciencias de la Vida y Agricultura</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cupos">Cupos:</label>
                <input type="number" class="form-control" id="cupos" name="cupos" value="{{ $proyecto->cupos }}" required>
            </div>

            <div class="form-group">
                <label for="FechaInicio">Fecha de Inicio:</label>
                <input type="date" class="form-control" id="FechaInicio" name="FechaInicio" value="{{ $proyecto->FechaInicio }}" required>
            </div>

            <div class="form-group">
                <label for="FechaFinalizacion">Fecha de Finalización:</label>
                <input type="date" class="form-control" id="FechaFinalizacion" name="FechaFinalizacion" value="{{ $proyecto->FechaFinalizacion }}" required>
            </div>

            <div class="form-group">
                <label for="Estado">Estado del Proyecto:</label>
                <select class="form-control" id="Estado" name="Estado" required>
                    <option value="Ejecucion" {{ $proyecto->Estado === 'Ejecucion' ? 'selected' : '' }}>Ejecucion</option>
                    <option value="Terminado" {{ $proyecto->Estado === 'Terminado' ? 'selected' : '' }}>Terminado</option>
                </select>
            </div>

            <button type="submit" class="btn btn-sm btn-secondary">Guardar Cambios</button>
        </form>
    </div>
@endsection
