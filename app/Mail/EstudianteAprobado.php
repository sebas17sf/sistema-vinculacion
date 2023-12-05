<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Estudiante;

class EstudianteAprobado extends Mailable
{
    use SerializesModels;

    public $estudiante;

    public function __construct(Estudiante $estudiante) 
    {
        $this->estudiante = $estudiante;
    }

    



    public function build()
    {
        return $this->subject('¡Felicidades! Tu estado ha sido aprobado')
                    ->view('emails.estudiante-aprobado'); 
    }
}
