<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\PersonByNameResult;
use NotKinopoisk\Models\PersonSearchResult;
use NotKinopoisk\Models\Staff;
use NotKinopoisk\Responses\MovieStaffResponse;
use NotKinopoisk\Responses\PaginatedResponse;

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
	 * @api /api/v1/persons
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
	public function searchByName(string $name, int $page = 1): PaginatedResponse {
		$data = $this->get($this->buildUri("persons"), [
			'name' => $name,
		]);

		$response              = PaginatedResponse::fromArray($data, PersonByNameResult::class);
		$response->currentPage = $page;
		$response->totalPages  = 2;

		return $response;
	}

	/**
	 * Получает детальную информацию о персоне по ID
	 *
	 * Извлекает полную информацию о персоне из API.
	 * Включает биографию, фильмографию, награды и другие данные.
	 *
	 * @api /api/v1/staff/{id}
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

	/**
	 * Получает персонал фильма (актеры, режиссеры и другие участники)
	 *
	 * Извлекает полную информацию о персонале, участвовавшем в создании фильма.
	 * Включает актеров, режиссеров, сценаристов, продюсеров, операторов и других
	 * участников съемочного процесса.
	 *
	 * Метод автоматически преобразует массив данных от API в массив объектов Staff,
	 * предоставляя удобный интерфейс для работы с информацией о персонале.
	 *
	 * @api    /api/v1/staff
	 *
	 * @param   int  $filmId  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\MovieStaffResponse Массив объектов персонала фильма
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При общих ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException При неверном или недействительном API ключе
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException При превышении лимита запросов
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException При отсутствии фильма с указанным ID
	 * @example
	 * ```php
	 * // Получение персонала фильма "Матрица" (ID: 301)
	 * $staff = $personService->getFilmStaff(301);
	 *
	 * echo "Всего участников: " . count($staff) . "\n";
	 *
	 * foreach ($staff as $person) {
	 *     echo "{$person->getDisplayName()} - {$person->professionText}";
	 *
	 *     if ($person->description) {
	 *         echo " ({$person->description})";
	 *     }
	 *     echo "\n";
	 * }
	 *
	 * // Фильтрация по типу профессии
	 * $actors = array_filter($staff, fn($person) => $person->isActor());
	 * $directors = array_filter($staff, fn($person) => $person->isDirector());
	 *
	 * echo "Актеров: " . count($actors) . "\n";
	 * echo "Режиссеров: " . count($directors) . "\n";
	 * ```
	 */
	public function getFilmStaff(int $filmId): MovieStaffResponse {
		$data = $this->get($this->buildUri("staff"), ['filmId' => $filmId]);

		return MovieStaffResponse::fromArray($data, Staff::class);
	}

}