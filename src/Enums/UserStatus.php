<?php
declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case Activo = 'activo';
    case Inactivo = 'inactivo';

    public function label(): string
    {
        return match ($this) {
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Activo => 'badge-success',
            self::Inactivo => 'badge-danger',
        };
    }
}
