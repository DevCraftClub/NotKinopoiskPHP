<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Models\Country;
use NotKinopoisk\Models\FilmCollection;
use NotKinopoisk\Models\Genre;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели FilmCollectionItem
 * 
 * @package Tests\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class FilmCollectionItemTest extends TestCase
{
    /**
     * Тест создания объекта FilmCollectionItem
     */
    public function testCreateFilmCollectionItem(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [new Country('США')],
            genres: [new Genre('боевик')],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.7,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_small.jpg'
        );

        $this->assertEquals(301, $item->kinopoiskId);
        $this->assertEquals('Матрица', $item->nameRu);
        $this->assertEquals('The Matrix', $item->nameEn);
        $this->assertEquals('The Matrix', $item->nameOriginal);
        $this->assertCount(1, $item->countries);
        $this->assertInstanceOf(Country::class, $item->countries[0]);
        $this->assertEquals('США', $item->countries[0]->country);
        $this->assertCount(1, $item->genres);
        $this->assertInstanceOf(Genre::class, $item->genres[0]);
        $this->assertEquals('боевик', $item->genres[0]->genre);
        $this->assertEquals(8.7, $item->ratingKinopoisk);
        $this->assertEquals(8.7, $item->ratingImbd);
        $this->assertEquals(1999, $item->year);
        $this->assertEquals(ContentType::FILM, $item->type);
        $this->assertEquals('https://example.com/poster.jpg', $item->posterUrl);
        $this->assertEquals('https://example.com/poster_small.jpg', $item->posterUrlPreview);
    }

    /**
     * Тест создания объекта FilmCollectionItem из массива данных
     */
    public function testFromArray(): void
    {
        $data = [
            'kinopoiskId' => 301,
            'nameRu' => 'Матрица',
            'nameEn' => 'The Matrix',
            'nameOriginal' => 'The Matrix',
            'countries' => [['country' => 'США']],
            'genres' => [['genre' => 'боевик']],
            'ratingKinopoisk' => 8.7,
            'ratingImbd' => 8.7,
            'year' => 1999,
            'type' => 'FILM',
            'posterUrl' => 'https://example.com/poster.jpg',
            'posterUrlPreview' => 'https://example.com/poster_small.jpg'
        ];

        $item = FilmCollection::fromArray($data);

        $this->assertEquals(301, $item->kinopoiskId);
        $this->assertEquals('Матрица', $item->nameRu);
        $this->assertEquals('The Matrix', $item->nameEn);
        $this->assertEquals('The Matrix', $item->nameOriginal);
        $this->assertCount(1, $item->countries);
        $this->assertInstanceOf(Country::class, $item->countries[0]);
        $this->assertEquals('США', $item->countries[0]->country);
        $this->assertCount(1, $item->genres);
        $this->assertInstanceOf(Genre::class, $item->genres[0]);
        $this->assertEquals('боевик', $item->genres[0]->genre);
        $this->assertEquals(8.7, $item->ratingKinopoisk);
        $this->assertEquals(8.7, $item->ratingImbd);
        $this->assertEquals(1999, $item->year);
        $this->assertEquals(ContentType::FILM, $item->type);
        $this->assertEquals('https://example.com/poster.jpg', $item->posterUrl);
        $this->assertEquals('https://example.com/poster_small.jpg', $item->posterUrlPreview);
    }

    /**
     * Тест создания объекта с null значениями
     */
    public function testFromArrayWithNullValues(): void
    {
        $data = [
            'kinopoiskId' => 301,
            'nameRu' => null,
            'nameEn' => null,
            'nameOriginal' => null,
            'countries' => [],
            'genres' => [],
            'ratingKinopoisk' => null,
            'ratingImbd' => null,
            'year' => null,
            'type' => 'FILM',
            'posterUrl' => 'https://example.com/poster.jpg',
            'posterUrlPreview' => 'https://example.com/poster_small.jpg'
        ];

        $item = FilmCollection::fromArray($data);

        $this->assertNull($item->nameRu);
        $this->assertNull($item->nameEn);
        $this->assertNull($item->nameOriginal);
        $this->assertEquals([], $item->countries);
        $this->assertEquals([], $item->genres);
        $this->assertNull($item->ratingKinopoisk);
        $this->assertNull($item->ratingImbd);
        $this->assertNull($item->year);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: null,
            ratingImbd: null,
            year: null,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('Матрица', $item->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() с английским названием
     */
    public function testGetDisplayNameWithEnglishName(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: null,
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: null,
            ratingImbd: null,
            year: null,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('The Matrix', $item->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() с оригинальным названием
     */
    public function testGetDisplayNameWithOriginalName(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: null,
            nameEn: null,
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: null,
            ratingImbd: null,
            year: null,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('The Matrix', $item->getDisplayName());
    }

    /**
     * Тест метода getDisplayName() без названия
     */
    public function testGetDisplayNameWithNoName(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: null,
            nameEn: null,
            nameOriginal: null,
            countries: [],
            genres: [],
            ratingKinopoisk: null,
            ratingImbd: null,
            year: null,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('Без названия', $item->getDisplayName());
    }

    /**
     * Тест метода getMainRating()
     */
    public function testGetMainRating(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.5,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals(8.7, $item->getMainRating());
    }

    /**
     * Тест метода getMainRating() с рейтингом IMDb
     */
    public function testGetMainRatingWithImdbRating(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: null,
            ratingImbd: 8.5,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals(8.5, $item->getMainRating());
    }

    /**
     * Тест метода getMainRating() без рейтингов
     */
    public function testGetMainRatingWithNoRatings(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: null,
            ratingImbd: null,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertNull($item->getMainRating());
    }

    /**
     * Тест метода isSerial()
     */
    public function testIsSerial(): void
    {
        $filmItem = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.7,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $seriesItem = new FilmCollection(
            kinopoiskId: 302,
            nameRu: 'Игра престолов',
            nameEn: 'Game of Thrones',
            nameOriginal: 'Game of Thrones',
            countries: [],
            genres: [],
            ratingKinopoisk: 9.3,
            ratingImbd: 9.3,
            year: 2011,
            type: ContentType::SERIES,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertFalse($filmItem->isSerial());
        $this->assertTrue($seriesItem->isSerial());
    }

    /**
     * Тест метода getCountriesString()
     */
    public function testGetCountriesString(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [
                new Country('США'),
                new Country('Австралия')
            ],
            genres: [],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.7,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('США, Австралия', $item->getCountriesString());
    }

    /**
     * Тест метода getCountriesString() с пустым массивом
     */
    public function testGetCountriesStringWithEmptyArray(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.7,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('', $item->getCountriesString());
    }

    /**
     * Тест метода getGenresString()
     */
    public function testGetGenresString(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [
                new Genre('боевик'),
                new Genre('фантастика')
            ],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.7,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('боевик, фантастика', $item->getGenresString());
    }

    /**
     * Тест метода getGenresString() с пустым массивом
     */
    public function testGetGenresStringWithEmptyArray(): void
    {
        $item = new FilmCollection(
            kinopoiskId: 301,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            countries: [],
            genres: [],
            ratingKinopoisk: 8.7,
            ratingImbd: 8.7,
            year: 1999,
            type: ContentType::FILM,
            posterUrl: '',
            posterUrlPreview: ''
        );

        $this->assertEquals('', $item->getGenresString());
    }
} 