<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\PersonSearchResult;

/**
 * Сервис для работы с персонами в Kinopoisk API
 * 
 * Предоставляет методы для поиска и получения информации о персонах:
 * актерах, режиссерах, сценаристах и других участниках кинопроизводства.
 * 
 * Основные возможности:
 * - Поиск персон по имени
 * - Получение детальной информации о персоне
 * - Работа с фильмографией и биографией
 * 
 * @package NotKinopoisk\Services
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\AbstractService
 * @see \NotKinopoisk\Models\Person
 * @see \NotKinopoisk\Models\PersonSearchResult
 * 
 * @example
 * ```php
 * $client = new Client('api-key');
 * $personService = $client->persons;
 * 
 * // Поиск персоны
 * $results = $personService->searchByName('Том Круз');
 * 
 * // Получение детальной информации
 * if (!$results->isEmpty()) {
 *     $person = $personService->getById($results->items[0]->personId);
 *     echo $person->getDisplayName();
 * }
 * ```
 */
class PersonService extends AbstractService
{
    /**
     * Поиск персон по имени
     * 
     * CREATE операция - создает поисковый запрос для поиска персон
     * по имени или части имени. Возвращает список найденных персон.
     * 
     * @param string $name Имя или часть имени для поиска
     * 
     * @return \NotKinopoisk\Models\PersonSearchResult Результат поиска персон
     * 
     * @throws \NotKinopoisk\Exception\ApiException При ошибках API
     * 
     * @example
     * ```php
     * $results = $personService->searchByName('Том Круз');
     * echo "Найдено персон: {$results->getCount()}\n";
     * 
     * foreach ($results->items as $person) {
     *     echo "- {$person->name} (ID: {$person->personId})\n";
     * }
     * ```
     */
    public function searchByName(string $name): PersonSearchResult
    {
        $data = $this->get($this->buildV1Uri("persons"), [
            'name' => $name
        ]);
        return PersonSearchResult::fromArray($data);
    }

    /**
     * Получает детальную информацию о персоне по ID
     * 
     * READ операция - извлекает полную информацию о персоне из API.
     * Включает биографию, фильмографию, награды и другие данные.
     * 
     * @param int $id Уникальный идентификатор персоны в Кинопоиске
     * 
     * @return \NotKinopoisk\Models\Person Объект персоны с полной информацией
     * 
     * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если персона с указанным ID не найдена
     * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
     * 
     * @example
     * ```php
     * $person = $personService->getById(12345);
     * echo "Имя: " . $person->getDisplayName();
     * echo "Профессия: " . $person->profession;
     * echo "Биография: " . $person->biography;
     * ```
     */
    public function getById(int $id): Person
    {
        $data = $this->get($this->buildUri("staff/{$id}"));
        return Person::fromArray($data);
    }
} 