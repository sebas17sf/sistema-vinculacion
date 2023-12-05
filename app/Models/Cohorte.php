<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohorte extends Model
{
    use HasFactory;
    protected $table = 'Cohorte';
    protected $primaryKey = 'ID_cohorte';

    protected $fillable = [
        'Cohorte', 
    ];
}
