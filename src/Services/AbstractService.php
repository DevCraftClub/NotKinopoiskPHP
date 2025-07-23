<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ApiVersion;

/**
 * Абстрактный базовый класс для сервисов Kinopoisk API
 *
 * Предоставляет общую функциональность для всех сервисов, работающих
 * с Kinopoisk API. Содержит базовые методы для выполнения запросов
 * и обработки ответов.
 *
 * Основные возможности:
 * - Общие методы для работы с API
 * - Обработка ошибок и исключений
 * - Логирование запросов и ответов
 * - Базовая валидация данных
 *
 * @package NotKinopoisk\Services
 * @api     https://kinopoiskapiunofficial.tech
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\HttpClient
 * @link    https://kinopoiskapiunofficial.tech/documentation/api
 *
 * @example
 * ```php
 * class MyService extends AbstractService
 * {
 *     public function getData(): array
 *     {
 *         $response = $this->client->get('/api/v1/endpoint');
 *         return $response->getData();
 *     }
 * }
 * ```
 */
abstract class AbstractService {

	/**
	 * Основной клиент для работы с API
	 *
	 * @var \NotKinopoisk\Client
	 */
	protected Client     $client;
	protected ApiVersion $apiVersion;

	/**
	 * Конструктор абстрактного сервиса для работы с Kinopoisk API
	 *
	 * Инициализирует базовый сервис с переданным клиентом API и версией API.
	 * Все наследующие классы должны вызывать этот конструктор для корректной
	 * инициализации и работы с API. Устанавливает версию API по умолчанию
	 * как 'v1', если не указано иное.
	 *
	 * @since 1.0.0
	 *
	 * @see   \NotKinopoisk\Client
	 * @see   \NotKinopoisk\Services\FilmService
	 * @see   \NotKinopoisk\Services\PersonService
	 *
	 * @param   \NotKinopoisk\Client  $client      Основной клиент для работы с API
	 * @param   ApiVersion            $apiVersion  Версия API для использования (по умолчанию 'v1')
	 *
	 * @return void
	 *
	 * @example
	 * ```php
	 * // Создание сервиса с версией API по умолчанию
	 * $client = new Client('your-api-key');
	 * $service = new FilmService($client);
	 *
	 * // Создание сервиса с указанной версией API
	 * $service = new FilmService($client, 'v2.2');
	 * ```
	 */
	public function __construct(Client $client, ApiVersion $apiVersion = ApiVersion::V1) {
		$this->client     = $client;
		$this->apiVersion = $apiVersion;
	}

	/**
	 * Устанавливает версию API для работы сервиса
	 *
	 * Защищенный метод для установки версии API, которая будет использоваться
	 * при построении URL-адресов запросов к Kinopoisk API. Версия определяет
	 * структуру запросов и доступные эндпоинты.
	 *
	 * @param ApiVersion $apiVersion Версия API из перечисления доступных версий
	 *
	 * @return void
	 *
	 * @see ApiVersion::getApiVersions() Получение всех доступных версий API
	 * @see AbstractService::buildUri() Построение URI с учетом версии API
	 *
	 * @example
	 * ```php
	 * // Установка версии API v2.2
	 * $this->setApiVersion(ApiVersion::V22);
	 *
	 * // Установка версии API v2.1
	 * $this->setApiVersion(ApiVersion::V21);
	 * ```
	 *
	 * @internal Метод предназначен для внутреннего использования в классах-наследниках
	 */
	protected function setApiVersion(ApiVersion $apiVersion): void {
		$this->apiVersion = $apiVersion;
	}

	/**
	 * Выполняет GET запрос к API
	 *
	 * Делегирует выполнение GET запроса основному клиенту. Предоставляет
	 * удобный интерфейс для наследующих классов.
	 *
	 * @param   string  $uri    URI запроса относительно базового URL API
	 * @param   array   $query  Параметры запроса, которые будут добавлены к URI как query string
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
	protected function get(string $uri, array $query = []): array {
		return $this->client->get($uri, $query);
	}

	/**
	 * Строит URI для API запроса версии 2.2
	 *
	 * Формирует полный URI для запросов к API версии 2.2, добавляя
	 * соответствующий префикс к переданному пути.
	 *
	 * @param   string  $endpoint  Путь к ресурсу (без префикса /api/v2.2/)
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
	protected function buildUri(string $endpoint): string {
		return "/api/{$this->apiVersion->value}/" . ltrim($endpoint, '/');
	}

}