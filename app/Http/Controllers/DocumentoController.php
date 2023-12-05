<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\Settings;
use Barryvdh\DomPDF\Facade as PDF;
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
use Phpdocx\Create\CreateDocx;

use Illuminate\Support\Facades\Http;
use App\Models\ActividadEstudiante;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Worksheet\TextRun;

use Carbon\Carbon;
use App\Models\Estudiante;


class DocumentoController extends Controller
{

    public function generar()
    {
        // Ruta a la plantilla de Word en la carpeta "public/Plantillas"
        $plantillaPath = public_path('Plantillas\\1.2-Acta-Designacion-Estudiantes.docx');

        // Verificar si el archivo de plantilla existe
        if (!file_exists($plantillaPath)) {
            abort(404, 'El archivo de plantilla no existe.');
        }

        // Cargar la plantilla de Word existente
        $template = new TemplateProcessor($plantillaPath);

        // Obtener el usuario actual (asegúrate de que el usuario esté autenticado)
        $usuario = auth()->user();

        if (!$usuario) {
            // Manejar el caso en que el usuario no esté autenticado
            abort(403, 'No estás autenticado.');
        }

        // Obtener el estudiante asociado al usuario
        $estudiante = $usuario->estudiante;

        if (!$estudiante) {
            // Manejar el caso en que no se encontró el estudiante asociado al usuario
            abort(404, 'No se encontró el estudiante asociado a tu usuario.');
        }

        // Obtener el ProyectoID del modelo AsignacionProyecto del estudiante
        $asignacionProyecto = $estudiante->asignaciones->first();

        if ($asignacionProyecto) {
            $proyectoID = $asignacionProyecto->ProyectoID;
        } else {
            // Manejar el caso en que no se encontró la asignación de proyecto para el estudiante
            abort(404, 'No se encontró la asignación de proyecto para el estudiante.');
        }

        // Consulta para obtener los datos de los estudiantes asignados a un proyecto específico
        $datosEstudiantes = DB::table('estudiantes')
            ->join('asignacionproyectos', 'estudiantes.EstudianteID', '=', 'asignacionproyectos.EstudianteID')
            ->join('proyectos', 'asignacionproyectos.ProyectoID', '=', 'proyectos.ProyectoID')
            ->select(
                'estudiantes.Apellidos',
                'estudiantes.Nombres',
                'estudiantes.cedula',
                'estudiantes.Carrera',
                'estudiantes.Provincia',
                'proyectos.FechaInicio',
                'proyectos.NombreProyecto',
            )
            ->where('proyectos.Estado', '=', 'Ejecucion')
            ->where('asignacionproyectos.ProyectoID', '=', $proyectoID) // Filtrar por ProyectoID de AsignacionProyecto
            ->orderBy('estudiantes.Apellidos', 'asc')
            ->get();

        // Verificar si se recuperaron datos
        if ($datosEstudiantes->isEmpty()) {
            // Manejar el caso en que no se encontraron datos
            abort(404, 'No se encontraron datos de estudiantes asignados al proyecto activo.');
        }

        // Obtener Carrera, Provincia y FechaInicio del primer estudiante asignado al proyecto
        $primerEstudiante = $datosEstudiantes->first();
        $carreraEstudiante = mb_strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú'], ['A', 'E', 'I', 'O', 'U'], $primerEstudiante->Carrera));
        $provinciaEstudiante = $primerEstudiante->Provincia;
        $carreraNormal = $primerEstudiante->Carrera;
        $fechaInicioProyecto = $primerEstudiante->FechaInicio;
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre',
        ];
        $fechaFormateada = date('d', strtotime($fechaInicioProyecto)) . ' ' . $meses[date('F', strtotime($fechaInicioProyecto))] . ' ' . date('Y', strtotime($fechaInicioProyecto));
        $NombreProyecto = $primerEstudiante->NombreProyecto;
        $horasVinculacionConstante = 96;

        // Clonar las filas en la plantilla
        $template->cloneRow('Nombres', count($datosEstudiantes));

        // Ordenar los datos por apellidos en orden ascendente (A-Z)
        $datosEstudiantes = $datosEstudiantes->sortBy('Apellidos');

        // Bucle para reemplazar los valores en la plantilla
        foreach ($datosEstudiantes as $index => $estudiante) {
            $template->setValue('Apellidos#' . ($index + 1), $estudiante->Apellidos);
            $template->setValue('Nombres#' . ($index + 1), $estudiante->Nombres);
            $template->setValue('Cedula#' . ($index + 1), $estudiante->cedula);
            $template->setValue('HorasVinculacion#' . ($index + 1), $horasVinculacionConstante);
        }

        // Reemplazar los valores constantes en la plantilla
        $template->setValue('Carrera', $carreraEstudiante);
        $template->setValue('CarreraNormal', $carreraNormal);
        $template->setValue('Provincia', $provinciaEstudiante);
        $template->setValue('FechaInicio', $fechaFormateada);
        $template->setValue('NombreProyecto', $NombreProyecto);

        // Guardar el documento generado
        $documentoGeneradoPath = storage_path('app/public/1.2-Acta-Designacion-Estudiantes.docx');
        $template->saveAs($documentoGeneradoPath);

        // Descargar el documento generado
        return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
    }



    ////////////////////////CARTA DE COMPROMISO ESTUDIANTE//////////////////////////
    public function generarCartaCompromiso()
    {
        // Ruta a la plantilla de Word en la carpeta "public/Plantillas"
        $plantillaPath = public_path('Plantillas\\1.2.1-Carta-Compromiso-Estudiante.docx');

        // Verificar si el archivo de plantilla existe
        if (!file_exists($plantillaPath)) {
            abort(404, 'El archivo de plantilla no existe.');
        }

        // Cargar la plantilla de Word existente
        $template = new TemplateProcessor($plantillaPath);

        // Obtener el usuario autenticado
        $usuario = auth()->user();

        // Obtener el estudiante relacionado con el usuario
        $estudiante = $usuario->estudiante;

        // Verificar si se encontró un estudiante relacionado
        if (!$estudiante) {
            // Manejar el caso en que el usuario no tenga un estudiante relacionado
            abort(404, 'No se encontraron datos de estudiante para este usuario.');
        }

        // Obtener las asignaciones de proyectos del estudiante
        $asignaciones = $estudiante->asignaciones;

        // Crear una lista para almacenar los nombres de proyectos
        $nombresProyectos = [];
        $apellidosProfesores = [];
        $nombresProfesores = [];
        $apellidosAsignados = [];
        $nombresAsignados = [];
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre',
        ];
        // Recorrer las asignaciones y obtener los datos de proyectos y profesores
        foreach ($asignaciones as $asignacion) {
            $proyecto = $asignacion->proyecto;
            if ($proyecto) {
                $nombresProyectos[] = $proyecto->NombreProyecto;
                $apellidosProfesores[] = $proyecto->ApellidoProfesor;
                $nombresProfesores[] = $proyecto->NombreProfesor;
                $apellidosAsignados[] = $proyecto->ApellidoAsignado;
                $fechaInicio = date('d', strtotime($proyecto->FechaInicio)) . ' ' . $meses[date('F', strtotime($proyecto->FechaInicio))] . ' ' . date('Y', strtotime($proyecto->FechaInicio));
                $fechasInicio[] = $fechaInicio;
                $nombresAsignados[] = $proyecto->NombreAsignado;
            }

        }

        // Obtener los datos del estudiante
        $apellidosEstudiante = $estudiante->Apellidos;
        $nombresEstudiante = $estudiante->Nombres;
        $cedulaEstudiante = $estudiante->cedula;
        $carreraEstudiante = $estudiante->Carrera;
        $provinciaEstudiante = $estudiante->Provincia;

        // Reemplazar los valores en la plantilla
        $template->setValue('Apellidos', $apellidosEstudiante);
        $template->setValue('Nombres', $nombresEstudiante);
        $template->setValue('Cedula', $cedulaEstudiante);
        $template->setValue('Carrera', $carreraEstudiante);
        $template->setValue('Provincia', $provinciaEstudiante);

        // Reemplazar la lista de proyectos
        $proyectosString = implode(', ', $nombresProyectos);
        $template->setValue('NombreProyecto', $proyectosString);

        // Reemplazar la lista de apellidos de profesores
        $apellidosProfesoresString = implode(', ', $apellidosProfesores);
        $template->setValue('ApellidoProfesor', $apellidosProfesoresString);

        $fechasInicioString = implode(', ', $fechasInicio);
        $template->setValue('FechaInicio', $fechasInicioString);

        // Reemplazar la lista de nombres de profesores
        $nombresProfesoresString = implode(', ', $nombresProfesores);
        $template->setValue('NombreProfesor', $nombresProfesoresString);

        // Reemplazar la lista de apellidos de asignados
        $apellidosAsignadosString = implode(', ', $apellidosAsignados);
        $template->setValue('ApellidoAsignado', $apellidosAsignadosString);

        // Reemplazar la lista de nombres de asignados
        $nombresAsignadosString = implode(', ', $nombresAsignados);
        $template->setValue('NombreAsignado', $nombresAsignadosString);

        // Guardar el documento generado
        $documentoGeneradoPath = storage_path('app/public/1.2.1-Carta-Compromiso-Estudiante.docx');
        $template->saveAs($documentoGeneradoPath);

        // Descargar el documento generado
        return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
    }




    ///////////////////////////////GENERAR 1.3 NÚMERO HORAS ESTUDIANTES//////////////////////////
    public function generarHorasEstudiante()
    {
        // Ruta a la plantilla XLSX en la carpeta "public/Plantillas"
        $plantillaPath = public_path('Plantillas\\1.3-Número-Horas-Estudiantes.xlsx');

        // Verificar si el archivo de plantilla existe
        if (!file_exists($plantillaPath)) {
            abort(404, 'El archivo de plantilla no existe.');

        }

        // Cargar la plantilla XLSX existente
        $spreadsheet = IOFactory::load($plantillaPath);

        // Obtener el usuario actual (asegúrate de que el usuario esté autenticado)
        $usuario = auth()->user();

        if (!$usuario) {
            // Manejar el caso en que el usuario no esté autenticado
            abort(403, 'No estás autenticado.');
        }

        // Obtener el estudiante asociado al usuario
        $estudiante = $usuario->estudiante;

        if (!$estudiante) {
            // Manejar el caso en que no se encontró el estudiante asociado al usuario
            abort(404, 'No se encontró el estudiante asociado a tu usuario.');
        }

        // Obtener el ProyectoID del modelo AsignacionProyecto del estudiante
        $asignacionProyecto = $estudiante->asignaciones->first();

        if ($asignacionProyecto) {
            $proyectoID = $asignacionProyecto->ProyectoID;
        } else {
            // Manejar el caso en que no se encontró la asignación de proyecto para el estudiante
            abort(404, 'No se encontró la asignación de proyecto para el estudiante.');
        }

        // Consulta para obtener los datos de los estudiantes asignados a un proyecto específico
        $datosEstudiantes = DB::table('estudiantes')
            ->join('asignacionproyectos', 'estudiantes.EstudianteID', '=', 'asignacionproyectos.EstudianteID')
            ->join('proyectos', 'asignacionproyectos.ProyectoID', '=', 'proyectos.ProyectoID')
            ->join('usuarios', 'estudiantes.UserID', '=', 'usuarios.UserID')
            ->select(
                'estudiantes.Apellidos',
                'estudiantes.Nombres',
                'estudiantes.cedula',
                'estudiantes.Departamento',
                'estudiantes.celular',
                'estudiantes.Carrera',
                'estudiantes.Provincia',
                'usuarios.CorreoElectronico',
                'proyectos.FechaInicio',
                'proyectos.FechaFinalizacion',
                'proyectos.NombreProyecto',
                'proyectos.DepartamentoTutor',
                'proyectos.NombreProfesor',
                'proyectos.ApellidoProfesor',
            )
            ->where('proyectos.Estado', '=', 'Ejecucion')
            ->where('asignacionproyectos.ProyectoID', '=', $proyectoID)
            ->orderBy('estudiantes.Apellidos', 'asc')
            ->get();



        // Verificar si se recuperaron datos
        if ($datosEstudiantes->isEmpty()) {
            // Manejar el caso en que no se encontraron datos
            abort(404, 'No se encontraron datos de estudiantes asignados al proyecto activo.');
        }

        // Obtener Carrera, Provincia y FechaInicio del primer estudiante asignado al proyecto
        $primerEstudiante = $datosEstudiantes->first();
        $fechaInicioProyecto = $primerEstudiante->FechaInicio;
        $fechaFinProyecto = $primerEstudiante->FechaFinalizacion;
        $departamentoProyecto = $primerEstudiante->Departamento;
        $departamento = "Departamento de " . $primerEstudiante->Departamento;

        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre',
        ];
        
        $fechaFormateada = date('d F Y', strtotime($fechaInicioProyecto));
        
        $fechaFormateada = strtr($fechaFormateada, $meses);
        
        
        $NombreProyecto = $primerEstudiante->NombreProyecto;
        $horasVinculacionConstante = 96;
        $matriz = 'Sede Santo Domingo';
        $nombreProfesor = $primerEstudiante->NombreProfesor;
        $apellidoProfesor = $primerEstudiante->ApellidoProfesor;
        $nombreCombinado = "Ing. {$nombreProfesor} {$apellidoProfesor}, Mgtr";



        // Obtener la hoja activa del archivo XLSX
        $sheet = $spreadsheet->getActiveSheet();

        // Clonar filas en la plantilla
        $filaInicio = 5; // La primera fila de datos comienza en la fila 2
        $cantidadFilas = count($datosEstudiantes);
        $proyectoCellStart = 'B5';
        $proyectoN = 'A5';
        $proyectoCellEnd = 'B' . (5 + count($datosEstudiantes) - 1);
        $proyectoNEnd = 'A' . (5 + count($datosEstudiantes) - 1);

        $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

        // Bucle para reemplazar los valores en la plantilla
        foreach ($datosEstudiantes as $index => $estudiante) {
            $apellidoNombre = $estudiante->Apellidos . ' ' . $estudiante->Nombres;
            $sheet->setCellValue('C' . ($filaInicio + $index), $apellidoNombre);
            $sheet->setCellValue('D' . ($filaInicio + $index), $estudiante->cedula);
            $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->celular);
            $horasVinculacionConstanteEntero = round($horasVinculacionConstante);
            $sheet->setCellValue('L' . ($filaInicio + $index), $horasVinculacionConstanteEntero);
            $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->Departamento);
            $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->Carrera);
            $sheet->setCellValue('J' . ($filaInicio + $index), $fechaInicioProyecto);
            $sheet->setCellValue('K' . ($filaInicio + $index), $fechaFinProyecto);
            $sheet->setCellValue('G' . ($filaInicio + $index), $matriz);
            $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->CorreoElectronico);
            

        }


        $sheet->mergeCells($proyectoCellStart . ':' . $proyectoCellEnd);
        $sheet->mergeCells($proyectoN . ':' . $proyectoNEnd);
        $sheet->setCellValue('B5', $NombreProyecto);
        $sheet->mergeCells('B18:D18');
        $sheet->mergeCells('B17:D17');
        $sheet->mergeCells('B19:D19');
        $sheet->setCellValue('A5', '1');



        // Reemplazar los valores constantes en la plantilla
        $sheet->setCellValue('C9', $fechaFormateada);
        $style = $sheet->getStyle('C9');
        $style->getFont()->setName('Calibri')->setSize(16);



        $sheet->setCellValue('C2', $departamentoProyecto);

        $sheet->setCellValue('B17', $nombreCombinado);
        $style = $sheet->getStyle('B17');
        $style->getFont()->setName('Calibri')->setSize(16)->setBold(true);
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('B18', $departamento);
        $style = $sheet->getStyle('B18');
        $style->getFont()->setName('Calibri')->setSize(16)->setBold(true);
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
       
        $sheet->setCellValue('B19', 'Director del proyecto');
        $style = $sheet->getStyle('B19');
        $style->getFont()->setName('Calibri')->setSize(16)->setBold(true);
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B9', 'Fecha:');
        $style = $sheet->getStyle('B9');
        $style->getFont()->setName('Calibri')->setSize(16)->setBold(true);



        // Guardar el documento generado
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $documentoGeneradoPath = storage_path('app/public/1.3-Número-Horas-Estudiantes.xlsx');

        $writer->save($documentoGeneradoPath);

        // Descargar el documento generado
        return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);


    }


    ////////////////////////Creacion de infomreeeeeeeeeee///////////////////////////////////
    public function generarInforme(Request $request)
    {
        // Ruta a la plantilla de Word en la carpeta "public/Plantillas"
        $plantillaPath = public_path('Plantillas\\1.-Informe-Servicio-Comunitario.docx');

        // Verificar si el archivo de plantilla existe
        if (!file_exists($plantillaPath)) {
            abort(404, 'El archivo de plantilla no existe.');
        }

        // Cargar la plantilla de Word existente
        $template = new TemplateProcessor($plantillaPath);

        // Obtener el usuario actual (asegúrate de que el usuario esté autenticado)
        $usuario = auth()->user();

        if (!$usuario) {
            // Manejar el caso en que el usuario no esté autenticado
            abort(403, 'No estás autenticado.');
        }

        // Obtener el estudiante asociado al usuario
        $estudiante = $usuario->estudiante;

        if (!$estudiante) {
            // Manejar el caso en que no se encontró el estudiante asociado al usuario
            abort(404, 'No se encontró el estudiante asociado a tu usuario.');
        }

        // Obtener el ProyectoID del modelo AsignacionProyecto del estudiante
        $asignacionProyecto = $estudiante->asignaciones->first();

        if ($asignacionProyecto) {
            $proyectoID = $asignacionProyecto->ProyectoID;
        } else {
            // Manejar el caso en que no se encontró la asignación de proyecto para el estudiante
            abort(404, 'No se encontró la asignación de proyecto para el estudiante.');
        }

        // Consulta para obtener los datos de los estudiantes asignados a un proyecto específico
        $datosEstudiantes = DB::table('estudiantes')
            ->join('asignacionproyectos', 'estudiantes.EstudianteID', '=', 'asignacionproyectos.EstudianteID')
            ->join('proyectos', 'asignacionproyectos.ProyectoID', '=', 'proyectos.ProyectoID')
            ->select(
                'estudiantes.Apellidos',
                'estudiantes.Nombres',
                'estudiantes.cedula',
                'estudiantes.Carrera',
                'estudiantes.Departamento',
                'estudiantes.Provincia',
                'proyectos.FechaInicio',
                'proyectos.FechaFinalizacion',
                'proyectos.NombreProyecto',
                'proyectos.NombreProfesor',
                'proyectos.ApellidoProfesor',
                'proyectos.NombreAsignado',
                'proyectos.ApellidoAsignado',
            )
            ->where('proyectos.Estado', '=', 'Ejecucion')
            ->where('asignacionproyectos.ProyectoID', '=', $proyectoID) // Filtrar por ProyectoID de AsignacionProyecto
            ->orderBy('estudiantes.Apellidos', 'asc')
            ->get();

        $datosEstudiantes2 = DB::table('estudiantes')
            ->join('asignacionproyectos', 'estudiantes.EstudianteID', '=', 'asignacionproyectos.EstudianteID')
            ->join('actividades_estudiante', 'estudiantes.EstudianteID', '=', 'actividades_estudiante.EstudianteID')
            ->join('proyectos', 'asignacionproyectos.ProyectoID', '=', 'proyectos.ProyectoID')
            ->select(
                'actividades_estudiante.fecha',
                'actividades_estudiante.actividades',
                'actividades_estudiante.numero_horas',
                'actividades_estudiante.evidencias',
                'actividades_estudiante.nombre_actividad',
            )
            ->where('proyectos.Estado', '=', 'Ejecucion')
            ->where('asignacionproyectos.ProyectoID', '=', $proyectoID) // Filtrar por ProyectoID de AsignacionProyecto
            ->orderBy('estudiantes.Apellidos', 'asc')
            ->get();

            $datosEstudiantes3 = DB::table('estudiantes')
            ->join('asignacionproyectos', 'estudiantes.EstudianteID', '=', 'asignacionproyectos.EstudianteID')
            ->join('actividades_estudiante', 'estudiantes.EstudianteID', '=', 'actividades_estudiante.EstudianteID')
            ->join('proyectos', 'asignacionproyectos.ProyectoID', '=', 'proyectos.ProyectoID')
            ->select(
                'actividades_estudiante.fecha',
                'actividades_estudiante.actividades',
                'actividades_estudiante.numero_horas',
                'actividades_estudiante.evidencias',
                'actividades_estudiante.nombre_actividad',
            )
            ->where('proyectos.Estado', '=', 'Ejecucion')
            ->where('asignacionproyectos.ProyectoID', '=', $proyectoID) // Filtrar por ProyectoID de AsignacionProyecto
            ->orderBy('estudiantes.Apellidos', 'asc')
            ->get();

        // Verificar si se recuperaron datos
        if ($datosEstudiantes->isEmpty()) {
            // Manejar el caso en que no se encontraron datos
            abort(404, 'No se encontraron datos de estudiantes asignados al proyecto activo.');
        }

        // Obtener Carrera, Provincia y FechaInicio del primer estudiante asignado al proyecto
        $primerEstudiante = $datosEstudiantes->first();
        $carreraEstudiante = strtoupper($primerEstudiante->Carrera);
        $provinciaEstudiante = $primerEstudiante->Provincia;
        $departamento = mb_strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú'], ['A', 'E', 'I', 'O', 'U'], $primerEstudiante->Departamento));
        $fechaInicioProyecto = $primerEstudiante->FechaInicio;
        $fechaFinProyecto = $primerEstudiante->FechaFinalizacion;
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre',
        ];
        
        $fechaFormateada2 = date('d ', strtotime($fechaFinProyecto)) . $meses[date('F', strtotime($fechaFinProyecto))] . date(' Y', strtotime($fechaFinProyecto));        
        $fechaFormateada = date('d ', strtotime($fechaInicioProyecto)) . $meses[date('F', strtotime($fechaInicioProyecto))] . date(' Y', strtotime($fechaInicioProyecto));
        $NombreProyecto = $primerEstudiante->NombreProyecto;
        $NombreProfesor = $primerEstudiante->NombreProfesor;
        $ApellidoProfesor = $primerEstudiante->ApellidoProfesor;
        $NombreAsignado = $primerEstudiante->NombreAsignado;
        $ApellidoAsignado = $primerEstudiante->ApellidoAsignado;

        $horasVinculacionConstante = 96;

        ///obtener nombre del estudiante
        $usuario = auth()->user();
        $estudiante = $usuario->estudiante;
        $nombreEstudiante = $estudiante->Nombres;
        $template->setValue('Nombre', $nombreEstudiante);
        $apelldioEstudiante = $estudiante->Apellidos;
        $template->setValue('Apellido', $apelldioEstudiante);

        $template->setValue('departamento', $departamento);

        $template->setValue('NombreProfesor', $NombreProfesor);
        $template->setValue('ApellidoProfesor', $ApellidoProfesor);
        $template->setValue('NombreAsignado', $NombreAsignado);
        $template->setValue('ApellidoAsignado', $ApellidoAsignado);
        $template->setValue('FechaFin', $fechaFormateada2);
        ///obtener Input nombreComunidad
        $nombreComunidad = $request->input('nombreComunidad');
        $provincia = $request->input('provincia');
        $template->setValue('provincia', $provincia);
        $canton = $request->input('canton');
        $template->setValue('canton', $canton);
        $parroquia = $request->input('parroquia');
        $template->setValue('parroquia', $parroquia);
        $direccion = $request->input('direccion');
        $template->setValue('direccion', $direccion);
        $template->setValue('comunidad', $nombreComunidad);

        $razones = $request->input('razones');
        $template->setValue('razones', $razones);
        
        $razones = $request->input('conclusiones');
        $template->setValue('conclusiones', $razones);

        $razones = $request->input('recomendaciones');
        $template->setValue('recomendaciones', $razones);


        

        // Clonar las filas en la plantilla
        $template->cloneRow('Nombres', count($datosEstudiantes));
        $template->cloneRow('actividades', count($datosEstudiantes2));



        // Ordenar los datos por apellidos en orden ascendente (A-Z)
        $datosEstudiantes = $datosEstudiantes->sortBy('Apellidos');

        // Bucle para reemplazar los valores en la plantilla
        $contador = 1; // Inicializamos el contador en 1
        foreach ($datosEstudiantes as $index => $estudiante) {
            $template->setValue('Numero#' . ($index + 1), $contador);
            $template->setValue('Apellidos#' . ($index + 1), $estudiante->Apellidos);
            $template->setValue('Nombres#' . ($index + 1), $estudiante->Nombres);
            $template->setValue('Cedula#' . ($index + 1), $estudiante->cedula);
            $template->setValue('Carrera#' . ($index + 1), $estudiante->Carrera);
            $template->setValue('HorasVinculacion#' . ($index + 1), $horasVinculacionConstante);
            $contador++;
        }
        foreach ($datosEstudiantes2 as $index => $estudiante) {
            $fechaActividades = date('d, F Y', strtotime($estudiante->fecha));
            $template->setValue('fecha#' . ($index + 1), $fechaActividades);
            $template->setValue('actividades#' . ($index + 1), $estudiante->actividades);
            $template->setValue('numero_horas#' . ($index + 1), $estudiante->numero_horas);
            // Obtener la ruta de la imagen desde la base de datos
            $rutaImagenDB = $estudiante->evidencias;
            // Verificar si la ruta comienza con "public/"
            if (strpos($rutaImagenDB, 'public/') === 0) {
                $rutaImagenDB = substr($rutaImagenDB, 7);
            }
            // Construir la ruta completa a la imagen en la carpeta "storage"
            $rutaImagen = storage_path('app/public/' . $rutaImagenDB);
            // Verificar si el archivo de imagen existe antes de usarlo
            if (file_exists($rutaImagen)) {
                // Insertar la imagen en el documento
                $template->setImageValue('evidencias#' . ($index + 1), [
                    'path' => $rutaImagen,
                    'width' => 150,
                    'height' => 150,
                    'ratio' => false,
                ]);


            }
        }

      //pasar todas las imagenes en un marcador para isnertarlaras
      $contadorFiguras = 1;
        $template->cloneRow('nombre_actividad', count($datosEstudiantes2));
        foreach ($datosEstudiantes2 as $index => $estudiante) {
            $nombreActividad = $estudiante->nombre_actividad;
            $nombreFigura = 'Figura ' . $contadorFiguras . ': ' . $nombreActividad;
            $template->setValue('nombre_actividad#' . ($index + 1), $nombreFigura);


            // Obtener la ruta de la imagen desde la base de datos
            $rutaImagenDB = $estudiante->evidencias;
            // Verificar si la ruta comienza con "public/"
            if (strpos($rutaImagenDB, 'public/') === 0) {
                $rutaImagenDB = substr($rutaImagenDB, 7);
            }
            // Construir la ruta completa a la imagen en la carpeta "storage"
            $rutaImagen = storage_path('app/public/' . $rutaImagenDB);
            // Verificar si el archivo de imagen existe antes de usarlo
            if (file_exists($rutaImagen)) {
                // Insertar la imagen en el documento
                $template->setImageValue('evidencias#' . ($index + 1), [
                    'path' => $rutaImagen,
                    'width' => 250,
                    'height' => 250,
                    'ratio' => false,
                ]);
            }
            $contadorFiguras++;

        }
        




      
        
        


        $objetivosEspecificos = $request->input('especificos');
        $alcanzados = $request->input('alcanzados'); 
        $porcentaje = $request->input('porcentaje'); 
        
        $contadorObjetivos = count($objetivosEspecificos);
        $template->cloneRow('especificos', $contadorObjetivos);
        
        foreach ($objetivosEspecificos as $index => $objetivo) {
            $template->setValue('especificos#' . ($index + 1), $objetivo);
            $template->setValue('alcanzados#' . ($index + 1), $alcanzados[$index]);
            $template->setValue('porcentaje#' . ($index + 1), $porcentaje[$index]);
        }
        

        // Reemplazar los valores constantes en la plantilla
        $template->setValue('Carrera', $carreraEstudiante);
        $template->setValue('Provincia', $provinciaEstudiante);
        $template->setValue('FechaInicio', $fechaFormateada);
        $template->setValue('NombreProyecto', $NombreProyecto);
        

        // Guardar el documento generado
        $documentoGeneradoPath = storage_path('app/public/1.-Informe-Servicio-Comunitario.docx');
        $template->saveAs($documentoGeneradoPath);

        // Descargar el documento generado
        return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
    
}


    ////////////////////////Creacion de reportes estudiantes vinculacion
    public function reportesVinculacion (Request $request){
        $plantillaPath = public_path('Plantillas\\Reporte-Vinculacion-Estudiantes.xlsx');
        $template = new TemplateProcessor($plantillaPath);

        $spreadsheet = IOFactory::load($plantillaPath);

        $datosEstudiantes = DB::table('estudiantesvinculacion')
        ->select(
            'estudiantesvinculacion.cedula_identidad',
            'estudiantesvinculacion.correo_electronico',
            'estudiantesvinculacion.espe_id',
            'estudiantesvinculacion.nombres',
            'estudiantesvinculacion.periodo_ingreso',
            'estudiantesvinculacion.periodo_vinculacion',
            'estudiantesvinculacion.actividades_macro',
            'estudiantesvinculacion.actividades_macro',
            'estudiantesvinculacion.docente_participante',
            'estudiantesvinculacion.fecha_inicio',
            'estudiantesvinculacion.fecha_fin',
            'estudiantesvinculacion.total_horas',
            'estudiantesvinculacion.director_proyecto',
            'estudiantesvinculacion.nombre_proyecto',
        )
        ->orderBy('estudiantesvinculacion.nombres', 'asc')
        ->get();

        $sheet = $spreadsheet->getActiveSheet();


        $filaInicio = 9; 
        $cantidadFilas = count($datosEstudiantes);
        $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

        $sheet->mergeCells('N9:V9');
    

        $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);
        $contador = 1; 

        // Bucle para reemplazar los valores en la plantilla
        foreach ($datosEstudiantes as $index => $estudiante) {
            $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
            $sheet->setCellValue('B' . ($filaInicio + $index), $estudiante->nombres);
            $sheet->setCellValue('C' . ($filaInicio + $index), $estudiante->cedula_identidad);
            $sheet->setCellValue('D' . ($filaInicio + $index), $estudiante->correo_electronico);
            $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->espe_id);
            $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->periodo_ingreso);
            $sheet->setCellValue('G' . ($filaInicio + $index), $estudiante->periodo_vinculacion);
            $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->actividades_macro);
            $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->docente_participante);
            $sheet->setCellValue('J' . ($filaInicio + $index), $estudiante->fecha_inicio);
            $sheet->setCellValue('K' . ($filaInicio + $index), $estudiante->fecha_fin);
            $sheet->setCellValue('L' . ($filaInicio + $index), $estudiante->total_horas);
            $sheet->setCellValue('M' . ($filaInicio + $index), $estudiante->director_proyecto);
            $coordenadaInicio = 'N' . ($filaInicio + $index); 
            $coordenadaFin = 'V' . ($filaInicio + $index);           
            $sheet->mergeCells($coordenadaInicio . ':' . $coordenadaFin);
            $sheet->setCellValue($coordenadaInicio, $estudiante->nombre_proyecto);
            $contador++;

    }
     // Guardar el documento generado
     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
     $documentoGeneradoPath = storage_path('app/public/Reporte-Vinculacion-Estudiantes.xlsx');

     $writer->save($documentoGeneradoPath);

     // Descargar el documento generado
     return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}


//////reporteria de proyectos///////

public function reportesProyectos (Request $request){
    $plantillaPath = public_path('Plantillas\\Reporte-Proyectos.xlsx');

    $spreadsheet = IOFactory::load($plantillaPath);

    $datosEstudiantes = DB::table('Proyectos')
    ->select(
        'Proyectos.NombreProyecto',
        'Proyectos.FechaInicio',
        'Proyectos.FechaFinalizacion',
        'Proyectos.DepartamentoTutor',
        'Proyectos.NombreProfesor',
        'Proyectos.ApellidoProfesor',
        'Proyectos.NombreAsignado',
        'Proyectos.ApellidoAsignado',
        'Proyectos.Estado',
        'Proyectos.DescripcionProyecto',

    )
    ->orderBy('Proyectos.NombreProyecto', 'asc')
    ->get();
    $sheet = $spreadsheet->getActiveSheet();


    $filaInicio = 9; 
    $cantidadFilas = count($datosEstudiantes);
    $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

    $contador = 1; 

    // Bucle para reemplazar los valores en la plantilla
    foreach ($datosEstudiantes as $index => $estudiante) {
        $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
        $sheet->setCellValue('B' . ($filaInicio + $index), $estudiante->NombreProyecto);
        $sheet->setCellValue('G' . ($filaInicio + $index), $estudiante->FechaInicio);
        $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->FechaFinalizacion);
        $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->DepartamentoTutor);
    
        // Combina apellido y nombre del profesor
        $profesor = $estudiante->ApellidoProfesor . ' ' . $estudiante->NombreProfesor;
        $sheet->setCellValue('C' . ($filaInicio + $index), $profesor);
    
        // Combina apellido y nombre del asignado
        $asignado = $estudiante->ApellidoAsignado . ' ' . $estudiante->NombreAsignado;
        $sheet->setCellValue('D' . ($filaInicio + $index), $asignado);
    
        $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->Estado);
        $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->DescripcionProyecto);
        $contador++;
    }
    
 // Guardar el documento generado
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
 $documentoGeneradoPath = storage_path('app/public/Reporte-Proyectos.xlsx');

 $writer->save($documentoGeneradoPath);

 // Descargar el documento generado
 return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}


////////Reporteria de estudiantes//////////
public function reportesEstudiantes (Request $request){
    $plantillaPath = public_path('Plantillas\\Reporte-Estudiantes.xlsx');

    $spreadsheet = IOFactory::load($plantillaPath);

    $datosEstudiantes = DB::table('Estudiantes')
    ->select(
        'Estudiantes.Nombres',
        'Estudiantes.Apellidos',
        'Estudiantes.espe_id',
        'Estudiantes.celular',
        'Estudiantes.cedula',
        'Estudiantes.Correo',
        'Estudiantes.Cohorte',
        'Estudiantes.Periodo',
        'Estudiantes.Carrera',
        'Estudiantes.Departamento',
        'Estudiantes.Estado',

    )
    ->orderBy('Estudiantes.Apellidos', 'asc')
    ->get();
    $sheet = $spreadsheet->getActiveSheet();


    $filaInicio = 9; 
    $cantidadFilas = count($datosEstudiantes);
    $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

    $contador = 1; 

    // Bucle para reemplazar los valores en la plantilla
    foreach ($datosEstudiantes as $index => $estudiante) {
        $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
        $sheet->setCellValue('B' . ($filaInicio + $index), $estudiante->Apellidos . ' ' . $estudiante->Nombres);
        $sheet->setCellValue('C' . ($filaInicio + $index), $estudiante->espe_id);
        $sheet->setCellValue('D' . ($filaInicio + $index), $estudiante->celular);
        $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->cedula);
        $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->Correo);
        $sheet->setCellValue('G' . ($filaInicio + $index), $estudiante->Cohorte);
        $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->Periodo);
        $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->Carrera);
        $sheet->setCellValue('J' . ($filaInicio + $index), $estudiante->Departamento);
    
        // Reemplazar el estado según las condiciones
        $estadoReemplazado = $estudiante->Estado;
        if ($estadoReemplazado === 'Aprobado') {
            $estadoReemplazado = 'Vinculación';
        } elseif ($estadoReemplazado === 'Aprobado-practicas') {
            $estadoReemplazado = 'Prácticas';
        }
    
        $sheet->setCellValue('K' . ($filaInicio + $index), $estadoReemplazado);
        $contador++;
    }
    
 // Guardar el documento generado
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
 $documentoGeneradoPath = storage_path('app/public/Reporte-Estudiantes.xlsx');

 $writer->save($documentoGeneradoPath);

 // Descargar el documento generado
 return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}



///////Reporteria para las empresas agregadas////////////////////////////////
public function reportesEmpresas (Request $request){
    $plantillaPath = public_path('Plantillas\\Reporte-Empresas.xlsx');

    $spreadsheet = IOFactory::load($plantillaPath);

    $datosEstudiantes = DB::table('Empresas')
    ->select(
        'Empresas.nombreEmpresa',
        'Empresas.rucEmpresa',
        'Empresas.provincia',
        'Empresas.ciudad',
        'Empresas.direccion',
        'Empresas.correo',
        'Empresas.nombreContacto',
        'Empresas.telefonoContacto',
        'Empresas.actividadesMacro',
        'Empresas.cuposDisponibles',
        'Empresas.created_at',
        'Empresas.updated_at',

    )
    ->get();
    $sheet = $spreadsheet->getActiveSheet();


    $filaInicio = 9; 
    $cantidadFilas = count($datosEstudiantes);
    $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

    $contador = 1; 

    // Bucle para reemplazar los valores en la plantilla
    foreach ($datosEstudiantes as $index => $estudiante) {
        $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
        $sheet->setCellValue('B' . ($filaInicio + $index), $estudiante->nombreEmpresa);
        $sheet->setCellValue('C' . ($filaInicio + $index), $estudiante->rucEmpresa);
        $sheet->setCellValue('D' . ($filaInicio + $index), $estudiante->provincia);
        $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->ciudad);
        $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->direccion);
        $sheet->setCellValue('G' . ($filaInicio + $index), $estudiante->correo);
        $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->nombreContacto);
        $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->telefonoContacto);
        $sheet->setCellValue('J' . ($filaInicio + $index), $estudiante->actividadesMacro);
        $sheet->setCellValue('K' . ($filaInicio + $index), $estudiante->cuposDisponibles);
        $sheet->setCellValue('L' . ($filaInicio + $index), $estudiante->created_at);
        $sheet->setCellValue('M' . ($filaInicio + $index), $estudiante->updated_at);
      
    }
    
 // Guardar el documento generado
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
 $documentoGeneradoPath = storage_path('app/public/Reporte-Empresas.xlsx');

 $writer->save($documentoGeneradoPath);

 // Descargar el documento generado
 return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}



/////////reporteria Practias I////////////////////////////////////////
public function reportesPracticaI (Request $request){
    $plantillaPath = public_path('Plantillas\\Reporte-PracticasI.xlsx');

    $spreadsheet = IOFactory::load($plantillaPath);

    $datosEstudiantes = DB::table('PracticasI')
    ->select(
        'PracticasI.NombreEstudiante',
        'PracticasI.ApellidoEstudiante',
        'PracticasI.Departamento',
        'PracticasI.Nivel',
        'PracticasI.Practicas',
        'PracticasI.DocenteTutor',
        'PracticasI.Empresa',
        'PracticasI.CedulaTutorEmpresarial',
        'PracticasI.NombreTutorEmpresarial',
        'PracticasI.Funcion',
        'PracticasI.TelefonoTutorEmpresarial',
        'PracticasI.EmailTutorEmpresarial',
        'PracticasI.DepartamentoTutorEmpresarial',
        'PracticasI.EstadoAcademico',
        'PracticasI.FechaInicio',
        'PracticasI.FechaFinalizacion',
        'PracticasI.HorasPlanificadas',
        'PracticasI.HoraEntrada',
        'PracticasI.HoraSalida',
        'PracticasI.AreaConocimiento',
        'PracticasI.Estado',
        'PracticasI.created_at',
        'PracticasI.updated_at',

    )
    ->get();
    $sheet = $spreadsheet->getActiveSheet();


    $filaInicio = 9; 
    $cantidadFilas = count($datosEstudiantes);
    $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

    $contador = 1; 

    // Bucle para reemplazar los valores en la plantilla
    foreach ($datosEstudiantes as $index => $estudiante) {
        $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
        $nombreCombinado = $estudiante->ApellidoEstudiante . ' ' . $estudiante->NombreEstudiante;
        $sheet->setCellValue('B' . ($filaInicio + $index), $nombreCombinado);
        $sheet->setCellValue('C' . ($filaInicio + $index), $estudiante->Departamento);
        $sheet->setCellValue('D' . ($filaInicio + $index), $estudiante->Nivel);
        $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->Practicas);
        $sheet->setCellValue('G' . ($filaInicio + $index), $estudiante->DocenteTutor);
        $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->Empresa);
        $sheet->setCellValue('K' . ($filaInicio + $index), $estudiante->CedulaTutorEmpresarial);
        $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->NombreTutorEmpresarial);
        $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->Funcion);
        $sheet->setCellValue('J' . ($filaInicio + $index), $estudiante->TelefonoTutorEmpresarial);
        $sheet->setCellValue('L' . ($filaInicio + $index), $estudiante->EmailTutorEmpresarial);
        $sheet->setCellValue('M' . ($filaInicio + $index), $estudiante->DepartamentoTutorEmpresarial);
        $sheet->setCellValue('N' . ($filaInicio + $index), $estudiante->EstadoAcademico);
        $sheet->setCellValue('O' . ($filaInicio + $index), $estudiante->FechaInicio);
        $sheet->setCellValue('P' . ($filaInicio + $index), $estudiante->FechaFinalizacion);
        $sheet->setCellValue('Q' . ($filaInicio + $index), $estudiante->HorasPlanificadas);
        $sheet->setCellValue('R' . ($filaInicio + $index), $estudiante->HoraEntrada);
        $sheet->setCellValue('S' . ($filaInicio + $index), $estudiante->HoraSalida);
        $sheet->setCellValue('T' . ($filaInicio + $index), $estudiante->AreaConocimiento);
        $sheet->setCellValue('U' . ($filaInicio + $index), $estudiante->Estado);
        $sheet->setCellValue('V' . ($filaInicio + $index), $estudiante->created_at);
        $sheet->setCellValue('W' . ($filaInicio + $index), $estudiante->updated_at);
      
    }
    
 // Guardar el documento generado
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
 $documentoGeneradoPath = storage_path('app/public/Reporte-PracticasI.xlsx');

 $writer->save($documentoGeneradoPath);

 // Descargar el documento generado
 return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}

    

/////////reporteria Practias II////////////////////////////////////////
public function reportesPracticaII (Request $request){
    $plantillaPath = public_path('Plantillas\\Reporte-PracticasII.xlsx');

    $spreadsheet = IOFactory::load($plantillaPath);

    $datosEstudiantes = DB::table('PracticasII')
    ->select(
        'PracticasII.NombreEstudiante',
        'PracticasII.ApellidoEstudiante',
        'PracticasII.Departamento',
        'PracticasII.Nivel',
        'PracticasII.Practicas',
        'PracticasII.DocenteTutor',
        'PracticasII.Empresa',
        'PracticasII.CedulaTutorEmpresarial',
        'PracticasII.NombreTutorEmpresarial',
        'PracticasII.Funcion',
        'PracticasII.TelefonoTutorEmpresarial',
        'PracticasII.EmailTutorEmpresarial',
        'PracticasII.DepartamentoTutorEmpresarial',
        'PracticasII.EstadoAcademico',
        'PracticasII.FechaInicio',
        'PracticasII.FechaFinalizacion',
        'PracticasII.HorasPlanificadas',
        'PracticasII.HoraEntrada',
        'PracticasII.HoraSalida',
        'PracticasII.AreaConocimiento',
        'PracticasII.Estado',
        'PracticasII.created_at',
        'PracticasII.updated_at',

    )
    ->get();
    $sheet = $spreadsheet->getActiveSheet();


    $filaInicio = 9; 
    $cantidadFilas = count($datosEstudiantes);
    $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

    $contador = 1; 

    // Bucle para reemplazar los valores en la plantilla
    foreach ($datosEstudiantes as $index => $estudiante) {
        $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
        $nombreCombinado = $estudiante->ApellidoEstudiante . ' ' . $estudiante->NombreEstudiante;
        $sheet->setCellValue('B' . ($filaInicio + $index), $nombreCombinado);
        $sheet->setCellValue('C' . ($filaInicio + $index), $estudiante->Departamento);
        $sheet->setCellValue('D' . ($filaInicio + $index), $estudiante->Nivel);
        $sheet->setCellValue('E' . ($filaInicio + $index), $estudiante->Practicas);
        $sheet->setCellValue('G' . ($filaInicio + $index), $estudiante->DocenteTutor);
        $sheet->setCellValue('F' . ($filaInicio + $index), $estudiante->Empresa);
        $sheet->setCellValue('K' . ($filaInicio + $index), $estudiante->CedulaTutorEmpresarial);
        $sheet->setCellValue('H' . ($filaInicio + $index), $estudiante->NombreTutorEmpresarial);
        $sheet->setCellValue('I' . ($filaInicio + $index), $estudiante->Funcion);
        $sheet->setCellValue('J' . ($filaInicio + $index), $estudiante->TelefonoTutorEmpresarial);
        $sheet->setCellValue('L' . ($filaInicio + $index), $estudiante->EmailTutorEmpresarial);
        $sheet->setCellValue('M' . ($filaInicio + $index), $estudiante->DepartamentoTutorEmpresarial);
        $sheet->setCellValue('N' . ($filaInicio + $index), $estudiante->EstadoAcademico);
        $sheet->setCellValue('O' . ($filaInicio + $index), $estudiante->FechaInicio);
        $sheet->setCellValue('P' . ($filaInicio + $index), $estudiante->FechaFinalizacion);
        $sheet->setCellValue('Q' . ($filaInicio + $index), $estudiante->HorasPlanificadas);
        $sheet->setCellValue('R' . ($filaInicio + $index), $estudiante->HoraEntrada);
        $sheet->setCellValue('S' . ($filaInicio + $index), $estudiante->HoraSalida);
        $sheet->setCellValue('T' . ($filaInicio + $index), $estudiante->AreaConocimiento);
        $sheet->setCellValue('U' . ($filaInicio + $index), $estudiante->Estado);
        $sheet->setCellValue('V' . ($filaInicio + $index), $estudiante->created_at);
        $sheet->setCellValue('W' . ($filaInicio + $index), $estudiante->updated_at);
      
    }
    
 // Guardar el documento generado
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
 $documentoGeneradoPath = storage_path('app/public/Reporte-PracticasII.xlsx');

 $writer->save($documentoGeneradoPath);

 // Descargar el documento generado
 return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}

/////////////////////reporte de estudiantes con proyectos en vinculacion//////////////////////////
public function reporteVinculacionProyectos(Request $request)
{
    $plantillaPath = public_path('Plantillas\\Proyectos_Vinculacion.xlsx');
    $spreadsheet = IOFactory::load($plantillaPath);

    $datosProyectosYEstudiantes = Proyecto::with(['estudiantes' => function ($query) {
        $query->orderBy('Apellidos', 'desc');
    }])->get();

    $sheet = $spreadsheet->getActiveSheet();

    $filaInicio = 9;
    $cantidadFilas = count($datosProyectosYEstudiantes);
    $sheet->insertNewRowBefore($filaInicio + 1, $cantidadFilas - 1);

    $contador = 1;

    // Bucle para reemplazar los valores en la plantilla
    foreach ($datosProyectosYEstudiantes as $index => $proyecto) {
        // Información del proyecto
        $sheet->setCellValue('A' . ($filaInicio + $index), $contador);
        $sheet->setCellValue('B' . ($filaInicio + $index), $proyecto->NombreProyecto);
        $sheet->setCellValue('C' . ($filaInicio + $index), $proyecto->ApellidoProfesor . ' ' . $proyecto->NombreProfesor);
        $sheet->setCellValue('E' . ($filaInicio + $index), $proyecto->ApellidoAsignado . ' ' . $proyecto->NombreAsignado);
        $sheet->setCellValue('D' . ($filaInicio + $index), $proyecto->DescripcionProyecto);
        $sheet->setCellValue('G' . ($filaInicio + $index), $proyecto->FechaInicio);
        $sheet->setCellValue('H' . ($filaInicio + $index), $proyecto->FechaFinalizacion);
        $sheet->setCellValue('I' . ($filaInicio + $index), $proyecto->DepartamentoTutor);

        // Información de los estudiantes asignados al proyecto
        $estudiantesAsignados = $proyecto->estudiantes;

        // Concatenar los nombres de los estudiantes con saltos de línea
        $nombresEstudiantes = [];
        foreach ($estudiantesAsignados as $estudiante) {
            $nombresEstudiantes[] = $estudiante->Apellidos . ' ' . $estudiante->Nombres;
        }

        $nombresEstudiantes = implode("\n", $nombresEstudiantes);

        // Establecer el estilo de texto para permitir saltos de línea
        $sheet->getStyle('F' . ($filaInicio + $index))->getAlignment()->setWrapText(true);

        $sheet->setCellValue('F' . ($filaInicio + $index), $nombresEstudiantes);

        $contador++;
    }

    // Guardar el documento generado
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $documentoGeneradoPath = storage_path('app/public/Proyectos_Vinculacion.xlsx');

    $writer->save($documentoGeneradoPath);

    // Descargar el documento generado
    return response()->download($documentoGeneradoPath)->deleteFileAfterSend(true);
}












































    /////////entrar a la vista
    public function mostrarFormulario()
{
    $estudiante = Auth::user()->estudiante;

    // Verificar el estado del estudiante
    if ($estudiante->Estado === 'En proceso de revision' || $estudiante->Estado === 'Aprobado-practicas') {
        // Redirigir o mostrar un mensaje de error, según tus necesidades
        return redirect()->back()->with('error', 'No tienes acceso a esta página en este momento.');
    }
    

    // Obtener las actividades registradas si el estado permite el acceso
    $actividadesRegistradas = ActividadEstudiante::where('EstudianteID', $estudiante->EstudianteID)->get();

    return view('estudiantes.documentos', ['actividadesRegistradas' => $actividadesRegistradas]);
}


}