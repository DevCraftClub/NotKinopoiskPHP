<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\MediaPost;

/**
 * Сервис для работы с медиа контентом
 * Реализует CRUD операции: Read (получение)
 */
class MediaService extends AbstractService
{
    /**
     * Получает медиа новости с сайта Кинопоиск
     * READ операция
     *
     * @param int $page Номер страницы
     * @return MediaPost[]
     */
    public function getPosts(int $page = 1): array
    {
        $data = $this->get($this->buildV1Uri("media_posts"), [
            'page' => $page
        ]);
        return array_map(fn($postData) => MediaPost::fromArray($postData), $data['items']);
    }
} 