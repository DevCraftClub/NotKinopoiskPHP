<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Models\Person;

/**
 * Модель результатов поиска персон
 *
 * Представляет результаты поиска персон в Kinopoisk API.
 * Содержит массив найденных персон и общее количество результатов.
 *
 * @package NotKinopoisk\Models
 * @author  Maxim Harder
 * @version 1.0.0
 * @since   1.0.0
 */
class PersonSearchResult
{
    /**
     * Конструктор модели результатов поиска персон
     * 
     * Создает новый экземпляр результатов поиска со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param array $items Массив найденных персон
     * @param int $total Общее количество результатов поиска
     * 
     * @example
     * ```php
     * $searchResult = new PersonSearchResult(
     *     items: [$person1, $person2, $person3],
     *     total: 150
     * );
     * ```
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total
    ) {
    }

    /**
     * Создает экземпляр результатов поиска из массива данных API
     * 
     * Статический метод для удобного создания объекта PersonSearchResult из данных,
     * полученных от Kinopoisk API. Автоматически создает объекты Person
     * для каждого элемента в массиве items.
     * 
     * @param array $data Массив данных результатов поиска от API
     * 
     * @return self Новый экземпляр результатов поиска
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'items' => [
     *         ['kinopoiskId' => 123, 'nameRu' => 'Актер 1', ...],
     *         ['kinopoiskId' => 456, 'nameRu' => 'Актер 2', ...]
     *     ],
     *     'total' => 150
     * ];
     * 
     * $searchResult = PersonSearchResult::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            items: array_map(fn($personData) => Person::fromArray($personData), $data['items']),
            total: $data['total']
        );
    }

    /**
     * Получает количество найденных персон
     * 
     * Возвращает количество персон в текущей странице результатов.
     * 
     * @return int Количество найденных персон
     * 
     * @example
     * ```php
     * echo "Найдено персон: {$searchResult->getCount()}";
     * ```
     */
    public function getCount(): int
    {
        return count($this->items);
    }

    /**
     * Проверяет, пусты ли результаты поиска
     * 
     * Определяет, содержит ли результат поиска какие-либо персоны.
     * 
     * @return bool true если результат пуст, false в противном случае
     * 
     * @example
     * ```php
     * if ($searchResult->isEmpty()) {
     *     echo "Персоны не найдены";
     * } else {
     *     echo "Найдено персон: {$searchResult->getCount()}";
     * }
     * ```
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }
} 