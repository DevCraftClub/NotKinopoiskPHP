<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель результата поиска персон из Kinopoisk API
 * 
 * Представляет результат поиска персон с пагинацией,
 * включая массив найденных персон и общее количество результатов.
 * 
 * Основные возможности:
 * - Хранение результатов поиска персон в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для работы с результатами поиска
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\PersonService
 * @see \NotKinopoisk\Models\Person
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $searchResult = PersonSearchResult::fromArray($apiData);
 * 
 * // Работа с результатами
 * echo "Найдено персон: {$searchResult->getCount()}\n";
 * echo "Всего результатов: {$searchResult->total}\n";
 * 
 * if (!$searchResult->isEmpty()) {
 *     foreach ($searchResult->items as $person) {
 *         echo "- {$person->getDisplayName()}\n";
 *     }
 * }
 * ```
 */
class PersonSearchResult
{
    /**
     * Конструктор модели результата поиска персон
     * 
     * Создает новый экземпляр результата поиска с массивом персон и общим количеством.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param array $items Массив объектов Person в результатах поиска
     * @param int $total Общее количество персон (всего в базе данных)
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
     * Создает экземпляр результата поиска персон из массива данных API
     * 
     * Статический метод для удобного создания объекта PersonSearchResult из данных,
     * полученных от Kinopoisk API. Автоматически создает объекты Person для каждого элемента.
     * 
     * @param array $data Массив данных результата поиска от API
     * 
     * @return self Новый экземпляр результата поиска персон
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'items' => [
     *         ['personId' => 123, 'nameRu' => 'Том Круз', ...],
     *         ['personId' => 124, 'nameRu' => 'Том Хэнкс', ...]
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
     * Получает количество персон в текущих результатах поиска
     * 
     * Возвращает количество персон в текущей странице результатов,
     * а не общее количество найденных персон.
     * 
     * @return int Количество персон в текущих результатах
     * 
     * @example
     * ```php
     * echo "Найдено на текущей странице: {$searchResult->getCount()} персон";
     * ```
     */
    public function getCount(): int
    {
        return count($this->items);
    }

    /**
     * Проверяет, пуст ли результат поиска
     * 
     * Определяет, содержит ли результат поиска какие-либо персоны
     * на текущей странице.
     * 
     * @return bool true если результат пуст, false в противном случае
     * 
     * @example
     * ```php
     * if ($searchResult->isEmpty()) {
     *     echo "Персоны не найдены";
     * } else {
     *     echo "Найдено {$searchResult->getCount()} персон";
     * }
     * ```
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }
} 