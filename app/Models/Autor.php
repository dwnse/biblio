<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';
    protected $primaryKey = 'id_autor';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombres',
        'apellidos',
        'nacionalidad',
        'fecha_nacimiento',
        'biografia',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function libros()
    {
        return $this->belongsToMany(Libro::class, 'libro_autor', 'id_autor', 'id_libro', 'id_autor', 'id_libro');
    }
}