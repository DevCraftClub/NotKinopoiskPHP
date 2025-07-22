<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель медиа поста
 */
class MediaPost
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly string $imageUrl,
        public readonly string $title,
        public readonly string $description,
        public readonly string $url,
        public readonly string $publishedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            imageUrl: $data['imageUrl'],
            title: $data['title'],
            description: $data['description'],
            url: $data['url'],
            publishedAt: $data['publishedAt']
        );
    }
} 