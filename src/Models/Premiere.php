<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель премьеры из Kinopoisk API
 *
 * Представляет информацию о премьере фильма, включая
 * название, год, страны, жанры и дату премьеры в России.
 *
 * Основные возможности:
 * - Хранение информации о премьере в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого названия фильма
 * - Доступ к метаданным премьеры
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/premieres
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @see     \NotKinopoisk\Models\Country
 * @see     \NotKinopoisk\Models\Genre
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films_premieres
 *
 * @example
 * ```php
 * // Создание из данных API
 * $premiere = Premiere::fromArray($apiData);
 *
 * // Использование
 * echo "Премьера: {$premiere->getDisplayName()}\n";
 * echo "Год: {$premiere->year}\n";
 * echo "Премьера в России: {$premiere->premiereRu}";
 * ```
 */
class Premiere implements ModelInterface {

	/**
	 * Конструктор модели премьеры
	 *
	 * Создает новый экземпляр премьеры со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int          $kinopoiskId       Уникальный идентификатор фильма в Кинопоиске
	 * @param   string|null  $nameRu            Название фильма на русском языке
	 * @param   string|null  $nameEn            Название фильма на английском языке
	 * @param   int          $year              Год выпуска фильма
	 * @param   string       $posterUrl         URL постера фильма
	 * @param   string       $posterUrlPreview  URL превью постера фильма
	 * @param   array        $countries         Массив стран производства
	 * @param   array        $genres            Массив жанров фильма
	 * @param   int|null     $duration          Продолжительность фильма в минутах
	 * @param   string       $premiereRu        Дата премьеры в России
	 *
	 * @example
	 * ```php
	 * $premiere = new Premiere(
	 *     kinopoiskId: 12345,
	 *     nameRu: 'Новый фильм',
	 *     nameEn: 'New Movie',
	 *     year: 2023,
	 *     posterUrl: 'https://...',
	 *     posterUrlPreview: 'https://...',
	 *     countries: [$country1, $country2],
	 *     genres: [$genre1, $genre2],
	 *     duration: 120,
	 *     premiereRu: '2023-12-01'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int     $kinopoiskId,
		public readonly ?string $nameRu,
		public readonly ?string $nameEn,
		public readonly int     $year,
		public readonly string  $posterUrl,
		public readonly string  $posterUrlPreview,
		public readonly array   $countries,
		public readonly array   $genres,
		public readonly ?int    $duration,
		public readonly string  $premiereRu,
	) {}

	/**
	 * Создает экземпляр премьеры из массива данных API
	 *
	 * Статический метод для удобного создания объекта Premiere из данных,
	 * полученных от Kinopoisk API. Автоматически создает объекты Country и Genre
	 * для каждого элемента в соответствующих массивах.
	 *
	 * @param   array  $data  Массив данных премьеры от API
	 *
	 * @return self Новый экземпляр премьеры
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'kinopoiskId' => 12345,
	 *     'nameRu' => 'Новый фильм',
	 *     'nameEn' => 'New Movie',
	 *     'year' => 2023,
	 *     'posterUrl' => 'https://...',
	 *     'posterUrlPreview' => 'https://...',
	 *     'countries' => [['country' => 'США'], ['country' => 'Великобритания']],
	 *     'genres' => [['genre' => 'Боевик'], ['genre' => 'Драма']],
	 *     'duration' => 120,
	 *     'premiereRu' => '2023-12-01'
	 * ];
	 *
	 * $premiere = Premiere::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			kinopoiskId     : $data['kinopoiskId'],
			nameRu          : $data['nameRu'] ?? NULL,
			nameEn          : $data['nameEn'] ?? NULL,
			year            : $data['year'],
			posterUrl       : $data['posterUrl'],
			posterUrlPreview: $data['posterUrlPreview'],
			countries       : array_map(fn ($country) => Country::fromArray($country), $data['countries']),
			genres          : array_map(fn ($genre) => Genre::fromArray($genre), $data['genres']),
			duration        : $data['duration'] ?? NULL,
			premiereRu      : $data['premiereRu'],
		);
	}

	/**
	 * Получает отображаемое название премьеры
	 *
	 * Возвращает наиболее подходящее название для отображения пользователю.
	 * Приоритет: русское название → английское название → "Без названия"
	 *
	 * @return string Отображаемое название премьеры
	 *
	 * @example
	 * ```php
	 * echo $premiere->getDisplayName(); // "Новый фильм" или "New Movie" или "Без названия"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? 'Без названия';
	}

	/**
	 * Преобразует объект премьеры в массив
	 *
	 * Возвращает массив со всеми свойствами премьеры, включая
	 * преобразованные в массивы объекты стран и жанров.
	 *
	 * @return array Массив данных премьеры
	 *
	 * @example
	 * ```php
	 * $array = $premiere->toArray();
	 * // [
	 * //     'kinopoiskId' => 12345,
	 * //     'nameRu' => 'Новый фильм',
	 * //     'nameEn' => 'New Movie',
	 * //     'year' => 2023,
	 * //     'posterUrl' => 'https://...',
	 * //     'posterUrlPreview' => 'https://...',
	 * //     'countries' => [['country' => 'США'], ['country' => 'Великобритания']],
	 * //     'genres' => [['genre' => 'Боевик'], ['genre' => 'Драма']],
	 * //     'duration' => 120,
	 * //     'premiereRu' => '2023-12-01'
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return [
			'kinopoiskId'      => $this->kinopoiskId,
			'nameRu'           => $this->nameRu,
			'nameEn'           => $this->nameEn,
			'year'             => $this->year,
			'posterUrl'        => $this->posterUrl,
			'posterUrlPreview' => $this->posterUrlPreview,
			'countries'        => array_map(fn ($country) => $country->toArray(), $this->countries),
			'genres'           => array_map(fn ($genre) => $genre->toArray(), $this->genres),
			'duration'         => $this->duration,
			'premiereRu'       => $this->premiereRu,
		];
	}

}