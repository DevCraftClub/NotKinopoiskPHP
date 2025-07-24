# CollectionType

Типы коллекций фильмов в Kinopoisk API.

## Описание

`CollectionType` определяет различные типы коллекций фильмов, которые можно получить через API: топ-250, популярные и т.д.

## Значения enum

### Типы коллекций

- `TOP_POPULAR_ALL` - Топ популярных фильмов и сериалов
- `TOP_POPULAR_MOVIES` - Топ популярных фильмов
- `TOP_POPULAR_SERIES` - Топ популярных сериалов
- `TOP_250_MOVIES` - Топ-250 фильмов
- `TOP_250_TV_SHOWS` - Топ-250 сериалов
- `VAMPIRE_THEME` - Тематическая коллекция: вампиры
- `COMICS_THEME` - Тематическая коллекция: комиксы
- `CLOSES_RELEASES` - Скоро выходящие фильмы
- `FAMILY` - Семейные фильмы
- `OSKAR_WINNERS_2021` - Победители Оскара 2021
- `LOVE_THEME` - Тематическая коллекция: любовь
- `ZOMBIE_THEME` - Тематическая коллекция: зомби
- `CATASTROPHE_THEME` - Тематическая коллекция: катастрофы
- `KIDS_ANIMATION_THEME` - Тематическая коллекция: детская анимация
- `POPULAR_SERIES` - Популярные сериалы

## Методы

### getDisplayName()

Получает человекочитаемое название типа коллекции.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа коллекции на русском языке

#### Пример использования

```php
echo CollectionType::TOP_250_MOVIES->getDisplayName(); // "Топ-250 фильмов"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\CollectionType;

// Создание клиента
$client = new Client('your-api-key');

// Получение различных коллекций
$filmService = $client->films;

echo "=== Работа с коллекциями фильмов ===\n";

// Получение топ-250 фильмов
$top250Movies = $filmService->getCollections(CollectionType::TOP_250_MOVIES);
echo "Топ-250 фильмов: " . count($top250Movies) . " элементов\n";

// Получение популярных сериалов
$popularSeries = $filmService->getCollections(CollectionType::TOP_POPULAR_SERIES);
echo "Популярные сериалы: " . count($popularSeries) . " элементов\n";

// Получение тематической коллекции
$vampireCollection = $filmService->getCollections(CollectionType::VAMPIRE_THEME);
echo "Вампиры: " . count($vampireCollection) . " элементов\n";

// Вывод информации о коллекциях
foreach ($vampireCollection as $index => $item) {
    echo ($index + 1) . ". {$item->getDisplayName()}\n";
    if ($item->year) {
        echo "   Год: {$item->year}\n";
    }
    $rating = $item->getMainRating();
    if ($rating !== null) {
        echo "   Рейтинг: {$rating}\n";
    }
}
```

## Работа с типами коллекций

```php
// Функция для получения всех типов коллекций
function getAllCollectionTypes(): array {
    return [
        CollectionType::TOP_POPULAR_ALL,
        CollectionType::TOP_POPULAR_MOVIES,
        CollectionType::TOP_POPULAR_SERIES,
        CollectionType::TOP_250_MOVIES,
        CollectionType::TOP_250_TV_SHOWS,
        CollectionType::VAMPIRE_THEME,
        CollectionType::COMICS_THEME,
        CollectionType::CLOSES_RELEASES,
        CollectionType::FAMILY,
        CollectionType::OSKAR_WINNERS_2021,
        CollectionType::LOVE_THEME,
        CollectionType::ZOMBIE_THEME,
        CollectionType::CATASTROPHE_THEME,
        CollectionType::KIDS_ANIMATION_THEME,
        CollectionType::POPULAR_SERIES
    ];
}

// Функция для получения рейтинговых коллекций
function getRatingCollections(): array {
    return [
        CollectionType::TOP_250_MOVIES,
        CollectionType::TOP_250_TV_SHOWS
    ];
}

// Функция для получения популярных коллекций
function getPopularCollections(): array {
    return [
        CollectionType::TOP_POPULAR_ALL,
        CollectionType::TOP_POPULAR_MOVIES,
        CollectionType::TOP_POPULAR_SERIES,
        CollectionType::POPULAR_SERIES
    ];
}

// Функция для получения тематических коллекций
function getThematicCollections(): array {
    return [
        CollectionType::VAMPIRE_THEME,
        CollectionType::COMICS_THEME,
        CollectionType::LOVE_THEME,
        CollectionType::ZOMBIE_THEME,
        CollectionType::CATASTROPHE_THEME,
        CollectionType::KIDS_ANIMATION_THEME
    ];
}

// Функция для получения специальных коллекций
function getSpecialCollections(): array {
    return [
        CollectionType::CLOSES_RELEASES,
        CollectionType::FAMILY,
        CollectionType::OSKAR_WINNERS_2021
    ];
}

// Функция для группировки коллекций по категориям
function groupCollectionsByCategory(): array {
    return [
        'rating' => getRatingCollections(),
        'popular' => getPopularCollections(),
        'thematic' => getThematicCollections(),
        'special' => getSpecialCollections()
    ];
}

// Использование
$allTypes = getAllCollectionTypes();
$ratingCollections = getRatingCollections();
$popularCollections = getPopularCollections();
$thematicCollections = getThematicCollections();
$specialCollections = getSpecialCollections();

echo "Всего типов коллекций: " . count($allTypes) . "\n";
echo "Рейтинговых коллекций: " . count($ratingCollections) . "\n";
echo "Популярных коллекций: " . count($popularCollections) . "\n";
echo "Тематических коллекций: " . count($thematicCollections) . "\n";
echo "Специальных коллекций: " . count($specialCollections) . "\n";

// Группировка по категориям
$categories = groupCollectionsByCategory();
foreach ($categories as $category => $collections) {
    echo "\n{$category}:\n";
    foreach ($collections as $collection) {
        echo "- {$collection->getDisplayName()}\n";
    }
}
```

## Создание отчета по коллекциям

```php
class CollectionTypeReport {
    private array $collections;

    public function __construct(array $collections) {
        $this->collections = $collections;
    }

    public function getCollectionsByType(CollectionType $type): array {
        return array_filter($this->collections, fn($item) => $item->type === $type);
    }

    public function getRatingCollections(): array {
        return getRatingCollections();
    }

    public function getPopularCollections(): array {
        return getPopularCollections();
    }

    public function getThematicCollections(): array {
        return getThematicCollections();
    }

    public function getSpecialCollections(): array {
        return getSpecialCollections();
    }

    public function getCategoryBreakdown(): array {
        return groupCollectionsByCategory();
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ТИПАМ КОЛЛЕКЦИЙ ===\n\n";

        $categories = $this->getCategoryBreakdown();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего типов коллекций: " . count(getAllCollectionTypes()) . "\n";
        $report .= "Рейтинговых коллекций: " . count($categories['rating']) . "\n";
        $report .= "Популярных коллекций: " . count($categories['popular']) . "\n";
        $report .= "Тематических коллекций: " . count($categories['thematic']) . "\n";
        $report .= "Специальных коллекций: " . count($categories['special']) . "\n\n";

        // Рейтинговые коллекции
        $report .= "🏆 РЕЙТИНГОВЫЕ КОЛЛЕКЦИИ:\n";
        foreach ($categories['rating'] as $collection) {
            $report .= "• {$collection->getDisplayName()}\n";
        }

        $report .= "\n";

        // Популярные коллекции
        $report .= "🔥 ПОПУЛЯРНЫЕ КОЛЛЕКЦИИ:\n";
        foreach ($categories['popular'] as $collection) {
            $report .= "• {$collection->getDisplayName()}\n";
        }

        $report .= "\n";

        // Тематические коллекции
        $report .= "🎭 ТЕМАТИЧЕСКИЕ КОЛЛЕКЦИИ:\n";
        foreach ($categories['thematic'] as $collection) {
            $report .= "• {$collection->getDisplayName()}\n";
        }

        $report .= "\n";

        // Специальные коллекции
        $report .= "⭐ СПЕЦИАЛЬНЫЕ КОЛЛЕКЦИИ:\n";
        foreach ($categories['special'] as $collection) {
            $report .= "• {$collection->getDisplayName()}\n";
        }

        $report .= "\n";

        // Детали по всем типам
        $report .= "📋 ВСЕ ТИПЫ КОЛЛЕКЦИЙ:\n";
        foreach (getAllCollectionTypes() as $collection) {
            $report .= "• {$collection->getDisplayName()}\n";
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
        $html .= ".collection-item { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".collection-name { font-weight: bold; font-size: 16px; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".rating { border-left: 5px solid #28a745; }\n";
        $html .= ".popular { border-left: 5px solid #007bff; }\n";
        $html .= ".thematic { border-left: 5px solid #ffc107; }\n";
        $html .= ".special { border-left: 5px solid #dc3545; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $categories = $this->getCategoryBreakdown();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего типов коллекций:</strong> " . count(getAllCollectionTypes()) . "</p>\n";
        $html .= "<p><strong>Рейтинговых коллекций:</strong> " . count($categories['rating']) . "</p>\n";
        $html .= "<p><strong>Популярных коллекций:</strong> " . count($categories['popular']) . "</p>\n";
        $html .= "<p><strong>Тематических коллекций:</strong> " . count($categories['thematic']) . "</p>\n";
        $html .= "<p><strong>Специальных коллекций:</strong> " . count($categories['special']) . "</p>\n";
        $html .= "</div>\n";

        // Рейтинговые коллекции
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🏆 Рейтинговые коллекции</div>\n";

        foreach ($categories['rating'] as $collection) {
            $html .= "<div class='collection-item rating'>\n";
            $html .= "<div class='collection-name'>{$collection->getDisplayName()}</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        // Популярные коллекции
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🔥 Популярные коллекции</div>\n";

        foreach ($categories['popular'] as $collection) {
            $html .= "<div class='collection-item popular'>\n";
            $html .= "<div class='collection-name'>{$collection->getDisplayName()}</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        // Тематические коллекции
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🎭 Тематические коллекции</div>\n";

        foreach ($categories['thematic'] as $collection) {
            $html .= "<div class='collection-item thematic'>\n";
            $html .= "<div class='collection-name'>{$collection->getDisplayName()}</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        // Специальные коллекции
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>⭐ Специальные коллекции</div>\n";

        foreach ($categories['special'] as $collection) {
            $html .= "<div class='collection-item special'>\n";
            $html .= "<div class='collection-name'>{$collection->getDisplayName()}</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$collections = getAllCollectionTypes();
$report = new CollectionTypeReport($collections);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по типам коллекций');
file_put_contents('collection_types_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в collection_types_report.html\n";
```

## Анализ коллекций

```php
function analyzeCollectionTypes(): array {
    $analysis = [
        'totalTypes' => count(getAllCollectionTypes()),
        'categoryDistribution' => [],
        'typeDetails' => []
    ];

    $categories = groupCollectionsByCategory();

    foreach ($categories as $category => $collections) {
        $analysis['categoryDistribution'][$category] = [
            'count' => count($collections),
            'collections' => $collections
        ];
    }

    foreach (getAllCollectionTypes() as $collection) {
        $analysis['typeDetails'][$collection->value] = [
            'type' => $collection,
            'displayName' => $collection->getDisplayName(),
            'category' => getCollectionCategory($collection)
        ];
    }

    return $analysis;
}

function getCollectionCategory(CollectionType $collection): string {
    if (in_array($collection, getRatingCollections())) {
        return 'rating';
    } elseif (in_array($collection, getPopularCollections())) {
        return 'popular';
    } elseif (in_array($collection, getThematicCollections())) {
        return 'thematic';
    } elseif (in_array($collection, getSpecialCollections())) {
        return 'special';
    }
    return 'unknown';
}

// Использование
$analysis = analyzeCollectionTypes();

echo "=== Анализ типов коллекций ===\n";
echo "Всего типов коллекций: {$analysis['totalTypes']}\n";

echo "\nРаспределение по категориям:\n";
foreach ($analysis['categoryDistribution'] as $category => $data) {
    $percentage = round(($data['count'] / $analysis['totalTypes']) * 100, 1);
    echo "- {$category}: {$data['count']} коллекций ({$percentage}%)\n";
}

echo "\nДетали по типам:\n";
foreach ($analysis['typeDetails'] as $typeKey => $data) {
    echo "- {$data['displayName']} ({$data['category']})\n";
}
```

## Связанные классы

- [`FilmCollection`](../models/film-collection.md) - Модель элемента коллекции
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
