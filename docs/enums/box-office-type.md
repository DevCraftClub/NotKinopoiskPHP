# BoxOfficeType

Типы кассовых сборов в Kinopoisk API.

## Описание

`BoxOfficeType` определяет различные типы финансовых данных, связанных с фильмом: бюджет, сборы по странам и маркетинговые расходы.

## Значения enum

### Типы финансовых данных

- `BUDGET` - Бюджет фильма
- `RUS` - Сборы в России
- `USA` - Сборы в США
- `WORLD` - Мировые сборы
- `MARKETING` - Средства спущенные на маркетинг

## Методы

### isBudget()

Проверяет, является ли тип бюджетом.

```php
public function isBudget(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это бюджет, `false` в противном случае

#### Пример использования

```php
if (BoxOfficeType::BUDGET->isBudget()) {
    echo "Это бюджет фильма";
}
```

### isRevenue()

Проверяет, является ли тип сборами.

```php
public function isRevenue(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это сборы, `false` в противном случае

#### Описание

Возвращает `true` для типов `RUS`, `USA` и `WORLD`.

#### Пример использования

```php
if (BoxOfficeType::RUS->isRevenue()) {
    echo "Это сборы";
}
```

### getDisplayName()

Получает человекочитаемое название типа кассовых сборов.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа на русском языке

#### Пример использования

```php
echo BoxOfficeType::BUDGET->getDisplayName(); // "Бюджет"
echo BoxOfficeType::RUS->getDisplayName(); // "Сборы в России"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\BoxOfficeType;

// Создание клиента
$client = new Client('your-api-key');

// Получение кассовых сборов фильма
$filmService = $client->films;
$boxOffice = $filmService->getBoxOffice(301); // Матрица

echo "=== Анализ типов кассовых сборов ===\n";

// Группировка по типам
$budgets = [];
$revenues = [];
$marketing = [];

foreach ($boxOffice as $item) {
    if ($item->type->isBudget()) {
        $budgets[] = $item;
    } elseif ($item->type->isRevenue()) {
        $revenues[] = $item;
    } elseif ($item->type === BoxOfficeType::MARKETING) {
        $marketing[] = $item;
    }
}

// Вывод бюджетов
if (!empty($budgets)) {
    echo "\n💰 Бюджеты:\n";
    foreach ($budgets as $budget) {
        echo "• {$budget->type->getDisplayName()}: {$budget->getFormattedAmount()}\n";
    }
}

// Вывод сборов
if (!empty($revenues)) {
    echo "\n💵 Сборы:\n";
    foreach ($revenues as $revenue) {
        echo "• {$revenue->type->getDisplayName()}: {$revenue->getFormattedAmount()}\n";
    }
}

// Вывод маркетинговых расходов
if (!empty($marketing)) {
    echo "\n📢 Маркетинг:\n";
    foreach ($marketing as $item) {
        echo "• {$item->type->getDisplayName()}: {$item->getFormattedAmount()}\n";
    }
}
```

## Работа с типами кассовых сборов

```php
// Функция для получения всех типов
function getAllBoxOfficeTypes(): array {
    return [
        BoxOfficeType::BUDGET,
        BoxOfficeType::RUS,
        BoxOfficeType::USA,
        BoxOfficeType::WORLD,
        BoxOfficeType::MARKETING
    ];
}

// Функция для получения только типов сборов
function getRevenueTypes(): array {
    return array_filter(getAllBoxOfficeTypes(), fn($type) => $type->isRevenue());
}

// Функция для получения только бюджетных типов
function getBudgetTypes(): array {
    return array_filter(getAllBoxOfficeTypes(), fn($type) => $type->isBudget());
}

// Функция для группировки по категориям
function groupBoxOfficeByCategory(array $boxOffice): array {
    $categories = [
        'budgets' => [],
        'revenues' => [],
        'marketing' => []
    ];

    foreach ($boxOffice as $item) {
        if ($item->type->isBudget()) {
            $categories['budgets'][] = $item;
        } elseif ($item->type->isRevenue()) {
            $categories['revenues'][] = $item;
        } elseif ($item->type === BoxOfficeType::MARKETING) {
            $categories['marketing'][] = $item;
        }
    }

    return $categories;
}

// Функция для получения статистики по типам
function getBoxOfficeTypeStats(array $boxOffice): array {
    $stats = [];

    foreach (getAllBoxOfficeTypes() as $type) {
        $stats[$type->value] = [
            'type' => $type,
            'displayName' => $type->getDisplayName(),
            'count' => 0,
            'total' => 0,
            'isBudget' => $type->isBudget(),
            'isRevenue' => $type->isRevenue()
        ];
    }

    foreach ($boxOffice as $item) {
        $typeKey = $item->type->value;
        $stats[$typeKey]['count']++;
        $stats[$typeKey]['total'] += $item->amount;
    }

    return $stats;
}

// Использование
$boxOffice = $filmService->getBoxOffice(301);

// Получение типов
$allTypes = getAllBoxOfficeTypes();
$revenueTypes = getRevenueTypes();
$budgetTypes = getBudgetTypes();

echo "Всего типов: " . count($allTypes) . "\n";
echo "Типов сборов: " . count($revenueTypes) . "\n";
echo "Типов бюджетов: " . count($budgetTypes) . "\n";

// Группировка по категориям
$categories = groupBoxOfficeByCategory($boxOffice);
echo "Бюджетов: " . count($categories['budgets']) . "\n";
echo "Типов сборов: " . count($categories['revenues']) . "\n";
echo "Маркетинговых расходов: " . count($categories['marketing']) . "\n";

// Статистика по типам
$stats = getBoxOfficeTypeStats($boxOffice);
echo "\nСтатистика по типам:\n";
foreach ($stats as $typeKey => $data) {
    if ($data['count'] > 0) {
        echo "- {$data['displayName']}: {$data['count']} записей, " . number_format($data['total']) . "\n";
    }
}
```

## Создание отчета по типам

```php
class BoxOfficeTypeReport {
    private array $boxOffice;

    public function __construct(array $boxOffice) {
        $this->boxOffice = $boxOffice;
    }

    public function getTypeBreakdown(): array {
        return groupBoxOfficeByCategory($this->boxOffice);
    }

    public function getTypeStats(): array {
        return getBoxOfficeTypeStats($this->boxOffice);
    }

    public function getTotalByType(BoxOfficeType $type): int {
        $total = 0;
        foreach ($this->boxOffice as $item) {
            if ($item->type === $type) {
                $total += $item->amount;
            }
        }
        return $total;
    }

    public function getRevenueBreakdown(): array {
        $breakdown = [];

        foreach ($this->boxOffice as $item) {
            if ($item->type->isRevenue()) {
                $typeName = $item->type->getDisplayName();
                if (!isset($breakdown[$typeName])) {
                    $breakdown[$typeName] = 0;
                }
                $breakdown[$typeName] += $item->amount;
            }
        }

        arsort($breakdown);
        return $breakdown;
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ТИПАМ КАССОВЫХ СБОРОВ ===\n\n";

        $categories = $this->getTypeBreakdown();
        $stats = $this->getTypeStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего записей: " . count($this->boxOffice) . "\n";
        $report .= "Бюджетов: " . count($categories['budgets']) . "\n";
        $report .= "Типов сборов: " . count($categories['revenues']) . "\n";
        $report .= "Маркетинговых расходов: " . count($categories['marketing']) . "\n\n";

        // Детали по типам
        $report .= "📋 ДЕТАЛИ ПО ТИПАМ:\n";
        foreach ($stats as $typeKey => $data) {
            if ($data['count'] > 0) {
                $report .= "• {$data['displayName']}:\n";
                $report .= "  - Количество записей: {$data['count']}\n";
                $report .= "  - Общая сумма: " . number_format($data['total']) . "\n";
                $report .= "  - Тип: " . ($data['isBudget'] ? 'Бюджет' : ($data['isRevenue'] ? 'Сборы' : 'Маркетинг')) . "\n\n";
            }
        }

        // Разбивка сборов
        $revenueBreakdown = $this->getRevenueBreakdown();
        if (!empty($revenueBreakdown)) {
            $report .= "💵 РАЗБИВКА СБОРОВ:\n";
            foreach ($revenueBreakdown as $typeName => $total) {
                $report .= "• {$typeName}: " . number_format($total) . "\n";
            }
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1000px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".type-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".type-name { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".type-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; }\n";
        $html .= ".type-detail { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".budget { border-left: 5px solid #007bff; }\n";
        $html .= ".revenue { border-left: 5px solid #28a745; }\n";
        $html .= ".marketing { border-left: 5px solid #ffc107; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $categories = $this->getTypeBreakdown();
        $stats = $this->getTypeStats();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего записей:</strong> " . count($this->boxOffice) . "</p>\n";
        $html .= "<p><strong>Бюджетов:</strong> " . count($categories['budgets']) . "</p>\n";
        $html .= "<p><strong>Типов сборов:</strong> " . count($categories['revenues']) . "</p>\n";
        $html .= "<p><strong>Маркетинговых расходов:</strong> " . count($categories['marketing']) . "</p>\n";
        $html .= "</div>\n";

        // Детали по типам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Детали по типам</div>\n";

        foreach ($stats as $typeKey => $data) {
            if ($data['count'] > 0) {
                $cssClass = $data['isBudget'] ? 'budget' : ($data['isRevenue'] ? 'revenue' : 'marketing');
                $html .= "<div class='type-item {$cssClass}'>\n";
                $html .= "<div class='type-name'>{$data['displayName']}</div>\n";
                $html .= "<div class='type-details'>\n";
                $html .= "<div class='type-detail'>Количество записей: {$data['count']}</div>\n";
                $html .= "<div class='type-detail'>Общая сумма: " . number_format($data['total']) . "</div>\n";
                $html .= "<div class='type-detail'>Тип: " . ($data['isBudget'] ? 'Бюджет' : ($data['isRevenue'] ? 'Сборы' : 'Маркетинг')) . "</div>\n";
                $html .= "</div>\n</div>\n";
            }
        }

        $html .= "</div>\n";

        // Разбивка сборов
        $revenueBreakdown = $this->getRevenueBreakdown();
        if (!empty($revenueBreakdown)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>Разбивка сборов</div>\n";

            foreach ($revenueBreakdown as $typeName => $total) {
                $html .= "<div class='type-item revenue'>\n";
                $html .= "<div class='type-name'>{$typeName}</div>\n";
                $html .= "<div class='type-detail'>Общая сумма: " . number_format($total) . "</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$boxOffice = $filmService->getBoxOffice(301);
$report = new BoxOfficeTypeReport($boxOffice);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по типам кассовых сборов');
file_put_contents('box_office_types_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в box_office_types_report.html\n";
```

## Анализ эффективности типов

```php
function analyzeTypeEffectiveness(array $boxOffice): array {
    $analysis = [
        'totalRecords' => count($boxOffice),
        'typeDistribution' => [],
        'currencyDistribution' => [],
        'averageAmounts' => [],
        'totalAmounts' => []
    ];

    $typeStats = [];
    $currencyStats = [];

    foreach ($boxOffice as $item) {
        $typeKey = $item->type->value;
        $currency = $item->currency ?? 'Unknown';

        // Статистика по типам
        if (!isset($typeStats[$typeKey])) {
            $typeStats[$typeKey] = [
                'count' => 0,
                'total' => 0,
                'displayName' => $item->type->getDisplayName()
            ];
        }
        $typeStats[$typeKey]['count']++;
        $typeStats[$typeKey]['total'] += $item->amount;

        // Статистика по валютам
        if (!isset($currencyStats[$currency])) {
            $currencyStats[$currency] = [
                'count' => 0,
                'total' => 0
            ];
        }
        $currencyStats[$currency]['count']++;
        $currencyStats[$currency]['total'] += $item->amount;
    }

    // Вычисление средних значений
    foreach ($typeStats as $typeKey => $data) {
        $analysis['averageAmounts'][$typeKey] = round($data['total'] / $data['count']);
        $analysis['totalAmounts'][$typeKey] = $data['total'];
    }

    $analysis['typeDistribution'] = $typeStats;
    $analysis['currencyDistribution'] = $currencyStats;

    return $analysis;
}

// Использование
$boxOffice = $filmService->getBoxOffice(301);
$analysis = analyzeTypeEffectiveness($boxOffice);

echo "=== Анализ эффективности типов ===\n";
echo "Всего записей: {$analysis['totalRecords']}\n";

echo "\nРаспределение по типам:\n";
foreach ($analysis['typeDistribution'] as $typeKey => $data) {
    echo "- {$data['displayName']}: {$data['count']} записей\n";
    echo "  Средняя сумма: " . number_format($analysis['averageAmounts'][$typeKey]) . "\n";
    echo "  Общая сумма: " . number_format($analysis['totalAmounts'][$typeKey]) . "\n";
}

echo "\nРаспределение по валютам:\n";
foreach ($analysis['currencyDistribution'] as $currency => $data) {
    echo "- {$currency}: {$data['count']} записей, " . number_format($data['total']) . "\n";
}
```

## Связанные классы

- [`BoxOffice`](../models/box-office.md) - Модель кассовых сборов
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
