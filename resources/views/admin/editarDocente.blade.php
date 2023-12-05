@extends('layouts.admin')

@section('title', 'Editar Docente')

@section('content')



    <form action="{{ route('admin.actualizarMaestro', ['id' => $maestro->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="nombres"><strong>Ingrese Nombres:</strong></label>
            <input type="text" id="nombres" name="nombres" class="form-control" value="{{ $maestro->Nombres }}" required>
        </div>

        <div class="form-group col-md-4">
            <label for="apellidos"><strong>Ingrese Apellidos:</strong></label>
            <input type="text" id="apellidos" name="apellidos" class="form-control" value="{{ $maestro->Apellidos }}" required>
        </div>

        <div class="form-group col-md-4">
            <label for="correo"><strong>Ingrese Correo:</strong></label>
            <input type="email" id="correo" name="correo" class="form-control" value="{{ $maestro->Correo }}" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="cedula"><strong>Ingrese la Cedula:</strong></label>
            <input type="text" id="cedula" name="cedula" class="form-control" value="{{ $maestro->Cedula }}" required>
        </div>

        <div class="form-group col-md-4">
            <label for="departamento"><strong>Seleccione el departamento al que pertenece:</strong></label>
            <select id="departamento" name="departamento" class="form-control" required>
                <option value="Ciencias de la Computación" {{ $maestro->Departamento === 'Ciencias de la Computación' ? 'selected' : '' }}>Departamento de Ciencias de Computación</option>
                <option value="Ciencias de la Vida" {{ $maestro->Departamento === 'Ciencias de la Vida' ? 'selected' : '' }}>Departamento de Ciencias de la Vida</option>
                <option value="Ciencias Exactas" {{ $maestro->Departamento === 'Ciencias Exactas' ? 'selected' : '' }}>Departamento de Ciencias Exactas</option>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-outline-secondary btn-block">Guardar Cambios</button>
</form>

@endsection
