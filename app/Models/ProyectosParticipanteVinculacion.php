<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectosParticipanteVinculacion extends Model
{
    use HasFactory;
    protected $table = 'ProyectosParticipanteVinculacion';
    protected $primaryKey = 'ProyectoDirectorID';
    protected $fillable = ['ProyectoID', 'UserID'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'ProyectoID');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UserID');
    }
}
