<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadEstudiante extends Model
{
    use HasFactory;
    protected $table = 'actividades_estudiante';
    protected $primaryKey = 'ID_Actividades';
    public $timestamps = true;

    protected $fillable = [
        'EstudianteID',
        'fecha',
        'actividades',
        'numero_horas',
        'evidencias',
        'nombre_actividad',
    ];

    public function estudiante()
        {
        return $this->belongsTo(Estudiante::class, 'EstudianteID');
     }
    
}
