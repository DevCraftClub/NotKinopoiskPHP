<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

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
        return new self(
            genre: $data['genre']
        );
    }
} 