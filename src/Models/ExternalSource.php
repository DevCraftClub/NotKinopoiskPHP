<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель внешнего источника
 */
class ExternalSource
{
    public function __construct(
        public readonly string $url,
        public readonly string $platform,
        public readonly string $logoUrl,
        public readonly ?int $positiveRating,
        public readonly ?int $negativeRating,
        public readonly ?string $author,
        public readonly ?string $title,
        public readonly ?string $description
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            platform: $data['platform'],
            logoUrl: $data['logoUrl'],
            positiveRating: $data['positiveRating'] ?? null,
            negativeRating: $data['negativeRating'] ?? null,
            author: $data['author'] ?? null,
            title: $data['title'] ?? null,
            description: $data['description'] ?? null
        );
    }
} 