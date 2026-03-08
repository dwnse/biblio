<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    use HasFactory;

    protected $table = 'recomendaciones';
    protected $primaryKey = 'id_recomendacion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_libro',
        'detalle',
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro', 'id_libro');
    }
}