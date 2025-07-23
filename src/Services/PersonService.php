<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\PersonSearchResult;

/**
 * Сервис для работы с персонами в Kinopoisk API
 *
 * Предоставляет методы для получения информации о персонах (актерах, режиссерах,
 * сценаристах и т.д.) из Kinopoisk API. Поддерживает поиск персон и получение
 * детальной информации о конкретной персоне.
 *
 * Основные возможности:
 * - Поиск персон по различным критериям
 * - Получение детальной информации о персоне
 * - Поддержка пагинации результатов поиска
 * - Обработка ошибок API
 *
 * @package NotKinopoisk\Services
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Person
 * @see     \NotKinopoisk\Models\PersonSearchResult
 * @see     \NotKinopoisk\Services\HttpClient
 * @api     /api/v1/persons, /api/v1/persons/{id}
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/persons
 *
 * @example
 * ```php
 * $personService = new PersonService($httpClient);
 *
 * // Поиск персон
 * $searchResult = $personService->search('Том Круз');
 *
 * // Получение информации о персоне
 * $person = $personService->getById(12345);
 * ```
 */
class PersonService extends AbstractService {

	/**
	 * Поиск персон по имени
	 *
	 * CREATE операция - создает поисковый запрос для поиска персон
	 * по имени или части имени. Возвращает список найденных персон.
	 *
	 * @param   string  $name  Имя или часть имени для поиска
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
	public function searchByName(string $name): PersonSearchResult {
		$data = $this->get($this->buildV1Uri("persons"), [
			'name' => $name,
		]);

		return PersonSearchResult::fromArray($data);
	}

	/**
	 * Получает детальную информацию о персоне по ID
	 *
	 * READ операция - извлекает полную информацию о персоне из API.
	 * Включает биографию, фильмографию, награды и другие данные.
	 *
	 * @param   int  $id  Уникальный идентификатор персоны в Кинопоиске
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
	public function getById(int $id): Person {
		$data = $this->get($this->buildUri("staff/{$id}"));

		return Person::fromArray($data);
	}

}