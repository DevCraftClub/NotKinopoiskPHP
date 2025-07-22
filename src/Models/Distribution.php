<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель данных о прокате фильма
 */
class Distribution
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $subType,
        public readonly ?string $date,
        public readonly ?bool $reRelease,
        public readonly ?Country $country,
        public readonly array $companies
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            subType: $data['subType'] ?? null,
            date: $data['date'] ?? null,
            reRelease: $data['reRelease'] ?? null,
            country: isset($data['country']) ? Country::fromArray($data['country']) : null,
            companies: $data['companies'] ?? []
        );
    }
} 