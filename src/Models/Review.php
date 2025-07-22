<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

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