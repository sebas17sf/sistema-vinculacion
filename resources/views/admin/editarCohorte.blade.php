@extends('layouts.admin')

@section('content')
    <div class="container">
        <h4>Editar Cohorte</h4>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.actualizarCohorte', ['id' => $cohorte->ID_cohorte]) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="cohorte">Ingrese la Cohorte:</label>
                <input type="text" name="cohorte" id="cohorte" class="form-control" value="{{ $cohorte->Cohorte }}" required>
            </div>
            <button type="submit" class="btn btn-outline-secondary btn-block">Actualizar Cohorte</button>
        </form>
    </div>
@endsection
