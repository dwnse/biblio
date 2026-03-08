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
        Schema::create('libros', function (Blueprint $table) {
            $table->increments('id_libro');
            $table->unsignedInteger('id_editorial')->nullable();
            $table->string('titulo', 200);
            $table->string('isbn', 20)->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->year('anio_publicacion')->nullable();
            $table->string('portada_url', 255)->nullable();
            $table->string('archivo_url', 255)->nullable();
            $table->integer('cantidad_disponible')->default(0);
            $table->enum('estado', ['disponible', 'no_disponible'])->default('disponible');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->foreign('id_editorial')->references('id_editorial')->on('editorial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
