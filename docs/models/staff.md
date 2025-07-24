# Staff

Модель персонала фильма из Kinopoisk API.

## Описание

`Staff` представляет информацию об участнике съемочной группы фильма: актерах, режиссерах, сценаристах, операторах и других специалистах. Содержит данные о роли, профессии и описании работы в фильме.

### Основные возможности

- Хранение информации о персонале в неизменяемом виде
- Создание объекта из массива данных API
- Удобные методы для определения профессии
- Получение отображаемого имени

**API Endpoint:** `/api/v1/staff`

## Свойства

### Основная информация

- `$staffId` (int) - Уникальный идентификатор персонала в Кинопоиске
- `$nameRu` (string|null) - Имя персонала на русском языке
- `$nameEn` (string|null) - Имя персонала на английском языке
- `$description` (string|null) - Описание роли или работы в фильме
- `$posterUrl` (string) - URL фотографии персонала

### Профессиональная информация

- `$professionText` (string) - Текстовое описание профессии
- `$professionKey` (ProfessionKey) - Ключ профессии (ACTOR, DIRECTOR, WRITER и т.д.)

## Конструктор

```php
public function __construct(
    public readonly int $staffId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $description,
    public readonly string $posterUrl,
    public readonly string $professionText,
    public readonly ProfessionKey $professionKey,
)
```

### Пример создания

```php
$staff = new Staff(
    staffId: 12345,
    nameRu: 'Том Круз',
    nameEn: 'Tom Cruise',
    description: 'Нео',
    posterUrl: 'https://...',
    professionText: 'Актер',
    professionKey: ProfessionKey::ACTOR
);
```

## Методы

### fromArray()

Создает экземпляр персонала из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных персонала от API

#### Возвращаемое значение

- `self` - Новый экземпляр персонала

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'staffId' => 12345,
    'nameRu' => 'Том Круз',
    'nameEn' => 'Tom Cruise',
    'description' => 'Нео',
    'posterUrl' => 'https://...',
    'professionText' => 'Актер',
    'professionKey' => 'ACTOR'
];

$staff = Staff::fromArray($apiData);
```

### getDisplayName()

Получает отображаемое имя персонала.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое имя персонала (приоритет: русское имя → английское имя → "Без имени")

#### Пример использования

```php
echo $staff->getDisplayName(); // "Том Круз" или "Tom Cruise" или "Без имени"
```

### isActor()

Проверяет, является ли персонал актером.

```php
public function isActor(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это актер, `false` в противном случае

#### Пример использования

```php
if ($staff->isActor()) {
    echo "Актер: {$staff->description}";
}
```

### isDirector()

Проверяет, является ли персонал режиссером.

```php
public function isDirector(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это режиссер, `false` в противном случае

#### Пример использования

```php
if ($staff->isDirector()) {
    echo "Режиссер";
}
```

### isWriter()

Проверяет, является ли персонал сценаристом.

```php
public function isWriter(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это сценарист, `false` в противном случае

#### Пример использования

```php
if ($staff->isWriter()) {
    echo "Сценарист";
}
```

### toArray()

Преобразует объект персонала в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными персонала

#### Пример использования

```php
$staffArray = $staff->toArray();
echo json_encode($staffArray); // JSON представление персонала
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Staff;
use NotKinopoisk\Enums\ProfessionKey;

// Создание клиента
$client = new Client('your-api-key');

// Получение персонала фильма
$personService = $client->persons;
$staff = $personService->getFilmStaff(301); // Матрица

echo "=== Персонал фильма 'Матрица' ===\n";

// Группировка по профессиям
$actors = [];
$directors = [];
$writers = [];
$others = [];

foreach ($staff as $member) {
    if ($member->isActor()) {
        $actors[] = $member;
    } elseif ($member->isDirector()) {
        $directors[] = $member;
    } elseif ($member->isWriter()) {
        $writers[] = $member;
    } else {
        $others[] = $member;
    }
}

// Вывод актеров
if (!empty($actors)) {
    echo "\n🎭 Актеры:\n";
    foreach ($actors as $actor) {
        echo "- {$actor->getDisplayName()}";
        if ($actor->description) {
            echo " ({$actor->description})";
        }
        echo "\n";
    }
}

// Вывод режиссеров
if (!empty($directors)) {
    echo "\n🎬 Режиссеры:\n";
    foreach ($directors as $director) {
        echo "- {$director->getDisplayName()}\n";
    }
}

// Вывод сценаристов
if (!empty($writers)) {
    echo "\n✍️ Сценаристы:\n";
    foreach ($writers as $writer) {
        echo "- {$writer->getDisplayName()}\n";
    }
}

// Вывод остальных участников
if (!empty($others)) {
    echo "\n🔧 Остальные участники:\n";
    foreach ($others as $other) {
        echo "- {$other->getDisplayName()} ({$other->professionText})\n";
    }
}
```

## Работа с персоналом

```php
// Функция для получения персонала по профессии
function getStaffByProfession(array $staff, ProfessionKey $professionKey): array {
    return array_filter($staff, function($member) use ($professionKey) {
        return $member->professionKey === $professionKey;
    });
}

// Функция для поиска персонала по имени
function findStaffByName(array $staff, string $name): ?Staff {
    $name = strtolower($name);

    foreach ($staff as $member) {
        if (strpos(strtolower($member->getDisplayName()), $name) !== false) {
            return $member;
        }
    }

    return null;
}

// Использование
$staff = $personService->getFilmStaff(301);

// Получение всех актеров
$actors = getStaffByProfession($staff, ProfessionKey::ACTOR);
echo "Актеров в фильме: " . count($actors) . "\n";

// Поиск конкретного актера
$neo = findStaffByName($staff, 'Киану');
if ($neo) {
    echo "Найден актер: {$neo->getDisplayName()} - {$neo->description}\n";
}
```

## Статистика персонала

```php
function getStaffStatistics(array $staff): array {
    $stats = [];

    foreach ($staff as $member) {
        $profession = $member->professionText;
        if (!isset($stats[$profession])) {
            $stats[$profession] = 0;
        }
        $stats[$profession]++;
    }

    return $stats;
}

// Использование
$staff = $personService->getFilmStaff(301);
$stats = getStaffStatistics($staff);

echo "=== Статистика персонала ===\n";
foreach ($stats as $profession => $count) {
    echo "{$profession}: {$count} человек\n";
}
```

## Связанные классы

- [`Person`](person.md) - Модель персоны
- [`ProfessionKey`](../enums/profession-key.md) - Ключи профессий
- [`PersonService`](../services/person-service.md) - Сервис для работы с персонами
- [`MovieStaffResponse`](../responses/movie-staff-response.md) - Ответ с персоналом фильма
