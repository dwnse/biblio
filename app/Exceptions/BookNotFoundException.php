<?php
declare(strict_types=1);

namespace App\Exceptions;

class BookNotFoundException extends BaseException
{
    public function __construct(string $message = 'Libro no encontrado', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, 'El libro solicitado no existe.', $code, $previous);
    }
}
