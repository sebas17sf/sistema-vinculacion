<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('practicasii', function (Blueprint $table) {
            $table->id('PracticasII');
            $table->unsignedBigInteger('EstudianteID');
            $table->foreign('EstudianteID')->references('EstudianteID')->on('Estudiantes');
            $table->string('NombreEstudiante', 100);
            $table->string('ApellidoEstudiante', 100);
            $table->string('Departamento', 100);
            $table->string('Nivel', 100);
            $table->string('Practicas', 100);
            $table->string('DocenteTutor', 100);
            $table->string('Empresa', 100);
            $table->string('CedulaTutorEmpresarial', 100);
            $table->string('NombreTutorEmpresarial', 100);
            $table->string('Funcion', 100);
            $table->string('TelefonoTutorEmpresarial', 100);
            $table->string('EmailTutorEmpresarial', 100);
            $table->string('DepartamentoTutorEmpresarial', 100);
            $table->string('EstadoAcademico', 100);
            $table->date('FechaInicio');
            $table->date('FechaFinalizacion');
            $table->string('HorasPlanificadas', 100);
            $table->string('HoraEntrada', 100);
            $table->string('HoraSalida', 100);
            $table->string('AreaConocimiento', 100);
            $table->string('Estado', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practicasii');
    }
};
