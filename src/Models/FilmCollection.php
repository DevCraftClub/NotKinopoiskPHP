<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель коллекции фильмов
 */
class FilmCollection
{
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $totalPages
    ) {
    }

    /**
     * Создает экземпляр коллекции фильмов из массива данных API
     *
     * @param array $data Данные коллекции
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $items = [];
        if (isset($data['items'])) {
            $items = array_map(fn($filmData) => Film::fromArray($filmData), $data['items']);
        } elseif (isset($data['films'])) {
            $items = array_map(fn($filmData) => Film::fromArray($filmData), $data['films']);
        }

        return new self(
            items: $items,
            total: $data['total'] ?? $data['searchFilmsCountResult'] ?? 0,
            totalPages: $data['totalPages'] ?? $data['pagesCount'] ?? 1
        );
    }

    /**
     * Получает количество фильмов в коллекции
     *
     * @return int
     */
    public function getCount(): int
    {
        return count($this->items);
    }

    /**
     * Проверяет, пуста ли коллекция
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Получает первый фильм из коллекции
     *
     * @return Film|null
     */
    public function getFirst(): ?Film
    {
        return $this->items[0] ?? null;
    }

    /**
     * Получает последний фильм из коллекции
     *
     * @return Film|null
     */
    public function getLast(): ?Film
    {
        return $this->items[count($this->items) - 1] ?? null;
    }
} 