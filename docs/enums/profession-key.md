# ProfessionKey

Enum профессий персоны в фильме.

## Описание

`ProfessionKey` представляет различные профессии, которые может иметь персона в фильме согласно Kinopoisk API.

**API Endpoint:** `/api/v1/persons/{id}`

## Значения enum

### Основные профессии

- `ACTOR` - Актер
- `DIRECTOR` - Режиссер
- `WRITER` - Сценарист
- `PRODUCER` - Продюсер
- `PRODUCER_USSR` - Продюсер (СССР)
- `COMPOSER` - Композитор
- `OPERATOR` - Оператор
- `EDITOR` - Монтажер
- `DESIGN` - Художник
- `TRANSLATOR` - Переводчик
- `VOICE_DIRECTOR` - Режиссер дубляжа

### Специальные роли

- `HIMSELF` - В роли самого себя
- `HERSELF` - В роли самой себя
- `HRONO_TITR_MALE` - За кадром (мужской голос)
- `HRONO_TITR_FEMALE` - За кадром (женский голос)
- `UNKNOWN` - Неизвестно

## Методы

### isActor()

Проверяет, является ли профессия актерской.

```php
public function isActor(): bool
```

#### Возвращаемое значение

- `bool` - `true` если актер, `false` в противном случае

#### Пример использования

```php
if (ProfessionKey::ACTOR->isActor()) {
    echo "Актерская роль";
}
```

### isDirector()

Проверяет, является ли профессия режиссерской.

```php
public function isDirector(): bool
```

#### Возвращаемое значение

- `bool` - `true` если режиссер, `false` в противном случае

#### Пример использования

```php
if (ProfessionKey::DIRECTOR->isDirector()) {
    echo "Режиссерская работа";
}
```

### isWriter()

Проверяет, является ли профессия сценарной.

```php
public function isWriter(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сценарист, `false` в противном случае

#### Пример использования

```php
if (ProfessionKey::WRITER->isWriter()) {
    echo "Сценарная работа";
}
```

### isVoiceDirector()

Проверяет, является ли роль режиссёром озвучивания.

```php
public function isVoiceDirector(): bool
```

#### Возвращаемое значение

- `bool` - `true` если режиссёр озвучивания, `false` в противном случае

#### Описание

Определяет, соответствует ли текущий экземпляр enum константе VOICE_DIRECTOR. Используется для фильтрации персонала по специфической роли в озвучивании фильмов и сериалов.

#### Пример использования

```php
if ($role->isVoiceDirector()) {
    echo 'Это режиссёр озвучивания';
}
```

### isComposer()

Проверяет, является ли роль композитором.

```php
public function isComposer(): bool
```

#### Возвращаемое значение

- `bool` - `true` если композитор, `false` в противном случае

#### Описание

Определяет, соответствует ли текущий экземпляр enum константе COMPOSER. Используется для идентификации людей, ответственных за музыкальное сопровождение фильма или сериала.

#### Пример использования

```php
if ($role->isComposer()) {
    echo 'Этот человек создал музыку к фильму';
}
```

### isEditor()

Проверяет, является ли роль редактором.

```php
public function isEditor(): bool
```

#### Возвращаемое значение

- `bool` - `true` если редактор, `false` в противном случае

#### Описание

Определяет, соответствует ли текущий экземпляр enum константе EDITOR. Используется для определения людей, занимавшихся монтажом и редактированием материала фильма или сериала.

#### Пример использования

```php
if ($role->isEditor()) {
    echo 'Этот человек занимался монтажом';
}
```

### isDesigner()

Проверяет, является ли роль дизайнером.

```php
public function isDesigner(): bool
```

#### Возвращаемое значение

- `bool` - `true` если дизайнер, `false` в противном случае

#### Описание

Определяет, соответствует ли текущий экземпляр enum константе DESIGN. Используется для идентификации специалистов по визуальному оформлению, художников-постановщиков и дизайнеров костюмов.

#### Пример использования

```php
if ($role->isDesigner()) {
    echo 'Этот человек работал над визуальным оформлением';
}
```

### isTranslator()

Проверяет, является ли роль переводчиком.

```php
public function isTranslator(): bool
```

#### Возвращаемое значение

- `bool` - `true` если переводчик, `false` в противном случае

#### Описание

Определяет, соответствует ли текущий экземпляр enum константе TRANSLATOR. Используется для определения людей, ответственных за перевод диалогов и локализацию контента на другие языки.

#### Пример использования

```php
if ($role->isTranslator()) {
    echo 'Этот человек занимался переводом';
}
```

### isProducer()

Проверяет, является ли профессия продюсерской.

```php
public function isProducer(): bool
```

#### Возвращаемое значение

- `bool` - `true` если продюсер, `false` в противном случае

#### Пример использования

```php
if (ProfessionKey::PRODUCER->isProducer()) {
    echo "Продюсерская работа";
}
```

### getShortName()

Получает краткое название профессии.

```php
public function getShortName(): string
```

#### Возвращаемое значение

- `string` - Краткое название профессии

#### Описание

Возвращает краткое название без дополнительных пояснений.

#### Пример использования

```php
echo ProfessionKey::HRONO_TITR_MALE->getShortName(); // "За кадром"
echo ProfessionKey::HIMSELF->getShortName(); // "В роли самого себя"
```

### getDisplayName()

Получает отображаемое название профессии на русском языке.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название профессии

#### Описание

Возвращает человекочитаемое название профессии для отображения пользователю.

#### Пример использования

```php
echo ProfessionKey::ACTOR->getDisplayName(); // "Актер"
echo ProfessionKey::DIRECTOR->getDisplayName(); // "Режиссер"
```

### getCategory()

Получает категорию профессии.

```php
public function getCategory(): string
```

#### Возвращаемое значение

- `string` - Категория профессии

#### Описание

Возвращает категорию, к которой относится профессия.

#### Пример использования

```php
echo ProfessionKey::ACTOR->getCategory(); // "Творческая"
echo ProfessionKey::PRODUCER->getCategory(); // "Управленческая"
```

### isCreative()

Проверяет, является ли профессия творческой.

```php
public function isCreative(): bool
```

#### Возвращаемое значение

- `bool` - `true` если творческая профессия, `false` в противном случае

#### Описание

К творческим профессиям относятся актер, режиссер, сценарист, композитор.

#### Пример использования

```php
if (ProfessionKey::ACTOR->isCreative()) {
    echo "Творческая профессия";
}
```

### isManagement()

Проверяет, является ли профессия управленческой.

```php
public function isManagement(): bool
```

#### Возвращаемое значение

- `bool` - `true` если управленческая профессия, `false` в противном случае

#### Описание

К управленческим профессиям относятся продюсеры.

#### Пример использования

```php
if (ProfessionKey::PRODUCER->isManagement()) {
    echo "Управленческая профессия";
}
```

### isTechnical()

Проверяет, является ли профессия технической.

```php
public function isTechnical(): bool
```

#### Возвращаемое значение

- `bool` - `true` если техническая профессия, `false` в противном случае

#### Описание

К техническим профессиям относятся оператор, монтажер, художник.

#### Пример использования

```php
if (ProfessionKey::OPERATOR->isTechnical()) {
    echo "Техническая профессия";
}
```

### isSpecial()

Проверяет, является ли профессия специальной.

```php
public function isSpecial(): bool
```

#### Возвращаемое значение

- `bool` - `true` если специальная профессия, `false` в противном случае

#### Описание

К специальным профессиям относятся роли самого себя, за кадром, переводчик.

#### Пример использования

```php
if (ProfessionKey::HIMSELF->isSpecial()) {
    echo "Специальная роль";
}
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ProfessionKey;

// Создание клиента
$client = new Client('your-api-key');

// Получение персонала фильма
$personService = $client->persons;
$staff = $personService->getFilmStaff(301); // Матрица

echo "=== Анализ персонала фильма 'Матрица' ===\n";

// Группировка по категориям
$categories = [
    'Творческая' => [],
    'Управленческая' => [],
    'Техническая' => [],
    'Специальная' => []
];

foreach ($staff as $member) {
    $category = $member->professionKey->getCategory();
    if (isset($categories[$category])) {
        $categories[$category][] = $member;
    }
}

// Вывод статистики по категориям
foreach ($categories as $category => $members) {
    if (!empty($members)) {
        echo "\n📁 {$category} (" . count($members) . " человек):\n";

        // Группировка по профессиям внутри категории
        $professions = [];
        foreach ($members as $member) {
            $profession = $member->professionKey->getDisplayName();
            if (!isset($professions[$profession])) {
                $professions[$profession] = 0;
            }
            $professions[$profession]++;
        }

        foreach ($professions as $profession => $count) {
            echo "  - {$profession}: {$count}\n";
        }
    }
}

// Поиск конкретных профессий
echo "\n🔍 Поиск конкретных профессий:\n";

$actors = array_filter($staff, fn($member) => $member->professionKey->isActor());
$directors = array_filter($staff, fn($member) => $member->professionKey->isDirector());
$writers = array_filter($staff, fn($member) => $member->professionKey->isWriter());
$composers = array_filter($staff, fn($member) => $member->professionKey->isComposer());

echo "Актеров: " . count($actors) . "\n";
echo "Режиссеров: " . count($directors) . "\n";
echo "Сценаристов: " . count($writers) . "\n";
echo "Композиторов: " . count($composers) . "\n";
```

## Работа с профессиями

```php
// Функция для получения персонала по профессии
function getStaffByProfession(array $staff, ProfessionKey $profession): array {
    return array_filter($staff, fn($member) => $member->professionKey === $profession);
}

// Функция для получения персонала по категории
function getStaffByCategory(array $staff, string $category): array {
    return array_filter($staff, function($member) use ($category) {
        return $member->professionKey->getCategory() === $category;
    });
}

// Функция для анализа профессий
function analyzeProfessions(array $staff): array {
    $analysis = [
        'total' => count($staff),
        'categories' => [],
        'professions' => [],
        'creative' => 0,
        'management' => 0,
        'technical' => 0,
        'special' => 0
    ];

    foreach ($staff as $member) {
        $profession = $member->professionKey;

        // Подсчет по категориям
        $category = $profession->getCategory();
        if (!isset($analysis['categories'][$category])) {
            $analysis['categories'][$category] = 0;
        }
        $analysis['categories'][$category]++;

        // Подсчет по профессиям
        $professionName = $profession->getDisplayName();
        if (!isset($analysis['professions'][$professionName])) {
            $analysis['professions'][$professionName] = 0;
        }
        $analysis['professions'][$professionName]++;

        // Подсчет по типам
        if ($profession->isCreative()) $analysis['creative']++;
        if ($profession->isManagement()) $analysis['management']++;
        if ($profession->isTechnical()) $analysis['technical']++;
        if ($profession->isSpecial()) $analysis['special']++;
    }

    return $analysis;
}

// Использование
$staff = $personService->getFilmStaff(301);
$analysis = analyzeProfessions($staff);

echo "=== Анализ профессий ===\n";
echo "Всего участников: {$analysis['total']}\n";
echo "Творческих: {$analysis['creative']}\n";
echo "Управленческих: {$analysis['management']}\n";
echo "Технических: {$analysis['technical']}\n";
echo "Специальных: {$analysis['special']}\n";

echo "\nПо категориям:\n";
foreach ($analysis['categories'] as $category => $count) {
    echo "- {$category}: {$count}\n";
}
```

## Создание отчета о персонале

```php
function createStaffReport(array $staff): string {
    $report = "=== ОТЧЕТ О ПЕРСОНАЛЕ ФИЛЬМА ===\n\n";

    // Основные творческие роли
    $mainRoles = [
        ProfessionKey::DIRECTOR => 'Режиссер',
        ProfessionKey::WRITER => 'Сценарист',
        ProfessionKey::COMPOSER => 'Композитор'
    ];

    foreach ($mainRoles as $profession => $title) {
        $members = getStaffByProfession($staff, $profession);
        if (!empty($members)) {
            $report .= "🎬 {$title}:\n";
            foreach ($members as $member) {
                $report .= "  - {$member->getDisplayName()}\n";
            }
            $report .= "\n";
        }
    }

    // Актеры
    $actors = getStaffByProfession($staff, ProfessionKey::ACTOR);
    if (!empty($actors)) {
        $report .= "🎭 Актеры (" . count($actors) . "):\n";
        foreach (array_slice($actors, 0, 10) as $actor) {
            $report .= "  - {$actor->getDisplayName()}";
            if ($actor->description) {
                $report .= " ({$actor->description})";
            }
            $report .= "\n";
        }
        if (count($actors) > 10) {
            $report .= "  ... и еще " . (count($actors) - 10) . " актеров\n";
        }
        $report .= "\n";
    }

    // Технический персонал
    $technical = getStaffByCategory($staff, 'Техническая');
    if (!empty($technical)) {
        $report .= "🔧 Технический персонал (" . count($technical) . "):\n";
        $techProfessions = [];
        foreach ($technical as $member) {
            $profession = $member->professionKey->getDisplayName();
            if (!isset($techProfessions[$profession])) {
                $techProfessions[$profession] = [];
            }
            $techProfessions[$profession][] = $member;
        }

        foreach ($techProfessions as $profession => $members) {
            $report .= "  {$profession} (" . count($members) . "):\n";
            foreach (array_slice($members, 0, 3) as $member) {
                $report .= "    - {$member->getDisplayName()}\n";
            }
            if (count($members) > 3) {
                $report .= "    ... и еще " . (count($members) - 3) . "\n";
            }
        }
    }

    return $report;
}

// Использование
$staff = $personService->getFilmStaff(301);
$report = createStaffReport($staff);
echo $report;
```

## Связанные классы

- [`Staff`](../models/staff.md) - Модель персонала
- [`PersonFilm`](../models/person-film.md) - Фильм в фильмографии персоны
- [`PersonService`](../services/person-service.md) - Сервис для работы с персонами
