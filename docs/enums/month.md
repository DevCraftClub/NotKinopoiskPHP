# Month

Enum месяцев года для API запросов.

## Описание

`Month` представляет месяцы года, используемые в различных API запросах Kinopoisk API, например для получения премьер фильмов.

**API Endpoint:** `/api/v2.2/films/premieres`

## Значения enum

### Месяцы года

- `JANUARY` - Январь
- `FEBRUARY` - Февраль
- `MARCH` - Март
- `APRIL` - Апрель
- `MAY` - Май
- `JUNE` - Июнь
- `JULY` - Июль
- `AUGUST` - Август
- `SEPTEMBER` - Сентябрь
- `OCTOBER` - Октябрь
- `NOVEMBER` - Ноябрь
- `DECEMBER` - Декабрь

## Методы

### getDisplayName()

Получает отображаемое название месяца на русском языке.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название месяца

#### Пример использования

```php
echo Month::JANUARY->getDisplayName(); // "Январь"
echo Month::DECEMBER->getDisplayName(); // "Декабрь"
```

### getShortName()

Получает краткое название месяца (3 буквы).

```php
public function getShortName(): string
```

#### Возвращаемое значение

- `string` - Краткое название месяца

#### Пример использования

```php
echo Month::JANUARY->getShortName(); // "Янв"
echo Month::DECEMBER->getShortName(); // "Дек"
```

### getNumber()

Получает номер месяца (1-12).

```php
public function getNumber(): int
```

#### Возвращаемое значение

- `int` - Номер месяца (1-12)

#### Пример использования

```php
echo Month::JANUARY->getNumber(); // 1
echo Month::DECEMBER->getNumber(); // 12
```

### isWinter()

Проверяет, является ли месяц зимним.

```php
public function isWinter(): bool
```

#### Возвращаемое значение

- `bool` - `true` если зимний месяц, `false` в противном случае

#### Описание

К зимним месяцам относятся декабрь, январь, февраль.

#### Пример использования

```php
if (Month::DECEMBER->isWinter()) {
    echo "Зимний месяц";
}
```

### isSpring()

Проверяет, является ли месяц весенним.

```php
public function isSpring(): bool
```

#### Возвращаемое значение

- `bool` - `true` если весенний месяц, `false` в противном случае

#### Описание

К весенним месяцам относятся март, апрель, май.

#### Пример использования

```php
if (Month::MARCH->isSpring()) {
    echo "Весенний месяц";
}
```

### isSummer()

Проверяет, является ли месяц летним.

```php
public function isSummer(): bool
```

#### Возвращаемое значение

- `bool` - `true` если летний месяц, `false` в противном случае

#### Описание

К летним месяцам относятся июнь, июль, август.

#### Пример использования

```php
if (Month::JULY->isSummer()) {
    echo "Летний месяц";
}
```

### isAutumn()

Проверяет, является ли месяц осенним.

```php
public function isAutumn(): bool
```

#### Возвращаемое значение

- `bool` - `true` если осенний месяц, `false` в противном случае

#### Описание

К осенним месяцам относятся сентябрь, октябрь, ноябрь.

#### Пример использования

```php
if (Month::SEPTEMBER->isAutumn()) {
    echo "Осенний месяц";
}
```

### getSeason()

Получает сезон месяца.

```php
public function getSeason(): string
```

#### Возвращаемое значение

- `string` - Название сезона

#### Пример использования

```php
echo Month::JANUARY->getSeason(); // "Зима"
echo Month::JULY->getSeason(); // "Лето"
```

### isQuarterStart()

Проверяет, является ли месяц первым в квартале.

```php
public function isQuarterStart(): bool
```

#### Возвращаемое значение

- `bool` - `true` если первый месяц квартала, `false` в противном случае

#### Пример использования

```php
if (Month::JANUARY->isQuarterStart()) {
    echo "Начало квартала";
}
```

### getQuarter()

Получает номер квартала (1-4).

```php
public function getQuarter(): int
```

#### Возвращаемое значение

- `int` - Номер квартала (1-4)

#### Пример использования

```php
echo Month::JANUARY->getQuarter(); // 1
echo Month::OCTOBER->getQuarter(); // 4
```

### isYearEnd()

Проверяет, является ли месяц последним в году.

```php
public function isYearEnd(): bool
```

#### Возвращаемое значение

- `bool` - `true` если декабрь, `false` в противном случае

#### Пример использования

```php
if (Month::DECEMBER->isYearEnd()) {
    echo "Конец года";
}
```

### isYearStart()

Проверяет, является ли месяц первым в году.

```php
public function isYearStart(): bool
```

#### Возвращаемое значение

- `bool` - `true` если январь, `false` в противном случае

#### Пример использования

```php
if (Month::JANUARY->isYearStart()) {
    echo "Начало года";
}
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\Month;

// Создание клиента
$client = new Client('your-api-key');

// Получение премьер за январь 2024
$filmService = $client->films;
$premieres = $filmService->getPremieres(2024, Month::JANUARY);

echo "=== Премьеры за {$premieres->month->getDisplayName()} 2024 ===\n";

// Анализ премьер по месяцам
foreach ($premieres->items as $premiere) {
    $month = Month::from($premiere->month);
    echo "Фильм: {$premiere->nameRu}\n";
    echo "Месяц: {$month->getDisplayName()} ({$month->getShortName()})\n";
    echo "Сезон: {$month->getSeason()}\n";
    echo "Квартал: {$month->getQuarter()}\n\n";
}
```

## Работа с месяцами

```php
// Функция для получения всех месяцев
function getAllMonths(): array {
    return [
        Month::JANUARY, Month::FEBRUARY, Month::MARCH, Month::APRIL,
        Month::MAY, Month::JUNE, Month::JULY, Month::AUGUST,
        Month::SEPTEMBER, Month::OCTOBER, Month::NOVEMBER, Month::DECEMBER
    ];
}

// Функция для получения месяцев по сезону
function getMonthsBySeason(string $season): array {
    return array_filter(getAllMonths(), function($month) use ($season) {
        return $month->getSeason() === $season;
    });
}

// Функция для получения месяцев по кварталу
function getMonthsByQuarter(int $quarter): array {
    return array_filter(getAllMonths(), function($month) use ($quarter) {
        return $month->getQuarter() === $quarter;
    });
}

// Функция для получения следующего месяца
function getNextMonth(Month $currentMonth): Month {
    $months = getAllMonths();
    $currentIndex = array_search($currentMonth, $months);
    $nextIndex = ($currentIndex + 1) % 12;
    return $months[$nextIndex];
}

// Функция для получения предыдущего месяца
function getPreviousMonth(Month $currentMonth): Month {
    $months = getAllMonths();
    $currentIndex = array_search($currentMonth, $months);
    $previousIndex = ($currentIndex - 1 + 12) % 12;
    return $months[$previousIndex];
}

// Функция для получения месяцев в диапазоне
function getMonthsInRange(Month $startMonth, Month $endMonth): array {
    $months = getAllMonths();
    $startIndex = array_search($startMonth, $months);
    $endIndex = array_search($endMonth, $months);

    if ($startIndex <= $endIndex) {
        return array_slice($months, $startIndex, $endIndex - $startIndex + 1);
    } else {
        // Переход через год
        $firstPart = array_slice($months, $startIndex);
        $secondPart = array_slice($months, 0, $endIndex + 1);
        return array_merge($firstPart, $secondPart);
    }
}

// Использование
$allMonths = getAllMonths();
echo "Всего месяцев: " . count($allMonths) . "\n";

// Месяцы по сезонам
$winterMonths = getMonthsBySeason('Зима');
echo "Зимние месяцы: " . implode(', ', array_map(fn($m) => $m->getDisplayName(), $winterMonths)) . "\n";

$summerMonths = getMonthsBySeason('Лето');
echo "Летние месяцы: " . implode(', ', array_map(fn($m) => $m->getDisplayName(), $summerMonths)) . "\n";

// Месяцы по кварталам
$firstQuarter = getMonthsByQuarter(1);
echo "Первый квартал: " . implode(', ', array_map(fn($m) => $m->getDisplayName(), $firstQuarter)) . "\n";

// Следующий и предыдущий месяц
$currentMonth = Month::JUNE;
$nextMonth = getNextMonth($currentMonth);
$previousMonth = getPreviousMonth($currentMonth);

echo "Текущий: {$currentMonth->getDisplayName()}\n";
echo "Следующий: {$nextMonth->getDisplayName()}\n";
echo "Предыдущий: {$previousMonth->getDisplayName()}\n";

// Диапазон месяцев
$rangeMonths = getMonthsInRange(Month::NOVEMBER, Month::FEBRUARY);
echo "Диапазон (ноябрь-февраль): " . implode(', ', array_map(fn($m) => $m->getDisplayName(), $rangeMonths)) . "\n";
```

## Создание календаря премьер

```php
class PremieresCalendar {
    private array $premieres;

    public function __construct(array $premieres) {
        $this->premieres = $premieres;
    }

    public function getPremieresByMonth(Month $month): array {
        return array_filter($this->premieres, function($premiere) use ($month) {
            return Month::from($premiere->month) === $month;
        });
    }

    public function getPremieresBySeason(string $season): array {
        $seasonMonths = getMonthsBySeason($season);
        $seasonPremieres = [];

        foreach ($seasonMonths as $month) {
            $monthPremieres = $this->getPremieresByMonth($month);
            $seasonPremieres = array_merge($seasonPremieres, $monthPremieres);
        }

        return $seasonPremieres;
    }

    public function getPremieresByQuarter(int $quarter): array {
        $quarterMonths = getMonthsByQuarter($quarter);
        $quarterPremieres = [];

        foreach ($quarterMonths as $month) {
            $monthPremieres = $this->getPremieresByMonth($month);
            $quarterPremieres = array_merge($quarterPremieres, $monthPremieres);
        }

        return $quarterPremieres;
    }

    public function getMonthlyStatistics(): array {
        $stats = [];

        foreach (getAllMonths() as $month) {
            $monthPremieres = $this->getPremieresByMonth($month);
            $stats[$month->value] = [
                'month' => $month,
                'count' => count($monthPremieres),
                'season' => $month->getSeason(),
                'quarter' => $month->getQuarter()
            ];
        }

        return $stats;
    }

    public function createDetailedReport(): string {
        $report = "=== КАЛЕНДАРЬ ПРЕМЬЕР ===\n\n";

        $stats = $this->getMonthlyStatistics();

        // Общая статистика
        $totalPremieres = array_sum(array_column($stats, 'count'));
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего премьер: {$totalPremieres}\n";
        $report .= "Среднее количество в месяц: " . round($totalPremieres / 12, 1) . "\n\n";

        // Статистика по сезонам
        $seasonStats = [];
        foreach (['Зима', 'Весна', 'Лето', 'Осень'] as $season) {
            $seasonPremieres = $this->getPremieresBySeason($season);
            $seasonStats[$season] = count($seasonPremieres);
        }

        $report .= "🌍 СТАТИСТИКА ПО СЕЗОНАМ:\n";
        foreach ($seasonStats as $season => $count) {
            $percentage = round(($count / $totalPremieres) * 100, 1);
            $report .= "• {$season}: {$count} премьер ({$percentage}%)\n";
        }

        $report .= "\n";

        // Статистика по кварталам
        $report .= "📅 СТАТИСТИКА ПО КВАРТАЛАМ:\n";
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $quarterPremieres = $this->getPremieresByQuarter($quarter);
            $percentage = round((count($quarterPremieres) / $totalPremieres) * 100, 1);
            $report .= "• Квартал {$quarter}: " . count($quarterPremieres) . " премьер ({$percentage}%)\n";
        }

        $report .= "\n";

        // Детали по месяцам
        $report .= "📋 ДЕТАЛИ ПО МЕСЯЦАМ:\n";
        foreach ($stats as $monthKey => $monthStats) {
            $month = $monthStats['month'];
            $report .= "\n{$month->getDisplayName()}:\n";
            $report .= "  Количество премьер: {$monthStats['count']}\n";
            $report .= "  Сезон: {$monthStats['season']}\n";
            $report .= "  Квартал: {$monthStats['quarter']}\n";

            if ($month->isQuarterStart()) {
                $report .= "  🎯 Начало квартала\n";
            }

            if ($month->isYearStart()) {
                $report .= "  🎆 Начало года\n";
            }

            if ($month->isYearEnd()) {
                $report .= "  🎄 Конец года\n";
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
        $html .= ".month-item { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }\n";
        $html .= ".month-header { font-weight: bold; font-size: 18px; margin-bottom: 10px; }\n";
        $html .= ".month-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; }\n";
        $html .= ".month-detail { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".season-winter { border-left: 5px solid #007bff; }\n";
        $html .= ".season-spring { border-left: 5px solid #28a745; }\n";
        $html .= ".season-summer { border-left: 5px solid #ffc107; }\n";
        $html .= ".season-autumn { border-left: 5px solid #dc3545; }\n";
        $html .= ".highlight { background-color: #fff3cd; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getMonthlyStatistics();
        $totalPremieres = array_sum(array_column($stats, 'count'));

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего премьер:</strong> {$totalPremieres}</p>\n";
        $html .= "<p><strong>Среднее количество в месяц:</strong> " . round($totalPremieres / 12, 1) . "</p>\n";
        $html .= "</div>\n";

        // Детали по месяцам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Детали по месяцам</div>\n";

        foreach ($stats as $monthKey => $monthStats) {
            $month = $monthStats['month'];
            $season = strtolower($monthStats['season']);
            $cssClass = "season-{$season}";

            if ($month->isQuarterStart() || $month->isYearStart() || $month->isYearEnd()) {
                $cssClass .= " highlight";
            }

            $html .= "<div class='month-item {$cssClass}'>\n";
            $html .= "<div class='month-header'>{$month->getDisplayName()}</div>\n";
            $html .= "<div class='month-details'>\n";
            $html .= "<div class='month-detail'>Количество премьер: {$monthStats['count']}</div>\n";
            $html .= "<div class='month-detail'>Сезон: {$monthStats['season']}</div>\n";
            $html .= "<div class='month-detail'>Квартал: {$monthStats['quarter']}</div>\n";

            if ($month->isQuarterStart()) {
                $html .= "<div class='month-detail'>🎯 Начало квартала</div>\n";
            }

            if ($month->isYearStart()) {
                $html .= "<div class='month-detail'>🎆 Начало года</div>\n";
            }

            if ($month->isYearEnd()) {
                $html .= "<div class='month-detail'>🎄 Конец года</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$premieres = $filmService->getPremieres(2024, Month::JANUARY);
$calendar = new PremieresCalendar($premieres->items);

// Создание текстового отчета
$textReport = $calendar->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $calendar->createHtmlReport('Календарь премьер 2024');
file_put_contents('premieres_calendar.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в premieres_calendar.html\n";
```

## Анализ сезонности

```php
function analyzeSeasonality(array $premieres): array {
    $analysis = [
        'totalPremieres' => count($premieres),
        'seasonalDistribution' => [],
        'quarterlyDistribution' => [],
        'monthlyDistribution' => [],
        'peakMonth' => null,
        'lowestMonth' => null,
        'seasonalTrends' => []
    ];

    $monthlyCounts = [];
    $seasonalCounts = [];
    $quarterlyCounts = [];

    foreach ($premieres as $premiere) {
        $month = Month::from($premiere->month);
        $season = $month->getSeason();
        $quarter = $month->getQuarter();

        // Подсчет по месяцам
        $monthKey = $month->value;
        if (!isset($monthlyCounts[$monthKey])) {
            $monthlyCounts[$monthKey] = 0;
        }
        $monthlyCounts[$monthKey]++;

        // Подсчет по сезонам
        if (!isset($seasonalCounts[$season])) {
            $seasonalCounts[$season] = 0;
        }
        $seasonalCounts[$season]++;

        // Подсчет по кварталам
        if (!isset($quarterlyCounts[$quarter])) {
            $quarterlyCounts[$quarter] = 0;
        }
        $quarterlyCounts[$quarter]++;
    }

    // Анализ распределения
    $analysis['monthlyDistribution'] = $monthlyCounts;
    $analysis['seasonalDistribution'] = $seasonalCounts;
    $analysis['quarterlyDistribution'] = $quarterlyCounts;

    // Пиковый и минимальный месяцы
    if (!empty($monthlyCounts)) {
        $maxCount = max($monthlyCounts);
        $minCount = min($monthlyCounts);

        foreach ($monthlyCounts as $monthKey => $count) {
            if ($count === $maxCount) {
                $analysis['peakMonth'] = Month::from($monthKey);
            }
            if ($count === $minCount) {
                $analysis['lowestMonth'] = Month::from($monthKey);
            }
        }
    }

    // Сезонные тренды
    $analysis['seasonalTrends'] = [
        'winter' => $seasonalCounts['Зима'] ?? 0,
        'spring' => $seasonalCounts['Весна'] ?? 0,
        'summer' => $seasonalCounts['Лето'] ?? 0,
        'autumn' => $seasonalCounts['Осень'] ?? 0
    ];

    return $analysis;
}

// Использование
$premieres = $filmService->getPremieres(2024, Month::JANUARY);
$analysis = analyzeSeasonality($premieres->items);

echo "=== Анализ сезонности премьер ===\n";
echo "Всего премьер: {$analysis['totalPremieres']}\n";

echo "\nРаспределение по сезонам:\n";
foreach ($analysis['seasonalDistribution'] as $season => $count) {
    $percentage = round(($count / $analysis['totalPremieres']) * 100, 1);
    echo "- {$season}: {$count} премьер ({$percentage}%)\n";
}

echo "\nРаспределение по кварталам:\n";
for ($quarter = 1; $quarter <= 4; $quarter++) {
    $count = $analysis['quarterlyDistribution'][$quarter] ?? 0;
    $percentage = round(($count / $analysis['totalPremieres']) * 100, 1);
    echo "- Квартал {$quarter}: {$count} премьер ({$percentage}%)\n";
}

if ($analysis['peakMonth']) {
    echo "\nПиковый месяц: {$analysis['peakMonth']->getDisplayName()} (" . $analysis['monthlyDistribution'][$analysis['peakMonth']->value] . " премьер)\n";
}

if ($analysis['lowestMonth']) {
    echo "Минимальный месяц: {$analysis['lowestMonth']->getDisplayName()} (" . $analysis['monthlyDistribution'][$analysis['lowestMonth']->value] . " премьер)\n";
}
```

## Связанные классы

- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
