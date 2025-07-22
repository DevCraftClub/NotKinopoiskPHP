<?php

declare(strict_types=1);

namespace NotKinopoisk;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;
use NotKinopoisk\Services\FilmService;
use NotKinopoisk\Services\PersonService;
use NotKinopoisk\Services\StaffService;
use NotKinopoisk\Services\UserService;
use NotKinopoisk\Services\MediaService;

/**
 * Основной клиент для работы с Kinopoisk Unofficial API
 */
class Client
{
    private const BASE_URL = 'https://kinopoiskapiunofficial.tech';


    private HttpClient $httpClient;
    private string $apiKey;

    // Сервисы для работы с различными ресурсами
    public readonly FilmService $films;
    public readonly PersonService $persons;
    public readonly StaffService $staff;
    public readonly UserService $users;
    public readonly MediaService $media;

    /**
     * Конструктор клиента
     *
     * @param string|null $apiKey API ключ для доступа к Kinopoisk API (если не передан, будет взят из env)
     * @param array $config Дополнительная конфигурация для HTTP клиента
     * @throws \InvalidArgumentException
     */
    public function __construct(?string $apiKey = null, array $config = [])
    {
        // Автоматически загружаем .env файл, если он существует
        $this->loadDotenv();
        
        $this->apiKey = $apiKey ?? getenv('KINOPOISK_API_KEY');
        if (!$this->apiKey) {
            throw new \InvalidArgumentException('API ключ не передан и не найден в переменной окружения KINOPOISK_API_KEY');
        }
        
        $defaultConfig = [
            'base_uri' => self::BASE_URL,
            'timeout' => 30,
            'headers' => [
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $this->httpClient = new HttpClient(array_merge($defaultConfig, $config));

        // Инициализация сервисов
        $this->films = new FilmService($this);
        $this->persons = new PersonService($this);
        $this->staff = new StaffService($this);
        $this->users = new UserService($this);
        $this->media = new MediaService($this);
    }

    /**
     * Загружает .env файл, если он существует
     */
    private function loadDotenv(): void
    {
        if (class_exists('\Dotenv\Dotenv')) {
            $envFile = getcwd() . '/.env';
            if (file_exists($envFile)) {
                \Dotenv\Dotenv::createImmutable(dirname($envFile))->load();
            }
        }
    }

    /**
     * Выполняет HTTP запрос к API
     *
     * @param string $method HTTP метод
     * @param string $uri URI запроса
     * @param array $options Дополнительные опции запроса
     * @return array Ответ API
     * @throws ApiException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $uri, $options);
            $content = $response->getBody()->getContents();
            
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException $e) {
            $this->handleHttpException($e);
            // Этот код недостижим, так как handleHttpException всегда выбрасывает исключение
            return [];
        } catch (\JsonException $e) {
            throw new ApiException('Ошибка парсинга JSON ответа: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Выполняет GET запрос
     *
     * @param string $uri URI запроса
     * @param array $query Параметры запроса
     * @return array Ответ API
     * @throws ApiException
     */
    public function get(string $uri, array $query = []): array
    {
        $options = [];
        if (!empty($query)) {
            $options['query'] = $query;
        }

        return $this->request('GET', $uri, $options);
    }

    /**
     * Обрабатывает HTTP исключения и преобразует их в специфичные исключения API
     *
     * @param GuzzleException $e Исключение Guzzle
     * @throws ApiException
     * @throws InvalidApiKeyException
     * @throws RateLimitException
     * @throws ResourceNotFoundException
     */
    private function handleHttpException(GuzzleException $e): void
    {
        if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
            $response = $e->getResponse();
            if ($response === null) {
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
                        $e
                    );
            }
        }

        throw new ApiException('Ошибка сети: ' . $e->getMessage(), 0, $e);
    }

    /**
     * Получает API ключ
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Получает базовый URL API
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }
} 