<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use PHPUnit\Framework\TestCase;

class PersonServiceTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }

    public function testSearchByName(): void
    {
        $result = self::$client->persons->searchByName('Том Круз');
        $this->assertIsArray($result->items);
    }

    public function testGetById(): void
    {
        $result = self::$client->persons->searchByName('Том Круз');
        if (!empty($result->items) && $result->items[0]->personId !== null) {
            $person = self::$client->persons->getById($result->items[0]->personId);
            $this->assertNotEmpty($person->getDisplayName());
        } else {
            $this->markTestSkipped('Не удалось найти персону для тестирования');
        }
    }

    public function testGetFilmStaff(): void
    {
        $staff = self::$client->persons->getFilmStaff(301); // Матрица
        $this->assertIsArray($staff->staff);
    }
} 