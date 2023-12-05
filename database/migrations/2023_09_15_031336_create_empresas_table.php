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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombreEmpresa');
            $table->string('rucEmpresa');
            $table->string('provincia');
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('correo');
            $table->string('nombreContacto');
            $table->string('telefonoContacto');
            $table->string('actividadesMacro');
            $table->integer('cuposDisponibles'); 
            $table->binary('cartaCompromiso')->length(1000000);
            $table->binary('convenio')->length(1000000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
