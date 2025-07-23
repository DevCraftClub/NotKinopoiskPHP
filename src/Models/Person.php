<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель персоны из Kinopoisk API
 *
 * Представляет полную информацию о персоне (актер, режиссер, сценарист и т.д.),
 * полученную из Kinopoisk API. Содержит биографические данные, фильмографию,
 * информацию о супругах и другие детали.
 *
 * Основные возможности:
 * - Хранение полной информации о персоне в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для работы с именами и отображением
 * - Поддержка фильмографии и информации о супругах
 *
 * @package NotKinopoisk\Models
 * @api     /api/v1/staff/{id}
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\PersonService
 * @see     \NotKinopoisk\Models\PersonSpouse
 * @see     \NotKinopoisk\Models\PersonFilm
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/staff/get_api_v1_staff__id_
 *
 * @example
 * ```php
 * // Создание из данных API
 * $person = Person::fromArray($apiData);
 *
 * // Получение отображаемого имени
 * echo $person->getDisplayName();
 *
 * // Работа с фильмографией
 * foreach ($person->films as $film) {
 *     echo $film->getDisplayName();
 * }
 * ```
 */
class Person {

	/**
	 * Конструктор модели персоны
	 *
	 * Создает новый экземпляр персоны со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int|null                             $personId    Уникальный идентификатор персоны в Кинопоиске
	 * @param   string|null                          $nameRu      Имя персоны на русском языке
	 * @param   string|null                          $nameEn      Имя персоны на английском языке
	 * @param   string|null                          $sex         Пол персоны
	 * @param   string|null                          $posterUrl   URL постера/фотографии персоны
	 * @param   string|null                          $growth      Рост персоны
	 * @param   string|null                          $birthday    Дата рождения
	 * @param   string|null                          $death       Дата смерти (если применимо)
	 * @param   int|null                             $age         Возраст персоны
	 * @param   string|null                          $birthplace  Место рождения
	 * @param   string|null                          $deathplace  Место смерти
	 * @param   \NotKinopoisk\Models\PersonSpouse[]  $spouses     Массив информации о супругах
	 * @param   int|null                             $hasAwards   Наличие наград
	 * @param   string|null                          $profession  Профессия персоны
	 * @param   string|null                          $facts       Интересные факты
	 * @param   \NotKinopoisk\Models\PersonFilm[]    $films        Массив информации о фильмах
	 * @param   string|null                          $biography   Биография персоны
	 * @param   string|null                          $births      Информация о рождении
	 * @param   string|null                          $deaths      Информация о смерти
	 * @param   string|null                          $total       Информация о количестве работ
	 *
	 * @example
	 * ```php
	 * $person = new Person(
	 *     personId: 12345,
	 *     nameRu: 'Том Круз',
	 *     nameEn: 'Tom Cruise',
	 *     posterUrl: 'https://...',
	 *     profession: 'Актер',
	 *     biography: 'Биография...',
	 *     spouses: [new PersonSpouse(...)]
	 * );
	 * ```
	 */
	public function __construct(
		public readonly ?int    $personId,
		public readonly ?string $nameRu,
		public readonly ?string $nameEn,
		public readonly ?string $sex,
		public readonly ?string $posterUrl,
		public readonly ?string $growth,
		public readonly ?string $birthday,
		public readonly ?string $death,
		public readonly ?int    $age,
		public readonly ?string $birthplace,
		public readonly ?string $deathplace,
		public readonly array   $spouses,
		public readonly ?int    $hasAwards,
		public readonly ?string $profession,
		public readonly ?string $facts,
		public readonly array   $films,
		public readonly ?string $biography,
		public readonly ?string $births,
		public readonly ?string $deaths,
		public readonly ?string $total,
	) {}

	/**
	 * Создает экземпляр персоны из массива данных API
	 *
	 * Статический метод для удобного создания объекта Person из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и устанавливает значения по умолчанию.
	 *
	 * @param   array  $data  Массив данных персоны от API
	 *
	 * @return self Новый экземпляр персоны
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'personId' => 12345,
	 *     'nameRu' => 'Том Круз',
	 *     'nameEn' => 'Tom Cruise',
	 *     'profession' => 'Актер',
	 *     'biography' => 'Биография...',
	 *     'spouses' => [
	 *         [
	 *             'personId' => 32169,
	 *             'name' => 'Сьюзан Дауни',
	 *             'divorced' => false,
	 *             'sex' => 'FEMALE',
	 *             'children' => 2,
	 *             'webUrl' => 'https://...',
	 *             'relation' => 'супруга'
	 *         ]
	 *     ],
	 *     'films' => [
	 *         [
	 *             'filmId' => 32169,
	 *             'nameRu' => 'Солист',
	 *             'nameEn' => 'The Soloist',
	 *             'rating' => '7.2',
	 *             'general' => false,
	 *             'description' => 'Steve Lopez',
	 *             'professionKey' => 'ACTOR'
	 *         ]
	 *     ]
	 * ];
	 *
	 * $person = Person::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		$spouses = [];
		if (isset($data['spouses']) && is_array($data['spouses'])) {
			$spouses = array_map(fn ($spouseData) => PersonSpouse::fromArray($spouseData), $data['spouses']);
		}

		$films = [];
		if (isset($data['films']) && is_array($data['films'])) {
			$films = array_map(fn ($filmData) => PersonFilm::fromArray($filmData), $data['films']);
		}

		return new self(
			personId  : $data['personId'] ?? NULL,
			nameRu    : $data['nameRu'] ?? NULL,
			nameEn    : $data['nameEn'] ?? NULL,
			sex       : $data['sex'] ?? NULL,
			posterUrl : $data['posterUrl'] ?? NULL,
			growth    : $data['growth'] ?? NULL,
			birthday  : $data['birthday'] ?? NULL,
			death     : $data['death'] ?? NULL,
			age       : $data['age'] ?? NULL,
			birthplace: $data['birthplace'] ?? NULL,
			deathplace: $data['deathplace'] ?? NULL,
			spouses   : $spouses,
			hasAwards : $data['hasAwards'] ?? NULL,
			profession: $data['profession'] ?? NULL,
			facts     : $data['facts'] ?? NULL,
			films     : $films,
			biography : $data['biography'] ?? NULL,
			births    : $data['births'] ?? NULL,
			deaths    : $data['deaths'] ?? NULL,
			total     : $data['total'] ?? NULL,
		);
	}

	/**
	 * Получает отображаемое имя персоны
	 *
	 * Возвращает наиболее подходящее имя для отображения пользователю.
	 * Приоритет: русское имя → английское имя → "Неизвестно"
	 *
	 * @return string Отображаемое имя персоны
	 *
	 * @example
	 * ```php
	 * echo $person->getDisplayName(); // "Том Круз" или "Tom Cruise" или "Неизвестно"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? 'Неизвестно';
	}

}