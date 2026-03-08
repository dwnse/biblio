<?php
declare(strict_types=1);

namespace App\Enums;

enum BookStatus: string
{
    case Disponible = 'disponible';
    case NoDisponible = 'no_disponible';

    public function label(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::NoDisponible => 'No Disponible',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Disponible => 'badge-success',
            self::NoDisponible => 'badge-danger',
        };
    }
}
