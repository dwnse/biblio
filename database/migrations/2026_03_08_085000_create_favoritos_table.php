<?php
// Migration para tabla favoritos
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->increments('id_favorito');
            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_libro');
            $table->timestamp('fecha_agregado')->useCurrent();
            $table->unique(['id_usuario', 'id_libro']);
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_libro')->references('id_libro')->on('libros')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};
