<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Staff;

/**
 * Сервис для работы с персоналом фильмов в Kinopoisk API
 *
 * Предоставляет методы для получения информации о персонале фильмов:
 * актерах, режиссерах, сценаристах и других участниках съемочной группы.
 *
 * Основные возможности:
 * - Получение списка персонала фильма
 * - Фильтрация по профессиям
 * - Работа с детальной информацией о ролях
 *
 * @package NotKinopoisk\Services
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\AbstractService
 * @see     \NotKinopoisk\Models\Staff
 *
 * @example
 * ```php
 * $client = new Client('api-key');
 * $staffService = $client->staff;
 *
 * // Получение персонала фильма
 * $staff = $staffService->getByFilmId(301);
 *
 * // Фильтрация по профессии
 * $actors = array_filter($staff, fn($person) => $person->professionKey === 'ACTOR');
 * ```
 */
class StaffService extends AbstractService {

	/**
	 * Получает персонал фильма по ID фильма
	 *
	 * READ операция - извлекает список всех участников съемочной группы
	 * фильма: актеров, режиссеров, сценаристов, операторов и других.
	 *
	 * @param   int  $filmId  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\Staff[] Массив участников съемочной группы
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $staff = $staffService->getByFilmId(301);
	 *
	 * foreach ($staff as $person) {
	 *     echo "{$person->nameRu} - {$person->professionText}\n";
	 *     if ($person->description) {
	 *         echo "  Роль: {$person->description}\n";
	 *     }
	 * }
	 * ```
	 */
	public function getByFilmId(int $filmId): array {
		$data = $this->get($this->buildV1Uri("staff"), ['filmId' => $filmId]);

		return array_map(fn ($staffData) => Staff::fromArray($staffData), $data);
	}

}