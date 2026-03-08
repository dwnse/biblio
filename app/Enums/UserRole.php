<?php
declare(strict_types=1);

namespace App\Enums;

enum UserRole: int
{
    case Administrador = 1;
    case Usuario = 2;

    public function label(): string
    {
        return match ($this) {
            self::Administrador => 'Administrador',
            self::Usuario => 'Usuario',
        };
    }
}
