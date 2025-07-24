<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель фильтров из Kinopoisk API
 *
 * Представляет доступные фильтры для поиска фильмов,
 * включая списки жанров и стран для фильтрации.
 *
 * Основные возможности:
 * - Хранение списков доступных фильтров в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к спискам жанров и стран
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/filters
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @see     \NotKinopoisk\Models\Genre
 * @see     \NotKinopoisk\Models\Country
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films_filters
 *
 * @example
 * ```php
 * // Создание из данных API
 * $filters = Filters::fromArray($apiData);
 *
 * // Использование
 * echo "Доступно жанров: " . count($filters->genres) . "\n";
 * echo "Доступно стран: " . count($filters->countries) . "\n";
 *
 * foreach ($filters->genres as $genre) {
 *     echo "- {$genre->genre}\n";
 * }
 * ```
 */
class Filters implements ModelInterface {

	/**
	 * Конструктор модели фильтров
	 *
	 * Создает новый экземпляр фильтров со списками доступных жанров и стран.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   \NotKinopoisk\Models\Genre[]    $genres     Массив доступных жанров
	 * @param   \NotKinopoisk\Models\Country[]  $countries  Массив доступных стран
	 *
	 * @example
	 * ```php
	 * $filters = new Filters(
	 *     genres: [$genre1, $genre2, $genre3],
	 *     countries: [$country1, $country2]
	 * );
	 * ```
	 */
	public function __construct(
		public readonly array $genres,
		public readonly array $countries,
	) {}

	/**
	 * Создает экземпляр фильтров из массива данных API
	 *
	 * Статический метод для удобного создания объекта Filters из данных,
	 * полученных от Kinopoisk API. Автоматически создает объекты Genre и Country
	 * для каждого элемента в соответствующих массивах.
	 *
	 * @param   array  $data  Массив данных фильтров от API
	 *
	 * @return self Новый экземпляр фильтров
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'genres' => [
	 *         ['genre' => 'Боевик'],
	 *         ['genre' => 'Драма'],
	 *         ['genre' => 'Комедия']
	 *     ],
	 *     'countries' => [
	 *         ['country' => 'США'],
	 *         ['country' => 'Россия'],
	 *         ['country' => 'Великобритания']
	 *     ]
	 * ];
	 *
	 * $filters = Filters::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			genres   : array_map(fn ($genre) => Genre::fromArray($genre), $data['genres']),
			countries: array_map(fn ($country) => Country::fromArray($country), $data['countries']),
		);
	}

	/**
	 * Преобразует объект фильтров в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными фильтров
	 *
	 * @example
	 * ```php
	 * $filtersArray = $filters->toArray();
	 * echo json_encode($filtersArray); // JSON представление фильтров
	 * ```
	 */
	public function toArray(): array {
		return [
			'genres'    => array_map(fn ($genre) => $genre instanceof Genre ? $genre->toArray() : $genre, $this->genres),
			'countries' => array_map(fn ($country) => $country instanceof Country ? $country->toArray() : $country, $this->countries),
		];
	}

}