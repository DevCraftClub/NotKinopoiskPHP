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
        $this->assertGreaterThan(0, $result->getCount());
        $this->assertNotEmpty($result->items[0]->getDisplayName());
    }

    public function testGetById(): void
    {
        $result = self::$client->persons->searchByName('Том Круз');
        if ($result->items[0]->personId !== null) {
            $person = self::$client->persons->getById($result->items[0]->personId);
            $this->assertNotEmpty($person->getDisplayName());
            $this->assertNotEmpty($person->posterUrl);
        } else {
            $this->markTestSkipped('personId is null');
        }
    }
} 