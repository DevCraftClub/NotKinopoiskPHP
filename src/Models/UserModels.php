<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\ContentType;

/**
 * Модель пользовательского голоса
 *
 * Представляет голос пользователя за фильм в Kinopoisk API.
 * Содержит информацию о фильме и оценке пользователя.
 *
 * @package NotKinopoisk\Models
 * @author  Maxim Harder
 * @version 1.0.0
 * @since   1.0.0
 */
class UserVote
{
    /**
     * Конструктор модели пользовательского голоса
     *
     * @param int         $kinopoiskId      Уникальный идентификатор фильма
     * @param string|null $nameRu           Название на русском
     * @param string|null $nameEn           Название на английском
     * @param string|null $nameOriginal     Оригинальное название
     * @param array       $countries        Массив стран
     * @param array       $genres           Массив жанров
     * @param float|null  $ratingKinopoisk  Рейтинг Кинопоиска
     * @param float|null  $ratingImbd       Рейтинг IMDb
     * @param string|null $year             Год выпуска
     * @param ContentType $type             Тип контента
     * @param string      $posterUrl        URL постера
     * @param string      $posterUrlPreview URL превью постера
     * @param int         $userRating       Оценка пользователя
     */
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?float $ratingKinopoisk,
        public readonly ?float $ratingImbd,
        public readonly ?string $year,
        public readonly ContentType $type,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly int $userRating
    ) {
    }

    /**
     * Создает экземпляр модели из массива данных API
     *
     * @param array $data Массив данных от API
     *
     * @return self Экземпляр модели
     *
     * @throws \InvalidArgumentException При некорректных данных
     *
     * @example
     * ```php
     * $voteData = [
     *     'kinopoiskId' => 301,
     *     'nameRu' => 'Матрица',
     *     'nameEn' => 'The Matrix',
     *     'nameOriginal' => 'The Matrix',
     *     'countries' => [['country' => 'США']],
     *     'genres' => [['genre' => 'боевик']],
     *     'ratingKinopoisk' => 8.5,
     *     'ratingImbd' => 8.7,
     *     'year' => '1999',
     *     'type' => 'FILM',
     *     'posterUrl' => 'https://example.com/poster.jpg',
     *     'posterUrlPreview' => 'https://example.com/poster_small.jpg',
     *     'userRating' => 9
     * ];
     * $vote = UserVote::fromArray($voteData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            countries: $data['countries'] ?? [],
            genres: $data['genres'] ?? [],
            ratingKinopoisk: $data['ratingKinopoisk'] ?? null,
            ratingImbd: $data['ratingImbd'] ?? null,
            year: $data['year'] ?? null,
            type: ContentType::from($data['type']),
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            userRating: $data['userRating']
        );
    }

    /**
     * Получает отображаемое название фильма
     *
     * Возвращает название фильма в приоритетном порядке:
     * русское название -> английское название -> оригинальное название
     *
     * @return string Отображаемое название фильма
     *
     * @example
     * ```php
     * $displayName = $vote->getDisplayName();
     * echo "Название: $displayName";
     * ```
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
    }
} 