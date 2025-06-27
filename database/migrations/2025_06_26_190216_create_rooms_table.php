<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('tipo')->default('estándar'); // puede ser estándar, suite, etc.
            $table->enum('estado', ['disponible', 'ocupada', 'reservada', 'limpieza'])->default('disponible');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
