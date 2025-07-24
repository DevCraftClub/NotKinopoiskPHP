# ContentType

Типы контента в Kinopoisk API.

## Описание

`ContentType` определяет различные типы контента, которые могут быть возвращены API: фильмы, сериалы, мини-сериалы и т.д.

## Значения enum

### Типы контента

- `FILM` - Фильм
- `SERIES` - Сериал
- `MINI_SERIES` - Мини-сериал
- `TV_SHOW` - Телешоу
- `TV_MOVIE` - ТВ-фильм
- `VIDEO` - Видео
- `SHORT` - Короткометражка
- `DOCUMENTARY` - Документальный фильм
- `TV_SERIES` - ТВ-сериал (альтернативное название)
- `UNKNOWN` - Неизвестный тип
- `ALL` - Все типы (для фильтрации)

## Методы

### isFilm()

Проверяет, является ли контент фильмом.

```php
public function isFilm(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это фильм, `false` в противном случае

#### Описание

Возвращает `true` для следующих типов: FILM, TV_MOVIE, VIDEO, SHORT, DOCUMENTARY.

#### Пример использования

```php
if (ContentType::FILM->isFilm()) {
    echo "Это фильм";
}
```

### isSeries()

Проверяет, является ли контент сериалом.

```php
public function isSeries(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это сериал, `false` в противном случае

#### Описание

Возвращает `true` для следующих типов: SERIES, MINI_SERIES, TV_SHOW, TV_SERIES.

#### Пример использования

```php
if (ContentType::SERIES->isSeries()) {
    echo "Это сериал";
}
```

### getDisplayName()

Получает человекочитаемое название типа контента.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа контента на русском языке

#### Пример использования

```php
echo ContentType::FILM->getDisplayName(); // "Фильм"
echo ContentType::SERIES->getDisplayName(); // "Сериал"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ContentType;

// Создание клиента
$client = new Client('your-api-key');

// Получение фильмов
$filmService = $client->films;
$films = $filmService->getTopFilms(ContentType::FILM);

echo "=== Работа с типами контента ===\n";

// Вывод всех типов контента
echo "Доступные типы контента:\n";
foreach ([
    ContentType::FILM,
    ContentType::SERIES,
    ContentType::MINI_SERIES,
    ContentType::TV_SHOW,
    ContentType::TV_MOVIE,
    ContentType::VIDEO,
    ContentType::SHORT,
    ContentType::DOCUMENTARY,
    ContentType::TV_SERIES
] as $type) {
    $icon = getContentTypeIcon($type);
    $displayName = $type->getDisplayName();
    echo "{$icon} {$displayName} ({$type->value})\n";
}

echo "\n";

// Группировка контента по типам
$groupedContent = [];
foreach ($films as $film) {
    $type = $film->type;
    if (!isset($groupedContent[$type->value])) {
        $groupedContent[$type->value] = [];
    }
    $groupedContent[$type->value][] = $film;
}

// Вывод контента по типам
foreach ($groupedContent as $typeValue => $typeContent) {
    $type = ContentType::from($typeValue);
    $icon = getContentTypeIcon($type);
    $displayName = $type->getDisplayName();

    echo "{$icon} {$displayName} (" . count($typeContent) . " элементов):\n";
    foreach (array_slice($typeContent, 0, 3) as $content) {
        echo "  • {$content->getDisplayName()}\n";
    }
    if (count($typeContent) > 3) {
        echo "  ... и еще " . (count($typeContent) - 3) . " элементов\n";
    }
    echo "\n";
}
```

## Работа с типами контента

```php
// Функция для получения иконки типа контента
function getContentTypeIcon(ContentType $type): string {
    return match ($type) {
        ContentType::FILM => '🎬',
        ContentType::SERIES => '📺',
        ContentType::MINI_SERIES => '📺',
        ContentType::TV_SHOW => '🎭',
        ContentType::TV_MOVIE => '📺',
        ContentType::VIDEO => '🎥',
        ContentType::SHORT => '⏱️',
        ContentType::DOCUMENTARY => '📹',
        ContentType::TV_SERIES => '📺',
        ContentType::UNKNOWN => '❓',
        ContentType::ALL => '📚'
    };
}

// Функция для получения цвета типа контента
function getContentTypeColor(ContentType $type): string {
    return match ($type) {
        ContentType::FILM => '#007bff',        // Синий
        ContentType::SERIES => '#28a745',      // Зеленый
        ContentType::MINI_SERIES => '#20c997', // Бирюзовый
        ContentType::TV_SHOW => '#ffc107',     // Желтый
        ContentType::TV_MOVIE => '#fd7e14',    // Оранжевый
        ContentType::VIDEO => '#6f42c1',       // Фиолетовый
        ContentType::SHORT => '#e83e8c',       // Розовый
        ContentType::DOCUMENTARY => '#6c757d', // Серый
        ContentType::TV_SERIES => '#17a2b8',   // Голубой
        ContentType::UNKNOWN => '#6c757d',     // Серый
        ContentType::ALL => '#343a40'          // Темно-серый
    };
}

// Функция для получения описания типа контента
function getContentTypeDescription(ContentType $type): string {
    return match ($type) {
        ContentType::FILM => 'Полнометражный художественный фильм',
        ContentType::SERIES => 'Многосерийный телевизионный сериал',
        ContentType::MINI_SERIES => 'Короткий сериал с ограниченным количеством серий',
        ContentType::TV_SHOW => 'Телевизионное шоу или программа',
        ContentType::TV_MOVIE => 'Фильм, созданный специально для телевидения',
        ContentType::VIDEO => 'Короткое видео или клип',
        ContentType::SHORT => 'Короткометражный фильм',
        ContentType::DOCUMENTARY => 'Документальный фильм',
        ContentType::TV_SERIES => 'Телевизионный сериал (альтернативное название)',
        ContentType::UNKNOWN => 'Тип контента не определен',
        ContentType::ALL => 'Все типы контента'
    };
}

// Функция для фильтрации контента по типу
function filterContentByType(array $content, ContentType $type): array {
    return array_filter($content, fn($item) => $item->type === $type);
}

// Функция для получения статистики по типам контента
function getContentTypeStats(array $content): array {
    $stats = [
        'total' => count($content),
        'films' => 0,
        'series' => 0,
        'other' => 0,
        'byType' => []
    ];

    foreach ($content as $item) {
        $type = $item->type;

        if ($type->isFilm()) {
            $stats['films']++;
        } elseif ($type->isSeries()) {
            $stats['series']++;
        } else {
            $stats['other']++;
        }

        if (!isset($stats['byType'][$type->value])) {
            $stats['byType'][$type->value] = 0;
        }
        $stats['byType'][$type->value]++;
    }

    return $stats;
}

// Функция для получения всех типов контента
function getAllContentTypes(): array {
    return [
        ContentType::FILM,
        ContentType::SERIES,
        ContentType::MINI_SERIES,
        ContentType::TV_SHOW,
        ContentType::TV_MOVIE,
        ContentType::VIDEO,
        ContentType::SHORT,
        ContentType::DOCUMENTARY,
        ContentType::TV_SERIES
    ];
}

// Функция для получения основных типов контента
function getMainContentTypes(): array {
    return [ContentType::FILM, ContentType::SERIES];
}

// Функция для получения телевизионных типов контента
function getTVContentTypes(): array {
    return [
        ContentType::TV_SHOW,
        ContentType::TV_MOVIE,
        ContentType::TV_SERIES,
        ContentType::MINI_SERIES
    ];
}

// Функция для получения коротких типов контента
function getShortContentTypes(): array {
    return [ContentType::SHORT, ContentType::VIDEO];
}

// Использование
$allTypes = getAllContentTypes();

echo "Работа с типами контента:\n";
foreach ($allTypes as $type) {
    $icon = getContentTypeIcon($type);
    $displayName = $type->getDisplayName();
    $color = getContentTypeColor($type);
    $description = getContentTypeDescription($type);
    $isFilm = $type->isFilm() ? 'Да' : 'Нет';
    $isSeries = $type->isSeries() ? 'Да' : 'Нет';

    echo "{$icon} {$displayName}\n";
    echo "   Цвет: {$color}\n";
    echo "   Описание: {$description}\n";
    echo "   Фильм: {$isFilm}\n";
    echo "   Сериал: {$isSeries}\n\n";
}

// Группировка по категориям
$mainTypes = getMainContentTypes();
$tvTypes = getTVContentTypes();
$shortTypes = getShortContentTypes();

echo "Основные типы:\n";
foreach ($mainTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}

echo "\nТелевизионные типы:\n";
foreach ($tvTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}

echo "\nКороткие типы:\n";
foreach ($shortTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}
```

## Создание отчета по типам контента

```php
class ContentTypeReport {
    private array $content;

    public function __construct(array $content) {
        $this->content = $content;
    }

    public function getContent(): array {
        return $this->content;
    }

    public function getContentByType(ContentType $type): array {
        return filterContentByType($this->content, $type);
    }

    public function getContentTypeStats(): array {
        return getContentTypeStats($this->content);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ТИПАМ КОНТЕНТА ===\n\n";

        $stats = $this->getContentTypeStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего элементов: {$stats['total']}\n";
        $report .= "Фильмов: {$stats['films']}\n";
        $report .= "Сериалов: {$stats['series']}\n";
        $report .= "Других типов: {$stats['other']}\n\n";

        // Процентное распределение
        if ($stats['total'] > 0) {
            $filmsPercent = round(($stats['films'] / $stats['total']) * 100, 1);
            $seriesPercent = round(($stats['series'] / $stats['total']) * 100, 1);
            $otherPercent = round(($stats['other'] / $stats['total']) * 100, 1);

            $report .= "📈 ПРОЦЕНТНОЕ РАСПРЕДЕЛЕНИЕ:\n";
            $report .= "• Фильмы: {$filmsPercent}%\n";
            $report .= "• Сериалы: {$seriesPercent}%\n";
            $report .= "• Другие типы: {$otherPercent}%\n\n";
        }

        // Детали по типам
        $report .= "📋 ДЕТАЛИ ПО ТИПАМ:\n";
        foreach ($stats['byType'] as $typeValue => $count) {
            $type = ContentType::from($typeValue);
            $icon = getContentTypeIcon($type);
            $displayName = $type->getDisplayName();
            $percent = round(($count / $stats['total']) * 100, 1);

            $report .= "{$icon} {$displayName}: {$count} ({$percent}%)\n";
        }
        $report .= "\n";

        // Фильмы
        $films = $this->getContentByType(ContentType::FILM);
        if (!empty($films)) {
            $report .= "🎬 ФИЛЬМЫ (" . count($films) . " элементов):\n";
            foreach (array_slice($films, 0, 5) as $film) {
                $report .= "• {$film->getDisplayName()}\n";
            }
            if (count($films) > 5) {
                $report .= "... и еще " . (count($films) - 5) . " фильмов\n";
            }
            $report .= "\n";
        }

        // Сериалы
        $series = $this->getContentByType(ContentType::SERIES);
        if (!empty($series)) {
            $report .= "📺 СЕРИАЛЫ (" . count($series) . " элементов):\n";
            foreach (array_slice($series, 0, 5) as $seriesItem) {
                $report .= "• {$seriesItem->getDisplayName()}\n";
            }
            if (count($series) > 5) {
                $report .= "... и еще " . (count($series) - 5) . " сериалов\n";
            }
            $report .= "\n";
        }

        // Мини-сериалы
        $miniSeries = $this->getContentByType(ContentType::MINI_SERIES);
        if (!empty($miniSeries)) {
            $report .= "📺 МИНИ-СЕРИАЛЫ (" . count($miniSeries) . " элементов):\n";
            foreach (array_slice($miniSeries, 0, 5) as $miniSeriesItem) {
                $report .= "• {$miniSeriesItem->getDisplayName()}\n";
            }
            if (count($miniSeries) > 5) {
                $report .= "... и еще " . (count($miniSeries) - 5) . " мини-сериалов\n";
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
        $html .= ".content-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".content-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".content-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".content-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".film { border-left: 5px solid #007bff; }\n";
        $html .= ".series { border-left: 5px solid #28a745; }\n";
        $html .= ".mini-series { border-left: 5px solid #20c997; }\n";
        $html .= ".tv-show { border-left: 5px solid #ffc107; }\n";
        $html .= ".tv-movie { border-left: 5px solid #fd7e14; }\n";
        $html .= ".video { border-left: 5px solid #6f42c1; }\n";
        $html .= ".short { border-left: 5px solid #e83e8c; }\n";
        $html .= ".documentary { border-left: 5px solid #6c757d; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; transition: width 0.3s ease; }\n";
        $html .= ".film-fill { background-color: #007bff; }\n";
        $html .= ".series-fill { background-color: #28a745; }\n";
        $html .= ".other-fill { background-color: #6c757d; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getContentTypeStats();
        $films = $this->getContentByType(ContentType::FILM);
        $series = $this->getContentByType(ContentType::SERIES);
        $miniSeries = $this->getContentByType(ContentType::MINI_SERIES);

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего элементов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Фильмов:</strong> {$stats['films']}</p>\n";
        $html .= "<p><strong>Сериалов:</strong> {$stats['series']}</p>\n";
        $html .= "<p><strong>Других типов:</strong> {$stats['other']}</p>\n";

        // Прогресс-бары
        if ($stats['total'] > 0) {
            $filmsPercent = round(($stats['films'] / $stats['total']) * 100, 1);
            $seriesPercent = round(($stats['series'] / $stats['total']) * 100, 1);
            $otherPercent = round(($stats['other'] / $stats['total']) * 100, 1);

            $html .= "<h3>Распределение по категориям</h3>\n";
            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill film-fill' style='width: {$filmsPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Фильмы: {$filmsPercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill series-fill' style='width: {$seriesPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Сериалы: {$seriesPercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill other-fill' style='width: {$otherPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Другие типы: {$otherPercent}%</p>\n";
        }

        $html .= "</div>\n";

        // Фильмы
        if (!empty($films)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🎬 Фильмы (" . count($films) . " элементов)</div>\n";
            $html .= "<div class='content-grid'>\n";

            foreach (array_slice($films, 0, 12) as $film) {
                $html .= "<div class='content-item film'>\n";
                $html .= "<div class='content-title'>{$film->getDisplayName()}</div>\n";
                if ($film->rating) {
                    $html .= "<div class='content-meta'>Рейтинг: {$film->rating}</div>\n";
                }
                if ($film->year) {
                    $html .= "<div class='content-meta'>Год: {$film->year}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Сериалы
        if (!empty($series)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📺 Сериалы (" . count($series) . " элементов)</div>\n";
            $html .= "<div class='content-grid'>\n";

            foreach (array_slice($series, 0, 12) as $seriesItem) {
                $html .= "<div class='content-item series'>\n";
                $html .= "<div class='content-title'>{$seriesItem->getDisplayName()}</div>\n";
                if ($seriesItem->rating) {
                    $html .= "<div class='content-meta'>Рейтинг: {$seriesItem->rating}</div>\n";
                }
                if ($seriesItem->year) {
                    $html .= "<div class='content-meta'>Год: {$seriesItem->year}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Мини-сериалы
        if (!empty($miniSeries)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📺 Мини-сериалы (" . count($miniSeries) . " элементов)</div>\n";
            $html .= "<div class='content-grid'>\n";

            foreach (array_slice($miniSeries, 0, 12) as $miniSeriesItem) {
                $html .= "<div class='content-item mini-series'>\n";
                $html .= "<div class='content-title'>{$miniSeriesItem->getDisplayName()}</div>\n";
                if ($miniSeriesItem->rating) {
                    $html .= "<div class='content-meta'>Рейтинг: {$miniSeriesItem->rating}</div>\n";
                }
                if ($miniSeriesItem->year) {
                    $html .= "<div class='content-meta'>Год: {$miniSeriesItem->year}</div>\n";
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
$content = $filmService->getTopFilms(ContentType::ALL);
$report = new ContentTypeReport($content);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по типам контента');
file_put_contents('content_types_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в content_types_report.html\n";
```

## Анализ типов контента

```php
function analyzeContentTypes(array $content): array {
    $analysis = [
        'totalContent' => count($content),
        'contentTypeDistribution' => [
            'films' => 0,
            'series' => 0,
            'other' => 0
        ],
        'contentTypePercentages' => [
            'films' => 0,
            'series' => 0,
            'other' => 0
        ],
        'detailedTypeDistribution' => [],
        'ratingAnalysis' => [
            'averageRating' => 0,
            'highestRatedType' => '',
            'lowestRatedType' => '',
            'typeRatings' => []
        ]
    ];

    $totalRating = 0;
    $ratedContent = 0;
    $typeRatings = [];

    foreach ($content as $item) {
        $type = $item->type;

        // Распределение по категориям
        if ($type->isFilm()) {
            $analysis['contentTypeDistribution']['films']++;
        } elseif ($type->isSeries()) {
            $analysis['contentTypeDistribution']['series']++;
        } else {
            $analysis['contentTypeDistribution']['other']++;
        }

        // Детальное распределение по типам
        if (!isset($analysis['detailedTypeDistribution'][$type->value])) {
            $analysis['detailedTypeDistribution'][$type->value] = 0;
        }
        $analysis['detailedTypeDistribution'][$type->value]++;

        // Анализ рейтингов
        if ($item->rating) {
            $rating = (float)$item->rating;
            $totalRating += $rating;
            $ratedContent++;

            if (!isset($typeRatings[$type->value])) {
                $typeRatings[$type->value] = [];
            }
            $typeRatings[$type->value][] = $rating;
        }
    }

    // Вычисление процентов
    if ($analysis['totalContent'] > 0) {
        $analysis['contentTypePercentages']['films'] = round(($analysis['contentTypeDistribution']['films'] / $analysis['totalContent']) * 100, 1);
        $analysis['contentTypePercentages']['series'] = round(($analysis['contentTypeDistribution']['series'] / $analysis['totalContent']) * 100, 1);
        $analysis['contentTypePercentages']['other'] = round(($analysis['contentTypeDistribution']['other'] / $analysis['totalContent']) * 100, 1);
    }

    // Анализ рейтингов по типам
    if (!empty($typeRatings)) {
        $analysis['ratingAnalysis']['averageRating'] = round($totalRating / $ratedContent, 2);

        $typeAverages = [];
        foreach ($typeRatings as $typeValue => $ratings) {
            $typeAverages[$typeValue] = round(array_sum($ratings) / count($ratings), 2);
        }

        if (!empty($typeAverages)) {
            $analysis['ratingAnalysis']['highestRatedType'] = array_search(max($typeAverages), $typeAverages);
            $analysis['ratingAnalysis']['lowestRatedType'] = array_search(min($typeAverages), $typeAverages);
            $analysis['ratingAnalysis']['typeRatings'] = $typeAverages;
        }
    }

    return $analysis;
}

// Использование
$content = $filmService->getTopFilms(ContentType::ALL);
$analysis = analyzeContentTypes($content);

echo "=== Анализ типов контента ===\n";
echo "Всего элементов: {$analysis['totalContent']}\n";

echo "\nРаспределение по категориям:\n";
echo "- Фильмы: {$analysis['contentTypeDistribution']['films']} ({$analysis['contentTypePercentages']['films']}%)\n";
echo "- Сериалы: {$analysis['contentTypeDistribution']['series']} ({$analysis['contentTypePercentages']['series']}%)\n";
echo "- Другие типы: {$analysis['contentTypeDistribution']['other']} ({$analysis['contentTypePercentages']['other']}%)\n";

echo "\nДетальное распределение по типам:\n";
foreach ($analysis['detailedTypeDistribution'] as $typeValue => $count) {
    $type = ContentType::from($typeValue);
    echo "- {$type->getDisplayName()}: {$count}\n";
}

echo "\nАнализ рейтингов:\n";
echo "- Средний рейтинг: {$analysis['ratingAnalysis']['averageRating']}\n";
if ($analysis['ratingAnalysis']['highestRatedType']) {
    $highestType = ContentType::from($analysis['ratingAnalysis']['highestRatedType']);
    echo "- Самый высокий рейтинг у: {$highestType->getDisplayName()}\n";
}
if ($analysis['ratingAnalysis']['lowestRatedType']) {
    $lowestType = ContentType::from($analysis['ratingAnalysis']['lowestRatedType']);
    echo "- Самый низкий рейтинг у: {$lowestType->getDisplayName()}\n";
}
```

## Связанные классы

- [`Film`](film.md) - Модель фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
