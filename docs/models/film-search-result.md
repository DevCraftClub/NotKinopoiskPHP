# FilmSearchResult

Модель результата поиска фильма из Kinopoisk API.

## Описание

`FilmSearchResult` представляет краткую информацию о фильме в результатах поиска по ключевому слову. Содержит основные данные: названия, тип контента, год, описание, рейтинг и постер.

### Основные возможности

- Хранение информации о фильме в неизменяемом виде
- Создание объекта из массива данных API
- Получение отображаемого названия фильма
- Доступ к метаданным поискового результата

**API Endpoint:** `/api/v2.1/films/search-by-keyword`

## Свойства

### Основная информация

- `$filmId` (int) - Уникальный идентификатор фильма в Кинопоиске
- `$nameRu` (string|null) - Название фильма на русском языке
- `$nameEn` (string|null) - Название фильма на английском языке
- `$type` (ContentType) - Тип контента (FILM, TV_SHOW, VIDEO, MINI_SERIES, TV_SERIES, UNKNOWN)
- `$year` (string|null) - Год выпуска фильма (строка)
- `$description` (string|null) - Краткое описание фильма
- `$filmLength` (string|null) - Длительность фильма в формате "2:17"
- `$countries` (array<Country>) - Массив стран производства
- `$genres` (array<Genre>) - Массив жанров фильма
- `$rating` (string|null) - Рейтинг фильма (строка, может быть "7.9" или "99%")
- `$ratingVoteCount` (int|null) - Количество голосов за рейтинг
- `$posterUrl` (string) - URL постера фильма
- `$posterUrlPreview` (string) - URL превью постера фильма

## Конструктор

```php
public function __construct(
    public readonly int $filmId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ContentType $type,
    public readonly ?string $year,
    public readonly ?string $description,
    public readonly ?string $filmLength,
    public readonly array $countries,
    public readonly array $genres,
    public readonly ?string $rating,
    public readonly ?int $ratingVoteCount,
    public readonly string $posterUrl,
    public readonly string $posterUrlPreview,
)
```

### Пример создания

```php
$searchResult = new FilmSearchResult(
    filmId: 263531,
    nameRu: 'Мстители',
    nameEn: 'The Avengers',
    type: ContentType::FILM,
    year: '2012',
    description: 'США, Джосс Уидон(фантастика)',
    filmLength: '2:17',
    countries: [$country1, $country2],
    genres: [$genre1, $genre2],
    rating: '7.9',
    ratingVoteCount: 284245,
    posterUrl: 'https://example.com/poster.jpg',
    posterUrlPreview: 'https://example.com/poster_small.jpg'
);
```

## Методы

### fromArray()

Создает экземпляр результата поиска из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных результата поиска от API

#### Возвращаемое значение

- `self` - Новый экземпляр результата поиска

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Описание

Статический метод для удобного создания объекта FilmSearchResult из данных, полученных от Kinopoisk API. Автоматически создает объекты Country и Genre для каждого элемента в соответствующих массивах.

#### Пример использования

```php
$apiData = [
    'filmId' => 263531,
    'nameRu' => 'Мстители',
    'nameEn' => 'The Avengers',
    'type' => 'FILM',
    'year' => '2012',
    'description' => 'США, Джосс Уидон(фантастика)',
    'filmLength' => '2:17',
    'countries' => [['country' => 'США']],
    'genres' => [['genre' => 'фантастика']],
    'rating' => '7.9',
    'ratingVoteCount' => 284245,
    'posterUrl' => 'https://example.com/poster.jpg',
    'posterUrlPreview' => 'https://example.com/poster_small.jpg'
];

$searchResult = FilmSearchResult::fromArray($apiData);
```

### getDisplayName()

Получает отображаемое название фильма.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название фильма

#### Описание

Возвращает наиболее подходящее название для отображения пользователю. Приоритет: русское название → английское название → "Без названия"

#### Пример использования

```php
echo $searchResult->getDisplayName(); // "Мстители" или "The Avengers" или "Без названия"
```

### isSerial()

Проверяет, является ли контент сериалом.

```php
public function isSerial(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это сериал, `false` если фильм

#### Описание

Определяет тип контента на основе поля type. Возвращает true для TV_SERIES, MINI_SERIES и TV_SHOW.

#### Пример использования

```php
if ($searchResult->isSerial()) {
    echo "Это сериал";
} else {
    echo "Это фильм";
}
```

### getCountriesString()

Получает список стран в виде строки.

```php
public function getCountriesString(): string
```

#### Возвращаемое значение

- `string` - Список стран или пустая строка

#### Пример использования

```php
echo "Страны: {$searchResult->getCountriesString()}"; // "США, Великобритания"
```

### getGenresString()

Получает список жанров в виде строки.

```php
public function getGenresString(): string
```

#### Возвращаемое значение

- `string` - Список жанров или пустая строка

#### Пример использования

```php
echo "Жанры: {$searchResult->getGenresString()}"; // "фантастика, боевик"
```

### toArray()

Преобразует объект результата поиска в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив данных результата поиска

#### Описание

Возвращает массив со всеми свойствами результата поиска, включая преобразованные в массивы объекты стран и жанров.

#### Пример использования

```php
$array = $searchResult->toArray();
// [
//     'filmId' => 263531,
//     'nameRu' => 'Мстители',
//     'nameEn' => 'The Avengers',
//     'type' => 'FILM',
//     'year' => '2012',
//     'description' => 'США, Джосс Уидон(фантастика)',
//     'filmLength' => '2:17',
//     'countries' => [['country' => 'США']],
//     'genres' => [['genre' => 'фантастика']],
//     'rating' => '7.9',
//     'ratingVoteCount' => 284245,
//     'posterUrl' => 'https://example.com/poster.jpg',
//     'posterUrlPreview' => 'https://example.com/poster_small.jpg'
// ]
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ContentType;

// Создание клиента
$client = new Client('your-api-key');

// Поиск фильмов по ключевому слову
$filmService = $client->films;
$searchResults = $filmService->searchByKeyword('матрица');

echo "=== Результаты поиска по запросу 'матрица' ===\n";

// Обработка результатов поиска
foreach ($searchResults as $index => $result) {
    echo "\n" . ($index + 1) . ". {$result->getDisplayName()}\n";
    echo "   ID: {$result->filmId}\n";
    echo "   Тип: {$result->type->getDisplayName()}\n";

    if ($result->year) {
        echo "   Год: {$result->year}\n";
    }

    if ($result->filmLength) {
        echo "   Длительность: {$result->filmLength}\n";
    }

    if ($result->rating) {
        echo "   Рейтинг: {$result->rating}";
        if ($result->ratingVoteCount) {
            echo " ({$result->ratingVoteCount} голосов)";
        }
        echo "\n";
    }

    $countries = $result->getCountriesString();
    if ($countries) {
        echo "   Страны: {$countries}\n";
    }

    $genres = $result->getGenresString();
    if ($genres) {
        echo "   Жанры: {$genres}\n";
    }

    if ($result->description) {
        echo "   Описание: {$result->description}\n";
    }

    echo "   Постер: {$result->posterUrl}\n";
}
```

## Работа с результатами поиска

```php
// Функция для фильтрации по типу контента
function filterByContentType(array $searchResults, ContentType $type): array {
    return array_filter($searchResults, fn($result) => $result->type === $type);
}

// Функция для фильтрации по году
function filterByYear(array $searchResults, int $year): array {
    return array_filter($searchResults, fn($result) => $result->year == $year);
}

// Функция для фильтрации по рейтингу
function filterByRating(array $searchResults, float $minRating): array {
    return array_filter($searchResults, function($result) use ($minRating) {
        if (!$result->rating) return false;
        $rating = (float) $result->rating;
        return $rating >= $minRating;
    });
}

// Функция для фильтрации по жанру
function filterByGenre(array $searchResults, string $genreName): array {
    return array_filter($searchResults, function($result) use ($genreName) {
        foreach ($result->genres as $genre) {
            if (stripos($genre->genre, $genreName) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Функция для фильтрации по стране
function filterByCountry(array $searchResults, string $countryName): array {
    return array_filter($searchResults, function($result) use ($countryName) {
        foreach ($result->countries as $country) {
            if (stripos($country->country, $countryName) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Функция для сортировки по рейтингу
function sortByRating(array $searchResults, bool $descending = true): array {
    usort($searchResults, function($a, $b) use ($descending) {
        $ratingA = $a->rating ? (float) $a->rating : 0;
        $ratingB = $b->rating ? (float) $b->rating : 0;

        if ($descending) {
            return $ratingB <=> $ratingA;
        }
        return $ratingA <=> $ratingB;
    });

    return $searchResults;
}

// Функция для сортировки по году
function sortByYear(array $searchResults, bool $descending = true): array {
    usort($searchResults, function($a, $b) use ($descending) {
        $yearA = $a->year ? (int) $a->year : 0;
        $yearB = $b->year ? (int) $b->year : 0;

        if ($descending) {
            return $yearB <=> $yearA;
        }
        return $yearA <=> $yearB;
    });

    return $searchResults;
}

// Использование
$searchResults = $filmService->searchByKeyword('матрица');

// Фильтрация
$films = filterByContentType($searchResults, ContentType::FILM);
$series = filterByContentType($searchResults, ContentType::TV_SERIES);
$highRated = filterByRating($searchResults, 7.0);
$recent = filterByYear($searchResults, 2020);
$action = filterByGenre($searchResults, 'боевик');
$usFilms = filterByCountry($searchResults, 'США');

echo "Фильмов: " . count($films) . "\n";
echo "Сериалов: " . count($series) . "\n";
echo "С высоким рейтингом: " . count($highRated) . "\n";
echo "Современных: " . count($recent) . "\n";
echo "Боевиков: " . count($action) . "\n";
echo "Американских: " . count($usFilms) . "\n";

// Сортировка
$topRated = sortByRating($searchResults, true);
$newest = sortByYear($searchResults, true);
```

## Создание галереи результатов поиска

```php
class SearchResultsGallery {
    private array $searchResults;

    public function __construct(array $searchResults) {
        $this->searchResults = $searchResults;
    }

    public function getResults(): array {
        return $this->searchResults;
    }

    public function getFilms(): array {
        return filterByContentType($this->searchResults, ContentType::FILM);
    }

    public function getSeries(): array {
        return filterByContentType($this->searchResults, ContentType::TV_SERIES);
    }

    public function getHighRated(float $minRating = 7.0): array {
        return filterByRating($this->searchResults, $minRating);
    }

    public function getByGenre(string $genreName): array {
        return filterByGenre($this->searchResults, $genreName);
    }

    public function getByCountry(string $countryName): array {
        return filterByCountry($this->searchResults, $countryName);
    }

    public function getTopRated(int $limit = 10): array {
        $sorted = sortByRating($this->searchResults, true);
        return array_slice($sorted, 0, $limit);
    }

    public function getNewest(int $limit = 10): array {
        $sorted = sortByYear($this->searchResults, true);
        return array_slice($sorted, 0, $limit);
    }

    public function createHtmlGallery(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".gallery { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".gallery-title { text-align: center; margin-bottom: 30px; }\n";
        $html .= ".results-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }\n";
        $html .= ".result-card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: transform 0.2s; }\n";
        $html .= ".result-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }\n";
        $html .= ".result-poster { width: 100%; height: 200px; object-fit: cover; }\n";
        $html .= ".result-info { padding: 15px; }\n";
        $html .= ".result-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }\n";
        $html .= ".result-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".result-rating { font-weight: bold; color: #28a745; }\n";
        $html .= ".result-description { font-size: 12px; color: #888; margin-top: 10px; }\n";
        $html .= ".stats { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".film { border-left: 5px solid #28a745; }\n";
        $html .= ".series { border-left: 5px solid #007bff; }\n";
        $html .= ".unknown { border-left: 5px solid #6c757d; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='gallery'>\n";
        $html .= "<h1 class='gallery-title'>{$title}</h1>\n";

        // Статистика
        $films = $this->getFilms();
        $series = $this->getSeries();
        $highRated = $this->getHighRated();

        $html .= "<div class='stats'>\n";
        $html .= "<h2>Статистика поиска</h2>\n";
        $html .= "<p><strong>Всего результатов:</strong> " . count($this->searchResults) . "</p>\n";
        $html .= "<p><strong>Фильмов:</strong> " . count($films) . "</p>\n";
        $html .= "<p><strong>Сериалов:</strong> " . count($series) . "</p>\n";
        $html .= "<p><strong>С рейтингом 7+:</strong> " . count($highRated) . "</p>\n";
        $html .= "</div>\n";

        // Сетка результатов
        $html .= "<div class='results-grid'>\n";

        foreach ($this->searchResults as $result) {
            $cssClass = '';
            switch ($result->type) {
                case ContentType::FILM:
                    $cssClass = 'film';
                    break;
                case ContentType::TV_SERIES:
                case ContentType::MINI_SERIES:
                case ContentType::TV_SHOW:
                    $cssClass = 'series';
                    break;
                default:
                    $cssClass = 'unknown';
            }

            $html .= "<div class='result-card {$cssClass}'>\n";
            $html .= "<img src='{$result->posterUrlPreview}' alt='{$result->getDisplayName()}' class='result-poster'>\n";
            $html .= "<div class='result-info'>\n";
            $html .= "<div class='result-title'>{$result->getDisplayName()}</div>\n";

            if ($result->year) {
                $html .= "<div class='result-meta'>Год: {$result->year}</div>\n";
            }

            if ($result->filmLength) {
                $html .= "<div class='result-meta'>Длительность: {$result->filmLength}</div>\n";
            }

            if ($result->rating) {
                $html .= "<div class='result-rating'>★ {$result->rating}";
                if ($result->ratingVoteCount) {
                    $html .= " ({$result->ratingVoteCount})";
                }
                $html .= "</div>\n";
            }

            $countries = $result->getCountriesString();
            if ($countries) {
                $html .= "<div class='result-meta'>Страны: {$countries}</div>\n";
            }

            $genres = $result->getGenresString();
            if ($genres) {
                $html .= "<div class='result-meta'>Жанры: {$genres}</div>\n";
            }

            if ($result->description) {
                $html .= "<div class='result-description'>{$result->description}</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО РЕЗУЛЬТАТАМ ПОИСКА ===\n\n";

        $films = $this->getFilms();
        $series = $this->getSeries();
        $highRated = $this->getHighRated();
        $topRated = $this->getTopRated(5);
        $newest = $this->getNewest(5);

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего результатов: " . count($this->searchResults) . "\n";
        $report .= "Фильмов: " . count($films) . "\n";
        $report .= "Сериалов: " . count($series) . "\n";
        $report .= "С рейтингом 7+: " . count($highRated) . "\n\n";

        // Топ рейтинг
        $report .= "🏆 ТОП ПО РЕЙТИНГУ:\n";
        foreach ($topRated as $index => $result) {
            $report .= ($index + 1) . ". {$result->getDisplayName()}";
            if ($result->rating) {
                $report .= " (★ {$result->rating})";
            }
            if ($result->year) {
                $report .= " ({$result->year})";
            }
            $report .= "\n";
        }

        $report .= "\n";

        // Новейшие
        $report .= "🆕 НОВЕЙШИЕ:\n";
        foreach ($newest as $index => $result) {
            $report .= ($index + 1) . ". {$result->getDisplayName()}";
            if ($result->year) {
                $report .= " ({$result->year})";
            }
            if ($result->rating) {
                $report .= " (★ {$result->rating})";
            }
            $report .= "\n";
        }

        $report .= "\n";

        // Детали по всем результатам
        $report .= "📋 ВСЕ РЕЗУЛЬТАТЫ:\n";
        foreach ($this->searchResults as $index => $result) {
            $report .= "\n" . ($index + 1) . ". {$result->getDisplayName()}\n";
            $report .= "   ID: {$result->filmId}\n";
            $report .= "   Тип: {$result->type->getDisplayName()}\n";

            if ($result->year) {
                $report .= "   Год: {$result->year}\n";
            }

            if ($result->filmLength) {
                $report .= "   Длительность: {$result->filmLength}\n";
            }

            if ($result->rating) {
                $report .= "   Рейтинг: {$result->rating}";
                if ($result->ratingVoteCount) {
                    $report .= " ({$result->ratingVoteCount} голосов)";
                }
                $report .= "\n";
            }

            $countries = $result->getCountriesString();
            if ($countries) {
                $report .= "   Страны: {$countries}\n";
            }

            $genres = $result->getGenresString();
            if ($genres) {
                $report .= "   Жанры: {$genres}\n";
            }

            if ($result->description) {
                $report .= "   Описание: {$result->description}\n";
            }
        }

        return $report;
    }
}

// Использование
$searchResults = $filmService->searchByKeyword('матрица');
$gallery = new SearchResultsGallery($searchResults);

// Создание HTML галереи
$htmlGallery = $gallery->createHtmlGallery('Результаты поиска: Матрица');
file_put_contents('search_results_gallery.html', $htmlGallery);
echo "✅ HTML галерея сохранена в search_results_gallery.html\n";

// Создание текстового отчета
$textReport = $gallery->createDetailedReport();
echo $textReport;
```

## Анализ результатов поиска

```php
function analyzeSearchResults(array $searchResults): array {
    $analysis = [
        'totalResults' => count($searchResults),
        'contentTypes' => [],
        'years' => [],
        'ratings' => [],
        'countries' => [],
        'genres' => [],
        'averageRating' => 0,
        'ratingDistribution' => [
            'excellent' => 0, // 9+
            'good' => 0,      // 7-8.9
            'average' => 0,   // 5-6.9
            'poor' => 0       // <5
        ]
    ];

    $totalRating = 0;
    $ratedCount = 0;

    foreach ($searchResults as $result) {
        // Статистика по типам контента
        $typeKey = $result->type->value;
        if (!isset($analysis['contentTypes'][$typeKey])) {
            $analysis['contentTypes'][$typeKey] = 0;
        }
        $analysis['contentTypes'][$typeKey]++;

        // Статистика по годам
        if ($result->year) {
            $year = (int) $result->year;
            if (!isset($analysis['years'][$year])) {
                $analysis['years'][$year] = 0;
            }
            $analysis['years'][$year]++;
        }

        // Статистика по рейтингам
        if ($result->rating) {
            $rating = (float) $result->rating;
            $totalRating += $rating;
            $ratedCount++;

            if (!isset($analysis['ratings'][$rating])) {
                $analysis['ratings'][$rating] = 0;
            }
            $analysis['ratings'][$rating]++;

            // Распределение по качеству
            if ($rating >= 9) {
                $analysis['ratingDistribution']['excellent']++;
            } elseif ($rating >= 7) {
                $analysis['ratingDistribution']['good']++;
            } elseif ($rating >= 5) {
                $analysis['ratingDistribution']['average']++;
            } else {
                $analysis['ratingDistribution']['poor']++;
            }
        }

        // Статистика по странам
        foreach ($result->countries as $country) {
            $countryName = $country->country;
            if (!isset($analysis['countries'][$countryName])) {
                $analysis['countries'][$countryName] = 0;
            }
            $analysis['countries'][$countryName]++;
        }

        // Статистика по жанрам
        foreach ($result->genres as $genre) {
            $genreName = $genre->genre;
            if (!isset($analysis['genres'][$genreName])) {
                $analysis['genres'][$genreName] = 0;
            }
            $analysis['genres'][$genreName]++;
        }
    }

    // Средний рейтинг
    if ($ratedCount > 0) {
        $analysis['averageRating'] = round($totalRating / $ratedCount, 2);
    }

    return $analysis;
}

// Использование
$searchResults = $filmService->searchByKeyword('матрица');
$analysis = analyzeSearchResults($searchResults);

echo "=== Анализ результатов поиска ===\n";
echo "Всего результатов: {$analysis['totalResults']}\n";
echo "Средний рейтинг: {$analysis['averageRating']}\n";

echo "\nРаспределение по типам контента:\n";
foreach ($analysis['contentTypes'] as $typeKey => $count) {
    $type = ContentType::from($typeKey);
    $percentage = round(($count / $analysis['totalResults']) * 100, 1);
    echo "- {$type->getDisplayName()}: {$count} ({$percentage}%)\n";
}

echo "\nРаспределение по рейтингам:\n";
echo "- Отличные (9+): {$analysis['ratingDistribution']['excellent']}\n";
echo "- Хорошие (7-8.9): {$analysis['ratingDistribution']['good']}\n";
echo "- Средние (5-6.9): {$analysis['ratingDistribution']['average']}\n";
echo "- Плохие (<5): {$analysis['ratingDistribution']['poor']}\n";

echo "\nТоп стран:\n";
arsort($analysis['countries']);
$topCountries = array_slice($analysis['countries'], 0, 5, true);
foreach ($topCountries as $country => $count) {
    echo "- {$country}: {$count} результатов\n";
}

echo "\nТоп жанров:\n";
arsort($analysis['genres']);
$topGenres = array_slice($analysis['genres'], 0, 5, true);
foreach ($topGenres as $genre => $count) {
    echo "- {$genre}: {$count} результатов\n";
}
```

## Связанные классы

- [`ContentType`](../enums/content-type.md) - Типы контента
- [`Country`](country.md) - Модель страны
- [`Genre`](genre.md) - Модель жанра
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
