<!DOCTYPE html>
<html>
<head>
    <title>Problema con solicitud</title>
</head>
<body>
<h1 style="text-align: center;">Saludos cordiales Estudiante de la SEDE Santo Domingo de los Colorados</h1>
<img src="https://fernandoromero.files.wordpress.com/2020/09/espe.jpg?w=1024" alt="Imagen de tu elección" style="display: block; margin: 0 auto; ">
<p>Nombre: {{ $estudiante->Nombres }}</p>
<p>Apellido: {{ $estudiante->Apellidos }}</p>
<p>Lo Sentimos se ha negado su solicitud de vinculacion revise el comentario del director de carrera</p>
<p>Comentario: {{ $estudiante->comentario }}</p>

<p>Saludos cordiales</p>
<p>ATT: Director de Carrera</p>
</body>
</html>