@extends('layouts.coordinador')

@section('title', 'Agregar Proyecto')

@section('content')
    <div class="container mt-5">
        <h2>Agregar Nuevo Proyecto</h2>
        <form method="POST" action="{{ route('coordinador.crearProyecto') }}">
            @csrf

            <div class="form-group">
                <label for="DirectorProyecto">Director del Proyecto:</label>
                <select name="DirectorProyecto" class="form-control" required>
                    <option value="">Seleccionar Director</option>
                    @foreach ($profesores as $profesor)
                        <option value="{{ $profesor->Correo }}">Nombres: {{ $profesor->Apellidos }} {{ $profesor->Nombres }} - Departamento: {{ $profesor->Departamento }} - Correo: {{ $profesor->Correo }}  </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ProfesorParticipante">Profesor Participante:</label>
                <select name="ProfesorParticipante" class="form-control" required>
                    <option value="">Seleccionar Profesor Participante</option>
                    @foreach ($profesores as $profesor)
                    <option value="{{ $profesor->Correo }}">Nombres: {{ $profesor->Apellidos }} {{ $profesor->Nombres }} - Departamento: {{ $profesor->Departamento }} - Correo: {{ $profesor->Correo }}  </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="NombreProyecto">Nombre del Proyecto:</label>
                <input type="text" name="NombreProyecto" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="DescripcionProyecto">Descripción del Proyecto:</label>
                <textarea name="DescripcionProyecto" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="DepartamentoTutor">Departamento:</label>
                <select name="DepartamentoTutor" class="form-control" required>
                    <option value="Ciencias de la Computación">DCCO - Departamento de Computación</option>
                    <option value="Ciencias Exactas">DCEX - Ciencias Exactas</option>
                    <option value="Ciencias de la Vida y Agricultura">DCVA - Departamento de Ciencias de la Vida y Agricultura</option>
                </select>
            </div>
            <div class="form-group">
                <label for="FechaInicio">Fecha de Inicio:</label>
                <input type="date" name="FechaInicio" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="FechaFinalizacion">Fecha de Finalización:</label>
                <input type="date" name="FechaFinalizacion" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cupos">Cupos:</label>
                <input type="number" name="cupos" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Estado">Estado:</label>
                <select name="Estado" class="form-control" required>
                    <option value="Ejecucion">En Ejecución</option>
                    <option value="Terminado">Terminado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Proyecto</button>
        </form>
    </div>
@endsection
