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
        Schema::create('libro_categoria', function (Blueprint $table) {
            $table->increments('id_libro_categoria');
            $table->unsignedInteger('id_libro');
            $table->unsignedInteger('id_categoria');
            $table->foreign('id_libro')->references('id_libro')->on('libros')->onDelete('cascade');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_categoria');
    }
};
