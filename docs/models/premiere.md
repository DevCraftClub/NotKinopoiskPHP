# Premiere

Модель премьеры из Kinopoisk API.

## Описание

`Premiere` представляет информацию о премьере фильма, включая название, год, страны, жанры и дату премьеры в России.

### Основные возможности

- Хранение информации о премьере в неизменяемом виде
- Создание объекта из массива данных API
- Получение отображаемого названия фильма
- Доступ к метаданным премьеры

**API Endpoint:** `/api/v2.2/films/premieres`

## Свойства

### Основная информация

- `$kinopoiskId` (int) - Уникальный идентификатор фильма в Кинопоиске
- `$nameRu` (string|null) - Название фильма на русском языке
- `$nameEn` (string|null) - Название фильма на английском языке
- `$year` (int) - Год выпуска фильма
- `$posterUrl` (string) - URL постера фильма
- `$posterUrlPreview` (string) - URL превью постера фильма
- `$countries` (array<Country>) - Массив стран производства
- `$genres` (array<Genre>) - Массив жанров фильма
- `$duration` (int|null) - Продолжительность фильма в минутах
- `$premiereRu` (string) - Дата премьеры в России

## Конструктор

```php
public function __construct(
    public readonly int $kinopoiskId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly int $year,
    public readonly string $posterUrl,
    public readonly string $posterUrlPreview,
    public readonly array $countries,
    public readonly array $genres,
    public readonly ?int $duration,
    public readonly string $premiereRu,
)
```

### Пример создания

```php
$premiere = new Premiere(
    kinopoiskId: 12345,
    nameRu: 'Новый фильм',
    nameEn: 'New Movie',
    year: 2023,
    posterUrl: 'https://...',
    posterUrlPreview: 'https://...',
    countries: [$country1, $country2],
    genres: [$genre1, $genre2],
    duration: 120,
    premiereRu: '2023-12-01'
);
```

## Методы

### fromArray()

Создает экземпляр премьеры из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных премьеры от API

#### Возвращаемое значение

- `self` - Новый экземпляр премьеры

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Описание

Статический метод для удобного создания объекта Premiere из данных, полученных от Kinopoisk API. Автоматически создает объекты Country и Genre для каждого элемента в соответствующих массивах.

#### Пример использования

```php
$apiData = [
    'kinopoiskId' => 12345,
    'nameRu' => 'Новый фильм',
    'nameEn' => 'New Movie',
    'year' => 2023,
    'posterUrl' => 'https://...',
    'posterUrlPreview' => 'https://...',
    'countries' => [['country' => 'США'], ['country' => 'Великобритания']],
    'genres' => [['genre' => 'Боевик'], ['genre' => 'Драма']],
    'duration' => 120,
    'premiereRu' => '2023-12-01'
];

$premiere = Premiere::fromArray($apiData);
```

### getDisplayName()

Получает отображаемое название премьеры.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название премьеры

#### Описание

Возвращает наиболее подходящее название для отображения пользователю. Приоритет: русское название → английское название → "Без названия"

#### Пример использования

```php
echo $premiere->getDisplayName(); // "Новый фильм" или "New Movie" или "Без названия"
```

### toArray()

Преобразует объект премьеры в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив данных премьеры

#### Пример использования

```php
$array = $premiere->toArray();
// [
//     'kinopoiskId' => 12345,
//     'nameRu' => 'Новый фильм',
//     'nameEn' => 'New Movie',
//     'year' => 2023,
//     'posterUrl' => 'https://...',
//     'posterUrlPreview' => 'https://...',
//     'countries' => [['country' => 'США'], ['country' => 'Великобритания']],
//     'genres' => [['genre' => 'Боевик'], ['genre' => 'Драма']],
//     'duration' => 120,
//     'premiereRu' => '2023-12-01'
// ]
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение премьер
$filmService = $client->films;
$premieres = $filmService->getPremieres();

echo "=== Премьеры фильмов ===\n";

// Общая статистика
echo "Всего премьер: " . count($premieres) . "\n\n";

// Вывод информации о премьерах
foreach ($premieres as $index => $premiere) {
    echo ($index + 1) . ". {$premiere->getDisplayName()}\n";
    echo "   ID: {$premiere->kinopoiskId}\n";
    echo "   Год: {$premiere->year}\n";
    echo "   Премьера в России: {$premiere->premiereRu}\n";

    if ($premiere->duration) {
        echo "   Продолжительность: {$premiere->duration} мин.\n";
    }

    $countries = implode(', ', array_map('strval', $premiere->countries));
    if ($countries) {
        echo "   Страны: {$countries}\n";
    }

    $genres = implode(', ', array_map('strval', $premiere->genres));
    if ($genres) {
        echo "   Жанры: {$genres}\n";
    }

    echo "   Постер: {$premiere->posterUrl}\n";
    echo "\n";
}
```

## Работа с премьерами

```php
// Функция для фильтрации премьер по году
function filterPremieresByYear(array $premieres, int $year): array {
    return array_filter($premieres, fn($premiere) => $premiere->year === $year);
}

// Функция для фильтрации премьер по жанру
function filterPremieresByGenre(array $premieres, string $genreName): array {
    return array_filter($premieres, function($premiere) use ($genreName) {
        foreach ($premiere->genres as $genre) {
            if (stripos($genre->genre, $genreName) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Функция для фильтрации премьер по стране
function filterPremieresByCountry(array $premieres, string $countryName): array {
    return array_filter($premieres, function($premiere) use ($countryName) {
        foreach ($premiere->countries as $country) {
            if (stripos($country->country, $countryName) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Функция для фильтрации премьер по дате
function filterPremieresByDate(array $premieres, string $startDate, string $endDate): array {
    return array_filter($premieres, function($premiere) use ($startDate, $endDate) {
        $premiereDate = $premiere->premiereRu;
        return $premiereDate >= $startDate && $premiereDate <= $endDate;
    });
}

// Функция для сортировки премьер по дате
function sortPremieresByDate(array $premieres, bool $ascending = true): array {
    usort($premieres, function($a, $b) use ($ascending) {
        $comparison = strcmp($a->premiereRu, $b->premiereRu);
        return $ascending ? $comparison : -$comparison;
    });

    return $premieres;
}

// Функция для сортировки премьер по году
function sortPremieresByYear(array $premieres, bool $ascending = true): array {
    usort($premieres, function($a, $b) use ($ascending) {
        $comparison = $a->year <=> $b->year;
        return $ascending ? $comparison : -$comparison;
    });

    return $premieres;
}

// Использование
$premieres = $filmService->getPremieres();

// Фильтрация
$premieres2023 = filterPremieresByYear($premieres, 2023);
$actionPremieres = filterPremieresByGenre($premieres, 'боевик');
$usPremieres = filterPremieresByCountry($premieres, 'сша');
$decemberPremieres = filterPremieresByDate($premieres, '2023-12-01', '2023-12-31');

echo "Премьер 2023 года: " . count($premieres2023) . "\n";
echo "Боевиков: " . count($actionPremieres) . "\n";
echo "Американских фильмов: " . count($usPremieres) . "\n";
echo "Декабрьских премьер: " . count($decemberPremieres) . "\n";

// Сортировка
$sortedByDate = sortPremieresByDate($premieres, true);
$sortedByYear = sortPremieresByYear($premieres, false);
```

## Создание отчета по премьерам

```php
class PremiereReport {
    private array $premieres;

    public function __construct(array $premieres) {
        $this->premieres = $premieres;
    }

    public function getPremieres(): array {
        return $this->premieres;
    }

    public function getPremieresByYear(int $year): array {
        return filterPremieresByYear($this->premieres, $year);
    }

    public function getPremieresByGenre(string $genreName): array {
        return filterPremieresByGenre($this->premieres, $genreName);
    }

    public function getPremieresByCountry(string $countryName): array {
        return filterPremieresByCountry($this->premieres, $countryName);
    }

    public function getPremieresByDate(string $startDate, string $endDate): array {
        return filterPremieresByDate($this->premieres, $startDate, $endDate);
    }

    public function getUpcomingPremieres(int $days = 30): array {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        return filterPremieresByDate($this->premieres, $today, $futureDate);
    }

    public function getRecentPremieres(int $days = 30): array {
        $today = date('Y-m-d');
        $pastDate = date('Y-m-d', strtotime("-{$days} days"));
        return filterPremieresByDate($this->premieres, $pastDate, $today);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ПРЕМЬЕРАМ ===\n\n";

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего премьер: " . count($this->premieres) . "\n";

        // Статистика по годам
        $years = [];
        foreach ($this->premieres as $premiere) {
            $year = $premiere->year;
            if (!isset($years[$year])) {
                $years[$year] = 0;
            }
            $years[$year]++;
        }
        krsort($years);

        $report .= "Премьер по годам:\n";
        foreach ($years as $year => $count) {
            $report .= "- {$year}: {$count} премьер\n";
        }

        $report .= "\n";

        // Статистика по жанрам
        $genres = [];
        foreach ($this->premieres as $premiere) {
            foreach ($premiere->genres as $genre) {
                $genreName = $genre->genre;
                if (!isset($genres[$genreName])) {
                    $genres[$genreName] = 0;
                }
                $genres[$genreName]++;
            }
        }
        arsort($genres);

        $report .= "Премьер по жанрам:\n";
        foreach (array_slice($genres, 0, 10) as $genre => $count) {
            $report .= "- {$genre}: {$count} премьер\n";
        }

        $report .= "\n";

        // Статистика по странам
        $countries = [];
        foreach ($this->premieres as $premiere) {
            foreach ($premiere->countries as $country) {
                $countryName = $country->country;
                if (!isset($countries[$countryName])) {
                    $countries[$countryName] = 0;
                }
                $countries[$countryName]++;
            }
        }
        arsort($countries);

        $report .= "Премьер по странам:\n";
        foreach (array_slice($countries, 0, 10) as $country => $count) {
            $report .= "- {$country}: {$count} премьер\n";
        }

        $report .= "\n";

        // Ближайшие премьеры
        $upcoming = $this->getUpcomingPremieres(30);
        $report .= "🎬 БЛИЖАЙШИЕ ПРЕМЬЕРЫ (30 дней):\n";
        foreach (array_slice($upcoming, 0, 10) as $premiere) {
            $report .= "• {$premiere->getDisplayName()} ({$premiere->premiereRu})\n";
        }

        $report .= "\n";

        // Все премьеры
        $report .= "📋 ВСЕ ПРЕМЬЕРЫ:\n";
        $sortedPremieres = sortPremieresByDate($this->premieres, true);
        foreach ($sortedPremieres as $premiere) {
            $report .= "\n• {$premiere->getDisplayName()}\n";
            $report .= "  ID: {$premiere->kinopoiskId}\n";
            $report .= "  Год: {$premiere->year}\n";
            $report .= "  Премьера: {$premiere->premiereRu}\n";

            if ($premiere->duration) {
                $report .= "  Продолжительность: {$premiere->duration} мин.\n";
            }

            $countries = implode(', ', array_map('strval', $premiere->countries));
            if ($countries) {
                $report .= "  Страны: {$countries}\n";
            }

            $genres = implode(', ', array_map('strval', $premiere->genres));
            if ($genres) {
                $report .= "  Жанры: {$genres}\n";
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
        $html .= ".premiere-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".premiere-title { font-weight: bold; font-size: 18px; margin-bottom: 10px; }\n";
        $html .= ".premiere-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".premiere-poster { width: 100px; height: 150px; object-fit: cover; border-radius: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".premiere-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".upcoming { border-left: 5px solid #28a745; }\n";
        $html .= ".recent { border-left: 5px solid #007bff; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего премьер:</strong> " . count($this->premieres) . "</p>\n";
        $html .= "</div>\n";

        // Ближайшие премьеры
        $upcoming = $this->getUpcomingPremieres(30);
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🎬 Ближайшие премьеры (30 дней)</div>\n";
        $html .= "<div class='premiere-grid'>\n";

        foreach (array_slice($upcoming, 0, 12) as $premiere) {
            $html .= "<div class='premiere-item upcoming'>\n";
            $html .= "<img src='{$premiere->posterUrlPreview}' alt='{$premiere->getDisplayName()}' class='premiere-poster'>\n";
            $html .= "<div class='premiere-title'>{$premiere->getDisplayName()}</div>\n";
            $html .= "<div class='premiere-meta'>Год: {$premiere->year}</div>\n";
            $html .= "<div class='premiere-meta'>Премьера: {$premiere->premiereRu}</div>\n";

            if ($premiere->duration) {
                $html .= "<div class='premiere-meta'>Продолжительность: {$premiere->duration} мин.</div>\n";
            }

            $countries = implode(', ', array_map('strval', $premiere->countries));
            if ($countries) {
                $html .= "<div class='premiere-meta'>Страны: {$countries}</div>\n";
            }

            $genres = implode(', ', array_map('strval', $premiere->genres));
            if ($genres) {
                $html .= "<div class='premiere-meta'>Жанры: {$genres}</div>\n";
            }

            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n";

        // Все премьеры
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📋 Все премьеры</div>\n";
        $html .= "<div class='premiere-grid'>\n";

        $sortedPremieres = sortPremieresByDate($this->premieres, true);
        foreach ($sortedPremieres as $premiere) {
            $html .= "<div class='premiere-item'>\n";
            $html .= "<img src='{$premiere->posterUrlPreview}' alt='{$premiere->getDisplayName()}' class='premiere-poster'>\n";
            $html .= "<div class='premiere-title'>{$premiere->getDisplayName()}</div>\n";
            $html .= "<div class='premiere-meta'>ID: {$premiere->kinopoiskId}</div>\n";
            $html .= "<div class='premiere-meta'>Год: {$premiere->year}</div>\n";
            $html .= "<div class='premiere-meta'>Премьера: {$premiere->premiereRu}</div>\n";

            if ($premiere->duration) {
                $html .= "<div class='premiere-meta'>Продолжительность: {$premiere->duration} мин.</div>\n";
            }

            $countries = implode(', ', array_map('strval', $premiere->countries));
            if ($countries) {
                $html .= "<div class='premiere-meta'>Страны: {$countries}</div>\n";
            }

            $genres = implode(', ', array_map('strval', $premiere->genres));
            if ($genres) {
                $html .= "<div class='premiere-meta'>Жанры: {$genres}</div>\n";
            }

            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$premieres = $filmService->getPremieres();
$report = new PremiereReport($premieres);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по премьерам');
file_put_contents('premieres_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в premieres_report.html\n";
```

## Анализ премьер

```php
function analyzePremieres(array $premieres): array {
    $analysis = [
        'totalPremieres' => count($premieres),
        'years' => [],
        'genres' => [],
        'countries' => [],
        'durationDistribution' => [
            'short' => 0,    // <90 мин
            'medium' => 0,   // 90-120 мин
            'long' => 0      // >120 мин
        ],
        'monthlyDistribution' => [],
        'averageDuration' => 0
    ];

    $totalDuration = 0;
    $durationCount = 0;

    foreach ($premieres as $premiere) {
        // Статистика по годам
        $year = $premiere->year;
        if (!isset($analysis['years'][$year])) {
            $analysis['years'][$year] = 0;
        }
        $analysis['years'][$year]++;

        // Статистика по жанрам
        foreach ($premiere->genres as $genre) {
            $genreName = $genre->genre;
            if (!isset($analysis['genres'][$genreName])) {
                $analysis['genres'][$genreName] = 0;
            }
            $analysis['genres'][$genreName]++;
        }

        // Статистика по странам
        foreach ($premiere->countries as $country) {
            $countryName = $country->country;
            if (!isset($analysis['countries'][$countryName])) {
                $analysis['countries'][$countryName] = 0;
            }
            $analysis['countries'][$countryName]++;
        }

        // Статистика по продолжительности
        if ($premiere->duration) {
            $duration = $premiere->duration;
            $totalDuration += $duration;
            $durationCount++;

            if ($duration < 90) {
                $analysis['durationDistribution']['short']++;
            } elseif ($duration <= 120) {
                $analysis['durationDistribution']['medium']++;
            } else {
                $analysis['durationDistribution']['long']++;
            }
        }

        // Статистика по месяцам
        $month = date('F', strtotime($premiere->premiereRu));
        if (!isset($analysis['monthlyDistribution'][$month])) {
            $analysis['monthlyDistribution'][$month] = 0;
        }
        $analysis['monthlyDistribution'][$month]++;
    }

    // Средняя продолжительность
    if ($durationCount > 0) {
        $analysis['averageDuration'] = round($totalDuration / $durationCount);
    }

    return $analysis;
}

// Использование
$premieres = $filmService->getPremieres();
$analysis = analyzePremieres($premieres);

echo "=== Анализ премьер ===\n";
echo "Всего премьер: {$analysis['totalPremieres']}\n";
echo "Средняя продолжительность: {$analysis['averageDuration']} мин.\n";

echo "\nРаспределение по продолжительности:\n";
echo "- Короткие (<90 мин): {$analysis['durationDistribution']['short']}\n";
echo "- Средние (90-120 мин): {$analysis['durationDistribution']['medium']}\n";
echo "- Длинные (>120 мин): {$analysis['durationDistribution']['long']}\n";

echo "\nТоп жанров:\n";
arsort($analysis['genres']);
foreach (array_slice($analysis['genres'], 0, 5) as $genre => $count) {
    echo "- {$genre}: {$count} премьер\n";
}

echo "\nТоп стран:\n";
arsort($analysis['countries']);
foreach (array_slice($analysis['countries'], 0, 5) as $country => $count) {
    echo "- {$country}: {$count} премьер\n";
}

echo "\nРаспределение по месяцам:\n";
ksort($analysis['monthlyDistribution']);
foreach ($analysis['monthlyDistribution'] as $month => $count) {
    echo "- {$month}: {$count} премьер\n";
}
```

## Связанные классы

- [`Country`](country.md) - Модель страны
- [`Genre`](genre.md) - Модель жанра
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
