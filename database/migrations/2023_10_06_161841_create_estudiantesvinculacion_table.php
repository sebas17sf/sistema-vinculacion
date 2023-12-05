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
        Schema::create('estudiantesvinculacion', function (Blueprint $table) {
            $table->id();
            $table->string('cedula_identidad', 255);
            $table->string('correo_electronico', 100);
            $table->string('espe_id', 50);
            $table->string('nombres', 100);
            $table->string('periodo_ingreso', 255);
            $table->string('periodo_vinculacion', 255);
            $table->string('actividades_macro', 500);
            $table->string('docente_participante', 100);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('total_horas');
            $table->string('director_proyecto', 100);
            $table->string('nombre_proyecto', 500);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantesvinculacion');
    }
};
