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
    Schema::create('comandas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('room_id');
        $table->enum('tipo', ['limpieza', 'comida', 'otro']);
        $table->text('descripcion');
        $table->enum('estado', ['pendiente', 'en_proceso', 'completado'])->default('pendiente');
        $table->timestamps();

        $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comandas');
    }
};
