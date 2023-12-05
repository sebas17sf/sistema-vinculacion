@extends('layouts.admin')

@section('content')
    <div class="container">

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





        <h3>Agregar Empresa</h3>
        <form action="{{ route('admin.guardarEmpresa') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="table-responsive-sm">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><label for="nombreEmpresa">Nombre de la Empresa:</label></td>
                            <td><input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" placeholder="Ingrese el Nombre de la Empresa" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="rucEmpresa">RUC de la Empresa:</label></td>
                            <td>
                                <input type="text" class="form-control" id="rucEmpresa" name="rucEmpresa" 
                                 placeholder="Ingrese RUC (13 dígitos)" required
                                 pattern="[0-9]{13}" title="Ingrese 13 dígitos numéricos">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="provincia">Provincia:</label></td>
                            <td>
                                <select class="form-control" id="provincia" name="provincia" required>
                                    <option value="" disabled selected>Selecciona una provincia</option>
                                    <option value="Azuay">Azuay</option>
                                    <option value="Bolívar">Bolívar</option>
                                    <option value="Cañar">Cañar</option>
                                    <option value="Carchi">Carchi</option>
                                    <option value="Chimborazo">Chimborazo</option>
                                    <option value="Cotopaxi">Cotopaxi</option>
                                    <option value="El Oro">El Oro</option>
                                    <option value="Esmeraldas">Esmeraldas</option>
                                    <option value="Galápagos">Galápagos</option>
                                    <option value="Guayas">Guayas</option>
                                    <option value="Imbabura">Imbabura</option>
                                    <option value="Loja">Loja</option>
                                    <option value="Los Ríos">Los Ríos</option>
                                    <option value="Manabí">Manabí</option>
                                    <option value="Morona Santiago">Morona Santiago</option>
                                    <option value="Napo">Napo</option>
                                    <option value="Orellana">Orellana</option>
                                    <option value="Pastaza">Pastaza</option>
                                    <option value="Pichincha">Pichincha</option>
                                    <option value="Santa Elena">Santa Elena</option>
                                    <option value="Santo Domingo de los Tsáchilas">Santo Domingo de los Tsáchilas</option>
                                    <option value="Sucumbíos">Sucumbíos</option>
                                    <option value="Tungurahua">Tungurahua</option>
                                    <option value="Zamora Chinchipe">Zamora Chinchipe</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="ciudad">Ciudad:</label></td>
                            <td><input class="form-control" id="ciudad" name="ciudad" placeholder="Ingrese la Ciudad" required></input></td>
                        </tr>

                        <tr>
                            <td><label for="direccion">Dirección:</label></td>
                            <td><input class="form-control" id="direccion" name="direccion" placeholder="Ingrese la Direccion" required></input></td>
                        </tr>


                        <tr>
                            <td><label for="correo">Correo de contacto de la Empresa:</label></td>
                            <td><input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese el Correo de la Empresa" required></td>
                        </tr>

                        <tr>
                            <td><label for="nombreContacto">Nombre del contacto de la Empresa:</label></td>
                            <td><input type="text" class="form-control" id="nombreContacto" name="nombreContacto"
                            placeholder="Ingrese el Nombre del contacto de la Empresa" required>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="telefonoContacto">Teléfono del contacto de la Empresa:</label></td>
                            <td>
                             <input type="text" class="form-control" id="telefonoContacto" name="telefonoContacto" 
                              placeholder="Ingrese el celular de la Empresa (10 dígitos)" required
                             pattern="[0-9]{10}" title="Ingrese 10 dígitos numéricos">
                            </td>

                        </tr>

                        <tr>
                            <td><label for="actividadesMacro">Actividades Macro requeridas:</label></td>
                            <td>
                                <textarea class="form-control" id="actividadesMacro" name="actividadesMacro" rows="4" placeholder="Ingrese las actividades macro requeridas" required></textarea>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="cuposDisponibles">Cupos Disponibles:</label></td>
                            <td>
                            <input type="text" class="form-control" id="cuposDisponibles" name="cuposDisponibles" 
                             placeholder="Ingrese los cupos disponibles para la Empresa" required
                               pattern="[0-9]*" title="Solo se permiten números">
                                </td>

                        </tr>

                        <tr>
                            <td><label for="cartaCompromiso">Carta Compromiso (PDF):</label></td>
                            <td><input type="file" class="form-control-file" id="cartaCompromiso" name="cartaCompromiso">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="convenio">Convenio (PDF):</label></td>
                            <td><input type="file" class="form-control-file" id="convenio" name="convenio"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-sm btn-secondary">Guardar Empresa</button>
        </form>
        <h3>Listado de Empresas Agregadas</h3>
        @if ($empresas->isEmpty())
            <p>No hay empresas agregadas.</p>
        @else
            <div class="d-flex">
                <form method="GET" action="{{ route('admin.agregarEmpresa') }}" class="mr-3">
                    <label for="elementosPorPagina">Empresa a visualizar:</label>
                    <select name="elementosPorPagina" id="elementosPorPagina" onchange="this.form.submit()">
                        <option value="10" @if (request('elementosPorPagina', $elementosPorPagina) == 10) selected @endif>10
                        </option>
                        <option value="20" @if (request('elementosPorPagina', $elementosPorPagina) == 20) selected @endif>20
                        </option>
                        <option value="50" @if (request('elementosPorPagina', $elementosPorPagina) == 50) selected @endif>50
                        </option>
                        <option value="100" @if (request('elementosPorPagina', $elementosPorPagina) == 100) selected @endif>100
                        </option>
                    </select>
                </form>
            </div>
            <div class="table-responsive-sm" style="overflow-x: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre de la Empresa</th>
                            <th>RUC de la Empresa</th>
                            <th>Provincia</th>
                            <th>Ciudad</th>
                            <th>Dirección</th>
                            <th>Correo de Contacto</th>
                            <th>Nombre del Contacto</th>
                            <th>Teléfono del Contacto</th>
                            <th>Actividades Macro</th>
                            <th>Cupos Disponibles</th>
                            <th>Descargar Carta</th>
                            <th>Descargar Convenio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empresas as $empresa)
                            <tr>
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->nombreEmpresa))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->rucEmpresa))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->provincia))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->ciudad))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->direccion))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->correo))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->nombreContacto))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->telefonoContacto))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->actividadesMacro))}}</td>          
                                <td>{{ strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'U', 'Ñ'], $empresa->cuposDisponibles))}}</td>          
                                <td>
                                    @if ($empresa->cartaCompromiso)
                                        <a
                                            href="{{ route('admin.descargar', ['tipo' => 'carta', 'id' => $empresa->id]) }}">
                                            <i class="material-icons">cloud_download</i>
                                        </a>
                                    @else
                                        <span class="text-muted">No disponible</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($empresa->convenio)
                                        <a
                                            href="{{ route('admin.descargar', ['tipo' => 'convenio', 'id' => $empresa->id]) }}">
                                            <i class="material-icons">cloud_download</i>
                                        </a>
                                    @else
                                        <span class="text-muted">No disponible</span>
                                    @endif
                                </td>

                                <td>
                                    <form action="{{ route('admin.eliminarEmpresa', ['id' => $empresa->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0">
                                            <i class="material-icons text-muted" style="font-size: 1.5em;">delete</i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.editarEmpresa', ['id' => $empresa->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-link p-0">
                                            <i class="material-icons text-muted" style="font-size: 1.5em;">edit</i>
                                        </button>
                                    </form>

                                </td>





                            </tr>
                        @endforeach
                    </tbody>
                </table>


                <form action="{{ route('coordinador.reportesEmpresas') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fas fa-file-excel"></i> Generar Reporte
        </button>
    </form>




                <div class="d-flex justify-content-center">
                    <ul class="pagination">
                        @if ($empresas->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Anterior</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $empresas->previousPageUrl() }}"
                                    aria-label="Anterior">Anterior</a>
                            </li>
                        @endif

                        @foreach ($empresas->getUrlRange(1, $empresas->lastPage()) as $page => $url)
                            @if ($page == $empresas->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($empresas->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $empresas->nextPageUrl() }}"
                                    aria-label="Siguiente">Siguiente</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Siguiente</span>
                            </li>
                        @endif
                    </ul>
                </div>



            </div>
    </div>
    @endif

    </div>


@endsection

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    table,
    th,
    td {
        font-size: 0.8rem;
    }


    th,
    td {
        padding: 8px 12px;
        text-align: left;
        border: 1px solid #ddd;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
