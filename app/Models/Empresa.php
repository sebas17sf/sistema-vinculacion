<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombreEmpresa',
        'rucEmpresa',
        'provincia',
        'ciudad',
        'direccion',
        'correo',
        'nombreContacto',
        'telefonoContacto',
        'actividadesMacro',
        'cuposDisponibles',
        'cartaCompromiso',
        'convenio'


    ];

}
