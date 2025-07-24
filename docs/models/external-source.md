# ExternalSource

Модель внешнего источника из Kinopoisk API.

## Описание

`ExternalSource` представляет информацию о рецензии или отзыве с внешней платформы, включая данные о платформе, рейтинге и содержании.

### Основные возможности

- Хранение информации о внешнем источнике в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к данным платформы и рейтингу

**API Endpoint:** `/api/v2.2/films/{id}/external_sources`

## Свойства

### Основная информация

- `$url` (string) - URL источника
- `$platform` (string) - Название платформы
- `$logoUrl` (string) - URL логотипа платформы
- `$positiveRating` (int|null) - Количество положительных оценок
- `$negativeRating` (int|null) - Количество отрицательных оценок
- `$author` (string|null) - Автор отзыва
- `$title` (string|null) - Заголовок отзыва
- `$description` (string|null) - Содержание отзыва

## Конструктор

```php
public function __construct(
    public readonly string $url,
    public readonly string $platform,
    public readonly string $logoUrl,
    public readonly ?int $positiveRating,
    public readonly ?int $negativeRating,
    public readonly ?string $author,
    public readonly ?string $title,
    public readonly ?string $description,
)
```

### Пример создания

```php
$externalSource = new ExternalSource(
    url: 'https://example.com/review',
    platform: 'IMDb',
    logoUrl: 'https://...',
    positiveRating: 90,
    negativeRating: 10,
    author: 'Пользователь',
    title: 'Отличный фильм',
    description: 'Подробный отзыв...'
);
```

## Методы

### fromArray()

Создает экземпляр внешнего источника из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных внешнего источника от API

#### Возвращаемое значение

- `self` - Новый экземпляр внешнего источника

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'url' => 'https://example.com/review',
    'platform' => 'IMDb',
    'logoUrl' => 'https://...',
    'positiveRating' => 90,
    'negativeRating' => 10,
    'author' => 'Пользователь',
    'title' => 'Отличный фильм',
    'description' => 'Подробный отзыв...'
];

$externalSource = ExternalSource::fromArray($apiData);
```

### toArray()

Преобразует объект внешнего источника в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными внешнего источника

#### Пример использования

```php
$externalSourceArray = $externalSource->toArray();
echo json_encode($externalSourceArray); // JSON представление внешнего источника
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\ExternalSource;

// Создание клиента
$client = new Client('your-api-key');

// Получение внешних источников фильма
$filmService = $client->films;
$externalSources = $filmService->getExternalSources(301); // Матрица

echo "=== Внешние источники фильма 'Матрица' ===\n";

// Обработка всех источников
foreach ($externalSources as $source) {
    echo "\n📰 {$source->platform}:\n";
    echo "  URL: {$source->url}\n";

    if ($source->title) {
        echo "  Заголовок: {$source->title}\n";
    }

    if ($source->author) {
        echo "  Автор: {$source->author}\n";
    }

    if ($source->positiveRating !== null && $source->negativeRating !== null) {
        $total = $source->positiveRating + $source->negativeRating;
        $percentage = $total > 0 ? round(($source->positiveRating / $total) * 100, 1) : 0;
        echo "  Рейтинг: {$source->positiveRating}/{$source->negativeRating} ({$percentage}% положительных)\n";
    }

    if ($source->description) {
        echo "  Описание: " . substr($source->description, 0, 100) . "...\n";
    }
}
```

## Работа с внешними источниками

```php
// Функция для фильтрации по платформе
function filterSourcesByPlatform(array $sources, string $platform): array {
    return array_filter($sources, fn($source) => strcasecmp($source->platform, $platform) === 0);
}

// Функция для получения источников с рейтингом
function getSourcesWithRating(array $sources): array {
    return array_filter($sources, fn($source) =>
        $source->positiveRating !== null && $source->negativeRating !== null
    );
}

// Функция для получения источников с автором
function getSourcesWithAuthor(array $sources): array {
    return array_filter($sources, fn($source) => $source->author !== null);
}

// Функция для получения статистики по платформам
function getPlatformStatistics(array $sources): array {
    $stats = [];

    foreach ($sources as $source) {
        $platform = $source->platform;
        if (!isset($stats[$platform])) {
            $stats[$platform] = [
                'count' => 0,
                'withRating' => 0,
                'withAuthor' => 0,
                'withDescription' => 0,
                'totalPositive' => 0,
                'totalNegative' => 0
            ];
        }

        $stats[$platform]['count']++;

        if ($source->positiveRating !== null && $source->negativeRating !== null) {
            $stats[$platform]['withRating']++;
            $stats[$platform]['totalPositive'] += $source->positiveRating;
            $stats[$platform]['totalNegative'] += $source->negativeRating;
        }

        if ($source->author !== null) {
            $stats[$platform]['withAuthor']++;
        }

        if ($source->description !== null) {
            $stats[$platform]['withDescription']++;
        }
    }

    return $stats;
}

// Функция для расчета общего рейтинга
function calculateOverallRating(array $sources): ?float {
    $sourcesWithRating = getSourcesWithRating($sources);

    if (empty($sourcesWithRating)) {
        return null;
    }

    $totalPositive = 0;
    $totalNegative = 0;

    foreach ($sourcesWithRating as $source) {
        $totalPositive += $source->positiveRating;
        $totalNegative += $source->negativeRating;
    }

    $total = $totalPositive + $totalNegative;

    return $total > 0 ? round(($totalPositive / $total) * 100, 1) : null;
}

// Использование
$externalSources = $filmService->getExternalSources(301);

// Фильтрация по платформе
$imdbSources = filterSourcesByPlatform($externalSources, 'IMDb');
echo "Источников IMDb: " . count($imdbSources) . "\n";

// Источники с рейтингом
$sourcesWithRating = getSourcesWithRating($externalSources);
echo "Источников с рейтингом: " . count($sourcesWithRating) . "\n";

// Источники с автором
$sourcesWithAuthor = getSourcesWithAuthor($externalSources);
echo "Источников с автором: " . count($sourcesWithAuthor) . "\n";

// Статистика по платформам
$platformStats = getPlatformStatistics($externalSources);
echo "Статистика по платформам:\n";
foreach ($platformStats as $platform => $stats) {
    echo "- {$platform}: {$stats['count']} источников\n";
    if ($stats['withRating'] > 0) {
        $avgRating = round(($stats['totalPositive'] / ($stats['totalPositive'] + $stats['totalNegative'])) * 100, 1);
        echo "  Средний рейтинг: {$avgRating}%\n";
    }
}

// Общий рейтинг
$overallRating = calculateOverallRating($externalSources);
if ($overallRating !== null) {
    echo "Общий рейтинг по всем источникам: {$overallRating}%\n";
}
```

## Создание отчета по внешним источникам

```php
class ExternalSourcesReport {
    private array $sources;

    public function __construct(array $sources) {
        $this->sources = $sources;
    }

    public function getSourcesByPlatform(string $platform): array {
        return filterSourcesByPlatform($this->sources, $platform);
    }

    public function getSourcesWithRating(): array {
        return getSourcesWithRating($this->sources);
    }

    public function getSourcesWithAuthor(): array {
        return getSourcesWithAuthor($this->sources);
    }

    public function getPlatformStatistics(): array {
        return getPlatformStatistics($this->sources);
    }

    public function getOverallRating(): ?float {
        return calculateOverallRating($this->sources);
    }

    public function getTopPlatforms(int $limit = 5): array {
        $stats = $this->getPlatformStatistics();

        // Сортировка по количеству источников
        uasort($stats, fn($a, $b) => $b['count'] <=> $a['count']);

        return array_slice($stats, 0, $limit, true);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ВНЕШНИМ ИСТОЧНИКАМ ===\n\n";

        $stats = $this->getPlatformStatistics();
        $overallRating = $this->getOverallRating();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего источников: " . count($this->sources) . "\n";
        $report .= "Уникальных платформ: " . count($stats) . "\n";
        $report .= "Источников с рейтингом: " . count($this->getSourcesWithRating()) . "\n";
        $report .= "Источников с автором: " . count($this->getSourcesWithAuthor()) . "\n";

        if ($overallRating !== null) {
            $report .= "Общий рейтинг: {$overallRating}%\n";
        }

        $report .= "\n";

        // Топ платформ
        $topPlatforms = $this->getTopPlatforms();
        $report .= "🏆 ТОП ПЛАТФОРМ:\n";
        foreach ($topPlatforms as $platform => $platformStats) {
            $report .= "• {$platform}: {$platformStats['count']} источников\n";

            if ($platformStats['withRating'] > 0) {
                $avgRating = round(($platformStats['totalPositive'] / ($platformStats['totalPositive'] + $platformStats['totalNegative'])) * 100, 1);
                $report .= "  Средний рейтинг: {$avgRating}%\n";
            }

            if ($platformStats['withAuthor'] > 0) {
                $report .= "  С автором: {$platformStats['withAuthor']}\n";
            }

            if ($platformStats['withDescription'] > 0) {
                $report .= "  С описанием: {$platformStats['withDescription']}\n";
            }
        }

        // Детали по источникам
        $report .= "\n📋 ДЕТАЛИ ПО ИСТОЧНИКАМ:\n";
        foreach ($this->sources as $index => $source) {
            $report .= "\n" . ($index + 1) . ". {$source->platform}:\n";
            $report .= "   URL: {$source->url}\n";

            if ($source->title) {
                $report .= "   Заголовок: {$source->title}\n";
            }

            if ($source->author) {
                $report .= "   Автор: {$source->author}\n";
            }

            if ($source->positiveRating !== null && $source->negativeRating !== null) {
                $total = $source->positiveRating + $source->negativeRating;
                $percentage = $total > 0 ? round(($source->positiveRating / $total) * 100, 1) : 0;
                $report .= "   Рейтинг: {$source->positiveRating}/{$source->negativeRating} ({$percentage}%)\n";
            }

            if ($source->description) {
                $report .= "   Описание: " . substr($source->description, 0, 150) . "...\n";
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
        $html .= ".source-item { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }\n";
        $html .= ".source-header { font-weight: bold; font-size: 16px; margin-bottom: 10px; color: #007bff; }\n";
        $html .= ".source-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }\n";
        $html .= ".source-detail { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".platform-item { margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".rating { font-weight: bold; color: #28a745; }\n";
        $html .= ".url { color: #007bff; text-decoration: none; }\n";
        $html .= ".url:hover { text-decoration: underline; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getPlatformStatistics();
        $overallRating = $this->getOverallRating();
        $topPlatforms = $this->getTopPlatforms();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего источников:</strong> " . count($this->sources) . "</p>\n";
        $html .= "<p><strong>Уникальных платформ:</strong> " . count($stats) . "</p>\n";
        $html .= "<p><strong>Источников с рейтингом:</strong> " . count($this->getSourcesWithRating()) . "</p>\n";
        $html .= "<p><strong>Источников с автором:</strong> " . count($this->getSourcesWithAuthor()) . "</p>\n";

        if ($overallRating !== null) {
            $html .= "<p><strong>Общий рейтинг:</strong> <span class='rating'>{$overallRating}%</span></p>\n";
        }

        $html .= "</div>\n";

        // Топ платформ
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Топ платформ</div>\n";

        foreach ($topPlatforms as $platform => $platformStats) {
            $html .= "<div class='platform-item'>\n";
            $html .= "<div class='source-header'>{$platform}</div>\n";
            $html .= "<div class='source-details'>\n";
            $html .= "<div class='source-detail'>Источников: {$platformStats['count']}</div>\n";

            if ($platformStats['withRating'] > 0) {
                $avgRating = round(($platformStats['totalPositive'] / ($platformStats['totalPositive'] + $platformStats['totalNegative'])) * 100, 1);
                $html .= "<div class='source-detail'>Средний рейтинг: <span class='rating'>{$avgRating}%</span></div>\n";
            }

            if ($platformStats['withAuthor'] > 0) {
                $html .= "<div class='source-detail'>С автором: {$platformStats['withAuthor']}</div>\n";
            }

            if ($platformStats['withDescription'] > 0) {
                $html .= "<div class='source-detail'>С описанием: {$platformStats['withDescription']}</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n";

        // Детали по источникам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Детали по источникам</div>\n";

        foreach ($this->sources as $index => $source) {
            $html .= "<div class='source-item'>\n";
            $html .= "<div class='source-header'>{$source->platform}</div>\n";
            $html .= "<div class='source-details'>\n";

            $html .= "<div class='source-detail'><strong>URL:</strong> <a href='{$source->url}' class='url' target='_blank'>{$source->url}</a></div>\n";

            if ($source->title) {
                $html .= "<div class='source-detail'><strong>Заголовок:</strong> {$source->title}</div>\n";
            }

            if ($source->author) {
                $html .= "<div class='source-detail'><strong>Автор:</strong> {$source->author}</div>\n";
            }

            if ($source->positiveRating !== null && $source->negativeRating !== null) {
                $total = $source->positiveRating + $source->negativeRating;
                $percentage = $total > 0 ? round(($source->positiveRating / $total) * 100, 1) : 0;
                $html .= "<div class='source-detail'><strong>Рейтинг:</strong> <span class='rating'>{$source->positiveRating}/{$source->negativeRating} ({$percentage}%)</span></div>\n";
            }

            if ($source->description) {
                $html .= "<div class='source-detail'><strong>Описание:</strong> " . htmlspecialchars(substr($source->description, 0, 200)) . "...</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$externalSources = $filmService->getExternalSources(301);
$report = new ExternalSourcesReport($externalSources);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по внешним источникам');
file_put_contents('external_sources_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в external_sources_report.html\n";
```

## Анализ внешних источников

```php
function analyzeExternalSources(array $sources): array {
    $analysis = [
        'totalSources' => count($sources),
        'platforms' => [],
        'sourcesWithRating' => 0,
        'sourcesWithAuthor' => 0,
        'sourcesWithDescription' => 0,
        'averageRating' => null,
        'ratingDistribution' => [],
        'topPlatforms' => [],
        'ratingRanges' => [
            'excellent' => 0, // 90-100%
            'good' => 0,      // 70-89%
            'average' => 0,   // 50-69%
            'poor' => 0       // 0-49%
        ]
    ];

    $platformStats = [];
    $ratings = [];

    foreach ($sources as $source) {
        // Статистика по платформам
        $platform = $source->platform;
        if (!isset($platformStats[$platform])) {
            $platformStats[$platform] = 0;
        }
        $platformStats[$platform]++;

        // Подсчет источников с данными
        if ($source->positiveRating !== null && $source->negativeRating !== null) {
            $analysis['sourcesWithRating']++;
            $total = $source->positiveRating + $source->negativeRating;
            if ($total > 0) {
                $rating = ($source->positiveRating / $total) * 100;
                $ratings[] = $rating;

                // Распределение по диапазонам
                if ($rating >= 90) $analysis['ratingRanges']['excellent']++;
                elseif ($rating >= 70) $analysis['ratingRanges']['good']++;
                elseif ($rating >= 50) $analysis['ratingRanges']['average']++;
                else $analysis['ratingRanges']['poor']++;
            }
        }

        if ($source->author !== null) {
            $analysis['sourcesWithAuthor']++;
        }

        if ($source->description !== null) {
            $analysis['sourcesWithDescription']++;
        }
    }

    // Анализ платформ
    $analysis['platforms'] = array_keys($platformStats);
    arsort($platformStats);
    $analysis['topPlatforms'] = array_slice($platformStats, 0, 5, true);

    // Средний рейтинг
    if (!empty($ratings)) {
        $analysis['averageRating'] = round(array_sum($ratings) / count($ratings), 1);
    }

    return $analysis;
}

// Использование
$externalSources = $filmService->getExternalSources(301);
$analysis = analyzeExternalSources($externalSources);

echo "=== Анализ внешних источников ===\n";
echo "Всего источников: {$analysis['totalSources']}\n";
echo "Уникальных платформ: " . count($analysis['platforms']) . "\n";
echo "Источников с рейтингом: {$analysis['sourcesWithRating']}\n";
echo "Источников с автором: {$analysis['sourcesWithAuthor']}\n";
echo "Источников с описанием: {$analysis['sourcesWithDescription']}\n";

if ($analysis['averageRating'] !== null) {
    echo "Средний рейтинг: {$analysis['averageRating']}%\n";
}

echo "\nТоп платформ:\n";
foreach ($analysis['topPlatforms'] as $platform => $count) {
    echo "- {$platform}: {$count} источников\n";
}

echo "\nРаспределение рейтингов:\n";
echo "- Отличные (90-100%): {$analysis['ratingRanges']['excellent']}\n";
echo "- Хорошие (70-89%): {$analysis['ratingRanges']['good']}\n";
echo "- Средние (50-69%): {$analysis['ratingRanges']['average']}\n";
echo "- Плохие (0-49%): {$analysis['ratingRanges']['poor']}\n";
```

## Связанные классы

- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
