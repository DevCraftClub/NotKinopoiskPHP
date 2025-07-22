<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель видео
 */
class Video
{
    public function __construct(
        public readonly string $url,
        public readonly string $name,
        public readonly string $site
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            name: $data['name'],
            site: $data['site']
        );
    }
} 