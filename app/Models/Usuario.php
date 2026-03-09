<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre',
        'apellidoP',
        'apellidoM',
        'email',
        'contrasena',
        'telefono',
        'estado',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'contrasena',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'ultimo_acceso' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'id_usuario', 'id_usuario');
    }

    public function descargas()
    {
        return $this->hasMany(Descarga::class, 'id_usuario', 'id_usuario');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'id_usuario', 'id_usuario');
    }
}