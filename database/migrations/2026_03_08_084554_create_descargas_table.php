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
        Schema::create('descargas', function (Blueprint $table) {
            $table->increments('id_descarga');
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_libro');
            $table->timestamp('fecha_descarga')->useCurrent();
            $table->string('ip_usuario', 45)->nullable();
            $table->string('dispositivo', 100)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_libro')->references('id_libro')->on('libros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descargas');
    }
};
