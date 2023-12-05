<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasEstudiante extends Model
{
    use HasFactory;
    protected $table = 'NotasEstudiante';
    protected $primaryKey = 'ID_Notas';

    protected $fillable = [
        'ID_Notas',
        'EstudianteID',
        'Tareas',
        'Resultados_Alcanzados',
        'Conocimientos',
        'Adaptabilidad',
        'Capacidad_liderazgo',
        'Asistencia',
        'Informe',
    ];
    public $timestamps = true;

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'EstudianteID', 'EstudianteID');
    }



}
