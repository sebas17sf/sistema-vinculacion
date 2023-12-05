@extends('layouts.coordinador')

@section('content')
    <div class="container">


        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


        <h3>Editar Empresa</h3>
        <form action="{{ route('coordinador.actualizarEmpresa', ['id' => $empresa->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="table-responsive-sm">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><label for="nombreEmpresa">Nombre de la Empresa:</label></td>
                            <td>
                                <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" required
                                    value="{{ $empresa->nombreEmpresa }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="rucEmpresa">RUC de la Empresa:</label></td>
                            <td>
                                <input type="text"  class="form-control" id="rucEmpresa" name="rucEmpresa" required
                                    value="{{ $empresa->rucEmpresa }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="provincia">Provincia:</label></td>
                            <td>
                                <select class="form-control" id="provincia" name="provincia" required>
                                    <option value="" disabled selected>Selecciona una provincia</option>
                                    <option value="Azuay" @if ($empresa->provincia == 'Azuay') selected @endif>Azuay</option>
                                    <option value="Bolívar" @if ($empresa->provincia == 'Bolívar') selected @endif>Bolívar</option>
                                    <!-- Otras opciones -->
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="ciudad">Ciudad:</label></td>
                            <td>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" required
                                    value="{{ $empresa->ciudad }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="direccion">Dirección:</label></td>
                            <td>
                                <input type="text" class="form-control" id="direccion" name="direccion" required
                                    value="{{ $empresa->direccion }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="correo">Correo de contacto de la Empresa:</label></td>
                            <td>
                                <input type="email" class="form-control" id="correo" name="correo" required
                                    value="{{ $empresa->correo }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="nombreContacto">Nombre del contacto de la Empresa:</label></td>
                            <td>
                                <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" required
                                    value="{{ $empresa->nombreContacto }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="telefonoContacto">Teléfono del contacto de la Empresa:</label></td>
                            <td>
                                <input type="text" class="form-control" id="telefonoContacto" name="telefonoContacto" required
                                    value="{{ $empresa->telefonoContacto }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="actividadesMacro">Actividades Macro requeridas:</label></td>
                            <td>
                                <textarea class="form-control" id="actividadesMacro" name="actividadesMacro" rows="4" required>
                                    {{ $empresa->actividadesMacro }}
                                </textarea>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="cuposDisponibles">Cupos Disponibles:</label></td>
                            <td>
                                <input type="text" class="form-control" id="cuposDisponibles" name="cuposDisponibles" required
                                    value="{{ $empresa->cuposDisponibles }}">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="cartaCompromiso">Carta Compromiso (PDF):</label></td>
                            <td>
                                @if ($empresa->cartaCompromiso)
                                    <a href="{{ route('admin.descargar', ['tipo' => 'carta', 'id' => $empresa->id]) }}">
                                        <i class="material-icons">cloud_download</i> Descargar
                                    </a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                                <input type="file" class="form-control-file" id="cartaCompromiso" name="cartaCompromiso">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="convenio">Convenio (PDF):</label></td>
                            <td>
                                @if ($empresa->convenio)
                                    <a href="{{ route('admin.descargar', ['tipo' => 'convenio', 'id' => $empresa->id]) }}">
                                        <i class="material-icons">cloud_download</i> Descargar
                                    </a>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                                <input type="file" class="form-control-file" id="convenio" name="convenio">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-secondary">Actualizar Empresa</button>
        </form>
    </div>
</div>
@endsection

<style>
    /* Estilos CSS */
</style>
