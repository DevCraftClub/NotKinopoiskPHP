<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель персонала фильма
 */
class Staff
{
    public function __construct(
        public readonly int $staffId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $description,
        public readonly string $posterUrl,
        public readonly string $professionText,
        public readonly string $professionKey
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            staffId: $data['staffId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            description: $data['description'] ?? null,
            posterUrl: $data['posterUrl'],
            professionText: $data['professionText'],
            professionKey: $data['professionKey']
        );
    }

    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? 'Без имени';
    }

    public function isActor(): bool
    {
        return $this->professionKey === 'ACTOR';
    }

    public function isDirector(): bool
    {
        return $this->professionKey === 'DIRECTOR';
    }

    public function isWriter(): bool
    {
        return $this->professionKey === 'WRITER';
    }
} 