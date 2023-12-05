<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $table = 'Periodo';
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'Periodo', 
        'PeriodoInicio',
        'PeriodoFin',
    ];
    public $timestamps = false;

}
