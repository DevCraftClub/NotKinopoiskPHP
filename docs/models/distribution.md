# Distribution

Модель данных о прокате фильма из Kinopoisk API.

## Описание

`Distribution` представляет информацию о прокате фильма в различных странах, включая тип проката, дату, страну и компании-дистрибьюторы.

### Основные возможности

- Хранение информации о прокате в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к метаданным проката и дистрибьюторам

**API Endpoint:** `/api/v2.2/films/{id}/distributions`

## Свойства

### Основная информация

- `$type` (DistributionType) - Тип проката
- `$subType` (string|null) - Подтип проката
- `$date` (string|null) - Дата проката
- `$reRelease` (bool|null) - Флаг повторного проката
- `$country` (Country|null) - Страна проката
- `$companies` (array) - Массив компаний-дистрибьюторов

## Конструктор

```php
public function __construct(
    public readonly DistributionType $type,
    public readonly ?string $subType,
    public readonly ?string $date,
    public readonly ?bool $reRelease,
    public readonly ?Country $country,
    public readonly array $companies,
)
```

### Пример создания

```php
$distribution = new Distribution(
    type: DistributionType::CINEMA,
    subType: 'WIDE',
    date: '2023-01-15',
    reRelease: false,
    country: $country,
    companies: ['Компания 1', 'Компания 2']
);
```

## Методы

### fromArray()

Создает экземпляр проката из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных проката от API

#### Возвращаемое значение

- `self` - Новый экземпляр проката

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Описание

Статический метод для удобного создания объекта Distribution из данных, полученных от Kinopoisk API. Автоматически обрабатывает nullable поля и создает объект Country при необходимости.

#### Пример использования

```php
$apiData = [
    'type' => 'CINEMA',
    'subType' => 'WIDE',
    'date' => '2023-01-15',
    'reRelease' => false,
    'country' => ['country' => 'США'],
    'companies' => ['Компания 1', 'Компания 2']
];

$distribution = Distribution::fromArray($apiData);
```

### toArray()

Преобразует объект проката в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными проката

#### Пример использования

```php
$distributionArray = $distribution->toArray();
echo json_encode($distributionArray); // JSON представление проката
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Distribution;
use NotKinopoisk\Enums\DistributionType;

// Создание клиента
$client = new Client('your-api-key');

// Получение данных о прокате фильма
$filmService = $client->films;
$distributions = $filmService->getDistributions(301); // Матрица

echo "=== Данные о прокате фильма 'Матрица' ===\n";

// Обработка всех записей о прокате
foreach ($distributions as $distribution) {
    echo "\n🎬 {$distribution->type->getDisplayName()}:\n";

    if ($distribution->subType) {
        echo "  Подтип: {$distribution->subType}\n";
    }

    if ($distribution->date) {
        echo "  Дата: {$distribution->date}\n";
    }

    if ($distribution->country) {
        echo "  Страна: {$distribution->country->country}\n";
    }

    if ($distribution->reRelease !== null) {
        echo "  Повторный прокат: " . ($distribution->reRelease ? 'Да' : 'Нет') . "\n";
    }

    if (!empty($distribution->companies)) {
        echo "  Дистрибьюторы: " . implode(', ', $distribution->companies) . "\n";
    }
}
```

## Работа с данными о прокате

```php
// Функция для фильтрации по типу проката
function filterDistributionsByType(array $distributions, DistributionType $type): array {
    return array_filter($distributions, fn($dist) => $dist->type === $type);
}

// Функция для фильтрации по стране
function filterDistributionsByCountry(array $distributions, string $countryName): array {
    return array_filter($distributions, function($dist) use ($countryName) {
        return $dist->country && strcasecmp($dist->country->country, $countryName) === 0;
    });
}

// Функция для получения прокатов с датами
function getDistributionsWithDates(array $distributions): array {
    return array_filter($distributions, fn($dist) => $dist->date !== null);
}

// Функция для получения повторных прокатов
function getReReleases(array $distributions): array {
    return array_filter($distributions, fn($dist) => $dist->reRelease === true);
}

// Функция для получения статистики по прокату
function getDistributionStatistics(array $distributions): array {
    $stats = [
        'totalDistributions' => count($distributions),
        'types' => [],
        'countries' => [],
        'companies' => [],
        'withDates' => 0,
        'reReleases' => 0,
        'dateRange' => [
            'earliest' => null,
            'latest' => null
        ]
    ];

    $dates = [];

    foreach ($distributions as $distribution) {
        // Статистика по типам
        $typeKey = $distribution->type->value;
        if (!isset($stats['types'][$typeKey])) {
            $stats['types'][$typeKey] = 0;
        }
        $stats['types'][$typeKey]++;

        // Статистика по странам
        if ($distribution->country) {
            $countryName = $distribution->country->country;
            if (!isset($stats['countries'][$countryName])) {
                $stats['countries'][$countryName] = 0;
            }
            $stats['countries'][$countryName]++;
        }

        // Статистика по компаниям
        foreach ($distribution->companies as $company) {
            if (!isset($stats['companies'][$company])) {
                $stats['companies'][$company] = 0;
            }
            $stats['companies'][$company]++;
        }

        // Подсчет с датами и повторных прокатов
        if ($distribution->date) {
            $stats['withDates']++;
            $dates[] = $distribution->date;
        }

        if ($distribution->reRelease === true) {
            $stats['reReleases']++;
        }
    }

    // Анализ диапазона дат
    if (!empty($dates)) {
        sort($dates);
        $stats['dateRange']['earliest'] = $dates[0];
        $stats['dateRange']['latest'] = end($dates);
    }

    return $stats;
}

// Использование
$distributions = $filmService->getDistributions(301);

// Фильтрация по типу
$cinemaDistributions = filterDistributionsByType($distributions, DistributionType::CINEMA);
echo "Кинопрокатов: " . count($cinemaDistributions) . "\n";

// Фильтрация по стране
$usDistributions = filterDistributionsByCountry($distributions, 'США');
echo "Прокатов в США: " . count($usDistributions) . "\n";

// Прокаты с датами
$distributionsWithDates = getDistributionsWithDates($distributions);
echo "Прокатов с датами: " . count($distributionsWithDates) . "\n";

// Повторные прокаты
$reReleases = getReReleases($distributions);
echo "Повторных прокатов: " . count($reReleases) . "\n";

// Статистика
$stats = getDistributionStatistics($distributions);
echo "Всего записей о прокате: {$stats['totalDistributions']}\n";
echo "Уникальных стран: " . count($stats['countries']) . "\n";
echo "Уникальных компаний: " . count($stats['companies']) . "\n";
```

## Создание отчета о прокате

```php
class DistributionReport {
    private array $distributions;

    public function __construct(array $distributions) {
        $this->distributions = $distributions;
    }

    public function getDistributionsByType(DistributionType $type): array {
        return filterDistributionsByType($this->distributions, $type);
    }

    public function getDistributionsByCountry(string $countryName): array {
        return filterDistributionsByCountry($this->distributions, $countryName);
    }

    public function getDistributionsWithDates(): array {
        return getDistributionsWithDates($this->distributions);
    }

    public function getReReleases(): array {
        return getReReleases($this->distributions);
    }

    public function getStatistics(): array {
        return getDistributionStatistics($this->distributions);
    }

    public function getTopCountries(int $limit = 5): array {
        $stats = $this->getStatistics();
        arsort($stats['countries']);
        return array_slice($stats['countries'], 0, $limit, true);
    }

    public function getTopCompanies(int $limit = 5): array {
        $stats = $this->getStatistics();
        arsort($stats['companies']);
        return array_slice($stats['companies'], 0, $limit, true);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ О ПРОКАТЕ ===\n\n";

        $stats = $this->getStatistics();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего записей о прокате: {$stats['totalDistributions']}\n";
        $report .= "Уникальных типов проката: " . count($stats['types']) . "\n";
        $report .= "Уникальных стран: " . count($stats['countries']) . "\n";
        $report .= "Уникальных компаний: " . count($stats['companies']) . "\n";
        $report .= "Прокатов с датами: {$stats['withDates']}\n";
        $report .= "Повторных прокатов: {$stats['reReleases']}\n";

        if ($stats['dateRange']['earliest']) {
            $report .= "Диапазон дат: {$stats['dateRange']['earliest']} - {$stats['dateRange']['latest']}\n";
        }

        $report .= "\n";

        // Статистика по типам
        $report .= "🎬 СТАТИСТИКА ПО ТИПАМ ПРОКАТА:\n";
        foreach ($stats['types'] as $typeKey => $count) {
            $type = DistributionType::from($typeKey);
            $report .= "• {$type->getDisplayName()}: {$count} записей\n";
        }

        $report .= "\n";

        // Топ стран
        $topCountries = $this->getTopCountries();
        $report .= "🌍 ТОП СТРАН ПО ПРОКАТУ:\n";
        foreach ($topCountries as $country => $count) {
            $report .= "• {$country}: {$count} прокатов\n";
        }

        $report .= "\n";

        // Топ компаний
        $topCompanies = $this->getTopCompanies();
        $report .= "🏢 ТОП КОМПАНИЙ-ДИСТРИБЬЮТОРОВ:\n";
        foreach ($topCompanies as $company => $count) {
            $report .= "• {$company}: {$count} прокатов\n";
        }

        $report .= "\n";

        // Детали по прокатам
        $report .= "📋 ДЕТАЛИ ПО ПРОКАТАМ:\n";
        foreach ($this->distributions as $index => $distribution) {
            $report .= "\n" . ($index + 1) . ". {$distribution->type->getDisplayName()}:\n";

            if ($distribution->subType) {
                $report .= "   Подтип: {$distribution->subType}\n";
            }

            if ($distribution->date) {
                $report .= "   Дата: {$distribution->date}\n";
            }

            if ($distribution->country) {
                $report .= "   Страна: {$distribution->country->country}\n";
            }

            if ($distribution->reRelease !== null) {
                $report .= "   Повторный прокат: " . ($distribution->reRelease ? 'Да' : 'Нет') . "\n";
            }

            if (!empty($distribution->companies)) {
                $report .= "   Дистрибьюторы: " . implode(', ', $distribution->companies) . "\n";
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
        $html .= ".distribution-item { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }\n";
        $html .= ".distribution-header { font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #007bff; }\n";
        $html .= ".distribution-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }\n";
        $html .= ".distribution-detail { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".top-item { margin-bottom: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".re-release { background-color: #fff3cd; border-left: 5px solid #ffc107; }\n";
        $html .= ".cinema { border-left: 5px solid #28a745; }\n";
        $html .= ".digital { border-left: 5px solid #17a2b8; }\n";
        $html .= ".physical { border-left: 5px solid #6c757d; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getStatistics();
        $topCountries = $this->getTopCountries();
        $topCompanies = $this->getTopCompanies();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего записей о прокате:</strong> {$stats['totalDistributions']}</p>\n";
        $html .= "<p><strong>Уникальных типов проката:</strong> " . count($stats['types']) . "</p>\n";
        $html .= "<p><strong>Уникальных стран:</strong> " . count($stats['countries']) . "</p>\n";
        $html .= "<p><strong>Уникальных компаний:</strong> " . count($stats['companies']) . "</p>\n";
        $html .= "<p><strong>Прокатов с датами:</strong> {$stats['withDates']}</p>\n";
        $html .= "<p><strong>Повторных прокатов:</strong> {$stats['reReleases']}</p>\n";

        if ($stats['dateRange']['earliest']) {
            $html .= "<p><strong>Диапазон дат:</strong> {$stats['dateRange']['earliest']} - {$stats['dateRange']['latest']}</p>\n";
        }

        $html .= "</div>\n";

        // Топ стран
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Топ стран по прокату</div>\n";

        foreach ($topCountries as $country => $count) {
            $html .= "<div class='top-item'>\n";
            $html .= "<div><strong>{$country}:</strong> {$count} прокатов</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        // Топ компаний
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Топ компаний-дистрибьюторов</div>\n";

        foreach ($topCompanies as $company => $count) {
            $html .= "<div class='top-item'>\n";
            $html .= "<div><strong>{$company}:</strong> {$count} прокатов</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        // Детали по прокатам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Детали по прокатам</div>\n";

        foreach ($this->distributions as $index => $distribution) {
            $cssClass = '';
            if ($distribution->reRelease === true) {
                $cssClass = 're-release';
            }

            switch ($distribution->type) {
                case DistributionType::CINEMA:
                    $cssClass .= ' cinema';
                    break;
                case DistributionType::DIGITAL:
                    $cssClass .= ' digital';
                    break;
                case DistributionType::PHYSICAL:
                    $cssClass .= ' physical';
                    break;
            }

            $html .= "<div class='distribution-item {$cssClass}'>\n";
            $html .= "<div class='distribution-header'>{$distribution->type->getDisplayName()}</div>\n";
            $html .= "<div class='distribution-details'>\n";

            if ($distribution->subType) {
                $html .= "<div class='distribution-detail'><strong>Подтип:</strong> {$distribution->subType}</div>\n";
            }

            if ($distribution->date) {
                $html .= "<div class='distribution-detail'><strong>Дата:</strong> {$distribution->date}</div>\n";
            }

            if ($distribution->country) {
                $html .= "<div class='distribution-detail'><strong>Страна:</strong> {$distribution->country->country}</div>\n";
            }

            if ($distribution->reRelease !== null) {
                $html .= "<div class='distribution-detail'><strong>Повторный прокат:</strong> " . ($distribution->reRelease ? 'Да' : 'Нет') . "</div>\n";
            }

            if (!empty($distribution->companies)) {
                $html .= "<div class='distribution-detail'><strong>Дистрибьюторы:</strong> " . implode(', ', $distribution->companies) . "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$distributions = $filmService->getDistributions(301);
$report = new DistributionReport($distributions);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет о прокате фильма');
file_put_contents('distribution_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в distribution_report.html\n";
```

## Анализ проката

```php
function analyzeDistribution(array $distributions): array {
    $analysis = [
        'totalDistributions' => count($distributions),
        'typeDistribution' => [],
        'countryDistribution' => [],
        'companyDistribution' => [],
        'dateAnalysis' => [
            'withDates' => 0,
            'dateRange' => null,
            'monthlyDistribution' => [],
            'yearlyDistribution' => []
        ],
        'reReleaseAnalysis' => [
            'total' => 0,
            'byType' => [],
            'byCountry' => []
        ]
    ];

    $dates = [];
    $monthlyStats = [];
    $yearlyStats = [];

    foreach ($distributions as $distribution) {
        // Распределение по типам
        $typeKey = $distribution->type->value;
        if (!isset($analysis['typeDistribution'][$typeKey])) {
            $analysis['typeDistribution'][$typeKey] = 0;
        }
        $analysis['typeDistribution'][$typeKey]++;

        // Распределение по странам
        if ($distribution->country) {
            $countryName = $distribution->country->country;
            if (!isset($analysis['countryDistribution'][$countryName])) {
                $analysis['countryDistribution'][$countryName] = 0;
            }
            $analysis['countryDistribution'][$countryName]++;
        }

        // Распределение по компаниям
        foreach ($distribution->companies as $company) {
            if (!isset($analysis['companyDistribution'][$company])) {
                $analysis['companyDistribution'][$company] = 0;
            }
            $analysis['companyDistribution'][$company]++;
        }

        // Анализ дат
        if ($distribution->date) {
            $analysis['dateAnalysis']['withDates']++;
            $dates[] = $distribution->date;

            // Месячное распределение
            $month = date('Y-m', strtotime($distribution->date));
            if (!isset($monthlyStats[$month])) {
                $monthlyStats[$month] = 0;
            }
            $monthlyStats[$month]++;

            // Годовое распределение
            $year = date('Y', strtotime($distribution->date));
            if (!isset($yearlyStats[$year])) {
                $yearlyStats[$year] = 0;
            }
            $yearlyStats[$year]++;
        }

        // Анализ повторных прокатов
        if ($distribution->reRelease === true) {
            $analysis['reReleaseAnalysis']['total']++;

            // По типам
            $typeKey = $distribution->type->value;
            if (!isset($analysis['reReleaseAnalysis']['byType'][$typeKey])) {
                $analysis['reReleaseAnalysis']['byType'][$typeKey] = 0;
            }
            $analysis['reReleaseAnalysis']['byType'][$typeKey]++;

            // По странам
            if ($distribution->country) {
                $countryName = $distribution->country->country;
                if (!isset($analysis['reReleaseAnalysis']['byCountry'][$countryName])) {
                    $analysis['reReleaseAnalysis']['byCountry'][$countryName] = 0;
                }
                $analysis['reReleaseAnalysis']['byCountry'][$countryName]++;
            }
        }
    }

    // Анализ диапазона дат
    if (!empty($dates)) {
        sort($dates);
        $analysis['dateAnalysis']['dateRange'] = [
            'earliest' => $dates[0],
            'latest' => end($dates)
        ];
    }

    $analysis['dateAnalysis']['monthlyDistribution'] = $monthlyStats;
    $analysis['dateAnalysis']['yearlyDistribution'] = $yearlyStats;

    return $analysis;
}

// Использование
$distributions = $filmService->getDistributions(301);
$analysis = analyzeDistribution($distributions);

echo "=== Анализ проката ===\n";
echo "Всего записей о прокате: {$analysis['totalDistributions']}\n";
echo "Уникальных типов: " . count($analysis['typeDistribution']) . "\n";
echo "Уникальных стран: " . count($analysis['countryDistribution']) . "\n";
echo "Уникальных компаний: " . count($analysis['companyDistribution']) . "\n";

echo "\nРаспределение по типам:\n";
foreach ($analysis['typeDistribution'] as $typeKey => $count) {
    $type = DistributionType::from($typeKey);
    $percentage = round(($count / $analysis['totalDistributions']) * 100, 1);
    echo "- {$type->getDisplayName()}: {$count} ({$percentage}%)\n";
}

echo "\nТоп стран:\n";
arsort($analysis['countryDistribution']);
$topCountries = array_slice($analysis['countryDistribution'], 0, 5, true);
foreach ($topCountries as $country => $count) {
    echo "- {$country}: {$count} прокатов\n";
}

echo "\nТоп компаний:\n";
arsort($analysis['companyDistribution']);
$topCompanies = array_slice($analysis['companyDistribution'], 0, 5, true);
foreach ($topCompanies as $company => $count) {
    echo "- {$company}: {$count} прокатов\n";
}

if ($analysis['dateAnalysis']['dateRange']) {
    echo "\nДиапазон дат проката:\n";
    echo "Самый ранний: {$analysis['dateAnalysis']['dateRange']['earliest']}\n";
    echo "Самый поздний: {$analysis['dateAnalysis']['dateRange']['latest']}\n";
}

echo "\nПовторных прокатов: {$analysis['reReleaseAnalysis']['total']}\n";
```

## Связанные классы

- [`DistributionType`](../enums/distribution-type.md) - Типы проката
- [`Country`](country.md) - Модель страны
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
