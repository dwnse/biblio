<?php
declare(strict_types=1);

namespace App\Exceptions;

class UserNotFoundException extends BaseException
{
    public function __construct(string $message = 'Usuario no encontrado', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, 'El usuario solicitado no existe.', $code, $previous);
    }
}
