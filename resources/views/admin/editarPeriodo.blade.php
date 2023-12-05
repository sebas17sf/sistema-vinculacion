@extends('layouts.admin') 

@section('content')
<div class="container">
    <h4>Editar Período Académico</h4>

    <form method="POST" action="{{ route('admin.actualizarPeriodo', ['id' => $periodo->id]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="periodoInicio">Fecha de Inicio:</label>
            <input type="date" name="periodoInicio" class="form-control" value="" required>
        </div>

        <div class="form-group">
            <label for="periodoFin">Fecha de Fin:</label>
            <input type="date" name="periodoFin" class="form-control" value="" required>
        </div>

        <button type="submit" class="btn btn-outline-secondary btn-block">Actualizar</button>
    </form>
</div>
@endsection
