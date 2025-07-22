<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\PersonSearchResult;

/**
 * Сервис для работы с персонами (актерами, режиссерами и т.д.)
 * Реализует CRUD операции: Create (поиск), Read (получение)
 */
class PersonService extends AbstractService
{
    /**
     * Поиск персон по имени
     * CREATE операция (создание поискового запроса)
     *
     * @param string $name Имя для поиска
     * @param int $page Номер страницы
     * @return PersonSearchResult
     */
    public function searchByName(string $name, int $page = 1): PersonSearchResult
    {
        $data = $this->get($this->buildV1Uri("persons"), [
            'name' => $name,
            'page' => $page
        ]);
        return PersonSearchResult::fromArray($data);
    }

    /**
     * Получает информацию о персоне по ID
     * READ операция
     *
     * @param int $id ID персоны в Кинопоиске
     * @return Person
     */
    public function getById(int $id): Person
    {
        $data = $this->get($this->buildV1Uri("staff/{$id}"));
        return Person::fromArray($data);
    }
} 