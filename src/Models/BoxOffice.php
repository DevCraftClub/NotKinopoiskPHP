<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель данных о бюджете и сборах фильма из Kinopoisk API
 * 
 * Представляет финансовую информацию о фильме, включая бюджет,
 * сборы в различных регионах (Россия, США, мир) и валюту.
 * 
 * Основные возможности:
 * - Хранение финансовых данных в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Форматирование сумм с валютой
 * - Определение типа финансовых данных
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $boxOffice = BoxOffice::fromArray($apiData);
 * 
 * // Работа с финансовыми данными
 * if ($boxOffice->isBudget()) {
 *     echo "Бюджет: {$boxOffice->getFormattedAmount()}\n";
 * } elseif ($boxOffice->isRevenue()) {
 *     echo "Сборы {$boxOffice->name}: {$boxOffice->getFormattedAmount()}\n";
 * }
 * ```
 */
class BoxOffice
{
    /**
     * Конструктор модели финансовых данных
     * 
     * Создает новый экземпляр финансовых данных со всеми необходимыми параметрами.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $type Тип данных (BUDGET, RUS, USA, WORLD)
     * @param int $amount Сумма в минимальных единицах валюты
     * @param string $currencyCode Код валюты (USD, RUB и т.д.)
     * @param string $name Название типа данных
     * @param string $symbol Символ валюты ($, ₽ и т.д.)
     * 
     * @example
     * ```php
     * $boxOffice = new BoxOffice(
     *     type: 'BUDGET',
     *     amount: 100000000,
     *     currencyCode: 'USD',
     *     name: 'Бюджет',
     *     symbol: '$'
     * );
     * ```
     */
    public function __construct(
        public readonly string $type,
        public readonly int $amount,
        public readonly string $currencyCode,
        public readonly string $name,
        public readonly string $symbol
    ) {
    }

    /**
     * Создает экземпляр финансовых данных из массива данных API
     * 
     * Статический метод для удобного создания объекта BoxOffice из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив финансовых данных от API
     * 
     * @return self Новый экземпляр финансовых данных
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'type' => 'BUDGET',
     *     'amount' => 100000000,
     *     'currencyCode' => 'USD',
     *     'name' => 'Бюджет',
     *     'symbol' => '$'
     * ];
     * 
     * $boxOffice = BoxOffice::fromArray($apiData);
     * ```
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
     * Возвращает сумму, отформатированную с разделителями тысяч
     * и символом валюты для удобного отображения.
     * 
     * @return string Отформатированная сумма с валютой
     * 
     * @example
     * ```php
     * echo $boxOffice->getFormattedAmount(); // "$100 000 000" или "₽1 500 000 000"
     * ```
     */
    public function getFormattedAmount(): string
    {
        return $this->symbol . number_format($this->amount, 0, '.', ' ');
    }

    /**
     * Проверяет, является ли это бюджетом
     * 
     * Определяет, относятся ли финансовые данные к бюджету фильма.
     * 
     * @return bool true если это бюджет, false в противном случае
     * 
     * @example
     * ```php
     * if ($boxOffice->isBudget()) {
     *     echo "Бюджет фильма: {$boxOffice->getFormattedAmount()}";
     * }
     * ```
     */
    public function isBudget(): bool
    {
        return $this->type === 'BUDGET';
    }

    /**
     * Проверяет, является ли это сборами
     * 
     * Определяет, относятся ли финансовые данные к сборам фильма
     * в различных регионах (Россия, США, мир).
     * 
     * @return bool true если это сборы, false в противном случае
     * 
     * @example
     * ```php
     * if ($boxOffice->isRevenue()) {
     *     echo "Сборы {$boxOffice->name}: {$boxOffice->getFormattedAmount()}";
     * }
     * ```
     */
    public function isRevenue(): bool
    {
        return $this->type === 'RUS' || $this->type === 'USA' || $this->type === 'WORLD';
    }
} 