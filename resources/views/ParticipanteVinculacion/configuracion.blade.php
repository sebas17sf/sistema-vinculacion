@extends('layouts.participante')

@section('title', 'Editar Perfil')

@section('content')


<style>
    .user-avatar {
        text-align: center;
    }

    .user-avatar img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        display: block;
    }

    .card-header {
        background-color: #f8f9fa; /* Cambia el color de fondo del encabezado */
    }

    .form-group label {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .form-group label i {
        margin-right: 10px;
    }

    .btn-primary {
        background-color: #6c757d; /* Cambia el color del botón a gris */
        border-color: #6c757d;
    }

    .btn-primary:hover {
        background-color: #495057; /* Cambia el color al pasar el cursor por encima */
        border-color: #495057;
    }
</style>

<br>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="user-avatar">
                        <!-- Aquí puedes incluir la imagen del usuario en formato circular -->
                        <img src="https://cdn-icons-png.flaticon.com/512/456/456212.png" alt="Foto de perfil" class="rounded-circle" width="100">
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title text-center">Editar Perfil</h5>
                    <form method="POST" action="{{ route('ParticipanteVinculacion.actualizarConfiguracion', ['ID_Participante' => Auth::user()->UserID]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nombre">
                                <i class="material-icons">person</i> Nombres
                            </label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ Auth::user()->Nombre }}">
                        </div>

                        <div class="form-group">
                            <label for="apellido">
                                <i class="material-icons">person</i> Apellidos
                            </label>
                            <input type="text" name="apellido" id="apellido" class="form-control" value="{{ Auth::user()->Apellido }}">
                        </div>

                        <div class="form-group">
                            <label for="contrasena">
                                <i class="material-icons">lock</i> Nueva Contraseña
                            </label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="material-icons">save</i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
