<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель внешнего источника из Kinopoisk API
 * 
 * Представляет информацию о рецензии или отзыве с внешней платформы,
 * включая данные о платформе, рейтинге и содержании.
 * 
 * Основные возможности:
 * - Хранение информации о внешнем источнике в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к данным платформы и рейтингу
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $externalSource = ExternalSource::fromArray($apiData);
 * 
 * // Использование
 * echo "Платформа: {$externalSource->platform}\n";
 * echo "Автор: {$externalSource->author}\n";
 * echo "Рейтинг: {$externalSource->positiveRating}/{$externalSource->negativeRating}";
 * ```
 */
class ExternalSource
{
    /**
     * Конструктор модели внешнего источника
     * 
     * Создает новый экземпляр внешнего источника со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $url URL источника
     * @param string $platform Название платформы
     * @param string $logoUrl URL логотипа платформы
     * @param int|null $positiveRating Количество положительных оценок
     * @param int|null $negativeRating Количество отрицательных оценок
     * @param string|null $author Автор отзыва
     * @param string|null $title Заголовок отзыва
     * @param string|null $description Содержание отзыва
     * 
     * @example
     * ```php
     * $externalSource = new ExternalSource(
     *     url: 'https://example.com/review',
     *     platform: 'IMDb',
     *     logoUrl: 'https://...',
     *     positiveRating: 90,
     *     negativeRating: 10,
     *     author: 'Пользователь',
     *     title: 'Отличный фильм',
     *     description: 'Подробный отзыв...'
     * );
     * ```
     */
    public function __construct(
        public readonly string $url,
        public readonly string $platform,
        public readonly string $logoUrl,
        public readonly ?int $positiveRating,
        public readonly ?int $negativeRating,
        public readonly ?string $author,
        public readonly ?string $title,
        public readonly ?string $description
    ) {
    }

    /**
     * Создает экземпляр внешнего источника из массива данных API
     * 
     * Статический метод для удобного создания объекта ExternalSource из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля.
     * 
     * @param array $data Массив данных внешнего источника от API
     * 
     * @return self Новый экземпляр внешнего источника
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'url' => 'https://example.com/review',
     *     'platform' => 'IMDb',
     *     'logoUrl' => 'https://...',
     *     'positiveRating' => 90,
     *     'negativeRating' => 10,
     *     'author' => 'Пользователь',
     *     'title' => 'Отличный фильм',
     *     'description' => 'Подробный отзыв...'
     * ];
     * 
     * $externalSource = ExternalSource::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            platform: $data['platform'],
            logoUrl: $data['logoUrl'],
            positiveRating: $data['positiveRating'] ?? null,
            negativeRating: $data['negativeRating'] ?? null,
            author: $data['author'] ?? null,
            title: $data['title'] ?? null,
            description: $data['description'] ?? null
        );
    }
} 