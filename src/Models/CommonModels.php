<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель страны
 */
class Country
{
    public function __construct(
        public readonly string $country
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self($data['country']);
    }
}

/**
 * Модель жанра
 */
class Genre
{
    public function __construct(
        public readonly string $genre
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self($data['genre']);
    }
}

/**
 * Модель видео
 */
class Video
{
    public function __construct(
        public readonly string $url,
        public readonly string $name,
        public readonly string $site
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            name: $data['name'],
            site: $data['site']
        );
    }
}

/**
 * Модель изображения
 */
class Image
{
    public function __construct(
        public readonly string $imageUrl,
        public readonly string $previewUrl
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            imageUrl: $data['imageUrl'],
            previewUrl: $data['previewUrl']
        );
    }
}

/**
 * Модель похожего фильма
 */
class RelatedFilm
{
    public function __construct(
        public readonly int $filmId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly string $relationType
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            filmId: $data['filmId'] ?? $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            relationType: $data['relationType']
        );
    }

    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
    }
}

/**
 * Модель рецензии
 */
class Review
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly string $type,
        public readonly string $date,
        public readonly int $positiveRating,
        public readonly int $negativeRating,
        public readonly string $author,
        public readonly ?string $title,
        public readonly string $description
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            type: $data['type'],
            date: $data['date'],
            positiveRating: $data['positiveRating'],
            negativeRating: $data['negativeRating'],
            author: $data['author'],
            title: $data['title'] ?? null,
            description: $data['description']
        );
    }
}

/**
 * Модель внешнего источника
 */
class ExternalSource
{
    public function __construct(
        public readonly string $url,
        public readonly string $platform,
        public readonly string $logoUrl,
        public readonly int $positiveRating,
        public readonly int $negativeRating,
        public readonly string $author,
        public readonly ?string $title,
        public readonly string $description
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            platform: $data['platform'],
            logoUrl: $data['logoUrl'],
            positiveRating: $data['positiveRating'],
            negativeRating: $data['negativeRating'],
            author: $data['author'],
            title: $data['title'] ?? null,
            description: $data['description']
        );
    }
}

/**
 * Модель премьеры
 */
class Premiere
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly int $year,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?int $duration,
        public readonly string $premiereRu
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            year: $data['year'],
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            countries: array_map(fn($country) => Country::fromArray($country), $data['countries']),
            genres: array_map(fn($genre) => Genre::fromArray($genre), $data['genres']),
            duration: $data['duration'] ?? null,
            premiereRu: $data['premiereRu']
        );
    }

    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? 'Без названия';
    }
}

/**
 * Модель фильтров
 */
class Filters
{
    public function __construct(
        public readonly array $genres,
        public readonly array $countries
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            genres: $data['genres'],
            countries: $data['countries']
        );
    }
} 