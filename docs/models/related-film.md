# RelatedFilm

Модель связанного фильма из Kinopoisk API.

## Описание

`RelatedFilm` представляет информацию о фильме, связанном с основным фильмом (похожие фильмы, сиквелы, приквелы и т.д.).

### Основные возможности

- Хранение информации о связанном фильме в неизменяемом виде
- Создание объекта из массива данных API
- Получение отображаемого названия фильма
- Определение типа связи между фильмами

**API Endpoint:** `/api/v2.2/films/{id}/similars`

## Свойства

### Основная информация

- `$filmId` (int) - Уникальный идентификатор фильма
- `$nameRu` (string|null) - Название на русском языке
- `$nameEn` (string|null) - Название на английском языке
- `$nameOriginal` (string|null) - Оригинальное название
- `$posterUrl` (string) - URL постера
- `$posterUrlPreview` (string) - URL превью постера
- `$relationType` (RelationType) - Тип связи

## Конструктор

```php
public function __construct(
    public readonly int $filmId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $nameOriginal,
    public readonly string $posterUrl,
    public readonly string $posterUrlPreview,
    public readonly RelationType $relationType,
)
```

### Пример создания

```php
$relatedFilm = new RelatedFilm(
    filmId: 301,
    nameRu: 'Матрица',
    nameEn: 'The Matrix',
    nameOriginal: 'The Matrix',
    posterUrl: 'https://example.com/poster.jpg',
    posterUrlPreview: 'https://example.com/poster_small.jpg',
    relationType: RelationType::SIMILAR
);
```

## Методы

### fromArray()

Создает экземпляр модели из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных от API

#### Возвращаемое значение

- `self` - Экземпляр модели

#### Исключения

- `\InvalidArgumentException` - При некорректных данных

#### Пример использования

```php
$filmData = [
    'filmId' => 301,
    'nameRu' => 'Матрица',
    'nameEn' => 'The Matrix',
    'nameOriginal' => 'The Matrix',
    'posterUrl' => 'https://example.com/poster.jpg',
    'posterUrlPreview' => 'https://example.com/poster_small.jpg',
    'relationType' => 'SIMILAR'
];
$film = RelatedFilm::fromArray($filmData);
```

### getDisplayName()

Получает отображаемое название фильма.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название фильма

#### Описание

Возвращает название фильма в приоритетном порядке: русское название → английское название → оригинальное название

#### Пример использования

```php
$displayName = $film->getDisplayName();
echo "Название: $displayName";
```

### toArray()

Преобразует модель в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными модели

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\RelationType;

// Создание клиента
$client = new Client('your-api-key');

// Получение связанных фильмов
$filmService = $client->films;
$relatedFilms = $filmService->getSimilarFilms(301); // ID фильма "Матрица"

echo "=== Связанные фильмы ===\n";

// Общая статистика
echo "Всего связанных фильмов: " . count($relatedFilms) . "\n\n";

// Группировка по типам связи
$groupedByType = [];
foreach ($relatedFilms as $film) {
    $type = $film->relationType->value;
    if (!isset($groupedByType[$type])) {
        $groupedByType[$type] = [];
    }
    $groupedByType[$type][] = $film;
}

// Вывод по группам
foreach ($groupedByType as $type => $films) {
    echo "📋 {$type} (" . count($films) . " фильмов):\n";
    foreach ($films as $film) {
        echo "  • {$film->getDisplayName()} (ID: {$film->filmId})\n";
    }
    echo "\n";
}
```

## Работа со связанными фильмами

```php
// Функция для фильтрации по типу связи
function filterByRelationType(array $relatedFilms, RelationType $type): array {
    return array_filter($relatedFilms, fn($film) => $film->relationType === $type);
}

// Функция для получения фильмов по названию
function findFilmsByName(array $relatedFilms, string $name): array {
    return array_filter($relatedFilms, function($film) use ($name) {
        $displayName = strtolower($film->getDisplayName());
        $searchName = strtolower($name);
        return strpos($displayName, $searchName) !== false;
    });
}

// Функция для группировки по типу связи
function groupByRelationType(array $relatedFilms): array {
    $grouped = [];
    foreach ($relatedFilms as $film) {
        $type = $film->relationType->value;
        if (!isset($grouped[$type])) {
            $grouped[$type] = [];
        }
        $grouped[$type][] = $film;
    }
    return $grouped;
}

// Функция для получения статистики по типам связи
function getRelationTypeStats(array $relatedFilms): array {
    $stats = [];
    foreach ($relatedFilms as $film) {
        $type = $film->relationType->value;
        if (!isset($stats[$type])) {
            $stats[$type] = 0;
        }
        $stats[$type]++;
    }
    return $stats;
}

// Использование
$relatedFilms = $filmService->getSimilarFilms(301);

// Фильтрация
$similarFilms = filterByRelationType($relatedFilms, RelationType::SIMILAR);
$sequels = filterByRelationType($relatedFilms, RelationType::SEQUEL);
$prequels = filterByRelationType($relatedFilms, RelationType::PREQUEL);

echo "Похожих фильмов: " . count($similarFilms) . "\n";
echo "Сиквелов: " . count($sequels) . "\n";
echo "Приквелов: " . count($prequels) . "\n";

// Поиск по названию
$matrixFilms = findFilmsByName($relatedFilms, 'матрица');
echo "Фильмов с 'матрица' в названии: " . count($matrixFilms) . "\n";

// Статистика
$stats = getRelationTypeStats($relatedFilms);
echo "\nСтатистика по типам связи:\n";
foreach ($stats as $type => $count) {
    echo "- {$type}: {$count} фильмов\n";
}
```

## Создание отчета по связанным фильмам

```php
class RelatedFilmReport {
    private array $relatedFilms;

    public function __construct(array $relatedFilms) {
        $this->relatedFilms = $relatedFilms;
    }

    public function getRelatedFilms(): array {
        return $this->relatedFilms;
    }

    public function getByRelationType(RelationType $type): array {
        return filterByRelationType($this->relatedFilms, $type);
    }

    public function getGroupedByType(): array {
        return groupByRelationType($this->relatedFilms);
    }

    public function getStats(): array {
        return getRelationTypeStats($this->relatedFilms);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО СВЯЗАННЫМ ФИЛЬМАМ ===\n\n";

        $stats = $this->getStats();
        $grouped = $this->getGroupedByType();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего связанных фильмов: " . count($this->relatedFilms) . "\n\n";

        // Статистика по типам связи
        $report .= "📋 СТАТИСТИКА ПО ТИПАМ СВЯЗИ:\n";
        foreach ($stats as $type => $count) {
            $percentage = round(($count / count($this->relatedFilms)) * 100, 1);
            $report .= "• {$type}: {$count} фильмов ({$percentage}%)\n";
        }

        $report .= "\n";

        // Детали по типам связи
        foreach ($grouped as $type => $films) {
            $report .= "🎬 {$type} (" . count($films) . " фильмов):\n";
            foreach ($films as $film) {
                $report .= "  • {$film->getDisplayName()}\n";
                $report .= "    ID: {$film->filmId}\n";
                if ($film->nameOriginal && $film->nameOriginal !== $film->getDisplayName()) {
                    $report .= "    Оригинальное название: {$film->nameOriginal}\n";
                }
            }
            $report .= "\n";
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
        $html .= ".film-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".film-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".film-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".film-poster { width: 80px; height: 120px; object-fit: cover; border-radius: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".film-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".similar { border-left: 5px solid #28a745; }\n";
        $html .= ".sequel { border-left: 5px solid #007bff; }\n";
        $html .= ".prequel { border-left: 5px solid #ffc107; }\n";
        $html .= ".remake { border-left: 5px solid #dc3545; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getStats();
        $grouped = $this->getGroupedByType();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего связанных фильмов:</strong> " . count($this->relatedFilms) . "</p>\n";
        $html .= "</div>\n";

        // Статистика по типам связи
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📋 Статистика по типам связи</div>\n";
        foreach ($stats as $type => $count) {
            $percentage = round(($count / count($this->relatedFilms)) * 100, 1);
            $html .= "<p><strong>{$type}:</strong> {$count} фильмов ({$percentage}%)</p>\n";
        }
        $html .= "</div>\n";

        // Фильмы по типам связи
        foreach ($grouped as $type => $films) {
            $cssClass = strtolower($type);

            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🎬 {$type} (" . count($films) . " фильмов)</div>\n";
            $html .= "<div class='film-grid'>\n";

            foreach ($films as $film) {
                $html .= "<div class='film-item {$cssClass}'>\n";
                $html .= "<img src='{$film->posterUrlPreview}' alt='{$film->getDisplayName()}' class='film-poster'>\n";
                $html .= "<div class='film-title'>{$film->getDisplayName()}</div>\n";
                $html .= "<div class='film-meta'>ID: {$film->filmId}</div>\n";
                if ($film->nameOriginal && $film->nameOriginal !== $film->getDisplayName()) {
                    $html .= "<div class='film-meta'>Оригинальное название: {$film->nameOriginal}</div>\n";
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
$relatedFilms = $filmService->getSimilarFilms(301);
$report = new RelatedFilmReport($relatedFilms);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по связанным фильмам');
file_put_contents('related_films_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в related_films_report.html\n";
```

## Анализ связанных фильмов

```php
function analyzeRelatedFilms(array $relatedFilms): array {
    $analysis = [
        'totalFilms' => count($relatedFilms),
        'relationTypeDistribution' => [],
        'nameAnalysis' => [
            'hasRussianName' => 0,
            'hasEnglishName' => 0,
            'hasOriginalName' => 0,
            'nameLengths' => []
        ]
    ];

    foreach ($relatedFilms as $film) {
        // Статистика по типам связи
        $type = $film->relationType->value;
        if (!isset($analysis['relationTypeDistribution'][$type])) {
            $analysis['relationTypeDistribution'][$type] = 0;
        }
        $analysis['relationTypeDistribution'][$type]++;

        // Анализ названий
        if ($film->nameRu) {
            $analysis['nameAnalysis']['hasRussianName']++;
        }
        if ($film->nameEn) {
            $analysis['nameAnalysis']['hasEnglishName']++;
        }
        if ($film->nameOriginal) {
            $analysis['nameAnalysis']['hasOriginalName']++;
        }

        $displayName = $film->getDisplayName();
        $analysis['nameAnalysis']['nameLengths'][] = strlen($displayName);
    }

    // Средняя длина названия
    if (!empty($analysis['nameAnalysis']['nameLengths'])) {
        $analysis['nameAnalysis']['averageNameLength'] = round(array_sum($analysis['nameAnalysis']['nameLengths']) / count($analysis['nameAnalysis']['nameLengths']));
    }

    return $analysis;
}

// Использование
$relatedFilms = $filmService->getSimilarFilms(301);
$analysis = analyzeRelatedFilms($relatedFilms);

echo "=== Анализ связанных фильмов ===\n";
echo "Всего фильмов: {$analysis['totalFilms']}\n";

echo "\nРаспределение по типам связи:\n";
foreach ($analysis['relationTypeDistribution'] as $type => $count) {
    $percentage = round(($count / $analysis['totalFilms']) * 100, 1);
    echo "- {$type}: {$count} фильмов ({$percentage}%)\n";
}

echo "\nАнализ названий:\n";
echo "- С русским названием: {$analysis['nameAnalysis']['hasRussianName']}\n";
echo "- С английским названием: {$analysis['nameAnalysis']['hasEnglishName']}\n";
echo "- С оригинальным названием: {$analysis['nameAnalysis']['hasOriginalName']}\n";
echo "- Средняя длина названия: {$analysis['nameAnalysis']['averageNameLength']} символов\n";
```

## Связанные классы

- [`RelationType`](../enums/relation-type.md) - Типы связи между фильмами
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
