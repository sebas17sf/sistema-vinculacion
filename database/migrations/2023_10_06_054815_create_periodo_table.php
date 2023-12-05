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
        Schema::create('Periodo', function (Blueprint $table) {
            $table->id();
            $table->string('Periodo', 200);
            $table->date('PeriodoInicio');
            $table->date('PeriodoFin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo');
    }
};
