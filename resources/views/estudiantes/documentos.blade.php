@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4 class="text-center mb-4">Generar Documentos</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Generar Acta de Designación de Estudiantes</h4>
                    <form action="{{ route('generar-documento') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-light btn-block">
                            <i class="fas fa-file-excel"></i> Generar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Generar Carta de Compromiso de Estudiante</h4>
                    <form action="{{ route('generar-documento-cartaCompromiso') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-light btn-block">
                            <i class="fas fa-file-excel"></i> Generar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Generar Número de Horas de Estudiantes</h4>
                    <form action="{{ route('generar-documento-numeroHoras') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light btn-block">
                            <i class="fas fa-file-excel"></i> Generar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button id="toggleFormBtn" class="btn btn-light btn-block">Registrar actividad</button>

    <div id="registroActividades" style="display: none;">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Registro de Actividades</h4>
                        <form action="{{ route('estudiantes.guardarActividad') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="fecha"><strong>Fecha:</strong></label>
                                <input type="date" id="fecha" name="fecha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="actividades"><strong>Actividades a realizar:</strong></label>
                                <textarea id="actividades" name="actividades" class="form-control" rows="4"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="horas"><strong>Número de horas:</strong></label>
                                <input type="number" id="horas" name="horas" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="evidencias"><strong>Resultados de la actividad
                                        (evidencias):</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">cloud_upload</i>
                                        </span>
                                    </div>
                                    <input type="file" id="evidencias" name="evidencias"
                                        accept="image/jpeg, image/jpg, image/png" class="form-control-file" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nombre_actividad"><strong>Asigne Nombre de la actividad:</strong></label>
                                <input type="text" id="nombre_actividad" name="nombre_actividad" class="form-control"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-light btn-block">Guardar Actividad</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h4 class="text-center">Actividades Registradas</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Actividades</th>
                        <th>Número de Horas</th>
                        <th>Nombre de la Actividad</th>
                        <th>Evidencias</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actividadesRegistradas as $actividad)
                    <tr>
                        <td>{{ $actividad->fecha }}</td>
                        <td>{{ $actividad->actividades }}</td>
                        <td>{{ $actividad->numero_horas }}</td>
                        <td>{{ $actividad->nombre_actividad }}</td>
                        <td>
                            @php
                            $urlImagen = Storage::url($actividad->evidencias);


                            @endphp
                            <img src="{{ asset($urlImagen) }}" alt="Evidencia" width="100">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <button id="toggleFormBtn2" class="btn btn-light btn-block">Crear Informe</button>
    <div id="registroInforme" style="display: none;">
        <form action="{{ route('estudiantes.generarInforme') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="nombreComunidad"><strong>Nombre de la Comunidad o Comunidades
                        Beneficiarias:</strong></label>
                <input type="text" id="nombreComunidad" name="nombreComunidad" class="form-control" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="provincia"><strong>Provincia:</strong></label>
                    <input type="text" id="provincia" name="provincia" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="canton"><strong>Canton:</strong></label>
                    <input type="text" id="canton" name="canton" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="parroquia"><strong>Parroquia:</strong></label>
                    <input type="text" id="parroquia" name="parroquia" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="direccion"><strong>Dirección:</strong></label>
                    <input type="text" id="direccion" name="direccion" class="form-control" required>
                </div>
            </div>

            <div id="campos">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="especificos"><strong>Objetivos Específicos:</strong></label>
                        <textarea name="especificos[]" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="alcanzados"><strong>Resultados alcanzados:</strong></label>
                        <textarea name="alcanzados[]" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="porcentaje"><strong>Porcentaje alcanzado:</strong></label>
                        <textarea name="porcentaje[]" class="form-control" rows="4" required></textarea>
                    </div>
                </div>

            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="agregarCampo()"><i
                    class="material-icons">add</i></button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="eliminarCampo()"><i
                    class="material-icons">delete</i></button>

                    <table>
    <tr>
        <td>
            <label for="razones"><strong>Explicar las razones que justifican las actividades realizadas:</strong></label>
        </td>
        <td>
            <textarea id="razones" name="razones" rows="10" cols="100"></textarea>
        </td>
    </tr>
    <tr>
        <td>
            <label for="conclusiones"><strong>Conclusiones:</strong></label>
        </td>
        <td>
            <textarea id="conclusiones" name="conclusiones" rows="10" cols="100"></textarea>
        </td>
    </tr>
    <tr>
        <td>
            <label for="recomendaciones"><strong>Recomendaciones:</strong></label>
        </td>
        <td>
            <textarea id="recomendaciones" name="recomendaciones" rows="10" cols="100"></textarea>
        </td>
    </tr>
</table>





            <button type="submit" class="btn btn-light btn-block">Crear Informe</button>
        </form>

    </div>



</div>
</div>


@if(session('success'))
<div class="alert alert-success mt-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-4">
    {{ session('error') }}
</div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.css">
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        // Manejar el clic en el botón para mostrar/ocultar el formulario
        $("#toggleFormBtn").click(function () {
            $("#registroActividades").toggle();
            // Cambiar el texto del botón según si el formulario está visible u oculto
            if ($("#registroActividades").is(":visible")) {
                $(this).text("Ocultar Registro de Actividades");
            } else {
                $(this).text("Registrar Actividad");
            }
        });
    });
    $(document).ready(function () {
        // Manejar el clic en el botón para mostrar/ocultar el formulario
        $("#toggleFormBtn2").click(function () {
            $("#registroInforme").toggle();
            // Cambiar el texto del botón según si el formulario está visible u oculto
            if ($("#registroInforme").is(":visible")) {
                $(this).text("Ocultar creacion de Informe");
            } else {
                $(this).text("Crear Informe de Servicio a la comunidad");
            }
        });
    });

    function agregarCampo() {
        var campos = document.getElementById('campos');
        var nuevoCampo = document.createElement('div');
        nuevoCampo.className = 'form-row';
        nuevoCampo.innerHTML = `
                <div class="form-group col-md-4">
                    <label><strong>Nuevo Objetivo Específico:</strong></label>
                    <textarea name="especificos[]" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label><strong>Nuevo Resultado Alcanzado:</strong></label>
                    <textarea name="alcanzados[]" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label><strong>Nuevo Porcentaje Alcanzado:</strong></label>
                    <textarea name="porcentaje[]" class="form-control" rows="4" required></textarea>
                </div>
            `;
        campos.appendChild(nuevoCampo);
    }

    function eliminarCampo() {
        var campos = document.getElementById('campos');
        var camposAdicionales = campos.querySelectorAll('.form-row:not(:first-child)');
        if (camposAdicionales.length > 0) {
            campos.removeChild(camposAdicionales[camposAdicionales.length - 1]);
        }
    }





</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/fontawesome.min.css">
<style>
    /* Estilos CSS personalizados */
    .card {
        background-color: #ffffff;
        /* Color de fondo de las tarjetas */
    }

    .card-title {
        font-size: 1.25rem;
    }

    .btn-light {
        background-color: #e9ecef;
        /* Color de fondo de los botones */
        color: #333;
        /* Color de texto de los botones */
    }

    .btn-light:hover {
        background-color: #d9d9d9;
        /* Color de fondo al pasar el mouse sobre los botones */
    }

    label strong {
        font-weight: bold;
    }

    body,
    input,
    select,
    th,
    td,
    label,
    button,
    table {
        background-color: #F5F5F5;
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.5;

    }
</style>
@endsection