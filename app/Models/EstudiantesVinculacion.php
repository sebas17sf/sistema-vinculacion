<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudiantesVinculacion extends Model
{
    use HasFactory;
    protected $table = 'estudiantesvinculacion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cedula_identidad',
        'correo_electronico',
        'espe_id',
        'nombres',
        'periodo_ingreso',
        'periodo_vinculacion',
        'actividades_macro',
        'docente_participante',
        'fecha_inicio',
        'fecha_fin',
        'total_horas',
        'director_proyecto',
        'nombre_proyecto',
    ];
    public $timestamps = false;

}
