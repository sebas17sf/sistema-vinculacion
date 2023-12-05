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
        Schema::create('NotasEstudiante', function (Blueprint $table) {
            $table->id('ID_Notas');
            $table->unsignedBigInteger('EstudianteID');
            $table->foreign('EstudianteID')->references('EstudianteID')->on('Estudiantes');
            $table->decimal('Tareas', 5, 2);
            $table->decimal('Resultados_Alcanzados', 5, 2);
            $table->decimal('Conocimientos', 5, 2);
            $table->decimal('Adaptabilidad', 5, 2);
            $table->decimal('Aplicacion', 5, 2);
            $table->decimal('Capacidad_liderazgo', 5, 2);
            $table->decimal('Asistencia', 5, 2);
            $table->string('Informe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('NotasEstudiante');
    }
};
