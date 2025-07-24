# DefaultResponse

Базовый класс для ответов Kinopoisk API.

## Описание

`DefaultResponse` предоставляет базовую функциональность для всех типов ответов API, включая создание из массива данных и валидацию целевого класса. Содержит общие свойства: общее количество элементов и массив элементов.

### Основные возможности

- Хранение общего количества элементов и массива данных
- Создание экземпляра из массива данных API с валидацией
- Автоматическое преобразование элементов в указанный класс
- Проверка существования и совместимости целевого класса

## Свойства

- `$total` (int) - Общее количество элементов в коллекции (включая не загруженные)
- `$items` (array) - Массив элементов данных в текущем ответе

## Конструктор

```php
public function __construct(
    public int $total,
    public array $items,
)
```

### Параметры

- `$total` (int) - Общее количество элементов в коллекции
- `$items` (array) - Массив элементов данных

### Исключения

- `\NotKinopoisk\Exception\KpValidationException` - Если данные невалидны

### Пример использования

```php
// В дочернем классе
public function __construct(int $total, array $films) {
    parent::__construct($total, $films);
}
```

## Методы

### fromArray()

Создает экземпляр ответа из массива данных API.

```php
public static function fromArray(array $data, string $cls): self
```

#### Параметры

- `$data` (array) - Массив данных от API, содержащий 'total' и 'items'
- `$cls` (string) - Полное имя класса для преобразования элементов

#### Возвращаемое значение

- `self` - Новый экземпляр текущего класса с преобразованными данными

#### Исключения

- `KpValidationException` - Если указанный класс не существует
- `KpValidationException` - Если класс не имеет метода fromArray
- `KpValidationException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'total' => 1000,
    'items' => [
        ['id' => 1, 'name' => 'Film 1'],
        ['id' => 2, 'name' => 'Film 2']
    ]
];

$response = FilmResponse::fromArray($apiData, Film::class);
// $response->items будет содержать массив объектов Film
```

### checkClass()

Валидирует целевой класс для преобразования элементов.

```php
public static function checkClass(string $cls): void
```

#### Параметры

- `$cls` (string) - Полное имя класса

#### Исключения

- `KpValidationException` - Если класс невалиден

#### Описание

Проверяет:

- Существование класса
- Наличие статического метода `fromArray`
- Статичность метода `fromArray`

### first()

Возвращает первый элемент или null, если элементов нет.

```php
public function first(): ?object
```

#### Возвращаемое значение

- `object|null` - Первый элемент или null

#### Пример использования

```php
$firstFilm = $response->first();
if ($firstFilm) {
    echo "Первый фильм: " . $firstFilm->getDisplayName();
}
```

### last()

Возвращает последний элемент или null, если элементов нет.

```php
public function last(): ?object
```

#### Возвращаемое значение

- `object|null` - Последний элемент или null

#### Пример использования

```php
$lastFilm = $response->last();
if ($lastFilm) {
    echo "Последний фильм: " . $lastFilm->getDisplayName();
}
```

### isEmpty()

Проверяет, пуст ли ответ.

```php
public function isEmpty(): bool
```

#### Возвращаемое значение

- `bool` - `true`, если нет элементов

#### Пример использования

```php
if ($response->isEmpty()) {
    echo "Результаты не найдены";
} else {
    echo "Найдено элементов: " . $response->getCount();
}
```

### toArray()

Преобразует ответ в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array{total: int, items: array}` - Массив с данными ответа

#### Пример использования

```php
$array = $response->toArray();
echo "Всего: {$array['total']}, Загружено: " . count($array['items']);
```

### getCount()

Возвращает количество элементов в текущем ответе.

```php
public function getCount(): int
```

#### Возвращаемое значение

- `int` - Количество загруженных элементов

#### Пример использования

```php
echo "Загружено элементов: " . $response->getCount();
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Film;

// Создание клиента
$client = new Client('your-api-key');

// Получение фильмов
$filmService = $client->films;
$response = $filmService->getTopFilms();

echo "=== Работа с DefaultResponse ===\n";

// Основная информация
echo "Общее количество фильмов: {$response->total}\n";
echo "Загружено в ответе: " . $response->getCount() . "\n";
echo "Ответ пустой: " . ($response->isEmpty() ? 'Да' : 'Нет') . "\n\n";

// Работа с элементами
if (!$response->isEmpty()) {
    $firstFilm = $response->first();
    $lastFilm = $response->last();

    echo "Первый фильм: {$firstFilm->getDisplayName()}\n";
    echo "Последний фильм: {$lastFilm->getDisplayName()}\n\n";

    // Вывод всех фильмов
    echo "Список фильмов:\n";
    foreach ($response->items as $index => $film) {
        echo ($index + 1) . ". {$film->getDisplayName()}\n";
        if ($film->rating) {
            echo "   Рейтинг: {$film->rating}\n";
        }
        if ($film->year) {
            echo "   Год: {$film->year}\n";
        }
        echo "\n";
    }
}

// Преобразование в массив
$arrayData = $response->toArray();
echo "Данные в виде массива:\n";
echo "total: {$arrayData['total']}\n";
echo "items count: " . count($arrayData['items']) . "\n";
```

## Работа с DefaultResponse

```php
// Функция для фильтрации элементов ответа
function filterResponseItems(DefaultResponse $response, callable $filter): array {
    return array_filter($response->items, $filter);
}

// Функция для сортировки элементов ответа
function sortResponseItems(DefaultResponse $response, callable $comparator): array {
    $items = $response->items;
    usort($items, $comparator);
    return $items;
}

// Функция для получения статистики ответа
function getResponseStats(DefaultResponse $response): array {
    return [
        'total' => $response->total,
        'loaded' => $response->getCount(),
        'isEmpty' => $response->isEmpty(),
        'hasMore' => $response->total > $response->getCount(),
        'loadPercentage' => $response->total > 0 ? round(($response->getCount() / $response->total) * 100, 1) : 0
    ];
}

// Функция для получения элементов по индексам
function getResponseItemsByIndexes(DefaultResponse $response, array $indexes): array {
    $result = [];
    foreach ($indexes as $index) {
        if (isset($response->items[$index])) {
            $result[] = $response->items[$index];
        }
    }
    return $result;
}

// Функция для получения случайных элементов
function getRandomResponseItems(DefaultResponse $response, int $count = 1): array {
    if ($response->isEmpty()) {
        return [];
    }

    $items = $response->items;
    $totalItems = count($items);

    if ($count >= $totalItems) {
        return $items;
    }

    $randomIndexes = array_rand($items, $count);
    if (!is_array($randomIndexes)) {
        $randomIndexes = [$randomIndexes];
    }

    return array_map(fn($index) => $items[$index], $randomIndexes);
}

// Функция для группировки элементов
function groupResponseItems(DefaultResponse $response, callable $groupBy): array {
    $groups = [];

    foreach ($response->items as $item) {
        $key = $groupBy($item);
        if (!isset($groups[$key])) {
            $groups[$key] = [];
        }
        $groups[$key][] = $item;
    }

    return $groups;
}

// Функция для поиска элементов
function searchResponseItems(DefaultResponse $response, callable $search): ?object {
    foreach ($response->items as $item) {
        if ($search($item)) {
            return $item;
        }
    }
    return null;
}

// Функция для получения уникальных элементов
function getUniqueResponseItems(DefaultResponse $response, callable $uniqueBy): array {
    $unique = [];
    $seen = [];

    foreach ($response->items as $item) {
        $key = $uniqueBy($item);
        if (!isset($seen[$key])) {
            $seen[$key] = true;
            $unique[] = $item;
        }
    }

    return $unique;
}

// Использование
$response = $filmService->getTopFilms();

// Статистика
$stats = getResponseStats($response);
echo "Статистика ответа:\n";
echo "- Всего элементов: {$stats['total']}\n";
echo "- Загружено: {$stats['loaded']}\n";
echo "- Пустой ответ: " . ($stats['isEmpty'] ? 'Да' : 'Нет') . "\n";
echo "- Есть еще элементы: " . ($stats['hasMore'] ? 'Да' : 'Нет') . "\n";
echo "- Процент загрузки: {$stats['loadPercentage']}%\n";

// Фильтрация
$highRatedFilms = filterResponseItems($response, fn($film) => $film->rating && $film->rating >= 8.0);
echo "\nВысоко оцененных фильмов: " . count($highRatedFilms) . "\n";

// Сортировка
$sortedByRating = sortResponseItems($response, function($a, $b) {
    $ratingA = $a->rating ?? 0;
    $ratingB = $b->rating ?? 0;
    return $ratingB <=> $ratingA;
});

echo "\nТоп-5 по рейтингу:\n";
foreach (array_slice($sortedByRating, 0, 5) as $index => $film) {
    echo ($index + 1) . ". {$film->getDisplayName()} - {$film->rating}\n";
}

// Группировка по годам
$groupedByYear = groupResponseItems($response, fn($film) => $film->year ?? 'Неизвестно');
echo "\nГруппировка по годам:\n";
foreach ($groupedByYear as $year => $films) {
    echo "- {$year}: " . count($films) . " фильмов\n";
}

// Поиск
$matrixFilm = searchResponseItems($response, fn($film) =>
    stripos($film->getDisplayName(), 'матрица') !== false
);

if ($matrixFilm) {
    echo "\nНайден фильм 'Матрица': {$matrixFilm->getDisplayName()}\n";
}

// Случайные элементы
$randomFilms = getRandomResponseItems($response, 3);
echo "\nСлучайные фильмы:\n";
foreach ($randomFilms as $film) {
    echo "- {$film->getDisplayName()}\n";
}
```

## Создание отчета по ответу

```php
class ResponseReport {
    private DefaultResponse $response;

    public function __construct(DefaultResponse $response) {
        $this->response = $response;
    }

    public function getResponse(): DefaultResponse {
        return $this->response;
    }

    public function getStats(): array {
        return getResponseStats($this->response);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ОТВЕТУ API ===\n\n";

        $stats = $this->getStats();

        // Основная информация
        $report .= "📊 ОСНОВНАЯ ИНФОРМАЦИЯ:\n";
        $report .= "Общее количество элементов: {$stats['total']}\n";
        $report .= "Загружено элементов: {$stats['loaded']}\n";
        $report .= "Ответ пустой: " . ($stats['isEmpty'] ? 'Да' : 'Нет') . "\n";
        $report .= "Есть еще элементы: " . ($stats['hasMore'] ? 'Да' : 'Нет') . "\n";
        $report .= "Процент загрузки: {$stats['loadPercentage']}%\n\n";

        if (!$this->response->isEmpty()) {
            // Первый и последний элементы
            $first = $this->response->first();
            $last = $this->response->last();

            $report .= "🎬 ПЕРВЫЙ ЭЛЕМЕНТ:\n";
            $report .= "• {$first->getDisplayName()}\n";
            if (method_exists($first, 'rating') && $first->rating) {
                $report .= "• Рейтинг: {$first->rating}\n";
            }
            if (method_exists($first, 'year') && $first->year) {
                $report .= "• Год: {$first->year}\n";
            }
            $report .= "\n";

            $report .= "🎬 ПОСЛЕДНИЙ ЭЛЕМЕНТ:\n";
            $report .= "• {$last->getDisplayName()}\n";
            if (method_exists($last, 'rating') && $last->rating) {
                $report .= "• Рейтинг: {$last->rating}\n";
            }
            if (method_exists($last, 'year') && $last->year) {
                $report .= "• Год: {$last->year}\n";
            }
            $report .= "\n";

            // Статистика по рейтингам (если есть)
            $ratings = array_filter(array_map(fn($item) =>
                method_exists($item, 'rating') ? $item->rating : null,
                $this->response->items
            ));

            if (!empty($ratings)) {
                $avgRating = round(array_sum($ratings) / count($ratings), 2);
                $maxRating = max($ratings);
                $minRating = min($ratings);

                $report .= "📈 СТАТИСТИКА ПО РЕЙТИНГАМ:\n";
                $report .= "Средний рейтинг: {$avgRating}\n";
                $report .= "Максимальный рейтинг: {$maxRating}\n";
                $report .= "Минимальный рейтинг: {$minRating}\n";
                $report .= "Элементов с рейтингом: " . count($ratings) . "\n\n";
            }

            // Статистика по годам (если есть)
            $years = array_filter(array_map(fn($item) =>
                method_exists($item, 'year') ? $item->year : null,
                $this->response->items
            ));

            if (!empty($years)) {
                $yearStats = array_count_values($years);
                arsort($yearStats);

                $report .= "📅 СТАТИСТИКА ПО ГОДАМ:\n";
                foreach (array_slice($yearStats, 0, 10, true) as $year => $count) {
                    $report .= "• {$year}: {$count} элементов\n";
                }
                $report .= "\n";
            }

            // Список всех элементов
            $report .= "📋 СПИСОК ВСЕХ ЭЛЕМЕНТОВ:\n";
            foreach ($this->response->items as $index => $item) {
                $report .= ($index + 1) . ". {$item->getDisplayName()}\n";
                if (method_exists($item, 'rating') && $item->rating) {
                    $report .= "   Рейтинг: {$item->rating}\n";
                }
                if (method_exists($item, 'year') && $item->year) {
                    $report .= "   Год: {$item->year}\n";
                }
            }
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".item-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".item-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; background-color: #007bff; transition: width 0.3s ease; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getStats();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Основная информация</h2>\n";
        $html .= "<p><strong>Общее количество элементов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Загружено элементов:</strong> {$stats['loaded']}</p>\n";
        $html .= "<p><strong>Ответ пустой:</strong> " . ($stats['isEmpty'] ? 'Да' : 'Нет') . "</p>\n";
        $html .= "<p><strong>Есть еще элементы:</strong> " . ($stats['hasMore'] ? 'Да' : 'Нет') . "</p>\n";

        // Прогресс-бар загрузки
        $html .= "<h3>Процент загрузки</h3>\n";
        $html .= "<div class='progress-bar'>\n";
        $html .= "<div class='progress-fill' style='width: {$stats['loadPercentage']}%'></div>\n";
        $html .= "</div>\n";
        $html .= "<p>{$stats['loadPercentage']}% загружено</p>\n";

        $html .= "</div>\n";

        // Элементы
        if (!$this->response->isEmpty()) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📋 Список элементов</div>\n";
            $html .= "<div class='item-grid'>\n";

            foreach ($this->response->items as $item) {
                $html .= "<div class='item'>\n";
                $html .= "<div class='item-title'>{$item->getDisplayName()}</div>\n";
                if (method_exists($item, 'rating') && $item->rating) {
                    $html .= "<div class='item-meta'>Рейтинг: {$item->rating}</div>\n";
                }
                if (method_exists($item, 'year') && $item->year) {
                    $html .= "<div class='item-meta'>Год: {$item->year}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$response = $filmService->getTopFilms();
$report = new ResponseReport($response);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по ответу API');
file_put_contents('response_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в response_report.html\n";
```

## Анализ ответов API

```php
function analyzeResponse(DefaultResponse $response): array {
    $analysis = [
        'basicStats' => [
            'total' => $response->total,
            'loaded' => $response->getCount(),
            'isEmpty' => $response->isEmpty(),
            'hasMore' => $response->total > $response->getCount(),
            'loadPercentage' => $response->total > 0 ? round(($response->getCount() / $response->total) * 100, 1) : 0
        ],
        'itemAnalysis' => [
            'firstItem' => null,
            'lastItem' => null,
            'ratingStats' => [],
            'yearStats' => [],
            'typeStats' => []
        ]
    ];

    if (!$response->isEmpty()) {
        $analysis['itemAnalysis']['firstItem'] = $response->first();
        $analysis['itemAnalysis']['lastItem'] = $response->last();

        // Анализ рейтингов
        $ratings = array_filter(array_map(fn($item) =>
            method_exists($item, 'rating') ? $item->rating : null,
            $response->items
        ));

        if (!empty($ratings)) {
            $analysis['itemAnalysis']['ratingStats'] = [
                'count' => count($ratings),
                'average' => round(array_sum($ratings) / count($ratings), 2),
                'max' => max($ratings),
                'min' => min($ratings),
                'distribution' => array_count_values(array_map(fn($r) => floor($r), $ratings))
            ];
        }

        // Анализ годов
        $years = array_filter(array_map(fn($item) =>
            method_exists($item, 'year') ? $item->year : null,
            $response->items
        ));

        if (!empty($years)) {
            $analysis['itemAnalysis']['yearStats'] = [
                'count' => count($years),
                'distribution' => array_count_values($years),
                'range' => [min($years), max($years)]
            ];
        }

        // Анализ типов
        $types = array_filter(array_map(fn($item) =>
            method_exists($item, 'type') ? $item->type->value : null,
            $response->items
        ));

        if (!empty($types)) {
            $analysis['itemAnalysis']['typeStats'] = [
                'count' => count($types),
                'distribution' => array_count_values($types)
            ];
        }
    }

    return $analysis;
}

// Использование
$response = $filmService->getTopFilms();
$analysis = analyzeResponse($response);

echo "=== Анализ ответа API ===\n";

// Основная статистика
$basicStats = $analysis['basicStats'];
echo "Основная статистика:\n";
echo "- Всего элементов: {$basicStats['total']}\n";
echo "- Загружено: {$basicStats['loaded']}\n";
echo "- Пустой ответ: " . ($basicStats['isEmpty'] ? 'Да' : 'Нет') . "\n";
echo "- Есть еще элементы: " . ($basicStats['hasMore'] ? 'Да' : 'Нет') . "\n";
echo "- Процент загрузки: {$basicStats['loadPercentage']}%\n";

// Анализ элементов
$itemAnalysis = $analysis['itemAnalysis'];

if ($itemAnalysis['firstItem']) {
    echo "\nПервый элемент: {$itemAnalysis['firstItem']->getDisplayName()}\n";
}

if ($itemAnalysis['lastItem']) {
    echo "Последний элемент: {$itemAnalysis['lastItem']->getDisplayName()}\n";
}

// Статистика рейтингов
if (!empty($itemAnalysis['ratingStats'])) {
    $ratingStats = $itemAnalysis['ratingStats'];
    echo "\nСтатистика рейтингов:\n";
    echo "- Элементов с рейтингом: {$ratingStats['count']}\n";
    echo "- Средний рейтинг: {$ratingStats['average']}\n";
    echo "- Максимальный рейтинг: {$ratingStats['max']}\n";
    echo "- Минимальный рейтинг: {$ratingStats['min']}\n";

    echo "\nРаспределение по рейтингам:\n";
    krsort($ratingStats['distribution']);
    foreach ($ratingStats['distribution'] as $rating => $count) {
        echo "- {$rating}+: {$count} элементов\n";
    }
}

// Статистика годов
if (!empty($itemAnalysis['yearStats'])) {
    $yearStats = $itemAnalysis['yearStats'];
    echo "\nСтатистика годов:\n";
    echo "- Элементов с годом: {$yearStats['count']}\n";
    echo "- Диапазон: {$yearStats['range'][0]} - {$yearStats['range'][1]}\n";

    echo "\nТоп годов:\n";
    arsort($yearStats['distribution']);
    foreach (array_slice($yearStats['distribution'], 0, 10, true) as $year => $count) {
        echo "- {$year}: {$count} элементов\n";
    }
}

// Статистика типов
if (!empty($itemAnalysis['typeStats'])) {
    $typeStats = $itemAnalysis['typeStats'];
    echo "\nСтатистика типов:\n";
    echo "- Элементов с типом: {$typeStats['count']}\n";

    echo "\nРаспределение по типам:\n";
    arsort($typeStats['distribution']);
    foreach ($typeStats['distribution'] as $type => $count) {
        echo "- {$type}: {$count} элементов\n";
    }
}
```

## Связанные классы

- [`ResponseInterface`](../interfaces/response-interface.md) - Интерфейс для ответов API
- [`KpValidationException`](../exceptions/kp-validation-exception.md) - Исключение валидации
- [`Film`](../models/film.md) - Модель фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
