# Абстрактные классы

Абстрактные базовые классы библиотеки NotKinopoisk PHP Wrapper.

## AbstractService

Абстрактный базовый класс для сервисов Kinopoisk API.

### Описание

Предоставляет общую функциональность для всех сервисов, работающих с Kinopoisk API. Содержит базовые методы для выполнения запросов и обработки ответов.

### Основные возможности

- Общие методы для работы с API
- Обработка ошибок и исключений
- Логирование запросов и ответов
- Базовая валидация данных

### Свойства

#### `$client`

**Тип:** `\NotKinopoisk\Client`

Основной клиент для работы с API.

#### `$apiVersion`

**Тип:** `ApiVersion`

Версия API для использования.

### Методы

#### `__construct(Client $client, ApiVersion $apiVersion = ApiVersion::V1)`

Конструктор абстрактного сервиса для работы с Kinopoisk API.

**Параметры:**

- `$client` (\NotKinopoisk\Client) - Основной клиент для работы с API
- `$apiVersion` (ApiVersion) - Версия API для использования (по умолчанию 'v1')

**Пример:**

```php
// Создание сервиса с версией API по умолчанию
$client = new Client('your-api-key');
$service = new FilmService($client);

// Создание сервиса с указанной версией API
$service = new FilmService($client, ApiVersion::V2_2);
```

#### `setApiVersion(ApiVersion $apiVersion): void`

Устанавливает версию API для работы сервиса.

**Параметры:**

- `$apiVersion` (ApiVersion) - Версия API из перечисления доступных версий

#### `get(string $uri, array $query = []): array`

Выполняет GET запрос к API.

**Параметры:**

- `$uri` (string) - URI для запроса
- `$query` (array) - Параметры запроса

**Возвращает:**

- `array` - Ответ от API

#### `buildUri(string $endpoint, ?ApiVersion $api_version = null): string`

Строит URI для запроса с учетом версии API.

**Параметры:**

- `$endpoint` (string) - Конечная точка API
- `$api_version` (ApiVersion|null) - Версия API (если не указана, используется текущая)

**Возвращает:**

- `string` - Полный URI для запроса

### Пример реализации

```php
class MyService extends AbstractService
{
    public function getData(): array
    {
        $response = $this->client->get('/api/v1/endpoint');
        return $response->getData();
    }

    public function getCustomData(string $id): array
    {
        $uri = $this->buildUri("/films/{$id}");
        return $this->get($uri);
    }
}
```

### Наследующие классы

- `\NotKinopoisk\Services\FilmService` - Сервис для работы с фильмами
- `\NotKinopoisk\Services\PersonService` - Сервис для работы с персонами
- `\NotKinopoisk\Services\MediaService` - Сервис для работы с медиа
- `\NotKinopoisk\Services\UserService` - Сервис для работы с пользователем

### Связанные классы

- `\NotKinopoisk\Client` - Основной клиент API
- `\NotKinopoisk\Enums\ApiVersion` - Перечисление версий API

## Информация о пакете

- **Пакет:** NotKinopoisk\Services
- **API:** https://kinopoiskapiunofficial.tech
- **Документация API:** https://kinopoiskapiunofficial.tech/documentation/api
- **Автор:** Maxim Harder <dev@devcraft.club>
- **Версия:** 1.0.0
