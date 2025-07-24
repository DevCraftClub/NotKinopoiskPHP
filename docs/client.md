# Client - Основной клиент

Основной клиент для работы с Kinopoisk API.

---

**📚 Навигация:** [Главная](index.md) → Client

**🔗 Связанные классы:**

- [FilmService](services/film-service.md) - Сервис для работы с фильмами
- [PersonService](services/person-service.md) - Сервис для работы с персонами
- [MediaService](services/media-service.md) - Сервис для работы с медиа
- [UserService](services/user-service.md) - Сервис для работы с пользователями
- [ApiVersion](enums/api-version.md) - Версии API
- [ApiException](exceptions/api-exception.md) - Базовое исключение API
- [InvalidApiKeyException](exceptions/invalid-api-key-exception.md) - Неверный API ключ
- [RateLimitException](exceptions/rate-limit-exception.md) - Превышение лимита
- [ResourceNotFoundException](exceptions/resource-not-found-exception.md) - Ресурс не найден

---

## Описание

Предоставляет единую точку входа для работы с Kinopoisk API. Содержит все сервисы для работы с различными типами данных: фильмы, персоны, сериалы и другие.

## Основные возможности

- Инициализация с API ключом
- Доступ к различным сервисам API
- Централизованная обработка ошибок
- Управление HTTP клиентом

## Свойства

### `$films`

**Тип:** `\NotKinopoisk\Services\FilmService`

Сервис для работы с фильмами.

### `$persons`

**Тип:** `\NotKinopoisk\Services\PersonService`

Сервис для работы с персонами (актеры, режиссеры и т.д.).

### `$users`

**Тип:** `\NotKinopoisk\Services\UserService`

Сервис для работы с пользовательскими данными.

### `$media`

**Тип:** `\NotKinopoisk\Services\MediaService`

Сервис для работы с медиа контентом.

## Методы

### `__construct(?string $apiKey = null, array $config = [])`

Конструктор клиента.

**Параметры:**

- `$apiKey` (string|null) - API ключ для доступа к Kinopoisk API. Если не указан, будет попытка загрузить из переменной окружения `KINOPOISK_API_KEY`
- `$config` (array) - Дополнительная конфигурация для HTTP клиента

**Исключения:**

- `InvalidArgumentException` - Если API ключ не указан и не найден в переменных окружения

**Пример:**

```php
// С API ключом
$client = new Client('your-api-key');

// Без API ключа (будет загружен из переменной окружения)
$client = new Client();

// С дополнительной конфигурацией
$client = new Client('your-api-key', [
    'timeout' => 30,
    'headers' => ['User-Agent' => 'MyApp/1.0']
]);
```

### `get(string $uri, array $query = []): array`

Выполняет GET запрос к API.

**Параметры:**

- `$uri` (string) - URI для запроса
- `$query` (array) - Параметры запроса

**Возвращает:**

- `array` - Ответ от API

**Исключения:**

- `ApiException` - При ошибке API
- `InvalidApiKeyException` - При неверном API ключе
- `RateLimitException` - При превышении лимита запросов
- `ResourceNotFoundException` - При отсутствии ресурса

### `request(string $method, string $uri, array $options = []): array`

Выполняет HTTP запрос к API.

**Параметры:**

- `$method` (string) - HTTP метод (GET, POST, PUT, DELETE)
- `$uri` (string) - URI для запроса
- `$options` (array) - Дополнительные опции запроса

**Возвращает:**

- `array` - Ответ от API

**Исключения:**

- `ApiException` - При ошибке API
- `InvalidApiKeyException` - При неверном API ключе
- `RateLimitException` - При превышении лимита запросов
- `ResourceNotFoundException` - При отсутствии ресурса

### `getApiKey(): string|null`

Получает текущий API ключ.

**Возвращает:**

- `string|null` - API ключ или null, если не установлен

### `getBaseUrl(): string`

Получает базовый URL для API запросов.

**Возвращает:**

- `string` - Базовый URL (по умолчанию: https://kinopoiskapiunofficial.tech)

### `setBaseUrl(string $url): void`

Устанавливает базовый URL для API запросов.

**Параметры:**

- `$url` (string) - Новый базовый URL

## Примеры использования

### Базовое использование

```php
<?php

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Работа с фильмами
$film = $client->films->getById(301);
echo $film->getDisplayName();

// Работа с персонами
$person = $client->persons->getById(12345);
echo $person->getDisplayName();

// Поиск персон
$searchResult = $client->persons->search('Том Круз');
```

### Использование с переменными окружения

```php
<?php

use NotKinopoisk\Client;

// API ключ будет загружен из переменной окружения KINOPOISK_API_KEY
$client = new Client();

// Работа с медиа контентом
$images = $client->media->getImages(301, ImageType::POSTER);
```

### Использование с дополнительной конфигурацией

```php
<?php

use NotKinopoisk\Client;

$config = [
    'timeout' => 60,
    'headers' => [
        'User-Agent' => 'MyApp/1.0',
        'Accept' => 'application/json'
    ],
    'verify' => true
];

$client = new Client('your-api-key', $config);
```

## Обработка ошибок

```php
<?php

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;

try {
    $client = new Client('your-api-key');
    $film = $client->films->getById(301);
} catch (InvalidApiKeyException $e) {
    echo "Неверный API ключ: " . $e->getMessage();
} catch (RateLimitException $e) {
    echo "Превышен лимит запросов: " . $e->getMessage();
} catch (ResourceNotFoundException $e) {
    echo "Ресурс не найден: " . $e->getMessage();
} catch (ApiException $e) {
    echo "Ошибка API: " . $e->getMessage();
}
```

## Информация о пакете

- **Пакет:** NotKinopoisk
- **API:** https://kinopoiskapiunofficial.tech
- **Документация API:** https://kinopoiskapiunofficial.tech/documentation/api
- **Автор:** Maxim Harder <dev@devcraft.club>
- **Версия:** 1.0.0
