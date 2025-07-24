# RelationType

Типы связи между фильмами в Kinopoisk API.

## Описание

`RelationType` определяет различные типы связей между фильмами в API Кинопоиска.

## Значения enum

### Типы связи

- `SIMILAR` - Похожий фильм (фильм, который похож по жанру, стилю или содержанию)
- `SEQUEL` - Сиквел (продолжение фильма, действие которого происходит после событий оригинала)
- `PREQUEL` - Приквел (фильм, действие которого происходит до событий оригинала)
- `REMAKE` - Ремейк (новая версия существующего фильма)
- `UNKNOWN` - Неизвестный тип связи (используется для случаев, когда тип связи не определен)

## Методы

### getDescription()

Получает описание типа связи.

```php
public function getDescription(): string
```

#### Возвращаемое значение

- `string` - Описание типа связи на русском языке

#### Пример использования

```php
$description = RelationType::SIMILAR->getDescription();
echo $description; // "Похожий фильм"
```

### isKnown()

Проверяет, является ли тип связи известным.

```php
public function isKnown(): bool
```

#### Возвращаемое значение

- `bool` - `true`, если тип связи известен

#### Пример использования

```php
if (RelationType::SIMILAR->isKnown()) {
    echo "Это известный тип связи";
}
```

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

echo "=== Работа с типами связи между фильмами ===\n";

// Вывод всех типов связи
echo "Доступные типы связи:\n";
foreach ([RelationType::SIMILAR, RelationType::SEQUEL, RelationType::PREQUEL, RelationType::REMAKE, RelationType::UNKNOWN] as $type) {
    $description = $type->getDescription();
    $isKnown = $type->isKnown() ? 'Да' : 'Нет';
    echo "• {$type->value}: {$description} (Известный: {$isKnown})\n";
}

echo "\n";

// Группировка связанных фильмов по типу связи
$groupedByType = [];
foreach ($relatedFilms as $film) {
    $type = $film->relationType;
    if (!isset($groupedByType[$type->value])) {
        $groupedByType[$type->value] = [];
    }
    $groupedByType[$type->value][] = $film;
}

// Вывод фильмов по типам связи
foreach ($groupedByType as $typeValue => $films) {
    $type = RelationType::from($typeValue);
    $description = $type->getDescription();
    echo "📋 {$description} (" . count($films) . " фильмов):\n";
    foreach ($films as $film) {
        echo "  • {$film->getDisplayName()}\n";
    }
    echo "\n";
}
```

## Работа с типами связи

```php
// Функция для получения всех известных типов связи
function getKnownRelationTypes(): array {
    return [
        RelationType::SIMILAR,
        RelationType::SEQUEL,
        RelationType::PREQUEL,
        RelationType::REMAKE
    ];
}

// Функция для получения отображаемого названия типа связи
function getRelationTypeDisplayName(RelationType $type): string {
    return $type->getDescription();
}

// Функция для получения иконки типа связи
function getRelationTypeIcon(RelationType $type): string {
    return match ($type) {
        RelationType::SIMILAR => '🔍',
        RelationType::SEQUEL => '➡️',
        RelationType::PREQUEL => '⬅️',
        RelationType::REMAKE => '🔄',
        RelationType::UNKNOWN => '❓'
    };
}

// Функция для получения цвета типа связи
function getRelationTypeColor(RelationType $type): string {
    return match ($type) {
        RelationType::SIMILAR => '#28a745', // Зеленый
        RelationType::SEQUEL => '#007bff',  // Синий
        RelationType::PREQUEL => '#ffc107', // Желтый
        RelationType::REMAKE => '#dc3545',  // Красный
        RelationType::UNKNOWN => '#6c757d'  // Серый
    };
}

// Функция для проверки, является ли тип связи последовательным
function isSequentialRelation(RelationType $type): bool {
    return in_array($type, [RelationType::SEQUEL, RelationType::PREQUEL]);
}

// Функция для проверки, является ли тип связи производным
function isDerivativeRelation(RelationType $type): bool {
    return in_array($type, [RelationType::REMAKE, RelationType::SEQUEL, RelationType::PREQUEL]);
}

// Использование
$knownTypes = getKnownRelationTypes();

echo "Известные типы связи:\n";
foreach ($knownTypes as $type) {
    $icon = getRelationTypeIcon($type);
    $name = getRelationTypeDisplayName($type);
    $color = getRelationTypeColor($type);
    $isSequential = isSequentialRelation($type) ? 'Да' : 'Нет';
    $isDerivative = isDerivativeRelation($type) ? 'Да' : 'Нет';

    echo "{$icon} {$name} (Цвет: {$color}, Последовательный: {$isSequential}, Производный: {$isDerivative})\n";
}
```

## Создание отчета по типам связи

```php
class RelationTypeReport {
    private array $relatedFilms;

    public function __construct(array $relatedFilms) {
        $this->relatedFilms = $relatedFilms;
    }

    public function getRelatedFilms(): array {
        return $this->relatedFilms;
    }

    public function getFilmsByRelationType(RelationType $type): array {
        return array_filter($this->relatedFilms, fn($film) => $film->relationType === $type);
    }

    public function getRelationTypeStats(): array {
        $stats = [];
        foreach ($this->relatedFilms as $film) {
            $type = $film->relationType->value;
            if (!isset($stats[$type])) {
                $stats[$type] = 0;
            }
            $stats[$type]++;
        }
        return $stats;
    }

    public function getKnownRelationTypes(): array {
        return getKnownRelationTypes();
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ТИПАМ СВЯЗИ ===\n\n";

        $stats = $this->getRelationTypeStats();
        $knownTypes = $this->getKnownRelationTypes();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего связанных фильмов: " . count($this->relatedFilms) . "\n";
        $report .= "Известных типов связи: " . count($knownTypes) . "\n\n";

        // Статистика по типам связи
        $report .= "📋 СТАТИСТИКА ПО ТИПАМ СВЯЗИ:\n";
        foreach ($stats as $typeValue => $count) {
            $type = RelationType::from($typeValue);
            $description = $type->getDescription();
            $icon = getRelationTypeIcon($type);
            $percentage = round(($count / count($this->relatedFilms)) * 100, 1);
            $report .= "{$icon} {$description}: {$count} фильмов ({$percentage}%)\n";
        }

        $report .= "\n";

        // Детали по каждому типу связи
        foreach ($knownTypes as $type) {
            $films = $this->getFilmsByRelationType($type);
            if (empty($films)) continue;

            $icon = getRelationTypeIcon($type);
            $description = $type->getDescription();

            $report .= "🎬 {$icon} {$description} (" . count($films) . " фильмов):\n";
            foreach ($films as $film) {
                $report .= "  • {$film->getDisplayName()}\n";
            }
            $report .= "\n";
        }

        // Анализ типов связи
        $report .= "📈 АНАЛИЗ ТИПОВ СВЯЗИ:\n";
        $sequentialCount = 0;
        $derivativeCount = 0;

        foreach ($this->relatedFilms as $film) {
            if (isSequentialRelation($film->relationType)) {
                $sequentialCount++;
            }
            if (isDerivativeRelation($film->relationType)) {
                $derivativeCount++;
            }
        }

        $report .= "• Последовательные связи (сиквелы/приквелы): {$sequentialCount}\n";
        $report .= "• Производные связи (ремейки/сиквелы/приквелы): {$derivativeCount}\n";
        $report .= "• Похожие фильмы: " . ($stats[RelationType::SIMILAR->value] ?? 0) . "\n";

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".relation-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".relation-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".relation-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".relation-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".similar { border-left: 5px solid #28a745; }\n";
        $html .= ".sequel { border-left: 5px solid #007bff; }\n";
        $html .= ".prequel { border-left: 5px solid #ffc107; }\n";
        $html .= ".remake { border-left: 5px solid #dc3545; }\n";
        $html .= ".unknown { border-left: 5px solid #6c757d; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getRelationTypeStats();
        $knownTypes = $this->getKnownRelationTypes();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего связанных фильмов:</strong> " . count($this->relatedFilms) . "</p>\n";
        $html .= "<p><strong>Известных типов связи:</strong> " . count($knownTypes) . "</p>\n";
        $html .= "</div>\n";

        // Статистика по типам связи
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📋 Статистика по типам связи</div>\n";
        foreach ($stats as $typeValue => $count) {
            $type = RelationType::from($typeValue);
            $description = $type->getDescription();
            $icon = getRelationTypeIcon($type);
            $percentage = round(($count / count($this->relatedFilms)) * 100, 1);
            $html .= "<p><strong>{$icon} {$description}:</strong> {$count} фильмов ({$percentage}%)</p>\n";
        }
        $html .= "</div>\n";

        // Фильмы по типам связи
        foreach ($knownTypes as $type) {
            $films = $this->getFilmsByRelationType($type);
            if (empty($films)) continue;

            $icon = getRelationTypeIcon($type);
            $description = $type->getDescription();
            $cssClass = strtolower($type->value);

            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>{$icon} {$description} (" . count($films) . " фильмов)</div>\n";
            $html .= "<div class='relation-grid'>\n";

            foreach ($films as $film) {
                $html .= "<div class='relation-item {$cssClass}'>\n";
                $html .= "<div class='relation-title'>{$film->getDisplayName()}</div>\n";
                $html .= "<div class='relation-meta'>ID: {$film->filmId}</div>\n";
                if ($film->nameOriginal && $film->nameOriginal !== $film->getDisplayName()) {
                    $html .= "<div class='relation-meta'>Оригинальное название: {$film->nameOriginal}</div>\n";
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
$report = new RelationTypeReport($relatedFilms);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по типам связи');
file_put_contents('relation_types_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в relation_types_report.html\n";
```

## Анализ типов связи

```php
function analyzeRelationTypes(array $relatedFilms): array {
    $analysis = [
        'totalFilms' => count($relatedFilms),
        'relationTypeDistribution' => [],
        'sequentialRelations' => 0,
        'derivativeRelations' => 0,
        'similarRelations' => 0,
        'unknownRelations' => 0
    ];

    foreach ($relatedFilms as $film) {
        $type = $film->relationType;

        // Статистика по типам связи
        $typeValue = $type->value;
        if (!isset($analysis['relationTypeDistribution'][$typeValue])) {
            $analysis['relationTypeDistribution'][$typeValue] = 0;
        }
        $analysis['relationTypeDistribution'][$typeValue]++;

        // Категоризация связей
        if (isSequentialRelation($type)) {
            $analysis['sequentialRelations']++;
        }
        if (isDerivativeRelation($type)) {
            $analysis['derivativeRelations']++;
        }
        if ($type === RelationType::SIMILAR) {
            $analysis['similarRelations']++;
        }
        if ($type === RelationType::UNKNOWN) {
            $analysis['unknownRelations']++;
        }
    }

    return $analysis;
}

// Использование
$relatedFilms = $filmService->getSimilarFilms(301);
$analysis = analyzeRelationTypes($relatedFilms);

echo "=== Анализ типов связи ===\n";
echo "Всего фильмов: {$analysis['totalFilms']}\n";

echo "\nРаспределение по типам связи:\n";
foreach ($analysis['relationTypeDistribution'] as $type => $count) {
    $percentage = round(($count / $analysis['totalFilms']) * 100, 1);
    echo "- {$type}: {$count} фильмов ({$percentage}%)\n";
}

echo "\nКатегоризация связей:\n";
echo "- Последовательные связи: {$analysis['sequentialRelations']}\n";
echo "- Производные связи: {$analysis['derivativeRelations']}\n";
echo "- Похожие фильмы: {$analysis['similarRelations']}\n";
echo "- Неизвестные связи: {$analysis['unknownRelations']}\n";
```

## Связанные классы

- [`RelatedFilm`](../models/related-film.md) - Модель связанного фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
