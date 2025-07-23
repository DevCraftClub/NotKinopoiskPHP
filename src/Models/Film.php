<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

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
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * @see \NotKinopoisk\Models\FilmCollection
 * 
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
class Film
{
    /**
     * Конструктор модели фильма
     * 
     * Создает новый экземпляр фильма со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $kinopoiskId Уникальный идентификатор фильма в Кинопоиске
     * @param string|null $kinopoiskHDId Идентификатор фильма в Кинопоиск HD (если доступен)
     * @param string|null $imdbId Идентификатор фильма в IMDb
     * @param string|null $nameRu Название фильма на русском языке
     * @param string|null $nameEn Название фильма на английском языке
     * @param string|null $nameOriginal Оригинальное название фильма
     * @param string $posterUrl URL постера фильма в высоком разрешении
     * @param string $posterUrlPreview URL постера фильма в низком разрешении
     * @param string|null $coverUrl URL обложки фильма
     * @param string|null $logoUrl URL логотипа фильма
     * @param int|null $reviewsCount Количество рецензий на фильм
     * @param float|null $ratingGoodReview Рейтинг хороших рецензий
     * @param int|null $ratingGoodReviewVoteCount Количество голосов за хорошие рецензии
     * @param float|null $ratingKinopoisk Рейтинг Кинопоиска
     * @param int|null $ratingKinopoiskVoteCount Количество голосов на Кинопоиске
     * @param float|null $ratingImdb Рейтинг IMDb
     * @param int|null $ratingImdbVoteCount Количество голосов на IMDb
     * @param float|null $ratingFilmCritics Рейтинг кинокритиков
     * @param int|null $ratingFilmCriticsVoteCount Количество голосов кинокритиков
     * @param float|null $ratingAwait Рейтинг ожидания
     * @param int|null $ratingAwaitCount Количество голосов ожидания
     * @param float|null $ratingRfCritics Рейтинг российских кинокритиков
     * @param int|null $ratingRfCriticsVoteCount Количество голосов российских кинокритиков
     * @param string|null $webUrl URL страницы фильма на Кинопоиске
     * @param int|null $year Год выпуска фильма
     * @param int|null $filmLength Длительность фильма в минутах
     * @param string|null $slogan Слоган фильма
     * @param string|null $description Полное описание фильма
     * @param string|null $shortDescription Краткое описание фильма
     * @param string|null $editorAnnotation Редакторская аннотация
     * @param bool|null $isTicketsAvailable Доступны ли билеты в кинотеатрах
     * @param string|null $productionStatus Статус производства фильма
     * @param string $type Тип контента (FILM, TV_SERIES, MINI_SERIES, TV_SHOW)
     * @param string|null $ratingMpaa Рейтинг MPAA
     * @param string|null $ratingAgeLimits Возрастные ограничения
     * @param bool|null $hasImax Доступен ли в формате IMAX
     * @param bool|null $has3D Доступен ли в формате 3D
     * @param string|null $lastSync Время последней синхронизации данных
     * @param array $countries Массив стран производства
     * @param array $genres Массив жанров фильма
     * @param int|null $startYear Год начала производства (для сериалов)
     * @param int|null $endYear Год окончания производства (для сериалов)
     * @param bool|null $serial Является ли сериалом
     * @param bool|null $shortFilm Является ли короткометражным фильмом
     * @param bool|null $completed Завершен ли (для сериалов)
     * 
     * @example
     * ```php
     * $film = new Film(
     *     kinopoiskId: 301,
     *     nameRu: 'Матрица',
     *     nameEn: 'The Matrix',
     *     posterUrl: 'https://...',
     *     posterUrlPreview: 'https://...',
     *     type: 'FILM',
     *     year: 1999,
     *     countries: [],
     *     genres: []
     * );
     * ```
     */
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $kinopoiskHDId,
        public readonly ?string $imdbId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly ?string $coverUrl,
        public readonly ?string $logoUrl,
        public readonly ?int $reviewsCount,
        public readonly ?float $ratingGoodReview,
        public readonly ?int $ratingGoodReviewVoteCount,
        public readonly ?float $ratingKinopoisk,
        public readonly ?int $ratingKinopoiskVoteCount,
        public readonly ?float $ratingImdb,
        public readonly ?int $ratingImdbVoteCount,
        public readonly ?float $ratingFilmCritics,
        public readonly ?int $ratingFilmCriticsVoteCount,
        public readonly ?float $ratingAwait,
        public readonly ?int $ratingAwaitCount,
        public readonly ?float $ratingRfCritics,
        public readonly ?int $ratingRfCriticsVoteCount,
        public readonly ?string $webUrl,
        public readonly ?int $year,
        public readonly ?int $filmLength,
        public readonly ?string $slogan,
        public readonly ?string $description,
        public readonly ?string $shortDescription,
        public readonly ?string $editorAnnotation,
        public readonly ?bool $isTicketsAvailable,
        public readonly ?string $productionStatus,
        public readonly string $type,
        public readonly ?string $ratingMpaa,
        public readonly ?string $ratingAgeLimits,
        public readonly ?bool $hasImax,
        public readonly ?bool $has3D,
        public readonly ?string $lastSync,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?int $startYear,
        public readonly ?int $endYear,
        public readonly ?bool $serial,
        public readonly ?bool $shortFilm,
        public readonly ?bool $completed
    ) {
    }

    /**
     * Создает экземпляр фильма из массива данных API
     * 
     * Статический метод для удобного создания объекта Film из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
     * и устанавливает значения по умолчанию.
     * 
     * @param array $data Массив данных фильма от API
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
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            kinopoiskHDId: $data['kinopoiskHDId'] ?? null,
            imdbId: $data['imdbId'] ?? null,
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            coverUrl: $data['coverUrl'] ?? null,
            logoUrl: $data['logoUrl'] ?? null,
            reviewsCount: $data['reviewsCount'] ?? null,
            ratingGoodReview: $data['ratingGoodReview'] ?? null,
            ratingGoodReviewVoteCount: $data['ratingGoodReviewVoteCount'] ?? null,
            ratingKinopoisk: $data['ratingKinopoisk'] ?? null,
            ratingKinopoiskVoteCount: $data['ratingKinopoiskVoteCount'] ?? null,
            ratingImdb: $data['ratingImdb'] ?? null,
            ratingImdbVoteCount: $data['ratingImdbVoteCount'] ?? null,
            ratingFilmCritics: $data['ratingFilmCritics'] ?? null,
            ratingFilmCriticsVoteCount: $data['ratingFilmCriticsVoteCount'] ?? null,
            ratingAwait: $data['ratingAwait'] ?? null,
            ratingAwaitCount: $data['ratingAwaitCount'] ?? null,
            ratingRfCritics: $data['ratingRfCritics'] ?? null,
            ratingRfCriticsVoteCount: $data['ratingRfCriticsVoteCount'] ?? null,
            webUrl: $data['webUrl'] ?? null,
            year: $data['year'] ?? null,
            filmLength: $data['filmLength'] ?? null,
            slogan: $data['slogan'] ?? null,
            description: $data['description'] ?? null,
            shortDescription: $data['shortDescription'] ?? null,
            editorAnnotation: $data['editorAnnotation'] ?? null,
            isTicketsAvailable: $data['isTicketsAvailable'] ?? null,
            productionStatus: $data['productionStatus'] ?? null,
            type: $data['type'],
            ratingMpaa: $data['ratingMpaa'] ?? null,
            ratingAgeLimits: $data['ratingAgeLimits'] ?? null,
            hasImax: $data['hasImax'] ?? null,
            has3D: $data['has3D'] ?? null,
            lastSync: $data['lastSync'] ?? null,
            countries: $data['countries'],
            genres: $data['genres'],
            startYear: $data['startYear'] ?? null,
            endYear: $data['endYear'] ?? null,
            serial: $data['serial'] ?? null,
            shortFilm: $data['shortFilm'] ?? null,
            completed: $data['completed'] ?? null
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
    public function getDisplayName(): string
    {
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
    public function isSerial(): bool
    {
        return in_array($this->type, ['TV_SERIES', 'MINI_SERIES', 'TV_SHOW'], true);
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
    public function getMainRating(): ?float
    {
        return $this->ratingKinopoisk ?? $this->ratingImdb ?? $this->ratingFilmCritics;
    }
} 