<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

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