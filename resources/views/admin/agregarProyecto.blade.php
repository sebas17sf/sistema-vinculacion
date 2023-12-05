@extends('layouts.admin')

@section('title', 'Agregar Proyecto')

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session("success") }}',
            confirmButtonText: 'Ok'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session("error") }}',
            confirmButtonText: 'Ok'
        });
    </script>
@endif

@section('content')
    <div class="container mt-5">
        <h4>Agregar Nuevo Proyecto</h4>
        <form method="POST" action="{{ route('admin.crearProyecto') }}">
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
                <input type="text" name="NombreProyecto" class="form-control" placeholder="Ingrese el Nombre del Proyecto" required>
            </div>
            <div class="form-group">
                <label for="DescripcionProyecto">Descripción del Proyecto:</label>
                <textarea name="DescripcionProyecto" class="form-control" placeholder="Describa el Proyecto" required></textarea>
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
    <input type="number" name="cupos" class="form-control" placeholder="Ingrese los Cupos para este proyecto" required min="1" max="10">
</div>


            <div class="form-group">
                <label for="Estado">Estado:</label>
                <select name="Estado" class="form-control" required>
                    <option value="Ejecucion">En Ejecución</option>
                    <option value="Terminado">Terminado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Agregar Proyecto</button>
        </form>
    </div>
@endsection


<style>
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


