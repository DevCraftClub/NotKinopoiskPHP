<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\ProfessionKey;

/**
 * Модель фильма персоны из Kinopoisk API
 *
 * Представляет информацию о фильме, в котором участвовала персона
 * (актер, режиссер, сценарист и т.д.), полученную из Kinopoisk API.
 * Содержит данные о роли персоны в фильме.
 *
 * Основные возможности:
 * - Хранение информации о фильме в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для работы с названиями фильмов
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Person
 * @see     \NotKinopoisk\Services\PersonService
 * @see     \NotKinopoisk\Enums\ProfessionKey
 * @api     /api/v1/persons/{id}
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/persons/get_api_v1_persons__id_
 *
 * @example
 * ```php
 * // Создание из данных API
 * $film = PersonFilm::fromArray($apiData);
 *
 * // Получение отображаемого названия
 * echo $film->getDisplayName();
 *
 * // Проверка роли
 * if ($film->isActor()) {
 *     echo "Актерская роль";
 * }
 * ```
 */
class PersonFilm {

	/**
	 * Конструктор модели фильма персоны
	 *
	 * Создает новый экземпляр фильма персоны со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int                                $filmId         Уникальный идентификатор фильма в Кинопоиске
	 * @param   string|null                        $nameRu         Название фильма на русском языке
	 * @param   string|null                        $nameEn         Название фильма на английском языке
	 * @param   string|null                        $rating         Рейтинг фильма
	 * @param   bool                               $general        Является ли фильм общим (не специфичным для персоны)
	 * @param   string|null                        $description    Описание роли персоны в фильме
	 * @param   \NotKinopoisk\Enums\ProfessionKey  $professionKey  Ключ профессии персоны в фильме
	 *
	 * @example
	 * ```php
	 * $film = new PersonFilm(
	 *     filmId: 32169,
	 *     nameRu: 'Солист',
	 *     nameEn: 'The Soloist',
	 *     rating: '7.2',
	 *     general: false,
	 *     description: 'Steve Lopez',
	 *     professionKey: ProfessionKey::ACTOR
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int           $filmId,
		public readonly ?string       $nameRu,
		public readonly ?string       $nameEn,
		public readonly ?string       $rating,
		public readonly bool          $general,
		public readonly ?string       $description,
		public readonly ProfessionKey $professionKey,
	) {}

	/**
	 * Создает экземпляр фильма персоны из массива данных API
	 *
	 * Статический метод для удобного создания объекта PersonFilm из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и устанавливает значения по умолчанию.
	 *
	 * @param   array  $data  Массив данных фильма от API
	 *
	 * @return self Новый экземпляр фильма персоны
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'filmId' => 32169,
	 *     'nameRu' => 'Солист',
	 *     'nameEn' => 'The Soloist',
	 *     'rating' => '7.2',
	 *     'general' => false,
	 *     'description' => 'Steve Lopez',
	 *     'professionKey' => 'ACTOR'
	 * ];
	 *
	 * $film = PersonFilm::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			filmId       : $data['filmId'],
			nameRu       : $data['nameRu'] ?? NULL,
			nameEn       : $data['nameEn'] ?? NULL,
			rating       : $data['rating'] ?? NULL,
			general      : $data['general'] ?? FALSE,
			description  : $data['description'] ?? NULL,
			professionKey: ProfessionKey::from($data['professionKey']),
		);
	}

	/**
	 * Проверяет, является ли персона актером в этом фильме
	 *
	 * @return bool true если актер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isActor()) {
	 *     echo "Актерская роль";
	 * }
	 * ```
	 */
	public function isActor(): bool {
		return $this->professionKey->isActor();
	}

	/**
	 * Проверяет, является ли персона режиссером в этом фильме
	 *
	 * @return bool true если режиссер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isDirector()) {
	 *     echo "Режиссерская работа";
	 * }
	 * ```
	 */
	public function isDirector(): bool {
		return $this->professionKey->isDirector();
	}

	/**
	 * Проверяет, является ли персона сценаристом в этом фильме
	 *
	 * @return bool true если сценарист, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isWriter()) {
	 *     echo "Сценарная работа";
	 * }
	 * ```
	 */
	public function isWriter(): bool {
		return $this->professionKey->isWriter();
	}

	/**
	 * Проверяет, является ли персона продюсером в этом фильме
	 *
	 * @return bool true если продюсер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isProducer()) {
	 *     echo "Продюсерская работа";
	 * }
	 * ```
	 */
	public function isProducer(): bool {
		return $this->professionKey->isProducer();
	}

	/**
	 * Проверяет, является ли фильм общим
	 *
	 * Общие фильмы - это те, которые не специфичны для конкретной персоны,
	 * а показывают общую фильмографию.
	 *
	 * @return bool true если общий фильм, false если специфичный
	 *
	 * @example
	 * ```php
	 * if ($film->isGeneral()) {
	 *     echo "Общий фильм";
	 * } else {
	 *     echo "Специфичный для персоны";
	 * }
	 * ```
	 */
	public function isGeneral(): bool {
		return $this->general;
	}

	/**
	 * Получает полную информацию о фильме
	 *
	 * Возвращает строку с полной информацией о фильме, включая название,
	 * профессию, рейтинг и описание роли.
	 *
	 * @return string Полная информация о фильме
	 *
	 * @example
	 * ```php
	 * echo $film->getFullInfo();
	 * // "Солист (Актер, 7.2) - Steve Lopez"
	 * ```
	 */
	public function getFullInfo(): string {
		$name        = $this->getDisplayName();
		$profession  = $this->getProfessionName();
		$rating      = $this->getRatingInfo();
		$description = $this->description ? " - {$this->description}" : '';

		return "{$name} ({$profession}, {$rating}){$description}";
	}

	/**
	 * Получает отображаемое название фильма
	 *
	 * Возвращает наиболее подходящее название для отображения пользователю.
	 * Приоритет: русское название → английское название → "Без названия"
	 *
	 * @return string Отображаемое название фильма
	 *
	 * @example
	 * ```php
	 * echo $film->getDisplayName(); // "Солист" или "The Soloist" или "Без названия"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? 'Без названия';
	}

	/**
	 * Получает человекочитаемое название профессии
	 *
	 * Возвращает название профессии на русском языке.
	 *
	 * @return string Название профессии
	 *
	 * @example
	 * ```php
	 * echo $film->getProfessionName(); // "Актер", "Режиссер", "Сценарист"
	 * ```
	 */
	public function getProfessionName(): string {
		return $this->professionKey->getDisplayName();
	}

	/**
	 * Получает информацию о рейтинге в виде строки
	 *
	 * Возвращает рейтинг или "Нет рейтинга", если рейтинг отсутствует.
	 *
	 * @return string Информация о рейтинге
	 *
	 * @example
	 * ```php
	 * echo "Рейтинг: {$film->getRatingInfo()}"; // "7.2" или "Нет рейтинга"
	 * ```
	 */
	public function getRatingInfo(): string {
		return $this->rating ?? 'Нет рейтинга';
	}

	/**
	 * Проверяет, является ли профессия творческой
	 *
	 * @return bool true если творческая профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isCreativeProfession()) {
	 *     echo "Творческая работа";
	 * }
	 * ```
	 */
	public function isCreativeProfession(): bool {
		return $this->professionKey->isCreative();
	}

	/**
	 * Проверяет, является ли профессия технической
	 *
	 * @return bool true если техническая профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isTechnicalProfession()) {
	 *     echo "Техническая работа";
	 * }
	 * ```
	 */
	public function isTechnicalProfession(): bool {
		return $this->professionKey->isTechnical();
	}

	/**
	 * Проверяет, является ли профессия управленческой
	 *
	 * @return bool true если управленческая профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isManagementProfession()) {
	 *     echo "Управленческая работа";
	 * }
	 * ```
	 */
	public function isManagementProfession(): bool {
		return $this->professionKey->isManagement();
	}

	/**
	 * Проверяет, является ли профессия специальной
	 *
	 * @return bool true если специальная профессия, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($film->isSpecialProfession()) {
	 *     echo "Специальная роль";
	 * }
	 * ```
	 */
	public function isSpecialProfession(): bool {
		return $this->professionKey->isSpecial();
	}

	/**
	 * Получает категорию профессии
	 *
	 * @return string Категория профессии
	 *
	 * @example
	 * ```php
	 * echo $film->getProfessionCategory(); // "Творческая", "Техническая", etc.
	 * ```
	 */
	public function getProfessionCategory(): string {
		return $this->professionKey->getCategory();
	}

} 