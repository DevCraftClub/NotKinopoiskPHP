<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Базовое исключение для API ошибок
 */
class ApiException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 