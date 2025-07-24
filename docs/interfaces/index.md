# Интерфейсы

Базовые интерфейсы для определения контрактов в Kinopoisk API.

---

**📚 Навигация:** [Главная](../index.md) → Интерфейсы

---

## 📋 Список интерфейсов

### 📄 [ModelInterface](model-interface.md)

Базовый интерфейс для всех моделей данных.

**Основные возможности:**

- Определяет контракт для создания моделей из массива данных
- Обеспечивает единообразие в работе с моделями
- Определяет базовые методы для всех моделей

**Методы:**

- `fromArray(array $data): object` - Создание модели из массива данных
- `toArray(): array` - Преобразование модели в массив
- `getDisplayName(): string` - Получение отображаемого имени

**Реализуется:** Все модели данных

### 📄 [ResponseInterface](response-interface.md)

Базовый интерфейс для всех ответов API.

**Основные возможности:**

- Определяет контракт для создания ответов из массива данных
- Обеспечивает единообразие в работе с ответами
- Определяет базовые методы для всех ответов

**Методы:**

- `fromArray(array $data, string $cls): object` - Создание ответа из массива данных

**Реализуется:** Все классы ответов

## 🔗 Связанные компоненты

### Модели (реализуют ModelInterface)

- [Film](../models/film.md) - Основная модель фильма
- [Person](../models/person.md) - Модель персоны
- [Staff](../models/staff.md) - Модель съемочной группы
- [Review](../models/review.md) - Модель отзыва
- [Fact](../models/fact.md) - Модель факта
- [Image](../models/image.md) - Модель изображения
- [Video](../models/video.md) - Модель видео
- [Award](../models/award.md) - Модель награды
- [BoxOffice](../models/box-office.md) - Модель кассовых сборов
- [Country](../models/country.md) - Модель страны
- [Genre](../models/genre.md) - Модель жанра
- [Episode](../models/episode.md) - Модель эпизода
- [Season](../models/season.md) - Модель сезона
- [ExternalSource](../models/external-source.md) - Модель внешнего источника
- [Distribution](../models/distribution.md) - Модель дистрибуции
- [FilmSearchResult](../models/film-search-result.md) - Модель результата поиска
- [PersonSpouse](../models/person-spouse.md) - Модель супруга
- [PersonFilm](../models/person-film.md) - Модель фильма персоны
- [UserVote](../models/user-vote.md) - Модель голоса пользователя
- [FilmCollection](../models/film-collection.md) - Модель коллекции фильмов
- [Filters](../models/filters.md) - Модель фильтров
- [RelatedFilm](../models/related-film.md) - Модель связанного фильма
- [ApiKeyInfo](../models/api-key-info.md) - Модель информации об API ключе
- [ApiKeyQouta](../models/api-key-qouta.md) - Модель квоты API ключа
- [MediaPost](../models/media-post.md) - Модель медиа поста

### Ответы (реализуют ResponseInterface)

- [DefaultResponse](../responses/default-response.md) - Базовый ответ
- [PaginatedResponse](../responses/paginated-response.md) - Пагинированный ответ
- [KeywordSearchResponse](../responses/keyword-search-response.md) - Ответ поиска

### Сервисы (используют интерфейсы)

- [FilmService](../services/film-service.md) - Работает с моделями и ответами
- [PersonService](../services/person-service.md) - Работает с моделями и ответами
- [MediaService](../services/media-service.md) - Работает с моделями и ответами
- [UserService](../services/user-service.md) - Работает с моделями и ответами

## 🚀 Быстрый старт

### Работа с ModelInterface

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Interfaces\ModelInterface;
use NotKinopoisk\Models\Film;
use NotKinopoisk\Models\Person;

// Проверка, что модель реализует интерфейс
if (Film::class instanceof ModelInterface) {
    echo "Film реализует ModelInterface\n";
}

// Создание модели из массива данных
$filmData = [
    'kinopoiskId' => 301,
    'nameRu' => 'Матрица',
    'nameEn' => 'The Matrix',
    'rating' => 8.7,
    'year' => 1999
];

$film = Film::fromArray($filmData);

// Использование методов интерфейса
echo "Отображаемое имя: {$film->getDisplayName()}\n";

$filmArray = $film->toArray();
echo "Массив данных:\n";
print_r($filmArray);

// Работа с другой моделью
$personData = [
    'kinopoiskId' => 123,
    'nameRu' => 'Киану Ривз',
    'nameEn' => 'Keanu Reeves',
    'sex' => 'MALE'
];

$person = Person::fromArray($personData);
echo "Персона: {$person->getDisplayName()}\n";
```

### Работа с ResponseInterface

```php
use NotKinopoisk\Interfaces\ResponseInterface;
use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Responses\PaginatedResponse;

// Проверка, что ответ реализует интерфейс
if (DefaultResponse::class instanceof ResponseInterface) {
    echo "DefaultResponse реализует ResponseInterface\n";
}

// Создание ответа из массива данных
$responseData = [
    'total' => 2,
    'items' => [
        ['kinopoiskId' => 301, 'nameRu' => 'Матрица'],
        ['kinopoiskId' => 302, 'nameRu' => 'Матрица: Перезагрузка']
    ]
];

$response = DefaultResponse::fromArray($responseData, Film::class);

echo "Создан ответ с {$response->total} элементами\n";

// Работа с пагинированным ответом
$paginatedData = [
    'total' => 100,
    'items' => $responseData['items'],
    'currentPage' => 1,
    'totalPages' => 10
];

$paginatedResponse = PaginatedResponse::fromArray($paginatedData, Film::class);

echo "Страница {$paginatedResponse->currentPage} из {$paginatedResponse->totalPages}\n";
```

## 📖 Примеры использования

### Создание универсальной функции для работы с моделями

```php
// Функция для создания любой модели из массива данных
function createModelFromArray(string $modelClass, array $data): object {
    if (!is_subclass_of($modelClass, ModelInterface::class)) {
        throw new InvalidArgumentException("Класс {$modelClass} должен реализовывать ModelInterface");
    }

    return $modelClass::fromArray($data);
}

// Функция для создания массива моделей
function createModelsFromArray(string $modelClass, array $dataArray): array {
    return array_map(
        fn($data) => createModelFromArray($modelClass, $data),
        $dataArray
    );
}

// Функция для получения отображаемых имен
function getDisplayNames(array $models): array {
    return array_map(
        fn($model) => $model->getDisplayName(),
        $models
    );
}

// Использование
$filmData = [
    'kinopoiskId' => 301,
    'nameRu' => 'Матрица',
    'nameEn' => 'The Matrix'
];

$personData = [
    'kinopoiskId' => 123,
    'nameRu' => 'Киану Ривз',
    'nameEn' => 'Keanu Reeves'
];

// Создание моделей
$film = createModelFromArray(Film::class, $filmData);
$person = createModelFromArray(Person::class, $personData);

echo "Фильм: {$film->getDisplayName()}\n";
echo "Персона: {$person->getDisplayName()}\n";

// Создание массива моделей
$filmsData = [
    ['kinopoiskId' => 301, 'nameRu' => 'Матрица'],
    ['kinopoiskId' => 302, 'nameRu' => 'Матрица: Перезагрузка']
];

$films = createModelsFromArray(Film::class, $filmsData);
$names = getDisplayNames($films);

echo "Фильмы: " . implode(', ', $names) . "\n";
```

### Создание универсальной функции для работы с ответами

```php
// Функция для создания любого ответа из массива данных
function createResponseFromArray(string $responseClass, array $data, string $modelClass): object {
    if (!is_subclass_of($responseClass, ResponseInterface::class)) {
        throw new InvalidArgumentException("Класс {$responseClass} должен реализовывать ResponseInterface");
    }

    return $responseClass::fromArray($data, $modelClass);
}

// Функция для обработки ответов
function processResponse(object $response): array {
    $result = [
        'total' => $response->total,
        'count' => $response->getCount(),
        'isEmpty' => $response->isEmpty(),
        'items' => []
    ];

    // Добавляем информацию о пагинации, если есть
    if (property_exists($response, 'currentPage')) {
        $result['pagination'] = [
            'currentPage' => $response->currentPage,
            'totalPages' => $response->totalPages,
            'hasNextPage' => $response->hasNextPage(),
            'hasPreviousPage' => $response->hasPreviousPage()
        ];
    }

    // Добавляем информацию о поиске, если есть
    if (property_exists($response, 'keyword')) {
        $result['search'] = [
            'keyword' => $response->keyword,
            'pagesCount' => $response->pagesCount,
            'searchFilmsCountResult' => $response->searchFilmsCountResult
        ];
    }

    // Обрабатываем элементы
    foreach ($response->items as $item) {
        $result['items'][] = [
            'displayName' => $item->getDisplayName(),
            'data' => $item->toArray()
        ];
    }

    return $result;
}

// Использование
$responseData = [
    'total' => 2,
    'items' => [
        ['kinopoiskId' => 301, 'nameRu' => 'Матрица'],
        ['kinopoiskId' => 302, 'nameRu' => 'Матрица: Перезагрузка']
    ]
];

$response = createResponseFromArray(DefaultResponse::class, $responseData, Film::class);
$processed = processResponse($response);

echo "Обработанный ответ:\n";
print_r($processed);
```

### Проверка типов и валидация

```php
// Функция для проверки, что объект реализует интерфейс
function implementsInterface(object $object, string $interface): bool {
    $reflection = new ReflectionClass($object);
    return $reflection->implementsInterface($interface);
}

// Функция для валидации модели
function validateModel(object $model): array {
    $errors = [];

    if (!implementsInterface($model, ModelInterface::class)) {
        $errors[] = "Объект должен реализовывать ModelInterface";
        return $errors;
    }

    // Проверяем обязательные методы
    $requiredMethods = ['fromArray', 'toArray', 'getDisplayName'];

    foreach ($requiredMethods as $method) {
        if (!method_exists($model, $method)) {
            $errors[] = "Отсутствует обязательный метод: {$method}";
        }
    }

    // Проверяем, что getDisplayName возвращает строку
    try {
        $displayName = $model->getDisplayName();
        if (!is_string($displayName)) {
            $errors[] = "Метод getDisplayName должен возвращать строку";
        }
    } catch (Exception $e) {
        $errors[] = "Ошибка при вызове getDisplayName: " . $e->getMessage();
    }

    // Проверяем, что toArray возвращает массив
    try {
        $array = $model->toArray();
        if (!is_array($array)) {
            $errors[] = "Метод toArray должен возвращать массив";
        }
    } catch (Exception $e) {
        $errors[] = "Ошибка при вызове toArray: " . $e->getMessage();
    }

    return $errors;
}

// Использование
$film = Film::fromArray(['kinopoiskId' => 301, 'nameRu' => 'Матрица']);

if (implementsInterface($film, ModelInterface::class)) {
    echo "Film реализует ModelInterface\n";
}

$validationErrors = validateModel($film);
if (empty($validationErrors)) {
    echo "Модель валидна\n";
} else {
    echo "Ошибки валидации:\n";
    foreach ($validationErrors as $error) {
        echo "- {$error}\n";
    }
}
```

## 🔧 Общие методы

### ModelInterface

#### fromArray()

```php
public static function fromArray(array $data): object
```

Создает экземпляр модели из массива данных API.

#### toArray()

```php
public function toArray(): array
```

Преобразует модель в массив.

#### getDisplayName()

```php
public function getDisplayName(): string
```

Возвращает отображаемое имя объекта.

### ResponseInterface

#### fromArray()

```php
public static function fromArray(array $data, string $cls): object
```

Создает экземпляр ответа из массива данных API.

## 📊 Статистика интерфейсов

### ModelInterface

- **Реализуется:** 25+ моделями
- **Методы:** 3
- **Использование:** Все модели данных

### ResponseInterface

- **Реализуется:** 3+ классами ответов
- **Методы:** 1
- **Использование:** Все классы ответов

## 🔗 Связанные разделы

- [Модели](../models/index.md) - Реализуют ModelInterface
- [Ответы](../responses/index.md) - Реализуют ResponseInterface
- [Сервисы](../services/index.md) - Используют интерфейсы
- [Перечисления](../enums/index.md) - Используются в моделях
- [Исключения](../exceptions/index.md) - Обработка ошибок

---

**📚 Навигация:** [Главная](../index.md) → Интерфейсы
