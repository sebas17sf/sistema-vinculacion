<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinadorController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DirectorVinculacionController;
use App\Http\Controllers\ParticipanteVinculacionController;
use App\Http\Controllers\DocumentosVinculacion;

// Ruta para mostrar el formulario de inicio de sesión (GET)
Route::get('/', [LoginController::class, 'showOurTeam'])->name('ourTeam');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el inicio de sesión (POST)
Route::post('/login', [LoginController::class, 'login']);

// Ruta para mostrar el formulario de registro (GET)
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Ruta para procesar el registro de usuarios (POST)
Route::post('/register', [AuthController::class, 'register'])->name('register');

///funcion para cerrar la sesion del usuario
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


//////Protecion para los accesos importantes

Route::middleware(['auth'])->group(function () {
   // Ruta para mostrar el formulario de ingreso de datos del Estudiante
Route::get('/estudiantes/create', [EstudianteController::class, 'create'])->name('estudiantes.create');

// Ruta para procesar y guardar los datos del Estudiante
Route::post('/estudiantes', [EstudianteController::class, 'store'])->name('estudiantes.store');

Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');

// Ruta para mostrar el formulario de edición de datos del Estudiante
Route::get('/estudiantes/{estudiante}/edit', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
// Ruta para procesar y actualizar los datos del Estudiante
Route::put('/estudiantes/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update');
///Renviar informacion de aceptacion estudiante
Route::post('/estudiantes/{estudiante}/resend', [EstudianteController::class, 'resend'])->name('estudiantes.resend');
///guardarActividad
Route::post('/estudiantes/guardar-actividad', [EstudianteController::class, 'guardarActividad'])->name('estudiantes.guardarActividad');


//ruta para el administrador
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
////Actualziar estado del usuario profesor
Route::put('/admin/{id}/updateEstado', [AdminController::class, 'updateEstado'])->name('admin.updateEstado');
///eliminar un profesor que este negado
Route::put('/admin/{id}/updateEstado', [AdminController::class, 'updateEstado'])->name('admin.updateEstado');
///borrar los permisos concedidos
Route::delete('/admin/{id}/deletePermission', [AdminController::class, 'deletePermission'])->name('admin.deletePermission');

/////rutas para verificar el estudiante-admin
Route::get('/admin/estudiantes', [AdminController::class, 'estudiantes'])->name('admin.estudiantes');
Route::put('/admin/actualizar-estudiante/{id}', [AdminController::class, 'updateEstudiante'])->name('admin.updateEstudiante');

///ruta para visualizar los proyectos agregados por el admin
Route::get('/admin/proyectos', [AdminController::class, 'indexProyectos'])->name('admin.indexProyectos');
///ruta para crear un proyecto
Route::get('/admin/agregar-proyecto', [AdminController::class, 'crearProyectoForm'])->name('admin.agregarProyecto');
Route::post('/admin/agregar-proyecto', [AdminController::class, 'crearProyecto'])->name('admin.crearProyecto');
///editar proyecto
// Rutas para editar proyectos y eliminar
Route::get('/admin/proyectos/{ProyectoID}/edit', [AdminController::class, 'editProyectoForm'])->name('admin.editarProyecto');
Route::put('/admin/proyectos/{ProyectoID}', [AdminController::class, 'editProyecto'])->name('admin.updateProyecto');
Route::delete('/admin/proyectos/{ProyectoID}', [AdminController::class, 'deleteProyecto'])->name('admin.deleteProyecto');
//guardarCohorte
Route::post('/admin/guardar-cohorte', [AdminController::class, 'guardarCohorte'])->name('admin.guardarCohorte');
///guardarPeriodo
Route::post('/admin/guardar-periodo', [AdminController::class, 'guardarPeriodo'])->name('admin.guardarPeriodo');
///eliminarPeriodo
Route::delete('/admin/{id}/eliminar-periodo', [AdminController::class, 'eliminarPeriodo'])->name('admin.eliminarPeriodo');
////eliminarCohorte
Route::delete('/admin/{id}/eliminar-cohorte', [AdminController::class, 'eliminarCohorte'])->name('admin.eliminarCohorte');

///ruta cordinador index
Route::get('/coordinador', [CoordinadorController::class, 'index'])->name('coordinador.index');
///ruta para agregar proyecto coordinador
Route::get('/coordinador/agregar-proyecto', [CoordinadorController::class, 'crearProyectoForm'])->name('coordinador.agregarProyecto');
Route::post('/coordinador/agregar-proyecto', [CoordinadorController::class, 'crearProyecto'])->name('coordinador.crearProyecto');
///editar proyecto
// Rutas para editar proyectos y eliminar
Route::get('/coordinador/proyectos/{ProyectoID}/edit', [CoordinadorController::class, 'editProyectoForm'])->name('coordinador.editarProyecto');
Route::put('/coordinador/proyectos/{ProyectoID}', [CoordinadorController::class, 'editProyecto'])->name('coordinador.updateProyecto');
Route::delete('/coordinador/proyectos/{ProyectoID}', [CoordinadorController::class, 'deleteProyecto'])->name('coordinador.deleteProyecto');
///mostrar los estudiantes aprobados
Route::get('/coordinador/estudiantes-aprobados', [CoordinadorController::class, 'mostrarEstudiantesAprobados'])->name('coordinador.estudiantesAprobados');

///ruta para asignar proyecto
Route::get('/coordinador/asignar-proyecto', [CoordinadorController::class, 'asignarProyectos'])->name('coordinador.asignarProyecto');
//guardar asignacion
Route::post('/coordinador/guardar-asignacion', [CoordinadorController::class, 'guardarAsignacion'])->name('coordinador.guardarAsignacion');
//mostrar los proyectos asignados a los estudiantes
Route::get('/coordinador/proyectos-estudiantes', [CoordinadorController::class, 'proyectosEstudiantes'])->name('coordinador.proyectosEstudiantes');


//ruta para las practicas 1 y 2 del estudiante
Route::get('/estudiantes/practica1', [EstudianteController::class, 'practica1'])->name('estudiantes.practica1');
Route::get('/estudiantes/practica2', [EstudianteController::class, 'practica2'])->name('estudiantes.practica2');

////ruta para visular la vista agregarEmpresa del coordinador
Route::get('/coordinador/agregar-empresa', [CoordinadorController::class, 'agregarEmpresa'])->name('coordinador.agregarEmpresa');
///ruta para guardar la empresa del coordinador
Route::post('/coordinador/guardar-empresa', [CoordinadorController::class, 'guardarEmpresa'])->name('coordinador.guardarEmpresa');
///ruta para eliminar la empresa del coordinador
Route::delete('/coordinador/eliminar-empresa/{id}', [CoordinadorController::class, 'eliminarEmpresa'])->name('coordinador.eliminarEmpresa');

////rutas para el director
Route::get('/director', [DirectorController::class, 'index'])->name('director.index');
///ruta para mostrar los estudiantes aprobados
Route::get('/director/estudiantes-aprobados', [DirectorController::class, 'mostrarEstudiantesAprobados'])->name('director.estudiantesAprobados');
///ruta para mostrar los proyectos
Route::get('/director/proyectos', [DirectorController::class, 'indexProyectos'])->name('director.indexProyectos');

///ruta para guardar guardarPracticas del estudiante
Route::post('/estudiantes/guardar-practicas', [EstudianteController::class, 'guardarPracticas'])->name('guardarPracticas');

///ruta para mostrar la vista de aceptarFaseI del coordinador
Route::get('/coordinador/aceptar-faseI', [CoordinadorController::class, 'aceptarFaseI'])->name('coordinador.aceptarFaseI');

///ruta para actualizar el estado del estudiante
Route::put('/coordinador/actualizar-estado-estudiante/{id}', [CoordinadorController::class, 'actualizarEstadoEstudiante'])->name('coordinador.actualizarEstadoEstudiante');

////ruta para gener el documento del DocumetoController
Route::get('/estudiantes/documentos', [DocumentoController::class, 'mostrarFormulario'])->name('estudiantes.documentos');
Route::post('/estudiantes/generar-documento', [DocumentoController::class, 'generar'])->name('generar-documento');
Route::post('/estudiantes/generar-documento-cartaCompromiso', [DocumentoController::class, 'generarCartaCompromiso'])->name('generar-documento-cartaCompromiso');
Route::post('/estudiantes/generar-documento-numeroHoras', [DocumentoController::class, 'generarHorasEstudiante'])->name('generar-documento-numeroHoras');
Route::post('/estudiantes/generar-informe', [DocumentoController::class, 'generarInforme'])->name('estudiantes.generarInforme');

////rutas del participante vinculacion
Route::get('/participante-vinculacion', [ParticipanteVinculacionController::class, 'index'])->name('ParticipanteVinculacion.index');
Route::get('/participante-vinculacion/estudiantes', [ParticipanteVinculacionController::class, 'estudiantes'])->name('ParticipanteVinculacion.estudiantes');

///rutas para guardar las notas del estudiante, participante
Route::post('/participante-vinculacion/guardar-notas', [ParticipanteVinculacionController::class, 'guardarNotas'])->name('guardar-notas');
///ruta para los documentos del participante
Route::get('/participante-vinculacion/documentos', [DocumentosVinculacion::class, 'documentos'])->name('ParticipanteVinculacion.documentos');

///ruta para generarEvaluacionEstudiante
Route::post('/participante-vinculacion/generar-evaluacion-estudiante', [DocumentosVinculacion::class, 'generarEvaluacionEstudiante'])->name('ParticipanteVinculacion.generarEvaluacionEstudiante');

///ruta para generarHorasDocente
Route::post('/participante-vinculacion/generar-horas-docente', [DocumentosVinculacion::class, 'generarHorasDocente'])->name('ParticipanteVinculacion.generarHorasDocente');

///ruta para generarAsistencia
Route::post('/participante-vinculacion/generar-asistencia', [DocumentosVinculacion::class, 'generarAsistencia'])->name('ParticipanteVinculacion.generarAsistencia');

//configuracion estudiantes
Route::get('/estudiantes/configuracion', [EstudianteController::class, 'configuracion'])->name('estudiantes.configuracion');
Route::put('/estudiantes/actualizar-configuracion/{id}', [EstudianteController::class, 'actualizarConfiguracion'])->name('estudiantes.actualizarConfiguracion');

///agregar guardarMaestro
Route::post('/admin/guardar-maestro', [AdminController::class, 'guardarMaestro'])->name('admin.guardarMaestro');
///eliminar maestro
Route::delete('/admin/{id}/eliminar-maestro', [AdminController::class, 'eliminarMaestro'])->name('admin.eliminarMaestro');
//descargar coordinador
Route::get('/coordinador/descargar/{tipo}/{id}', [CoordinadorController::class, 'descargar'])
    ->name('coordinador.descargar');


////participante vinculacion configuracion
Route::get('/participante-vinculacion/configuracion', [ParticipanteVinculacionController::class, 'configuracion'])->name('ParticipanteVinculacion.configuracion');
///actualizar configuracion
Route::put('/participante-vinculacion/actualizar-configuracion/{ID_Participante}', [ParticipanteVinculacionController::class, 'actualizarConfiguracion'])->name('ParticipanteVinculacion.actualizarConfiguracion');

///reporte reportesVinculacion del coordinador en DocumentoController
Route::post('/coordinador/reportes-vinculacion', [DocumentoController::class, 'reportesVinculacion'])->name('coordinador.reportesVinculacion');

///DirectorVinculacion
Route::get('/director-vinculacion', [DirectorVinculacionController::class, 'index'])->name('director_vinculacion.index');
///estudiantes
Route::get('/director-vinculacion/estudiantes', [DirectorVinculacionController::class, 'estudiantes'])->name('director_vinculacion.estudiantes');

///actualizarInforme del director
Route::post('/director-vinculacion/actualizar-informe', [DirectorVinculacionController::class, 'actualizarInforme'])->name('director_vinculacion.actualizarInforme');

////descargarEvidencias del coordinador
Route::get('/coordinador/descargar-evidencias/{ProyectoID}', [CoordinadorController::class, 'descargarEvidencias'])->name('coordinador.descargarEvidencias');

///documentosDirector del director vinculacion
Route::get('/director-vinculacion/documentos', [DirectorVinculacionController::class, 'documentosDirector'])->name('director_vinculacion.documentos');

//generarInformeDirector
Route::post('/director-vinculacion/generar-informe-director', [DirectorVinculacionController::class, 'generarInformeDirector'])->name('director_vinculacion.generarInformeDirector');

////agregarEmpresa del admin
Route::get('/admin/agregar-empresa', [AdminController::class, 'agregarEmpresa'])->name('admin.agregarEmpresa');
///guardarEmpresa del admin
Route::post('/admin/guardar-empresa', [AdminController::class, 'guardarEmpresa'])->name('admin.guardarEmpresa');
///descargar del admin
Route::get('/admin/descargar/{tipo}/{id}', [AdminController::class, 'descargar'])
    ->name('admin.descargar');
///eliminarEmpresa del admin
Route::delete('/admin/eliminar-empresa/{id}', [AdminController::class, 'eliminarEmpresa'])->name('admin.eliminarEmpresa');
//editarEmpresa del admin
Route::get('/admin/empresa/{id}/edit', [AdminController::class, 'editarEmpresa'])->name('admin.editarEmpresa');
////actualizarEmpresa del admin
Route::put('/admin/actualizar-empresa/{id}', [AdminController::class, 'actualizarEmpresa'])->name('admin.actualizarEmpresa');
///editarDocente ir a la vista
Route::get('/admin/docente/{id}/edit', [AdminController::class, 'editarDocente'])->name('admin.editarDocente');
//////actualizarMaestro del admin
Route::put('/admin/actualizar-maestro/{id}', [AdminController::class, 'actualizarMaestro'])->name('admin.actualizarMaestro');
///guardarPracticas2 del estudiante
Route::post('/estudiantes/guardar-practicas2', [EstudianteController::class, 'guardarPracticas2'])->name('guardarPracticas2');
////actualizarEstadoEstudiante2 del coordinador
Route::put('/coordinador/actualizar-estado-estudiante2/{id}', [CoordinadorController::class, 'actualizarEstadoEstudiante2'])->name('coordinador.actualizarEstadoEstudiante2');
///editarCohorte del admin
Route::get('/admin/cohorte/{id}/edit', [AdminController::class, 'editarCohorte'])->name('admin.editarCohorte');
///actualizarCohorte del admin
Route::put('/admin/actualizar-cohorte/{id}', [AdminController::class, 'actualizarCohorte'])->name('admin.actualizarCohorte');
///editarPeriodo del admin
Route::get('/admin/periodo/{id}/edit', [AdminController::class, 'editarPeriodo'])->name('admin.editarPeriodo');
//actualizarPeriodo del admin
Route::put('/admin/actualizar-periodo/{id}', [AdminController::class, 'actualizarPeriodo'])->name('admin.actualizarPeriodo');
////editarNombreEmpresa del coordinador
Route::get('/coordinador/estudiante/{id}/edit', [CoordinadorController::class, 'editarNombreEmpresa'])->name('coordinador.editarNombreEmpresa');
///actualizarNombreEmpresa del coordinador
Route::put('/coordinador/actualizar-nombre-empresa/{id}', [CoordinadorController::class, 'actualizarNombreEmpresa'])->name('coordinador.actualizarNombreEmpresa');
///guardarAsignacion del admin
Route::post('/admin/guardar-asignacion', [AdminController::class, 'guardarAsignacion'])->name('admin.guardarAsignacion');
/////aceptarFasei del admin
Route::get('/admin/aceptar-fasei', [AdminController::class, 'aceptarFasei'])->name('admin.aceptarFaseI');
///actualizarEstadoEstudiante del admin
Route::put('/admin/actualizar-estado-estudiante/{id}', [AdminController::class, 'actualizarEstadoEstudiante'])->name('admin.actualizarEstadoEstudiante');
////actualizarEstadoEstudiante2 del admin
Route::put('/admin/actualizar-estado-estudiante2/{id}', [AdminController::class, 'actualizarEstadoEstudiante2'])->name('admin.actualizarEstadoEstudiante2');
////editarNombreEmpresa del admin
Route::get('/admin/estudiante/{id}/edit', [AdminController::class, 'editarNombreEmpresa'])->name('admin.editarNombreEmpresa');
////actualizarNombreEmpresa del admin
Route::put('/admin/actualizar-nombre-empresa/{id}', [AdminController::class, 'actualizarNombreEmpresa'])->name('admin.actualizarNombreEmpresa');
////practicas del director
Route::get('/director/practicas', [DirectorController::class, 'practicas'])->name('director.practicas');
/////editarEmpresa de coordinador
Route::get('/coordinador/empresa/{id}/edit', [CoordinadorController::class, 'editarEmpresa'])->name('coordinador.editarEmpresa');
////actualizarEmpresa de coordinador
Route::put('/coordinador/actualizar-empresa/{id}', [CoordinadorController::class, 'actualizarEmpresa'])->name('coordinador.actualizarEmpresa');
////certificadoMatricula del estudiante
Route::get('/estudiantes/certificado-matricula', [EstudianteController::class, 'certificadoMatricula'])->name('estudiantes.certificadoMatricula');
////reportesProyectos de DocumentoController para admin y coordinador
Route::post('/coordinador/reportes-proyectos', [DocumentoController::class, 'reportesProyectos'])->name('coordinador.reportesProyectos');
//////reportesEstudiantes de DocumentoController para admin y coordinador
Route::post('/coordinador/reportes-estudiantes', [DocumentoController::class, 'reportesEstudiantes'])->name('coordinador.reportesEstudiantes');
/////reportesEmpresas de DocumentoController para admin y coordinador
Route::post('/coordinador/reportes-empresas', [DocumentoController::class, 'reportesEmpresas'])->name('coordinador.reportesEmpresas');
////reportesPracticaI de DocumentoController para admin y coordinador
Route::post('/coordinador/reportes-practicaI', [DocumentoController::class, 'reportesPracticaI'])->name('coordinador.reportesPracticaI');
////reportesPracticaII de DocumentoController para admin y coordinador
Route::post('/coordinador/reportes-practicaII', [DocumentoController::class, 'reportesPracticaII'])->name('coordinador.reportesPracticaII');
////reporteVinculacionProyectos
Route::post('/coordinador/reportes-vinculacion-proyectos', [DocumentoController::class, 'reporteVinculacionProyectos'])->name('coordinador.reporteVinculacionProyectos');


});
