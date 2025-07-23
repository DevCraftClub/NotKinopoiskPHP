<?php

declare(strict_types=1);

namespace NotKinopoisk;

use Dotenv\Dotenv;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use JsonException;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;
use NotKinopoisk\Services\FilmService;
use NotKinopoisk\Services\MediaService;
use NotKinopoisk\Services\PersonService;
use NotKinopoisk\Services\StaffService;
use NotKinopoisk\Services\UserService;

/**
 * Основной клиент для работы с Kinopoisk API
 *
 * Предоставляет единую точку входа для работы с Kinopoisk API.
 * Содержит все сервисы для работы с различными типами данных:
 * фильмы, персоны, сериалы и другие.
 *
 * Основные возможности:
 * - Инициализация с API ключом
 * - Доступ к различным сервисам API
 * - Централизованная обработка ошибок
 * - Управление HTTP клиентом
 *
 * @package NotKinopoisk
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\PersonService
 * @see     \NotKinopoisk\Services\HttpClient
 * @api     https://kinopoiskapiunofficial.tech
 * @link    https://kinopoiskapiunofficial.tech/documentation/api
 *
 * @example
 * ```php
 * $client = new Client('your-api-key');
 *
 * // Работа с персонами
 * $person = $client->persons->getById(12345);
 *
 * // Поиск персон
 * $searchResult = $client->persons->search('Том Круз');
 * ```
 */
class Client {

	/**
	 * Сервис для работы с фильмами
	 *
	 * @var \NotKinopoisk\Services\FilmService
	 */
	public readonly FilmService $films;
	/**
	 * Сервис для работы с персонами (актеры, режиссеры и т.д.)
	 *
	 * @var \NotKinopoisk\Services\PersonService
	 */
	public readonly PersonService $persons;
	/**
	 * Сервис для работы с персоналом фильмов
	 *
	 * @var \NotKinopoisk\Services\StaffService
	 */
	public readonly StaffService $staff;
	/**
	 * Сервис для работы с пользовательскими данными
	 *
	 * @var \NotKinopoisk\Services\UserService
	 */
	public readonly UserService $users;
	/**
	 * Сервис для работы с медиа контентом
	 *
	 * @var \NotKinopoisk\Services\MediaService
	 */
	public readonly MediaService $media;
	/**
	 * Базовый URL для API запросов
	 *
	 * @var string
	 */
	private string $_baseUrl = 'https://kinopoiskapiunofficial.tech';
	/**
	 * HTTP клиент для выполнения запросов к API
	 *
	 * @var HttpClient
	 */
	private HttpClient $_httpClient;
	/**
	 * API ключ для доступа к Kinopoisk API
	 *
	 * @var string|null
	 */
	private string|null $_apiKey;

	/**
	 * Конструктор клиента
	 *
	 * Инициализирует клиент для работы с Kinopoisk API. Автоматически загружает
	 * переменные окружения из .env файла, если он существует. API ключ может быть
	 * передан напрямую или получен из переменной окружения KINOPOISK_API_KEY.
	 *
	 * @param   string|null  $apiKey  API ключ для доступа к Kinopoisk API. Если не передан,
	 *                                будет попытка получить из переменной окружения KINOPOISK_API_KEY
	 * @param   array        $config  Дополнительная конфигурация для HTTP клиента Guzzle.
	 *                                Может включать timeout, headers, proxy и другие опции
	 *
	 * @throws \InvalidArgumentException Если API ключ не передан и не найден в переменной окружения
	 *
	 * @example
	 * ```php
	 * // Создание с прямым указанием ключа
	 * $client = new Client('your-api-key');
	 *
	 * // Создание с ключом из .env файла
	 * $client = new Client();
	 *
	 * // Создание с дополнительной конфигурацией
	 * $client = new Client(null, [
	 *     'timeout' => 60,
	 *     'headers' => ['User-Agent' => 'MyApp/1.0']
	 * ]);
	 * ```
	 */
	public function __construct(?string $apiKey = NULL, array $config = []) {
		// Автоматически загружаем .env файл, если он существует
		$this->_loadDotenv();

		$envApiKey     = getenv('KINOPOISK_API_KEY');
		$this->_apiKey = $apiKey ?? ($envApiKey !== FALSE ? $envApiKey : NULL);
		if (!$this->_apiKey) {
			throw new InvalidArgumentException('API ключ не передан и не найден в переменной окружения KINOPOISK_API_KEY');
		}

		$baseUrl = getenv('KINOPOISK_API_BASE_URL');
		if ($baseUrl !== FALSE) {
			$this->_baseUrl = $baseUrl;
		}

		$defaultConfig = [
			'base_uri' => $this->_baseUrl,
			'timeout'  => 30,
			'headers'  => [
				'X-API-KEY'    => $this->_apiKey,
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			],
		];

		$this->_httpClient = new HttpClient(array_merge($defaultConfig, $config));

		// Инициализация сервисов
		$this->films   = new FilmService($this);
		$this->persons = new PersonService($this);
		$this->staff   = new StaffService($this);
		$this->users   = new UserService($this);
		$this->media   = new MediaService($this);
	}

	/**
	 * Загружает переменные окружения из .env файла
	 *
	 * Автоматически ищет .env файл в текущей рабочей директории и загружает
	 * переменные окружения, если файл существует и доступен для чтения.
	 * Метод безопасно обрабатывает случаи, когда библиотека dotenv не установлена.
	 *
	 * @see      \Dotenv\Dotenv
	 * @return void
	 *
	 * @internal Этот метод вызывается автоматически в конструкторе
	 *
	 */
	private function _loadDotenv(): void {
		if (class_exists('\Dotenv\Dotenv')) {
			$envFile = getcwd() . '/.env';
			if (file_exists($envFile)) {
				Dotenv::createImmutable(dirname($envFile))->load();
			}
		}
	}

	/**
	 * Выполняет GET запрос к API
	 *
	 * Удобный метод для выполнения GET запросов с параметрами запроса.
	 * Автоматически формирует query параметры из переданного массива.
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
	 * // Запрос с параметрами
	 * $response = $client->get('/api/v1/films/search-by-keyword', [
	 *     'keyword' => 'матрица',
	 *     'page' => 1
	 * ]);
	 * ```
	 */
	public function get(string $uri, array $query = []): array {
		$options = [];
		if (!empty($query)) {
			$options['query'] = $query;
		}

		return $this->request('GET', $uri, $options);
	}

	/**
	 * Выполняет HTTP запрос к Kinopoisk API
	 *
	 * Отправляет HTTP запрос к API с указанным методом, URI и опциями.
	 * Автоматически обрабатывает ответ и декодирует JSON. В случае ошибок
	 * HTTP преобразует их в специфичные исключения API.
	 *
	 * @param   string  $method   HTTP метод запроса (GET, POST, PUT, DELETE и т.д.)
	 * @param   string  $uri      URI запроса относительно базового URL API
	 * @param   array   $options  Дополнительные опции для HTTP клиента Guzzle.
	 *                            Может включать query, json, form_params, headers и т.д.
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
	 * $response = $client->request('GET', '/api/v2.2/films/301');
	 * $filmData = $response['data'];
	 * ```
	 */
	public function request(string $method, string $uri, array $options = []): array {
		try {
			$response = $this->_httpClient->request($method, $uri, $options);
			$content  = $response->getBody()->getContents();

			return json_decode($content, TRUE, 512, JSON_THROW_ON_ERROR);
		} catch (GuzzleException $e) {
			$this->_handleHttpException($e);
			// Этот код недостижим, но PHPStan требует return statement
			throw new ApiException('Ошибка HTTP запроса', 0, $e);
		} catch (JsonException $e) {
			throw new ApiException('Ошибка парсинга JSON ответа: ' . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * Обрабатывает HTTP исключения и преобразует их в специфичные исключения API
	 *
	 * Анализирует HTTP статус коды и создает соответствующие исключения API.
	 * Обеспечивает единообразную обработку ошибок для всех запросов к API.
	 *
	 * @param   \GuzzleHttp\Exception\GuzzleException  $e  Исключение от HTTP клиента Guzzle
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При общих ошибках API
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException При статусе 401 (неверный ключ)
	 * @throws \NotKinopoisk\Exception\RateLimitException При статусах 402, 429 (превышение лимитов)
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException При статусе 404 (ресурс не найден)
	 *
	 * @internal Этот метод используется внутренне для обработки ошибок HTTP запросов
	 */
	private function _handleHttpException(GuzzleException $e): void {
		if ($e instanceof RequestException && $e->hasResponse()) {
			$response = $e->getResponse();
			if ($response === NULL) {
				throw new ApiException('Ошибка сети: ' . $e->getMessage(), 0, $e);
			}
			$statusCode = $response->getStatusCode();

			switch ($statusCode) {
				case 401:
					throw new InvalidApiKeyException('Неверный или отсутствующий API ключ');
				case 402:
					throw new RateLimitException('Превышен лимит запросов (дневной или общий)');
				case 404:
					throw new ResourceNotFoundException('Запрашиваемый ресурс не найден');
				case 429:
					throw new RateLimitException('Слишком много запросов. Превышен лимит запросов в секунду');
				default:
					throw new ApiException(
						'HTTP ошибка ' . $statusCode . ': ' . $e->getMessage(),
						$statusCode,
						$e,
					);
			}
		}

		throw new ApiException('Ошибка сети: ' . $e->getMessage(), 0, $e);
	}

	/**
	 * Получает текущий API ключ
	 *
	 * Возвращает API ключ, который используется для аутентификации в API.
	 * Ключ может быть передан в конструкторе или получен из переменной окружения.
	 *
	 * @return string|null API ключ для доступа к Kinopoisk API
	 *
	 * @example
	 * ```php
	 * $apiKey = $client->getApiKey();
	 * echo "Используется ключ: " . substr($apiKey, 0, 8) . "...";
	 * ```
	 */
	public function getApiKey(): string|null {
		return $this->_apiKey;
	}

	/**
	 * Получает базовый URL API
	 *
	 * Возвращает базовый URL для всех запросов к Kinopoisk Unofficial API.
	 *
	 * @return string Базовый URL API
	 *
	 * @example
	 * ```php
	 * $baseUrl = $client->getBaseUrl();
	 * echo "API доступен по адресу: $baseUrl";
	 * ```
	 */
	public function getBaseUrl(): string {
		return $this->_baseUrl;
	}

	/**
	 * Устанавливает базовый URL для API запросов
	 *
	 * Данный метод позволяет изменить базовый URL, который используется для всех API запросов.
	 * По умолчанию используется URL 'https://kinopoiskapiunofficial.tech', но метод позволяет
	 * переопределить его для тестирования или использования альтернативных эндпоинтов.
	 *
	 * @since 1.0.0
	 * @see   Client::request() Метод, использующий базовый URL для выполнения запросов
	 *
	 * @see   Client::getBaseUrl() Получение текущего базового URL
	 *
	 * @param   string  $url  Новый базовый URL для API запросов (должен быть валидным URL)
	 *
	 * @return void
	 *
	 */
	public function setBaseUrl(string $url): void {
		$this->_baseUrl = $url;
	}

}