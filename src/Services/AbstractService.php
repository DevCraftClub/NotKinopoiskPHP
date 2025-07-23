<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Client;

/**
 * Абстрактный базовый сервис для всех сервисов API
 * 
 * Этот класс предоставляет общую функциональность для всех сервисов, работающих
 * с Kinopoisk API. Реализует принцип DRY (Don't Repeat Yourself) путем вынесения
 * общих методов в базовый класс.
 * 
 * Основные возможности:
 * - Хранение ссылки на основной клиент API
 * - Выполнение GET запросов к API
 * - Построение URI для различных версий API
 * 
 * @package NotKinopoisk\Services
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Client
 * @see \NotKinopoisk\Services\FilmService
 * @see \NotKinopoisk\Services\PersonService
 * @see \NotKinopoisk\Services\StaffService
 * @see \NotKinopoisk\Services\UserService
 * @see \NotKinopoisk\Services\MediaService
 * 
 * @example
 * ```php
 * class MyCustomService extends AbstractService
 * {
 *     public function getCustomData(int $id): array
 *     {
 *         return $this->get($this->buildUri("custom/{$id}"));
 *     }
 * }
 * ```
 */
abstract class AbstractService
{
    /**
     * Основной клиент для работы с API
     * 
     * @var \NotKinopoisk\Client
     */
    protected Client $client;

    /**
     * Конструктор абстрактного сервиса
     * 
     * Инициализирует сервис с переданным клиентом API. Все наследующие классы
     * должны вызывать этот конструктор для корректной работы.
     * 
     * @param \NotKinopoisk\Client $client Основной клиент для работы с API
     * 
     * @example
     * ```php
     * $client = new Client('api-key');
     * $service = new FilmService($client);
     * ```
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Выполняет GET запрос к API
     * 
     * Делегирует выполнение GET запроса основному клиенту. Предоставляет
     * удобный интерфейс для наследующих классов.
     * 
     * @param string $uri URI запроса относительно базового URL API
     * @param array $query Параметры запроса, которые будут добавлены к URI как query string
     * 
     * @return array Декодированный JSON ответ от API
     * 
     * @throws \NotKinopoisk\Exception\ApiException При ошибках API или сети
     * @throws \NotKinopoisk\Exception\InvalidApiKeyException При неверном API ключе
     * @throws \NotKinopoisk\Exception\RateLimitException При превышении лимитов запросов
     * @throws \NotKinopoisk\Exception\ResourceNotFoundException При отсутствии ресурса
     * 
     * @example
     * ```php
     * $data = $this->get('/films/301', ['page' => 1]);
     * ```
     */
    protected function get(string $uri, array $query = []): array
    {
        return $this->client->get($uri, $query);
    }

    /**
     * Строит URI для API запроса версии 2.2
     * 
     * Формирует полный URI для запросов к API версии 2.2, добавляя
     * соответствующий префикс к переданному пути.
     * 
     * @param string $path Путь к ресурсу (без префикса /api/v2.2/)
     * 
     * @return string Полный URI для запроса к API v2.2
     * 
     * @example
     * ```php
     * $uri = $this->buildUri('films/301');
     * // Результат: '/api/v2.2/films/301'
     * 
     * $uri = $this->buildUri('/films/301');
     * // Результат: '/api/v2.2/films/301'
     * ```
     */
    protected function buildUri(string $path): string
    {
        return '/api/v2.2/' . ltrim($path, '/');
    }

    /**
     * Строит URI для API запроса версии 1
     * 
     * Формирует полный URI для запросов к API версии 1, добавляя
     * соответствующий префикс к переданному пути.
     * 
     * @param string $path Путь к ресурсу (без префикса /api/v1/)
     * 
     * @return string Полный URI для запроса к API v1
     * 
     * @example
     * ```php
     * $uri = $this->buildV1Uri('films/search-by-keyword');
     * // Результат: '/api/v1/films/search-by-keyword'
     * 
     * $uri = $this->buildV1Uri('/films/search-by-keyword');
     * // Результат: '/api/v1/films/search-by-keyword'
     * ```
     */
    protected function buildV1Uri(string $path): string
    {
        return '/api/v1/' . ltrim($path, '/');
    }
} 