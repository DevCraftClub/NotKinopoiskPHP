<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Client;

/**
 * Абстрактный базовый сервис для всех сервисов API
 */
abstract class AbstractService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Выполняет GET запрос к API
     *
     * @param string $uri URI запроса
     * @param array $query Параметры запроса
     * @return array Ответ API
     */
    protected function get(string $uri, array $query = []): array
    {
        return $this->client->get($uri, $query);
    }

    /**
     * Строит URI для API запроса
     *
     * @param string $path Путь к ресурсу
     * @return string Полный URI
     */
    protected function buildUri(string $path): string
    {
        return '/api/v2.2/' . ltrim($path, '/');
    }

    /**
     * Строит URI для API v1 запроса
     *
     * @param string $path Путь к ресурсу
     * @return string Полный URI
     */
    protected function buildV1Uri(string $path): string
    {
        return '/api/v1/' . ltrim($path, '/');
    }
} 