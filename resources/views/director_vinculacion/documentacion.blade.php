@extends('layouts.directorVinculacion')

@section('content')
    <h4>Informe Docente - Vinculación a la Sociedad</h4>

    <form action="{{ route('director_vinculacion.generarInformeDirector') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="Objetivos"><strong>Ingrese el Objetivo del Proyecto:</strong></label>
                <textarea placeholder="Ingrese el objetivo" class="form-control" id="Objetivos" rows="2" name="Objetivos"></textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="intervencion"><strong>Lugar de intervención del Proyecto:</strong></label>
                <input placeholder="Ingrese el lugar donde se realizo el proyecto" type="text" class="form-control" id="intervencion" name="intervencion">
            </div>
        </div>

        <hr>
        <h4>Actividades Planificadas y Resultados Alcanzados</h4>
        <hr>


        <div id="campos">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="planificadas"><strong>Actividades planificadas:</strong></label>
                    <textarea placeholder="Ingrese las actividades planificadas" name="planificadas[]" class="form-control" rows="2" required></textarea>
                </div>

                <div class="form-group col-md-4">
                    <label for="alcanzados"><strong>Resultados alcanzados:</strong></label>
                    <textarea placeholder="Ingrese los resultados alcanzados" name="alcanzados[]" class="form-control" rows="2" required></textarea>
                </div>

                <div class="form-group col-md-4">
                    <label for="porcentaje"><strong>Porcentaje alcanzado:</strong></label>
                    <textarea placeholder="Ingrese los resultados alcanzados" name="porcentaje[]" class="form-control" rows="2" required></textarea>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="agregarCampo()"><i class="material-icons">add</i></button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="eliminarCampo()"><i class="material-icons">delete</i></button>

        <hr>
        <h4>Beneficiarios-atendidos</h4>
        <hr>

        <div class="form-row">
    <div class="form-group col-md-3">
        <label for="Hombres"><strong>Cantidad de Hombres beneficiarios-atendidos:</strong></label>
        <input placeholder="Ingrese los Hombres beneficiados" type="number" class="form-control" id="Hombres" name="Hombres" min="1">
    </div>

    <div class="form-group col-md-3">
        <label for="Mujeres"><strong>Cantidad de Mujeres beneficiarios-atendidos:</strong></label>
        <input placeholder="Ingrese las Mujeres beneficiadas" type="number" class="form-control" id="Mujeres" name="Mujeres" min="1">
    </div>

    <div class="form-group col-md-3">
        <label for="Niños"><strong>Cantidad de Niños beneficiarios-atendidos:</strong></label>
        <input placeholder="Ingrese los Niños beneficiados" type="number" class="form-control" id="Niños" name="Niños" min="1">
    </div>

    <div class="form-group col-md-3">
        <label for="capacidad"><strong>Cantidad de Personas con capacidad beneficiarios-atendidos:</strong></label>
        <input placeholder="Ingrese los Personas beneficiadas" type="number" class="form-control" id="capacidad" name="capacidad" min="1">
    </div>
</div>






        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="Observaciones1"><strong>Observaciones en Hombres:</strong></label>
                <textarea placeholder="Ingrese las Observaciones" class="form-control" id="Observaciones1" rows="3" name="Observaciones1"></textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="Observaciones2"><strong>Observaciones en Mujeres:</strong></label>
                <textarea placeholder="Ingrese las Observaciones" class="form-control" id="Observaciones2" rows="3" name="Observaciones2"></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="Observaciones3"><strong>Observaciones en Niños:</strong></label>
                <textarea placeholder="Ingrese las Observaciones" class="form-control" id="Observaciones3" rows="3" name="Observaciones3"></textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="Observaciones4"><strong>Observaciones en Personas con capacidad:</strong></label>
                <textarea placeholder="Ingrese las Observaciones" class="form-control" id="Observaciones4" rows="3" name="Observaciones4"></textarea>
            </div>
        </div>

        <hr>
        <h4>Conclusiones y Recomendaciones</h4>
        <hr>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="Conclusiones"><strong>Conclusiones:</strong></label>
                <textarea placeholder="Ingrese la conclusion" class="form-control" id="Conclusiones" rows="3" name="Conclusiones"></textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="Recomendaciones"><strong>Recomendaciones:</strong></label>
                <textarea placeholder="Ingrese la recomendacion" class="form-control" id="Recomendaciones" rows="3" name="Recomendaciones"></textarea>
            </div>
        </div>


        <button type="submit" class="btn btn-light btn-block">
            <i class="fas fa-cogs"></i> Generar
        </button>
    </form>


@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<script>
    function agregarCampo() {
        var campos = document.getElementById('campos');
        var nuevoCampo = document.createElement('div');
        nuevoCampo.className = 'form-row';
        nuevoCampo.innerHTML = `
            <div class="form-group col-md-4">
                <label><strong>Nueva Actividad Planificada:</strong></label>
                <textarea name="planificadas[]" class="form-control" rows="2" required></textarea>
            </div>
            <div class="form-group col-md-4">
                <label><strong>Nuevo Resultado Alcanzado:</strong></label>
                <textarea name="alcanzados[]" class="form-control" rows="2" required></textarea>
            </div>
            <div class="form-group col-md-4">
                <label><strong>Nuevo Porcentaje Alcanzado:</strong></label>
                <textarea name="porcentaje[]" class="form-control" rows="2" required></textarea>
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
