<?php

namespace App\Http\Controllers;
use App\Models\PracticaI;
use App\Models\PracticaII;
use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\Proyecto;
use App\Mail\EstudianteAprobado;
use App\Mail\EstudianteNegado;
use Illuminate\Pagination\Paginator;
use App\Models\Cohorte;
use App\Models\AsignacionProyecto;
use App\Models\Empresa;

use App\Models\Periodo;
use App\Models\DirectorVinculacion;
use App\Models\ParticipanteVincunlacion;
use App\Models\ProfesUniversidad;
use Illuminate\Support\Facades\Mail;
use App\Models\estudiantesvinculacion;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function boot()
    {
        Paginator::useBootstrap();
    }

    public function index()
    {
        //////visualizar ProfesUniversidad
        $profesores = ProfesUniversidad::paginate(10);
        $periodos = Periodo::all();
        $cohortes = Cohorte::all();

        // Obtén la lista de profesores pendientes
        $profesoresPendientes = Usuario::where('TipoUsuario', 'Profesor')->where('Estado', 'Pendiente')->get();

        // Obtén la lista de profesores con permisos concedidos
        $profesoresConPermisos = Usuario::where('TipoUsuario', 'Profesor')->whereIn('Estado', ['Vinculacion', 'Lector', 'Director-Departamento'])->get();

        return view('admin.index', [
            'profesoresPendientes' => $profesoresPendientes,
            'profesoresConPermisos' => $profesoresConPermisos,
            'profesores' => $profesores,
            'periodos' => $periodos,
            'cohortes' => $cohortes,
        ]);
    }

////actualizar permisos
public function updateEstado(Request $request, $id)
{
    // Validar el nuevo estado
    $request->validate([
        'nuevoEstado' => 'required|in:Vinculacion,Director-Departamento,Director-Carrera,Negado',
    ]);

    // Actualizar el estado del profesor
    $profesor = Usuario::find($id);
    $profesor->Estado = $request->nuevoEstado;
    $profesor->save();

    // Si el estado es "Negado", eliminar al profesor de la base de datos
    if ($request->nuevoEstado === 'Negado') {
        $profesor->delete();
    }

    return redirect()->route('admin.index')->with('success', 'Estado actualizado correctamente');
}




////eliminar profesor
public function deleteProfesor(Request $request, $id)
{
    // Buscar al profesor por su ID
    $profesor = Usuario::find($id);

    if ($profesor) {
        // Eliminar al profesor de la base de datos
        $profesor->delete();
    }

    return redirect()->route('admin.index')->with('success', 'Profesor eliminado correctamente');
}


///borrar los permisos concedidos
public function deletePermission(Request $request, $id)
{
    // Busca al usuario por su ID
    $usuario = Usuario::find($id);

    if (!$usuario) {
        // El usuario no existe, puedes manejar este caso como desees
        return redirect()->route('admin.index')->with('error', 'Usuario no encontrado');
    }

    // Verifica si el usuario tiene un estado que permite eliminar el permiso
    if (in_array($usuario->Estado, ['Vinculacion', 'Director-Departamento', 'Director-Carrera'])) {
        // Cambia el estado del usuario a 'Negado'
        $usuario->Estado = 'Pendiente';
        $usuario->save();

        return redirect()->route('admin.index')->with('success', 'Permiso eliminado correctamente');
    } elseif ($usuario->Estado === 'Pendiente') {
        // Si el estado ya es 'Negado', elimina el usuario
        $usuario->delete();

        return redirect()->route('admin.index')->with('success', 'Usuario eliminado correctamente');
    } else {
        return redirect()->route('admin.index')->with('error', 'No se puede eliminar el permiso de este usuario');
    }
}




///////////////Aceptacion de estudiantes para el proceso de vinculacion/////////////////////////////////////
public function estudiantes(Request $request)
{
    $elementosPorPagina = $request->input('elementosPorPagina');
    $elementosPorPaginaAprobados = $request->input('elementosPorPaginaAprobados'); // Cambio de nombre

    // Consulta para estudiantes en revisión
    $queryEstudiantesEnRevision = Estudiante::where('Estado', 'En proceso de revisión');

    // Búsqueda de estudiantes en revisión
    if ($request->has('buscarEstudiantesEnRevision')) {
        $busquedaEstudiantesEnRevision = $request->input('buscarEstudiantesEnRevision');
        $queryEstudiantesEnRevision->where(function ($query) use ($busquedaEstudiantesEnRevision) {
            $query->where('Nombres', 'like', '%' . $busquedaEstudiantesEnRevision . '%')
                ->orWhere('Apellidos', 'like', '%' . $busquedaEstudiantesEnRevision . '%');
        });
    }

    $estudiantesEnRevision = $queryEstudiantesEnRevision->get();

    // Consulta para estudiantes de vinculación
    $queryEstudiantesVinculacion = estudiantesvinculacion::orderBy('nombres', 'asc');

    // Búsqueda de estudiantes de vinculación
    if ($request->has('buscarEstudiantes')) {
        $busquedaEstudiantesVinculacion = $request->input('buscarEstudiantes');
        $queryEstudiantesVinculacion->where(function ($query) use ($busquedaEstudiantesVinculacion) {
            $query->where('cedula_identidad', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('correo_electronico', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('espe_id', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('nombres', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('periodo_ingreso', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('periodo_vinculacion', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('actividades_macro', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('docente_participante', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('fecha_inicio', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('fecha_fin', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('total_horas', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('director_proyecto', 'like', '%' . $busquedaEstudiantesVinculacion . '%')
                ->orWhere('nombre_proyecto', 'like', '%' . $busquedaEstudiantesVinculacion . '%');
        });
    }

    $estudiantesVinculacion = $queryEstudiantesVinculacion->paginate($elementosPorPagina);

    // Consulta y paginación para estudiantes aprobados
    $queryEstudiantesAprobados = Estudiante::whereIn('Estado', ['Aprobado', 'Aprobado-prácticas']);

    // Búsqueda de estudiantes aprobados
    if ($request->has('buscarEstudiantesAprobados')) {
        $busquedaEstudiantesAprobados = $request->input('buscarEstudiantesAprobados');
        $queryEstudiantesAprobados->where(function ($query) use ($busquedaEstudiantesAprobados) {
            $query->where('Nombres', 'like', '%' . $busquedaEstudiantesAprobados . '%')
                ->orWhere('Apellidos', 'like', '%' . $busquedaEstudiantesAprobados . '%');
        });
    }

    $estudiantesAprobados = $queryEstudiantesAprobados->paginate($elementosPorPaginaAprobados); // Cambio de nombre

    return view('admin.aceptacionEstudiantes', [
        'estudiantesEnRevision' => $estudiantesEnRevision,
        'estudiantesAprobados' => $estudiantesAprobados,
        'estudiantesVinculacion' => $estudiantesVinculacion,
        'elementosPorPagina' => $elementosPorPagina,
        'elementosPorPaginaAprobados' => $elementosPorPaginaAprobados, // Cambio de nombre
    ]);
}




// Actualizar el estado de un estudiante
public function updateEstudiante(Request $request, $id)
{
     $request->validate([
        'nuevoEstado' => 'required|in:Aprobado,Negado',
    ]);

    $estudiante = Estudiante::find($id);

    if (!$estudiante) {
        return redirect()->route('admin.estudiantes')->with('error', 'El estudiante no existe.');
    }

    $nuevoEstado = $request->input('nuevoEstado');
    $estudiante->Estado = $nuevoEstado;

    $nuevoComentario = $request->input('nuevoComentario');
    $estudiante->Comentario = $nuevoComentario;

    $estudiante->save();

    // Si el estado cambió a "Aprobado," envía un correo electrónico al estudiante
    if ($nuevoEstado === 'Aprobado') {
        // Obtén el modelo Usuario asociado al estudiante
        $usuario = $estudiante->usuario;

        if ($usuario) {
            // Accede al correo electrónico desde el modelo Usuario
            $email = $usuario->CorreoElectronico;

            // Verifica si el correo electrónico es válido antes de enviar el correo
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email)->send(new EstudianteAprobado($estudiante));
            } else {
                // Lógica para manejar el caso en que el correo electrónico no sea válido
            }
        }
    }elseif ($nuevoEstado === 'Negado') {
        // Obtén el modelo Usuario asociado al estudiante
        $usuario = $estudiante->usuario;

        if ($usuario) {
            // Accede al correo electrónico desde el modelo Usuario
            $email = $usuario->CorreoElectronico;

            // Verifica si el correo electrónico es válido antes de enviar el correo
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email)->send(new EstudianteNegado($estudiante));
            } else {

            }
        }
    }

    return redirect()->route('admin.estudiantes')->with('success', 'Estado del estudiante actualizado correctamente.');
}






/////////////////////////////visualizar proyectos
public function indexProyectos()
{
    $proyectos = Proyecto::all(); 
    $proyectosDisponibles = Proyecto::all();
    $estudiantesAprobados = Estudiante::where('Estado', 'Aprobado')
    ->whereNotIn('EstudianteID', AsignacionProyecto::pluck('EstudianteID')->toArray())
    ->get();

    return view('admin.indexProyectos', [
        'proyectos' => $proyectos,
        'proyectosDisponibles' => $proyectosDisponibles,
        'estudiantesAprobados' => $estudiantesAprobados,
    ]);
}

///////////////////////Vista para crear los proyectos

public function crearProyectoForm()
{
    $profesores = ProfesUniversidad::all();
    return view('admin.agregarProyecto', compact('profesores'));
}

///////////////////////guardar proyectos

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
             return redirect()->route('admin.indexProyectos')->with('error', 'No se puede crear el proyecto. Uno de los profesores ya está asignado a otro proyecto en ejecución.');
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

        return redirect()->route('admin.indexProyectos')->with('success', 'Proyecto agregado correctamente');
    }
///////////////editar proyecto
public function editProyectoForm($ProyectoID)
{
    $proyecto = Proyecto::findOrFail($ProyectoID);
    return view('admin.editarProyecto', compact('proyecto'));
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

    return redirect()->route('admin.indexProyectos')->with('success', 'Proyecto actualizado correctamente');
}
/////eliminar proyecto
public function deleteProyecto($ProyectoID)
{
    // Buscar el proyecto por ID
    $proyecto = Proyecto::findOrFail($ProyectoID);

    // Verificar si el Estado del proyecto es "Ejecucion"
    if ($proyecto->Estado === 'Ejecucion') {
        return redirect()->route('admin.indexProyectos')->with('error', 'No puedes eliminar un proyecto en estado de ejecución');
    }

    // Obtener todas las asignaciones relacionadas con el proyecto
    $asignaciones = AsignacionProyecto::where('ProyectoID', $ProyectoID)->get();

    // Eliminar cada asignación relacionada con el proyecto
    foreach ($asignaciones as $asignacion) {
        $asignacion->delete();
    }

    // Eliminar el proyecto
    $proyecto->delete();

    return redirect()->route('admin.indexProyectos')->with('success', 'Proyecto y asignaciones relacionadas eliminados correctamente');
}
///////asignar proyecto a estudiante/////////////
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







/////guardar maestros
public function guardarMaestro(Request $request)
{
    try {
        // Validar los datos del formulario
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'cedula' => 'required|string|min:10',
            'departamento' => 'required|string',
        ]);

        // Crea un nuevo maestro y guárdalo en la base de datos
        ProfesUniversidad::create([
            'Nombres' => $request->nombres,
            'Apellidos' => $request->apellidos,
            'Correo' => $request->correo,
            'Cedula' => $request->cedula,
            'Departamento' => $request->departamento,
        ]);

        return redirect()->route('admin.index')->with('success', 'Docente creado con éxito');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'No se pudo crear el Docente. Por favor, verifica los datos e intenta de nuevo.');
    }
}



public function eliminarMaestro(Request $request, $id)
{
    try {
        $maestro = ProfesUniversidad::find($id);

        if (!$maestro) {
            return redirect()->route('admin.index')->with('error', 'Maestro no encontrado.');
        }

        // Verificar si el maestro está relacionado con algún proyecto a través de su correo o el campo 'CorreoProfeAsignado' de los proyectos
        $proyectosRelacionados = Proyecto::where(function ($query) use ($maestro) {
            $query->where('CorreoElectronicoTutor', $maestro->Correo)
                ->orWhere('CorreoProfeAsignado', $maestro->Correo);
        })->get();

        if ($proyectosRelacionados->count() > 0) {
            // El maestro está relacionado con proyectos, agrega una variable de sesión
            session(['maestro_con_proyectos' => true]);
            return redirect()->route('admin.index')->with('warning', 'El maestro tiene proyectos asignados. No se puede eliminar.');
        }

        // El maestro no está relacionado con ningún proyecto, se puede eliminar
        $maestro->delete();

        return redirect()->route('admin.index')->with('success', 'Maestro eliminado con éxito.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'No se pudo eliminar el maestro. Por favor, verifica los datos e intenta de nuevo.');
    }
}
///////editar maestro/////////////////
public function editarDocente($id)
{

  $maestro = ProfesUniversidad::find($id);

    return view('admin.editarDocente', compact('maestro'));
}

public function actualizarMaestro(Request $request, $id)
{
    try {
        // Validar los datos de edición
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'cedula' => 'required|string|min:10',
            'departamento' => 'required|string',
        ]);

        // Encontrar el maestro que se va a editar
        $maestro = ProfesUniversidad::find($id);

        if (!$maestro) {
            return redirect()->route('admin.index')->with('error', 'Maestro no encontrado.');
        }

        // Actualizar los datos del maestro en la base de datos
        $maestro->update([
            'Nombres' => $request->nombres,
            'Apellidos' => $request->apellidos,
            'Correo' => $request->correo,
            'Cedula' => $request->cedula,
            'Departamento' => $request->departamento,
        ]);

        return redirect()->route('admin.index')->with('success', 'Maestro actualizado con éxito.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'No se pudo actualizar el maestro. Por favor, verifica los datos e intenta de nuevo.');
    }
}






 ////guardar cohorte
 public function guardarCohorte(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'cohorte' => 'required|string|unique:cohorte,Cohorte|max:6',
    ]);

    // Crear una nueva instancia del modelo Cohorte y asignar los valores
    $cohorte = new Cohorte;
    $cohorte->Cohorte = $request->cohorte;

    if ($cohorte->save()) {
        return redirect()->route('admin.index')->with('success', 'Cohorte guardada con éxito');
    } else {
        return redirect()->route('admin.index')->with('error', 'No se pudo crear el cohorte. Por favor, verifica los datos e intenta de nuevo.');
    }
}


 public function editarCohorte($id)
 {
     $cohorte = Cohorte::find($id);
     return view('admin.editarCohorte', compact('cohorte'));
 }
 
 public function actualizarCohorte(Request $request, $id)
{
    $request->validate([
        'cohorte' => 'required|string|max:6',
    ]);

    $cohorte = Cohorte::find($id);
    $cohorte->Cohorte = $request->cohorte;
    $cohorte->save();

    return redirect()->route('admin.index')->with('success', 'Cohorte actualizada con éxito');
}







 public function guardarPeriodo(Request $request)
 {
     $request->validate([
         'periodoInicio' => 'required|date',
         'periodoFin' => 'required|date|after:periodoInicio',
     ]);

     // Obtén las fechas del formulario
     $fechaInicio = \Carbon\Carbon::parse($request->periodoInicio);
     $fechaFin = \Carbon\Carbon::parse($request->periodoFin);

     // Formatea las fechas en el formato deseado (MESaño)
     $periodoInicio = strtoupper($fechaInicio->format('M')) . $fechaInicio->format('Y');
     $periodoFin = strtoupper($fechaFin->format('M')) . $fechaFin->format('y');

     // Combina las fechas para formar el período académico
     $periodoAcademico = $periodoInicio . '-' . $periodoFin;

     // Guarda el período académico en la base de datos
     Periodo::create([
         'Periodo' => $periodoAcademico,
         'PeriodoInicio' => $fechaInicio,
         'PeriodoFin' => $fechaFin,
     ]);

     return redirect()->route('admin.index')->with('success', 'Periodo académico creado con éxito.');
 }
 public function editarPeriodo($id)
 {
     // Buscar el período académico por su ID
     $periodo = Periodo::find($id);
 
     if (!$periodo) {
         return redirect()->route('admin.index')->with('error', 'El período académico no existe.');
     }
 
     return view('admin.editarPeriodo', compact('periodo'));
 }
 
 public function actualizarPeriodo(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'periodoInicio' => 'required|date',
        'periodoFin' => 'required|date|after:periodoInicio',
    ]);

    // Obtén las fechas del formulario
    $fechaInicio = \Carbon\Carbon::parse($request->periodoInicio);
    $fechaFin = \Carbon\Carbon::parse($request->periodoFin);

    // Formatea las fechas en el formato deseado (MESaño)
    $periodoInicio = strtoupper($fechaInicio->format('M')) . $fechaInicio->format('Y');
    $periodoFin = strtoupper($fechaFin->format('M')) . $fechaFin->format('y');

    // Combina las fechas para formar el período académico
    $periodoAcademico = $periodoInicio . '-' . $periodoFin;

    // Obtén el período académico por su ID
    $periodo = Periodo::find($id);

    if (!$periodo) {
        return redirect()->route('admin.index')->with('error', 'El período académico no existe.');
    }

    // Actualiza los datos del período
    $periodo->Periodo = $periodoAcademico; // Actualiza el nombre del período académico
    $periodo->PeriodoInicio = $fechaInicio; // Actualiza la fecha de inicio
    $periodo->PeriodoFin = $fechaFin; // Actualiza la fecha de fin
    $periodo->save();

    return redirect()->route('admin.index')->with('success', 'Período académico actualizado con éxito.');
}


 public function eliminarPeriodo(Request $request, $id){
        $periodo = Periodo::find($id);


        if (!$periodo) {
            return redirect()->route('admin.index')->with('error', 'Periodo académico no encontrado.');
        }

        $periodo->delete();

        return redirect()->route('admin.index')->with('success', 'Periodo académico eliminado con éxito.');
 }

 public function eliminarCohorte(Request $request, $id){
    $cohorte = Cohorte::find($id);


    if (!$cohorte) {
        return redirect()->route('admin.index')->with('error', 'Cohorte no encontrado.');
    }

    $cohorte->delete();

    return redirect()->route('admin.index')->with('success', 'Cohorte eliminado con éxito.');


}

//////guardar empresa////////////////
public function agregarEmpresa(Request $request)
{
    $elementosPorPagina = $request->input('elementosPorPagina');
    $empresas = Empresa::paginate($elementosPorPagina);

    return view('admin.agregarEmpresa', compact('empresas', 'elementosPorPagina'));
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
        return redirect()->route('admin.agregarEmpresa')->with('success', 'Empresa guardada exitosamente');
    } catch (\Exception $e) {
        // Maneja cualquier excepción que ocurra durante el proceso y registra el error
        return redirect()
            ->route('coordinador.agregarEmpresa')
            ->with('error', 'Ocurrió un error al guardar la empresa: ' . $e->getMessage());
    }
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

public function eliminarEmpresa($id)
{
    $empresa = Empresa::find($id);

    if (!$empresa) {
        return redirect()->back()->with('error', 'Empresa no encontrada.');
    }

    $empresa->delete();

    return redirect()->route('admin.agregarEmpresa')->with('success', 'Empresa eliminada exitosamente.');
}
///editar empresa///////////////////

public function editarEmpresa($id)
{
    $empresa = Empresa::find($id);

    if (!$empresa) {
        return redirect()->route('admin.agregarEmpresa')->with('error', 'Empresa no encontrada.');
    }

    return view('admin.editarEmpresa', compact('empresa'));
}

public function actualizarEmpresa(Request $request, $id)
{
    try {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            return redirect()->route('admin.agregarEmpresa')->with('error', 'Empresa no encontrada.');
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
        return redirect()->route('admin.agregarEmpresa')->with('success', 'Empresa actualizada exitosamente');
    } catch (\Exception $e) {
        // Maneja cualquier excepción que ocurra durante el proceso y registra el error
        return redirect()
            ->route('admin.agregarEmpresa')
            ->with('error', 'Ocurrió un error al actualizar la empresa: ' . $e->getMessage());
    }
}


//////////////////////////PRACTICAS////////////////////////////////////////
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


    return view('admin.aceptarFaseI', compact('estudiantesConPracticaI', 'estudiantesPracticas', 'estudiantesConPracticaII', 'estudiantesPracticasII'));

}

public function actualizarEstadoEstudiante(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'nuevoEstado' => 'required|in:En ejecucion,Negado,Terminado',
    ]);


    $practica = PracticaI::where('EstudianteID', $id)->first();

    if (!$practica) {
        return redirect()->route('admin.aceptarFaseI')->with('error', 'Práctica no encontrada.');
    }

    // Actualiza el estado de la práctica
    $nuevoEstado = $request->input('nuevoEstado');
    $practica->Estado = $nuevoEstado;
    $practica->save();

    if ($nuevoEstado === 'En ejecucion') {
        return redirect()->route('admin.aceptarFaseI')->with('success', 'Práctica aprobada correctamente.');
    }

    // Si el nuevo estado es 'Negado', elimina la práctica
    if ($nuevoEstado === 'Negado') {
        $practica->delete();
        return redirect()->route('admin.index')->with('success', 'Práctica negada y eliminada correctamente.');
    }

    // Redirecciona de regreso con un mensaje de éxito
    return redirect()->route('admin.aceptarFaseI')->with('success', 'Estado de la práctica actualizado correctamente.');
}

public function actualizarEstadoEstudiante2(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'nuevoEstado' => 'required|in:En ejecucion,Negado,Terminado',
    ]);

    $practica = PracticaII::where('EstudianteID', $id)->first();

    if (!$practica) {
        return redirect()->route('admin.aceptarFaseI')->with('error', 'Práctica no encontrada.');
    }

    // Actualiza el estado de la práctica
    $nuevoEstado = $request->input('nuevoEstado');
    $practica->Estado = $nuevoEstado;
    $practica->save();

    if ($nuevoEstado === 'En ejecucion') {
        return redirect()->route('admin.aceptarFaseI')->with('success', 'Práctica II aprobada correctamente.');
    }

    if ($nuevoEstado === 'Negado') {
        $practica->delete();
        return redirect()->route('admin.index')->with('success', 'Práctica II negada y eliminada correctamente.');
    }

    return redirect()->route('admin.aceptarFaseI')->with('success', 'Estado de la Práctica II actualizado correctamente.');
}

/////////////////////////////////EDITAR EMPRESA DEL ESTUDIANTE 
public function editarNombreEmpresa($id)
{
    $estudiante = Estudiante::find($id);
    $empresas = Empresa::all();



    return view('admin.editarNombreEmpresa', compact('estudiante', 'empresas'));
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
        return redirect()->route('admin.aceptarFaseI')->with('error', 'Práctica no encontrada.');
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

    return redirect()->route('admin.aceptarFaseI')->with('success', 'Empresa actualizado correctamente.');
}









}
