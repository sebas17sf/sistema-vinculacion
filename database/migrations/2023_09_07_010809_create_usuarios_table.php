<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('Usuarios', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('Nombre');
            $table->string('Apellido');
            $table->string('CorreoElectronico')->unique();
            $table->string('Contrasena');
            $table->string('TipoUsuario');
            $table->string('Estado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Usuarios');
    }
};