@extends('layouts.participante')


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
    
    <div class="container" style="overflow-x: auto;">
        <h4>Estudiantes Asignados</h4>

        <!-- Formulario de calificación -->
        <form method="post" action="{{ route('guardar-notas') }}">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Espe ID</th>
                        <th>Carrera</th>
                        <th>Departamento</th>
                        <th>Cumple con las tareas planificadas. Sobre 10%</th>
                        <th>Resultados Alcanzados. Sobre 10%</th>
                        <th>Demuestra conocimientos en el área de práctica pre profesional. Sobre 10%</th>
                        <th>Adaptabilidad e Integración al sistema de trabajo del proyecto. Sobre 10%</th>
                        <th>Aplicación y manejo de destrezas y habilidades acordes al perfil profesional</th>
                        <th>Demuestra capacidad de liderazgo y de trabajo en equipo. Sobre 10%</th>
                        <th>Asiste puntualmente. Sobre 10%</th>
                        <th></th> <!-- Columna oculta para el ID del estudiante -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $estudiante)
                        <tr>
                            <td class="wide-cell">{{ $estudiante->Apellidos }} {{ $estudiante->Nombres }}</td>
                            <td>{{ $estudiante->espe_id }}</td>
                            <td>{{ $estudiante->Carrera }}</td>
                            <td>{{ $estudiante->Departamento }}</td>
                            <td><input type="number" name="cumple_tareas[]" value="" min="1" max="10"
                                    step="0.01"></td>
                            <td><input type="number" name="resultados_alcanzados[]" value="" min="1"
                                    max="10" step="0.01"></td>
                            <td><input type="number" name="conocimientos_area[]" value="" min="1"
                                    max="10" step="0.01"></td>
                            <td><input type="number" name="adaptabilidad[]" value="" min="1" max="10"
                                    step="0.01"></td>
                            <td><input type="number" name="Aplicacion[]" value="" min="1" max="10"
                                    step="0.01"></td>
                            <td><input type="number" name="capacidad_liderazgo[]" value="" min="1"
                                    max="10" step="0.01"></td>
                            <td><input type="number" name="asistencia_puntual[]" value="" min="1"
                                    max="10" step="0.01"></td>

                            <td><input type="hidden" name="estudiante_id[]" value="{{ $estudiante->EstudianteID }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <button type="submit" class="btn btn-sm btn-secondary">Guardar Calificaciones</button>
        </form>

        <!-- Estudiantes Calificados -->
        @if (!$estudiantesConNotas->isEmpty())
            <h4>Estudiantes Calificados</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Espe ID</th>
                        <th>Carrera</th>
                        <th>Departamento</th>
                        <th>Cumple con las tareas planificadas. Sobre 10%</th>
                        <th>Resultados Alcanzados. Sobre 10%</th>
                        <th>Demuestra conocimientos en el área</th>
                        <th>Adaptabilidad</th>
                        <th>Aplicación de destrezas y habilidades</th>
                        <th>Capacidad de liderazgo</th>
                        <th>Asistencia puntual</th>
                        <th>Informe de Servicio Comunitario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantesConNotas as $estudiante)
                        <tr>
                            <td class="wide-cell">{{ $estudiante->Apellidos }} {{ $estudiante->Nombres }}</td>
                            <td>{{ $estudiante->espe_id }}</td>
                            <td>{{ $estudiante->Carrera }}</td>
                            <td>{{ $estudiante->Departamento }}</td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Tareas }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Resultados_Alcanzados }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Conocimientos }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Adaptabilidad }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Aplicacion }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Capacidad_liderazgo }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Asistencia }}<br>
                                @endforeach
                            </td>
                            <td >
                                @foreach ($estudiante->notas as $nota)
                                    {{ $nota->Informe }}<br>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection


<style>
    table.table {
        width: 100%;
        border-collapse: collapse;
    }

    table.table,
    th,
    td {
        font-size: 14px;
        border: 1px solid #ddd;
    }

    th {
        border: 1px solid #70a1ff;
        background-color: #eaf5ff;
        text-align: center;
        /* Centra el texto en las celdas del encabezado */
    }

    .wide-cell {
    white-space: normal; /* Permitir que el texto se divida en varias líneas */
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word; /* Romper palabras largas para ajustar al ancho de la celda */
}


    .body,
    table.table,
    tr,
    td,
    th {
        font-size: 12px;
        text-align: center;
        /* Centra el texto en las celdas de datos */
    }
</style>
