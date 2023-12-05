<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('Proyectos', function (Blueprint $table) {
            $table->id('ProyectoID');
            $table->string('NombreProfesor');
            $table->string('ApellidoProfesor');
            $table->string('NombreProyecto');
            $table->string('DescripcionProyecto');
            $table->string('NombreAsignado');
            $table->string('CedulaDirector');
            $table->string('CedulaAsignado');
            $table->string('ApellidoAsignado');
            $table->string('CorreoProfeAsignado');
            $table->string('CorreoElectronicoTutor');
            $table->string('DepartamentoTutor');
            $table->date('FechaInicio');
            $table->date('FechaFinalizacion');
            $table->integer('cupos');
            $table->string('Estado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Proyectos');
    }
};
