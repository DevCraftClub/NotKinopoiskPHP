<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель персоны (актер, режиссер и т.д.)
 */
class Person
{
    public function __construct(
        public readonly ?int $personId,
        public readonly ?string $webUrl,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $sex,
        public readonly string $posterUrl,
        public readonly ?string $growth,
        public readonly ?string $birthday,
        public readonly ?string $death,
        public readonly ?int $age,
        public readonly ?string $birthplace,
        public readonly ?string $deathplace,
        public readonly ?int $hasAwards,
        public readonly ?string $profession,
        public readonly array $facts,
        public readonly array $spouses,
        public readonly array $films
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            personId: $data['personId'] ?? null,
            webUrl: $data['webUrl'] ?? null,
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            sex: $data['sex'] ?? null,
            posterUrl: $data['posterUrl'],
            growth: $data['growth'] ?? null,
            birthday: $data['birthday'] ?? null,
            death: $data['death'] ?? null,
            age: $data['age'] ?? null,
            birthplace: $data['birthplace'] ?? null,
            deathplace: $data['deathplace'] ?? null,
            hasAwards: $data['hasAwards'] ?? null,
            profession: $data['profession'] ?? null,
            facts: $data['facts'] ?? [],
            spouses: $data['spouses'] ?? [],
            films: $data['films'] ?? []
        );
    }

    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? 'Без имени';
    }

    public function isAlive(): bool
    {
        return $this->death === null;
    }
} 