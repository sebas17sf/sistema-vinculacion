@extends('layouts.app')

@section('content')
@if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok'
            });
        </script>
    @endif





    <div class="container">
        @if (isset($practicaPendiente))
        <h4>Proceso activo</h4>
            <p>Detalles de la práctica pendiente:</p>
            <div class="form-group">
                <label for="NombreEstudiante">Estudiante:</label>
                <input type="text" id="NombreEstudiante" name="NombreEstudiante" class="form-control"
                    value="{{ $practicaPendiente->NombreEstudiante }} {{ $practicaPendiente->ApellidoEstudiante }}" readonly>
            </div>
            <div class="form-group">
                <label for="Nivel">Nivel:</label>
                <input type="text" id="Nivel" name="Nivel" class="form-control"
                    value="{{ $practicaPendiente->Nivel }}" readonly>
            </div>
            <div class="form-group">
                <label for="Practicas">Práctica:</label>
                <input type="text" id="Practicas" name="Practicas" class="form-control"
                    value="{{ $practicaPendiente->Practicas }}" readonly>
            </div>
            <div class="form-group">
                <label for="DocenteTutor">Docente Tutor:</label>
                <input type="text" id="DocenteTutor" name="DocenteTutor" class="form-control"
                    value="{{ $practicaPendiente->DocenteTutor }}" readonly>
            </div>
            <div class="form-group">
                <label for="Empresa">Empresa:</label>
                <input type="text" id="Empresa" name="Empresa" class="form-control"
                    value="{{ $practicaPendiente->Empresa }}" readonly>
            </div>
            <div class="form-group">
                <label for="NombreTutorEmpresarial">Nombre del tutor empresarial:</label>
                <input type="text" id="NombreTutorEmpresarial" name="NombreTutorEmpresarial" class="form-control"
                    value="{{ $practicaPendiente->NombreTutorEmpresarial }}" readonly>
            </div>

            <div class="form-group">
                <label for="CedulaTutorEmpresarial">Cédula del tutor empresarial:</label>
                <input type="text" id="CedulaTutorEmpresarial" name="CedulaTutorEmpresarial" class="form-control"
                    value="{{ $practicaPendiente->CedulaTutorEmpresarial }}" readonly>
            </div>

            <div class="form-group">
                <label for="Funcion">Función:</label>
                <input type="text" id="Funcion" name="Funcion" class="form-control"
                    value="{{ $practicaPendiente->Funcion }}" readonly>
            </div>

            <div class="form-group">
                <label for="TelefonoTutorEmpresarial">Teléfono del tutor empresarial:</label>
                <input type="text" id="TelefonoTutorEmpresarial" name="TelefonoTutorEmpresarial" class="form-control"
                    value="{{ $practicaPendiente->TelefonoTutorEmpresarial }}" readonly>
            </div>

            <div class="form-group">
                <label for="Estado">Estado de Fase I:</label>
                <input type="text" id="Estado" name="Estado" class="form-control"
                    value="{{ $practicaPendiente->Estado }}" readonly>
            </div>
        @else
            <br>
            <hr>

            <h3>Fase 1 - Inicio del proceso de prácticas pre profesionales del estudiante</h3>
            <form action="{{ route('guardarPracticas') }}" method="POST">
                @csrf
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>ID de Estudiante:</th>
                                <td>{{ strtoupper($estudiante->espe_id) }}</td>
                            </tr>
                            <tr>
                                <th>Cédula:</th>
                                <td>{{ strtoupper($estudiante->cedula) }}</td>
                            </tr>
                            <tr>
                                <th>Nombres Completos:</th>
                                <td>{{ strtoupper($estudiante->Apellidos) }} {{ strtoupper($estudiante->Nombres) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Correo:</th>
                                <td>{{ strtoupper($correoEstudiante) }}</td>
                            </tr>
                            <tr>
                                <th>Nivel:</th>
                                <td>
                                    <select id="Nivel" name="Nivel" class="form-control">
                                        <option value="Pregrado">Pregrado</option>
                                        <option value="Posgrado">Posgrado</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Campus:</th>
                                <td>EXTENSION SANTO DOMINGO</td>
                            </tr>
                            <tr>
                                <th>Departamento:</th>
                                <td>{{ strtoupper($estudiante->Departamento) }}</td>
                            </tr>
                            <tr>
                                <th>Escoja Práctica:</th>
                                <td>
                                    <select id="Practicas" name="Practicas" class="form-control">
                                        <option value="SERVICIO A LA COMUNIDAD">SERVICIO A LA COMUNIDAD</option>
                                        <option value="PASANTIAS">PASANTIAS</option>
                                        <option value="PRACTICAS PRE PROFESIONALES">PRACTICAS PRE PROFESIONALES
                                        </option>
                                        <option value="AYUDANDIA DE CATEDRA">AYUDANDIA DE CATEDRA</option>
                                        <option value="AYUDANTIA DE INVESTIGACION">AYUDANTIA DE INVESTIGACION</option>
                                        <option value="RECONOCE EXPERIENCIA LABORAL">RECONOCE EXPERIENCIA LABORAL
                                        </option>
                                        <option value="P. INTEGRADOR SABERES">P. INTEGRADOR SABERES</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Teléfono:</th>
                                <td>{{ strtoupper($estudiante->celular) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="table-responsive-sm">
                    <h3>Datos de la Práctica</h3>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Estado Académico Actual:</th>
                                <td>
                                    <select id="EstadoAcademico" name="EstadoAcademico" class="form-control">
                                        <option value="FINALIZANDO ESTUDIOS">FINALIZANDO ESTUDIOS</option>
                                        <option value="CURSANDO ESTUDIOS">CURSANDO ESTUDIOS</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha de inicio de la práctica:</th>
                                <td>
                                    <input type="date" id="FechaInicio" name="FechaInicio"
                                        class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha de finalización de la práctica:</th>
                                <td>
                                    <input type="date" id="FechaFinalizacion" name="FechaFinalizacion"
                                        class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <th>Horas planificadas:</th>
                                <td>
                                    <input type="number" id="HorasPlanificadas" name="HorasPlanificadas" class="form-control" min="80" max="144">
                                </td>
                            </tr>
                            <tr>
                                <th>Horario de entrada:</th>
                                <td>
                                    <input type="time" id="HoraEntrada" name="HoraEntrada"
                                        class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <th>Horario de salida:</th>
                                <td>
                                    <input type="time" id="HoraSalida" name="HoraSalida" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <th>Área de conocimiento:</th>
                                <td>
                                    <input type="text" id="AreaConocimiento" name="AreaConocimiento"
                                        class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>




                <button type="button" id="verOpcionesBtn" class="btn btn-sm btn-secondary">Ver opciones de prácticas</button>
                <br><br>
                <table id="opcionesPracticas" class="table table-bordered" style="display: none;">
                    <tbody>
                        <tr>
                            <th>Sugiera un docente como tutor académico:</th>
                            <td>
                                <select id="DocenteTutor" name="DocenteTutor" class="form-control">
                                    <option value="CORONEL GUERRERO CHRISTIAN ALFREDO - DCCO">CORONEL GUERRERO
                                        CHRISTIAN ALFREDO
                                        - DCCO</option>
                                    <option value="SALAZAR ARMIJOS DIEGO RICARDO - DCCO">SALAZAR ARMIJOS DIEGO RICARDO
                                        - DCCO
                                    </option>
                                    <option value="NÚÑEZ AGURTO ALBERTO DANIEL - DCCO">NÚÑEZ AGURTO ALBERTO DANIEL -
                                        DCCO
                                    </option>
                                    <option value="ORTIZ DELGADO LUIS ARMANDO - DCCO">ORTIZ DELGADO LUIS ARMANDO - DCCO
                                    </option>
                                    <option value="BENAVIDES ASTUDILLO DIEGO EDUARDO - DCCO">BENAVIDES ASTUDILLO DIEGO
                                        EDUARDO -
                                        DCCO</option>
                                    <option value="ANDRADE SALAZAR MILTON TEMISTOCLES - DCCO">ANDRADE SALAZAR MILTON
                                        TEMISTOCLES
                                        - DCCO</option>
                                    <option value="RODRIGUEZ GALÁN GERMÁN EDUARDO - DCCO">RODRIGUEZ GALÁN GERMÁN
                                        EDUARDO - DCCO
                                    </option>
                                    <option value="CAMINO ZAMBRANO EDWIN PATRICIO - DCCO">CAMINO ZAMBRANO EDWIN
                                        PATRICIO - DCCO
                                    </option>
                                    <option value="REVELO HERRERA HÉCTOR MAURICIO - DCCO">REVELO HERRERA HÉCTOR
                                        MAURICIO - DCCO
                                    </option>
                                    <option value="CHICA MONCAYO LUIS MANUEL - DCCO">CHICA MONCAYO LUIS MANUEL - DCCO
                                    </option>
                                    <option value="GUARACA MOYOTA MARGOTH ELISA - DCCO">GUARACA MOYOTA MARGOTH ELISA -
                                        DCCO
                                    </option>
                                    <option value="MARTÍNEZ CEPEDA VERÓNICA ISABEL - DCCO">MARTÍNEZ CEPEDA VERÓNICA
                                        ISABEL -
                                        DCCO</option>
                                    <option value="CASTILLO SALINAS LUIS ALBERTO - DCCO">CASTILLO SALINAS LUIS ALBERTO
                                        - DCCO
                                    </option>
                                    <option value="CISNEROS BASURTO WILSON EDMUNDO - DCCO">CISNEROS BASURTO WILSON
                                        EDMUNDO -
                                        DCCO</option>
                                    <option value="PÉREZ AGURTO FRANKLIN RAMIRO - DCCO">PÉREZ AGURTO FRANKLIN RAMIRO -
                                        DCCO
                                    </option>
                                    <option value="JAVIER JOSÉ CEVALLOS FARÍAS - DCCO">JAVIER JOSÉ CEVALLOS FARÍAS -
                                        DCCO
                                    </option>
                                    <option value="PABLO FRANCISCO PUENTE PONCE - DCCO">PABLO FRANCISCO PUENTE PONCE -
                                        DCCO
                                    </option>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <th>Empresa:</th>
                            <td>
                                <select id="Empresa" name="Empresa" class="form-control">
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->nombreEmpresa }}">{{ $empresa->nombreEmpresa }} -
                                            Requiere: {{ $empresa->actividadesMacro }} </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>Cédula del tutor empresarial:</th>
                            <td>
                                <input type="text" id="CedulaTutorEmpresarial" name="CedulaTutorEmpresarial"
                                    class="form-control">
                            </td>

                        </tr>

                        <tr>
                            <th>Nombre del tutor empresarial:</th>
                            <td>
                                <input type="text" id="NombreTutorEmpresarial" name="NombreTutorEmpresarial"
                                    class="form-control">
                            </td>

                        </tr>

                        <tr>
                            <th>Funcion:</th>
                            <td>
                                <input type="text" id="Funcion" name="Funcion" class="form-control">
                            </td>

                        </tr>

                        <tr>
                            <th>Telefono:</th>
                            <td>
                                <input type="text" id="TelefonoTutorEmpresarial" name="TelefonoTutorEmpresarial"
                                    class="form-control">
                            </td>

                        </tr>

                        <tr>
                            <th>Email:</th>
                            <td>
                                <input type="text" id="EmailTutorEmpresarial" name="EmailTutorEmpresarial"
                                    class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <th>Departamento dentro de la empresa:</th>
                            <td>
                                <input type="text" id="DepartamentoTutorEmpresarial"
                                    name="DepartamentoTutorEmpresarial" class="form-control">
                            </td>

                        </tr>



                    </tbody>

                </table>
                <button type="submit" id="iniciarPracticasBtn" class="btn btn-sm btn-secondary" style="display: none;">Iniciar
                    prácticas</button>
            </form>
        @endif
    </div>



@endsection



<script>
document.addEventListener('DOMContentLoaded', function() {
    var verOpcionesBtn = document.getElementById('verOpcionesBtn');
    var opcionesPracticas = document.getElementById('opcionesPracticas');
    var iniciarPracticasBtn = document.getElementById('iniciarPracticasBtn');

    var opcionesAbiertas = false; // Variable para rastrear el estado de las opciones

    verOpcionesBtn.addEventListener('click', function() {
        if (opcionesAbiertas) {
            opcionesPracticas.style.display = 'none'; // Cierra las opciones
            iniciarPracticasBtn.style.display = 'none'; // Oculta el botón de inicio
        } else {
            opcionesPracticas.style.display = 'table'; // Abre las opciones
            iniciarPracticasBtn.style.display = 'block'; // Muestra el botón de inicio
        }

        // Cambia el estado de las opciones
        opcionesAbiertas = !opcionesAbiertas;
    });

    iniciarPracticasBtn.addEventListener('click', function() {
        // Aquí puedes agregar la lógica para cuando se hace clic en "Iniciar prácticas"
    });
});

</script>
