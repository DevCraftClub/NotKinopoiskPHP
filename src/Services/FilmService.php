<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ApiVersion;
use NotKinopoisk\Enums\CollectionType;
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\FilmOrder;
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\Month;
use NotKinopoisk\Enums\ReviewOrder;
use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Models\Award;
use NotKinopoisk\Models\BoxOffice;
use NotKinopoisk\Models\Distribution;
use NotKinopoisk\Models\ExternalSource;
use NotKinopoisk\Models\Fact;
use NotKinopoisk\Models\Film;
use NotKinopoisk\Models\FilmCollection;
use NotKinopoisk\Models\FilmSearchResult;
use NotKinopoisk\Models\Filters;
use NotKinopoisk\Models\Image;
use NotKinopoisk\Models\Premiere;
use NotKinopoisk\Models\RelatedFilm;
use NotKinopoisk\Models\Review;
use NotKinopoisk\Models\Season;
use NotKinopoisk\Models\Video;
use NotKinopoisk\Responses\BudgetResponse;
use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Responses\KeywordSearchResponse;
use NotKinopoisk\Responses\PaginatedResponse;
use NotKinopoisk\Responses\ReviewResponse;
use NotKinopoisk\Responses\SequelPrequelResponse;

/**
 * Сервис для работы с фильмами в Kinopoisk API
 *
 * Предоставляет полный набор методов для взаимодействия с фильмами через Kinopoisk API.
 * Реализует CRUD операции: Create (поиск), Read (получение данных), Update (не поддерживается), Delete (не поддерживается).
 *
 * Основные возможности:
 * - Получение детальной информации о фильмах
 * - Поиск фильмов по ключевым словам и фильтрам
 * - Получение связанного контента (сезоны, факты, награды, отзывы)
 * - Работа с коллекциями фильмов (популярные, топ-250)
 * - Получение премьер и фильтров для поиска
 *
 * @package NotKinopoisk\Services
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\AbstractService
 * @see     \NotKinopoisk\Models\Film
 * @see     \NotKinopoisk\Models\FilmCollection
 *
 * @example
 * ```php
 * $client = new Client('api-key');
 * $filmService = $client->films;
 *
 * // Получение информации о фильме
 * $film = $filmService->getById(301);
 * echo $film->getDisplayName();
 *
 * // Поиск фильмов
 * $results = $filmService->searchByKeyword('матрица');
 * foreach ($results->items as $film) {
 *     echo $film->getDisplayName() . "\n";
 * }
 * ```
 */
class FilmService extends AbstractService {

	/**
	 * Конструктор класса сервиса
	 *
	 * Инициализирует новый экземпляр сервиса с переданным HTTP-клиентом
	 * и устанавливает версию API v2.2 для работы с Kinopoisk Unofficial API.
	 * Вызывает родительский конструктор для настройки базовой конфигурации.
	 *
	 * @see AbstractService::__construct() Родительский конструктор
	 * @see ApiVersion::V2_2 Используемая версия API
	 *
	 * @param   Client  $client  HTTP-клиент для выполнения запросов к API
	 *
	 * @example
	 * ```php
	 * $client = new Client('your-api-key');
	 * $service = new FilmService($client);
	 * ```
	 */
	public function __construct(Client $client) {
		parent::__construct($client, ApiVersion::V2_2);
	}

	/**
	 * Получает детальную информацию о фильме по ID
	 *
	 * READ операция - извлекает полную информацию о фильме из API.
	 * Возвращает объект Film со всеми доступными данными: названия, рейтинги,
	 * описания, технические характеристики и метаданные.
	 *
	 * @api /api/v2.2/films/{id}
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\Film Объект фильма с полной информацией
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм с указанным ID не найден
	 * @example
	 * ```php
	 * $film = $filmService->getById(301); // Матрица
	 * echo "Название: " . $film->getDisplayName();
	 * echo "Год: " . $film->year;
	 * echo "Рейтинг: " . $film->getMainRating();
	 * ```
	 */
	public function getById(int $id): Film {
		$data = $this->get($this->buildUri("films/{$id}"));

		return Film::fromArray($data);
	}

	/**
	 * Получает сезоны сериала
	 *
	 * READ операция - извлекает информацию о всех сезонах сериала.
	 * Метод предназначен для работы с сериалами (TV_SERIES, MINI_SERIES).
	 *
	 * @api /api/v2.2/films/{id}/seasons
	 *
	 * @param   int  $id  Уникальный идентификатор сериала в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив сезонов сериала
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если сериал не найден
	 * @example
	 * ```php
	 * // Получение сезонов популярного сериала
	 * $response = $filmService->getSeasons(12345);
	 *
	 * echo "Всего сезонов: {$response->total}\n";
	 *
	 * foreach ($response->items as $season) {
	 *     echo "Сезон {$season->number}: " . count($season->episodes) . " эпизодов\n";
	 *
	 *     foreach ($season->episodes as $episode) {
	 *         echo "  - Эпизод {$episode->episodeNumber}: {$episode->getDisplayName()}\n";
	 *     }
	 * }
	 * ```
	 */
	public function getSeasons(int $id): DefaultResponse {
		$data = $this->get($this->buildUri("films/{$id}/seasons"));

		return DefaultResponse::fromArray($data, Season::class);
	}

	/**
	 * Получает факты и ошибки фильма
	 *
	 * READ операция - извлекает интересные факты, ошибки и забавные моменты,
	 * связанные с фильмом. Включает как факты, так и ошибки в кинематографе.
	 *
	 * @api /api/v2.2/films/{id}/facts
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив фактов и ошибок фильма
	 *
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException При других ошибках API
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @example
	 * ```php
	 * $facts = $filmService->getFacts(301);
	 * foreach ($facts as $fact) {
	 *     if ($fact->isFact()) {
	 *         echo "Факт: {$fact->text}\n";
	 *     } else {
	 *         echo "Ошибка: {$fact->text}\n";
	 *     }
	 * }
	 * ```
	 */
	public function getFacts(int $id): DefaultResponse {
		$data = $this->get($this->buildUri("films/{$id}/facts"));

		return DefaultResponse::fromArray($data, Fact::class);
	}

	/**
	 * Получает данные о прокате фильма
	 *
	 * READ операция - извлекает информацию о прокате фильма в различных странах,
	 * включая даты премьер и ограничения по возрасту.
	 *
	 * @api /api/v2.2/films/{id}/distributions
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив данных о прокате
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException При других ошибках API
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @example
	 * ```php
	 * $distributions = $filmService->getDistributions(301);
	 * foreach ($distributions as $dist) {
	 *     echo "Страна: {$dist->country}, Премьера: {$dist->date}\n";
	 * }
	 * ```
	 */
	public function getDistributions(int $id): DefaultResponse {
		$data = $this->get($this->buildUri("films/{$id}/distributions"));

		return DefaultResponse::fromArray($data, Distribution::class);
	}

	/**
	 * Получает данные о бюджете и сборах фильма
	 *
	 * READ операция - извлекает финансовую информацию о фильме:
	 * бюджет, сборы в разных странах, рентабельность.
	 *
	 * @api /api/v2.2/films/{id}/box_office
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\BudgetResponse Массив данных о бюджете и сборах
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @example
	 * ```php
	 * $boxOffice = $filmService->getBoxOffice(301);
	 * foreach ($boxOffice as $box) {
	 *     echo "Тип: {$box->type}, Сумма: {$box->amount} {$box->currency}\n";
	 * }
	 * ```
	 */
	public function getBoxOffice(int $id): BudgetResponse {
		$data = $this->get($this->buildUri("films/{$id}/box_office"));

		return BudgetResponse::fromArray($data, BoxOffice::class);
	}

	/**
	 * Получает награды фильма
	 *
	 * READ операция - извлекает информацию о наградах, полученных фильмом
	 * на различных фестивалях и церемониях.
	 *
	 * @api /api/v2.2/films/{id}/awards
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив наград фильма
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException При других ошибках API
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @example
	 * ```php
	 * $awards = $filmService->getAwards(301);
	 * foreach ($awards as $award) {
	 *     echo "Награда: {$award->name}, Номинация: {$award->nomination}\n";
	 * }
	 * ```
	 */
	public function getAwards(int $id): DefaultResponse {
		$data = $this->get($this->buildUri("films/{$id}/awards"));

		return DefaultResponse::fromArray($data, Award::class);
	}

	/**
	 * Получает видео материалы фильма
	 *
	 * READ операция - извлекает трейлеры, тизеры и другие видео материалы,
	 * связанные с фильмом.
	 *
	 * @api /api/v2.2/films/{id}/videos
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив видео материалов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException|\NotKinopoisk\Exception\KpValidationException Если фильм не найден
	 * @example
	 * ```php
	 * $videos = $filmService->getVideos(301);
	 * foreach ($videos as $video) {
	 *     echo "Название: {$video->name}, Сайт: {$video->site}\n";
	 *     echo "URL: {$video->url}\n";
	 * }
	 * ```
	 */
	public function getVideos(int $id): DefaultResponse {
		$data = $this->get($this->buildUri("films/{$id}/videos"));

		return DefaultResponse::fromArray($data, Video::class);
	}

	/**
	 * Получает похожие фильмы
	 *
	 * READ операция - извлекает список фильмов, похожих на указанный,
	 * на основе жанров, актеров и других критериев.
	 *
	 * @api /api/v2.2/films/{id}/similars
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив похожих фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @example
	 * ```php
	 * $similar = $filmService->getSimilar(301);
	 * foreach ($similar as $film) {
	 *     echo "Похожий фильм: {$film->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getSimilar(int $id): DefaultResponse {
		$data = $this->get($this->buildUri("films/{$id}/similars"));

		return DefaultResponse::fromArray($data, RelatedFilm::class);
	}

	/**
	 * Получает изображения фильма
	 *
	 * READ операция - извлекает различные изображения, связанные с фильмом:
	 * кадры, постеры, обложки, промо-материалы.
	 *
	 * @api /api/v2.2/films/{id}/images
	 *
	 * @param   int        $id    Уникальный идентификатор фильма в Кинопоиске
	 * @param   ImageType  $type  Тип изображений
	 * @param   int        $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse Массив изображений
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @example
	 * ```php
	 * // Получение кадров из фильма
	 * $stills = $filmService->getImages(301, 'STILL');
	 *
	 * // Получение постеров
	 * $posters = $filmService->getImages(301, 'POSTER');
	 *
	 * foreach ($stills as $image) {
	 *     echo "Изображение: {$image->imageUrl}\n";
	 * }
	 * ```
	 */
	public function getImages(int $id, ImageType $type = ImageType::STILL, int $page = 1): PaginatedResponse {
		$data = $this->get($this->buildUri("films/{$id}/images"), [
			'type' => $type->value,
			'page' => $page,
		]);

		$response              = PaginatedResponse::fromArray($data, Image::class);
		$response->currentPage = $page;

		return $response;
	}

	/**
	 * Получает отзывы на фильм
	 *
	 * READ операция - извлекает пользовательские отзывы и рецензии
	 * на фильм с возможностью сортировки и пагинации.
	 *
	 * @api /api/v2.2/films/{id}/reviews
	 *
	 * @param   int                              $id     Уникальный идентификатор фильма в Кинопоиске
	 * @param   int                              $page   Номер страницы для пагинации
	 * @param   \NotKinopoisk\Enums\ReviewOrder  $order  Порядок сортировки отзывов
	 *
	 * @return \NotKinopoisk\Responses\ReviewResponse Массив отзывов
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @example
	 * ```php
	 * // Получение последних отзывов
	 * $reviews = $filmService->getReviews(301, 1, ReviewOrder::DATE_DESC);
	 *
	 * // Получение положительных отзывов
	 * $positiveReviews = $filmService->getReviews(301, 1, ReviewOrder::USER_POSITIVE_RATING_DESC);
	 *
	 * foreach ($reviews as $review) {
	 *     echo "Автор: {$review->author}\n";
	 *     echo "Отзыв: {$review->description}\n";
	 * }
	 * ```
	 */
	public function getReviews(int $id, int $page = 1, ReviewOrder $order = ReviewOrder::DATE_DESC): ReviewResponse {
		$data = $this->get($this->buildUri("films/{$id}/reviews"), [
			'page'  => $page,
			'order' => $order->value,
		]);

		$response              = ReviewResponse::fromArray($data, Review::class);
		$response->currentPage = $page;

		return $response;
	}

	/**
	 * Получает внешние источники отзывов
	 *
	 * READ операция - извлекает отзывы и рецензии на фильм из внешних
	 * источников (другие сайты, блоги, СМИ).
	 *
	 * @api /api/v2.2/films/{id}/external_sources
	 *
	 * @param   int  $id    Уникальный идентификатор фильма в Кинопоиске
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse Массив внешних источников
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @example
	 * ```php
	 * $sources = $filmService->getExternalSources(301);
	 * foreach ($sources as $source) {
	 *     echo "Платформа: {$source->platform}\n";
	 *     echo "URL: {$source->url}\n";
	 *     echo "Описание: {$source->description}\n";
	 * }
	 * ```
	 */
	public function getExternalSources(int $id, int $page = 1): PaginatedResponse {
		$data               = $this->get($this->buildUri("films/{$id}/external_sources"), [
			'page' => $page,
		]);
		$data['totalPages'] = ceil($data['total'] / 50);

		$response              = PaginatedResponse::fromArray($data, ExternalSource::class);
		$response->currentPage = $page;

		return $response;
	}

	/**
	 * Получает сиквелы и приквелы фильма
	 *
	 * Извлекает информацию о связанных фильмах (сиквелы, приквелы, ремейки).
	 * Возвращает массив связанных фильмов с указанием типа связи.
	 *
	 * @api /api/v2.1/films/{id}/sequels_and_prequels
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Responses\SequelPrequelResponse Массив связанных фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @example
	 * ```php
	 * $sequels = $filmService->getSequelsAndPrequels(301);
	 * foreach ($sequels->items as $sequel) {
	 *     echo "{$sequel->getDisplayName()} - {$sequel->relationType->value}\n";
	 * }
	 * ```
	 */
	public function getSequelsAndPrequels(int $id): SequelPrequelResponse {
		$data = $this->get($this->buildUri("films/{$id}/sequels_and_prequels", ApiVersion::V2_1));
		return SequelPrequelResponse::fromArray($data, RelatedFilm::class);
	}

	/**
	 * Поиск фильмов по ключевым словам
	 *
	 * Создает поисковый запрос по ключевым словам.
	 * Возвращает коллекцию фильмов, соответствующих поисковому запросу.
	 *
	 * @api /api/v2.1/films/search-by-keyword
	 *
	 * @param   string  $keyword  Ключевые слова для поиска
	 * @param   int     $page     Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\KeywordSearchResponse Коллекция найденных фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException
	 * @example
	 * ```php
	 * $results = $filmService->searchByKeyword('матрица');
	 * echo "Найдено фильмов: {$results->getCount()}\n";
	 *
	 * foreach ($results->items as $film) {
	 *     echo "- {$film->getDisplayName()} ({$film->year})\n";
	 * }
	 * ```
	 */
	public function searchByKeyword(string $keyword, int $page = 1): KeywordSearchResponse {
		$filters = [
			'keyword' => $keyword,
			'page'    => $page,
		];

		$data = $this->get($this->buildUri('films/search-by-keyword', ApiVersion::V2_1), $filters);

		$response              = KeywordSearchResponse::fromArray($data, FilmSearchResult::class);
		$response->currentPage = $page;

		return $response;
	}

	/**
	 * Получает премьеры фильмов
	 *
	 * READ операция - извлекает информацию о премьерах фильмов
	 * в указанном году и месяце.
	 *
	 * @api /api/v2.1/films/search-by-keyword
	 *
	 *
	 * @param   int                        $year   Год премьер
	 * @param   \NotKinopoisk\Enums\Month  $month  Месяц премьер
	 *
	 * @return \NotKinopoisk\Responses\DefaultResponse Массив премьер
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException
	 * @example
	 * ```php
	 * $premieres = $filmService->getPremieres(2024, Month::JUNE);
	 * foreach ($premieres as $premiere) {
	 *     echo "Премьера: {$premiere->getDisplayName()} - {$premiere->premiereRu}\n";
	 * }
	 * ```
	 */
	public function getPremieres(int $year, Month $month): DefaultResponse {
		$data = $this->get($this->buildUri("films/premieres"), [
			'year'  => $year,
			'month' => $month->value,
		]);

		return DefaultResponse::fromArray($data, Premiere::class);
	}

	/**
	 * Получает фильтры для поиска
	 *
	 * READ операция - извлекает доступные фильтры для поиска фильмов:
	 * жанры, страны, годы и другие параметры.
	 *
	 * @api /api/v2.2/films/filters
	 *
	 * @return \NotKinopoisk\Models\Filters Объект с доступными фильтрами
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 *
	 * @example
	 * ```php
	 * $filters = $filmService->getFilters();
	 *
	 * echo "Доступные жанры:\n";
	 * foreach ($filters->genres as $genre) {
	 *     echo "- {$genre['genre']}\n";
	 * }
	 *
	 * echo "Доступные страны:\n";
	 * foreach ($filters->countries as $country) {
	 *     echo "- {$country['country']}\n";
	 * }
	 * ```
	 */
	public function getFilters(): Filters {
		$data = $this->get($this->buildUri("films/filters"));

		return Filters::fromArray($data);
	}

	/**
	 * Получает популярные фильмы
	 *
	 * READ операция - извлекает список популярных фильмов
	 * с возможностью пагинации.
	 *
	 * @api /api/v2.2/films/collections
	 *
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse Коллекция популярных фильмов
	 * @throws \NotKinopoisk\Exception\ApiException|\NotKinopoisk\Exception\KpValidationException При ошибках API
	 *
	 * @example
	 * ```php
	 * $popular = $filmService->getPopular();
	 * echo "Популярных фильмов: {$popular->getCount()}\n";
	 *
	 * foreach ($popular->items as $film) {
	 *     echo "- {$film->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getPopular(int $page = 1): PaginatedResponse {
		return $this->getCollections(CollectionType::TOP_POPULAR_ALL, $page);
	}

	/**
	 * Получает коллекции фильмов
	 *
	 * READ операция - извлекает предустановленные коллекции фильмов:
	 * популярные, топ-250, новинки и другие подборки.
	 *
	 * @api /api/v2.2/films/collections
	 *
	 * @param   CollectionType  $type  Тип коллекции
	 * @param   int             $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse Коллекция фильмов
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException
	 * @throws \NotKinopoisk\Exception\KpValidationException
	 * @throws \NotKinopoisk\Exception\RateLimitException
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException
	 * @example
	 * ```php
	 * // Получение топ-250 фильмов
	 * $top250 = $filmService->getCollections('TOP_250_MOVIES');
	 *
	 * // Получение популярных сериалов
	 * $popularSeries = $filmService->getCollections('TOP_POPULAR_SERIES');
	 *
	 * echo "В коллекции: {$top250->getCount()} фильмов\n";
	 * ```
	 */
	public function getCollections(CollectionType $type = CollectionType::TOP_POPULAR_ALL, int $page = 1): PaginatedResponse {
		$data = $this->get($this->buildUri("films/collections"), [
			'type' => $type->value,
			'page' => $page,
		]);

		$response              = PaginatedResponse::fromArray($data, FilmCollection::class);
		$response->currentPage = $page;

		return $response;
	}

	/**
	 * Получает топ-250 фильмов
	 *
	 * READ операция - извлекает список топ-250 фильмов по версии Кинопоиска
	 * с возможностью пагинации.
	 *
	 * @api /api/v2.2/films/collections
	 *
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse Коллекция топ-250 фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException|\NotKinopoisk\Exception\KpValidationException При ошибках API
	 *
	 * @example
	 * ```php
	 * $top250 = $filmService->getTop250();
	 * echo "Топ-250 фильмов: {$top250->getCount()}\n";
	 *
	 * foreach ($top250->items as $film) {
	 *     echo "- {$film->getDisplayName()} (рейтинг: {$film->ratingKinopoisk})\n";
	 * }
	 * ```
	 */
	public function getTop250(int $page = 1): PaginatedResponse {
		return $this->getCollections(CollectionType::TOP_250_MOVIES, $page);
	}

	/**
	 * Поиск фильмов по фильтрам через Kinopoisk API
	 *
	 * Выполняет поиск фильмов с возможностью применения различных фильтров:
	 * страны, жанры, рейтинг, год выпуска, тип контента и другие параметры.
	 * Возвращает пагинированный результат с коллекцией найденных фильмов.
	 *
	 * Метод выполняет валидацию входных параметров и генерирует исключения
	 * при некорректных значениях. Поддерживает множественный выбор стран
	 * и жанров через массивы объектов.
	 *
	 * @api     /api/v2.2/films
	 * @since   1.0.0
	 *
	 * @see     \NotKinopoisk\Models\FilmCollection
	 * @see     \NotKinopoisk\Enums\FilmOrder
	 * @see     \NotKinopoisk\Enums\ContentType
	 * @see     \NotKinopoisk\Responses\PaginatedResponse
	 *
	 * @param   array|null                       $country     Массив объектов стран для фильтрации или null для всех стран
	 * @param   array|null                       $genre       Массив объектов жанров для фильтрации или null для всех жанров
	 * @param   \NotKinopoisk\Enums\FilmOrder    $order       Порядок сортировки результатов (по умолчанию RATING)
	 * @param   \NotKinopoisk\Enums\ContentType  $type        Тип контента для фильтрации (по умолчанию ALL)
	 * @param   float                            $ratingFrom  Минимальный рейтинг фильмов (от 0 до 10, по умолчанию 0)
	 * @param   float                            $ratingTo    Максимальный рейтинг фильмов (от 0 до 10, по умолчанию 10)
	 * @param   int                              $yearFrom    Начальный год выпуска (по умолчанию 1000)
	 * @param   int                              $yearTo      Конечный год выпуска (по умолчанию 3000)
	 * @param   string|null                      $imdbId      Идентификатор IMDb для поиска конкретного фильма или null
	 * @param   string|null                      $keyword     Ключевое слово для поиска в названии или null
	 * @param   int                              $page        Номер страницы результатов (начиная с 1, по умолчанию 1)
	 *
	 * @return  \NotKinopoisk\Responses\PaginatedResponse  Пагинированный ответ с коллекцией фильмов
	 *
	 * @throws  \NotKinopoisk\Exception\KpValidationException  При некорректных значениях параметров:
	 *                                                         - ratingFrom меньше 0
	 *                                                         - ratingTo больше 10
	 *                                                         - page меньше 1
	 *
	 * @example
	 * ```php
	 * // Поиск российских драм с высоким рейтингом
	 * $response = $service->searchFilmsByFilter(
	 *     country: [$russiaCountry],
	 *     genre: [$dramaGenre],
	 *     ratingFrom: 7.0,
	 *     yearFrom: 2000,
	 *     order: FilmOrder::RATING
	 * );
	 *
	 * // Поиск по ключевому слову
	 * $response = $service->searchFilmsByFilter(
	 *     keyword: 'Матрица',
	 *     type: ContentType::FILM
	 * );
	 *
	 * // Пагинация результатов
	 * $secondPage = $service->searchFilmsByFilter(page: 2);
	 * ```
	 */
	public function searchFilmsByFilter(
		?array      $country = NULL,
		?array      $genre = NULL,
		FilmOrder   $order = FilmOrder::RATING,
		ContentType $type = ContentType::ALL,
		float       $ratingFrom = 0,
		float       $ratingTo = 10,
		int         $yearFrom = 1000,
		int         $yearTo = 3000,
		?string     $imdbId = NULL,
		?string     $keyword = NULL,
		int         $page = 1,
	): PaginatedResponse {
		$filterQuery = [];

		if ($ratingFrom < 0) {
			throw new KpValidationException('Параметр "ratingFrom" не может быть отрицательным');
		}

		if ($ratingTo > 10) {
			throw new KpValidationException('Параметр "ratingTo" не может быть больше 10');
		}

		if ($page < 1) {
			throw new KpValidationException('Параметр "page" не может быть меньше 1');
		}

		if (!is_null($country)) {
			$countryString = [];
			foreach ($country as $countryCode) {
				$countryString[] = "countries={$countryCode->country}";
			}
			$filterQuery[] = implode('&', $countryString);
			unset($countryString);
		}

		if (!is_null($genre)) {
			$genreString = [];
			foreach ($genre as $genreCode) {
				$genreString[] = "genres={$genreCode->genre}";
			}
			$filterQuery[] = implode('&', $genreString);
			unset($genreString);
		}

		if (!is_null($imdbId)) {
			$filterQuery['imdbId'] = $imdbId;
		}

		if (!is_null($keyword)) {
			$filterQuery['keyword'] = $keyword;
		}

		$filterQuery['order']      = $order->value;
		$filterQuery['type']       = $type->value;
		$filterQuery['ratingFrom'] = $ratingFrom;
		$filterQuery['ratingTo']   = $ratingTo;
		$filterQuery['yearFrom']   = $yearFrom;
		$filterQuery['yearTo']     = $yearTo;
		$filterQuery['page']       = $page;

		$data = $this->get($this->buildUri('films'), $filterQuery);

		$response              = PaginatedResponse::fromArray($data, FilmCollection::class);
		$response->currentPage = $page;

		return $response;
	}

}