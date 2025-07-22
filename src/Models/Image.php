<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель изображения
 */
class Image
{
    public function __construct(
        public readonly string $imageUrl,
        public readonly string $previewUrl
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            imageUrl: $data['imageUrl'],
            previewUrl: $data['previewUrl']
        );
    }
} 