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
        Schema::create('resenas', function (Blueprint $table) {
            $table->increments('id_resena');
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_libro');
            $table->tinyInteger('calificacion')->unsigned();
            $table->text('comentario')->nullable();
            $table->timestamp('fecha_resena')->useCurrent();
            $table->enum('estado', ['visible', 'oculta'])->default('visible');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_libro')->references('id_libro')->on('libros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
