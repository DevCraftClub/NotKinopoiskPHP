<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель эпизода сериала
 */
class Episode
{
    public function __construct(
        public readonly int $seasonNumber,
        public readonly int $episodeNumber,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $synopsis,
        public readonly ?string $releaseDate
    ) {
    }

    /**
     * Создает экземпляр эпизода из массива данных API
     *
     * @param array $data Данные эпизода
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            seasonNumber: $data['seasonNumber'],
            episodeNumber: $data['episodeNumber'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            synopsis: $data['synopsis'] ?? null,
            releaseDate: $data['releaseDate'] ?? null
        );
    }

    /**
     * Получает название эпизода на русском языке или английском, если русское недоступно
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? "Эпизод {$this->episodeNumber}";
    }
} 