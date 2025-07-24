# PersonService

Сервис для работы с персонами в Kinopoisk API.

## Описание

`PersonService` предоставляет методы для получения информации о персонах (актерах, режиссерах, сценаристах и т.д.) из Kinopoisk API. Поддерживает поиск персон и получение детальной информации о конкретной персоне.

### Основные возможности

- Поиск персон по различным критериям
- Получение детальной информации о персоне
- Поддержка пагинации результатов поиска
- Обработка ошибок API

## Конструктор

```php
public function __construct(\NotKinopoisk\Client $client, string $apiVersion = 'v1.4')
```

### Параметры

- `$client` - Экземпляр клиента для выполнения HTTP запросов
- `$apiVersion` - Версия API (по умолчанию 'v1.4')

## Методы

### searchByName()

Поиск персон по имени.

```php
public function searchByName(string $name, int $page = 1): PaginatedResponse
```

**API Endpoint:** `/api/v1/persons`

#### Параметры

- `$name` (string) - Имя или часть имени для поиска
- `$page` (int) - Номер страницы (по умолчанию 1)

#### Возвращаемое значение

- `PaginatedResponse` - Результат поиска персон

#### Исключения

- `\NotKinopoisk\Exception\ApiException` - При ошибках API

#### Пример использования

```php
$results = $personService->searchByName('Том Круз');
echo "Найдено персон: {$results->getCount()}\n";

foreach ($results->items as $person) {
    echo "- {$person->name} (ID: {$person->personId})\n";
}
```

### getById()

Получает детальную информацию о персоне по ID.

```php
public function getById(int $id): Person
```

**API Endpoint:** `/api/v1/staff/{id}`

#### Параметры

- `$id` (int) - Уникальный идентификатор персоны в Кинопоиске

#### Возвращаемое значение

- `Person` - Объект персоны с полной информацией

#### Исключения

- `\NotKinopoisk\Exception\ResourceNotFoundException` - Если персона с указанным ID не найдена
- `\NotKinopoisk\Exception\ApiException` - При других ошибках API

#### Пример использования

```php
$person = $personService->getById(12345);
echo "Имя: " . $person->getDisplayName();
echo "Профессия: " . $person->profession;
echo "Биография: " . $person->biography;
```

### getFilmStaff()

Получает персонал фильма (актеры, режиссеры и другие участники).

```php
public function getFilmStaff(int $filmId): MovieStaffResponse
```

**API Endpoint:** `/api/v1/staff`

#### Параметры

- `$filmId` (int) - Уникальный идентификатор фильма в Кинопоиске

#### Возвращаемое значение

- `MovieStaffResponse` - Массив объектов персонала фильма

#### Исключения

- `\NotKinopoisk\Exception\ApiException` - При общих ошибках API
- `\NotKinopoisk\Exception\InvalidApiKeyException` - При неверном или недействительном API ключе
- `\NotKinopoisk\Exception\KpValidationException` - При ошибках валидации
- `\NotKinopoisk\Exception\RateLimitException` - При превышении лимита запросов
- `\NotKinopoisk\Exception\ResourceNotFoundException` - При отсутствии фильма с указанным ID

#### Пример использования

```php
// Получение персонала фильма "Матрица" (ID: 301)
$staff = $personService->getFilmStaff(301);

echo "Всего участников: " . count($staff) . "\n";

foreach ($staff as $person) {
    echo "{$person->getDisplayName()} - {$person->professionText}";

    if ($person->description) {
        echo " ({$person->description})";
    }
    echo "\n";
}

// Фильтрация по типу профессии
$actors = array_filter($staff, fn($person) => $person->isActor());
$directors = array_filter($staff, fn($person) => $person->isDirector());

echo "Актеров: " . count($actors) . "\n";
echo "Режиссеров: " . count($directors) . "\n";
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Services\PersonService;

// Создание клиента
$client = new Client('your-api-key');

// Получение сервиса персон
$personService = $client->persons;

// Поиск персон
$searchResults = $personService->searchByName('Киану Ривз');
echo "Найдено персон: {$searchResults->getCount()}\n";

// Получение информации о первой найденной персоне
if (!empty($searchResults->items)) {
    $firstPerson = $searchResults->items[0];
    $person = $personService->getById($firstPerson->personId);

    echo "Детальная информация:\n";
    echo "Имя: " . $person->getDisplayName() . "\n";
    echo "Профессия: " . $person->profession . "\n";
    echo "Биография: " . substr($person->biography, 0, 200) . "...\n";
}

// Получение персонала фильма
$staff = $personService->getFilmStaff(301); // Матрица
echo "Персонал фильма 'Матрица':\n";

foreach ($staff as $member) {
    echo "- {$member->getDisplayName()} ({$member->professionText})\n";
}
```

## Связанные классы

- [`Person`](../models/person.md) - Модель персоны
- [`PersonByNameResult`](../models/person-by-name-result.md) - Результат поиска по имени
- [`Staff`](../models/staff.md) - Модель участника съемочной группы
- [`MovieStaffResponse`](../responses/movie-staff-response.md) - Ответ с персоналом фильма
- [`PaginatedResponse`](../responses/paginated-response.md) - Пагинированный ответ
