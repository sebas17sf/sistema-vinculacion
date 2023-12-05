<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstudiantesTable extends Migration
{
    public function up()
    {
        Schema::create('Estudiantes', function (Blueprint $table) {
            $table->id('EstudianteID');
            $table->unsignedBigInteger('UserID');
            $table->foreign('UserID')->references('UserID')->on('Usuarios');
            $table->string('Nombres', 20);
            $table->string('Apellidos', 20);
            $table->string('espe_id', 20);
            $table->integer('celular');
            $table->String('cedula');
            $table->String('Cohorte');
            $table->String('Periodo');
            $table->String('Carrera');
            $table->String('Correo');
            $table->String('Provincia');
            $table->String('Departamento');
            $table->string ('Estado');
            $table->string('comentario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Estudiantes');
    }
}