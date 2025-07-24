# Season

Модель сезона сериала из Kinopoisk API.

## Описание

`Season` представляет информацию о сезоне сериала, включая номер сезона и массив эпизодов, входящих в этот сезон.

### Основные возможности

- Хранение информации о сезоне в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к эпизодам сезона

**API Endpoint:** `/api/v2.2/films/{id}/seasons`

## Свойства

### Основная информация

- `$number` (int) - Номер сезона
- `$episodes` (array) - Массив эпизодов сезона

## Конструктор

```php
public function __construct(
    public readonly int $number,
    public readonly array $episodes,
)
```

### Пример создания

```php
$season = new Season(
    number: 1,
    episodes: [$episode1, $episode2, $episode3]
);
```

## Методы

### fromArray()

Создает экземпляр сезона из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных сезона от API

#### Возвращаемое значение

- `self` - Новый экземпляр сезона

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Описание

Статический метод для удобного создания объекта Season из данных, полученных от Kinopoisk API. Автоматически создает объекты Episode для каждого эпизода в сезоне.

#### Пример использования

```php
$apiData = [
    'number' => 1,
    'episodes' => [
        ['number' => 1, 'name' => 'Пилот', ...],
        ['number' => 2, 'name' => 'Второй эпизод', ...]
    ]
];

$season = Season::fromArray($apiData);
```

### toArray()

Преобразует объект сезона в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными сезона

#### Пример использования

```php
$seasonArray = $season->toArray();
echo json_encode($seasonArray); // JSON представление сезона
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Season;

// Создание клиента
$client = new Client('your-api-key');

// Получение сезонов сериала
$filmService = $client->films;
$seasons = $filmService->getSeasons(123); // ID сериала

echo "=== Сезоны сериала ===\n";

// Обработка всех сезонов
foreach ($seasons as $season) {
    echo "\n📺 Сезон {$season->number} (" . count($season->episodes) . " эпизодов):\n";

    if (!empty($season->episodes)) {
        foreach ($season->episodes as $episode) {
            echo "  • S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()}\n";

            if ($episode->releaseDate) {
                echo "    Дата выхода: {$episode->releaseDate}\n";
            }
        }
    } else {
        echo "  Нет информации об эпизодах\n";
    }
}
```

## Работа с сезонами

```php
// Функция для получения сезона по номеру
function getSeasonByNumber(array $seasons, int $seasonNumber): ?Season {
    foreach ($seasons as $season) {
        if ($season->number === $seasonNumber) {
            return $season;
        }
    }
    return null;
}

// Функция для получения статистики по сезонам
function getSeasonsStatistics(array $seasons): array {
    $stats = [
        'totalSeasons' => count($seasons),
        'totalEpisodes' => 0,
        'episodesPerSeason' => [],
        'averageEpisodesPerSeason' => 0,
        'seasonsWithEpisodes' => 0,
        'seasonsWithoutEpisodes' => 0
    ];

    foreach ($seasons as $season) {
        $episodeCount = count($season->episodes);
        $stats['totalEpisodes'] += $episodeCount;
        $stats['episodesPerSeason'][$season->number] = $episodeCount;

        if ($episodeCount > 0) {
            $stats['seasonsWithEpisodes']++;
        } else {
            $stats['seasonsWithoutEpisodes']++;
        }
    }

    if ($stats['totalSeasons'] > 0) {
        $stats['averageEpisodesPerSeason'] = round($stats['totalEpisodes'] / $stats['totalSeasons'], 1);
    }

    return $stats;
}

// Функция для получения самого длинного сезона
function getLongestSeason(array $seasons): ?Season {
    $longestSeason = null;
    $maxEpisodes = 0;

    foreach ($seasons as $season) {
        $episodeCount = count($season->episodes);
        if ($episodeCount > $maxEpisodes) {
            $maxEpisodes = $episodeCount;
            $longestSeason = $season;
        }
    }

    return $longestSeason;
}

// Функция для получения сезонов с эпизодами
function getSeasonsWithEpisodes(array $seasons): array {
    return array_filter($seasons, fn($season) => !empty($season->episodes));
}

// Функция для получения всех эпизодов из всех сезонов
function getAllEpisodes(array $seasons): array {
    $allEpisodes = [];

    foreach ($seasons as $season) {
        $allEpisodes = array_merge($allEpisodes, $season->episodes);
    }

    return $allEpisodes;
}

// Использование
$seasons = $filmService->getSeasons(123);

// Получение конкретного сезона
$season1 = getSeasonByNumber($seasons, 1);
if ($season1) {
    echo "Сезон 1 содержит " . count($season1->episodes) . " эпизодов\n";
}

// Статистика по сезонам
$stats = getSeasonsStatistics($seasons);
echo "Всего сезонов: {$stats['totalSeasons']}\n";
echo "Всего эпизодов: {$stats['totalEpisodes']}\n";
echo "Среднее количество эпизодов на сезон: {$stats['averageEpisodesPerSeason']}\n";
echo "Сезонов с эпизодами: {$stats['seasonsWithEpisodes']}\n";
echo "Сезонов без эпизодов: {$stats['seasonsWithoutEpisodes']}\n";

// Самый длинный сезон
$longestSeason = getLongestSeason($seasons);
if ($longestSeason) {
    echo "Самый длинный сезон: {$longestSeason->number} (" . count($longestSeason->episodes) . " эпизодов)\n";
}

// Все эпизоды
$allEpisodes = getAllEpisodes($seasons);
echo "Всего эпизодов во всех сезонах: " . count($allEpisodes) . "\n";
```

## Создание отчета по сезонам

```php
class SeasonsReport {
    private array $seasons;

    public function __construct(array $seasons) {
        $this->seasons = $seasons;
    }

    public function getSeasonByNumber(int $seasonNumber): ?Season {
        return getSeasonByNumber($this->seasons, $seasonNumber);
    }

    public function getStatistics(): array {
        return getSeasonsStatistics($this->seasons);
    }

    public function getLongestSeason(): ?Season {
        return getLongestSeason($this->seasons);
    }

    public function getSeasonsWithEpisodes(): array {
        return getSeasonsWithEpisodes($this->seasons);
    }

    public function getAllEpisodes(): array {
        return getAllEpisodes($this->seasons);
    }

    public function getEpisodesBySeason(int $seasonNumber): array {
        $season = $this->getSeasonByNumber($seasonNumber);
        return $season ? $season->episodes : [];
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО СЕЗОНАМ ===\n\n";

        $stats = $this->getStatistics();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего сезонов: {$stats['totalSeasons']}\n";
        $report .= "Всего эпизодов: {$stats['totalEpisodes']}\n";
        $report .= "Среднее количество эпизодов на сезон: {$stats['averageEpisodesPerSeason']}\n";
        $report .= "Сезонов с эпизодами: {$stats['seasonsWithEpisodes']}\n";
        $report .= "Сезонов без эпизодов: {$stats['seasonsWithoutEpisodes']}\n\n";

        // Статистика по сезонам
        $report .= "📺 СТАТИСТИКА ПО СЕЗОНАМ:\n";
        foreach ($stats['episodesPerSeason'] as $seasonNumber => $episodeCount) {
            $report .= "• Сезон {$seasonNumber}: {$episodeCount} эпизодов\n";
        }

        // Самый длинный сезон
        $longestSeason = $this->getLongestSeason();
        if ($longestSeason) {
            $report .= "\n🏆 САМЫЙ ДЛИННЫЙ СЕЗОН:\n";
            $report .= "Сезон {$longestSeason->number}: " . count($longestSeason->episodes) . " эпизодов\n";
        }

        // Детали по сезонам
        $report .= "\n📋 ДЕТАЛИ ПО СЕЗОНАМ:\n";
        foreach ($this->seasons as $season) {
            $report .= "\nСезон {$season->number}:\n";
            $report .= "  Количество эпизодов: " . count($season->episodes) . "\n";

            if (!empty($season->episodes)) {
                $report .= "  Эпизоды:\n";
                foreach (array_slice($season->episodes, 0, 5) as $episode) {
                    $report .= "    • S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()}\n";
                }

                if (count($season->episodes) > 5) {
                    $report .= "    ... и еще " . (count($season->episodes) - 5) . " эпизодов\n";
                }
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
        $html .= ".season-item { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }\n";
        $html .= ".season-header { font-weight: bold; font-size: 18px; margin-bottom: 10px; color: #007bff; }\n";
        $html .= ".episode-item { margin-bottom: 8px; padding: 8px; background-color: #f8f9fa; border-radius: 4px; }\n";
        $html .= ".episode-title { font-weight: bold; }\n";
        $html .= ".episode-details { font-size: 14px; color: #666; margin-top: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".highlight { background-color: #fff3cd; border-left: 5px solid #ffc107; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getStatistics();
        $longestSeason = $this->getLongestSeason();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего сезонов:</strong> {$stats['totalSeasons']}</p>\n";
        $html .= "<p><strong>Всего эпизодов:</strong> {$stats['totalEpisodes']}</p>\n";
        $html .= "<p><strong>Среднее количество эпизодов на сезон:</strong> {$stats['averageEpisodesPerSeason']}</p>\n";
        $html .= "<p><strong>Сезонов с эпизодами:</strong> {$stats['seasonsWithEpisodes']}</p>\n";
        $html .= "<p><strong>Сезонов без эпизодов:</strong> {$stats['seasonsWithoutEpisodes']}</p>\n";
        $html .= "</div>\n";

        // Самый длинный сезон
        if ($longestSeason) {
            $html .= "<div class='season-item highlight'>\n";
            $html .= "<div class='season-header'>🏆 Самый длинный сезон: {$longestSeason->number}</div>\n";
            $html .= "<p>Количество эпизодов: " . count($longestSeason->episodes) . "</p>\n";
            $html .= "</div>\n";
        }

        // Детали по сезонам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Детали по сезонам</div>\n";

        foreach ($this->seasons as $season) {
            $isLongest = $longestSeason && $season->number === $longestSeason->number;
            $cssClass = $isLongest ? 'highlight' : '';

            $html .= "<div class='season-item {$cssClass}'>\n";
            $html .= "<div class='season-header'>Сезон {$season->number} (" . count($season->episodes) . " эпизодов)</div>\n";

            if (!empty($season->episodes)) {
                foreach (array_slice($season->episodes, 0, 10) as $episode) {
                    $html .= "<div class='episode-item'>\n";
                    $html .= "<div class='episode-title'>S{$episode->seasonNumber}E{$episode->episodeNumber}: {$episode->getDisplayName()}</div>\n";

                    if ($episode->releaseDate) {
                        $html .= "<div class='episode-details'>Дата выхода: {$episode->releaseDate}</div>\n";
                    }

                    if ($episode->synopsis) {
                        $html .= "<div class='episode-details'>Синопсис: " . htmlspecialchars(substr($episode->synopsis, 0, 100)) . "...</div>\n";
                    }

                    $html .= "</div>\n";
                }

                if (count($season->episodes) > 10) {
                    $html .= "<div class='episode-item'>\n";
                    $html .= "<div class='episode-title'>... и еще " . (count($season->episodes) - 10) . " эпизодов</div>\n";
                    $html .= "</div>\n";
                }
            } else {
                $html .= "<p>Нет информации об эпизодах</p>\n";
            }

            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$seasons = $filmService->getSeasons(123);
$report = new SeasonsReport($seasons);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по сезонам сериала');
file_put_contents('seasons_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в seasons_report.html\n";
```

## Анализ сезонов

```php
function analyzeSeasons(array $seasons): array {
    $analysis = [
        'totalSeasons' => count($seasons),
        'totalEpisodes' => 0,
        'seasonsWithEpisodes' => 0,
        'seasonsWithoutEpisodes' => 0,
        'episodesPerSeason' => [],
        'averageEpisodesPerSeason' => 0,
        'longestSeason' => null,
        'shortestSeason' => null,
        'seasonDistribution' => []
    ];

    $episodeCounts = [];

    foreach ($seasons as $season) {
        $episodeCount = count($season->episodes);
        $analysis['totalEpisodes'] += $episodeCount;
        $analysis['episodesPerSeason'][$season->number] = $episodeCount;
        $episodeCounts[$season->number] = $episodeCount;

        if ($episodeCount > 0) {
            $analysis['seasonsWithEpisodes']++;
        } else {
            $analysis['seasonsWithoutEpisodes']++;
        }
    }

    // Вычисление среднего
    if ($analysis['totalSeasons'] > 0) {
        $analysis['averageEpisodesPerSeason'] = round($analysis['totalEpisodes'] / $analysis['totalSeasons'], 1);
    }

    // Самый длинный и короткий сезоны
    if (!empty($episodeCounts)) {
        $maxEpisodes = max($episodeCounts);
        $minEpisodes = min($episodeCounts);

        foreach ($seasons as $season) {
            if (count($season->episodes) === $maxEpisodes) {
                $analysis['longestSeason'] = $season;
            }
            if (count($season->episodes) === $minEpisodes) {
                $analysis['shortestSeason'] = $season;
            }
        }
    }

    // Распределение по сезонам
    foreach ($episodeCounts as $seasonNumber => $count) {
        $analysis['seasonDistribution'][$seasonNumber] = round(($count / $analysis['totalEpisodes']) * 100, 1);
    }

    return $analysis;
}

// Использование
$seasons = $filmService->getSeasons(123);
$analysis = analyzeSeasons($seasons);

echo "=== Анализ сезонов ===\n";
echo "Всего сезонов: {$analysis['totalSeasons']}\n";
echo "Всего эпизодов: {$analysis['totalEpisodes']}\n";
echo "Среднее количество эпизодов на сезон: {$analysis['averageEpisodesPerSeason']}\n";
echo "Сезонов с эпизодами: {$analysis['seasonsWithEpisodes']}\n";
echo "Сезонов без эпизодов: {$analysis['seasonsWithoutEpisodes']}\n";

if ($analysis['longestSeason']) {
    echo "Самый длинный сезон: {$analysis['longestSeason']->number} (" . count($analysis['longestSeason']->episodes) . " эпизодов)\n";
}

if ($analysis['shortestSeason']) {
    echo "Самый короткий сезон: {$analysis['shortestSeason']->number} (" . count($analysis['shortestSeason']->episodes) . " эпизодов)\n";
}

echo "\nРаспределение эпизодов по сезонам:\n";
foreach ($analysis['seasonDistribution'] as $season => $percentage) {
    echo "- Сезон {$season}: {$percentage}%\n";
}
```

## Связанные классы

- [`Episode`](episode.md) - Модель эпизода
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
