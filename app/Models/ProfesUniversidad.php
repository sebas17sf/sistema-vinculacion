<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfesUniversidad extends Model
{
    use HasFactory;
    protected $table = 'ProfesUniversidad';
    protected $fillable = [
        'Apellidos',
        'Nombres',
        'Correo',
        'Cedula',
        'Departamento',
    ];
}
