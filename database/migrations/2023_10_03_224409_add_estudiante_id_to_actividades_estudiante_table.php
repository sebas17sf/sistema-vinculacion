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
        Schema::create('actividades_estudiante', function (Blueprint $table) {
            $table->id('ID_Actividades');
            $table->unsignedBigInteger('EstudianteID');
            $table->foreign('EstudianteID')->references('EstudianteID')->on('estudiantes');
            $table->date('fecha');
            $table->text('actividades');
            $table->integer('numero_horas');
            $table->binary('evidencias');
            $table->string('nombre_actividad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades_estudiante', function (Blueprint $table) {

        });
    }
};
