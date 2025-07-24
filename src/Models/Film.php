<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\ProductionStatus;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель фильма из Kinopoisk API
 *
 * Представляет полную информацию о фильме, полученную из Kinopoisk Unofficial API.
 * Содержит все основные данные: названия, рейтинги, описания, технические характеристики
 * и метаданные фильма.
 *
 * Основные возможности:
 * - Хранение полной информации о фильме в неизменяемом виде (readonly свойства)
 * - Создание объекта из массива данных API
 * - Удобные методы для получения отображаемого названия и основного рейтинга
 * - Определение типа контента (фильм/сериал)
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}
 *
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\FilmCollection
 * @see     \NotKinopoisk\Services\FilmService
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id_
 * @example
 * ```php
 * // Создание из данных API
 * $film = Film::fromArray($apiData);
 *
 * // Получение отображаемого названия
 * echo $film->getDisplayName(); // "Матрица" или "The Matrix"
 *
 * // Проверка типа контента
 * if ($film->isSerial()) {
 *     echo "Это сериал";
 * }
 *
 * // Получение основного рейтинга
 * $rating = $film->getMainRating(); // 8.7
 * ```
 */
class Film implements ModelInterface {

	/**
	 * Конструктор модели фильма
	 *
	 * Создает новый экземпляр фильма со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int                                        $kinopoiskId                 Уникальный идентификатор фильма в Кинопоиске
	 * @param   string|null                                $kinopoiskHDId               Идентификатор фильма в Кинопоиск HD (если доступен)
	 * @param   string|null                                $imdbId                      Идентификатор фильма в IMDb
	 * @param   string|null                                $nameRu                      Название фильма на русском языке
	 * @param   string|null                                $nameEn                      Название фильма на английском языке
	 * @param   string|null                                $nameOriginal                Оригинальное название фильма
	 * @param   string                                     $posterUrl                   URL постера фильма в высоком разрешении
	 * @param   string                                     $posterUrlPreview            URL постера фильма в низком разрешении
	 * @param   string|null                                $coverUrl                    URL обложки фильма
	 * @param   string|null                                $logoUrl                     URL логотипа фильма
	 * @param   int|null                                   $reviewsCount                Количество рецензий на фильм
	 * @param   float|null                                 $ratingGoodReview            Рейтинг хороших рецензий
	 * @param   int|null                                   $ratingGoodReviewVoteCount   Количество голосов за хорошие рецензии
	 * @param   float|null                                 $ratingKinopoisk             Рейтинг Кинопоиска
	 * @param   int|null                                   $ratingKinopoiskVoteCount    Количество голосов на Кинопоиске
	 * @param   float|null                                 $ratingImdb                  Рейтинг IMDb
	 * @param   int|null                                   $ratingImdbVoteCount         Количество голосов на IMDb
	 * @param   float|null                                 $ratingFilmCritics           Рейтинг кинокритиков
	 * @param   int|null                                   $ratingFilmCriticsVoteCount  Количество голосов кинокритиков
	 * @param   float|null                                 $ratingAwait                 Рейтинг ожидания
	 * @param   int|null                                   $ratingAwaitCount            Количество голосов ожидания
	 * @param   float|null                                 $ratingRfCritics             Рейтинг российских кинокритиков
	 * @param   int|null                                   $ratingRfCriticsVoteCount    Количество голосов российских кинокритиков
	 * @param   string|null                                $webUrl                      URL страницы фильма на Кинопоиске
	 * @param   int|null                                   $year                        Год выпуска фильма
	 * @param   int|null                                   $filmLength                  Длительность фильма в минутах
	 * @param   string|null                                $slogan                      Слоган фильма
	 * @param   string|null                                $description                 Полное описание фильма
	 * @param   string|null                                $shortDescription            Краткое описание фильма
	 * @param   string|null                                $editorAnnotation            Редакторская аннотация
	 * @param   bool|null                                  $isTicketsAvailable          Доступны ли билеты в кинотеатрах
	 * @param   \NotKinopoisk\Enums\ProductionStatus|null  $productionStatus            Статус производства фильма
	 * @param   ContentType                                $type                        Тип контента (FILM, SERIES, MINI_SERIES, TV_SHOW)
	 * @param   string|null                                $ratingMpaa                  Рейтинг MPAA
	 * @param   string|null                                $ratingAgeLimits             Возрастные ограничения
	 * @param   bool|null                                  $hasImax                     Доступен ли в формате IMAX
	 * @param   bool|null                                  $has3D                       Доступен ли в формате 3D
	 * @param   string|null                                $lastSync                    Время последней синхронизации данных
	 * @param   \NotKinopoisk\Models\Country[]             $countries                   Массив стран производства
	 * @param   Genre[]                                    $genres                      Массив жанров фильма
	 * @param   int|null                                   $startYear                   Год начала производства (для сериалов)
	 * @param   int|null                                   $endYear                     Год окончания производства (для сериалов)
	 * @param   bool|null                                  $serial                      Является ли сериалом
	 * @param   bool|null                                  $shortFilm                   Является ли короткометражным фильмом
	 * @param   bool|null                                  $completed                   Завершен ли (для сериалов)
	 *
	 * @example
	 * ```php
	 * $film = new Film(
	 *     kinopoiskId: 301,
	 *     nameRu: 'Матрица',
	 *     nameEn: 'The Matrix',
	 *     posterUrl: 'https://...',
	 *     posterUrlPreview: 'https://...',
	 *     type: ContentType::FILM,
	 *     year: 1999,
	 *     countries: [],
	 *     genres: []
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int               $kinopoiskId,
		public readonly ?string           $kinopoiskHDId,
		public readonly ?string           $imdbId,
		public readonly ?string           $nameRu,
		public readonly ?string           $nameEn,
		public readonly ?string           $nameOriginal,
		public readonly string            $posterUrl,
		public readonly string            $posterUrlPreview,
		public readonly ?string           $coverUrl,
		public readonly ?string           $logoUrl,
		public readonly ?int              $reviewsCount,
		public readonly ?float            $ratingGoodReview,
		public readonly ?int              $ratingGoodReviewVoteCount,
		public readonly ?float            $ratingKinopoisk,
		public readonly ?int              $ratingKinopoiskVoteCount,
		public readonly ?float            $ratingImdb,
		public readonly ?int              $ratingImdbVoteCount,
		public readonly ?float            $ratingFilmCritics,
		public readonly ?int              $ratingFilmCriticsVoteCount,
		public readonly ?float            $ratingAwait,
		public readonly ?int              $ratingAwaitCount,
		public readonly ?float            $ratingRfCritics,
		public readonly ?int              $ratingRfCriticsVoteCount,
		public readonly ?string           $webUrl,
		public readonly ?int              $year,
		public readonly ?int              $filmLength,
		public readonly ?string           $slogan,
		public readonly ?string           $description,
		public readonly ?string           $shortDescription,
		public readonly ?string           $editorAnnotation,
		public readonly ?bool             $isTicketsAvailable,
		public readonly ?ProductionStatus $productionStatus,
		public readonly ContentType       $type,
		public readonly ?string           $ratingMpaa,
		public readonly ?string           $ratingAgeLimits,
		public readonly ?bool             $hasImax,
		public readonly ?bool             $has3D,
		public readonly ?string           $lastSync,
		public readonly array             $countries,
		public readonly array             $genres,
		public readonly ?int              $startYear,
		public readonly ?int              $endYear,
		public readonly ?bool             $serial,
		public readonly ?bool             $shortFilm,
		public readonly ?bool             $completed,
	) {}

	/**
	 * Создает экземпляр фильма из массива данных API
	 *
	 * Статический метод для удобного создания объекта Film из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и устанавливает значения по умолчанию.
	 *
	 * @param   array  $data  Массив данных фильма от API
	 *
	 * @return self Новый экземпляр фильма
	 *
	 * @throws \InvalidArgumentException Если отсутствуют обязательные поля
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'kinopoiskId' => 301,
	 *     'nameRu' => 'Матрица',
	 *     'nameEn' => 'The Matrix',
	 *     'posterUrl' => 'https://...',
	 *     'posterUrlPreview' => 'https://...',
	 *     'type' => 'FILM',
	 *     'countries' => [],
	 *     'genres' => []
	 * ];
	 *
	 * $film = Film::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			kinopoiskId               : $data['kinopoiskId'],
			kinopoiskHDId             : $data['kinopoiskHDId'] ?? NULL,
			imdbId                    : $data['imdbId'] ?? NULL,
			nameRu                    : $data['nameRu'] ?? NULL,
			nameEn                    : $data['nameEn'] ?? NULL,
			nameOriginal              : $data['nameOriginal'] ?? NULL,
			posterUrl                 : $data['posterUrl'],
			posterUrlPreview          : $data['posterUrlPreview'],
			coverUrl                  : $data['coverUrl'] ?? NULL,
			logoUrl                   : $data['logoUrl'] ?? NULL,
			reviewsCount              : $data['reviewsCount'] ?? NULL,
			ratingGoodReview          : $data['ratingGoodReview'] ?? NULL,
			ratingGoodReviewVoteCount : $data['ratingGoodReviewVoteCount'] ?? NULL,
			ratingKinopoisk           : $data['ratingKinopoisk'] ?? NULL,
			ratingKinopoiskVoteCount  : $data['ratingKinopoiskVoteCount'] ?? NULL,
			ratingImdb                : $data['ratingImdb'] ?? NULL,
			ratingImdbVoteCount       : $data['ratingImdbVoteCount'] ?? NULL,
			ratingFilmCritics         : $data['ratingFilmCritics'] ?? NULL,
			ratingFilmCriticsVoteCount: $data['ratingFilmCriticsVoteCount'] ?? NULL,
			ratingAwait               : $data['ratingAwait'] ?? NULL,
			ratingAwaitCount          : $data['ratingAwaitCount'] ?? NULL,
			ratingRfCritics           : $data['ratingRfCritics'] ?? NULL,
			ratingRfCriticsVoteCount  : $data['ratingRfCriticsVoteCount'] ?? NULL,
			webUrl                    : $data['webUrl'] ?? NULL,
			year                      : $data['year'] ?? NULL,
			filmLength                : $data['filmLength'] ?? NULL,
			slogan                    : $data['slogan'] ?? NULL,
			description               : $data['description'] ?? NULL,
			shortDescription          : $data['shortDescription'] ?? NULL,
			editorAnnotation          : $data['editorAnnotation'] ?? NULL,
			isTicketsAvailable        : $data['isTicketsAvailable'] ?? NULL,
			productionStatus          : $data['productionStatus'] ? ProductionStatus::from($data['productionStatus']) : NULL,
			type                      : ContentType::from($data['type']),
			ratingMpaa                : $data['ratingMpaa'] ?? NULL,
			ratingAgeLimits           : $data['ratingAgeLimits'] ?? NULL,
			hasImax                   : $data['hasImax'] ?? NULL,
			has3D                     : $data['has3D'] ?? NULL,
			lastSync                  : $data['lastSync'] ?? NULL,
			countries                 : $data['countries'] ? array_map(fn ($country) => Country::fromArray($country), $data['countries']) : [],
			genres                    : $data['genres'] ? array_map(fn ($genre) => Genre::fromArray($genre), $data['genres']) : [],
			startYear                 : $data['startYear'] ?? NULL,
			endYear                   : $data['endYear'] ?? NULL,
			serial                    : $data['serial'] ?? NULL,
			shortFilm                 : $data['shortFilm'] ?? NULL,
			completed                 : $data['completed'] ?? NULL,
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
	 * echo $film->getDisplayName(); // "Матрица" или "The Matrix" или "Без названия"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
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
	 * if ($film->isSerial()) {
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
	 * Получает основной рейтинг фильма
	 *
	 * Возвращает наиболее значимый рейтинг из доступных.
	 * Приоритет: рейтинг Кинопоиска → рейтинг IMDb → рейтинг кинокритиков
	 *
	 * @return float|null Основной рейтинг или null если рейтинги отсутствуют
	 *
	 * @example
	 * ```php
	 * $rating = $film->getMainRating();
	 * if ($rating !== null) {
	 *     echo "Рейтинг: {$rating}";
	 * } else {
	 *     echo "Рейтинг не доступен";
	 * }
	 * ```
	 */
	public function getMainRating(): ?float {
		return $this->ratingKinopoisk ?? $this->ratingImdb ?? $this->ratingFilmCritics;
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
	 * Форматирует длительность фильма в формат ЧЧ:ММ:СС
	 *
	 * Преобразует длительность фильма из минут в формат ЧЧ:ММ:СС.
	 * Если длительность не указана, возвращает '00:00:00'.
	 *
	 * @return string Длительность в формате ЧЧ:ММ:СС
	 *
	 * @example
	 * ```php
	 * echo $film->formatDuration(); // "02:30:00" для фильма длиной 150 минут
	 * ```
	 */
	public function formatDuration(): string {
		if ($this->filmLength === NULL) {
			return '00:00:00';
		}

		$hours   = floor($this->filmLength / 60);
		$minutes = $this->filmLength % 60;

		return sprintf('%0d:%02d:00', $hours, $minutes);
	}

	/**
	 * Форматирует длительность фильма в человекочитаемый формат
	 *
	 * Преобразует длительность фильма из минут в формат "X час(ов) Y минут".
	 * Учитывает правильные окончания для часов и минут.
	 * Если длительность не указана, возвращает пустую строку.
	 *
	 * @return string Длительность в формате "X час(ов) Y минут"
	 *
	 * @example
	 * ```php
	 * echo $film->formatDurationString(); // "2 часа 30 минут" для фильма длиной 150 минут
	 * ```
	 */
	public function formatDurationString(): string {
		if ($this->filmLength === NULL) {
			return '';
		}

		$hours   = floor($this->filmLength / 60);
		$minutes = $this->filmLength % 60;

		$hoursStr = match ((int) $hours) {
			1       => '1 час',
			2, 3, 4 => "$hours часа",
			default => "$hours часов"
		};

		$minutesStr = match ((int) $minutes) {
			1, 21, 31, 41, 51                                       => "$minutes минута",
			2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54 => "$minutes минуты",
			default                                                 => "$minutes минут"
		};

		if ((int) $hours === 0) {
			return $minutesStr;
		}

		if ($minutes === 0) {
			return $hoursStr;
		}

		return "$hoursStr $minutesStr";
	}

	/**
	 * Преобразует объект фильма в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными фильма
	 *
	 * @example
	 * ```php
	 * $filmArray = $film->toArray();
	 * echo json_encode($filmArray); // JSON представление фильма
	 * ```
	 */
	public function toArray(): array {
		return [
			'kinopoiskId'                => $this->kinopoiskId,
			'kinopoiskHDId'              => $this->kinopoiskHDId,
			'imdbId'                     => $this->imdbId,
			'nameRu'                     => $this->nameRu,
			'nameEn'                     => $this->nameEn,
			'nameOriginal'               => $this->nameOriginal,
			'posterUrl'                  => $this->posterUrl,
			'posterUrlPreview'           => $this->posterUrlPreview,
			'coverUrl'                   => $this->coverUrl,
			'logoUrl'                    => $this->logoUrl,
			'reviewsCount'               => $this->reviewsCount,
			'ratingGoodReview'           => $this->ratingGoodReview,
			'ratingGoodReviewVoteCount'  => $this->ratingGoodReviewVoteCount,
			'ratingKinopoisk'            => $this->ratingKinopoisk,
			'ratingKinopoiskVoteCount'   => $this->ratingKinopoiskVoteCount,
			'ratingImdb'                 => $this->ratingImdb,
			'ratingImdbVoteCount'        => $this->ratingImdbVoteCount,
			'ratingFilmCritics'          => $this->ratingFilmCritics,
			'ratingFilmCriticsVoteCount' => $this->ratingFilmCriticsVoteCount,
			'ratingAwait'                => $this->ratingAwait,
			'ratingAwaitCount'           => $this->ratingAwaitCount,
			'ratingRfCritics'            => $this->ratingRfCritics,
			'ratingRfCriticsVoteCount'   => $this->ratingRfCriticsVoteCount,
			'webUrl'                     => $this->webUrl,
			'year'                       => $this->year,
			'filmLength'                  => $this->filmLength,
			'slogan'                     => $this->slogan,
			'description'                => $this->description,
			'shortDescription'           => $this->shortDescription,
			'editorAnnotation'           => $this->editorAnnotation,
			'isTicketsAvailable'         => $this->isTicketsAvailable,
			'productionStatus'           => $this->productionStatus?->value,
			'type'                       => $this->type->value,
			'ratingMpaa'                 => $this->ratingMpaa,
			'ratingAgeLimits'            => $this->ratingAgeLimits,
			'hasImax'                    => $this->hasImax,
			'has3D'                      => $this->has3D,
			'lastSync'                   => $this->lastSync,
			'countries'                  => array_map(fn ($country) => $country->toArray(), $this->countries),
			'genres'                     => array_map(fn ($genre) => $genre->toArray(), $this->genres),
			'startYear'                  => $this->startYear,
			'endYear'                    => $this->endYear,
			'serial'                     => $this->serial,
			'shortFilm'                  => $this->shortFilm,
			'completed'                  => $this->completed,
		];
	}

}