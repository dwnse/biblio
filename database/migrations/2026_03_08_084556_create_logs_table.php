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
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id_log');
            $table->unsignedInteger('id_usuario')->nullable();
            $table->string('accion', 150);
            $table->string('tabla_afectada', 100);
            $table->integer('id_registro_afectado')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->string('ip', 45)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
