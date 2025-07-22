<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель факта или ошибки в фильме
 */
class Fact
{
    public function __construct(
        public readonly string $text,
        public readonly string $type,
        public readonly bool $spoiler
    ) {
    }

    /**
     * Создает экземпляр факта из массива данных API
     *
     * @param array $data Данные факта
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'],
            type: $data['type'],
            spoiler: $data['spoiler']
        );
    }

    /**
     * Проверяет, является ли факт ошибкой в фильме
     *
     * @return bool
     */
    public function isBlooper(): bool
    {
        return $this->type === 'BLOOPER';
    }

    /**
     * Проверяет, является ли факт интересным фактом
     *
     * @return bool
     */
    public function isFact(): bool
    {
        return $this->type === 'FACT';
    }
} 