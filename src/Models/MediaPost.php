<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель медиа поста из Kinopoisk API
 * 
 * Представляет информацию о медиа посте, связанном с фильмом:
 * новости, статьи, интервью и другие публикации.
 * 
 * Основные возможности:
 * - Хранение информации о медиа посте в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к метаданным и содержанию поста
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\MediaService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $mediaPost = MediaPost::fromArray($apiData);
 * 
 * // Использование
 * echo "Заголовок: {$mediaPost->title}\n";
 * echo "Описание: {$mediaPost->description}\n";
 * echo "URL: {$mediaPost->url}";
 * ```
 */
class MediaPost
{
    /**
     * Конструктор модели медиа поста
     * 
     * Создает новый экземпляр медиа поста со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $kinopoiskId Уникальный идентификатор фильма в Кинопоиске
     * @param string $imageUrl URL изображения поста
     * @param string $title Заголовок поста
     * @param string $description Описание поста
     * @param string $url URL поста
     * @param string $publishedAt Дата публикации поста
     * 
     * @example
     * ```php
     * $mediaPost = new MediaPost(
     *     kinopoiskId: 12345,
     *     imageUrl: 'https://...',
     *     title: 'Новости о фильме',
     *     description: 'Подробности о съемках...',
     *     url: 'https://kinopoisk.ru/news/...',
     *     publishedAt: '2023-01-15T10:30:00Z'
     * );
     * ```
     */
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly string $imageUrl,
        public readonly string $title,
        public readonly string $description,
        public readonly string $url,
        public readonly string $publishedAt
    ) {
    }

    /**
     * Создает экземпляр медиа поста из массива данных API
     * 
     * Статический метод для удобного создания объекта MediaPost из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных медиа поста от API
     * 
     * @return self Новый экземпляр медиа поста
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'kinopoiskId' => 12345,
     *     'imageUrl' => 'https://...',
     *     'title' => 'Новости о фильме',
     *     'description' => 'Подробности о съемках...',
     *     'url' => 'https://kinopoisk.ru/news/...',
     *     'publishedAt' => '2023-01-15T10:30:00Z'
     * ];
     * 
     * $mediaPost = MediaPost::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            imageUrl: $data['imageUrl'],
            title: $data['title'],
            description: $data['description'],
            url: $data['url'],
            publishedAt: $data['publishedAt']
        );
    }
} 