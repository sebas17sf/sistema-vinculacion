@extends('layouts.admin')

@section('title', 'Panel de Administrador')

@section('content')

<h4>Panel de Administrador</h4>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session("success") }}',
            confirmButtonText: 'Ok'
        });
    </script>
@endif

@if(session('maestro_con_proyectos'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'El Docente tiene proyectos asignados y no se puede eliminar.',
        });
        {{ session()->forget('maestro_con_proyectos') }}
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session("error") }}',
            confirmButtonText: 'Ok'
        });
    </script>
@endif



@if ($profesoresPendientes->isEmpty())
<p>No hay Docentes pendientes.</p>
@else
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo Electrónico</th>
            <th>Estado Actual</th>
            <th>Actualizar Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($profesoresPendientes as $profesor)
        <tr>
            <td>{{ $profesor->UserID }}</td>
            <td>{{ strtoupper($profesor->Nombre) }}</td>
            <td>{{ strtoupper($profesor->Apellido) }}</td>
            <td>{{ $profesor->CorreoElectronico }}</td>
            <td>{{ $profesor->Estado }}</td>
            <td>
                <form action="{{ route('admin.updateEstado', ['id' => $profesor->UserID]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="nuevoEstado">
                        <option value="Vinculacion">Vinculación</option>
                        <option value="Director-Departamento">Director-Departamento</option>
                        <option value="Negado">Negado</option>
                    </select>
                    <button type="submit">Actualizar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if ($profesoresConPermisos->isEmpty())
<p>No hay Docentes con permisos concedidos.</p>
@else
<h4>Permisos Concedidos</h4>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo Electrónico</th>
            <th>Estado Actual</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($profesoresConPermisos as $profesor)
        <tr>
            <td>{{ $profesor->UserID }}</td>
            <td>{{ strtoupper(str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'N'], $profesor->Nombre)) }}</td>
            <td>{{ strtoupper(str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'N'], $profesor->Apellido)) }}</td>
            <td>{{ $profesor->CorreoElectronico }}</td>
            <td>{{ $profesor->Estado }}</td>
            <td>
                <form action="{{ route('admin.deletePermission', ['id' => $profesor->UserID]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"> <i class="material-icons">clear</i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

@if(session('permisosConcedidos'))
<div class="alert alert-success">
    {{ session('permisosConcedidos') }}
</div>
@endif

<button id="toggleFormBtn" class="btn btn-outline-secondary btn-block">Agregar Maestros</button>
<div id="registrarMaestros" style="display: none;">
<hr>
    <form action="{{ route('admin.guardarMaestro') }}" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombres"><strong>Ingrese Nombres:</strong></label>
                <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ingrese los dos Nombres" required>
            </div>

            <div class="form-group col-md-4">
                <label for="apellidos"><strong>Ingrese Apellidos:</strong></label>
                <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ingrese los dos Apellidos" required>
            </div>

            <div class="form-group col-md-4">
                <label for="correo"><strong>Ingrese Correo:</strong></label>
                <input type="email" id="correo" name="correo" class="form-control" placeholder="Ingrese el Correo Electronico" required>
            </div>
        </div>

        <div class="form-row">
        <div class="form-group col-md-4">
    <label for="cedula"><strong>Ingrese la Cédula:</strong></label>
    <input type="text" id="cedula" name="cedula" class="form-control" placeholder="Ingrese Cédula (10 dígitos)" 
           pattern="\d{10}" title="Debe ingresar exactamente 10 números" required>
</div>


            <div class="form-group col-md-4">
                <label for="departamento"><strong>Seleccione el departamento al que pertenece:</strong></label>
                <select id="departamento" name="departamento" class="form-control" required>
                    <option value="Ciencias de la Computación">Departamento de Ciencias de Computación</option>
                    <option value="Ciencias de la Vida">Departamento de Ciencias de la Vida</option>
                    <option value="Ciencias Exactas">Departamento de Ciencias Exactas</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-secondary btn-block">Guardar Docente</button>
    </form>
</div>




<hr>
<h4>Docentes agregados</h4>

<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($profesores as $profesor)
        <tr>
            <td>{{ strtoupper(str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'N'], $profesor->Apellidos)) }} {{ strtoupper(str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'N'], $profesor->Nombres)) }}</td>
            <td>{{ $profesor->Correo }}</td>
            <td>{{ $profesor->Cedula }}</td>
            <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $profesor->Departamento)) }}</td>            <td>
                <form action="{{ route('admin.eliminarMaestro', ['id' => $profesor->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"> <i class="material-icons">delete</i></button>
                </form>

                <form action="{{ route('admin.editarDocente', ['id' => $profesor->id]) }}" method="GET">
                    @csrf
                    <button type="submit"> <i class="material-icons">edit</i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($profesores->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">Anterior</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $profesores->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
        </li>
        @endif

        @foreach ($profesores->getUrlRange(1, $profesores->lastPage()) as $page => $url)
        @if ($page == $profesores->currentPage())
        <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach

        @if ($profesores->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $profesores->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">Siguiente</span>
        </li>
        @endif
    </ul>
</div>

<button id="toggleFormBtn2" class="btn btn-outline-secondary btn-block">Agregar Cohoerte/Periodo Academico</button>
<div id="registrarPeriodos" style="display: none;">
    <br>
    <form action="{{ route('admin.guardarCohorte') }}" method="post">
    @csrf
    <div class="row align-items-center">
        <div class="col-md-8">
        <div class="form-group">
    <label for="cohorte"><strong>Ingrese la Cohorte:</strong></label>
    <input type="text" id="cohorte" name="cohorte" class="form-control" placeholder="Ingrese 6 números para la Cohorte" 
           pattern="\d{6}" title="Debe ingresar exactamente 6 números" required>
</div>

        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-outline-secondary btn-block">Guardar Cohorte</button>
        </div>
    </div>
    </form>

    <form action="{{ route('admin.guardarPeriodo') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="periodoInicio"><strong>Ingrese el inicio del Periodo Académico:</strong></label>
                <input type="date" id="periodoInicio" name="periodoInicio" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="periodoFin"><strong>Ingrese el fin del Periodo Académico:</strong></label>
                <input type="date" id="periodoFin" name="periodoFin" class="form-control" required>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-outline-secondary btn-block">Guardar Periodo Académico</button>
    </form>

    <div class="row">
    <div class="col-md-6">
    <h3>Periodos Agregados</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Periodo</th>
            <th>Acciones</th>
        </tr>
       </thead>
       <tbody>
        @foreach ($periodos as $periodo)
        <tr>
            <td>{{ $periodo->Periodo }}</td>
            <td>

                <form action="{{ route('admin.editarPeriodo', ['id' => $periodo->id]) }}" method="GET">
                    @csrf
                    <button type="submit"> <i class="material-icons">edit</i></button>
                </form>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>



        </div>
        <div class="col-md-6">
        <h3>Cohortes Agregadas</h3>
        <table class="table">
         <thead>
        <tr>
            <th>Cohorte</th>
            <th>Acciones</th>
        </tr>
         </thead>
    <tbody>
        @foreach ($cohortes as $cohorte)
        <tr>
            <td>{{ $cohorte->Cohorte }}</td>
            <td>

                <form action="{{ route('admin.editarCohorte', ['id' => $cohorte->ID_cohorte]) }}" method="GET">
                    @csrf
                    <button type="submit"> <i class="material-icons">edit</i></button>
                </form>




            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

</div>






@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.css">
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        // Manejar el clic en el botón para mostrar/ocultar el formulario de maestros
        $("#toggleFormBtn").click(function () {
            $("#registrarMaestros").toggle();
            // Cambiar el texto del botón según si el formulario está visible u oculto
            if ($("#registrarMaestros").is(":visible")) {
                $(this).text("Ocultar Registro de Maestros");
            } else {
                $(this).text("Registrar Maestros");
            }
        });

        // Manejar el clic en el botón para mostrar/ocultar el formulario de periodos
        $("#toggleFormBtn2").click(function () {
            $("#registrarPeriodos").toggle();
            // Cambiar el texto del botón según si el formulario está visible u oculto
            if ($("#registrarPeriodos").is(":visible")) {
                $(this).text("Ocultar Registro");
            } else {
                $(this).text("Agregar Cohorte/Periodo Académico");
            }
        });
    });
</script>


<style>
    .pagination {
        background-color: #eaf5ff;
        padding: 10px;
        border-radius: 5px;
    }

    .pagination li.page-item.active .page-link {
        background-color: #ffff;
        border-color: #007BFF;
    }

    .pagination li.page-item .page-link {
        color: #007BFF;
    }
</style>
