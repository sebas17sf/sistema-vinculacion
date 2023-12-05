<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'Proyectos';
    protected $primaryKey = 'ProyectoID';

    protected $fillable = [
        'NombreProfesor',
        'ApellidoProfesor',
        'NombreProyecto',
        'NombreAsignado',
        'CedulaDirector',
        'CedulaAsignado',
        'ApellidoAsignado',
        'CorreoProfeAsignado',
        'DescripcionProyecto',
        'CorreoElectronicoTutor',
        'DepartamentoTutor',
        'FechaInicio',
        'FechaFinalizacion',
        'cupos',
        'Estado',
    ];
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'AsignacionProyectos', 'ProyectoID', 'EstudianteID');
    }
    public function asignaciones()
{
    return $this->hasMany(AsignacionProyecto::class, 'ProyectoID', 'ProyectoID');
}

}
