# Filters

Модель фильтров из Kinopoisk API.

## Описание

`Filters` представляет доступные фильтры для поиска фильмов, включая списки жанров и стран для фильтрации.

### Основные возможности

- Хранение списков доступных фильтров в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к спискам жанров и стран

**API Endpoint:** `/api/v2.2/films/filters`

## Свойства

### Основная информация

- `$genres` (array<Genre>) - Массив доступных жанров
- `$countries` (array<Country>) - Массив доступных стран

## Конструктор

```php
public function __construct(
    public readonly array $genres,
    public readonly array $countries,
)
```

### Пример создания

```php
$filters = new Filters(
    genres: [$genre1, $genre2, $genre3],
    countries: [$country1, $country2]
);
```

## Методы

### fromArray()

Создает экземпляр фильтров из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных фильтров от API

#### Возвращаемое значение

- `self` - Новый экземпляр фильтров

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Описание

Статический метод для удобного создания объекта Filters из данных, полученных от Kinopoisk API. Автоматически создает объекты Genre и Country для каждого элемента в соответствующих массивах.

#### Пример использования

```php
$apiData = [
    'genres' => [
        ['genre' => 'Боевик'],
        ['genre' => 'Драма'],
        ['genre' => 'Комедия']
    ],
    'countries' => [
        ['country' => 'США'],
        ['country' => 'Россия'],
        ['country' => 'Великобритания']
    ]
];

$filters = Filters::fromArray($apiData);
```

### toArray()

Преобразует объект фильтров в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными фильтров

#### Пример использования

```php
$filtersArray = $filters->toArray();
echo json_encode($filtersArray); // JSON представление фильтров
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение доступных фильтров
$filmService = $client->films;
$filters = $filmService->getFilters();

echo "=== Доступные фильтры для поиска ===\n";

// Общая статистика
echo "Доступно жанров: " . count($filters->genres) . "\n";
echo "Доступно стран: " . count($filters->countries) . "\n\n";

// Вывод всех жанров
echo "🎭 ДОСТУПНЫЕ ЖАНРЫ:\n";
foreach ($filters->genres as $genre) {
    echo "- {$genre->genre}\n";
}

echo "\n";

// Вывод всех стран
echo "🌍 ДОСТУПНЫЕ СТРАНЫ:\n";
foreach ($filters->countries as $country) {
    echo "- {$country->country}\n";
}
```

## Работа с фильтрами

```php
// Функция для поиска жанра по названию
function findGenreByName(array $genres, string $name): ?Genre {
    foreach ($genres as $genre) {
        if (stripos($genre->genre, $name) !== false) {
            return $genre;
        }
    }
    return null;
}

// Функция для поиска страны по названию
function findCountryByName(array $countries, string $name): ?Country {
    foreach ($countries as $country) {
        if (stripos($country->country, $name) !== false) {
            return $country;
        }
    }
    return null;
}

// Функция для получения жанров по категориям
function categorizeGenres(array $genres): array {
    $categories = [
        'action' => [],
        'drama' => [],
        'comedy' => [],
        'horror' => [],
        'sci_fi' => [],
        'other' => []
    ];

    foreach ($genres as $genre) {
        $genreName = strtolower($genre->genre);

        if (strpos($genreName, 'боевик') !== false || strpos($genreName, 'экшен') !== false) {
            $categories['action'][] = $genre;
        } elseif (strpos($genreName, 'драма') !== false) {
            $categories['drama'][] = $genre;
        } elseif (strpos($genreName, 'комедия') !== false) {
            $categories['comedy'][] = $genre;
        } elseif (strpos($genreName, 'ужас') !== false || strpos($genreName, 'хоррор') !== false) {
            $categories['horror'][] = $genre;
        } elseif (strpos($genreName, 'фантастика') !== false || strpos($genreName, 'научная') !== false) {
            $categories['sci_fi'][] = $genre;
        } else {
            $categories['other'][] = $genre;
        }
    }

    return $categories;
}

// Функция для получения стран по регионам
function categorizeCountries(array $countries): array {
    $regions = [
        'europe' => [],
        'asia' => [],
        'america' => [],
        'africa' => [],
        'other' => []
    ];

    $europeanCountries = ['россия', 'великобритания', 'франция', 'германия', 'италия', 'испания'];
    $asianCountries = ['китай', 'япония', 'корея', 'индия', 'тайвань'];
    $americanCountries = ['сша', 'канада', 'мексика', 'бразилия', 'аргентина'];
    $africanCountries = ['египет', 'юар', 'нигерия', 'кения'];

    foreach ($countries as $country) {
        $countryName = strtolower($country->country);

        if (in_array($countryName, $europeanCountries)) {
            $regions['europe'][] = $country;
        } elseif (in_array($countryName, $asianCountries)) {
            $regions['asia'][] = $country;
        } elseif (in_array($countryName, $americanCountries)) {
            $regions['america'][] = $country;
        } elseif (in_array($countryName, $africanCountries)) {
            $regions['africa'][] = $country;
        } else {
            $regions['other'][] = $country;
        }
    }

    return $regions;
}

// Использование
$filters = $filmService->getFilters();

// Поиск конкретных жанров и стран
$actionGenre = findGenreByName($filters->genres, 'боевик');
$usCountry = findCountryByName($filters->countries, 'сша');

if ($actionGenre) {
    echo "Найден жанр: {$actionGenre->genre}\n";
}

if ($usCountry) {
    echo "Найдена страна: {$usCountry->country}\n";
}

// Категоризация жанров
$genreCategories = categorizeGenres($filters->genres);
echo "Боевиков: " . count($genreCategories['action']) . "\n";
echo "Драм: " . count($genreCategories['drama']) . "\n";
echo "Комедий: " . count($genreCategories['comedy']) . "\n";
echo "Ужасов: " . count($genreCategories['horror']) . "\n";
echo "Фантастики: " . count($genreCategories['sci_fi']) . "\n";
echo "Прочих: " . count($genreCategories['other']) . "\n";

// Категоризация стран
$countryRegions = categorizeCountries($filters->countries);
echo "Европейских стран: " . count($countryRegions['europe']) . "\n";
echo "Азиатских стран: " . count($countryRegions['asia']) . "\n";
echo "Американских стран: " . count($countryRegions['america']) . "\n";
echo "Африканских стран: " . count($countryRegions['africa']) . "\n";
echo "Прочих стран: " . count($countryRegions['other']) . "\n";
```

## Создание отчета по фильтрам

```php
class FiltersReport {
    private Filters $filters;

    public function __construct(Filters $filters) {
        $this->filters = $filters;
    }

    public function getFilters(): Filters {
        return $this->filters;
    }

    public function getGenres(): array {
        return $this->filters->genres;
    }

    public function getCountries(): array {
        return $this->filters->countries;
    }

    public function findGenreByName(string $name): ?Genre {
        return findGenreByName($this->filters->genres, $name);
    }

    public function findCountryByName(string $name): ?Country {
        return findCountryByName($this->filters->countries, $name);
    }

    public function getGenreCategories(): array {
        return categorizeGenres($this->filters->genres);
    }

    public function getCountryRegions(): array {
        return categorizeCountries($this->filters->countries);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ДОСТУПНЫМ ФИЛЬТРАМ ===\n\n";

        $genreCategories = $this->getGenreCategories();
        $countryRegions = $this->getCountryRegions();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего жанров: " . count($this->filters->genres) . "\n";
        $report .= "Всего стран: " . count($this->filters->countries) . "\n\n";

        // Статистика по жанрам
        $report .= "🎭 СТАТИСТИКА ПО ЖАНРАМ:\n";
        $report .= "• Боевики: " . count($genreCategories['action']) . "\n";
        $report .= "• Драмы: " . count($genreCategories['drama']) . "\n";
        $report .= "• Комедии: " . count($genreCategories['comedy']) . "\n";
        $report .= "• Ужасы: " . count($genreCategories['horror']) . "\n";
        $report .= "• Фантастика: " . count($genreCategories['sci_fi']) . "\n";
        $report .= "• Прочие: " . count($genreCategories['other']) . "\n\n";

        // Статистика по странам
        $report .= "🌍 СТАТИСТИКА ПО СТРАНАМ:\n";
        $report .= "• Европа: " . count($countryRegions['europe']) . "\n";
        $report .= "• Азия: " . count($countryRegions['asia']) . "\n";
        $report .= "• Америка: " . count($countryRegions['america']) . "\n";
        $report .= "• Африка: " . count($countryRegions['africa']) . "\n";
        $report .= "• Прочие: " . count($countryRegions['other']) . "\n\n";

        // Все жанры
        $report .= "📋 ВСЕ ДОСТУПНЫЕ ЖАНРЫ:\n";
        foreach ($this->filters->genres as $genre) {
            $report .= "• {$genre->genre}\n";
        }

        $report .= "\n";

        // Все страны
        $report .= "🌍 ВСЕ ДОСТУПНЫЕ СТРАНЫ:\n";
        foreach ($this->filters->countries as $country) {
            $report .= "• {$country->country}\n";
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
        $html .= ".filter-item { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".filter-name { font-weight: bold; font-size: 16px; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".genres-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }\n";
        $html .= ".countries-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }\n";
        $html .= ".action { border-left: 5px solid #dc3545; }\n";
        $html .= ".drama { border-left: 5px solid #007bff; }\n";
        $html .= ".comedy { border-left: 5px solid #ffc107; }\n";
        $html .= ".horror { border-left: 5px solid #6c757d; }\n";
        $html .= ".sci-fi { border-left: 5px solid #28a745; }\n";
        $html .= ".other { border-left: 5px solid #6f42c1; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $genreCategories = $this->getGenreCategories();
        $countryRegions = $this->getCountryRegions();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего жанров:</strong> " . count($this->filters->genres) . "</p>\n";
        $html .= "<p><strong>Всего стран:</strong> " . count($this->filters->countries) . "</p>\n";
        $html .= "</div>\n";

        // Статистика по жанрам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🎭 Статистика по жанрам</div>\n";
        $html .= "<p><strong>Боевики:</strong> " . count($genreCategories['action']) . "</p>\n";
        $html .= "<p><strong>Драмы:</strong> " . count($genreCategories['drama']) . "</p>\n";
        $html .= "<p><strong>Комедии:</strong> " . count($genreCategories['comedy']) . "</p>\n";
        $html .= "<p><strong>Ужасы:</strong> " . count($genreCategories['horror']) . "</p>\n";
        $html .= "<p><strong>Фантастика:</strong> " . count($genreCategories['sci_fi']) . "</p>\n";
        $html .= "<p><strong>Прочие:</strong> " . count($genreCategories['other']) . "</p>\n";
        $html .= "</div>\n";

        // Статистика по странам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🌍 Статистика по странам</div>\n";
        $html .= "<p><strong>Европа:</strong> " . count($countryRegions['europe']) . "</p>\n";
        $html .= "<p><strong>Азия:</strong> " . count($countryRegions['asia']) . "</p>\n";
        $html .= "<p><strong>Америка:</strong> " . count($countryRegions['america']) . "</p>\n";
        $html .= "<p><strong>Африка:</strong> " . count($countryRegions['africa']) . "</p>\n";
        $html .= "<p><strong>Прочие:</strong> " . count($countryRegions['other']) . "</p>\n";
        $html .= "</div>\n";

        // Все жанры
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📋 Все доступные жанры</div>\n";
        $html .= "<div class='genres-grid'>\n";

        foreach ($this->filters->genres as $genre) {
            $cssClass = 'other';
            $genreName = strtolower($genre->genre);

            if (strpos($genreName, 'боевик') !== false || strpos($genreName, 'экшен') !== false) {
                $cssClass = 'action';
            } elseif (strpos($genreName, 'драма') !== false) {
                $cssClass = 'drama';
            } elseif (strpos($genreName, 'комедия') !== false) {
                $cssClass = 'comedy';
            } elseif (strpos($genreName, 'ужас') !== false || strpos($genreName, 'хоррор') !== false) {
                $cssClass = 'horror';
            } elseif (strpos($genreName, 'фантастика') !== false || strpos($genreName, 'научная') !== false) {
                $cssClass = 'sci-fi';
            }

            $html .= "<div class='filter-item {$cssClass}'>\n";
            $html .= "<div class='filter-name'>{$genre->genre}</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n";

        // Все страны
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🌍 Все доступные страны</div>\n";
        $html .= "<div class='countries-grid'>\n";

        foreach ($this->filters->countries as $country) {
            $html .= "<div class='filter-item'>\n";
            $html .= "<div class='filter-name'>{$country->country}</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$filters = $filmService->getFilters();
$report = new FiltersReport($filters);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по доступным фильтрам');
file_put_contents('filters_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в filters_report.html\n";
```

## Анализ фильтров

```php
function analyzeFilters(Filters $filters): array {
    $analysis = [
        'totalGenres' => count($filters->genres),
        'totalCountries' => count($filters->countries),
        'genreCategories' => categorizeGenres($filters->genres),
        'countryRegions' => categorizeCountries($filters->countries),
        'popularGenres' => [],
        'popularCountries' => []
    ];

    // Анализ популярных жанров (по длине названия как пример)
    $genreLengths = [];
    foreach ($filters->genres as $genre) {
        $genreLengths[$genre->genre] = strlen($genre->genre);
    }
    arsort($genreLengths);
    $analysis['popularGenres'] = array_slice($genreLengths, 0, 10, true);

    // Анализ популярных стран (по алфавиту как пример)
    $countryNames = [];
    foreach ($filters->countries as $country) {
        $countryNames[] = $country->country;
    }
    sort($countryNames);
    $analysis['popularCountries'] = array_slice($countryNames, 0, 10);

    return $analysis;
}

// Использование
$filters = $filmService->getFilters();
$analysis = analyzeFilters($filters);

echo "=== Анализ фильтров ===\n";
echo "Всего жанров: {$analysis['totalGenres']}\n";
echo "Всего стран: {$analysis['totalCountries']}\n";

echo "\nРаспределение жанров по категориям:\n";
foreach ($analysis['genreCategories'] as $category => $genres) {
    $percentage = round((count($genres) / $analysis['totalGenres']) * 100, 1);
    echo "- {$category}: " . count($genres) . " жанров ({$percentage}%)\n";
}

echo "\nРаспределение стран по регионам:\n";
foreach ($analysis['countryRegions'] as $region => $countries) {
    $percentage = round((count($countries) / $analysis['totalCountries']) * 100, 1);
    echo "- {$region}: " . count($countries) . " стран ({$percentage}%)\n";
}

echo "\nТоп жанров по длине названия:\n";
foreach ($analysis['popularGenres'] as $genre => $length) {
    echo "- {$genre}: {$length} символов\n";
}

echo "\nПервые 10 стран по алфавиту:\n";
foreach ($analysis['popularCountries'] as $country) {
    echo "- {$country}\n";
}
```

## Связанные классы

- [`Genre`](genre.md) - Модель жанра
- [`Country`](country.md) - Модель страны
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
