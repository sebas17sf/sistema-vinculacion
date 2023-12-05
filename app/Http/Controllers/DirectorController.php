<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\PracticaI;
use App\Models\PracticaII;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use App\Models\estudiantesvinculacion;

class DirectorController extends Controller
{
    public function mostrarEstudiantesAprobados(Request $request)
{
    $elementosPorPagina = $request->input('elementosPorPagina');
    $elementosPorPagina2 = $request->input('elementosPorPagina2');

    $queryEstudiantesVinculacion = estudiantesvinculacion::query();
    $queryEstudiantesVinculacion->orderBy('nombres', 'asc');



    $query = Estudiante::whereIn('Estado', ['Aprobado', 'Aprobado-practicas']);

    if ($request->filled('buscar')) {
        $busqueda = $request->input('buscar');
        $query->where(function ($query) use ($busqueda) {
            $query->where('Nombres', 'like', '%' . $busqueda . '%')
                ->orWhere('Apellidos', 'like', '%' . $busqueda . '%')
                ->orWhere('cedula_identidad', 'like', '%' . $busqueda . '%')
                ->orWhere('espe_id', 'like', '%' . $busqueda . '%')
                ->orWhere('Estado', 'like', '%' . $busqueda . '%')
                ->orWhere('Cohorte', 'like', '%' . $busqueda . '%');
        });
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

    $estudiantesAprobados = $query->paginate($elementosPorPagina);
    $estudiantesVinculacion = $queryEstudiantesVinculacion->paginate($elementosPorPagina2);

    // Organizar estudiantes en los arreglos según el departamento
    $estudiantesDCCO = [];
    $estudiantesDCEX = [];
    $estudiantesDCVA = [];

    foreach ($estudiantesAprobados as $estudiante) {
        switch ($estudiante->Departamento) {
            case 'Ciencias de la Computación':
                $estudiantesDCCO[] = $estudiante;
                break;
            case 'DCEX':
                $estudiantesDCEX[] = $estudiante;
                break;
            case 'DCVA':
                $estudiantesDCVA[] = $estudiante;
                break;
        }
    }

    return view('director.estudiantesAprobados', compact('estudiantesDCCO', 'estudiantesDCEX', 'estudiantesDCVA', 'elementosPorPagina','elementosPorPagina2', 'estudiantesAprobados', 'estudiantesVinculacion'));
}



public function indexProyectos(Request $request)
{
    $elementosPorPagina = $request->input('elementosPorPagina');
    $query = Proyecto::query();

    // Realizar la búsqueda por los campos especificados
    if ($request->filled('buscar')) {
        $busqueda = $request->input('buscar');
        $query->where(function ($query) use ($busqueda) {
            $query->where('NombreProfesor', 'like', '%' . $busqueda . '%')
                ->orWhere('ApellidoProfesor', 'like', '%' . $busqueda . '%')
                ->orWhere('NombreProyecto', 'like', '%' . $busqueda . '%')
                ->orWhere('NombreAsignado', 'like', '%' . $busqueda . '%')
                ->orWhere('CedulaDirector', 'like', '%' . $busqueda . '%')
                ->orWhere('CedulaAsignado', 'like', '%' . $busqueda . '%')
                ->orWhere('ApellidoAsignado', 'like', '%' . $busqueda . '%')
                ->orWhere('CorreoProfeAsignado', 'like', '%' . $busqueda . '%')
                ->orWhere('DescripcionProyecto', 'like', '%' . $busqueda . '%')
                ->orWhere('CorreoElectronicoTutor', 'like', '%' . $busqueda . '%')
                ->orWhere('DepartamentoTutor', 'like', '%' . $busqueda . '%')
                ->orWhere('FechaInicio', 'like', '%' . $busqueda . '%')
                ->orWhere('FechaFinalizacion', 'like', '%' . $busqueda . '%')
                ->orWhere('cupos', 'like', '%' . $busqueda . '%')
                ->orWhere('Estado', 'like', '%' . $busqueda . '%');
        });
    }

    // Paginar los resultados de la búsqueda
    $proyectos = $query->paginate($elementosPorPagina);

    return view('director.proyectos', ['proyectos' => $proyectos, 'elementosPorPagina' => $elementosPorPagina]);
}


    public function index()
    {
        return view('director.index');
    }



    ////////////////visualizador de practicas//////////////////////

    public function practicas(Request $request)
{
    $elementosPorPagina = $request->input('elementosPorPagina'); 
    $searchInput = $request->input('searchInput');

    $estudiantesPracticaI = PracticaI::with('estudiante')
        ->whereIn('Estado', ['En ejecucion', 'Terminado'])
        ->where(function ($query) use ($searchInput) {
            $query->where('NombreEstudiante', 'like', "%$searchInput%")
                  ->orWhere('ApellidoEstudiante', 'like', "%$searchInput%")
                  ->orWhere('Nivel', 'like', "%$searchInput%")
                  ->orWhere('Practicas', 'like', "%$searchInput%")
                  ->orWhere('DocenteTutor', 'like', "%$searchInput%")
                  ->orWhere('Empresa', 'like', "%$searchInput%")
                  ->orWhere('Estado', 'like', "%$searchInput%");
        })
        ->paginate($elementosPorPagina); 

    $estudiantesPracticaII = PracticaII::with('estudiante')
        ->whereIn('Estado', ['En ejecucion', 'Terminado'])
        ->where(function ($query) use ($searchInput) {
            $query->where('NombreEstudiante', 'like', "%$searchInput%")
                  ->orWhere('ApellidoEstudiante', 'like', "%$searchInput%")
                  ->orWhere('Nivel', 'like', "%$searchInput%")
                  ->orWhere('Practicas', 'like', "%$searchInput%")
                  ->orWhere('DocenteTutor', 'like', "%$searchInput%")
                  ->orWhere('Empresa', 'like', "%$searchInput%")
                  ->orWhere('Estado', 'like', "%$searchInput%");
        })
        ->paginate($elementosPorPagina); 

    
    return view('director.practicas', compact('estudiantesPracticaI', 'estudiantesPracticaII', 'elementosPorPagina'));
}

    

        

    


}

