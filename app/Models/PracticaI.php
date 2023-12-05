<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticaI extends Model
{
    use HasFactory;
    protected $table = 'PracticasI';
    protected $primaryKey = 'PracticasI';

    protected $fillable = [
        'EstudianteID',
        'NombreEstudiante',
        'ApellidoEstudiante',
        'Departamento',
        'Nivel',
        'Practicas',
        'DocenteTutor',
        'Empresa',
        'CedulaTutorEmpresarial',
        'NombreTutorEmpresarial',
        'Funcion',
        'TelefonoTutorEmpresarial',
        'EmailTutorEmpresarial',
        'DepartamentoTutorEmpresarial',
        'EstadoAcademico',
        'FechaInicio',
        'FechaFinalizacion',
        'HorasPlanificadas',
        'HoraEntrada',
        'HoraSalida',
        'AreaConocimiento',
        'Estado'

    ];
    public $timestamps = true;


    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'EstudianteID', 'EstudianteID');
    }


}
