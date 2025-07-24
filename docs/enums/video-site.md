# VideoSite

Типы видео-сайтов в Kinopoisk API.

## Описание

`VideoSite` определяет различные платформы, на которых размещены видео: YouTube, Vimeo, Kinopoisk и другие.

## Значения enum

### Основные платформы

- `YOUTUBE` - YouTube
- `VIMEO` - Vimeo
- `KINOPOISK` - Кинопоиск
- `KINOPOISK_WIDGET` - Кинопоиск-виджет
- `UNKNOWN` - Неизвестный сайт

## Методы

### getDisplayName()

Получает человекочитаемое название сайта.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название сайта на русском языке

#### Пример использования

```php
echo VideoSite::YOUTUBE->getDisplayName(); // "YouTube"
echo VideoSite::VIMEO->getDisplayName(); // "Vimeo"
echo VideoSite::KINOPOISK->getDisplayName(); // "Кинопоиск"
```

### isExternal()

Проверяет, является ли сайт внешним (не Kinopoisk).

```php
public function isExternal(): bool
```

#### Возвращаемое значение

- `bool` - `true` если внешний сайт, `false` если Kinopoisk

#### Пример использования

```php
if (VideoSite::YOUTUBE->isExternal()) {
    echo "Внешний сайт";
}

if (!VideoSite::KINOPOISK->isExternal()) {
    echo "Внутренний сайт Кинопоиска";
}
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\VideoSite;

// Создание клиента
$client = new Client('your-api-key');

// Получение видео фильма
$filmService = $client->films;
$videos = $filmService->getVideos(301); // Матрица

echo "=== Анализ видео по платформам ===\n";

// Группировка по платформам
$videosBySite = [];
foreach ($videos as $video) {
    $siteKey = $video->site->value;
    if (!isset($videosBySite[$siteKey])) {
        $videosBySite[$siteKey] = [];
    }
    $videosBySite[$siteKey][] = $video;
}

// Вывод статистики по платформам
foreach ($videosBySite as $site => $siteVideos) {
    $videoSite = VideoSite::from($site);
    echo "\n🎬 {$videoSite->getDisplayName()} (" . count($siteVideos) . " видео):\n";

    // Проверка типа платформы
    if ($videoSite->isExternal()) {
        echo "  📤 Внешняя платформа\n";
    } else {
        echo "  🏠 Внутренняя платформа Кинопоиска\n";
    }

    // Вывод примеров видео
    foreach (array_slice($siteVideos, 0, 3) as $index => $video) {
        echo "  " . ($index + 1) . ". {$video->name}\n";
    }

    if (count($siteVideos) > 3) {
        echo "  ... и еще " . (count($siteVideos) - 3) . " видео\n";
    }
}
```

## Работа с платформами

```php
// Функция для фильтрации видео по типу платформы
function filterVideosByPlatformType(array $videos, bool $external = true): array {
    return array_filter($videos, function($video) use ($external) {
        return $video->site->isExternal() === $external;
    });
}

// Функция для получения статистики по платформам
function getVideoSiteStats(array $videos): array {
    $stats = [
        'total' => count($videos),
        'sites' => [],
        'external' => 0,
        'internal' => 0
    ];

    foreach ($videos as $video) {
        $siteKey = $video->site->value;

        // Статистика по сайтам
        if (!isset($stats['sites'][$siteKey])) {
            $stats['sites'][$siteKey] = [
                'site' => $video->site,
                'count' => 0,
                'external' => $video->site->isExternal()
            ];
        }
        $stats['sites'][$siteKey]['count']++;

        // Подсчет внешних/внутренних
        if ($video->site->isExternal()) {
            $stats['external']++;
        } else {
            $stats['internal']++;
        }
    }

    return $stats;
}

// Функция для создания отчета по платформам
function createPlatformReport(array $videos): string {
    $stats = getVideoSiteStats($videos);

    $report = "=== ОТЧЕТ ПО ПЛАТФОРМАМ ===\n\n";
    $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
    $report .= "Всего видео: {$stats['total']}\n";
    $report .= "Внешних платформ: {$stats['external']}\n";
    $report .= "Внутренних платформ: {$stats['internal']}\n\n";

    $report .= "🎬 ПО ПЛАТФОРМАМ:\n";
    foreach ($stats['sites'] as $siteKey => $siteData) {
        $platformType = $siteData['external'] ? 'Внешняя' : 'Внутренняя';
        $report .= "• {$siteData['site']->getDisplayName()}: {$siteData['count']} видео ({$platformType})\n";
    }

    return $report;
}

// Использование
$videos = $filmService->getVideos(301);

// Фильтрация по типу платформы
$externalVideos = filterVideosByPlatformType($videos, true);
$internalVideos = filterVideosByPlatformType($videos, false);

echo "Внешних видео: " . count($externalVideos) . "\n";
echo "Внутренних видео: " . count($internalVideos) . "\n";

// Получение статистики
$stats = getVideoSiteStats($videos);
echo "Всего видео: {$stats['total']}\n";
echo "Внешних платформ: {$stats['external']}\n";
echo "Внутренних платформ: {$stats['internal']}\n";

// Создание отчета
$report = createPlatformReport($videos);
echo $report;
```

## Анализ платформ

```php
function analyzeVideoPlatforms(array $videos): array {
    $analysis = [
        'total' => count($videos),
        'platforms' => [],
        'externalCount' => 0,
        'internalCount' => 0,
        'mostPopular' => null,
        'diversity' => 0
    ];

    $platformCounts = [];

    foreach ($videos as $video) {
        $siteKey = $video->site->value;

        // Подсчет по платформам
        if (!isset($platformCounts[$siteKey])) {
            $platformCounts[$siteKey] = 0;
        }
        $platformCounts[$siteKey]++;

        // Подсчет внешних/внутренних
        if ($video->site->isExternal()) {
            $analysis['externalCount']++;
        } else {
            $analysis['internalCount']++;
        }
    }

    // Анализ платформ
    foreach ($platformCounts as $siteKey => $count) {
        $videoSite = VideoSite::from($siteKey);
        $analysis['platforms'][$siteKey] = [
            'site' => $videoSite,
            'displayName' => $videoSite->getDisplayName(),
            'count' => $count,
            'percentage' => round(($count / $analysis['total']) * 100, 1),
            'external' => $videoSite->isExternal()
        ];
    }

    // Самая популярная платформа
    if (!empty($platformCounts)) {
        $mostPopularKey = array_keys($platformCounts, max($platformCounts))[0];
        $analysis['mostPopular'] = VideoSite::from($mostPopularKey);
    }

    // Разнообразие платформ
    $analysis['diversity'] = count($platformCounts);

    return $analysis;
}

// Использование
$videos = $filmService->getVideos(301);
$analysis = analyzeVideoPlatforms($videos);

echo "=== Анализ платформ ===\n";
echo "Всего видео: {$analysis['total']}\n";
echo "Внешних платформ: {$analysis['externalCount']}\n";
echo "Внутренних платформ: {$analysis['internalCount']}\n";
echo "Разнообразие платформ: {$analysis['diversity']}\n";

if ($analysis['mostPopular']) {
    echo "Самая популярная платформа: {$analysis['mostPopular']->getDisplayName()}\n";
}

echo "\nДетальная статистика:\n";
foreach ($analysis['platforms'] as $siteKey => $platformData) {
    $type = $platformData['external'] ? 'Внешняя' : 'Внутренняя';
    echo "• {$platformData['displayName']}: {$platformData['count']} видео ({$platformData['percentage']}%) - {$type}\n";
}
```

## Создание HTML галереи по платформам

```php
function createPlatformGallery(array $videos): string {
    $html = "<!DOCTYPE html>\n<html>\n<head>\n";
    $html .= "<title>Видео по платформам</title>\n";
    $html .= "<style>\n";
    $html .= ".gallery { max-width: 1200px; margin: 0 auto; padding: 20px; }\n";
    $html .= ".platform-section { margin-bottom: 40px; }\n";
    $html .= ".platform-title { font-size: 24px; font-weight: bold; margin-bottom: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 8px; }\n";
    $html .= ".video-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }\n";
    $html .= ".video-item { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }\n";
    $html .= ".video-title { font-weight: bold; margin-bottom: 10px; }\n";
    $html .= ".video-link { color: #007bff; text-decoration: none; }\n";
    $html .= ".video-link:hover { text-decoration: underline; }\n";
    $html .= ".external { border-left: 5px solid #28a745; }\n";
    $html .= ".internal { border-left: 5px solid #007bff; }\n";
    $html .= "</style>\n</head>\n<body>\n";
    $html .= "<div class='gallery'>\n";
    $html .= "<h1>Видео по платформам</h1>\n";

    // Группировка по платформам
    $videosBySite = [];
    foreach ($videos as $video) {
        $siteKey = $video->site->value;
        if (!isset($videosBySite[$siteKey])) {
            $videosBySite[$siteKey] = [];
        }
        $videosBySite[$siteKey][] = $video;
    }

    // Создание секций для каждой платформы
    foreach ($videosBySite as $site => $siteVideos) {
        $videoSite = VideoSite::from($site);
        $cssClass = $videoSite->isExternal() ? 'external' : 'internal';
        $platformType = $videoSite->isExternal() ? 'Внешняя платформа' : 'Внутренняя платформа';

        $html .= "<div class='platform-section'>\n";
        $html .= "<div class='platform-title'>{$videoSite->getDisplayName()} ({$platformType}) - " . count($siteVideos) . " видео</div>\n";
        $html .= "<div class='video-grid'>\n";

        foreach ($siteVideos as $video) {
            $html .= "<div class='video-item {$cssClass}'>\n";
            $html .= "<div class='video-title'>{$video->name}</div>\n";
            $html .= "<a href='{$video->url}' class='video-link' target='_blank'>Смотреть видео</a>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n";
    }

    $html .= "</div>\n</body>\n</html>";

    return $html;
}

// Использование
$videos = $filmService->getVideos(301);
$html = createPlatformGallery($videos);

// Сохранение HTML файла
file_put_contents('video_platforms.html', $html);
echo "✅ HTML галерея по платформам сохранена в video_platforms.html\n";
```

## Валидация платформ

```php
function validateVideoPlatform(VideoSite $site): array {
    $validation = [
        'valid' => true,
        'warnings' => [],
        'recommendations' => []
    ];

    // Проверка известности платформы
    if ($site === VideoSite::UNKNOWN) {
        $validation['warnings'][] = 'Неизвестная платформа';
        $validation['recommendations'][] = 'Рекомендуется проверить корректность данных';
    }

    // Проверка типа платформы
    if ($site->isExternal()) {
        $validation['recommendations'][] = 'Внешняя платформа - может потребоваться дополнительная обработка';
    } else {
        $validation['recommendations'][] = 'Внутренняя платформа Кинопоиска - стабильный источник';
    }

    // Специфичные проверки для каждой платформы
    switch ($site) {
        case VideoSite::YOUTUBE:
            $validation['recommendations'][] = 'YouTube - популярная платформа с широкой поддержкой';
            break;
        case VideoSite::VIMEO:
            $validation['recommendations'][] = 'Vimeo - качественные видео, может потребовать авторизации';
            break;
        case VideoSite::KINOPOISK:
            $validation['recommendations'][] = 'Кинопоиск - официальный источник, высокая надежность';
            break;
        case VideoSite::KINOPOISK_WIDGET:
            $validation['recommendations'][] = 'Кинопоиск-виджет - встроенный плеер';
            break;
    }

    return $validation;
}

// Использование
$videos = $filmService->getVideos(301);

foreach ($videos as $index => $video) {
    $validation = validateVideoPlatform($video->site);

    echo "Видео " . ($index + 1) . " - {$video->site->getDisplayName()}:\n";

    if (!empty($validation['warnings'])) {
        foreach ($validation['warnings'] as $warning) {
            echo "  ⚠️  {$warning}\n";
        }
    }

    foreach ($validation['recommendations'] as $recommendation) {
        echo "  💡 {$recommendation}\n";
    }

    echo "\n";
}
```

## Связанные классы

- [`Video`](../models/video.md) - Модель видео
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](../models/film.md) - Модель фильма
