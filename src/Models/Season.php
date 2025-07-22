<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель сезона сериала
 */
class Season
{
    public function __construct(
        public readonly int $number,
        public readonly array $episodes
    ) {
    }

    /**
     * Создает экземпляр сезона из массива данных API
     *
     * @param array $data Данные сезона
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            number: $data['number'],
            episodes: array_map(fn($episodeData) => Episode::fromArray($episodeData), $data['episodes'])
        );
    }
} 