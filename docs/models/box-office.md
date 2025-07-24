# BoxOffice

Модель кассовых сборов из Kinopoisk API.

## Описание

`BoxOffice` представляет финансовую информацию о фильме: бюджет, сборы в различных странах и мировые сборы.

### Основные возможности

- Хранение финансовой информации в неизменяемом виде
- Создание объекта из массива данных API
- Форматирование сумм для отображения
- Определение типа данных (бюджет или сборы)

**API Endpoint:** `/api/v2.2/films/{id}/box_office`

## Свойства

### Основная информация

- `$type` (BoxOfficeType) - Тип кассовых сборов (бюджет, сборы и т.д.)
- `$amount` (int) - Сумма в минимальных единицах валюты
- `$currency` (string|null) - Код валюты (USD, EUR, RUB и т.д.)
- `$symbol` (string) - Символ валюты для отображения

## Конструктор

```php
public function __construct(
    public readonly BoxOfficeType $type,
    public readonly int $amount,
    public readonly ?string $currency,
    public readonly string $symbol,
)
```

### Пример создания

```php
$boxOffice = new BoxOffice(
    type: BoxOfficeType::BUDGET,
    amount: 100000000,
    currency: 'USD',
    symbol: '$'
);
```

## Методы

### fromArray()

Создает объект кассовых сборов из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных от API

#### Возвращаемое значение

- `self` - Новый объект кассовых сборов

#### Исключения

- `\ValueError` - Если тип кассовых сборов не поддерживается

#### Пример использования

```php
$apiData = [
    'type' => 'BUDGET',
    'amount' => 100000000,
    'currency' => 'USD',
    'symbol' => '$'
];

$boxOffice = BoxOffice::fromArray($apiData);
```

### getFormattedAmount()

Получает отформатированную сумму для отображения.

```php
public function getFormattedAmount(): string
```

#### Возвращаемое значение

- `string` - Отформатированная сумма

#### Описание

Возвращает сумму в удобном для чтения формате с символом валюты и разделителями тысяч.

#### Пример использования

```php
echo $boxOffice->getFormattedAmount(); // "$100,000,000"
```

### isBudget()

Проверяет, является ли тип бюджетом.

```php
public function isBudget(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это бюджет, `false` в противном случае

#### Описание

Определяет, относятся ли данные к бюджету фильма.

#### Пример использования

```php
if ($boxOffice->isBudget()) {
    echo "Бюджет фильма: {$boxOffice->getFormattedAmount()}";
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

Определяет, относятся ли данные к сборам фильма (в России, США или мировым сборам).

#### Пример использования

```php
if ($boxOffice->isRevenue()) {
    echo "Сборы: {$boxOffice->getFormattedAmount()}";
}
```

### toArray()

Преобразует объект кассовых сборов в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными кассовых сборов

#### Пример использования

```php
$boxOfficeArray = $boxOffice->toArray();
echo json_encode($boxOfficeArray); // JSON представление кассовых сборов
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\BoxOffice;
use NotKinopoisk\Enums\BoxOfficeType;

// Создание клиента
$client = new Client('your-api-key');

// Получение кассовых сборов фильма
$filmService = $client->films;
$boxOffice = $filmService->getBoxOffice(301); // Матрица

echo "=== Кассовые сборы фильма 'Матрица' ===\n";

// Группировка по типам
$budgets = [];
$revenues = [];

foreach ($boxOffice as $item) {
    if ($item->isBudget()) {
        $budgets[] = $item;
    } elseif ($item->isRevenue()) {
        $revenues[] = $item;
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
```

## Работа с кассовыми сборами

```php
// Функция для фильтрации по типу
function filterBoxOfficeByType(array $boxOffice, BoxOfficeType $type): array {
    return array_filter($boxOffice, fn($item) => $item->type === $type);
}

// Функция для получения бюджета и сборов
function getBudgetAndRevenue(array $boxOffice): array {
    $budget = null;
    $revenue = [];

    foreach ($boxOffice as $item) {
        if ($item->isBudget()) {
            $budget = $item;
        } elseif ($item->isRevenue()) {
            $revenue[] = $item;
        }
    }

    return ['budget' => $budget, 'revenue' => $revenue];
}

// Функция для расчета рентабельности
function calculateProfitability(array $boxOffice): ?float {
    $budgetAndRevenue = getBudgetAndRevenue($boxOffice);

    if (!$budgetAndRevenue['budget'] || empty($budgetAndRevenue['revenue'])) {
        return null;
    }

    $budget = $budgetAndRevenue['budget']->amount;
    $totalRevenue = array_sum(array_map(fn($item) => $item->amount, $budgetAndRevenue['revenue']));

    if ($budget > 0) {
        return round((($totalRevenue - $budget) / $budget) * 100, 2);
    }

    return null;
}

// Функция для получения статистики по валютам
function getCurrencyStats(array $boxOffice): array {
    $stats = [];

    foreach ($boxOffice as $item) {
        $currency = $item->currency ?? 'Unknown';
        if (!isset($stats[$currency])) {
            $stats[$currency] = [
                'count' => 0,
                'total' => 0,
                'symbol' => $item->symbol
            ];
        }
        $stats[$currency]['count']++;
        $stats[$currency]['total'] += $item->amount;
    }

    return $stats;
}

// Использование
$boxOffice = $filmService->getBoxOffice(301);

// Фильтрация по типу
$budgets = filterBoxOfficeByType($boxOffice, BoxOfficeType::BUDGET);
echo "Бюджетов: " . count($budgets) . "\n";

// Получение бюджета и сборов
$budgetAndRevenue = getBudgetAndRevenue($boxOffice);
if ($budgetAndRevenue['budget']) {
    echo "Бюджет: {$budgetAndRevenue['budget']->getFormattedAmount()}\n";
}
echo "Типов сборов: " . count($budgetAndRevenue['revenue']) . "\n";

// Расчет рентабельности
$profitability = calculateProfitability($boxOffice);
if ($profitability !== null) {
    echo "Рентабельность: {$profitability}%\n";
}

// Статистика по валютам
$currencyStats = getCurrencyStats($boxOffice);
echo "Статистика по валютам:\n";
foreach ($currencyStats as $currency => $data) {
    echo "- {$currency}: {$data['count']} записей, {$data['symbol']}" . number_format($data['total']) . "\n";
}
```

## Создание финансового отчета

```php
class FinancialReport {
    private array $boxOffice;

    public function __construct(array $boxOffice) {
        $this->boxOffice = $boxOffice;
    }

    public function getBudget(): ?BoxOffice {
        foreach ($this->boxOffice as $item) {
            if ($item->isBudget()) {
                return $item;
            }
        }
        return null;
    }

    public function getRevenue(): array {
        return array_filter($this->boxOffice, fn($item) => $item->isRevenue());
    }

    public function getTotalRevenue(): int {
        $revenue = $this->getRevenue();
        return array_sum(array_map(fn($item) => $item->amount, $revenue));
    }

    public function getProfitability(): ?float {
        $budget = $this->getBudget();
        if (!$budget) return null;

        $totalRevenue = $this->getTotalRevenue();
        if ($budget->amount > 0) {
            return round((($totalRevenue - $budget->amount) / $budget->amount) * 100, 2);
        }

        return null;
    }

    public function createDetailedReport(): string {
        $report = "=== ФИНАНСОВЫЙ ОТЧЕТ ===\n\n";

        // Бюджет
        $budget = $this->getBudget();
        if ($budget) {
            $report .= "💰 БЮДЖЕТ:\n";
            $report .= "Тип: {$budget->type->getDisplayName()}\n";
            $report .= "Сумма: {$budget->getFormattedAmount()}\n";
            $report .= "Валюта: {$budget->currency}\n\n";
        }

        // Сборы
        $revenue = $this->getRevenue();
        if (!empty($revenue)) {
            $report .= "💵 СБОРЫ:\n";
            foreach ($revenue as $item) {
                $report .= "• {$item->type->getDisplayName()}: {$item->getFormattedAmount()}\n";
            }
            $report .= "\n";

            $totalRevenue = $this->getTotalRevenue();
            $report .= "Общие сборы: " . number_format($totalRevenue) . "\n\n";
        }

        // Рентабельность
        $profitability = $this->getProfitability();
        if ($profitability !== null) {
            $report .= "📊 РЕНТАБЕЛЬНОСТЬ:\n";
            $report .= "Процент: {$profitability}%\n";

            if ($profitability > 0) {
                $report .= "Статус: Прибыльный проект ✅\n";
            } else {
                $report .= "Статус: Убыточный проект ❌\n";
            }
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".item { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".amount { font-size: 18px; font-weight: bold; color: #28a745; }\n";
        $html .= ".profitability { font-size: 16px; font-weight: bold; }\n";
        $html .= ".profit { color: #28a745; }\n";
        $html .= ".loss { color: #dc3545; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        // Бюджет
        $budget = $this->getBudget();
        if ($budget) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>💰 Бюджет</div>\n";
            $html .= "<div class='item'>\n";
            $html .= "<div><strong>Тип:</strong> {$budget->type->getDisplayName()}</div>\n";
            $html .= "<div class='amount'>{$budget->getFormattedAmount()}</div>\n";
            $html .= "<div><strong>Валюта:</strong> {$budget->currency}</div>\n";
            $html .= "</div>\n</div>\n";
        }

        // Сборы
        $revenue = $this->getRevenue();
        if (!empty($revenue)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>💵 Сборы</div>\n";

            foreach ($revenue as $item) {
                $html .= "<div class='item'>\n";
                $html .= "<div><strong>{$item->type->getDisplayName()}:</strong></div>\n";
                $html .= "<div class='amount'>{$item->getFormattedAmount()}</div>\n";
                $html .= "</div>\n";
            }

            $totalRevenue = $this->getTotalRevenue();
            $html .= "<div class='stats'>\n";
            $html .= "<div><strong>Общие сборы:</strong> " . number_format($totalRevenue) . "</div>\n";
            $html .= "</div>\n";
            $html .= "</div>\n";
        }

        // Рентабельность
        $profitability = $this->getProfitability();
        if ($profitability !== null) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📊 Рентабельность</div>\n";
            $html .= "<div class='item'>\n";
            $cssClass = $profitability > 0 ? 'profit' : 'loss';
            $status = $profitability > 0 ? 'Прибыльный проект ✅' : 'Убыточный проект ❌';
            $html .= "<div class='profitability {$cssClass}'>{$profitability}%</div>\n";
            $html .= "<div>{$status}</div>\n";
            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$boxOffice = $filmService->getBoxOffice(301);
$report = new FinancialReport($boxOffice);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Финансовый отчет фильма "Матрица"');
file_put_contents('financial_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в financial_report.html\n";
```

## Анализ финансовых показателей

```php
function analyzeFinancialPerformance(array $boxOffice): array {
    $analysis = [
        'totalItems' => count($boxOffice),
        'budgets' => 0,
        'revenues' => 0,
        'currencies' => [],
        'totalBudget' => 0,
        'totalRevenue' => 0,
        'profitability' => null,
        'currencyBreakdown' => []
    ];

    $budgetAmount = 0;
    $revenueAmount = 0;
    $currencyStats = [];

    foreach ($boxOffice as $item) {
        // Подсчет типов
        if ($item->isBudget()) {
            $analysis['budgets']++;
            $budgetAmount += $item->amount;
        } elseif ($item->isRevenue()) {
            $analysis['revenues']++;
            $revenueAmount += $item->amount;
        }

        // Статистика по валютам
        $currency = $item->currency ?? 'Unknown';
        if (!isset($currencyStats[$currency])) {
            $currencyStats[$currency] = [
                'count' => 0,
                'total' => 0,
                'symbol' => $item->symbol
            ];
        }
        $currencyStats[$currency]['count']++;
        $currencyStats[$currency]['total'] += $item->amount;
    }

    $analysis['totalBudget'] = $budgetAmount;
    $analysis['totalRevenue'] = $revenueAmount;
    $analysis['currencyBreakdown'] = $currencyStats;

    // Расчет рентабельности
    if ($budgetAmount > 0) {
        $analysis['profitability'] = round((($revenueAmount - $budgetAmount) / $budgetAmount) * 100, 2);
    }

    return $analysis;
}

// Использование
$boxOffice = $filmService->getBoxOffice(301);
$analysis = analyzeFinancialPerformance($boxOffice);

echo "=== Анализ финансовых показателей ===\n";
echo "Всего записей: {$analysis['totalItems']}\n";
echo "Бюджетов: {$analysis['budgets']}\n";
echo "Типов сборов: {$analysis['revenues']}\n";
echo "Общий бюджет: " . number_format($analysis['totalBudget']) . "\n";
echo "Общие сборы: " . number_format($analysis['totalRevenue']) . "\n";

if ($analysis['profitability'] !== null) {
    echo "Рентабельность: {$analysis['profitability']}%\n";
}

echo "\nСтатистика по валютам:\n";
foreach ($analysis['currencyBreakdown'] as $currency => $data) {
    echo "- {$currency}: {$data['count']} записей, {$data['symbol']}" . number_format($data['total']) . "\n";
}
```

## Связанные классы

- [`BoxOfficeType`](../enums/box-office-type.md) - Типы кассовых сборов
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](film.md) - Модель фильма
