# AbstractService

## Описание

`AbstractService` - это абстрактный базовый класс для сервисов Kinopoisk API. Предоставляет общую функциональность для всех сервисов, работающих с Kinopoisk API, содержащий базовые методы для выполнения запросов и обработки ответов.

## Основные возможности

- Общие методы для работы с API
- Обработка ошибок и исключений
- Логирование запросов и ответов
- Базовая валидация данных
- Управление версиями API

## Наследование

```php
NotKinopoisk\Services\AbstractService
├── NotKinopoisk\Services\FilmService
├── NotKinopoisk\Services\PersonService
├── NotKinopoisk\Services\MediaService
└── NotKinopoisk\Services\UserService
```

## Конструктор

```php
public function __construct(Client $client, ApiVersion $apiVersion = ApiVersion::V1)
```

### Параметры

- `$client` (Client) - Основной клиент для работы с API
- `$apiVersion` (ApiVersion) - Версия API для использования (по умолчанию 'v1')

## Свойства

### client

```php
protected Client $client
```

**Описание:** Основной клиент для работы с API

**Тип:** `Client`

**Доступ:** Защищенный

**Пример:**

```php
// В наследующем классе
$response = $this->client->get('/api/v1/endpoint');
```

### apiVersion

```php
protected ApiVersion $apiVersion
```

**Описание:** Версия API для использования

**Тип:** `ApiVersion`

**Доступ:** Защищенный

**Пример:**

```php
// В наследующем классе
$version = $this->apiVersion->value; // 'v1', 'v2.1', 'v2.2'
```

## Защищенные методы

### setApiVersion()

```php
protected function setApiVersion(ApiVersion $apiVersion): void
```

Устанавливает версию API для работы сервиса.

#### Параметры

- `$apiVersion` (ApiVersion) - Версия API из перечисления доступных версий

#### Особенности

- Защищенный метод для внутреннего использования в классах-наследниках
- Версия определяет структуру запросов и доступные эндпоинты

#### Пример использования

```php
use NotKinopoisk\Services\AbstractService;
use NotKinopoisk\Enums\ApiVersion;

class MyService extends AbstractService
{
    public function useNewApiVersion(): void
    {
        // Установка версии API v2.2
        $this->setApiVersion(ApiVersion::V22);

        // Установка версии API v2.1
        $this->setApiVersion(ApiVersion::V21);
    }
}
```

### get()

```php
protected function get(string $uri, array $query = []): array
```

Выполняет GET запрос к API.

#### Параметры

- `$uri` (string) - URI запроса относительно базового URL API
- `$query` (array) - Параметры запроса, которые будут добавлены к URI как query string

#### Возвращает

- `array` - Декодированный JSON ответ от API

#### Исключения

- `ApiException` - При ошибках API или сети
- `InvalidApiKeyException` - При неверном API ключе
- `RateLimitException` - При превышении лимитов запросов
- `ResourceNotFoundException` - При отсутствии ресурса

#### Пример использования

```php
use NotKinopoisk\Services\AbstractService;

class FilmService extends AbstractService
{
    public function getFilm(int $id): array
    {
        return $this->get("/films/{$id}");
    }

    public function searchFilms(string $keyword, int $page = 1): array
    {
        return $this->get('/films', [
            'keyword' => $keyword,
            'page' => $page
        ]);
    }
}
```

### buildUri()

```php
protected function buildUri(string $endpoint, ?ApiVersion $api_version = null): string
```

Строит URI для API запроса указанной версии.

#### Параметры

- `$endpoint` (string) - Путь к ресурсу API (без префикса /api/vX.X/)
- `$api_version` (ApiVersion|null) - Версия API для использования; если null, используется $this->apiVersion

#### Возвращает

- `string` - Полный URI для запроса к указанной версии API

#### Особенности

- Автоматически использует версию API по умолчанию, если версия не указана явно
- Автоматически удаляет ведущий слеш из endpoint

#### Пример использования

```php
use NotKinopoisk\Services\AbstractService;
use NotKinopoisk\Enums\ApiVersion;

class MyService extends AbstractService
{
    public function buildEndpoints(): void
    {
        // Использование версии по умолчанию
        $uri = $this->buildUri('films/301');
        // Результат: '/api/v2.2/films/301' (если $this->apiVersion = ApiVersion::V22)

        // Явное указание версии API
        $uri = $this->buildUri('films/301', ApiVersion::V21);
        // Результат: '/api/v2.1/films/301'

        // Автоматическое удаление ведущего слеша
        $uri = $this->buildUri('/films/301');
        // Результат: '/api/v2.2/films/301'

        // Работа с вложенными ресурсами
        $uri = $this->buildUri('films/301/similars');
        // Результат: '/api/v2.2/films/301/similars'
    }
}
```

## Примеры использования

### Создание базового сервиса

```php
use NotKinopoisk\Client;
use NotKinopoisk\Services\AbstractService;
use NotKinopoisk\Enums\ApiVersion;

// Создание сервиса с версией API по умолчанию
$client = new Client('your-api-key');
$service = new FilmService($client);

// Создание сервиса с указанной версией API
$service = new FilmService($client, ApiVersion::V22);
```

### Создание собственного сервиса

```php
use NotKinopoisk\Client;
use NotKinopoisk\Services\AbstractService;
use NotKinopoisk\Enums\ApiVersion;

class CustomService extends AbstractService
{
    public function getData(): array
    {
        $response = $this->client->get('/api/v1/endpoint');
        return $response->getData();
    }

    public function getCustomEndpoint(): array
    {
        $uri = $this->buildUri('custom/endpoint');
        return $this->get($uri);
    }

    public function switchToNewApiVersion(): void
    {
        $this->setApiVersion(ApiVersion::V22);
    }
}
```

### Работа с разными версиями API

```php
use NotKinopoisk\Services\AbstractService;
use NotKinopoisk\Enums\ApiVersion;

class VersionedService extends AbstractService
{
    public function getDataWithVersion(ApiVersion $version): array
    {
        $uri = $this->buildUri('data', $version);
        return $this->get($uri);
    }

    public function compareApiVersions(): void
    {
        // Получение данных с версии v2.1
        $dataV21 = $this->getDataWithVersion(ApiVersion::V21);

        // Получение данных с версии v2.2
        $dataV22 = $this->getDataWithVersion(ApiVersion::V22);

        // Сравнение результатов
        $this->compareResults($dataV21, $dataV22);
    }
}
```

### Обработка ошибок

```php
use NotKinopoisk\Services\AbstractService;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\RateLimitException;

class ErrorHandlingService extends AbstractService
{
    public function getDataWithErrorHandling(): array
    {
        try {
            return $this->get('/api/v1/data');
        } catch (RateLimitException $e) {
            // Обработка превышения лимитов
            $this->handleRateLimit($e);
            throw $e;
        } catch (ApiException $e) {
            // Обработка общих ошибок API
            $this->handleApiError($e);
            throw $e;
        }
    }

    private function handleRateLimit(RateLimitException $e): void
    {
        // Логирование и ожидание
        error_log("Rate limit exceeded: " . $e->getMessage());
        sleep(60); // Ждем минуту
    }

    private function handleApiError(ApiException $e): void
    {
        // Логирование ошибки
        error_log("API Error: " . $e->getMessage());
    }
}
```

### Логирование запросов

```php
use NotKinopoisk\Services\AbstractService;

class LoggingService extends AbstractService
{
    public function getWithLogging(string $uri, array $query = []): array
    {
        $startTime = microtime(true);

        try {
            $response = $this->get($uri, $query);

            $duration = microtime(true) - $startTime;
            $this->logSuccess($uri, $query, $duration);

            return $response;
        } catch (\Exception $e) {
            $duration = microtime(true) - $startTime;
            $this->logError($uri, $query, $duration, $e);
            throw $e;
        }
    }

    private function logSuccess(string $uri, array $query, float $duration): void
    {
        error_log(sprintf(
            "API Success: %s (%.3fs) - Query: %s",
            $uri,
            $duration,
            json_encode($query)
        ));
    }

    private function logError(string $uri, array $query, float $duration, \Exception $e): void
    {
        error_log(sprintf(
            "API Error: %s (%.3fs) - Query: %s - Error: %s",
            $uri,
            $duration,
            json_encode($query),
            $e->getMessage()
        ));
    }
}
```

### Кэширование запросов

```php
use NotKinopoisk\Services\AbstractService;

class CachingService extends AbstractService
{
    private array $cache = [];
    private int $cacheTtl = 300; // 5 минут

    public function getWithCache(string $uri, array $query = []): array
    {
        $cacheKey = $this->buildCacheKey($uri, $query);

        // Проверяем кэш
        if (isset($this->cache[$cacheKey]) && $this->isCacheValid($cacheKey)) {
            return $this->cache[$cacheKey]['data'];
        }

        // Выполняем запрос
        $data = $this->get($uri, $query);

        // Сохраняем в кэш
        $this->cache[$cacheKey] = [
            'data' => $data,
            'timestamp' => time()
        ];

        return $data;
    }

    private function buildCacheKey(string $uri, array $query): string
    {
        return md5($uri . json_encode($query));
    }

    private function isCacheValid(string $cacheKey): bool
    {
        $cached = $this->cache[$cacheKey];
        return (time() - $cached['timestamp']) < $this->cacheTtl;
    }
}
```

### Валидация параметров

```php
use NotKinopoisk\Services\AbstractService;

class ValidatingService extends AbstractService
{
    public function getWithValidation(string $uri, array $query = []): array
    {
        $this->validateUri($uri);
        $this->validateQuery($query);

        return $this->get($uri, $query);
    }

    private function validateUri(string $uri): void
    {
        if (empty($uri)) {
            throw new \InvalidArgumentException('URI cannot be empty');
        }

        if (!preg_match('/^[a-zA-Z0-9\/\-_]+$/', $uri)) {
            throw new \InvalidArgumentException('Invalid URI format');
        }
    }

    private function validateQuery(array $query): void
    {
        foreach ($query as $key => $value) {
            if (empty($key)) {
                throw new \InvalidArgumentException('Query key cannot be empty');
            }

            if (!is_string($key)) {
                throw new \InvalidArgumentException('Query key must be string');
            }
        }
    }
}
```

## Связанные классы

- `Client` - Основной клиент для работы с API
- `ApiVersion` - Перечисление версий API
- `FilmService` - Сервис для работы с фильмами
- `PersonService` - Сервис для работы с персонами
- `MediaService` - Сервис для работы с медиа
- `UserService` - Сервис для работы с пользователями

## Исключения

- `ApiException` - Общие ошибки API
- `InvalidApiKeyException` - Неверный API ключ
- `RateLimitException` - Превышение лимитов запросов
- `ResourceNotFoundException` - Ресурс не найден

## API Endpoints

AbstractService используется как базовый класс для всех сервисов, работающих с Kinopoisk API:

- `/api/v1/*` - API версии 1.0
- `/api/v2.1/*` - API версии 2.1
- `/api/v2.2/*` - API версии 2.2
