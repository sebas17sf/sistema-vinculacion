<!DOCTYPE html>
<html>
<head>
    <title>Correo de Aprobación</title>
</head>
<body>
    <h1 style="text-align: center;">Saludos cordiales Estudiante de la SEDE Santo Domingo de los Colorados</h1>
    <img src="https://fernandoromero.files.wordpress.com/2020/09/espe.jpg?w=1024" alt="Imagen de tu elección" style="display: block; margin: 0 auto;">
    <p>Nombre: {{ $estudiante->Nombres }}</p>
    <p>Apellido: {{ $estudiante->Apellidos }}</p>
    <p>ID ESPE: {{ $estudiante->espe_id }}</p>
    <p>Celular: {{ $estudiante->celular }}</p>
    <p>Cedula: {{ $estudiante->cedula }}</p>
    <p>Cohorte: {{ $estudiante->Cohorte }}</p>
    <p>Departamento: {{ $estudiante->Departamento }}</p>
    <p>Estar atento de su cuenta. Pronto sera asignado aun Proyecto</p>
    <p>Saludos cordiales</p>
    <p>ATT: Director de Carrera</p>
</body>
</html>
