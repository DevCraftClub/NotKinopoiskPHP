# Person

Модель персоны из Kinopoisk API.

## Описание

`Person` представляет полную информацию о персоне (актер, режиссер, сценарист и т.д.), полученную из Kinopoisk API. Содержит биографические данные, фильмографию, информацию о супругах и другие детали.

### Основные возможности

- Хранение полной информации о персоне в неизменяемом виде
- Создание объекта из массива данных API
- Удобные методы для работы с именами и отображением
- Поддержка фильмографии и информации о супругах

**API Endpoint:** `/api/v1/staff/{id}`

## Свойства

### Основная информация

- `$personId` (int|null) - Уникальный идентификатор персоны в Кинопоиске
- `$nameRu` (string|null) - Имя персоны на русском языке
- `$nameEn` (string|null) - Имя персоны на английском языке
- `$sex` (string|null) - Пол персоны
- `$posterUrl` (string|null) - URL постера/фотографии персоны

### Физические характеристики

- `$growth` (string|null) - Рост персоны
- `$age` (int|null) - Возраст персоны

### Даты и места

- `$birthday` (string|null) - Дата рождения
- `$death` (string|null) - Дата смерти (если применимо)
- `$birthplace` (string|null) - Место рождения
- `$deathplace` (string|null) - Место смерти

### Профессиональная информация

- `$profession` (string|null) - Профессия персоны
- `$hasAwards` (int|null) - Наличие наград
- `$facts` (string|null) - Интересные факты
- `$biography` (string|null) - Биография персоны

### Связанные данные

- `$spouses` (PersonSpouse[]) - Массив информации о супругах
- `$films` (PersonFilm[]) - Массив информации о фильмах
- `$total` (string|null) - Информация о количестве работ

### Дополнительная информация

- `$births` (string|null) - Информация о рождении
- `$deaths` (string|null) - Информация о смерти

## Конструктор

```php
public function __construct(
    public readonly ?int $personId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $sex,
    public readonly ?string $posterUrl,
    public readonly ?string $growth,
    public readonly ?string $birthday,
    public readonly ?string $death,
    public readonly ?int $age,
    public readonly ?string $birthplace,
    public readonly ?string $deathplace,
    public readonly array $spouses,
    public readonly ?int $hasAwards,
    public readonly ?string $profession,
    public readonly ?string $facts,
    public readonly array $films,
    public readonly ?string $biography,
    public readonly ?string $births,
    public readonly ?string $deaths,
    public readonly ?string $total,
)
```

### Пример создания

```php
$person = new Person(
    personId: 12345,
    nameRu: 'Том Круз',
    nameEn: 'Tom Cruise',
    posterUrl: 'https://...',
    profession: 'Актер',
    biography: 'Биография...',
    spouses: [new PersonSpouse(...)]
);
```

## Методы

### fromArray()

Создает объект персоны из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных от API

#### Возвращаемое значение

- `self` - Новый экземпляр Person

#### Пример использования

```php
$apiData = [
    'personId' => 12345,
    'nameRu' => 'Том Круз',
    'nameEn' => 'Tom Cruise',
    'profession' => 'Актер',
    'spouses' => [
        [
            'personId' => 67890,
            'name' => 'Кэти Холмс',
            'divorced' => true,
            'divorcedReason' => 'Развод'
        ]
    ],
    'films' => [
        [
            'filmId' => 301,
            'nameRu' => 'Матрица',
            'professionKey' => 'ACTOR'
        ]
    ]
];

$person = Person::fromArray($apiData);
```

### getDisplayName()

Получает отображаемое имя персоны.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое имя персоны (приоритет: русское имя → английское имя → "Неизвестно")

#### Пример использования

```php
echo $person->getDisplayName(); // "Том Круз" или "Tom Cruise" или "Неизвестно"
```

### toArray()

Преобразует объект персоны в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными персоны

#### Пример использования

```php
$personArray = $person->toArray();
echo json_encode($personArray); // JSON представление персоны
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Person;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о персоне
$personService = $client->persons;
$person = $personService->getById(12345);

// Основная информация
echo "=== Информация о персоне ===\n";
echo "ID: {$person->personId}\n";
echo "Имя: " . $person->getDisplayName() . "\n";
echo "Профессия: {$person->profession}\n";
echo "Пол: {$person->sex}\n";
echo "Возраст: {$person->age}\n";

// Физические характеристики
if ($person->growth) {
    echo "Рост: {$person->growth}\n";
}

// Даты и места
if ($person->birthday) {
    echo "Дата рождения: {$person->birthday}\n";
}
if ($person->birthplace) {
    echo "Место рождения: {$person->birthplace}\n";
}
if ($person->death) {
    echo "Дата смерти: {$person->death}\n";
}
if ($person->deathplace) {
    echo "Место смерти: {$person->deathplace}\n";
}

// Биография
if ($person->biography) {
    echo "\n=== Биография ===\n";
    echo substr($person->biography, 0, 300) . "...\n";
}

// Интересные факты
if ($person->facts) {
    echo "\n=== Интересные факты ===\n";
    echo $person->facts . "\n";
}

// Супруги
if (!empty($person->spouses)) {
    echo "\n=== Супруги ===\n";
    foreach ($person->spouses as $spouse) {
        echo "- {$spouse->name}";
        if ($spouse->divorced) {
            echo " (разведены: {$spouse->divorcedReason})";
        }
        echo "\n";
    }
}

// Фильмография
if (!empty($person->films)) {
    echo "\n=== Фильмография ===\n";
    foreach ($person->films as $film) {
        echo "- {$film->getDisplayName()} ({$film->professionKey})\n";
    }
}

// Награды
if ($person->hasAwards) {
    echo "\n🏆 Имеет награды\n";
}

// Статистика
if ($person->total) {
    echo "\n📊 {$person->total}\n";
}
```

## Работа с фильмографией

```php
// Фильтрация фильмов по профессии
$actorFilms = array_filter($person->films, fn($film) => $film->professionKey === 'ACTOR');
$directorFilms = array_filter($person->films, fn($film) => $film->professionKey === 'DIRECTOR');

echo "Фильмов как актер: " . count($actorFilms) . "\n";
echo "Фильмов как режиссер: " . count($directorFilms) . "\n";

// Сортировка фильмов по году
usort($person->films, fn($a, $b) => $a->year <=> $b->year);

echo "Фильмы по хронологии:\n";
foreach ($person->films as $film) {
    echo "- {$film->year}: {$film->getDisplayName()}\n";
}
```

## Связанные классы

- [`PersonSpouse`](person-spouse.md) - Информация о супруге
- [`PersonFilm`](person-film.md) - Информация о фильме в фильмографии
- [`PersonService`](../services/person-service.md) - Сервис для работы с персонами
