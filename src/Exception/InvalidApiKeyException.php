<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Исключение для неверного или отсутствующего API ключа
 */
class InvalidApiKeyException extends ApiException
{
    public function __construct(string $message = 'Неверный или отсутствующий API ключ', int $code = 401)
    {
        parent::__construct($message, $code);
    }
} 