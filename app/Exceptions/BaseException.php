<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    protected string $userMessage;

    public function __construct(string $message = '', string $userMessage = '', int $code = 0, ?\Throwable $previous = null)
    {
        $this->userMessage = $userMessage ?: $message;
        parent::__construct($message, $code, $previous);
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
