<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';
    protected $primaryKey = 'id_libro';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_editorial',
        'titulo',
        'isbn',
        'descripcion',
        'anio_publicacion',
        'portada_url',
        'archivo_url',
        'cantidad_disponible',
        'estado',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'anio_publicacion' => 'integer',
    ];

    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial', 'id_editorial');
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'libro_autor', 'id_libro', 'id_autor', 'id_libro', 'id_autor');
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'libro_categoria', 'id_libro', 'id_categoria', 'id_libro', 'id_categoria');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'id_libro', 'id_libro');
    }

    public function descargas()
    {
        return $this->hasMany(Descarga::class, 'id_libro', 'id_libro');
    }

    public function recomendaciones()
    {
        return $this->hasMany(Recomendacion::class, 'id_libro', 'id_libro');
    }
}