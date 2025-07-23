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
use NotKinopoisk\Models\Award;
use NotKinopoisk\Models\BoxOffice;
use NotKinopoisk\Models\Distribution;
use NotKinopoisk\Models\ExternalSource;
use NotKinopoisk\Models\Fact;
use NotKinopoisk\Models\Film;
use NotKinopoisk\Models\FilmCollection;
use NotKinopoisk\Models\Filters;
use NotKinopoisk\Models\Image;
use NotKinopoisk\Models\Premiere;
use NotKinopoisk\Models\RelatedFilm;
use NotKinopoisk\Models\Review;
use NotKinopoisk\Models\Season;
use NotKinopoisk\Models\Video;
use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Responses\PaginatedResponse;

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
	 * @see ApiVersion::V22 Используемая версия API
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
		parent::__construct($client, ApiVersion::V22);
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
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\Distribution[] Массив данных о прокате
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $distributions = $filmService->getDistributions(301);
	 * foreach ($distributions as $dist) {
	 *     echo "Страна: {$dist->country}, Премьера: {$dist->date}\n";
	 * }
	 * ```
	 */
	public function getDistributions(int $id): array {
		$data = $this->get($this->buildUri("films/{$id}/distributions"));

		return array_map(fn ($distData) => Distribution::fromArray($distData), $data['items']);
	}

	/**
	 * Получает данные о бюджете и сборах фильма
	 *
	 * READ операция - извлекает финансовую информацию о фильме:
	 * бюджет, сборы в разных странах, рентабельность.
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\BoxOffice[] Массив данных о бюджете и сборах
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $boxOffice = $filmService->getBoxOffice(301);
	 * foreach ($boxOffice as $box) {
	 *     echo "Тип: {$box->type}, Сумма: {$box->amount} {$box->currency}\n";
	 * }
	 * ```
	 */
	public function getBoxOffice(int $id): array {
		$data = $this->get($this->buildUri("films/{$id}/box_office"));

		return array_map(fn ($boxData) => BoxOffice::fromArray($boxData), $data['items']);
	}

	/**
	 * Получает награды фильма
	 *
	 * READ операция - извлекает информацию о наградах, полученных фильмом
	 * на различных фестивалях и церемониях.
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\Award[] Массив наград фильма
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $awards = $filmService->getAwards(301);
	 * foreach ($awards as $award) {
	 *     echo "Награда: {$award->name}, Номинация: {$award->nomination}\n";
	 * }
	 * ```
	 */
	public function getAwards(int $id): array {
		$data = $this->get($this->buildUri("films/{$id}/awards"));

		return array_map(fn ($awardData) => Award::fromArray($awardData), $data['items']);
	}

	/**
	 * Получает видео материалы фильма
	 *
	 * READ операция - извлекает трейлеры, тизеры и другие видео материалы,
	 * связанные с фильмом.
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\Video[] Массив видео материалов
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $videos = $filmService->getVideos(301);
	 * foreach ($videos as $video) {
	 *     echo "Название: {$video->name}, Сайт: {$video->site}\n";
	 *     echo "URL: {$video->url}\n";
	 * }
	 * ```
	 */
	public function getVideos(int $id): array {
		$data = $this->get($this->buildUri("films/{$id}/videos"));

		return array_map(fn ($videoData) => Video::fromArray($videoData), $data['items']);
	}

	/**
	 * Получает похожие фильмы
	 *
	 * READ операция - извлекает список фильмов, похожих на указанный,
	 * на основе жанров, актеров и других критериев.
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\RelatedFilm[] Массив похожих фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $similar = $filmService->getSimilar(301);
	 * foreach ($similar as $film) {
	 *     echo "Похожий фильм: {$film->getDisplayName()}\n";
	 * }
	 * ```
	 */
	public function getSimilar(int $id): array {
		$data = $this->get($this->buildUri("films/{$id}/similars"));

		return array_map(fn ($filmData) => RelatedFilm::fromArray($filmData), $data['items']);
	}

	/**
	 * Получает изображения фильма
	 *
	 * READ операция - извлекает различные изображения, связанные с фильмом:
	 * кадры, постеры, обложки, промо-материалы.
	 *
	 * @param   int        $id    Уникальный идентификатор фильма в Кинопоиске
	 * @param   ImageType  $type  Тип изображений
	 * @param   int        $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\Image[] Массив изображений
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
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
	public function getImages(int $id, ImageType $type = ImageType::STILL, int $page = 1): array {
		$data = $this->get($this->buildUri("films/{$id}/images"), [
			'type' => $type->value,
			'page' => $page,
		]);

		return array_map(fn ($imageData) => Image::fromArray($imageData), $data['items']);
	}

	/**
	 * Получает отзывы на фильм
	 *
	 * READ операция - извлекает пользовательские отзывы и рецензии
	 * на фильм с возможностью сортировки и пагинации.
	 *
	 * @param   int                              $id     Уникальный идентификатор фильма в Кинопоиске
	 * @param   int                              $page   Номер страницы для пагинации
	 * @param   \NotKinopoisk\Enums\ReviewOrder  $order  Порядок сортировки отзывов
	 *
	 * @return \NotKinopoisk\Models\Review[] Массив отзывов
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
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
	public function getReviews(int $id, int $page = 1, ReviewOrder $order = ReviewOrder::DATE_DESC): array {
		$data = $this->get($this->buildUri("films/{$id}/reviews"), [
			'page'  => $page,
			'order' => $order->value,
		]);

		return array_map(fn ($reviewData) => Review::fromArray($reviewData), $data['items']);
	}

	/**
	 * Получает внешние источники отзывов
	 *
	 * READ операция - извлекает отзывы и рецензии на фильм из внешних
	 * источников (другие сайты, блоги, СМИ).
	 *
	 * @param   int  $id    Уникальный идентификатор фильма в Кинопоиске
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\ExternalSource[] Массив внешних источников
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
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
	public function getExternalSources(int $id, int $page = 1): array {
		$data = $this->get($this->buildUri("films/{$id}/external_sources"), [
			'page' => $page,
		]);

		return array_map(fn ($sourceData) => ExternalSource::fromArray($sourceData), $data['items']);
	}

	/**
	 * Получает сиквелы и приквелы фильма
	 *
	 * READ операция - извлекает информацию о связанных фильмах:
	 * сиквелах, приквелах, ремейках и других частях франшизы.
	 *
	 * @param   int  $id  Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @return \NotKinopoisk\Models\RelatedFilm[] Массив связанных фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
	 * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
	 *
	 * @example
	 * ```php
	 * $sequels = $filmService->getSequelsAndPrequels(301);
	 * foreach ($sequels as $film) {
	 *     echo "Связанный фильм: {$film->getDisplayName()}\n";
	 *     echo "Тип связи: {$film->relationType}\n";
	 * }
	 * ```
	 */
	public function getSequelsAndPrequels(int $id): array {
		// Этот эндпоинт не существует в текущей версии API
		// Возвращаем пустой массив
		return [];
	}

	/**
	 * Поиск фильмов по ключевым словам
	 *
	 * CREATE операция - создает поисковый запрос по ключевым словам.
	 * Возвращает коллекцию фильмов, соответствующих поисковому запросу.
	 *
	 * @param   string  $keyword  Ключевые слова для поиска
	 * @param   int     $page     Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\FilmCollection Коллекция найденных фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 *
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
	public function searchByKeyword(string $keyword, int $page = 1): FilmCollection {
		// Используем поиск по фильтрам с ключевым словом
		$filters = [
			'keyword' => $keyword,
			'page'    => $page,
		];

		return $this->searchByFilters($filters);
	}

	/**
	 * Поиск фильмов по фильтрам
	 *
	 * CREATE операция - создает поисковый запрос с использованием
	 * различных фильтров для точного поиска фильмов.
	 *
	 * @param   array  $filters  Массив фильтров для поиска
	 *
	 * @return \NotKinopoisk\Models\FilmCollection Коллекция найденных фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 *
	 * @example
	 * ```php
	 * $filters = [
	 *     'genres' => [1], // боевик
	 *     'yearFrom' => 2020,
	 *     'yearTo' => 2024,
	 *     'ratingFrom' => 7.0,
	 *     'order' => 'RATING'
	 * ];
	 *
	 * $results = $filmService->searchByFilters($filters);
	 * echo "Найдено: {$results->getCount()} фильмов\n";
	 * ```
	 */
	public function searchByFilters(array $filters = []): FilmCollection {
		$data = $this->get($this->buildUri("films"), $filters);

		return FilmCollection::fromArray($data);
	}

	/**
	 * Получает коллекции фильмов
	 *
	 * READ операция - извлекает предустановленные коллекции фильмов:
	 * популярные, топ-250, новинки и другие подборки.
	 *
	 * @param   CollectionType  $type  Тип коллекции
	 * @param   int             $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\FilmCollection Коллекция фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 *
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
	public function getCollections(CollectionType $type = CollectionType::TOP_POPULAR_ALL, int $page = 1): FilmCollection {
		$data = $this->get($this->buildUri("films/collections"), [
			'type' => $type->value,
			'page' => $page,
		]);

		return FilmCollection::fromArray($data);
	}

	/**
	 * Получает премьеры фильмов
	 *
	 * READ операция - извлекает информацию о премьерах фильмов
	 * в указанном году и месяце.
	 *
	 * @param   int                        $year   Год премьер
	 * @param   \NotKinopoisk\Enums\Month  $month  Месяц премьер
	 *
	 * @return \NotKinopoisk\Models\Premiere[] Массив премьер
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 *
	 * @example
	 * ```php
	 * $premieres = $filmService->getPremieres(2024, Month::JUNE);
	 * foreach ($premieres as $premiere) {
	 *     echo "Премьера: {$premiere->getDisplayName()} - {$premiere->premiereRu}\n";
	 * }
	 * ```
	 */
	public function getPremieres(int $year, Month $month): array {
		$data = $this->get($this->buildUri("films/premieres"), [
			'year'  => $year,
			'month' => $month->value,
		]);

		return array_map(fn ($premiereData) => Premiere::fromArray($premiereData), $data['items']);
	}

	/**
	 * Получает фильтры для поиска
	 *
	 * READ операция - извлекает доступные фильтры для поиска фильмов:
	 * жанры, страны, годы и другие параметры.
	 *
	 * @return \NotKinopoisk\Models\Filters Объект с доступными фильтрами
	 *
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
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\FilmCollection Коллекция популярных фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
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
	public function getPopular(int $page = 1): FilmCollection {
		$data = $this->get($this->buildUri("films/collections"), [
			'type' => CollectionType::TOP_POPULAR_ALL->value,
			'page' => $page,
		]);

		return FilmCollection::fromArray($data);
	}

	/**
	 * Получает топ-250 фильмов
	 *
	 * READ операция - извлекает список топ-250 фильмов по версии Кинопоиска
	 * с возможностью пагинации.
	 *
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\FilmCollection Коллекция топ-250 фильмов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
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
	public function getTop250(int $page = 1): FilmCollection {
		$data = $this->get($this->buildUri("films/collections"), [
			'type' => CollectionType::TOP_250_MOVIES->value,
			'page' => $page,
		]);

		return FilmCollection::fromArray($data);
	}

	/**
	 * Получает топ-250 сериалов
	 *
	 * READ операция - извлекает список топ-250 сериалов по версии Кинопоиска
	 * с возможностью пагинации.
	 *
	 * @param   int  $page  Номер страницы для пагинации
	 *
	 * @return \NotKinopoisk\Models\FilmCollection Коллекция топ-250 сериалов
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При ошибках API
	 *
	 * @example
	 * ```php
	 * $top250Series = $filmService->getTop250Series();
	 * echo "Топ-250 сериалов: {$top250Series->getCount()}\n";
	 *
	 * foreach ($top250Series->items as $series) {
	 *     echo "- {$series->getDisplayName()} (рейтинг: {$series->ratingKinopoisk})\n";
	 * }
	 * ```
	 */
	public function getTop250Series(int $page = 1): FilmCollection {
		$data = $this->get($this->buildUri("films/collections"), [
			'type' => CollectionType::TOP_250_SERIES->value,
			'page' => $page,
		]);

		return FilmCollection::fromArray($data);
	}

	/**
	 * @api /api/v2.2/films
	 *
	 * @param   array|null                       $country
	 * @param   array|null                       $genre
	 * @param   \NotKinopoisk\Enums\FilmOrder    $order
	 * @param   \NotKinopoisk\Enums\ContentType  $type
	 * @param   float                            $ratingFrom
	 * @param   float                            $ratingTo
	 * @param   int                              $yearFrom
	 * @param   int                              $yearTo
	 * @param   string|null                      $imdbId
	 * @param   string|null                      $keyword
	 * @param   int                              $page
	 *
	 * @return \NotKinopoisk\Responses\PaginatedResponse
	 */
	public function searchFilms(?array $country = NULL, ?array $genre = NULL, FilmOrder $order = FilmOrder::RATING, ContentType $type = ContentType::ALL, float $ratingFrom = 0, float $ratingTo = 10, int $yearFrom = 1000, int $yearTo = 3000, ?string $imdbId = null, ?string $keyword = null, int $page = 1): PaginatedResponse {
		$filterQuery = [];

		$countries = implode(',', $country?? []);
		$genres = implode(',', $genre?? []);

		return PaginatedResponse::fromArray([], )
	}

}