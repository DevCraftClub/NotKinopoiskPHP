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
        $apiKey = getenv('KINOPOISK_API_KEY');
        if ($apiKey === false) {
            $this->markTestSkipped('API ключ не найден в переменных окружения');
        }
        $info = self::$client->users->getApiKeyInfo($apiKey);
        $this->assertNotEmpty($info->accountType);
        $this->assertGreaterThanOrEqual(0, $info->totalQuota['used']);
    }
} 