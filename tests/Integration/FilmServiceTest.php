<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;
use PHPUnit\Framework\TestCase;

class FilmServiceTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }

    public function testGetById(): void
    {
        $film = self::$client->films->getById(301); // Матрица
        $this->assertEquals(301, $film->kinopoiskId);
        $this->assertStringContainsStringIgnoringCase('матриц', $film->getDisplayName());
        $this->assertNotEmpty($film->year);
        $this->assertNotEmpty($film->posterUrl);
    }

    public function testSearchByKeyword(): void
    {
        $results = self::$client->films->searchByKeyword('мстители');
        $this->assertGreaterThan(0, $results->getCount());
        $this->assertNotEmpty($results->items[0]->getDisplayName());
    }

    public function testGetPopular(): void
    {
        $popular = self::$client->films->getPopular();
        $this->assertGreaterThan(0, $popular->getCount());
    }

    public function testGetFacts(): void
    {
        $facts = self::$client->films->getFacts(301);
        $this->assertIsArray($facts);
        $this->assertNotEmpty($facts);
        $this->assertTrue(method_exists($facts[0], 'isFact'));
    }

    public function testGetVideos(): void
    {
        $videos = self::$client->films->getVideos(301);
        $this->assertIsArray($videos);
        $this->assertNotEmpty($videos);
        $this->assertNotEmpty($videos[0]->url);
    }

    public function testGetImages(): void
    {
        $images = self::$client->films->getImages(301, \NotKinopoisk\Enums\ImageType::POSTER);
        $this->assertIsArray($images);
        $this->assertNotEmpty($images);
        $this->assertNotEmpty($images[0]->imageUrl);
    }

    public function testGetReviews(): void
    {
        $reviews = self::$client->films->getReviews(301);
        $this->assertIsArray($reviews);
        $this->assertNotEmpty($reviews);
        $this->assertNotEmpty($reviews[0]->description);
    }

    public function testGetExternalSources(): void
    {
        $sources = self::$client->films->getExternalSources(301);
        $this->assertIsArray($sources);
    }

    public function testGetSequelsAndPrequels(): void
    {
        $sequels = self::$client->films->getSequelsAndPrequels(301);
        $this->assertIsArray($sequels);
    }

    public function testGetCollections(): void
    {
        $collections = self::$client->films->getCollections(\NotKinopoisk\Enums\CollectionType::TOP_250_MOVIES);
        $this->assertGreaterThan(0, $collections->getCount());
    }

    public function testGetPremieres(): void
    {
        $premieres = self::$client->films->getPremieres(2024, 'JUNE');
        $this->assertIsArray($premieres);
    }

    public function testGetFilters(): void
    {
        $filters = self::$client->films->getFilters();
        $this->assertIsArray($filters->genres);
        $this->assertIsArray($filters->countries);
    }

    public function testSearchByFilters(): void
    {
        $filters = [
            'genres' => [1], // боевик
            'yearFrom' => 2020,
            'yearTo' => 2024,
            'ratingFrom' => 7.0,
            'order' => 'RATING'
        ];
        $results = self::$client->films->searchByFilters($filters);
        $this->assertGreaterThanOrEqual(0, $results->getCount());
    }
} 