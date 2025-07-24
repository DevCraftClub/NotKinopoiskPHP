<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }

    public function testGetApiKeyInfo(): void
    {
        $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? getenv('KINOPOISK_API_KEY');
        $info = self::$client->users->getApiKeyInfo($apiKey);
        $this->assertNotEmpty($info->accountType);
        $this->assertGreaterThanOrEqual(0, $info->totalQuota->used);
    }

    public function testGetVotes(): void
    {
        // Используем тестовый ID пользователя
        $votes = self::$client->users->getVotes(1);
        $this->assertInstanceOf(\NotKinopoisk\Responses\PaginatedResponse::class, $votes);
        $this->assertIsArray($votes->items);
    }
} 