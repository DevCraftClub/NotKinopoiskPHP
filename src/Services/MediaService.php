<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\MediaPost;

/**
 * Сервис для работы с медиа контентом в Kinopoisk API
 * 
 * Предоставляет методы для получения медиа материалов, связанных с фильмами:
 * новости, статьи, интервью и другие публикации.
 * 
 * Основные возможности:
 * - Получение медиа постов о фильмах
 * - Работа с новостями и статьями
 * - Фильтрация по типам контента
 * 
 * @package NotKinopoisk\Services
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\AbstractService
 * @see \NotKinopoisk\Models\MediaPost
 * 
 * @example
 * ```php
 * $client = new Client('api-key');
 * $mediaService = $client->media;
 * 
 * // Получение медиа постов о фильме
 * $posts = $mediaService->getByFilmId(301);
 * 
 * foreach ($posts as $post) {
 *     echo "Заголовок: {$post->title}\n";
 *     echo "URL: {$post->url}\n";
 * }
 * ```
 */
class MediaService extends AbstractService
{
    /**
     * Получает медиа посты о фильме
     * 
     * READ операция - извлекает новости, статьи, интервью и другие
     * медиа материалы, связанные с указанным фильмом.
     * 
     * @param int $filmId Уникальный идентификатор фильма в Кинопоиске
     * 
     * @return \NotKinopoisk\Models\MediaPost[] Массив медиа постов
     * 
     * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если фильм не найден
     * @throws \NotKinopoisk\Exception\ApiException При других ошибках API
     * 
     * @example
     * ```php
     * $posts = $mediaService->getByFilmId(301);
     * 
     * foreach ($posts as $post) {
     *     echo "Заголовок: {$post->title}\n";
     *     echo "Описание: {$post->description}\n";
     *     echo "URL: {$post->url}\n";
     *     echo "Дата: {$post->date}\n";
     * }
     * ```
     */
    public function getByFilmId(int $filmId): array
    {
        $data = $this->get($this->buildUri("films/{$filmId}/media"));
        return array_map(fn($postData) => MediaPost::fromArray($postData), $data['items']);
    }
} 