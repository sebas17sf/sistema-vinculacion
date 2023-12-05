<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nuestro Equipo</title>
    <!-- Agrega el enlace al archivo CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Agrega el enlace a los íconos de Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<style>
    body {
        background-color: #009c8c;
        font-family: Arial, sans-serif;
        color: #fff;
    }

    .navbar {
        background-color: #fff;
        color: #009c8c;
    }

    .navbar-brand img {
        width: 150px;
        height: 50px;
    }

    .navbar-toggler-icon {
        background-color: #009c8c;
    }

    .team {
        padding: 50px 0;
    }

    .header h1 {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    h4 {
        font-size: 24px;
        font-weight: bold;
        margin-top: 20px;
    }

    p {
        font-size: 18px;
        line-height: 1.5;
    }

    .img-block h4 {
        margin-top: 10px;
    }

    /* Agregamos una clase para las tarjetas */
    .mission-vision-card {
        height: 100%;
    }
</style>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="https://www.espe.edu.ec/wp-content/uploads/2023/01/WhatsApp-Image-2023-01-12-at-09.11.54.jpeg" alt="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white" href="{{ route('login') }}">Acceder</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="team text-center py-5">
    <div class="container">
        <div class="header my-5">
            <h1>DIRECCIONAMIENTO ESTRATÉGICO</h1>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mission-vision-card h-100"> <!-- Agregamos la clase h-100 para que tengan la misma altura -->
                    <div class="card-body">
                        <h4 class="card-title text-dark">Misión</h4>
                        <p class="card-text text-justify text-dark">La Universidad de las Fuerzas Armadas-ESPE forma personas en el campo científico y tecnológico bajo un marco de principios y valores; y, genera conocimiento transferible para contribuir al progreso del país y Fuerzas Armadas, a través de la docencia, investigación y vinculación con la sociedad.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mission-vision-card h-100"> <!-- Agregamos la clase h-100 para que tengan la misma altura -->
                    <div class="card-body">
                        <h4 class="card-title text-dark">Visión</h4>
                        <p class="card-text text-justify text-dark">Al 2025, ser reconocidos a nivel nacional e internacional como una institución de educación superior de calidad en docencia, investigación y vinculación bajo el paradigma de una universidad inteligente, articulando la transferencia de ciencia y tecnología, a través de procesos de I+D+i; y, convirtiéndonos en un referente de pensamiento en seguridad y defensa, al servicio del país y Fuerzas Armadas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="header my-5">
            <h1>Conoce a nuestro Equipo</h1>
            <p class="text-muted">⬇️</p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="img-block mb-5">
                    <img src="https://www.wrappixel.com/demos/ui-kit/wrapkit/assets/images/team/t4.jpg" class="img-fluid img-thumbnail rounded-circle" alt="image1">
                    <div class="content mt-2">
                        <h4>Veronica Martinez</h4>
                        <p class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ">
                <div class="img-block mb-5">
                    <img src="https://www.wrappixel.com/demos/ui-kit/wrapkit/assets/images/team/t2.jpg" class="img-fluid img-thumbnail rounded-circle" alt="image1">
                    <div class="content mt-2">
                        <h4>E</h4>
                        <p class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="img-block mb-5">
                    <img src="https://www.wrappixel.com/demos/ui-kit/wrapkit/assets/images/team/t1.jpg" class="img-fluid img-thumbnail rounded-circle" alt="image1">
                    <div class="content mt-2">
                        <h4>Edwin Camino</h4>
                        <p class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="img-block mb-5">
                    <img src="https://www.wrappixel.com/demos/ui-kit/wrapkit/assets/images/team/t3.jpg" class="img-fluid img-thumbnail rounded-circle" alt="image1">
                    <div class="content mt-2">
                        <h4>Ing Cevallos</h4>
                        <p class="text-muted"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
