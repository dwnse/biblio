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
        Schema::create('autores', function (Blueprint $table) {
            $table->increments('id_autor');
            $table->string('nombres', 150);
            $table->string('apellidos', 150)->nullable();
            $table->string('nacionalidad', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('biografia')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores');
    }
};
