<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\estudiantesvinculacion;
use App\Models\Estudiante;
use ZipArchive;

use App\Models\ProyectosParticipanteVinculacion;
use Illuminate\Support\Facades\Mail;
use App\Models\Usuario;
use App\Models\ProfesUniversidad;
use Illuminate\Support\Facades\DB;
use App\Models\PracticaI;
use App\Models\PracticaII;
use App\Models\DirectorVinculacion;
use App\Models\ParticipanteVincunlacion;
use App\Mail\EstudianteAprobado;
use App\Models\Empresa;
use App\Models\AsignacionProyecto;



class CoordinadorController extends Controller
{
    public function index(Request $request)
{
    // Obtén la lista de proyectos
    $proyectos = Proyecto::all();
    $query = Proyecto::query();
    $elementosPorPagina = $request->input('elementosPorPagina');
    $proyectosDisponibles = Proyecto::where('Estado', 'Ejecucion')->get();
    $estudiantesAprobados = Estudiante::where('Estado', 'Aprobado')
            ->whereNotIn('EstudianteID', AsignacionProyecto::pluck('EstudianteID')->toArray())
            ->get();
    $proyectosPorDepartamento2 = Proyecto::paginate($elementosPorPagina);

    $terminoBusqueda = $request->input('terminoBusqueda');
    if ($terminoBusqueda) {
        $query->where(function ($subquery) use ($terminoBusqueda) {
            $subquery->where('ProyectoID', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('NombreProfesor', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('ApellidoProfesor', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('NombreAsignado', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('ApellidoAsignado', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('CorreoProfeAsignado', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('NombreProyecto', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('DescripcionProyecto', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('CorreoElectronicoTutor', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('DepartamentoTutor', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('FechaInicio', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('FechaFinalizacion', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('cupos', 'LIKE', "%$terminoBusqueda%")
                ->orWhere('Estado', 'LIKE', "%$terminoBusqueda%");
        });
    }


    // Organiza los proyectos por departamento
    $proyectosPorDepartamento = [];

    foreach ($proyectos as $proyecto) {
        $codigoDepartamento = $proyecto->DepartamentoTutor;

        if (!isset($proyectosPorDepartamento[$codigoDepartamento])) {
            $proyectosPorDepartamento[$codigoDepartamento] = [];
        }

        $proyectosPorDepartamento[$codigoDepartamento][] = $proyecto;
    }

    return view('coordinador.index', [
        'proyectosPorDepartamento' => $proyectosPorDepartamento,
        'proyectosDisponibles' => $proyectosDisponibles,
        'estudiantesAprobados' => $estudiantesAprobados,
        'proyectosPorDepartamento2' => $proyectosPorDepartamento2,
        'elementosPorPagina' => $elementosPorPagina,
        'proyectos' => $proyectos

    ]);
}





    public function crearProyectoForm()
    {
        $profesores = ProfesUniversidad::all();
        $proyectosPorDepartamento = Proyecto::paginate(10);



        return view('coordinador.agregarProyecto', ['profesores' => $profesores,
        'proyectosPorDepartamento' => $proyectosPorDepartamento

    ]);
    }

    public function crearProyecto(Request $request)
    {
        // Valida los datos del formulario antes de crear el proyecto
        $validatedData = $request->validate([
            'DirectorProyecto' => 'required|string|max:255',
            'ProfesorParticipante' => 'required|string|max:255',
            'NombreProyecto' => 'required|string|max:255',
            'DescripcionProyecto' => 'required|string',
            'DepartamentoTutor' => 'required|string|max:255',
            'FechaInicio' => 'required|date',
            'FechaFinalizacion' => 'required|date',
            'cupos' => 'required|integer',
            'Estado' => 'required|string|max:255',
        ]);

        // Divide el valor de DirectorProyecto y ProfesorParticipante para obtener los correos
        $directorCorreo = explode(' - ', $validatedData['DirectorProyecto'])[0];
        $participanteCorreo = explode(' - ', $validatedData['ProfesorParticipante'])[0];

        // Verifica si el director ya está asignado a otro proyecto
        $directorAsignado = Proyecto::where(function ($query) use ($directorCorreo) {
            $query->where('CorreoElectronicoTutor', $directorCorreo)
                ->orWhere('CorreoProfeAsignado', $directorCorreo);
        })->where('Estado', '<>', 'Terminado')->first();

        // Verifica si el participante ya está asignado a otro proyecto
        $participanteAsignado = Proyecto::where(function ($query) use ($participanteCorreo) {
            $query->where('CorreoElectronicoTutor', $participanteCorreo)
                ->orWhere('CorreoProfeAsignado', $participanteCorreo);
        })->where('Estado', '<>', 'Terminado')->first();

        // Si el director o el participante ya están asignados a otro proyecto, muestra un mensaje de error
             if ($directorAsignado || $participanteAsignado) {
             return redirect()->route('coordinador.index')->with('error', 'No se puede crear el proyecto. Uno de los profesores ya está asignado a otro proyecto en ejecución.');
                }
        

        // Continúa creando el proyecto si ninguno de los profesores está asignado a otro proyecto
        $director = ProfesUniversidad::where('Correo', $directorCorreo)->first();
        $participante = ProfesUniversidad::where('Correo', $participanteCorreo)->first();

        $proyecto = Proyecto::create([
            'NombreProfesor' => $director->Nombres,
            'ApellidoProfesor' => $director->Apellidos,
            'NombreAsignado' => $participante->Nombres,
            'CedulaDirector' => $director->Cedula,
            'CedulaAsignado' => $participante->Cedula,
            'ApellidoAsignado' => $participante->Apellidos,
            'CorreoProfeAsignado' => $participante->Correo,
            'NombreProyecto' => $validatedData['NombreProyecto'],
            'DescripcionProyecto' => $validatedData['DescripcionProyecto'],
            'CorreoElectronicoTutor' => $director->Correo,
            'DepartamentoTutor' => $validatedData['DepartamentoTutor'],
            'FechaInicio' => $validatedData['FechaInicio'],
            'FechaFinalizacion' => $validatedData['FechaFinalizacion'],
            'cupos' => $validatedData['cupos'],
            'Estado' => $validatedData['Estado'],
        ]);

        // Crear los registros en la tabla Usuarios y otras operaciones (no proporcionadas en el código anterior)

        Usuario::create([
            'Nombre' => $director->Nombres,
            'Apellido' => $director->Apellidos,
            'CorreoElectronico' => $director->Correo,
            'Contrasena' => bcrypt('123'),
            'TipoUsuario' => 'Profesor',
            'Estado' => 'DirectorVinculacion',
        ]);

        Usuario::create([
            'Nombre' => $participante->Nombres,
            'Apellido' => $participante->Apellidos,
            'CorreoElectronico' => $participante->Correo,
            'Contrasena' => bcrypt('123'),
            'TipoUsuario' => 'Profesor',
            'Estado' => 'ParticipanteVinculacion',
        ]);

        DirectorVinculacion::create([
            'Apellidos' => $director->Apellidos,
            'Nombres' => $director->Nombres,
            'Correo' => $director->Correo,
            'Cedula' => $director->Cedula,
            'Departamento' => $director->Departamento,
        ]);

        // Inserta registros en la tabla ParticipanteVincunlacion
        ParticipanteVincunlacion::create([
            'Apellidos' => $participante->Apellidos,
            'Nombres' => $participante->Nombres,
            'Correo' => $participante->Correo,
            'Cedula' => $participante->Cedula,
            'Departamento' => $participante->Departamento,
        ]);

        return redirect()->route('coordinador.index')->with('success', 'Proyecto agregado correctamente');
    }




    ////////editar los proyectos agregados
    public function editProyectoForm($ProyectoID)
    {
        $proyecto = Proyecto::findOrFail($ProyectoID);
        return view('coordinador.editarProyecto', compact('proyecto'));
    }

    public function editProyecto(Request $request, $ProyectoID)
{
    // Valida los datos del formulario de edición antes de actualizar el proyecto
    $validatedData = $request->validate([
        'NombreProfesor' => 'required|string|max:255',
        'ApellidoProfesor' => 'required|string|max:255',
        'NombreAsignado' => 'required|string|max:255',
        'ApellidoAsignado' => 'required|string|max:255',
        'CorreoProfeAsignado' => 'required|email|max:255',
        'NombreProyecto' => 'required|string|max:255',
        'DescripcionProyecto' => 'required|string',
        'CorreoElectronicoTutor' => 'required|email|max:255',
        'DepartamentoTutor' => 'required|string|max:255',
        'FechaInicio' => 'required|date',
        'FechaFinalizacion' => 'required|date',
        'cupos' => 'required|integer',
        'Estado' => 'required|string|max:255',
    ]);

    $proyecto = Proyecto::findOrFail($ProyectoID);
    $proyecto->update($validatedData);

    // Verificar si el estado del proyecto cambió a "Terminado"
    if ($proyecto->Estado === 'Terminado') {
        // Obtener todas las asignaciones relacionadas con el proyecto
        $asignaciones = AsignacionProyecto::where('ProyectoID', $ProyectoID)->get();

        foreach ($asignaciones as $asignacion) {
            $estudiante = $asignacion->estudiante;

            // Actualizar el estado del estudiante en la tabla existente
            $estudiante->update([
                'Estado' => 'Aprobado-practicas',
            ]);

            // Mover al estudiante a la tabla 'estudiantesvinculacion'
            EstudiantesVinculacion::create([
                'cedula_identidad' => $estudiante->cedula,
                'correo_electronico' => $estudiante->Correo,
                'espe_id' => $estudiante->espe_id,
                'nombres' => $estudiante->Apellidos . ' ' . $estudiante->Nombres,
                'periodo_ingreso' => $estudiante->Cohorte,
                'periodo_vinculacion' => $estudiante->Periodo,
                'actividades_macro' => $proyecto->DescripcionProyecto,
                'docente_participante' => $proyecto->NombreAsignado . ' ' . $proyecto->ApellidoAsignado,
                'fecha_inicio' => $proyecto->FechaInicio,
                'fecha_fin' => $proyecto->FechaFinalizacion,
                'total_horas' => '96',
                'director_proyecto' => $proyecto->NombreProfesor . ' ' . $proyecto->ApellidoProfesor,
                'nombre_proyecto' => $proyecto->NombreProyecto,
            ]);

        }
        Usuario::where('Nombre', $proyecto->NombreProfesor)->delete();
        Usuario::where('Nombre', $proyecto->NombreAsignado)->delete();
    }

    return redirect()->route('coordinador.index')->with('success', 'Proyecto actualizado correctamente');
}




    ///eliminar proyecto
    public function deleteProyecto($ProyectoID)
{
    // Buscar el proyecto por ID
    $proyecto = Proyecto::findOrFail($ProyectoID);

    // Verificar si el Estado del proyecto es "Ejecucion"
    if ($proyecto->Estado === 'Ejecucion') {
        return redirect()->route('coordinador.index')->with('error', 'No puedes eliminar un proyecto en estado de ejecución');
    }

    // Obtener todas las asignaciones relacionadas con el proyecto
    $asignaciones = AsignacionProyecto::where('ProyectoID', $ProyectoID)->get();

    // Eliminar cada asignación relacionada con el proyecto
    foreach ($asignaciones as $asignacion) {
        $asignacion->delete();
    }

    // Eliminar el proyecto
    $proyecto->delete();

    return redirect()->route('coordinador.index')->with('success', 'Proyecto y asignaciones relacionadas eliminados correctamente');
}




    ///////////////estudiantes ordenados por sus departamentos

    public function mostrarEstudiantesAprobados(Request $request)
    {
        // Obtener todos los estudiantes "Aprobados"
        $estudiantesAprobados = Estudiante::whereIn('Estado', ['Aprobado', 'Aprobado-practicas'])->get();
        $elementosPorPagina = $request->input('elementosPorPagina');
        $queryEstudiantesVinculacion = estudiantesvinculacion::query();
        $queryEstudiantesVinculacion->orderBy('nombres', 'asc');

        // Inicializar arreglos para cada departamento
        $estudiantesDCCO = [];
        $estudiantesDCEX = [];
        $estudiantesDCVA = [];



        // Organizar estudiantes en los arreglos según el departamento
        foreach ($estudiantesAprobados as $estudiante) {
            switch ($estudiante->Departamento) {
                case 'Ciencias de la Computación':
                    $estudiantesDCCO[] = $estudiante;
                    break;
                case 'Ciencias Exactas':
                    $estudiantesDCEX[] = $estudiante;
                    break;
                case 'Ciencias de la Vida y Agricultura':
                    $estudiantesDCVA[] = $estudiante;
                    break;
                // Agrega más casos según sea necesario para otros departamentos
            }
        }

        if ($request->has('buscarEstudiantes')) {
            $busqueda = $request->input('buscarEstudiantes');
            $queryEstudiantesVinculacion->where(function ($query) use ($busqueda) {
                $query->where('cedula_identidad', 'like', '%' . $busqueda . '%')
                    ->orWhere('correo_electronico', 'like', '%' . $busqueda . '%')
                    ->orWhere('espe_id', 'like', '%' . $busqueda . '%')
                    ->orWhere('nombres', 'like', '%' . $busqueda . '%')
                    ->orWhere('periodo_ingreso', 'like', '%' . $busqueda . '%')
                    ->orWhere('periodo_vinculacion', 'like', '%' . $busqueda . '%')
                    ->orWhere('actividades_macro', 'like', '%' . $busqueda . '%')
                    ->orWhere('docente_participante', 'like', '%' . $busqueda . '%')
                    ->orWhere('fecha_inicio', 'like', '%' . $busqueda . '%')
                    ->orWhere('fecha_fin', 'like', '%' . $busqueda . '%')
                    ->orWhere('total_horas', 'like', '%' . $busqueda . '%')
                    ->orWhere('director_proyecto', 'like', '%' . $busqueda . '%')
                    ->orWhere('nombre_proyecto', 'like', '%' . $busqueda . '%');
            });
        }

        $estudiantesVinculacion = $queryEstudiantesVinculacion->paginate($elementosPorPagina);




        // Retorna la vista con los estudiantes organizados por departamento
        return view('coordinador.estudiantesAprobados', compact('estudiantesDCCO', 'estudiantesDCEX', 'estudiantesDCVA', 'estudiantesVinculacion', 'elementosPorPagina'));
    }


    //vistar para asignar proyectos
    public function asignarProyectos()
    {
        // Obtener proyectos disponibles
        $proyectosDisponibles = Proyecto::all();

        // Obtener estudiantes aprobados que no están asignados a proyectos
        $estudiantesAprobados = Estudiante::where('Estado', 'Aprobado')
            ->whereNotIn('EstudianteID', AsignacionProyecto::pluck('EstudianteID')->toArray())
            ->get();

        return view('coordinador.asignarProyectos', [
            'proyectosDisponibles' => $proyectosDisponibles,
            'estudiantesAprobados' => $estudiantesAprobados,
        ]);
    }

    public function guardarAsignacion(Request $request)
{
    // Validación de datos
    $request->validate([
        'proyecto_id' => 'required|exists:proyectos,ProyectoID',
        'estudiante_id' => 'required|exists:estudiantes,EstudianteID',
        'fecha_asignacion' => 'required|date',
    ]);

    // Obtener el proyecto seleccionado
    $proyecto = Proyecto::find($request->proyecto_id);

    // Verificar si hay cupos disponibles en el proyecto
    if ($proyecto->cupos > 0) {
        // Obtener el correo del director y el participante desde el proyecto
        $directorCorreo = $proyecto->CorreoElectronicoTutor; // Asume que existe un campo 'CorreoDirector' en la tabla 'proyectos'
        $participanteCorreo = $proyecto->CorreoProfeAsignado; // Asume que existe un campo 'CorreoParticipante' en la tabla 'proyectos'

        // Buscar el ID del director en la tabla 'DirectorVincunlacion' por el correo
        $director = DirectorVinculacion::where('Correo', $directorCorreo)->first();

        // Buscar el ID del participante en la tabla 'ParticipanteVincunlacion' por el correo
        $participante = ParticipanteVincunlacion::where('Correo', $participanteCorreo)->first();

        // Crear una nueva asignación
        AsignacionProyecto::create([
            'EstudianteID' => $request->estudiante_id,
            'ProyectoID' => $request->proyecto_id,
            'DirectorID' => $director->ID_Director, // Utiliza el ID del director encontrado
            'ParticipanteID' => $participante->ID_Participante, // Utiliza el ID del participante encontrado
            'FechaAsignacion' => $request->fecha_asignacion,
        ]);

        // Reducir el número de cupos disponibles en el proyecto
        $proyecto->decrement('cupos');

        return redirect()->route('coordinador.proyectosEstudiantes')->with('success', 'Asignación realizada con éxito.');
    } else {
        return redirect()->route('coordinador.proyectosEstudiantes')->with('error', 'No hay cupos disponibles en el proyecto seleccionado.');
    }
}


    public function proyectosEstudiantes()
    {
        ///elementos por pagina
        $elementosPorPagina2 = request('elementosPorPagina2');
        // Obtén todas las asignaciones de proyectos con información de estudiante y proyecto
        $asignaciones = AsignacionProyecto::with('estudiante', 'proyecto')->paginate($elementosPorPagina2); // Cambia 10 por el número de elementos por página que desees

        return view('coordinador.proyectosEstudiantes', compact('asignaciones', 'elementosPorPagina2'));
    }




/////////////retornar a la vista agregar empresa
public function agregarEmpresa(Request $request)
{
    $elementosPorPagina = $request->input('elementosPorPagina');
    $empresas = Empresa::paginate($elementosPorPagina);

    return view('coordinador.agregarEmpresa', compact('empresas', 'elementosPorPagina'));
}

public function descargar($tipo, $id)
{
    $empresa = Empresa::findOrFail($id);

    if ($tipo === 'carta') {
        $archivoNombre = $empresa->cartaCompromiso;
        $nombreArchivo = 'carta_compromiso.pdf';
    } elseif ($tipo === 'convenio') {
        $archivoNombre = $empresa->convenio;
        $nombreArchivo = 'convenio.pdf';
    } else {
        abort(404);
    }

    $archivo = storage_path('app/').$archivoNombre;

    if (file_exists($archivo)) {
        return response()->file(
            $archivo,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
            ]
        );
    } else {
        abort(404); // Maneja el caso en que el archivo no exista
    }
}












public function guardarEmpresa(Request $request)
{
    try {
        // Valida los datos del formulario antes de guardar
        $validatedData = $request->validate([
            'nombreEmpresa' => 'required|string|max:255',
            'rucEmpresa' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email',
            'nombreContacto' => 'required|string|max:255',
            'telefonoContacto' => 'required|string|max:255',
            'actividadesMacro' => 'required|string',
            'cuposDisponibles' => 'required|integer',
            'cartaCompromiso' => 'file',
            'convenio' => 'file',
        ]);

        // Crea una nueva instancia de Empresa y asigna los datos validados
        $empresa = new Empresa([
            'nombreEmpresa' => $validatedData['nombreEmpresa'],
            'rucEmpresa' => $validatedData['rucEmpresa'],
            'provincia' => $validatedData['provincia'],
            'ciudad' => $validatedData['ciudad'],
            'direccion' => $validatedData['direccion'],
            'correo' => $validatedData['correo'],
            'nombreContacto' => $validatedData['nombreContacto'],
            'telefonoContacto' => $validatedData['telefonoContacto'],
            'actividadesMacro' => $validatedData['actividadesMacro'],
            'cuposDisponibles' => $validatedData['cuposDisponibles'],
        ]);

        // Maneja el archivo de cartaCompromiso
        if ($request->hasFile('cartaCompromiso')) {
            $cartaCompromisoPath = $request->file('cartaCompromiso')->store('archivos');
            $empresa->cartaCompromiso = $cartaCompromisoPath;
        }

        // Maneja el archivo de convenio
        if ($request->hasFile('convenio')) {
            $convenioPath = $request->file('convenio')->store('archivos');
            $empresa->convenio = $convenioPath;
        }

        // Guarda la empresa en la base de datos
        $empresa->save();

        // Redirige de vuelta a la página de agregar empresa con un mensaje de éxito
        return redirect()->route('coordinador.agregarEmpresa')->with('success', 'Empresa guardada exitosamente');
    } catch (\Exception $e) {
        // Maneja cualquier excepción que ocurra durante el proceso y registra el error
        return redirect()
            ->route('coordinador.agregarEmpresa')
            ->with('error', 'Ocurrió un error al guardar la empresa: ' . $e->getMessage());
    }
}





////eliminar empresa

public function eliminarEmpresa($id)
{
    $empresa = Empresa::find($id);

    if (!$empresa) {
        return redirect()->back()->with('error', 'Empresa no encontrada.');
    }

    $empresa->delete();

    return redirect()->route('coordinador.agregarEmpresa')->with('success', 'Empresa eliminada exitosamente.');
}
/////////editar empresa//////////////
public function editarEmpresa($id)
{
    $empresa = Empresa::find($id);

    if (!$empresa) {
        return redirect()->route('coordinador.agregarEmpresa')->with('error', 'Empresa no encontrada.');
    }

    return view('coordinador.editarEmpresa', compact('empresa'));
}

public function actualizarEmpresa(Request $request, $id)
{
    try {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            return redirect()->route('coordinador.agregarEmpresa')->with('error', 'Empresa no encontrada.');
        }

        // Valida los datos del formulario antes de actualizar
        $validatedData = $request->validate([
            'nombreEmpresa' => 'required|string|max:255',
            'rucEmpresa' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email',
            'nombreContacto' => 'required|string|max:255',
            'telefonoContacto' => 'required|string|max:255',
            'actividadesMacro' => 'required|string',
            'cuposDisponibles' => 'required|integer',
            'cartaCompromiso' => 'file',
            'convenio' => 'file',
        ]);

        // Actualiza los datos de la empresa con los nuevos valores validados
        $empresa->nombreEmpresa = $validatedData['nombreEmpresa'];
        $empresa->rucEmpresa = $validatedData['rucEmpresa'];
        $empresa->provincia = $validatedData['provincia'];
        $empresa->ciudad = $validatedData['ciudad'];
        $empresa->direccion = $validatedData['direccion'];
        $empresa->correo = $validatedData['correo'];
        $empresa->nombreContacto = $validatedData['nombreContacto'];
        $empresa->telefonoContacto = $validatedData['telefonoContacto'];
        $empresa->actividadesMacro = $validatedData['actividadesMacro'];
        $empresa->cuposDisponibles = $validatedData['cuposDisponibles'];


        // Maneja la actualización de la cartaCompromiso si se proporciona un nuevo archivo
        if ($request->hasFile('cartaCompromiso')) {
            $cartaCompromisoPath = $request->file('cartaCompromiso')->store('archivos');
            $empresa->cartaCompromiso = $cartaCompromisoPath;
        }

        // Maneja la actualización del convenio si se proporciona un nuevo archivo
        if ($request->hasFile('convenio')) {
            $convenioPath = $request->file('convenio')->store('archivos');
            $empresa->convenio = $convenioPath;
        }

        // Guarda los cambios en la empresa
        $empresa->save();

        // Redirige de vuelta a la página de agregar empresa con un mensaje de éxito
        return redirect()->route('coordinador.agregarEmpresa')->with('success', 'Empresa actualizada exitosamente');
    } catch (\Exception $e) {
        // Maneja cualquier excepción que ocurra durante el proceso y registra el error
        return redirect()
            ->route('coordinador.agregarEmpresa')
            ->with('error', 'Ocurrió un error al actualizar la empresa: ' . $e->getMessage());
    }
}









//ir a la vista de practica 1
public function aceptarFasei()
{
    $estudiantesConPracticaI = PracticaI::with('estudiante')
    ->where('Estado', 'PracticaI')
    ->get();

    $estudiantesConPracticaII = PracticaII::with('estudiante')
    ->where('Estado', 'PracticaII')
    ->get();


    $estudiantesPracticas = PracticaI::with('estudiante')
    ->where(function ($query) {
        $query->where('Estado', 'En ejecucion')
              ->orWhere('Estado', 'Terminado');
    })
    ->get();

    $estudiantesPracticasII = PracticaII::with('estudiante')
    ->where(function ($query) {
        $query->where('Estado', 'En ejecucion')
              ->orWhere('Estado', 'Terminado');
    })
    ->get();


    return view('coordinador.aceptarFaseI', compact('estudiantesConPracticaI', 'estudiantesPracticas', 'estudiantesConPracticaII', 'estudiantesPracticasII'));

}

public function actualizarEstadoEstudiante(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'nuevoEstado' => 'required|in:En ejecucion,Negado,Terminado',
    ]);


    $practica = PracticaI::where('EstudianteID', $id)->first();

    if (!$practica) {
        return redirect()->route('coordinador.aceptarFaseI')->with('error', 'Práctica no encontrada.');
    }

    // Actualiza el estado de la práctica
    $nuevoEstado = $request->input('nuevoEstado');
    $practica->Estado = $nuevoEstado;
    $practica->save();

    if ($nuevoEstado === 'En ejecucion') {
        return redirect()->route('coordinador.aceptarFaseI')->with('success', 'Práctica aprobada correctamente.');
    }

    // Si el nuevo estado es 'Negado', elimina la práctica
    if ($nuevoEstado === 'Negado') {
        $practica->delete();
        return redirect()->route('coordinador.index')->with('success', 'Práctica negada y eliminada correctamente.');
    }

    // Redirecciona de regreso con un mensaje de éxito
    return redirect()->route('coordinador.aceptarFaseI')->with('success', 'Estado de la práctica actualizado correctamente.');
}

public function actualizarEstadoEstudiante2(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'nuevoEstado' => 'required|in:En ejecucion,Negado,Terminado',
    ]);

    $practica = PracticaII::where('EstudianteID', $id)->first();

    if (!$practica) {
        return redirect()->route('coordinador.aceptarFaseI')->with('error', 'Práctica no encontrada.');
    }

    // Actualiza el estado de la práctica
    $nuevoEstado = $request->input('nuevoEstado');
    $practica->Estado = $nuevoEstado;
    $practica->save();

    if ($nuevoEstado === 'En ejecucion') {
        return redirect()->route('coordinador.aceptarFaseI')->with('success', 'Práctica II aprobada correctamente.');
    }

    if ($nuevoEstado === 'Negado') {
        $practica->delete();
        return redirect()->route('coordinador.index')->with('success', 'Práctica II negada y eliminada correctamente.');
    }

    return redirect()->route('coordinador.aceptarFaseI')->with('success', 'Estado de la Práctica II actualizado correctamente.');
}




///////////////Descargar evidencias//////////////////////
public function descargarEvidencias($ProyectoID)
{
    $proyecto = Proyecto::find($ProyectoID);

    if (!$proyecto) {
        return back()->with('error', 'Proyecto no encontrado');
    }

    $estudiantesAsignados = $proyecto->estudiantes;

    if ($estudiantesAsignados->isEmpty()) {
        return back()->with('error', 'No se encontraron estudiantes asignados a este proyecto');
    }

    // Crear un archivo ZIP para las evidencias
    $zip = new ZipArchive();
    $zipFile = storage_path('app/public/evidencias/' . $proyecto->NombreProyecto . '.zip');

    if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
        foreach ($estudiantesAsignados as $estudiante) {
            $actividades = $estudiante->actividades;

            foreach ($actividades as $actividad) {
                $evidencia = storage_path('app/' . $actividad->evidencias);

                if (file_exists($evidencia)) {
                    $nombreArchivo = basename($evidencia);
                    $zip->addFile($evidencia, $nombreArchivo);
                }
            }
        }
        $zip->close();
    }

    if (file_exists($zipFile)) {
        // Configura el nombre del archivo ZIP para la descarga
        $nombreArchivoDescarga = $proyecto->NombreProyecto .'_'. $proyecto->FechaInicio. '_'. $proyecto->NombreProfesor.'.zip';

        return response()->download($zipFile, $nombreArchivoDescarga, [
            'Content-Disposition' => "attachment; filename=$nombreArchivoDescarga",
        ]);
    } else {
        return back()->with('error', 'No se pudieron crear las evidencias');
    }
}

//////editar empresa del estudiante
public function editarNombreEmpresa($id)
{
    $estudiante = Estudiante::find($id);
    $empresas = Empresa::all();



    return view('coordinador.editarNombreEmpresa', compact('estudiante', 'empresas'));
}


/////////////////////////actualizar la empresa del estudiante
public function actualizarNombreEmpresa(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'nuevoNombreEmpresa' => 'required|string|max:255',
    ]);

    $practicaI = PracticaI::where('EstudianteID', $id)->first();
    $practicaII = PracticaII::where('EstudianteID', $id)->first();

    if (!$practicaI && !$practicaII) {
        return redirect()->route('coordinador.aceptarFaseI')->with('error', 'Práctica no encontrada.');
    }

    $nuevoNombreEmpresa = $request->input('nuevoNombreEmpresa');

    if ($practicaI) {
        // Actualiza el valor "Empresa" en la tabla PracticaI
        $practicaI->Empresa = $nuevoNombreEmpresa;
        $practicaI->save();
    }

    if ($practicaII) {
        // Actualiza el valor "Empresa" en la tabla PracticaII
        $practicaII->Empresa = $nuevoNombreEmpresa;
        $practicaII->save();
    }

    return redirect()->route('coordinador.aceptarFaseI')->with('success', 'Empresa actualizado correctamente.');
}









}
