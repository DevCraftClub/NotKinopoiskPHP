# Episode

Модель эпизода сериала из Kinopoisk API.

## Описание

`Episode` представляет информацию об отдельном эпизоде сериала, включая номер сезона, номер эпизода, название, синопсис и дату выхода.

### Основные возможности

- Хранение информации об эпизоде в неизменяемом виде
- Создание объекта из массива данных API
- Получение отображаемого названия эпизода
- Поддержка многоязычных названий

## Свойства

### Основная информация

- `$seasonNumber` (int) - Номер сезона
- `$episodeNumber` (int) - Номер эпизода в сезоне
- `$nameRu` (string|null) - Название эпизода на русском языке
- `$nameEn` (string|null) - Название эпизода на английском языке
- `$synopsis` (string|null) - Краткое описание сюжета эпизода
- `$releaseDate` (string|null) - Дата выхода эпизода

## Конструктор

```php
public function __construct(
    public readonly int $seasonNumber,
    public readonly int $episodeNumber,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $synopsis,
    public readonly ?string $releaseDate,
)
```

### Пример создания

```php
$episode = new Episode(
    seasonNumber: 1,
    episodeNumber: 1,
    nameRu: 'Пилот',
    nameEn: 'Pilot',
    synopsis: 'Первый эпизод сериала...',
    releaseDate: '2023-01-15'
);
```

## Методы

### fromArray()

Создает экземпляр эпизода из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных эпизода от API

#### Возвращаемое значение

- `self` - Новый экземпляр эпизода

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'seasonNumber' => 1,
    'episodeNumber' => 1,
    'nameRu' => 'Пилот',
    'nameEn' => 'Pilot',
    'synopsis' => 'Первый эпизод сериала...',
    'releaseDate' => '2023-01-15'
];

$episode = Episode::fromArray($apiData);
```

### getDisplayName()

Получает отображаемое название эпизода.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название эпизода

#### Описание

Возвращает наиболее подходящее название для отображения пользователю. Приоритет: русское название → английское название → "Эпизод N"

#### Пример использования

```php
echo $episode->getDisplayName(); // "Пилот" или "Pilot" или "Эпизод 1"
```

### toArray()

Преобразует объект эпизода в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными эпизода

#### Пример использования

```php
$episodeArray = $episode->toArray();
echo json_encode($episodeArray); // JSON представление эпизода
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Episode;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о сериале
$filmService = $client->films;
$seasons = $filmService->getSeasons(123); // ID сериала

echo "=== Эпизоды сериала ===\n";

// Обработка всех сезонов и эпизодов
foreach ($seasons as $season) {
    echo "\n📺 Сезон {$season->number} ({$season->episodesCount} эпизодов):\n";

    if (!empty($season->episodes)) {
        foreach ($season->episodes as $episode) {
            echo "  • S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()}\n";

            if ($episode->releaseDate) {
                echo "    Дата выхода: {$episode->releaseDate}\n";
            }

            if ($episode->synopsis) {
                echo "    Синопсис: " . substr($episode->synopsis, 0, 100) . "...\n";
            }
        }
    }
}
```

## Работа с эпизодами

```php
// Функция для фильтрации эпизодов по сезону
function filterEpisodesBySeason(array $episodes, int $seasonNumber): array {
    return array_filter($episodes, fn($episode) => $episode->seasonNumber === $seasonNumber);
}

// Функция для поиска эпизодов по названию
function searchEpisodesByName(array $episodes, string $searchTerm): array {
    $searchTerm = strtolower($searchTerm);
    return array_filter($episodes, function($episode) use ($searchTerm) {
        $nameRu = strtolower($episode->nameRu ?? '');
        $nameEn = strtolower($episode->nameEn ?? '');
        return str_contains($nameRu, $searchTerm) || str_contains($nameEn, $searchTerm);
    });
}

// Функция для получения статистики по эпизодам
function getEpisodesStatistics(array $episodes): array {
    $stats = [
        'totalEpisodes' => count($episodes),
        'seasons' => [],
        'episodesWithSynopsis' => 0,
        'episodesWithReleaseDate' => 0,
        'episodesWithRussianName' => 0,
        'episodesWithEnglishName' => 0
    ];

    foreach ($episodes as $episode) {
        // Статистика по сезонам
        $seasonKey = $episode->seasonNumber;
        if (!isset($stats['seasons'][$seasonKey])) {
            $stats['seasons'][$seasonKey] = 0;
        }
        $stats['seasons'][$seasonKey]++;

        // Подсчет эпизодов с различными данными
        if ($episode->synopsis) $stats['episodesWithSynopsis']++;
        if ($episode->releaseDate) $stats['episodesWithReleaseDate']++;
        if ($episode->nameRu) $stats['episodesWithRussianName']++;
        if ($episode->nameEn) $stats['episodesWithEnglishName']++;
    }

    ksort($stats['seasons']);
    return $stats;
}

// Функция для получения эпизодов в хронологическом порядке
function getEpisodesInChronologicalOrder(array $episodes): array {
    $sorted = $episodes;
    usort($sorted, function($a, $b) {
        // Сначала по номеру сезона, затем по номеру эпизода
        if ($a->seasonNumber !== $b->seasonNumber) {
            return $a->seasonNumber <=> $b->seasonNumber;
        }
        return $a->episodeNumber <=> $b->episodeNumber;
    });
    return $sorted;
}

// Функция для получения последних эпизодов
function getLatestEpisodes(array $episodes, int $limit = 10): array {
    $withDates = array_filter($episodes, fn($episode) => $episode->releaseDate !== null);

    usort($withDates, function($a, $b) {
        return strcmp($b->releaseDate, $a->releaseDate);
    });

    return array_slice($withDates, 0, $limit);
}

// Использование
$seasons = $filmService->getSeasons(123);
$allEpisodes = [];

// Сбор всех эпизодов
foreach ($seasons as $season) {
    if (!empty($season->episodes)) {
        $allEpisodes = array_merge($allEpisodes, $season->episodes);
    }
}

// Фильтрация по сезону
$season1Episodes = filterEpisodesBySeason($allEpisodes, 1);
echo "Эпизодов в 1 сезоне: " . count($season1Episodes) . "\n";

// Поиск по названию
$pilotEpisodes = searchEpisodesByName($allEpisodes, 'пилот');
echo "Эпизодов с 'пилот' в названии: " . count($pilotEpisodes) . "\n";

// Статистика
$stats = getEpisodesStatistics($allEpisodes);
echo "Всего эпизодов: {$stats['totalEpisodes']}\n";
echo "Эпизодов с синопсисом: {$stats['episodesWithSynopsis']}\n";
echo "Эпизодов с датой выхода: {$stats['episodesWithReleaseDate']}\n";

// Последние эпизоды
$latestEpisodes = getLatestEpisodes($allEpisodes, 5);
echo "Последние 5 эпизодов:\n";
foreach ($latestEpisodes as $episode) {
    echo "- S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()} ({$episode->releaseDate})\n";
}
```

## Создание отчета по эпизодам

```php
class EpisodesReport {
    private array $episodes;

    public function __construct(array $episodes) {
        $this->episodes = $episodes;
    }

    public function getEpisodesBySeason(int $seasonNumber): array {
        return filterEpisodesBySeason($this->episodes, $seasonNumber);
    }

    public function getStatistics(): array {
        return getEpisodesStatistics($this->episodes);
    }

    public function getChronologicalEpisodes(): array {
        return getEpisodesInChronologicalOrder($this->episodes);
    }

    public function getLatestEpisodes(int $limit = 10): array {
        return getLatestEpisodes($this->episodes, $limit);
    }

    public function getEpisodesWithSynopsis(): array {
        return array_filter($this->episodes, fn($episode) => $episode->synopsis !== null);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ЭПИЗОДАМ ===\n\n";

        $stats = $this->getStatistics();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего эпизодов: {$stats['totalEpisodes']}\n";
        $report .= "Эпизодов с синопсисом: {$stats['episodesWithSynopsis']}\n";
        $report .= "Эпизодов с датой выхода: {$stats['episodesWithReleaseDate']}\n";
        $report .= "Эпизодов с русским названием: {$stats['episodesWithRussianName']}\n";
        $report .= "Эпизодов с английским названием: {$stats['episodesWithEnglishName']}\n\n";

        // Статистика по сезонам
        $report .= "📺 СТАТИСТИКА ПО СЕЗОНАМ:\n";
        foreach ($stats['seasons'] as $seasonNumber => $episodeCount) {
            $report .= "• Сезон {$seasonNumber}: {$episodeCount} эпизодов\n";
        }

        // Последние эпизоды
        $latestEpisodes = $this->getLatestEpisodes(5);
        if (!empty($latestEpisodes)) {
            $report .= "\n🆕 ПОСЛЕДНИЕ ЭПИЗОДЫ:\n";
            foreach ($latestEpisodes as $episode) {
                $report .= "• S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()} ({$episode->releaseDate})\n";
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
        $html .= ".episode-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".episode-header { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".episode-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }\n";
        $html .= ".episode-detail { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".season-section { margin-bottom: 25px; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getStatistics();
        $chronologicalEpisodes = $this->getChronologicalEpisodes();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего эпизодов:</strong> {$stats['totalEpisodes']}</p>\n";
        $html .= "<p><strong>Эпизодов с синопсисом:</strong> {$stats['episodesWithSynopsis']}</p>\n";
        $html .= "<p><strong>Эпизодов с датой выхода:</strong> {$stats['episodesWithReleaseDate']}</p>\n";
        $html .= "<p><strong>Эпизодов с русским названием:</strong> {$stats['episodesWithRussianName']}</p>\n";
        $html .= "<p><strong>Эпизодов с английским названием:</strong> {$stats['episodesWithEnglishName']}</p>\n";
        $html .= "</div>\n";

        // Эпизоды по сезонам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Эпизоды по сезонам</div>\n";

        $currentSeason = null;
        foreach ($chronologicalEpisodes as $episode) {
            if ($currentSeason !== $episode->seasonNumber) {
                if ($currentSeason !== null) {
                    $html .= "</div>\n";
                }
                $currentSeason = $episode->seasonNumber;
                $seasonEpisodes = $this->getEpisodesBySeason($currentSeason);
                $html .= "<div class='season-section'>\n";
                $html .= "<h3>Сезон {$currentSeason} (" . count($seasonEpisodes) . " эпизодов)</h3>\n";
            }

            $html .= "<div class='episode-item'>\n";
            $html .= "<div class='episode-header'>S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()}</div>\n";
            $html .= "<div class='episode-details'>\n";

            if ($episode->nameRu && $episode->nameEn) {
                $html .= "<div class='episode-detail'><strong>Названия:</strong> {$episode->nameRu} / {$episode->nameEn}</div>\n";
            } elseif ($episode->nameRu) {
                $html .= "<div class='episode-detail'><strong>Название:</strong> {$episode->nameRu}</div>\n";
            } elseif ($episode->nameEn) {
                $html .= "<div class='episode-detail'><strong>Название:</strong> {$episode->nameEn}</div>\n";
            }

            if ($episode->releaseDate) {
                $html .= "<div class='episode-detail'><strong>Дата выхода:</strong> {$episode->releaseDate}</div>\n";
            }

            if ($episode->synopsis) {
                $html .= "<div class='episode-detail'><strong>Синопсис:</strong> " . htmlspecialchars(substr($episode->synopsis, 0, 150)) . "...</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        if ($currentSeason !== null) {
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$seasons = $filmService->getSeasons(123);
$allEpisodes = [];

foreach ($seasons as $season) {
    if (!empty($season->episodes)) {
        $allEpisodes = array_merge($allEpisodes, $season->episodes);
    }
}

$report = new EpisodesReport($allEpisodes);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по эпизодам сериала');
file_put_contents('episodes_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в episodes_report.html\n";
```

## Анализ эпизодов

```php
function analyzeEpisodes(array $episodes): array {
    $analysis = [
        'totalEpisodes' => count($episodes),
        'seasons' => [],
        'episodesPerSeason' => [],
        'averageEpisodesPerSeason' => 0,
        'episodesWithData' => [
            'synopsis' => 0,
            'releaseDate' => 0,
            'nameRu' => 0,
            'nameEn' => 0
        ],
        'releaseDateRange' => [
            'earliest' => null,
            'latest' => null
        ],
        'seasonDistribution' => []
    ];

    $seasonStats = [];
    $releaseDates = [];

    foreach ($episodes as $episode) {
        // Статистика по сезонам
        $seasonKey = $episode->seasonNumber;
        if (!isset($seasonStats[$seasonKey])) {
            $seasonStats[$seasonKey] = 0;
        }
        $seasonStats[$seasonKey]++;

        // Подсчет эпизодов с данными
        if ($episode->synopsis) $analysis['episodesWithData']['synopsis']++;
        if ($episode->releaseDate) {
            $analysis['episodesWithData']['releaseDate']++;
            $releaseDates[] = $episode->releaseDate;
        }
        if ($episode->nameRu) $analysis['episodesWithData']['nameRu']++;
        if ($episode->nameEn) $analysis['episodesWithData']['nameEn']++;
    }

    // Анализ сезонов
    $analysis['seasons'] = array_keys($seasonStats);
    $analysis['episodesPerSeason'] = $seasonStats;
    $analysis['averageEpisodesPerSeason'] = count($episodes) / count($seasonStats);

    // Анализ дат выпуска
    if (!empty($releaseDates)) {
        sort($releaseDates);
        $analysis['releaseDateRange']['earliest'] = $releaseDates[0];
        $analysis['releaseDateRange']['latest'] = end($releaseDates);
    }

    // Распределение по сезонам
    foreach ($seasonStats as $season => $count) {
        $analysis['seasonDistribution'][$season] = round(($count / count($episodes)) * 100, 1);
    }

    return $analysis;
}

// Использование
$seasons = $filmService->getSeasons(123);
$allEpisodes = [];

foreach ($seasons as $season) {
    if (!empty($season->episodes)) {
        $allEpisodes = array_merge($allEpisodes, $season->episodes);
    }
}

$analysis = analyzeEpisodes($allEpisodes);

echo "=== Анализ эпизодов ===\n";
echo "Всего эпизодов: {$analysis['totalEpisodes']}\n";
echo "Количество сезонов: " . count($analysis['seasons']) . "\n";
echo "Среднее количество эпизодов на сезон: " . round($analysis['averageEpisodesPerSeason'], 1) . "\n";

echo "\nЭпизоды с данными:\n";
foreach ($analysis['episodesWithData'] as $type => $count) {
    $percentage = round(($count / $analysis['totalEpisodes']) * 100, 1);
    echo "- {$type}: {$count} ({$percentage}%)\n";
}

if ($analysis['releaseDateRange']['earliest']) {
    echo "\nДиапазон дат выпуска:\n";
    echo "Самый ранний: {$analysis['releaseDateRange']['earliest']}\n";
    echo "Самый поздний: {$analysis['releaseDateRange']['latest']}\n";
}

echo "\nРаспределение по сезонам:\n";
foreach ($analysis['seasonDistribution'] as $season => $percentage) {
    echo "- Сезон {$season}: {$percentage}%\n";
}
```

## Связанные классы

- [`Season`](season.md) - Модель сезона
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
