<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

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