<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель фильма
 */
class Film
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $kinopoiskHDId,
        public readonly ?string $imdbId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly ?string $coverUrl,
        public readonly ?string $logoUrl,
        public readonly ?int $reviewsCount,
        public readonly ?float $ratingGoodReview,
        public readonly ?int $ratingGoodReviewVoteCount,
        public readonly ?float $ratingKinopoisk,
        public readonly ?int $ratingKinopoiskVoteCount,
        public readonly ?float $ratingImdb,
        public readonly ?int $ratingImdbVoteCount,
        public readonly ?float $ratingFilmCritics,
        public readonly ?int $ratingFilmCriticsVoteCount,
        public readonly ?float $ratingAwait,
        public readonly ?int $ratingAwaitCount,
        public readonly ?float $ratingRfCritics,
        public readonly ?int $ratingRfCriticsVoteCount,
        public readonly ?string $webUrl,
        public readonly ?int $year,
        public readonly ?int $filmLength,
        public readonly ?string $slogan,
        public readonly ?string $description,
        public readonly ?string $shortDescription,
        public readonly ?string $editorAnnotation,
        public readonly ?bool $isTicketsAvailable,
        public readonly ?string $productionStatus,
        public readonly string $type,
        public readonly ?string $ratingMpaa,
        public readonly ?string $ratingAgeLimits,
        public readonly ?bool $hasImax,
        public readonly ?bool $has3D,
        public readonly ?string $lastSync,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?int $startYear,
        public readonly ?int $endYear,
        public readonly ?bool $serial,
        public readonly ?bool $shortFilm,
        public readonly ?bool $completed
    ) {
    }

    /**
     * Создает экземпляр фильма из массива данных API
     *
     * @param array $data Данные фильма
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            kinopoiskHDId: $data['kinopoiskHDId'] ?? null,
            imdbId: $data['imdbId'] ?? null,
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            coverUrl: $data['coverUrl'] ?? null,
            logoUrl: $data['logoUrl'] ?? null,
            reviewsCount: $data['reviewsCount'] ?? null,
            ratingGoodReview: $data['ratingGoodReview'] ?? null,
            ratingGoodReviewVoteCount: $data['ratingGoodReviewVoteCount'] ?? null,
            ratingKinopoisk: $data['ratingKinopoisk'] ?? null,
            ratingKinopoiskVoteCount: $data['ratingKinopoiskVoteCount'] ?? null,
            ratingImdb: $data['ratingImdb'] ?? null,
            ratingImdbVoteCount: $data['ratingImdbVoteCount'] ?? null,
            ratingFilmCritics: $data['ratingFilmCritics'] ?? null,
            ratingFilmCriticsVoteCount: $data['ratingFilmCriticsVoteCount'] ?? null,
            ratingAwait: $data['ratingAwait'] ?? null,
            ratingAwaitCount: $data['ratingAwaitCount'] ?? null,
            ratingRfCritics: $data['ratingRfCritics'] ?? null,
            ratingRfCriticsVoteCount: $data['ratingRfCriticsVoteCount'] ?? null,
            webUrl: $data['webUrl'] ?? null,
            year: $data['year'] ?? null,
            filmLength: $data['filmLength'] ?? null,
            slogan: $data['slogan'] ?? null,
            description: $data['description'] ?? null,
            shortDescription: $data['shortDescription'] ?? null,
            editorAnnotation: $data['editorAnnotation'] ?? null,
            isTicketsAvailable: $data['isTicketsAvailable'] ?? null,
            productionStatus: $data['productionStatus'] ?? null,
            type: $data['type'],
            ratingMpaa: $data['ratingMpaa'] ?? null,
            ratingAgeLimits: $data['ratingAgeLimits'] ?? null,
            hasImax: $data['hasImax'] ?? null,
            has3D: $data['has3D'] ?? null,
            lastSync: $data['lastSync'] ?? null,
            countries: $data['countries'],
            genres: $data['genres'],
            startYear: $data['startYear'] ?? null,
            endYear: $data['endYear'] ?? null,
            serial: $data['serial'] ?? null,
            shortFilm: $data['shortFilm'] ?? null,
            completed: $data['completed'] ?? null
        );
    }

    /**
     * Получает название фильма на русском языке или английском, если русское недоступно
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
    }

    /**
     * Проверяет, является ли фильм сериалом
     *
     * @return bool
     */
    public function isSerial(): bool
    {
        return in_array($this->type, ['TV_SERIES', 'MINI_SERIES', 'TV_SHOW'], true);
    }

    /**
     * Получает основной рейтинг фильма
     *
     * @return float|null
     */
    public function getMainRating(): ?float
    {
        return $this->ratingKinopoisk ?? $this->ratingImdb ?? $this->ratingFilmCritics;
    }
} 