# Video

Модель видео из Kinopoisk API.

## Описание

`Video` представляет информацию о видео материале, связанном с фильмом: трейлеры, клипы, закулисные материалы и другие видео.

### Основные возможности

- Хранение информации о видео в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к URL, названию и платформе видео

## Свойства

### Основная информация

- `$url` (string) - URL видео
- `$name` (string) - Название видео
- `$site` (VideoSite) - Платформа или сайт, где размещено видео

## Конструктор

```php
public function __construct(
    public readonly string $url,
    public readonly string $name,
    public readonly VideoSite $site,
)
```

### Пример создания

```php
$video = new Video(
    url: 'https://youtube.com/watch?v=...',
    name: 'Трейлер фильма',
    site: VideoSite::YOUTUBE
);
```

## Методы

### fromArray()

Создает экземпляр видео из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных видео от API

#### Возвращаемое значение

- `self` - Новый экземпляр видео

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'url' => 'https://youtube.com/watch?v=...',
    'name' => 'Трейлер фильма',
    'site' => 'YOUTUBE'
];

$video = Video::fromArray($apiData);
```

### toArray()

Преобразует объект видео в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными видео

#### Пример использования

```php
$videoArray = $video->toArray();
echo json_encode($videoArray); // JSON представление видео
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Video;
use NotKinopoisk\Enums\VideoSite;

// Создание клиента
$client = new Client('your-api-key');

// Получение видео фильма
$filmService = $client->films;
$videos = $filmService->getVideos(301); // Матрица

echo "=== Видео фильма 'Матрица' ===\n";

// Группировка по платформам
$videosBySite = [];
foreach ($videos as $video) {
    $siteKey = $video->site->value;
    if (!isset($videosBySite[$siteKey])) {
        $videosBySite[$siteKey] = [];
    }
    $videosBySite[$siteKey][] = $video;
}

// Вывод видео по платформам
foreach ($videosBySite as $site => $siteVideos) {
    echo "\n🎬 {$video->site->getDisplayName()} (" . count($siteVideos) . " видео):\n";

    foreach (array_slice($siteVideos, 0, 5) as $index => $video) {
        echo ($index + 1) . ". {$video->name}\n";
        echo "   URL: {$video->url}\n";
        echo "   ---\n";
    }

    if (count($siteVideos) > 5) {
        echo "... и еще " . (count($siteVideos) - 5) . " видео\n";
    }
}
```

## Работа с видео

```php
// Функция для фильтрации видео по платформе
function filterVideosBySite(array $videos, VideoSite $site): array {
    return array_filter($videos, fn($video) => $video->site === $site);
}

// Функция для поиска видео по названию
function searchVideosByName(array $videos, string $keyword): array {
    $keyword = strtolower($keyword);

    return array_filter($videos, function($video) use ($keyword) {
        return strpos(strtolower($video->name), $keyword) !== false;
    });
}

// Функция для получения всех платформ
function getVideoSites(array $videos): array {
    $sites = [];
    foreach ($videos as $video) {
        $siteKey = $video->site->value;
        if (!isset($sites[$siteKey])) {
            $sites[$siteKey] = [
                'site' => $video->site,
                'count' => 0,
                'videos' => []
            ];
        }
        $sites[$siteKey]['count']++;
        $sites[$siteKey]['videos'][] = $video;
    }

    return $sites;
}

// Использование
$videos = $filmService->getVideos(301);

// Фильтрация по платформе
$youtubeVideos = filterVideosBySite($videos, VideoSite::YOUTUBE);
echo "Видео на YouTube: " . count($youtubeVideos) . "\n";

// Поиск по названию
$trailerVideos = searchVideosByName($videos, 'трейлер');
echo "Видео с 'трейлер' в названии: " . count($trailerVideos) . "\n";

// Получение всех платформ
$sites = getVideoSites($videos);
echo "Платформы с видео:\n";
foreach ($sites as $siteKey => $siteData) {
    echo "- {$siteData['site']->getDisplayName()}: {$siteData['count']} видео\n";
}
```

## Создание плейлиста

```php
class VideoPlaylist {
    private array $videos;

    public function __construct(array $videos) {
        $this->videos = $videos;
    }

    public function getBySite(VideoSite $site): array {
        return filterVideosBySite($this->videos, $site);
    }

    public function getTrailers(): array {
        return searchVideosByName($this->videos, 'трейлер');
    }

    public function getClips(): array {
        return searchVideosByName($this->videos, 'клип');
    }

    public function getBehindTheScenes(): array {
        return searchVideosByName($this->videos, 'закулисье');
    }

    public function createHtmlPlaylist(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".playlist { max-width: 800px; margin: 0 auto; padding: 20px; }\n";
        $html .= ".video-item { margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; }\n";
        $html .= ".video-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }\n";
        $html .= ".video-platform { color: #666; font-size: 14px; }\n";
        $html .= ".video-link { color: #007bff; text-decoration: none; }\n";
        $html .= ".video-link:hover { text-decoration: underline; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='playlist'>\n";
        $html .= "<h1>{$title}</h1>\n";

        foreach ($this->videos as $index => $video) {
            $html .= "<div class='video-item'>\n";
            $html .= "<div class='video-title'>" . ($index + 1) . ". {$video->name}</div>\n";
            $html .= "<div class='video-platform'>Платформа: {$video->site->getDisplayName()}</div>\n";
            $html .= "<a href='{$video->url}' class='video-link' target='_blank'>Смотреть видео</a>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }

    public function getStatistics(): array {
        $stats = [
            'total' => count($this->videos),
            'sites' => [],
            'trailers' => 0,
            'clips' => 0,
            'behindScenes' => 0
        ];

        $sites = getVideoSites($this->videos);
        foreach ($sites as $siteKey => $siteData) {
            $stats['sites'][$siteKey] = $siteData['count'];
        }

        $stats['trailers'] = count($this->getTrailers());
        $stats['clips'] = count($this->getClips());
        $stats['behindScenes'] = count($this->getBehindTheScenes());

        return $stats;
    }
}

// Использование
$videos = $filmService->getVideos(301);
$playlist = new VideoPlaylist($videos);

// Получение статистики
$stats = $playlist->getStatistics();
echo "=== Статистика видео ===\n";
echo "Всего видео: {$stats['total']}\n";
echo "Трейлеров: {$stats['trailers']}\n";
echo "Клипов: {$stats['clips']}\n";
echo "Закулисных материалов: {$stats['behindScenes']}\n";

echo "\nПо платформам:\n";
foreach ($stats['sites'] as $site => $count) {
    echo "- {$site}: {$count} видео\n";
}

// Создание HTML плейлиста
$html = $playlist->createHtmlPlaylist('Видео фильма "Матрица"');
file_put_contents('matrix_videos.html', $html);
echo "\n✅ HTML плейлист сохранен в matrix_videos.html\n";
```

## Анализ видео контента

```php
function analyzeVideoContent(array $videos): array {
    $analysis = [
        'total' => count($videos),
        'sites' => [],
        'namePatterns' => [],
        'avgNameLength' => 0,
        'mostCommonWords' => []
    ];

    $totalNameLength = 0;
    $allWords = [];
    $siteStats = [];

    foreach ($videos as $video) {
        // Статистика по платформам
        $siteKey = $video->site->value;
        if (!isset($siteStats[$siteKey])) {
            $siteStats[$siteKey] = 0;
        }
        $siteStats[$siteKey]++;

        // Анализ названий
        $totalNameLength += strlen($video->name);

        // Разбор слов в названии
        $words = preg_split('/\s+/', strtolower($video->name));
        foreach ($words as $word) {
            $word = trim($word, '.,!?()[]{}"\'-');
            if (strlen($word) > 2) {
                if (!isset($allWords[$word])) {
                    $allWords[$word] = 0;
                }
                $allWords[$word]++;
            }
        }

        // Паттерны в названиях
        $patterns = [
            'трейлер' => 'Трейлеры',
            'клип' => 'Клипы',
            'закулисье' => 'Закулисные материалы',
            'интервью' => 'Интервью',
            'превью' => 'Превью',
            'съёмки' => 'Материалы со съёмок'
        ];

        foreach ($patterns as $pattern => $category) {
            if (stripos($video->name, $pattern) !== false) {
                if (!isset($analysis['namePatterns'][$category])) {
                    $analysis['namePatterns'][$category] = 0;
                }
                $analysis['namePatterns'][$category]++;
            }
        }
    }

    // Средняя длина названия
    if ($analysis['total'] > 0) {
        $analysis['avgNameLength'] = round($totalNameLength / $analysis['total']);
    }

    // Статистика по платформам
    $analysis['sites'] = $siteStats;

    // Самые частые слова
    arsort($allWords);
    $analysis['mostCommonWords'] = array_slice($allWords, 0, 10, true);

    return $analysis;
}

// Использование
$videos = $filmService->getVideos(301);
$analysis = analyzeVideoContent($videos);

echo "=== Анализ видео контента ===\n";
echo "Всего видео: {$analysis['total']}\n";
echo "Средняя длина названия: {$analysis['avgNameLength']} символов\n";

echo "\nПо типам контента:\n";
foreach ($analysis['namePatterns'] as $type => $count) {
    echo "- {$type}: {$count}\n";
}

echo "\nПо платформам:\n";
foreach ($analysis['sites'] as $site => $count) {
    echo "- {$site}: {$count} видео\n";
}

echo "\nСамые частые слова в названиях:\n";
foreach ($analysis['mostCommonWords'] as $word => $count) {
    echo "- '{$word}': {$count} раз\n";
}
```

## Валидация видео

```php
function validateVideo(Video $video): array {
    $validation = [
        'valid' => true,
        'errors' => [],
        'warnings' => []
    ];

    // Проверка URL
    if (empty($video->url)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'URL видео пустой';
    } elseif (!filter_var($video->url, FILTER_VALIDATE_URL)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'Некорректный URL видео';
    }

    // Проверка названия
    if (empty($video->name)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'Название видео пустое';
    }

    // Проверка платформы
    if (!in_array($video->site, VideoSite::cases())) {
        $validation['warnings'][] = 'Неизвестная платформа: ' . $video->site->value;
    }

    // Проверка доступности видео
    if ($validation['valid']) {
        $headers = @get_headers($video->url);
        if (!$headers || strpos($headers[0], '200') === false) {
            $validation['warnings'][] = 'Видео недоступно по указанному URL';
        }
    }

    return $validation;
}

// Использование
$videos = $filmService->getVideos(301);

foreach ($videos as $index => $video) {
    $validation = validateVideo($video);

    if (!$validation['valid']) {
        echo "❌ Видео " . ($index + 1) . " невалидно:\n";
        foreach ($validation['errors'] as $error) {
            echo "  - {$error}\n";
        }
    } elseif (!empty($validation['warnings'])) {
        echo "⚠️  Видео " . ($index + 1) . " имеет предупреждения:\n";
        foreach ($validation['warnings'] as $warning) {
            echo "  - {$warning}\n";
        }
    } else {
        echo "✅ Видео " . ($index + 1) . " валидно\n";
    }
}
```

## Связанные классы

- [`VideoSite`](../enums/video-site.md) - Платформы для видео
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](film.md) - Модель фильма
