@extends('layouts.registro')

@section('title', 'Ingresar Datos del Estudiante')

@section('content')


<div class="container mt-5">
    <h2 class="mb-4 text-center">Ingresar Datos del Estudiante</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('estudiantes.store') }}" class="custom-form">
        @csrf

        <div class="form-group">
    <label for="Nombres"><i class="material-icons">person</i> Nombres:</label>
    <input id="Nombres" type="text" class="form-control" name="Nombres" required autofocus
        placeholder="Ingrese sus Nombres" pattern="[A-Za-zÁ-úñÑ\s]+" title="Ingrese solo letras (sin caracteres especiales)">
</div>


<div class="form-group">
    <label for="Apellidos"><i class="material-icons">person</i> Apellidos:</label>
    <input id="Apellidos" type="text" class="form-control" name="Apellidos" required
        placeholder="Ingrese sus Apellidos" pattern="[A-Za-zÁ-úñÑ\s]+" title="Ingrese solo letras (sin caracteres especiales)">
</div>


        <div class="form-group">
            <label for="espe_id"><i class="material-icons">person</i> ESPE ID:</label>
            <input id="espe_id" type="text" class="form-control" name="espe_id" required
                placeholder="Ingrese su ESPE ID">
        </div>

        <div class="form-group">
            <label for="celular"><i class="material-icons">phone</i> Celular:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">+593</span>
                </div>
                <input id="celular" type="text" class="form-control" name="celular" required pattern="[0-9]{10}"
                    placeholder="Ingrese su número de celular (10 dígitos)">
            </div>
        </div>

        <div class="form-group">
    <label for="cedula"><i class="material-icons">credit_card</i> Cédula:</label>
    <input id="cedula" type="text" class="form-control" name="cedula" required pattern="[0-9]{10}" title="Ingrese un número de cédula válido (10 dígitos)" placeholder="Ingrese su número de cédula (10 dígitos)">
</div>


        <div class="form-group">
            <label for="Cohorte"><i class="material-icons">event_note</i> Cohorte:</label>
            <select class="form-control" id="Cohorte" name="Cohorte" required>
                @foreach ($cohortes as $cohorte)
                <option value="{{ $cohorte->Cohorte }}">{{ $cohorte->Cohorte }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Periodo"><i class="material-icons">event_note</i> Periodo:</label>
            <select class="form-control" id="Periodo" name="Periodo" required>
                @foreach ($periodos as $periodo)
                <option value="{{ $periodo->Periodo }}">{{ $periodo->Periodo }}</option>
                @endforeach
            </select>
        </div>



        <div class="form-group">
            <label for="Carrera"><i class="material-icons">school</i> Carrera:</label>
            <select class="form-control" id="Carrera" name="Carrera" required>
                <option value="Ingeniería en Tecnologías de la información">Ingeniería en Tecnologías de la información
                </option>
                <option value="Ingeniería en Agropecuaria">Ingeniería en Agropecuaria</option>
                <option value="Ingeniería en Biotecnologia">Ingeniería en Biotecnologia</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Provincia"><i class="material-icons">location_city</i> Localidad:</label>
            <select class="form-control" id="Provincia" name="Provincia" required>
                <option value="Santo Domingo">Santo Domingo</option>
                <option value="Luz de America">Luz de America</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Departamento"><i class="material-icons">location_city</i> Departamento:</label>
            <select class="form-control" id="Departamento" name="Departamento" required>
                <option value="Ciencias de la Computación">DCCO - Departamento de Computación</option>
                <option value="Ciencias Exactas">DCEX - Ciencias Exactas</option>
                <option value="Ciencias de la Vida y Agricultura">DCVA - Departamento de Ciencias de la Vida y
                    Agricultura</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Datos</button>
    </form>

</div>

@endsection