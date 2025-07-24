<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель элемента коллекции фильмов из Kinopoisk API
 *
 * Представляет упрощенную информацию о фильме в коллекциях,
 * содержащую только основные поля без детальной информации.
 * Используется в ответах API для коллекций фильмов.
 *
 * Основные возможности:
 * - Хранение базовой информации о фильме в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для работы с названиями фильмов
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Film
 * @see     \NotKinopoisk\Models\FilmCollection
 * @see     \NotKinopoisk\Services\FilmService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $item = FilmCollectionItem::fromArray($apiData);
 *
 * // Использование
 * echo "Название: {$item->getDisplayName()}\n";
 * echo "Год: {$item->year}\n";
 * echo "Рейтинг: {$item->ratingKinopoisk}\n";
 * ```
 */
class FilmCollection implements ModelInterface {

	/**
	 * Конструктор элемента коллекции фильмов
	 *
	 * Создает новый экземпляр элемента коллекции со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @package string|null                     $imdbId            Идентификатор фильма в IMDb
	 *
	 * @param   int                             $kinopoiskId       Уникальный идентификатор фильма в Кинопоиске
	 * @param   string|null                     $nameRu            Название фильма на русском языке
	 * @param   string|null                     $nameEn            Название фильма на английском языке
	 * @param   string|null                     $nameOriginal      Оригинальное название фильма
	 * @param   \NotKinopoisk\Models\Country[]  $countries         Массив стран производства
	 * @param   \NotKinopoisk\Models\Genre[]    $genres            Массив жанров фильма
	 * @param   float|null                      $ratingKinopoisk   Рейтинг на Кинопоиске
	 * @param   float|null                      $ratingImbd        Рейтинг на IMDb
	 * @param   int|null                        $year              Год выпуска фильма
	 * @param   ContentType                     $type              Тип контента (фильм, сериал и т.д.)
	 * @param   string                          $posterUrl         URL постера фильма
	 * @param   string                          $posterUrlPreview  URL превью постера фильма
	 *
	 * @example
	 * ```php
	 * $item = new FilmCollectionItem(
	 *     kinopoiskId: 301,
	 *     nameRu: 'Матрица',
	 *     nameEn: 'The Matrix',
	 *     nameOriginal: 'The Matrix',
	 *     countries: [new Country('США')],
	 *     genres: [new Genre('боевик')],
	 *     ratingKinopoisk: 8.7,
	 *     ratingImbd: 8.7,
	 *     year: 1999,
	 *     type: ContentType::FILM,
	 *     posterUrl: 'https://example.com/poster.jpg',
	 *     posterUrlPreview: 'https://example.com/poster_small.jpg'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int         $kinopoiskId,
		public readonly ?string     $imdbId,
		public readonly ?string     $nameRu,
		public readonly ?string     $nameEn,
		public readonly ?string     $nameOriginal,
		public readonly array       $countries,
		public readonly array       $genres,
		public readonly ?float      $ratingKinopoisk,
		public readonly ?float      $ratingImbd,
		public readonly ?int        $year,
		public readonly ContentType $type,
		public readonly string      $posterUrl,
		public readonly string      $posterUrlPreview,
	) {}

	/**
	 * Создает экземпляр элемента коллекции из массива данных API
	 *
	 * Статический метод для удобного создания объекта FilmCollectionItem из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и устанавливает значения по умолчанию.
	 *
	 * @param   array  $data  Массив данных элемента коллекции от API
	 *
	 * @return self Новый экземпляр элемента коллекции
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'kinopoiskId' => 301,
	 *     'nameRu' => 'Матрица',
	 *     'nameEn' => 'The Matrix',
	 *     'nameOriginal' => 'The Matrix',
	 *     'countries' => [['country' => 'США']],
	 *     'genres' => [['genre' => 'боевик']],
	 *     'ratingKinopoisk' => 8.7,
	 *     'ratingImbd' => 8.7,
	 *     'year' => 1999,
	 *     'type' => 'FILM',
	 *     'posterUrl' => 'https://example.com/poster.jpg',
	 *     'posterUrlPreview' => 'https://example.com/poster_small.jpg'
	 * ];
	 *
	 * $item = FilmCollectionItem::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			kinopoiskId    : $data['kinopoiskId'],
			imdbId         : $data['imdbId'] ?? NULL,
			nameRu         : $data['nameRu'] ?? NULL,
			nameEn         : $data['nameEn'] ?? NULL,
			nameOriginal   : $data['nameOriginal'] ?? NULL,
			countries      : $data['countries'] ? array_map(fn ($country) => Country::fromArray($country), $data['countries']) : [],
			genres         : $data['genres'] ? array_map(fn ($genre) => Genre::fromArray($genre), $data['genres']) : [],
			ratingKinopoisk: $data['ratingKinopoisk'] ?? NULL,
			ratingImbd     : $data['ratingImbd'] ?? NULL,
			year           : $data['year'] ?? NULL,
			type           : ContentType::from($data['type']),
			posterUrl      : $data['posterUrl'], posterUrlPreview: $data['posterUrlPreview'],
		);
	}

	/**
	 * Получает отображаемое название фильма
	 *
	 * Возвращает наиболее подходящее название для отображения пользователю.
	 * Приоритет: русское название → английское название → оригинальное название → "Без названия"
	 *
	 * @return string Отображаемое название фильма
	 *
	 * @example
	 * ```php
	 * echo $item->getDisplayName(); // "Матрица" или "The Matrix" или "Без названия"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
	}

	/**
	 * Получает основной рейтинг фильма
	 *
	 * Возвращает наиболее значимый рейтинг из доступных.
	 * Приоритет: рейтинг Кинопоиска → рейтинг IMDb
	 *
	 * @return float|null Основной рейтинг или null если рейтинги отсутствуют
	 *
	 * @example
	 * ```php
	 * $rating = $item->getMainRating();
	 * if ($rating !== null) {
	 *     echo "Рейтинг: {$rating}";
	 * } else {
	 *     echo "Рейтинг не указан";
	 * }
	 * ```
	 */
	public function getMainRating(): ?float {
		return $this->ratingKinopoisk ?? $this->ratingImbd;
	}

	/**
	 * Проверяет, является ли контент сериалом
	 *
	 * Определяет тип контента на основе поля type. Возвращает true для
	 * сериалов и телешоу.
	 *
	 * @return bool true если это сериал, false если фильм
	 *
	 * @example
	 * ```php
	 * if ($item->isSerial()) {
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
	 * echo "Страны: {$item->getCountriesString()}"; // "США, Великобритания"
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
	 * echo "Жанры: {$item->getGenresString()}"; // "боевик, фантастика"
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
	 * Преобразует объект элемента коллекции фильмов в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными элемента коллекции
	 *
	 * @example
	 * ```php
	 * $itemArray = $item->toArray();
	 * echo json_encode($itemArray); // JSON представление элемента коллекции
	 * ```
	 */
	public function toArray(): array {
		return [
			'kinopoiskId'      => $this->kinopoiskId,
			'imdbId'           => $this->imdbId,
			'nameRu'           => $this->nameRu,
			'nameEn'           => $this->nameEn,
			'nameOriginal'     => $this->nameOriginal,
			'countries'        => array_map(fn ($country) => $country->toArray(), $this->countries),
			'genres'           => array_map(fn ($genre) => $genre->toArray(), $this->genres),
			'ratingKinopoisk'  => $this->ratingKinopoisk,
			'ratingImbd'       => $this->ratingImbd,
			'year'             => $this->year,
			'type'             => $this->type->value,
			'posterUrl'        => $this->posterUrl,
			'posterUrlPreview' => $this->posterUrlPreview,
		];
	}

} 