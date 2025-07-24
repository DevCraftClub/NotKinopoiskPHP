<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\ProductionStatus;
use NotKinopoisk\Models\Film;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для модели Film
 * 
 * @package Tests\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
class FilmTest extends TestCase
{
    /**
     * Тест создания объекта Film
     */
    public function testCreateFilm(): void
    {
        $film = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: 'tt0133093',
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: 1000,
            ratingGoodReview: 8.5,
            ratingGoodReviewVoteCount: 500,
            ratingKinopoisk: 8.7,
            ratingKinopoiskVoteCount: 10000,
            ratingImdb: 8.7,
            ratingImdbVoteCount: 2000000,
            ratingFilmCritics: 8.0,
            ratingFilmCriticsVoteCount: 200,
            ratingAwait: 8.2,
            ratingAwaitCount: 1000,
            ratingRfCritics: 8.5,
            ratingRfCriticsVoteCount: 50,
            webUrl: 'https://kinopoisk.ru/film/301/',
            year: 1999,
            filmLength: 136,
            slogan: 'Добро пожаловать в реальный мир',
            description: 'Описание фильма...',
            shortDescription: 'Краткое описание',
            editorAnnotation: null,
            isTicketsAvailable: false,
            productionStatus: ProductionStatus::COMPLETED,
            type: ContentType::FILM,
            ratingMpaa: 'R',
            ratingAgeLimits: '16+',
            hasImax: true,
            has3D: false,
            lastSync: '2023-01-01T00:00:00Z',
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: false,
            shortFilm: false,
            completed: true
        );

        $this->assertEquals(301, $film->kinopoiskId);
        $this->assertEquals('Матрица', $film->nameRu);
        $this->assertEquals('The Matrix', $film->nameEn);
        $this->assertEquals(ContentType::FILM, $film->type);
        $this->assertEquals(1999, $film->year);
    }

    /**
     * Тест создания объекта Film из массива данных
     */
    public function testFromArray(): void
    {
        $data = [
            'kinopoiskId' => 301,
            'kinopoiskHDId' => null,
            'imdbId' => 'tt0133093',
            'nameRu' => 'Матрица',
            'nameEn' => 'The Matrix',
            'nameOriginal' => 'The Matrix',
            'posterUrl' => 'https://example.com/poster.jpg',
            'posterUrlPreview' => 'https://example.com/poster_preview.jpg',
            'coverUrl' => null,
            'logoUrl' => null,
            'reviewsCount' => 1000,
            'ratingGoodReview' => 8.5,
            'ratingGoodReviewVoteCount' => 500,
            'ratingKinopoisk' => 8.7,
            'ratingKinopoiskVoteCount' => 10000,
            'ratingImdb' => 8.7,
            'ratingImdbVoteCount' => 2000000,
            'ratingFilmCritics' => 8.0,
            'ratingFilmCriticsVoteCount' => 200,
            'ratingAwait' => 8.2,
            'ratingAwaitCount' => 1000,
            'ratingRfCritics' => 8.5,
            'ratingRfCriticsVoteCount' => 50,
            'webUrl' => 'https://kinopoisk.ru/film/301/',
            'year' => 1999,
            'filmLength' => 136,
            'slogan' => 'Добро пожаловать в реальный мир',
            'description' => 'Описание фильма...',
            'shortDescription' => 'Краткое описание',
            'editorAnnotation' => null,
            'isTicketsAvailable' => false,
            'productionStatus' => 'COMPLETED',
            'type' => 'FILM',
            'ratingMpaa' => 'R',
            'ratingAgeLimits' => '16+',
            'hasImax' => true,
            'has3D' => false,
            'lastSync' => '2023-01-01T00:00:00Z',
            'countries' => [],
            'genres' => [],
            'startYear' => null,
            'endYear' => null,
            'serial' => false,
            'shortFilm' => false,
            'completed' => true
        ];

        $film = Film::fromArray($data);

        $this->assertEquals(301, $film->kinopoiskId);
        $this->assertEquals('Матрица', $film->nameRu);
        $this->assertEquals('The Matrix', $film->nameEn);
        $this->assertEquals(ContentType::FILM, $film->type);
        $this->assertEquals(ProductionStatus::COMPLETED, $film->productionStatus);
        $this->assertEquals(1999, $film->year);
    }

    /**
     * Тест метода getDisplayName()
     */
    public function testGetDisplayName(): void
    {
        $film = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: null,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: null,
            ratingGoodReview: null,
            ratingGoodReviewVoteCount: null,
            ratingKinopoisk: null,
            ratingKinopoiskVoteCount: null,
            ratingImdb: null,
            ratingImdbVoteCount: null,
            ratingFilmCritics: null,
            ratingFilmCriticsVoteCount: null,
            ratingAwait: null,
            ratingAwaitCount: null,
            ratingRfCritics: null,
            ratingRfCriticsVoteCount: null,
            webUrl: null,
            year: null,
            filmLength: null,
            slogan: null,
            description: null,
            shortDescription: null,
            editorAnnotation: null,
            isTicketsAvailable: null,
            productionStatus: null,
            type: ContentType::FILM,
            ratingMpaa: null,
            ratingAgeLimits: null,
            hasImax: null,
            has3D: null,
            lastSync: null,
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: null,
            shortFilm: null,
            completed: null
        );

        $this->assertEquals('Матрица', $film->getDisplayName());

        // Тест с английским названием
        $filmEn = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: null,
            nameRu: null,
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: null,
            ratingGoodReview: null,
            ratingGoodReviewVoteCount: null,
            ratingKinopoisk: null,
            ratingKinopoiskVoteCount: null,
            ratingImdb: null,
            ratingImdbVoteCount: null,
            ratingFilmCritics: null,
            ratingFilmCriticsVoteCount: null,
            ratingAwait: null,
            ratingAwaitCount: null,
            ratingRfCritics: null,
            ratingRfCriticsVoteCount: null,
            webUrl: null,
            year: null,
            filmLength: null,
            slogan: null,
            description: null,
            shortDescription: null,
            editorAnnotation: null,
            isTicketsAvailable: null,
            productionStatus: null,
            type: ContentType::FILM,
            ratingMpaa: null,
            ratingAgeLimits: null,
            hasImax: null,
            has3D: null,
            lastSync: null,
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: null,
            shortFilm: null,
            completed: null
        );

        $this->assertEquals('The Matrix', $filmEn->getDisplayName());
    }

    /**
     * Тест метода isSerial()
     */
    public function testIsSerial(): void
    {
        $film = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: null,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: null,
            ratingGoodReview: null,
            ratingGoodReviewVoteCount: null,
            ratingKinopoisk: null,
            ratingKinopoiskVoteCount: null,
            ratingImdb: null,
            ratingImdbVoteCount: null,
            ratingFilmCritics: null,
            ratingFilmCriticsVoteCount: null,
            ratingAwait: null,
            ratingAwaitCount: null,
            ratingRfCritics: null,
            ratingRfCriticsVoteCount: null,
            webUrl: null,
            year: null,
            filmLength: null,
            slogan: null,
            description: null,
            shortDescription: null,
            editorAnnotation: null,
            isTicketsAvailable: null,
            productionStatus: null,
            type: ContentType::FILM,
            ratingMpaa: null,
            ratingAgeLimits: null,
            hasImax: null,
            has3D: null,
            lastSync: null,
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: null,
            shortFilm: null,
            completed: null
        );

        $this->assertFalse($film->isSerial());

        // Тест сериала
        $series = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: null,
            nameRu: 'Сериал',
            nameEn: 'Series',
            nameOriginal: 'Series',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: null,
            ratingGoodReview: null,
            ratingGoodReviewVoteCount: null,
            ratingKinopoisk: null,
            ratingKinopoiskVoteCount: null,
            ratingImdb: null,
            ratingImdbVoteCount: null,
            ratingFilmCritics: null,
            ratingFilmCriticsVoteCount: null,
            ratingAwait: null,
            ratingAwaitCount: null,
            ratingRfCritics: null,
            ratingRfCriticsVoteCount: null,
            webUrl: null,
            year: null,
            filmLength: null,
            slogan: null,
            description: null,
            shortDescription: null,
            editorAnnotation: null,
            isTicketsAvailable: null,
            productionStatus: null,
            type: ContentType::SERIES,
            ratingMpaa: null,
            ratingAgeLimits: null,
            hasImax: null,
            has3D: null,
            lastSync: null,
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: null,
            shortFilm: null,
            completed: null
        );

        $this->assertTrue($series->isSerial());
    }

    /**
     * Тест метода getMainRating()
     */
    public function testGetMainRating(): void
    {
        $film = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: null,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: null,
            ratingGoodReview: null,
            ratingGoodReviewVoteCount: null,
            ratingKinopoisk: 8.7,
            ratingKinopoiskVoteCount: null,
            ratingImdb: 8.5,
            ratingImdbVoteCount: null,
            ratingFilmCritics: 8.0,
            ratingFilmCriticsVoteCount: null,
            ratingAwait: null,
            ratingAwaitCount: null,
            ratingRfCritics: null,
            ratingRfCriticsVoteCount: null,
            webUrl: null,
            year: null,
            filmLength: null,
            slogan: null,
            description: null,
            shortDescription: null,
            editorAnnotation: null,
            isTicketsAvailable: null,
            productionStatus: null,
            type: ContentType::FILM,
            ratingMpaa: null,
            ratingAgeLimits: null,
            hasImax: null,
            has3D: null,
            lastSync: null,
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: null,
            shortFilm: null,
            completed: null
        );

        $this->assertEquals(8.7, $film->getMainRating());

        // Тест без рейтинга Кинопоиска
        $filmWithoutKinopoisk = new Film(
            kinopoiskId: 301,
            kinopoiskHDId: null,
            imdbId: null,
            nameRu: 'Матрица',
            nameEn: 'The Matrix',
            nameOriginal: 'The Matrix',
            posterUrl: 'https://example.com/poster.jpg',
            posterUrlPreview: 'https://example.com/poster_preview.jpg',
            coverUrl: null,
            logoUrl: null,
            reviewsCount: null,
            ratingGoodReview: null,
            ratingGoodReviewVoteCount: null,
            ratingKinopoisk: null,
            ratingKinopoiskVoteCount: null,
            ratingImdb: 8.5,
            ratingImdbVoteCount: null,
            ratingFilmCritics: 8.0,
            ratingFilmCriticsVoteCount: null,
            ratingAwait: null,
            ratingAwaitCount: null,
            ratingRfCritics: null,
            ratingRfCriticsVoteCount: null,
            webUrl: null,
            year: null,
            filmLength: null,
            slogan: null,
            description: null,
            shortDescription: null,
            editorAnnotation: null,
            isTicketsAvailable: null,
            productionStatus: null,
            type: ContentType::FILM,
            ratingMpaa: null,
            ratingAgeLimits: null,
            hasImax: null,
            has3D: null,
            lastSync: null,
            countries: [],
            genres: [],
            startYear: null,
            endYear: null,
            serial: null,
            shortFilm: null,
            completed: null
        );

        $this->assertEquals(8.5, $filmWithoutKinopoisk->getMainRating());
    }
} 