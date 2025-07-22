<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Исключение для ненайденных ресурсов
 */
class ResourceNotFoundException extends ApiException
{
    public function __construct(string $message = 'Запрашиваемый ресурс не найден', int $code = 404)
    {
        parent::__construct($message, $code);
    }
} 