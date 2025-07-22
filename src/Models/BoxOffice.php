<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель данных о бюджете и сборах фильма
 */
class BoxOffice
{
    public function __construct(
        public readonly string $type,
        public readonly int $amount,
        public readonly string $currencyCode,
        public readonly string $name,
        public readonly string $symbol
    ) {
    }

    /**
     * Создает экземпляр данных о бюджете из массива данных API
     *
     * @param array $data Данные о бюджете
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            amount: $data['amount'],
            currencyCode: $data['currencyCode'],
            name: $data['name'],
            symbol: $data['symbol']
        );
    }

    /**
     * Получает отформатированную сумму с валютой
     *
     * @return string
     */
    public function getFormattedAmount(): string
    {
        return $this->symbol . number_format($this->amount, 0, '.', ' ');
    }

    /**
     * Проверяет, является ли это бюджетом
     *
     * @return bool
     */
    public function isBudget(): bool
    {
        return $this->type === 'BUDGET';
    }

    /**
     * Проверяет, является ли это сборами
     *
     * @return bool
     */
    public function isRevenue(): bool
    {
        return $this->type === 'RUS' || $this->type === 'USA' || $this->type === 'WORLD';
    }
} 