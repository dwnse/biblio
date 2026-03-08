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
        Schema::create('libro_autor', function (Blueprint $table) {
            $table->increments('id_libro_autor');
            $table->unsignedInteger('id_libro');
            $table->unsignedInteger('id_autor');
            $table->foreign('id_libro')->references('id_libro')->on('libros')->onDelete('cascade');
            $table->foreign('id_autor')->references('id_autor')->on('autores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_autor');
    }
};
