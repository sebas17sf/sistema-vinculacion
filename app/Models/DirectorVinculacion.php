<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorVinculacion extends Model
{
    use HasFactory;
    protected $table = 'DirectorVincunlacion';
    protected $primaryKey = 'ID_Director';
    
    protected $fillable = [
        'Apellidos',
        'Nombres',
        'Correo',
        'Cedula',
        'Departamento',
    ];

    public $timestamps = true;

    
}
