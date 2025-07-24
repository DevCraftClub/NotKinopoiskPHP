# UserVote

Модель пользовательского голоса.

## Описание

`UserVote` представляет голос пользователя за фильм в Kinopoisk API. Содержит информацию о фильме и оценке пользователя.

**API Endpoint:** `/api/v1/kp_users/{id}/votes`

## Свойства

### Основная информация

- `$kinopoiskId` (int) - Уникальный идентификатор фильма
- `$nameRu` (string|null) - Название на русском
- `$nameEn` (string|null) - Название на английском
- `$nameOriginal` (string|null) - Оригинальное название
- `$countries` (Country[]) - Массив стран
- `$genres` (Genre[]) - Массив жанров
- `$ratingKinopoisk` (float|null) - Рейтинг Кинопоиска
- `$ratingImbd` (float|null) - Рейтинг IMDb
- `$year` (string|null) - Год выпуска
- `$type` (ContentType) - Тип контента
- `$posterUrl` (string) - URL постера
- `$posterUrlPreview` (string) - URL превью постера
- `$userRating` (int) - Оценка пользователя

## Конструктор

```php
public function __construct(
    public readonly int $kinopoiskId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $nameOriginal,
    public readonly array $countries,
    public readonly array $genres,
    public readonly ?float $ratingKinopoisk,
    public readonly ?float $ratingImbd,
    public readonly ?string $year,
    public readonly ContentType $type,
    public readonly string $posterUrl,
    public readonly string $posterUrlPreview,
    public readonly int $userRating,
)
```

### Пример создания

```php
$vote = new UserVote(
    kinopoiskId: 301,
    nameRu: 'Матрица',
    nameEn: 'The Matrix',
    nameOriginal: 'The Matrix',
    countries: [new Country('США')],
    genres: [new Genre('боевик')],
    ratingKinopoisk: 8.5,
    ratingImbd: 8.7,
    year: '1999',
    type: ContentType::FILM,
    posterUrl: 'https://example.com/poster.jpg',
    posterUrlPreview: 'https://example.com/poster_small.jpg',
    userRating: 9
);
```

## Методы

### fromArray()

Создает экземпляр модели из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных от API

#### Возвращаемое значение

- `self` - Экземпляр модели

#### Исключения

- `\InvalidArgumentException` - При некорректных данных

#### Пример использования

```php
$voteData = [
    'kinopoiskId' => 301,
    'nameRu' => 'Матрица',
    'nameEn' => 'The Matrix',
    'nameOriginal' => 'The Matrix',
    'countries' => [['country' => 'США']],
    'genres' => [['genre' => 'боевик']],
    'ratingKinopoisk' => 8.5,
    'ratingImbd' => 8.7,
    'year' => '1999',
    'type' => 'FILM',
    'posterUrl' => 'https://example.com/poster.jpg',
    'posterUrlPreview' => 'https://example.com/poster_small.jpg',
    'userRating' => 9
];
$vote = UserVote::fromArray($voteData);
```

### getDisplayName()

Получает отображаемое название фильма.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название фильма

#### Описание

Возвращает название фильма в приоритетном порядке: русское название → английское название → оригинальное название

#### Пример использования

```php
$displayName = $vote->getDisplayName();
echo "Название: $displayName";
```

### toArray()

Преобразует объект пользовательского голоса в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив данных пользовательского голоса

#### Описание

Возвращает массив со всеми свойствами пользовательского голоса, включая преобразованные в массивы объекты стран и жанров.

#### Пример использования

```php
$array = $vote->toArray();
// [
//     'kinopoiskId' => 301,
//     'nameRu' => 'Матрица',
//     'nameEn' => 'The Matrix',
//     'nameOriginal' => 'The Matrix',
//     'countries' => [['country' => 'США']],
//     'genres' => [['genre' => 'боевик']],
//     'ratingKinopoisk' => 8.5,
//     'ratingImbd' => 8.7,
//     'year' => '1999',
//     'type' => 'FILM',
//     'posterUrl' => 'https://example.com/poster.jpg',
//     'posterUrlPreview' => 'https://example.com/poster_small.jpg',
//     'userRating' => 9
// ]
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение голосов пользователя
$userService = $client->users;
$votes = $userService->getUserVotes(12345); // ID пользователя

echo "=== Анализ голосов пользователя ===\n";

if (!empty($votes)) {
    echo "Всего голосов: " . count($votes) . "\n\n";

    foreach ($votes as $index => $vote) {
        echo ($index + 1) . ". {$vote->getDisplayName()}\n";
        echo "   ID: {$vote->kinopoiskId}\n";
        echo "   Оценка пользователя: {$vote->userRating}/10\n";
        echo "   Рейтинг Кинопоиска: " . ($vote->ratingKinopoisk ?? 'Нет') . "\n";
        echo "   Рейтинг IMDb: " . ($vote->ratingImbd ?? 'Нет') . "\n";
        echo "   Год: " . ($vote->year ?? 'Неизвестно') . "\n";
        echo "   Тип: {$vote->type->getDisplayName()}\n";

        if (!empty($vote->countries)) {
            $countries = array_map(fn($c) => $c->country, $vote->countries);
            echo "   Страны: " . implode(', ', $countries) . "\n";
        }

        if (!empty($vote->genres)) {
            $genres = array_map(fn($g) => $g->genre, $vote->genres);
            echo "   Жанры: " . implode(', ', $genres) . "\n";
        }

        echo "\n";
    }
} else {
    echo "Голоса не найдены\n";
}
```

## Работа с пользовательскими голосами

```php
// Функция для фильтрации голосов по оценке
function filterVotesByRating(array $votes, int $minRating): array {
    return array_filter($votes, fn($vote) => $vote->userRating >= $minRating);
}

// Функция для фильтрации голосов по типу контента
function filterVotesByType(array $votes, ContentType $type): array {
    return array_filter($votes, fn($vote) => $vote->type === $type);
}

// Функция для фильтрации голосов по году
function filterVotesByYear(array $votes, int $year): array {
    return array_filter($votes, fn($vote) => $vote->year == $year);
}

// Функция для получения статистики по голосам
function getVoteStats(array $votes): array {
    $stats = [
        'total' => count($votes),
        'averageUserRating' => 0,
        'averageKinopoiskRating' => 0,
        'averageImdbRating' => 0,
        'ratingDistribution' => array_fill(1, 10, 0),
        'typeDistribution' => [],
        'yearDistribution' => [],
        'genreDistribution' => [],
        'countryDistribution' => []
    ];

    $totalUserRating = 0;
    $totalKinopoiskRating = 0;
    $totalImdbRating = 0;
    $ratedKinopoisk = 0;
    $ratedImdb = 0;

    foreach ($votes as $vote) {
        // Пользовательские оценки
        $totalUserRating += $vote->userRating;
        $stats['ratingDistribution'][$vote->userRating]++;

        // Рейтинги Кинопоиска
        if ($vote->ratingKinopoisk) {
            $totalKinopoiskRating += $vote->ratingKinopoisk;
            $ratedKinopoisk++;
        }

        // Рейтинги IMDb
        if ($vote->ratingImbd) {
            $totalImdbRating += $vote->ratingImbd;
            $ratedImdb++;
        }

        // Распределение по типам
        $typeKey = $vote->type->value;
        if (!isset($stats['typeDistribution'][$typeKey])) {
            $stats['typeDistribution'][$typeKey] = 0;
        }
        $stats['typeDistribution'][$typeKey]++;

        // Распределение по годам
        if ($vote->year) {
            if (!isset($stats['yearDistribution'][$vote->year])) {
                $stats['yearDistribution'][$vote->year] = 0;
            }
            $stats['yearDistribution'][$vote->year]++;
        }

        // Распределение по жанрам
        foreach ($vote->genres as $genre) {
            if (!isset($stats['genreDistribution'][$genre->genre])) {
                $stats['genreDistribution'][$genre->genre] = 0;
            }
            $stats['genreDistribution'][$genre->genre]++;
        }

        // Распределение по странам
        foreach ($vote->countries as $country) {
            if (!isset($stats['countryDistribution'][$country->country])) {
                $stats['countryDistribution'][$country->country] = 0;
            }
            $stats['countryDistribution'][$country->country]++;
        }
    }

    // Вычисление средних значений
    if ($stats['total'] > 0) {
        $stats['averageUserRating'] = round($totalUserRating / $stats['total'], 2);
    }

    if ($ratedKinopoisk > 0) {
        $stats['averageKinopoiskRating'] = round($totalKinopoiskRating / $ratedKinopoisk, 2);
    }

    if ($ratedImdb > 0) {
        $stats['averageImdbRating'] = round($totalImdbRating / $ratedImdb, 2);
    }

    return $stats;
}

// Функция для получения топ голосов по оценке
function getTopRatedVotes(array $votes, int $limit = 10): array {
    usort($votes, function($a, $b) {
        return $b->userRating <=> $a->userRating;
    });

    return array_slice($votes, 0, $limit);
}

// Функция для получения голосов с наибольшей разницей в оценках
function getVotesWithRatingDifference(array $votes, int $limit = 10): array {
    $votesWithDiff = array_map(function($vote) {
        $diff = 0;
        if ($vote->ratingKinopoisk) {
            $diff = abs($vote->userRating - $vote->ratingKinopoisk);
        }
        return ['vote' => $vote, 'difference' => $diff];
    }, $votes);

    usort($votesWithDiff, function($a, $b) {
        return $b['difference'] <=> $a['difference'];
    });

    return array_slice($votesWithDiff, 0, $limit);
}

// Функция для получения любимых жанров пользователя
function getUserFavoriteGenres(array $votes): array {
    $genreStats = [];

    foreach ($votes as $vote) {
        foreach ($vote->genres as $genre) {
            if (!isset($genreStats[$genre->genre])) {
                $genreStats[$genre->genre] = [
                    'count' => 0,
                    'totalRating' => 0
                ];
            }
            $genreStats[$genre->genre]['count']++;
            $genreStats[$genre->genre]['totalRating'] += $vote->userRating;
        }
    }

    // Вычисление средних оценок
    foreach ($genreStats as $genre => &$stats) {
        $stats['averageRating'] = round($stats['totalRating'] / $stats['count'], 2);
    }

    // Сортировка по количеству фильмов
    uasort($genreStats, function($a, $b) {
        return $b['count'] <=> $a['count'];
    });

    return $genreStats;
}

// Использование
$votes = $userService->getUserVotes(12345);

// Фильтрация
$highRatedVotes = filterVotesByRating($votes, 8);
$filmVotes = filterVotesByType($votes, ContentType::FILM);
$recentVotes = filterVotesByYear($votes, 2020);

echo "Фильтрация голосов:\n";
echo "Высоко оцененные (8+): " . count($highRatedVotes) . "\n";
echo "Фильмы: " . count($filmVotes) . "\n";
echo "Фильмы 2020 года: " . count($recentVotes) . "\n";

// Статистика
$stats = getVoteStats($votes);
echo "\nСтатистика:\n";
echo "Всего голосов: {$stats['total']}\n";
echo "Средняя оценка пользователя: {$stats['averageUserRating']}\n";
echo "Средний рейтинг Кинопоиска: {$stats['averageKinopoiskRating']}\n";
echo "Средний рейтинг IMDb: {$stats['averageImdbRating']}\n";

// Топ голосов
$topVotes = getTopRatedVotes($votes, 5);
echo "\nТоп-5 по оценке:\n";
foreach ($topVotes as $index => $vote) {
    echo ($index + 1) . ". {$vote->getDisplayName()} - {$vote->userRating}/10\n";
}

// Любимые жанры
$favoriteGenres = getUserFavoriteGenres($votes);
echo "\nЛюбимые жанры:\n";
foreach (array_slice($favoriteGenres, 0, 5, true) as $genre => $stats) {
    echo "• {$genre}: {$stats['count']} фильмов, средняя оценка {$stats['averageRating']}\n";
}
```

## Создание отчета по голосам пользователя

```php
class UserVoteReport {
    private array $votes;

    public function __construct(array $votes) {
        $this->votes = $votes;
    }

    public function getVotes(): array {
        return $this->votes;
    }

    public function getVoteStats(): array {
        return getVoteStats($this->votes);
    }

    public function getTopRatedVotes(int $limit = 10): array {
        return getTopRatedVotes($this->votes, $limit);
    }

    public function getFavoriteGenres(): array {
        return getUserFavoriteGenres($this->votes);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ГОЛОСАМ ПОЛЬЗОВАТЕЛЯ ===\n\n";

        $stats = $this->getVoteStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего голосов: {$stats['total']}\n";
        $report .= "Средняя оценка пользователя: {$stats['averageUserRating']}\n";
        $report .= "Средний рейтинг Кинопоиска: {$stats['averageKinopoiskRating']}\n";
        $report .= "Средний рейтинг IMDb: {$stats['averageImdbRating']}\n\n";

        // Распределение оценок
        $report .= "📈 РАСПРЕДЕЛЕНИЕ ОЦЕНОК:\n";
        for ($i = 10; $i >= 1; $i--) {
            $count = $stats['ratingDistribution'][$i];
            $percent = round(($count / $stats['total']) * 100, 1);
            $stars = str_repeat('⭐', $i);
            $report .= "{$stars} {$i}/10: {$count} голосов ({$percent}%)\n";
        }
        $report .= "\n";

        // Распределение по типам
        $report .= "🎬 РАСПРЕДЕЛЕНИЕ ПО ТИПАМ:\n";
        foreach ($stats['typeDistribution'] as $typeValue => $count) {
            $type = ContentType::from($typeValue);
            $percent = round(($count / $stats['total']) * 100, 1);
            $report .= "• {$type->getDisplayName()}: {$count} ({$percent}%)\n";
        }
        $report .= "\n";

        // Топ жанров
        $favoriteGenres = $this->getFavoriteGenres();
        $report .= "🎭 ЛЮБИМЫЕ ЖАНРЫ:\n";
        foreach (array_slice($favoriteGenres, 0, 10, true) as $genre => $genreStats) {
            $report .= "• {$genre}: {$genreStats['count']} фильмов, средняя оценка {$genreStats['averageRating']}\n";
        }
        $report .= "\n";

        // Топ стран
        arsort($stats['countryDistribution']);
        $report .= "🌍 ЛЮБИМЫЕ СТРАНЫ:\n";
        foreach (array_slice($stats['countryDistribution'], 0, 10, true) as $country => $count) {
            $percent = round(($count / $stats['total']) * 100, 1);
            $report .= "• {$country}: {$count} фильмов ({$percent}%)\n";
        }
        $report .= "\n";

        // Топ голосов
        $topVotes = $this->getTopRatedVotes(10);
        $report .= "🏆 ТОП-10 ПО ОЦЕНКЕ:\n";
        foreach ($topVotes as $index => $vote) {
            $stars = str_repeat('⭐', $vote->userRating);
            $report .= ($index + 1) . ". {$vote->getDisplayName()} {$stars} ({$vote->userRating}/10)\n";
            if ($vote->year) {
                $report .= "   Год: {$vote->year}\n";
            }
            if (!empty($vote->genres)) {
                $genres = array_map(fn($g) => $g->genre, $vote->genres);
                $report .= "   Жанры: " . implode(', ', $genres) . "\n";
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
        $html .= ".vote-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".vote-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".vote-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".vote-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".rating-10 { border-left: 5px solid #28a745; }\n";
        $html .= ".rating-9 { border-left: 5px solid #20c997; }\n";
        $html .= ".rating-8 { border-left: 5px solid #17a2b8; }\n";
        $html .= ".rating-7 { border-left: 5px solid #ffc107; }\n";
        $html .= ".rating-6 { border-left: 5px solid #fd7e14; }\n";
        $html .= ".rating-low { border-left: 5px solid #dc3545; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; transition: width 0.3s ease; }\n";
        $html .= ".rating-fill { background-color: #28a745; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getVoteStats();
        $topVotes = $this->getTopRatedVotes(12);
        $favoriteGenres = $this->getFavoriteGenres();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего голосов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Средняя оценка пользователя:</strong> {$stats['averageUserRating']}</p>\n";
        $html .= "<p><strong>Средний рейтинг Кинопоиска:</strong> {$stats['averageKinopoiskRating']}</p>\n";
        $html .= "<p><strong>Средний рейтинг IMDb:</strong> {$stats['averageImdbRating']}</p>\n";

        // Распределение оценок
        $html .= "<h3>Распределение оценок</h3>\n";
        for ($i = 10; $i >= 1; $i--) {
            $count = $stats['ratingDistribution'][$i];
            $percent = round(($count / $stats['total']) * 100, 1);

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill rating-fill' style='width: {$percent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>{$i}/10: {$count} голосов ({$percent}%)</p>\n";
        }

        $html .= "</div>\n";

        // Топ голосов
        if (!empty($topVotes)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🏆 Топ голосов по оценке</div>\n";
            $html .= "<div class='vote-grid'>\n";

            foreach ($topVotes as $vote) {
                $cssClass = 'rating-' . $vote->userRating;
                if ($vote->userRating < 6) $cssClass = 'rating-low';

                $html .= "<div class='vote-item {$cssClass}'>\n";
                $html .= "<div class='vote-title'>{$vote->getDisplayName()}</div>\n";
                $html .= "<div class='vote-meta'>Оценка: {$vote->userRating}/10</div>\n";
                if ($vote->ratingKinopoisk) {
                    $html .= "<div class='vote-meta'>Кинопоиск: {$vote->ratingKinopoisk}</div>\n";
                }
                if ($vote->ratingImbd) {
                    $html .= "<div class='vote-meta'>IMDb: {$vote->ratingImbd}</div>\n";
                }
                if ($vote->year) {
                    $html .= "<div class='vote-meta'>Год: {$vote->year}</div>\n";
                }
                if (!empty($vote->genres)) {
                    $genres = array_map(fn($g) => $g->genre, $vote->genres);
                    $html .= "<div class='vote-meta'>Жанры: " . implode(', ', $genres) . "</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Любимые жанры
        if (!empty($favoriteGenres)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🎭 Любимые жанры</div>\n";
            $html .= "<div class='vote-grid'>\n";

            foreach (array_slice($favoriteGenres, 0, 12, true) as $genre => $genreStats) {
                $html .= "<div class='vote-item'>\n";
                $html .= "<div class='vote-title'>{$genre}</div>\n";
                $html .= "<div class='vote-meta'>Фильмов: {$genreStats['count']}</div>\n";
                $html .= "<div class='vote-meta'>Средняя оценка: {$genreStats['averageRating']}</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$votes = $userService->getUserVotes(12345);
$report = new UserVoteReport($votes);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по голосам пользователя');
file_put_contents('user_votes_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в user_votes_report.html\n";
```

## Анализ голосов пользователя

```php
function analyzeUserVotes(array $votes): array {
    $analysis = [
        'totalVotes' => count($votes),
        'ratingAnalysis' => [
            'averageUserRating' => 0,
            'averageKinopoiskRating' => 0,
            'averageImdbRating' => 0,
            'ratingDistribution' => array_fill(1, 10, 0),
            'ratingCorrelation' => 0
        ],
        'contentTypeAnalysis' => [
            'typeDistribution' => [],
            'averageRatingByType' => []
        ],
        'yearAnalysis' => [
            'yearDistribution' => [],
            'averageRatingByYear' => []
        ],
        'genreAnalysis' => [
            'genreDistribution' => [],
            'averageRatingByGenre' => []
        ],
        'countryAnalysis' => [
            'countryDistribution' => [],
            'averageRatingByCountry' => []
        ]
    ];

    $totalUserRating = 0;
    $totalKinopoiskRating = 0;
    $totalImdbRating = 0;
    $ratedKinopoisk = 0;
    $ratedImdb = 0;
    $ratingDifferences = [];

    foreach ($votes as $vote) {
        // Пользовательские оценки
        $totalUserRating += $vote->userRating;
        $analysis['ratingAnalysis']['ratingDistribution'][$vote->userRating]++;

        // Рейтинги Кинопоиска
        if ($vote->ratingKinopoisk) {
            $totalKinopoiskRating += $vote->ratingKinopoisk;
            $ratedKinopoisk++;
            $ratingDifferences[] = abs($vote->userRating - $vote->ratingKinopoisk);
        }

        // Рейтинги IMDb
        if ($vote->ratingImbd) {
            $totalImdbRating += $vote->ratingImbd;
            $ratedImdb++;
        }

        // Анализ по типам контента
        $typeKey = $vote->type->value;
        if (!isset($analysis['contentTypeAnalysis']['typeDistribution'][$typeKey])) {
            $analysis['contentTypeAnalysis']['typeDistribution'][$typeKey] = 0;
            $analysis['contentTypeAnalysis']['averageRatingByType'][$typeKey] = ['total' => 0, 'count' => 0];
        }
        $analysis['contentTypeAnalysis']['typeDistribution'][$typeKey]++;
        $analysis['contentTypeAnalysis']['averageRatingByType'][$typeKey]['total'] += $vote->userRating;
        $analysis['contentTypeAnalysis']['averageRatingByType'][$typeKey]['count']++;

        // Анализ по годам
        if ($vote->year) {
            if (!isset($analysis['yearAnalysis']['yearDistribution'][$vote->year])) {
                $analysis['yearAnalysis']['yearDistribution'][$vote->year] = 0;
                $analysis['yearAnalysis']['averageRatingByYear'][$vote->year] = ['total' => 0, 'count' => 0];
            }
            $analysis['yearAnalysis']['yearDistribution'][$vote->year]++;
            $analysis['yearAnalysis']['averageRatingByYear'][$vote->year]['total'] += $vote->userRating;
            $analysis['yearAnalysis']['averageRatingByYear'][$vote->year]['count']++;
        }

        // Анализ по жанрам
        foreach ($vote->genres as $genre) {
            if (!isset($analysis['genreAnalysis']['genreDistribution'][$genre->genre])) {
                $analysis['genreAnalysis']['genreDistribution'][$genre->genre] = 0;
                $analysis['genreAnalysis']['averageRatingByGenre'][$genre->genre] = ['total' => 0, 'count' => 0];
            }
            $analysis['genreAnalysis']['genreDistribution'][$genre->genre]++;
            $analysis['genreAnalysis']['averageRatingByGenre'][$genre->genre]['total'] += $vote->userRating;
            $analysis['genreAnalysis']['averageRatingByGenre'][$genre->genre]['count']++;
        }

        // Анализ по странам
        foreach ($vote->countries as $country) {
            if (!isset($analysis['countryAnalysis']['countryDistribution'][$country->country])) {
                $analysis['countryAnalysis']['countryDistribution'][$country->country] = 0;
                $analysis['countryAnalysis']['averageRatingByCountry'][$country->country] = ['total' => 0, 'count' => 0];
            }
            $analysis['countryAnalysis']['countryDistribution'][$country->country]++;
            $analysis['countryAnalysis']['averageRatingByCountry'][$country->country]['total'] += $vote->userRating;
            $analysis['countryAnalysis']['averageRatingByCountry'][$country->country]['count']++;
        }
    }

    // Вычисление средних значений
    if ($analysis['totalVotes'] > 0) {
        $analysis['ratingAnalysis']['averageUserRating'] = round($totalUserRating / $analysis['totalVotes'], 2);
    }

    if ($ratedKinopoisk > 0) {
        $analysis['ratingAnalysis']['averageKinopoiskRating'] = round($totalKinopoiskRating / $ratedKinopoisk, 2);
    }

    if ($ratedImdb > 0) {
        $analysis['ratingAnalysis']['averageImdbRating'] = round($totalImdbRating / $ratedImdb, 2);
    }

    // Корреляция оценок
    if (!empty($ratingDifferences)) {
        $analysis['ratingAnalysis']['ratingCorrelation'] = round(array_sum($ratingDifferences) / count($ratingDifferences), 2);
    }

    // Вычисление средних оценок по категориям
    foreach ($analysis['contentTypeAnalysis']['averageRatingByType'] as $type => &$data) {
        if ($data['count'] > 0) {
            $data['average'] = round($data['total'] / $data['count'], 2);
        }
    }

    foreach ($analysis['yearAnalysis']['averageRatingByYear'] as $year => &$data) {
        if ($data['count'] > 0) {
            $data['average'] = round($data['total'] / $data['count'], 2);
        }
    }

    foreach ($analysis['genreAnalysis']['averageRatingByGenre'] as $genre => &$data) {
        if ($data['count'] > 0) {
            $data['average'] = round($data['total'] / $data['count'], 2);
        }
    }

    foreach ($analysis['countryAnalysis']['averageRatingByCountry'] as $country => &$data) {
        if ($data['count'] > 0) {
            $data['average'] = round($data['total'] / $data['count'], 2);
        }
    }

    return $analysis;
}

// Использование
$votes = $userService->getUserVotes(12345);
$analysis = analyzeUserVotes($votes);

echo "=== Анализ голосов пользователя ===\n";
echo "Всего голосов: {$analysis['totalVotes']}\n";

echo "\nАнализ оценок:\n";
echo "- Средняя оценка пользователя: {$analysis['ratingAnalysis']['averageUserRating']}\n";
echo "- Средний рейтинг Кинопоиска: {$analysis['ratingAnalysis']['averageKinopoiskRating']}\n";
echo "- Средний рейтинг IMDb: {$analysis['ratingAnalysis']['averageImdbRating']}\n";
echo "- Средняя разница с Кинопоиском: {$analysis['ratingAnalysis']['ratingCorrelation']}\n";

echo "\nРаспределение оценок:\n";
for ($i = 10; $i >= 1; $i--) {
    $count = $analysis['ratingAnalysis']['ratingDistribution'][$i];
    $percent = round(($count / $analysis['totalVotes']) * 100, 1);
    echo "- {$i}/10: {$count} голосов ({$percent}%)\n";
}

echo "\nСредние оценки по типам контента:\n";
foreach ($analysis['contentTypeAnalysis']['averageRatingByType'] as $type => $data) {
    if (isset($data['average'])) {
        $contentType = ContentType::from($type);
        echo "- {$contentType->getDisplayName()}: {$data['average']}\n";
    }
}

echo "\nТоп жанров по средней оценке:\n";
$genreAverages = [];
foreach ($analysis['genreAnalysis']['averageRatingByGenre'] as $genre => $data) {
    if (isset($data['average'])) {
        $genreAverages[$genre] = $data['average'];
    }
}
arsort($genreAverages);
foreach (array_slice($genreAverages, 0, 10, true) as $genre => $average) {
    echo "- {$genre}: {$average}\n";
}
```

## Связанные классы

- [`Country`](country.md) - Модель страны
- [`Genre`](genre.md) - Модель жанра
- [`ContentType`](../enums/content-type.md) - Enum типов контента
- [`UserService`](../services/user-service.md) - Сервис для работы с пользователями
