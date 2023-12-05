<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add Google Icons link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
       body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin: 50px auto 0; /* Ajusta el valor del margin-top para controlar la separación desde arriba */
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Registro de Usuario</h3>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="Nombre">Nombre:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="material-icons">person</i></span>
                    </div>
                    <input type="text" class="form-control" id="Nombre" name="Nombre" required>
                </div>
            </div>

            <div class="form-group">
                <label for="Apellido">Apellido:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="material-icons">person</i></span>
                    </div>
                    <input type="text" class="form-control" id="Apellido" name="Apellido" required>
                </div>
            </div>

            <div class="form-group">
                <label for="CorreoElectronico">Correo Electrónico:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="material-icons">email</i></span>
                    </div>
                    <input type="email" class="form-control" id="CorreoElectronico" name="CorreoElectronico" required>
                </div>
            </div>

            <div class="form-group">
                <label for="Contrasena">Contraseña:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="material-icons">lock</i></span>
                    </div>
                    <input type="password" class="form-control" id="Contrasena" name="Contrasena" required>
                </div>
            </div>

            <div class="form-group">
                <label for="TipoUsuario">Tipo de Usuario:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="material-icons">person_outline</i></span>
                    </div>
                    <select class="form-control" id="TipoUsuario" name="TipoUsuario" required>
                        <option value="Estudiante">Estudiante</option>
                        <option value="Profesor">Profesor</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
        </form>
    </div>

    <!-- Add Bootstrap JavaScript and jQuery dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
