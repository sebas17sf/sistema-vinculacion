<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add Google Icons link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #F5F5F5;
            font-size: 14px;
            font-family: " Arial", sans-serif;

        }
        .table{
            font-size: 12px;
        }


        .sidebar {
            background-image: url('/plantillas/imagenespe.jpg');
            background-size: cover;
            width: 230px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 20px;
            box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            padding: 10px 20px;
            /* Espaciado de los enlaces */
            text-decoration: none;
            /* Quitar subrayado de los enlaces */
            font-size: 14px;
            /* Tamaño de fuente de los enlaces */
            color: #000000;
            /* Texto blanco */
            display: flex;
            /* Mostrar como flex para alinear icono y texto */
            align-items: center;
            /* Centrar verticalmente el contenido */
        }

        .sidebar a .material-icons {
            margin-right: 10px;
            /* Espaciado a la derecha del icono */
        }

        .sidebar a:hover {
            background-color: #55595e;
            color: #ffffff;
            /* Texto blanco */
        }

        /* Estilo para el botón "Cerrar Sesión" */
        .logout-btn {
            color: #000000;
            /* Texto blanco */
            border: none;
            /* Sin bordes */
            width: 100%;
            /* Ancho del 100% */
            position: absolute;
            /* Posición absoluta */
            bottom: 0;
            /* Alinear en la parte inferior */
        }

        .logout-btn:hover {
            background-color: #ffff;
            /* Cambia el color de fondo al pasar el cursor */
        }

        /* Estilo personalizado para el logo y el nombre */
        .navbar-brand {
            color: #000000;
            /* Texto blanco */
            font-size: 1.5rem;
            /* Tamaño de fuente */
            font-weight: bold;
            display: flex;
            align-items: center;
            /* Centrar verticalmente el contenido */
        }

        .navbar-brand img {
            max-height: 30px;
            /* Altura máxima del logo */
            margin-right: 10px;
            /* Añade un margen a la derecha del logo */
        }

        /* Estilo para ajustar el contenido principal a la derecha de la barra de navegación */
        .content {
            min-height: calc(100vh - 70px);
            /* Altura mínima del viewport - altura del footer */
            margin-left: 250px;
            /* Ajusta el margen izquierdo para alinear el contenido a la derecha */
            padding: 20px;
            /* Espaciado del contenido */
            margin-bottom: 70px;
            /* Margen inferior igual a la altura del footer */
            transition: min-height 0.3s ease;
        }


        /* Estilo para el footer */
        .footer {
            background-color: #00713d;
            ;
            /* Color de fondo verde */
            color: #ffffff;
            /* Texto blanco */
            text-align: right;
            /* Alineación de texto centrada */
            padding: 10px 0;
            /* Espaciado del footer */
            width: 100%;
            /* Ancho del 100% para centrarlo en la pantalla */
            z-index: 1;
            /* Asegura que el footer esté por encima de .right-sidebar */
            bottom: 0;
            /* Alinear en la parte inferior */

        }

        .logo-container {
            background-color: #ffffff;
            position: fixed;
            width: 230px;
            height: 80px;
            top: 0;
            left: 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            z-index: 2;
        }

        /* Estilo para la imagen circular del logo */
        .logo-image {
            width: 150px;
            height: 50px;
        }

        /* Estilo para el texto encima de la imagen */


        .dropdown:hover .dropdown-menu {
            display: block;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 2;
            /* Asegurarse de que el submenú tenga un índice z mayor que las opciones del menú principal */
        }


        /* Estilo para las opciones del submenu */
        .dropdown-menu {
            color: #ffffff;
            background-color: transparent;
            border: none;
            border-radius: 0;
            box-shadow: none;
            margin-top: 0;
        }

        /* Estilo personalizado para las opciones del submenu */
        .dropdown-item {
            color: #ffffff;
            padding: 10px 20px;
            font-size: 18px;
        }

        /* Estilo personalizado al pasar el cursor sobre las opciones del submenu */
        .dropdown-item:hover {
            background-color: #ffff;
        }

        .sidebar-links {
            margin-top: 80px;
            z-index: 1;
        }

        .sidebar-links a.active {
            background-color: #55595e;
            color: #ffffff;
        }

        .dropdown:hover .content {
            min-height: calc(100vh - 220px);
            /* Ajusta la altura mínima cuando el submenú está abierto */
        }

        .config-links {
            color: #000000;
            display: flex;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            margin: 5px;
            position: absolute;
            top: 10px;
            font-size: 5px;
            right: 10px;
            z-index: 3;

        }

        .config-link {
            display: inline-block;
            font-size: 15px;
            margin-right: 10px;
        }
        .table th {
            border: 1px solid #70a1ff;
            background-color: #eaf5ff;
            width: 200px;

        }

        .custom-table {
            border-collapse: collapse;
        }

        td,
        tr {
            border: 1px solid #70a1ff;
        }

    </style>
</head>

<body>
    <header>
        <div class="sidebar">
            <div class="logo-container">
                <img src="/plantillas/favicon.jpg" alt="Logo ESPE" class="logo-image">
            </div>
            <div class="sidebar-links">
                <a href="{{ route('estudiantes.index') }}"
                    class="{{ Request::is('estudiantes/index') ? 'active' : '' }}">
                    <i class="material-icons">assignment</i> Informacion
                </a>

                <a href="{{ route('estudiantes.documentos') }}"
                    class="{{ Request::is('estudiantes/documentos') ? 'active' : '' }}">
                    <i class="material-icons">description</i> Reportes/Actividades
                </a>

                <a href="{{ route('estudiantes.practica1') }}"
                    class="{{ Request::is('estudiantes/practica1') ? 'active' : '' }}">
                    <i class="material-icons">work</i> Prácticas pre profesionales
                </a>

                <a href="{{ route('logout') }}" class="logout-btn">
                    <i class="material-icons">exit_to_app</i> Cerrar Sesión
                </a>



            </div>

        </div>
    </header>
    <div class="config-links">
        <a class="navbar-brand config-link">
        <i class="material-icons">person</i> {{ Auth::user()->Nombre }} {{ Auth::user()->Apellido }}
        </a>
        <a href="{{ route('estudiantes.configuracion') }}" class="navbar-brand config-link">
            <i class="material-icons">settings</i> Configuración
        </a>
    </div>


    <div class="content">
        <main class="container">
            @yield('content')
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <span>© 202 Universidad de las Fuerzas Armadas ESPE - Todos los derechos reservados</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
