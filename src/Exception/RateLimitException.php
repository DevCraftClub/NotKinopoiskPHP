<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Исключение для превышения лимитов запросов
 */
class RateLimitException extends ApiException
{
    public function __construct(string $message = 'Превышен лимит запросов', int $code = 429)
    {
        parent::__construct($message, $code);
    }
} 