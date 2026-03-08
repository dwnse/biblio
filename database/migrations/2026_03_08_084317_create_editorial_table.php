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
        Schema::create('editorial', function (Blueprint $table) {
            $table->increments('id_editorial');
            $table->string('nombre', 150);
            $table->string('pais', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('sitio_web', 200)->nullable();
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editorial');
    }
};
