<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use PHPUnit\Framework\TestCase;

class PersonServiceStaffTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }

    public function testGetFilmStaff(): void
    {
        $staff = self::$client->persons->getFilmStaff(301); // Матрица
        $this->assertIsArray($staff->items);
        if (!empty($staff->items)) {
            $this->assertNotEmpty($staff->items[0]->getDisplayName());
        }
    }
} 