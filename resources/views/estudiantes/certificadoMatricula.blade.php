<!DOCTYPE html> <html> <head>
<title>Certificado de Matrícula</title> <style> body { background-image: url('imagenespe.jpg'); background-size: cover;
    background-repeat: no-repeat; margin: 0; /* Elimina los márgenes por defecto */ } .container { position: relative;
    max-width: 800px; /* Ajusta el ancho de tu contenido */ margin: 0 auto; /* Centra el contenido en la página */
    padding: 20px; background: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente para el contenido */ } .logo {
    position: absolute; top: 20px; left: 20px; width: 200px; z-index: 1; } h1 { margin-top: 140px; } </style> </head>
    <body> <div class="container"> <img src="plantillas/favicon.jpg" class="logo" alt="Logotipo">

        <h1>Certificado de Matrícula</h1>
        <p>Documento Generado por el Sistema Vinculacion-Practicas ESPE.</p>
        <hr>
        <h3>Datos del Estudiante</h3>
        <p><strong>Nombre:</strong> {{ $estudiante->Apellidos }} {{ $estudiante->Nombres }}</p>
        <p><strong>Matrícula:</strong> {{ $estudiante->Cohorte }}</p>
        <p><strong>Periodo:</strong> {{ $estudiante->Periodo }}</p>
        <p><strong>Carrera:</strong> {{ $estudiante->Carrera }}</p>
        <p><strong>Correo:</strong> {{ $estudiante->Correo }}</p>
        <p><strong>Teléfono:</strong> {{ $estudiante->celular }}</p>
        <p><strong>Cédula:</strong> {{ $estudiante->cedula }}</p>
        <p><strong>ESPE ID:</strong> {{ $estudiante->espe_id }}</p>
        <p><strong>Departamento:</strong> {{ $estudiante->Departamento }}</p>

        <hr>
        <h3>Información de proceso Actual</h3>
        <p><strong>Estado:</strong>
            @if ($estudiante->Estado == 'Aprobado')
            Aprobado Vinculación
            @elseif ($estudiante->Estado == 'Aprobado-practicas')
            Practicas
            @else
            {{ $estudiante->Estado }}
            @endif
        </p>
        </div>
        </body>

        </html>