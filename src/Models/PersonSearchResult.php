<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель результата поиска персон
 */
class PersonSearchResult
{
    public function __construct(
        public readonly array $items,
        public readonly int $total
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            items: array_map(fn($personData) => Person::fromArray($personData), $data['items']),
            total: $data['total']
        );
    }

    public function getCount(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
} 