@extends('layouts.app')

@section('title', 'Editar Datos del Estudiante')


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



<div class="container">
    <br>
    <br>
    <h4 class="mb-4 text-center"><i class="material-icons">edit</i> Editar Datos del Estudiante</h4>
    <hr>


    <form method="POST" action="{{ route('estudiantes.update', ['estudiante' => $estudiante->EstudianteID]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Nombres"><i class="material-icons">person</i> Nombres:</label>
            <input id="Nombres" type="text" class="form-control" name="Nombres" value="{{ $estudiante->Nombres }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="Apellidos"><i class="material-icons">person</i> Apellidos:</label>
            <input id="Apellidos" type="text" class="form-control" name="Apellidos" value="{{ $estudiante->Apellidos }}" required>
        </div>

        <div class="form-group">
            <label for="espe_id"><i class="material-icons">info</i> ESPE ID:</label>
            <input id="espe_id" type="text" class="form-control" name="espe_id" value="{{ $estudiante->espe_id }}" required>
        </div>

        <div class="form-group">
            <label for="celular"><i class="material-icons">phone</i> Celular:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">+593</span>
                </div>
                <input id="celular" type="text" class="form-control" name="celular" value="{{ $estudiante->celular }}" required pattern="[0-9]{10}">
            </div>
        </div>

        <div class="form-group">
            <label for="cedula"><i class="material-icons">credit_card</i> Cédula:</label>
            <input id="cedula" type="text" class="form-control" name="cedula" value="{{ $estudiante->cedula }}" required>
        </div>

        <div class="form-group">
            <label for="Cohorte"><i class="material-icons">calendar_today</i> Cohorte:</label>
            <select class="form-control" id="Cohorte" name="Cohorte" required>
                <option value="201710" @if($estudiante->Cohorte == '201710') selected @endif>201710</option>
                <option value="201720" @if($estudiante->Cohorte == '201720') selected @endif>201720</option>
                <option value="201810" @if($estudiante->Cohorte == '201810') selected @endif>201810</option>
                <option value="201811" @if($estudiante->Cohorte == '201811') selected @endif>201811</option>
                <option value="201950" @if($estudiante->Cohorte == '201950') selected @endif>201950</option>
                <option value="201951" @if($estudiante->Cohorte == '201951') selected @endif>201951</option>
                <option value="202050" @if($estudiante->Cohorte == '202050') selected @endif>202050</option>
                <option value="202051" @if($estudiante->Cohorte == '202051') selected @endif>202051</option>
            </select>
        </div>

        <div class="form-group">
    <label for="Carrera"><i class="material-icons">school</i> Carrera:</label>
    <select class="form-control" id="Carrera" name="Carrera" required>
        <option value="Ingeniería en Tecnologías de la información" @if($estudiante->Carrera == 'Ingeniería en Tecnologías de la información') selected @endif>Ingeniería en Tecnologías de la información</option>
        <option value="Ingeniería en Agropecuaria" @if($estudiante->Carrera == 'Ingeniería en Agropecuaria') selected @endif>Ingeniería en Agropecuaria</option>
        <option value="Ingeniería en Biotecnologia" @if($estudiante->Carrera == 'Ingeniería en Biotecnologia') selected @endif>Ingeniería en Biotecnologia</option>
    </select>
</div>
<div class="form-group">
    <label for="Provincia"><i class="material-icons">location_city</i> Provincia:</label>
    <select class="form-control" id="Provincia" name="Provincia" required>
        <option value="Santo Domingo" @if($estudiante->Provincia == 'Santo Domingo de los Tsáchilas') selected @endif>Santo Domingo de los Tsáchilas</option>
        <option value="Luz de America" @if($estudiante->Provincia == 'Luz de America') selected @endif>Luz de America</option>
    </select>
</div>


        <div class="form-group">
            <label for="Departamento"><i class="material-icons">school</i> Departamento:</label>
            <select class="form-control" id="Departamento" name="Departamento" required>
                <option value="Ciencias de la Computacion" @if($estudiante->Departamento == 'DCCO') selected @endif>DCCO - Departamento de Computación</option>
                <option value="Ciencias Exactas" @if($estudiante->Departamento == 'DCEX') selected @endif>DCEX - Ciencias Exactas</option>
                <option value="Ciencias de la Vida y Agricultura" @if($estudiante->Departamento == 'DCVA') selected @endif>DCVA - Departamento de Ciencias de la Vida y Agricultura</option>
            </select>
        </div>

        <button type="submit" class="btn btn-sm btn-secondary"><i class="material-icons">save</i> Guardar Cambios</button>
    </form>

</div>
<style>
    .custom-form {
        max-width: 500px; /* Ancho máximo del formulario */
        margin: 0 auto; /* Centrar horizontalmente */
        padding: 20px; /* Espaciado interior */
        border: 1px solid #ccc; /* Borde del formulario */
    }
    .custom-form h2 {
        text-align: center; /* Alinear al centro */
        margin-bottom: 20px; /* Espaciado inferior */
    }
    
</style>
@endsection
