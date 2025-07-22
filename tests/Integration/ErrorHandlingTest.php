<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\ResourceNotFoundException;
use NotKinopoisk\Exception\RateLimitException;
use PHPUnit\Framework\TestCase;

class ErrorHandlingTest extends TestCase
{
    public function testInvalidApiKey(): void
    {
        $client = new Client('invalid-key-123');
        $this->expectException(InvalidApiKeyException::class);
        $client->films->getById(301);
    }

    public function testResourceNotFound(): void
    {
        $client = new Client();
        $this->expectException(ResourceNotFoundException::class);
        $client->films->getById(9999999); // Несуществующий фильм (меньше 10000000)
    }

    // RateLimit тест не реализован, чтобы не нарушать правила API
} 