# Fact

Модель факта из Kinopoisk API.

## Описание

`Fact` представляет информацию о факте или ошибке, связанной с фильмом: интересные факты о съемках, ошибки в фильме (блуперы) и т.д.

### Основные возможности

- Хранение информации о факте в неизменяемом виде
- Создание объекта из массива данных API
- Определение типа факта (ошибка или интересный факт)
- Проверка на спойлеры

**API Endpoint:** `/api/v2.2/films/{id}/facts`

## Свойства

### Основная информация

- `$text` (string) - Текст факта или описания ошибки
- `$type` (FactType) - Тип факта (FACT, BLOOPER и т.д.)
- `$spoiler` (bool) - Флаг, указывающий на наличие спойлера

## Конструктор

```php
public function __construct(
    public readonly string $text,
    public readonly FactType $type,
    public readonly bool $spoiler,
)
```

### Пример создания

```php
$fact = new Fact(
    text: 'В сцене драки видно, что актер использует дублера',
    type: FactType::BLOOPER,
    spoiler: false
);
```

## Методы

### fromArray()

Создает экземпляр факта из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных факта от API

#### Возвращаемое значение

- `self` - Новый экземпляр факта

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'text' => 'Интересный факт о съемках...',
    'type' => 'FACT',
    'spoiler' => false
];

$fact = Fact::fromArray($apiData);
```

### isBlooper()

Проверяет, является ли факт ошибкой в фильме.

```php
public function isBlooper(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это ошибка в фильме, `false` в противном случае

#### Описание

Определяет, относится ли факт к категории ошибок (блуперов), которые были допущены во время съемок фильма.

#### Пример использования

```php
if ($fact->isBlooper()) {
    echo "Найдена ошибка: {$fact->text}";
}
```

### isFact()

Проверяет, является ли факт интересным фактом.

```php
public function isFact(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это интересный факт, `false` в противном случае

#### Описание

Определяет, относится ли факт к категории интересных фактов о съемках, актерах или других аспектах создания фильма.

#### Пример использования

```php
if ($fact->isFact()) {
    echo "Интересный факт: {$fact->text}";
}
```

### toArray()

Преобразует объект факта в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными факта

#### Пример использования

```php
$factArray = $fact->toArray();
echo json_encode($factArray); // JSON представление факта
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Fact;
use NotKinopoisk\Enums\FactType;

// Создание клиента
$client = new Client('your-api-key');

// Получение фактов о фильме
$filmService = $client->films;
$facts = $filmService->getFacts(301); // Матрица

echo "=== Факты о фильме 'Матрица' ===\n";

// Группировка по типам
$bloopers = [];
$interestingFacts = [];
$spoilers = [];

foreach ($facts as $fact) {
    if ($fact->spoiler) {
        $spoilers[] = $fact;
    }

    if ($fact->isBlooper()) {
        $bloopers[] = $fact;
    } elseif ($fact->isFact()) {
        $interestingFacts[] = $fact;
    }
}

// Вывод интересных фактов
if (!empty($interestingFacts)) {
    echo "\n🎬 Интересные факты (" . count($interestingFacts) . "):\n";
    foreach (array_slice($interestingFacts, 0, 5) as $fact) {
        echo "📝 {$fact->text}\n";
        if ($fact->spoiler) {
            echo "⚠️  Содержит спойлер!\n";
        }
        echo "---\n";
    }
}

// Вывод ошибок в фильме
if (!empty($bloopers)) {
    echo "\n🎭 Ошибки в фильме (" . count($bloopers) . "):\n";
    foreach (array_slice($bloopers, 0, 3) as $fact) {
        echo "❌ {$fact->text}\n";
        if ($fact->spoiler) {
            echo "⚠️  Содержит спойлер!\n";
        }
        echo "---\n";
    }
}

// Вывод спойлеров
if (!empty($spoilers)) {
    echo "\n🚨 Факты со спойлерами (" . count($spoilers) . "):\n";
    foreach (array_slice($spoilers, 0, 2) as $fact) {
        echo "⚠️  {$fact->text}\n";
        echo "Тип: {$fact->type->getDisplayName()}\n";
        echo "---\n";
    }
}
```

## Работа с фактами

```php
// Функция для фильтрации фактов по типу
function filterFactsByType(array $facts, FactType $type): array {
    return array_filter($facts, fn($fact) => $fact->type === $type);
}

// Функция для поиска фактов по ключевому слову
function searchFactsByKeyword(array $facts, string $keyword): array {
    $keyword = strtolower($keyword);

    return array_filter($facts, function($fact) use ($keyword) {
        return strpos(strtolower($fact->text), $keyword) !== false;
    });
}

// Функция для получения фактов без спойлеров
function getFactsWithoutSpoilers(array $facts): array {
    return array_filter($facts, fn($fact) => !$fact->spoiler);
}

// Использование
$facts = $filmService->getFacts(301);

// Фильтрация по типу
$bloopersOnly = filterFactsByType($facts, FactType::BLOOPER);
echo "Ошибок в фильме: " . count($bloopersOnly) . "\n";

// Поиск по ключевому слову
$matrixFacts = searchFactsByKeyword($facts, 'матрица');
echo "Фактов о матрице: " . count($matrixFacts) . "\n";

// Факты без спойлеров
$safeFacts = getFactsWithoutSpoilers($facts);
echo "Фактов без спойлеров: " . count($safeFacts) . "\n";
```

## Анализ фактов

```php
function analyzeFacts(array $facts): array {
    $analysis = [
        'total' => count($facts),
        'bloopers' => 0,
        'interestingFacts' => 0,
        'spoilers' => 0,
        'avgTextLength' => 0,
        'types' => []
    ];

    $totalLength = 0;
    $typeStats = [];

    foreach ($facts as $fact) {
        // Подсчет типов
        if ($fact->isBlooper()) {
            $analysis['bloopers']++;
        } elseif ($fact->isFact()) {
            $analysis['interestingFacts']++;
        }

        // Подсчет спойлеров
        if ($fact->spoiler) {
            $analysis['spoilers']++;
        }

        // Статистика по типам
        $typeKey = $fact->type->value;
        if (!isset($typeStats[$typeKey])) {
            $typeStats[$typeKey] = 0;
        }
        $typeStats[$typeKey]++;

        // Длина текста
        $totalLength += strlen($fact->text);
    }

    // Средняя длина текста
    if ($analysis['total'] > 0) {
        $analysis['avgTextLength'] = round($totalLength / $analysis['total']);
    }

    // Статистика по типам
    foreach ($typeStats as $type => $count) {
        $analysis['types'][$type] = [
            'count' => $count,
            'percentage' => round(($count / $analysis['total']) * 100, 1)
        ];
    }

    return $analysis;
}

// Использование
$facts = $filmService->getFacts(301);
$analysis = analyzeFacts($facts);

echo "=== Анализ фактов ===\n";
echo "Всего фактов: {$analysis['total']}\n";
echo "Ошибок в фильме: {$analysis['bloopers']}\n";
echo "Интересных фактов: {$analysis['interestingFacts']}\n";
echo "Спойлеров: {$analysis['spoilers']}\n";
echo "Средняя длина текста: {$analysis['avgTextLength']} символов\n";

echo "\nСтатистика по типам:\n";
foreach ($analysis['types'] as $type => $data) {
    echo "- {$type}: {$data['count']} ({$data['percentage']}%)\n";
}
```

## Создание галереи фактов

```php
function createFactsGallery(array $facts, bool $includeSpoilers = false): array {
    $gallery = [
        'bloopers' => [],
        'interestingFacts' => [],
        'spoilers' => []
    ];

    foreach ($facts as $fact) {
        // Факты со спойлерами
        if ($fact->spoiler) {
            if ($includeSpoilers) {
                $gallery['spoilers'][] = $fact;
            }
            continue;
        }

        // Ошибки в фильме
        if ($fact->isBlooper()) {
            $gallery['bloopers'][] = $fact;
        }

        // Интересные факты
        if ($fact->isFact()) {
            $gallery['interestingFacts'][] = $fact;
        }
    }

    return $gallery;
}

// Использование
$facts = $filmService->getFacts(301);
$gallery = createFactsGallery($facts, false); // Без спойлеров

echo "=== Галерея фактов ===\n";
echo "🎭 Ошибок в фильме: " . count($gallery['bloopers']) . "\n";
echo "🎬 Интересных фактов: " . count($gallery['interestingFacts']) . "\n";

// Вывод примеров
if (!empty($gallery['interestingFacts'])) {
    echo "\n📝 Примеры интересных фактов:\n";
    foreach (array_slice($gallery['interestingFacts'], 0, 3) as $fact) {
        echo "- " . substr($fact->text, 0, 100) . "...\n";
    }
}
```

## Связанные классы

- [`FactType`](../enums/fact-type.md) - Типы фактов
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](film.md) - Модель фильма
