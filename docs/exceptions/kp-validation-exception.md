# KpValidationException

## Описание

`KpValidationException` - это исключение для ошибок валидации данных Kinopoisk API. Выбрасывается при обнаружении некорректных или недостающих данных в параметрах запросов к API или при валидации ответов от сервера.

## Основные возможности

- Обработка ошибок валидации входных данных
- Проверка корректности параметров запросов
- Валидация ответов от сервера
- Цепочка исключений с предыдущими ошибками
- Совместимость с Kinopoisk API

## Наследование

```php
\Exception
└── NotKinopoisk\Exception\KpValidationException
```

## Конструктор

```php
public function __construct(
    string $message = '',
    int $code = 0,
    ?\Throwable $previous = null
)
```

### Параметры

- `$message` (string) - Сообщение об ошибке валидации
- `$code` (int) - Код ошибки (обычно 0 для ошибок валидации)
- `$previous` (\Throwable|null) - Предыдущее исключение в цепочке

## Когда выбрасывается

`KpValidationException` выбрасывается в следующих случаях:

### Валидация параметров запросов

```php
// Некорректный ID фильма
if ($filmId <= 0) {
    throw new KpValidationException('ID фильма должен быть положительным числом');
}

// Неверный формат даты
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    throw new KpValidationException('Неверный формат даты: ожидается YYYY-MM-DD');
}

// Пустое обязательное поле
if (empty($requiredField)) {
    throw new KpValidationException('Обязательное поле не может быть пустым');
}
```

### Валидация ответов API

```php
// Отсутствует обязательное поле в ответе
if (!array_key_exists('items', $response)) {
    throw new KpValidationException('Отсутствует обязательное поле "items" в данных API');
}

// Неверный тип данных
if (!is_array($response['items'])) {
    throw new KpValidationException('Поле "items" должно быть массивом');
}

// Некорректная структура данных
if ($response['total'] < 0) {
    throw new KpValidationException('Поле "total" должно быть неотрицательным целым числом');
}
```

### Валидация классов и методов

```php
// Класс не существует
if (!class_exists($className)) {
    throw new KpValidationException("Указанный класс не существует: {$className}");
}

// Метод не найден
if (!method_exists($className, 'fromArray')) {
    throw new KpValidationException("Класс {$className} не имеет статического метода fromArray");
}

// Метод не статический
$reflection = new \ReflectionMethod($className, 'fromArray');
if (!$reflection->isStatic()) {
    throw new KpValidationException("Метод fromArray в классе {$className} должен быть статическим");
}
```

## Примеры использования

### Базовое использование

```php
use NotKinopoisk\Exception\KpValidationException;

try {
    // Некорректный ID фильма
    $client->films->getById(-1);
} catch (KpValidationException $e) {
    echo "Ошибка валидации: " . $e->getMessage();
    // Выведет: "Ошибка валидации: ID фильма должен быть положительным числом"
}
```

### Создание исключения

```php
use NotKinopoisk\Exception\KpValidationException;

// Простое исключение
throw new KpValidationException('Неверный формат даты: ожидается YYYY-MM-DD');

// Исключение с кодом ошибки
throw new KpValidationException('ID фильма не может быть отрицательным', 400);

// Исключение с предыдущим исключением
try {
    $data = json_decode($jsonString, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \JsonException('Ошибка парсинга JSON');
    }
} catch (\JsonException $e) {
    throw new KpValidationException('Некорректный формат данных', 0, $e);
}
```

### Обработка в сервисах

```php
use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Services\FilmService;

class FilmService extends AbstractService
{
    public function getById(int $filmId): Film
    {
        // Валидация входных данных
        if ($filmId <= 0) {
            throw new KpValidationException('ID фильма должен быть положительным числом');
        }

        // Выполнение запроса
        $response = $this->client->get("/api/v1/films/{$filmId}");

        // Валидация ответа
        if (!isset($response['kinopoiskId'])) {
            throw new KpValidationException('Некорректная структура ответа API');
        }

        return Film::fromArray($response);
    }
}
```

### Обработка в моделях

```php
use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Models\Film;

class Film
{
    public static function fromArray(array $data): self
    {
        // Валидация обязательных полей
        if (!isset($data['kinopoiskId'])) {
            throw new KpValidationException('Отсутствует обязательное поле "kinopoiskId"');
        }

        if (!is_int($data['kinopoiskId']) || $data['kinopoiskId'] <= 0) {
            throw new KpValidationException('Поле "kinopoiskId" должно быть положительным целым числом');
        }

        // Создание объекта
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null
        );
    }
}
```

### Обработка в ответах API

```php
use NotKinopoisk\Exception\KpValidationException;
use NotKinopoisk\Responses\DefaultResponse;

class DefaultResponse
{
    public static function fromArray(array $data, string $cls): self
    {
        // Валидация структуры данных
        if (!array_key_exists('items', $data)) {
            throw new KpValidationException('Отсутствует обязательное поле "items" в данных API');
        }

        if (!is_array($data['items'])) {
            throw new KpValidationException('Поле "items" должно быть массивом');
        }

        // Валидация целевого класса
        if (!class_exists($cls)) {
            throw new KpValidationException("Указанный класс не существует: {$cls}");
        }

        if (!method_exists($cls, 'fromArray')) {
            throw new KpValidationException("Класс {$cls} не имеет статического метода fromArray");
        }

        // Создание ответа
        $items = array_map(
            static fn (array $itemData): object => $cls::fromArray($itemData),
            $data['items']
        );

        return new self(
            total: $data['total'] ?? count($items),
            items: $items
        );
    }
}
```

## Обработка исключений

### Простая обработка

```php
use NotKinopoisk\Exception\KpValidationException;

try {
    $film = $client->films->getById($filmId);
} catch (KpValidationException $e) {
    echo "Ошибка валидации: " . $e->getMessage();
    echo "Код ошибки: " . $e->getCode();
}
```

### Детальная обработка

```php
use NotKinopoisk\Exception\KpValidationException;

try {
    $response = $client->films->search($query, $filters);
} catch (KpValidationException $e) {
    echo "Ошибка валидации: " . $e->getMessage() . "\n";
    echo "Код ошибки: " . $e->getCode() . "\n";
    echo "Файл: " . $e->getFile() . "\n";
    echo "Строка: " . $e->getLine() . "\n";

    // Проверка предыдущего исключения
    if ($e->getPrevious()) {
        echo "Предыдущее исключение: " . $e->getPrevious()->getMessage() . "\n";
    }
}
```

### Логирование ошибок

```php
use NotKinopoisk\Exception\KpValidationException;

try {
    $result = $client->films->getById($filmId);
} catch (KpValidationException $e) {
    // Логирование ошибки
    error_log("Validation error: " . $e->getMessage());

    // Возврат значения по умолчанию
    return null;
}
```

### Перехват в middleware

```php
use NotKinopoisk\Exception\KpValidationException;

class ApiMiddleware
{
    public function handle($request, $next)
    {
        try {
            return $next($request);
        } catch (KpValidationException $e) {
            // Возврат JSON ответа с ошибкой валидации
            return response()->json([
                'error' => 'validation_error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], 400);
        }
    }
}
```

## Связанные исключения

`KpValidationException` является частью иерархии исключений Kinopoisk API:

- `ApiException` - Базовое исключение API
- `InvalidApiKeyException` - Неверный API ключ
- `RateLimitException` - Превышение лимита запросов
- `ResourceNotFoundException` - Ресурс не найден
- `KpValidationException` - Ошибка валидации данных

### Пример обработки разных типов исключений

```php
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;
use NotKinopoisk\Exception\KpValidationException;

try {
    $film = $client->films->getById($filmId);
} catch (KpValidationException $e) {
    echo "Ошибка валидации: " . $e->getMessage();
} catch (ResourceNotFoundException $e) {
    echo "Фильм не найден: " . $e->getMessage();
} catch (InvalidApiKeyException $e) {
    echo "Неверный API ключ: " . $e->getMessage();
} catch (RateLimitException $e) {
    echo "Превышен лимит запросов: " . $e->getMessage();
} catch (ApiException $e) {
    echo "Общая ошибка API: " . $e->getMessage();
}
```

## Лучшие практики

### 1. Специфичные сообщения об ошибках

```php
// Плохо
throw new KpValidationException('Ошибка');

// Хорошо
throw new KpValidationException('ID фильма должен быть положительным числом, получено: ' . $filmId);
```

### 2. Валидация на ранней стадии

```php
public function search(string $query, array $filters = []): SearchResponse
{
    // Валидация входных данных в начале метода
    if (empty(trim($query))) {
        throw new KpValidationException('Поисковый запрос не может быть пустым');
    }

    if (strlen($query) < 2) {
        throw new KpValidationException('Поисковый запрос должен содержать минимум 2 символа');
    }

    // Выполнение логики
    return $this->performSearch($query, $filters);
}
```

### 3. Использование цепочки исключений

```php
try {
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \JsonException('Ошибка парсинга JSON: ' . json_last_error_msg());
    }
} catch (\JsonException $e) {
    throw new KpValidationException('Некорректный формат ответа API', 0, $e);
}
```

### 4. Проверка типов данных

```php
public function validateFilmData(array $data): void
{
    if (!isset($data['kinopoiskId'])) {
        throw new KpValidationException('Отсутствует обязательное поле "kinopoiskId"');
    }

    if (!is_int($data['kinopoiskId'])) {
        throw new KpValidationException('Поле "kinopoiskId" должно быть целым числом');
    }

    if ($data['kinopoiskId'] <= 0) {
        throw new KpValidationException('Поле "kinopoiskId" должно быть положительным числом');
    }
}
```

## Связанные классы

- `ApiException` - Базовое исключение API
- `InvalidApiKeyException` - Исключение для неверного API ключа
- `RateLimitException` - Исключение для превышения лимита
- `ResourceNotFoundException` - Исключение для отсутствующего ресурса
- `Client` - Основной клиент API
- `AbstractService` - Базовый класс сервисов
