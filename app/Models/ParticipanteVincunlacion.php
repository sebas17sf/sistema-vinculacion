<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteVincunlacion extends Model
{
    use HasFactory;
    protected $table = 'ParticipanteVincunlacion';

    // Nombre de la columna que es clave primaria en la tabla
    protected $primaryKey = 'ID_Participante';

    // Campos que pueden ser llenados en masa (en el proceso de registro)
    protected $fillable = [
        'Apellidos',
        'Nombres',
        'Correo',
        'Cedula',
        'Departamento',
        // Agrega más campos según sea necesario
    ];

    // Desactivar timestamps (created_at y updated_at) en el modelo
    public $timestamps = true;

    // Definir relaciones

    public function asignacionProyectos()
    {
        return $this->hasMany(AsignacionProyecto::class, 'ParticipanteID', 'ID_Participante');
    }

    
}
