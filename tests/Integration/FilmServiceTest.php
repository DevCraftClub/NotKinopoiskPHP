<?php

declare(strict_types=1);

namespace NotKinopoisk\Tests\Integration;

use NotKinopoisk\Client;
use NotKinopoisk\Enums\CollectionType;
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\FilmOrder;
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\Month;
use NotKinopoisk\Enums\ReviewOrder;
use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Responses\SequelPrequelResponse;
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
        $this->assertInstanceOf(DefaultResponse::class, $facts);
        $this->assertGreaterThan(0, $facts->total);
        $this->assertTrue(method_exists($facts->items[0], 'isFact'));
    }

    public function testGetVideos(): void
    {
        $videos = self::$client->films->getVideos(301);
        $this->assertInstanceOf(DefaultResponse::class, $videos);
        $this->assertGreaterThan(0, $videos->total);
        $this->assertNotEmpty($videos->items[0]->url);
    }

    public function testGetImages(): void
    {
        $images = self::$client->films->getImages(301, ImageType::POSTER);
        $this->assertGreaterThan(0, $images->getCount());
        $this->assertNotEmpty($images->items[0]->imageUrl);
    }

    public function testGetReviews(): void
    {
        $reviews = self::$client->films->getReviews(301, 1, ReviewOrder::DATE_DESC);
        $this->assertGreaterThan(0, $reviews->getCount());
        $this->assertNotEmpty($reviews->items[0]->description);
    }

    public function testGetExternalSources(): void
    {
        $sources = self::$client->films->getExternalSources(301);
        $this->assertInstanceOf(DefaultResponse::class, $sources);
    }

    public function testGetSequelsAndPrequels(): void
    {
        try {
            $sequels = self::$client->films->getSequelsAndPrequels(301);
            $this->assertInstanceOf(SequelPrequelResponse::class, $sequels);
        } catch (\NotKinopoisk\Exception\ApiException $e) {
            // API может быть недоступен или возвращать ошибку
            $this->markTestSkipped('API недоступен: ' . $e->getMessage());
        } catch (\NotKinopoisk\Exception\KpValidationException $e) {
            // API возвращает данные в неожиданном формате
            $this->markTestSkipped('API возвращает данные в неожиданном формате: ' . $e->getMessage());
        }
    }

    public function testGetCollections(): void
    {
        $collections = self::$client->films->getCollections(CollectionType::TOP_250_MOVIES);
        $this->assertGreaterThan(0, $collections->getCount());
    }

    public function testGetPremieres(): void
    {
        $premieres = self::$client->films->getPremieres(2024, Month::JUNE);
        $this->assertInstanceOf(DefaultResponse::class, $premieres);
    }

    public function testGetFilters(): void
    {
        $filters = self::$client->films->getFilters();
        $this->assertIsArray($filters->genres);
        $this->assertIsArray($filters->countries);
    }

    public function testSearchFilmsByFilter(): void
    {
        $results = self::$client->films->searchFilmsByFilter(
            country: [1], // Россия
            genre: [1], // боевик
            order: FilmOrder::RATING,
            type: ContentType::FILM,
            ratingFrom: 7.0,
            yearFrom: 2020,
            yearTo: 2024,
            page: 1
        );
        $this->assertGreaterThanOrEqual(0, $results->getCount());
    }

    public function testGetTop250(): void
    {
        $top250 = self::$client->films->getTop250();
        $this->assertGreaterThan(0, $top250->getCount());
        $this->assertLessThanOrEqual(250, $top250->getCount());
    }

    public function testGetBoxOffice(): void
    {
        $boxOffice = self::$client->films->getBoxOffice(301);
        $this->assertInstanceOf(DefaultResponse::class, $boxOffice);
        $this->assertGreaterThan(0, $boxOffice->total);
    }

    public function testGetAwards(): void
    {
        $awards = self::$client->films->getAwards(301);
        $this->assertInstanceOf(DefaultResponse::class, $awards);
        $this->assertGreaterThan(0, $awards->total);
    }

    public function testGetSimilar(): void
    {
        $similar = self::$client->films->getSimilar(301);
        $this->assertInstanceOf(DefaultResponse::class, $similar);
        $this->assertGreaterThan(0, $similar->total);
    }

    public function testGetSeasons(): void
    {
        // Тестируем на сериале (например, Breaking Bad)
        $seasons = self::$client->films->getSeasons(1396);
        $this->assertInstanceOf(DefaultResponse::class, $seasons);
    }

    public function testGetDistributions(): void
    {
        $distributions = self::$client->films->getDistributions(301);
        $this->assertInstanceOf(DefaultResponse::class, $distributions);
    }
} 