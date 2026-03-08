<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    protected $table = 'editorial';
    protected $primaryKey = 'id_editorial';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'pais',
        'ciudad',
        'telefono',
        'email',
        'sitio_web',
        'estado',
    ];

    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_editorial', 'id_editorial');
    }
}
