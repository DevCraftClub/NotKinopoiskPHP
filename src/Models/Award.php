<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель награды
 */
class Award
{
    public function __construct(
        public readonly string $name,
        public readonly bool $win,
        public readonly ?string $imageUrl,
        public readonly string $nominationName,
        public readonly int $year,
        public readonly array $persons
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            win: $data['win'],
            imageUrl: $data['imageUrl'] ?? null,
            nominationName: $data['nominationName'],
            year: $data['year'],
            persons: $data['persons'] ?? []
        );
    }
} 