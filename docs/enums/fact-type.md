# FactType

Типы фактов в Kinopoisk API.

## Описание

`FactType` определяет различные типы фактов, которые могут быть связаны с фильмом: интересные факты, ошибки (блуперы) и т.д.

## Значения enum

### Типы фактов

- `FACT` - Интересный факт
- `BLOOPER` - Ошибка в фильме (блупер)

## Методы

### isBlooper()

Проверяет, является ли факт ошибкой в фильме.

```php
public function isBlooper(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это ошибка в фильме, `false` в противном случае

#### Пример использования

```php
if (FactType::BLOOPER->isBlooper()) {
    echo "Это ошибка в фильме";
}
```

### isFact()

Проверяет, является ли факт интересным фактом.

```php
public function isFact(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это интересный факт, `false` в противном случае

#### Пример использования

```php
if (FactType::FACT->isFact()) {
    echo "Это интересный факт";
}
```

### getDisplayName()

Получает человекочитаемое название типа факта.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа факта на русском языке

#### Пример использования

```php
echo FactType::FACT->getDisplayName(); // "Интересный факт"
echo FactType::BLOOPER->getDisplayName(); // "Ошибка в фильме"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\FactType;

// Создание клиента
$client = new Client('your-api-key');

// Получение фактов о фильме
$filmService = $client->films;
$facts = $filmService->getFacts(301); // ID фильма "Матрица"

echo "=== Работа с типами фактов ===\n";

// Вывод всех типов фактов
echo "Доступные типы фактов:\n";
foreach ([FactType::FACT, FactType::BLOOPER] as $type) {
    $icon = getFactTypeIcon($type);
    $displayName = $type->getDisplayName();
    echo "{$icon} {$displayName} ({$type->value})\n";
}

echo "\n";

// Группировка фактов по типам
$groupedFacts = [];
foreach ($facts as $fact) {
    $type = $fact->type;
    if (!isset($groupedFacts[$type->value])) {
        $groupedFacts[$type->value] = [];
    }
    $groupedFacts[$type->value][] = $fact;
}

// Вывод фактов по типам
foreach ($groupedFacts as $typeValue => $typeFacts) {
    $type = FactType::from($typeValue);
    $icon = getFactTypeIcon($type);
    $displayName = $type->getDisplayName();

    echo "{$icon} {$displayName} (" . count($typeFacts) . " фактов):\n";
    foreach ($typeFacts as $fact) {
        echo "  • {$fact->text}\n";
    }
    echo "\n";
}
```

## Работа с типами фактов

```php
// Функция для получения иконки типа факта
function getFactTypeIcon(FactType $type): string {
    return match ($type) {
        FactType::FACT => '💡',
        FactType::BLOOPER => '🎬'
    };
}

// Функция для получения цвета типа факта
function getFactTypeColor(FactType $type): string {
    return match ($type) {
        FactType::FACT => '#28a745',    // Зеленый
        FactType::BLOOPER => '#ffc107'  // Желтый
    };
}

// Функция для получения описания типа факта
function getFactTypeDescription(FactType $type): string {
    return match ($type) {
        FactType::FACT => 'Интересные факты о съемках, актерах, сюжете и других аспектах фильма',
        FactType::BLOOPER => 'Ошибки, несоответствия и ляпы, замеченные в фильме'
    };
}

// Функция для фильтрации фактов по типу
function filterFactsByType(array $facts, FactType $type): array {
    return array_filter($facts, fn($fact) => $fact->type === $type);
}

// Функция для получения статистики по типам фактов
function getFactTypeStats(array $facts): array {
    $stats = [
        'total' => count($facts),
        'facts' => 0,
        'bloopers' => 0
    ];

    foreach ($facts as $fact) {
        if ($fact->type->isFact()) {
            $stats['facts']++;
        } elseif ($fact->type->isBlooper()) {
            $stats['bloopers']++;
        }
    }

    return $stats;
}

// Функция для получения всех типов фактов
function getAllFactTypes(): array {
    return [FactType::FACT, FactType::BLOOPER];
}

// Использование
$allTypes = getAllFactTypes();

echo "Работа с типами фактов:\n";
foreach ($allTypes as $type) {
    $icon = getFactTypeIcon($type);
    $displayName = $type->getDisplayName();
    $color = getFactTypeColor($type);
    $description = getFactTypeDescription($type);

    echo "{$icon} {$displayName}\n";
    echo "   Цвет: {$color}\n";
    echo "   Описание: {$description}\n\n";
}
```

## Создание отчета по типам фактов

```php
class FactTypeReport {
    private array $facts;

    public function __construct(array $facts) {
        $this->facts = $facts;
    }

    public function getFacts(): array {
        return $this->facts;
    }

    public function getFactsByType(FactType $type): array {
        return filterFactsByType($this->facts, $type);
    }

    public function getFactTypeStats(): array {
        return getFactTypeStats($this->facts);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ТИПАМ ФАКТОВ ===\n\n";

        $stats = $this->getFactTypeStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего фактов: {$stats['total']}\n";
        $report .= "Интересных фактов: {$stats['facts']}\n";
        $report .= "Ошибок в фильме: {$stats['bloopers']}\n\n";

        // Процентное распределение
        if ($stats['total'] > 0) {
            $factsPercent = round(($stats['facts'] / $stats['total']) * 100, 1);
            $bloopersPercent = round(($stats['bloopers'] / $stats['total']) * 100, 1);

            $report .= "📈 ПРОЦЕНТНОЕ РАСПРЕДЕЛЕНИЕ:\n";
            $report .= "• Интересные факты: {$factsPercent}%\n";
            $report .= "• Ошибки в фильме: {$bloopersPercent}%\n\n";
        }

        // Интересные факты
        $interestingFacts = $this->getFactsByType(FactType::FACT);
        if (!empty($interestingFacts)) {
            $report .= "💡 ИНТЕРЕСНЫЕ ФАКТЫ (" . count($interestingFacts) . " фактов):\n";
            foreach ($interestingFacts as $index => $fact) {
                $report .= ($index + 1) . ". {$fact->text}\n";
            }
            $report .= "\n";
        }

        // Ошибки в фильме
        $bloopers = $this->getFactsByType(FactType::BLOOPER);
        if (!empty($bloopers)) {
            $report .= "🎬 ОШИБКИ В ФИЛЬМЕ (" . count($bloopers) . " ошибок):\n";
            foreach ($bloopers as $index => $fact) {
                $report .= ($index + 1) . ". {$fact->text}\n";
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
        $html .= ".fact-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".fact-text { font-size: 16px; line-height: 1.6; margin-bottom: 10px; }\n";
        $html .= ".fact-meta { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".fact-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 15px; }\n";
        $html .= ".fact { border-left: 5px solid #28a745; }\n";
        $html .= ".blooper { border-left: 5px solid #ffc107; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; transition: width 0.3s ease; }\n";
        $html .= ".fact-fill { background-color: #28a745; }\n";
        $html .= ".blooper-fill { background-color: #ffc107; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getFactTypeStats();
        $interestingFacts = $this->getFactsByType(FactType::FACT);
        $bloopers = $this->getFactsByType(FactType::BLOOPER);

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего фактов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Интересных фактов:</strong> {$stats['facts']}</p>\n";
        $html .= "<p><strong>Ошибок в фильме:</strong> {$stats['bloopers']}</p>\n";

        // Прогресс-бары
        if ($stats['total'] > 0) {
            $factsPercent = round(($stats['facts'] / $stats['total']) * 100, 1);
            $bloopersPercent = round(($stats['bloopers'] / $stats['total']) * 100, 1);

            $html .= "<h3>Распределение по типам</h3>\n";
            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill fact-fill' style='width: {$factsPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Интересные факты: {$factsPercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill blooper-fill' style='width: {$bloopersPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Ошибки в фильме: {$bloopersPercent}%</p>\n";
        }

        $html .= "</div>\n";

        // Интересные факты
        if (!empty($interestingFacts)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>💡 Интересные факты (" . count($interestingFacts) . " фактов)</div>\n";
            $html .= "<div class='fact-grid'>\n";

            foreach ($interestingFacts as $fact) {
                $html .= "<div class='fact-item fact'>\n";
                $html .= "<div class='fact-text'>{$fact->text}</div>\n";
                $html .= "<div class='fact-meta'>Тип: Интересный факт</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Ошибки в фильме
        if (!empty($bloopers)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🎬 Ошибки в фильме (" . count($bloopers) . " ошибок)</div>\n";
            $html .= "<div class='fact-grid'>\n";

            foreach ($bloopers as $fact) {
                $html .= "<div class='fact-item blooper'>\n";
                $html .= "<div class='fact-text'>{$fact->text}</div>\n";
                $html .= "<div class='fact-meta'>Тип: Ошибка в фильме</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$facts = $filmService->getFacts(301);
$report = new FactTypeReport($facts);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по типам фактов');
file_put_contents('fact_types_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в fact_types_report.html\n";
```

## Анализ типов фактов

```php
function analyzeFactTypes(array $facts): array {
    $analysis = [
        'totalFacts' => count($facts),
        'factTypeDistribution' => [
            'facts' => 0,
            'bloopers' => 0
        ],
        'factTypePercentages' => [
            'facts' => 0,
            'bloopers' => 0
        ],
        'textAnalysis' => [
            'averageLength' => 0,
            'longestFact' => '',
            'shortestFact' => '',
            'factLengths' => []
        ]
    ];

    foreach ($facts as $fact) {
        // Статистика по типам
        if ($fact->type->isFact()) {
            $analysis['factTypeDistribution']['facts']++;
        } elseif ($fact->type->isBlooper()) {
            $analysis['factTypeDistribution']['bloopers']++;
        }

        // Анализ текста
        $textLength = strlen($fact->text);
        $analysis['textAnalysis']['factLengths'][] = $textLength;

        if (empty($analysis['textAnalysis']['longestFact']) || $textLength > strlen($analysis['textAnalysis']['longestFact'])) {
            $analysis['textAnalysis']['longestFact'] = $fact->text;
        }

        if (empty($analysis['textAnalysis']['shortestFact']) || $textLength < strlen($analysis['textAnalysis']['shortestFact'])) {
            $analysis['textAnalysis']['shortestFact'] = $fact->text;
        }
    }

    // Вычисление процентов
    if ($analysis['totalFacts'] > 0) {
        $analysis['factTypePercentages']['facts'] = round(($analysis['factTypeDistribution']['facts'] / $analysis['totalFacts']) * 100, 1);
        $analysis['factTypePercentages']['bloopers'] = round(($analysis['factTypeDistribution']['bloopers'] / $analysis['totalFacts']) * 100, 1);
    }

    // Средняя длина текста
    if (!empty($analysis['textAnalysis']['factLengths'])) {
        $analysis['textAnalysis']['averageLength'] = round(array_sum($analysis['textAnalysis']['factLengths']) / count($analysis['textAnalysis']['factLengths']));
    }

    return $analysis;
}

// Использование
$facts = $filmService->getFacts(301);
$analysis = analyzeFactTypes($facts);

echo "=== Анализ типов фактов ===\n";
echo "Всего фактов: {$analysis['totalFacts']}\n";

echo "\nРаспределение по типам:\n";
echo "- Интересные факты: {$analysis['factTypeDistribution']['facts']} ({$analysis['factTypePercentages']['facts']}%)\n";
echo "- Ошибки в фильме: {$analysis['factTypeDistribution']['bloopers']} ({$analysis['factTypePercentages']['bloopers']}%)\n";

echo "\nАнализ текста:\n";
echo "- Средняя длина: {$analysis['textAnalysis']['averageLength']} символов\n";
echo "- Самый длинный факт: " . substr($analysis['textAnalysis']['longestFact'], 0, 100) . "...\n";
echo "- Самый короткий факт: {$analysis['textAnalysis']['shortestFact']}\n";
```

## Связанные классы

- [`Fact`](../models/fact.md) - Модель факта
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
