<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\Models\Periodo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\AsignacionProyecto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\PracticaI;
use App\Models\PracticaII;
use Illuminate\Database\QueryException;
use Intervention\Image\Facades\Image;
use App\Models\ActividadEstudiante;
use App\Models\Usuario;



class EstudianteController extends Controller
{
    public function create()
    {
        $cohortes = Cohorte::all();
        $periodos = Periodo::all();
        // Verifica si el usuario autenticado es un estudiante y ya tiene información registrada
        if (Auth::check() && Auth::user()->estudiante) {
            // Si es un estudiante con información registrada, redirige a la página index
            return redirect()->route('estudiantes.index');
        }


        // Si no es un estudiante o no tiene información registrada, muestra la página create
        return view('estudiantes.create', compact('cohortes', 'periodos'));
    }

    public function store(Request $request)
    {
        // Valida los datos del formulario antes de intentar crear el estudiante
        $validatedData = $request->validate([
            'Nombres' => 'required|string|max:255',
            'Apellidos' => 'required|string|max:255',
            'espe_id' => 'required|string|max:255',
            'celular' => 'required|string|max:10',
            'cedula' => 'required|string|max:11',
            'Cohorte' => 'required|string|max:10',
            'Periodo' => 'required|string|max:20',
            'Carrera' => 'required|string|max:255',
            'Provincia' => 'required|string|max:255',
            'Departamento' => 'required|string|max:255',
        ]);

        // Obtén el UserID del usuario autenticado
        $userId = Auth::id();
        $CorreoElectronico = Auth::user()->CorreoElectronico;

        $fechaActual = now();
        $periodoSeleccionado = $validatedData['Periodo'];
        $periodo = Periodo::where('Periodo', $periodoSeleccionado)->first();
        $fechaFinPeriodo = $periodo->PeriodoFin;


        // Comprueba si la fecha actual es mayor que la fecha de finalización del período
        if ($fechaActual > $fechaFinPeriodo) {
            return redirect()->route('estudiantes.create')->with('error', 'No puedes registrar un estudiante en un período académico que ya ha finalizado.');
        }

        // Crea un nuevo registro de Estudiante y asocia el UserID
        Estudiante::create([
            'UserID' => $userId,
            'Nombres' => $validatedData['Nombres'],
            'Apellidos' => $validatedData['Apellidos'],
            'espe_id' => $validatedData['espe_id'],
            'celular' => $validatedData['celular'],
            'cedula' => $validatedData['cedula'],
            'Cohorte' => $validatedData['Cohorte'],
            'Periodo' => $validatedData['Periodo'],
            'Carrera' => $validatedData['Carrera'],
            'Correo' =>$CorreoElectronico,
            'Provincia' => $validatedData['Provincia'],
            'Departamento' => $validatedData['Departamento'],
            'Estado' => 'En proceso de revision'
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Datos del Estudiante ingresados correctamente');
    }


// Controlador Estudiante

public function index()
{
    // Verifica si el usuario está autenticado
    if (Auth::check()) {
        // Obtén el usuario autenticado
        $user = Auth::user();

        // Verifica si el usuario autenticado es un estudiante y tiene información registrada
        if ($user->estudiante) {
            // Obtén los datos del estudiante relacionado con el usuario
            $estudiante = $user->estudiante;

            // Obtén la asignación de proyecto del estudiante (si existe)
            $asignacionProyecto = AsignacionProyecto::where('EstudianteID', $estudiante->EstudianteID)->first();

            return view('estudiantes.index', compact('estudiante', 'asignacionProyecto'));
        }
    }

    // Si el usuario no es un estudiante o no tiene información registrada, puedes redirigirlo a una página de error o mostrar un mensaje adecuado.
    return view('error'); // Puedes personalizar esto según tus necesidades.
}








    //////editar estudiante
    public function edit(Estudiante $estudiante)
    {
        // Lógica para mostrar el formulario de edición
        return view('estudiantes.edit', compact('estudiante'));
    }


    public function update(Request $request, Estudiante $estudiante)
    {
        // Valida los datos del formulario antes de actualizar el estudiante
        $validatedData = $request->validate([
            'Nombres' => 'required|string|max:255',
            'Apellidos' => 'required|string|max:255',
            'espe_id' => 'required|string|max:255',
            'celular' => 'required|string|max:10',
            'cedula' => 'required|string|max:11',
            'Cohorte' => 'required|string|max:10',
            'Carrera' => 'required|string|max:255',
            'Provincia' => 'required|string|max:255',
            'Departamento' => 'required|string|max:255',
        ]);

        // Actualiza los campos del estudiante con los datos validados
        $estudiante->update([
            'Nombres' => $validatedData['Nombres'],
            'Apellidos' => $validatedData['Apellidos'],
            'espe_id' => $validatedData['espe_id'],
            'celular' => $validatedData['celular'],
            'cedula' => $validatedData['cedula'],
            'Cohorte' => $validatedData['Cohorte'],
            'Carrera' => $validatedData['Carrera'],
            'Provincia' => $validatedData['Provincia'],
            'Departamento' => $validatedData['Departamento'],
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Información del Estudiante actualizada correctamente');
    }


    /////renviar informacion para aceptacion
    public function resend(Request $request, Estudiante $estudiante)
{
    // Verificar si el estado actual es "Negado"
    if ($estudiante->Estado === 'Negado') {
        // Actualizar el estado a "En proceso de revisión"
        $estudiante->update([
            'Estado' => 'En proceso de revision',
        ]);

        // Redirigir al estudiante a la página de información con un mensaje de éxito
        return redirect()->route('estudiantes.index', ['estudiante' => $estudiante->EstudianteID])->with('success', 'Información reenviada con éxito');
    } else {
        // Si el estado no es "Negado", mostrar un mensaje de error
        return redirect()->route('estudiantes.index', ['estudiante' => $estudiante->EstudianteID])->with('error', 'No puede renviar la informacion. Usted ya tiene un proceso de verificacion en curso.');
    }
}



///vista a practica1.blade.php
public function practica1()
{
    $user = Auth::user();
    $estudiante = $user->estudiante;

    // Verifica si el usuario autenticado es un estudiante y su estado es "Aprobado-practicas"
    if ($estudiante && $estudiante->Estado === 'Aprobado-practicas') {
        $correoEstudiante = $estudiante->Usuario->CorreoElectronico;
        $empresas = Empresa::all();
        $practicaPendiente = PracticaI::where('EstudianteID', $estudiante->EstudianteID)->where('Estado', 'En ejecucion')->first();

        $estadoPractica = PracticaI::where('EstudianteID', $estudiante->EstudianteID)->where('Estado', 'Terminado')->first();

        if ($estadoPractica) {
            // Si el estado de la práctica es "Terminado", redirige a la vista de practicaII
            return redirect()->route('estudiantes.practica2');
        }

        return view('estudiantes.practica1', compact('estudiante', 'correoEstudiante', 'empresas', 'practicaPendiente'));
    }

    // Si no cumple con los requisitos, muestra un mensaje de alerta y redirige a otra página
    return redirect()->route('estudiantes.index')->with('error', 'No tiene acceso a esta página.');
}



public function practica2()
{
    $user = Auth::user();
    $estudiante = $user->estudiante;

    if ($estudiante && $estudiante->Estado === 'Aprobado-practicas') {
        $correoEstudiante = $estudiante->Usuario->CorreoElectronico;
        $empresas = Empresa::all();

        // Consulta para PracticaI
        $practicaPendienteI = PracticaI::where('EstudianteID', $estudiante->EstudianteID)->where('Estado', 'Terminado')->first();
        $estadoPracticaI = PracticaI::where('EstudianteID', $estudiante->EstudianteID)->where('Estado', 'Terminado')->first();
        $horasPlanificadasI = $practicaPendienteI ? $practicaPendienteI->HorasPlanificadas : 0;

        // Consulta para PracticaII
        $practicaPendiente = PracticaII::where('EstudianteID', $estudiante->EstudianteID)->where('Estado', 'En ejecucion')->first();
        $estadoPractica = PracticaII::where('EstudianteID', $estudiante->EstudianteID)->where('Estado', 'Terminado')->first();

        return view('estudiantes.practica2', compact('estudiante', 'correoEstudiante', 'empresas', 'horasPlanificadasI', 'practicaPendiente', 'estadoPractica'));
    }

    return redirect()->route('estudiantes.index')->with('error', 'No tiene acceso a esta página.');
}




///////guardar practicas
public function guardarPracticas(Request $request)
{
    // Valida los datos del formulario antes de intentar crear la práctica
    $validatedData = $request->validate([
        'Nivel' => 'required|string|max:255',
        'Practicas' => 'required|string|max:255',
        'DocenteTutor' => 'required|string|max:255',
        'Empresa' => 'required|string|max:255',
        'CedulaTutorEmpresarial' => 'required|string|max:255',
        'NombreTutorEmpresarial' => 'required|string|max:255',
        'Funcion' => 'required|string|max:255',
        'TelefonoTutorEmpresarial' => 'required|string|max:10',
        'EmailTutorEmpresarial' => 'required|string|email|max:255',
        'DepartamentoTutorEmpresarial' => 'required|string|max:255',
        'EstadoAcademico' => 'required|string|max:255',
        'FechaInicio' => 'required|date',
        'FechaFinalizacion' => 'required|date',
        'HorasPlanificadas' => 'required|string|max:255',
        'HoraEntrada' => 'required|string|max:255',
        'HoraSalida' => 'required|string|max:255',
        'AreaConocimiento' => 'required|string|max:255',

    ]);

    // Obtén el UserID del usuario autenticado
    $userId = Auth::id();

    // Obtén el modelo Estudiante del usuario autenticado
    $estudiante = Estudiante::where('UserID', $userId)->first();

    // Verifica si se encontró el estudiante
    if ($estudiante) {
        // Crea un nuevo registro de PracticaI y asocia los datos del estudiante
        PracticaI::create([
            'EstudianteID' => $estudiante->EstudianteID,
            'NombreEstudiante' => $estudiante->Nombres,
            'ApellidoEstudiante' => $estudiante->Apellidos,
            'Departamento' => $estudiante->Departamento,
            'Nivel' => $validatedData['Nivel'],
            'Practicas' => $validatedData['Practicas'],
            'DocenteTutor' => $validatedData['DocenteTutor'],
            'Empresa' => $validatedData['Empresa'],
            'CedulaTutorEmpresarial' => $validatedData['CedulaTutorEmpresarial'],
            'NombreTutorEmpresarial' => $validatedData['NombreTutorEmpresarial'],
            'Funcion' => $validatedData['Funcion'],
            'TelefonoTutorEmpresarial' => $validatedData['TelefonoTutorEmpresarial'],
            'EmailTutorEmpresarial' => $validatedData['EmailTutorEmpresarial'],
            'DepartamentoTutorEmpresarial' => $validatedData['DepartamentoTutorEmpresarial'],
            'EstadoAcademico' => $validatedData['EstadoAcademico'],
            'FechaInicio' => $validatedData['FechaInicio'],
            'FechaFinalizacion' => $validatedData['FechaFinalizacion'],
            'HorasPlanificadas' => $validatedData['HorasPlanificadas'],
            'HoraEntrada' => $validatedData['HoraEntrada'],
            'HoraSalida' => $validatedData['HoraSalida'],
            'AreaConocimiento' => $validatedData['AreaConocimiento'],
            'Estado' => 'PracticaI'
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Práctica guardada exitosamente');
    }

    // Manejo de error si no se encuentra el estudiante
    return redirect()->route('estudiantes.index')->with('error', 'No se encontró información del estudiante.');
}

///////guardar practicas2////////
public function guardarPracticas2(Request $request)
{
    // Valida los datos del formulario antes de intentar crear la práctica
    $validatedData = $request->validate([
        'Nivel' => 'required|string|max:255',
        'Practicas' => 'required|string|max:255',
        'DocenteTutor' => 'required|string|max:255',
        'Empresa' => 'required|string|max:255',
        'CedulaTutorEmpresarial' => 'required|string|max:255',
        'NombreTutorEmpresarial' => 'required|string|max:255',
        'Funcion' => 'required|string|max:255',
        'TelefonoTutorEmpresarial' => 'required|string|max:10',
        'EmailTutorEmpresarial' => 'required|string|email|max:255',
        'DepartamentoTutorEmpresarial' => 'required|string|max:255',
        'EstadoAcademico' => 'required|string|max:255',
        'FechaInicio' => 'required|date',
        'FechaFinalizacion' => 'required|date',
        'HorasPlanificadas' => 'required|string|max:255',
        'HoraEntrada' => 'required|string|max:255',
        'HoraSalida' => 'required|string|max:255',
        'AreaConocimiento' => 'required|string|max:255',

    ]);

    // Obtén el UserID del usuario autenticado
    $userId = Auth::id();

    // Obtén el modelo Estudiante del usuario autenticado
    $estudiante = Estudiante::where('UserID', $userId)->first();

    // Verifica si se encontró el estudiante
    if ($estudiante) {
        // Crea un nuevo registro de PracticaI y asocia los datos del estudiante
        PracticaII::create([
            'EstudianteID' => $estudiante->EstudianteID,
            'NombreEstudiante' => $estudiante->Nombres,
            'ApellidoEstudiante' => $estudiante->Apellidos,
            'Departamento' => $estudiante->Departamento,
            'Nivel' => $validatedData['Nivel'],
            'Practicas' => $validatedData['Practicas'],
            'DocenteTutor' => $validatedData['DocenteTutor'],
            'Empresa' => $validatedData['Empresa'],
            'CedulaTutorEmpresarial' => $validatedData['CedulaTutorEmpresarial'],
            'NombreTutorEmpresarial' => $validatedData['NombreTutorEmpresarial'],
            'Funcion' => $validatedData['Funcion'],
            'TelefonoTutorEmpresarial' => $validatedData['TelefonoTutorEmpresarial'],
            'EmailTutorEmpresarial' => $validatedData['EmailTutorEmpresarial'],
            'DepartamentoTutorEmpresarial' => $validatedData['DepartamentoTutorEmpresarial'],
            'EstadoAcademico' => $validatedData['EstadoAcademico'],
            'FechaInicio' => $validatedData['FechaInicio'],
            'FechaFinalizacion' => $validatedData['FechaFinalizacion'],
            'HorasPlanificadas' => $validatedData['HorasPlanificadas'],
            'HoraEntrada' => $validatedData['HoraEntrada'],
            'HoraSalida' => $validatedData['HoraSalida'],
            'AreaConocimiento' => $validatedData['AreaConocimiento'],
            'Estado' => 'PracticaII'
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Práctica guardada exitosamente');
    }

    // Manejo de error si no se encuentra el estudiante
    return redirect()->route('estudiantes.index')->with('error', 'No se encontró información del estudiante.');
}


public function guardarActividad(Request $request)
{

    $request->validate([
        'fecha' => 'required|date',
        'actividades' => 'required|string',
        'horas' => 'required|integer',
        'evidencias' => 'required|file|mimes:jpeg,jpg,png|max:500000', // Aumenta el límite de tamaño
        'nombre_actividad' => 'required|string',
    ]);

    // Procesar el archivo de evidencias
    if ($request->hasFile('evidencias')) {
        $evidencia = $request->file('evidencias');

        // Comprimir la imagen antes de guardarla
        $imagenComprimida = Image::make($evidencia->getRealPath())
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $rutaImagen = 'public/evidencias/' . uniqid() . '.' . $evidencia->getClientOriginalExtension();

        Storage::put($rutaImagen, $imagenComprimida->stream(), 'public');

        // Crear una nueva instancia de ActividadEstudiante con los datos del formulario
        $actividadEstudiante = new ActividadEstudiante([
            'EstudianteID' => Auth::user()->estudiante->EstudianteID,
            'fecha' => $request->input('fecha'),
            'actividades' => $request->input('actividades'),
            'numero_horas' => $request->input('horas'),
            'evidencias' => $rutaImagen, // Almacena la ruta del archivo en la base de datos
            'nombre_actividad' => $request->input('nombre_actividad'),
        ]);

        try {
            // Guardar la actividad en la base de datos
            $actividadEstudiante->save();

            return redirect()->route('estudiantes.documentos')->with('success', 'Actividad registrada exitosamente.');
        } catch (\Exception $e) {
            // Manejar el error, por ejemplo, registrándolo o devolviendo una respuesta con el mensaje de error
            return redirect()->back()->with('error', 'Error al guardar la actividad: ' . $e->getMessage());
        }
    } else {
        return redirect()->back()->with('error', 'Verifica el ingreso de los datos en la Actividad.');
    }

}

        public function configuracion()
        {
            return view('estudiantes.configuracion');
        }
        public function actualizarConfiguracion(Request $request,$id)
        {
            // Obtener el ID del usuario autenticado
            $id = Auth::user()->UserID;


            // Validar los datos del formulario
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'contrasena' => 'required|string|min:6',
            ]);

            // Buscar al usuario por su ID
            $user = Usuario::find($id);


            // Actualizar los datos del usuario
            $user->update([
                'Nombre' => $request->nombre,
                'Apellido' => $request->apellido,
                'Contrasena' => bcrypt($request->contrasena),
            ]);

            return redirect()->route('estudiantes.index')->with('success', 'Perfil actualizado con éxito');
        }


        ////////certificado de matricula estudiante///////////////////

        public function certificadoMatricula()
{
    // Obtén al estudiante actualmente autenticado a través de la relación definida en el modelo Usuario
    $estudiante = Auth::user()->estudiante;

    if ($estudiante) {
        // Crea una vista para el certificado de matrícula y pasa los datos del estudiante
        $pdf = PDF::loadView('estudiantes.certificadoMatricula', compact('estudiante'));

        // Genera y descarga el PDF
        return $pdf->download('certificadoMatricula.pdf');
    } else {
        // Maneja la situación en la que no se encuentra el estudiante
        return redirect()->back()->with('error', 'No se pudo encontrar al estudiante.');
    }
}

        










    }