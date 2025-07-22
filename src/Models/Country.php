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
        return new self(
            country: $data['country']
        );
    }
} 