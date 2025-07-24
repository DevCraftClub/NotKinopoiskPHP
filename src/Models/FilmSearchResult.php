<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель результата поиска фильма из Kinopoisk API
 *
 * Представляет краткую информацию о фильме в результатах поиска по ключевому слову.
 * Содержит основные данные: названия, тип контента, год, описание, рейтинг и постер.
 *
 * Основные возможности:
 * - Хранение информации о фильме в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого названия фильма
 * - Доступ к метаданным поискового результата
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.1/films/search-by-keyword
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @see     \NotKinopoisk\Models\Country
 * @see     \NotKinopoisk\Models\Genre
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_1_films_search_by_keyword
 *
 * @example
 * ```php
 * // Создание из данных API
 * $searchResult = FilmSearchResult::fromArray($apiData);
 *
 * // Использование
 * echo "Фильм: {$searchResult->getDisplayName()}\n";
 * echo "Год: {$searchResult->year}\n";
 * echo "Рейтинг: {$searchResult->rating}";
 * ```
 */
class FilmSearchResult implements ModelInterface {

	/**
	 * Конструктор модели результата поиска фильма
	 *
	 * Создает новый экземпляр результата поиска со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int                                  $filmId             Уникальный идентификатор фильма в Кинопоиске
	 * @param   string|null                          $nameRu            Название фильма на русском языке
	 * @param   string|null                          $nameEn            Название фильма на английском языке
	 * @param   ContentType                          $type              Тип контента (FILM, TV_SHOW, VIDEO, MINI_SERIES, TV_SERIES, UNKNOWN)
	 * @param   string|null                          $year              Год выпуска фильма (строка)
	 * @param   string|null                          $description       Краткое описание фильма
	 * @param   string|null                          $filmLength         Длительность фильма в формате "2:17"
	 * @param   array<\NotKinopoisk\Models\Country>  $countries         Массив стран производства
	 * @param   array<\NotKinopoisk\Models\Genre>    $genres            Массив жанров фильма
	 * @param   string|null                          $rating            Рейтинг фильма (строка, может быть "7.9" или "99%")
	 * @param   int|null                             $ratingVoteCount   Количество голосов за рейтинг
	 * @param   string                               $posterUrl         URL постера фильма
	 * @param   string                               $posterUrlPreview  URL превью постера фильма
	 *
	 * @example
	 * ```php
	 * $searchResult = new FilmSearchResult(
	 *     filmId: 263531,
	 *     nameRu: 'Мстители',
	 *     nameEn: 'The Avengers',
	 *     type: ContentType::FILM,
	 *     year: '2012',
	 *     description: 'США, Джосс Уидон(фантастика)',
	 *     filmLength: '2:17',
	 *     countries: [$country1, $country2],
	 *     genres: [$genre1, $genre2],
	 *     rating: '7.9',
	 *     ratingVoteCount: 284245,
	 *     posterUrl: 'https://example.com/poster.jpg',
	 *     posterUrlPreview: 'https://example.com/poster_small.jpg'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int         $filmId,
		public readonly ?string     $nameRu,
		public readonly ?string     $nameEn,
		public readonly ContentType $type,
		public readonly ?string     $year,
		public readonly ?string     $description,
		public readonly ?string     $filmLength,
		public readonly array       $countries,
		public readonly array       $genres,
		public readonly ?string     $rating,
		public readonly ?int        $ratingVoteCount,
		public readonly string      $posterUrl,
		public readonly string      $posterUrlPreview,
	) {}

	/**
	 * Создает экземпляр результата поиска из массива данных API
	 *
	 * Статический метод для удобного создания объекта FilmSearchResult из данных,
	 * полученных от Kinopoisk API. Автоматически создает объекты Country и Genre
	 * для каждого элемента в соответствующих массивах.
	 *
	 * @param   array  $data  Массив данных результата поиска от API
	 *
	 * @return static Новый экземпляр результата поиска
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'filmId' => 263531,
	 *     'nameRu' => 'Мстители',
	 *     'nameEn' => 'The Avengers',
	 *     'type' => 'FILM',
	 *     'year' => '2012',
	 *     'description' => 'США, Джосс Уидон(фантастика)',
	 *     'filmLength' => '2:17',
	 *     'countries' => [['country' => 'США']],
	 *     'genres' => [['genre' => 'фантастика']],
	 *     'rating' => '7.9',
	 *     'ratingVoteCount' => 284245,
	 *     'posterUrl' => 'https://example.com/poster.jpg',
	 *     'posterUrlPreview' => 'https://example.com/poster_small.jpg'
	 * ];
	 *
	 * $searchResult = FilmSearchResult::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			filmId           : $data['filmId'],
			nameRu          : $data['nameRu'] ?? NULL,
			nameEn          : $data['nameEn'] ?? NULL,
			type            : ContentType::from($data['type']),
			year            : $data['year'] ?? NULL,
			description     : $data['description'] ?? NULL,
			filmLength       : $data['filmLength'] ?? NULL,
			countries       : array_map(fn ($country) => Country::fromArray($country), $data['countries'] ?? []),
			genres          : array_map(fn ($genre) => Genre::fromArray($genre), $data['genres'] ?? []),
			rating          : $data['rating'] ?? NULL,
			ratingVoteCount : $data['ratingVoteCount'] ?? NULL,
			posterUrl       : $data['posterUrl'],
			posterUrlPreview: $data['posterUrlPreview'],
		);
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
	 * echo $searchResult->getDisplayName(); // "Мстители" или "The Avengers" или "Без названия"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? 'Без названия';
	}

	/**
	 * Проверяет, является ли контент сериалом
	 *
	 * Определяет тип контента на основе поля type. Возвращает true для
	 * TV_SERIES, MINI_SERIES и TV_SHOW.
	 *
	 * @return bool true если это сериал, false если фильм
	 *
	 * @example
	 * ```php
	 * if ($searchResult->isSerial()) {
	 *     echo "Это сериал";
	 * } else {
	 *     echo "Это фильм";
	 * }
	 * ```
	 */
	public function isSerial(): bool {
		return $this->type->isSeries();
	}

	/**
	 * Получает список стран в виде строки
	 *
	 * Возвращает названия стран, разделенные запятыми.
	 *
	 * @return string Список стран или пустая строка
	 *
	 * @example
	 * ```php
	 * echo "Страны: {$searchResult->getCountriesString()}"; // "США, Великобритания"
	 * ```
	 */
	public function getCountriesString(): string {
		if (empty($this->countries)) {
			return '';
		}

		$countryNames = array_map('strval', $this->countries);

		return implode(', ', array_filter($countryNames));
	}

	/**
	 * Получает список жанров в виде строки
	 *
	 * Возвращает названия жанров, разделенные запятыми.
	 *
	 * @return string Список жанров или пустая строка
	 *
	 * @example
	 * ```php
	 * echo "Жанры: {$searchResult->getGenresString()}"; // "фантастика, боевик"
	 * ```
	 */
	public function getGenresString(): string {
		if (empty($this->genres)) {
			return '';
		}

		$genreNames = array_map('strval', $this->genres);

		return implode(', ', array_filter($genreNames));
	}

	/**
	 * Преобразует объект результата поиска в массив
	 *
	 * Возвращает массив со всеми свойствами результата поиска, включая
	 * преобразованные в массивы объекты стран и жанров.
	 *
	 * @return array Массив данных результата поиска
	 *
	 * @example
	 * ```php
	 * $array = $searchResult->toArray();
	 * // [
	 * //     'filmId' => 263531,
	 * //     'nameRu' => 'Мстители',
	 * //     'nameEn' => 'The Avengers',
	 * //     'type' => 'FILM',
	 * //     'year' => '2012',
	 * //     'description' => 'США, Джосс Уидон(фантастика)',
	 * //     'filmLength' => '2:17',
	 * //     'countries' => [['country' => 'США']],
	 * //     'genres' => [['genre' => 'фантастика']],
	 * //     'rating' => '7.9',
	 * //     'ratingVoteCount' => 284245,
	 * //     'posterUrl' => 'https://example.com/poster.jpg',
	 * //     'posterUrlPreview' => 'https://example.com/poster_small.jpg'
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return [
			'filmId'            => $this->filmId,
			'nameRu'           => $this->nameRu,
			'nameEn'           => $this->nameEn,
			'type'             => $this->type->value,
			'year'             => $this->year,
			'description'      => $this->description,
			'filmLength'        => $this->filmLength,
			'countries'        => array_map(fn ($country) => $country->toArray(), $this->countries),
			'genres'           => array_map(fn ($genre) => $genre->toArray(), $this->genres),
			'rating'           => $this->rating,
			'ratingVoteCount'  => $this->ratingVoteCount,
			'posterUrl'        => $this->posterUrl,
			'posterUrlPreview' => $this->posterUrlPreview,
		];
	}

} 