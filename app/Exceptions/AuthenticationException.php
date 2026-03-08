<?php
declare(strict_types=1);

namespace App\Exceptions;

class AuthenticationException extends BaseException
{
    public function __construct(string $message = 'Error de autenticación', int $code = 401, ?\Throwable $previous = null)
    {
        parent::__construct($message, 'Credenciales inválidas.', $code, $previous);
    }
}
