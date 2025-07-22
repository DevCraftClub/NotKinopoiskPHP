<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use PHPUnit\Framework\TestCase;

class StaffServiceTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }

    public function testGetByFilmId(): void
    {
        $staff = self::$client->staff->getByFilmId(301); // Матрица
        $this->assertIsArray($staff);
        $this->assertNotEmpty($staff);
        $this->assertNotEmpty($staff[0]->getDisplayName());
    }
} 