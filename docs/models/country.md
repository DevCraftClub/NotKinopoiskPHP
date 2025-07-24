# Country

Модель страны из Kinopoisk API.

## Описание

`Country` представляет информацию о стране производства фильма или сериала. Простая модель для хранения названия страны.

### Основные возможности

- Хранение названия страны в неизменяемом виде
- Создание объекта из массива данных API
- Автоматическое преобразование в строку

## Свойства

### Основная информация

- `$country` (string) - Название страны
- `$id` (int|null) - Уникальный идентификатор страны в Кинопоиске

## Конструктор

```php
public function __construct(
    public readonly string $country,
    public readonly ?int $id = NULL,
)
```

### Пример создания

```php
$country = new Country('США');
$countryWithId = new Country('Россия', 1);
```

## Методы

### \_\_toString()

Преобразует объект страны в строку.

```php
public function __toString(): string
```

#### Возвращаемое значение

- `string` - Название страны

#### Описание

Магический метод, который автоматически вызывается при попытке преобразования объекта Country в строковое представление.

#### Пример использования

```php
$country = new Country('США');
echo $country; // Выведет: США
echo (string) $country; // Выведет: США

// Использование в строковом контексте
$message = "Фильм снят в стране: {$country}";
```

### fromArray()

Создает экземпляр страны из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных страны от API

#### Возвращаемое значение

- `self` - Новый экземпляр страны

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = ['country' => 'США'];
$country = Country::fromArray($apiData);
```

### toArray()

Преобразует объект страны в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными страны

#### Пример использования

```php
$countryArray = $country->toArray();
echo json_encode($countryArray); // JSON представление страны
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Country;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о фильме
$filmService = $client->films;
$film = $filmService->getById(301); // Матрица

echo "=== Страны производства фильма 'Матрица' ===\n";

// Вывод стран производства
if (!empty($film->countries)) {
    echo "Страны производства:\n";
    foreach ($film->countries as $index => $country) {
        echo ($index + 1) . ". {$country}\n";
    }
} else {
    echo "Информация о странах производства отсутствует\n";
}

// Создание объектов стран
$countries = [
    new Country('США'),
    new Country('Австралия'),
    new Country('Великобритания')
];

echo "\nСозданные страны:\n";
foreach ($countries as $country) {
    echo "- {$country}\n";
}
```

## Работа со странами

```php
// Функция для фильтрации фильмов по стране
function filterFilmsByCountry(array $films, string $countryName): array {
    return array_filter($films, function($film) use ($countryName) {
        foreach ($film->countries as $country) {
            if (strcasecmp($country->country, $countryName) === 0) {
                return true;
            }
        }
        return false;
    });
}

// Функция для получения статистики по странам
function getCountriesStatistics(array $films): array {
    $stats = [];

    foreach ($films as $film) {
        foreach ($film->countries as $country) {
            $countryName = $country->country;
            if (!isset($stats[$countryName])) {
                $stats[$countryName] = 0;
            }
            $stats[$countryName]++;
        }
    }

    arsort($stats);
    return $stats;
}

// Функция для поиска фильмов по нескольким странам
function findFilmsByMultipleCountries(array $films, array $countryNames): array {
    return array_filter($films, function($film) use ($countryNames) {
        $filmCountries = array_map(fn($country) => $country->country, $film->countries);

        foreach ($countryNames as $countryName) {
            if (in_array($countryName, $filmCountries)) {
                return true;
            }
        }
        return false;
    });
}

// Использование
$films = $filmService->getTopFilms();

// Фильтрация по стране
$usFilms = filterFilmsByCountry($films, 'США');
echo "Фильмов из США: " . count($usFilms) . "\n";

// Получение статистики
$stats = getCountriesStatistics($films);
echo "Топ стран по количеству фильмов:\n";
foreach (array_slice($stats, 0, 10, true) as $country => $count) {
    echo "- {$country}: {$count} фильмов\n";
}

// Поиск по нескольким странам
$europeanFilms = findFilmsByMultipleCountries($films, ['Франция', 'Германия', 'Италия']);
echo "Европейских фильмов: " . count($europeanFilms) . "\n";
```

## Создание отчета по странам

```php
class CountriesReport {
    private array $films;

    public function __construct(array $films) {
        $this->films = $films;
    }

    public function getTopCountries(int $limit = 10): array {
        $stats = getCountriesStatistics($this->films);
        return array_slice($stats, 0, $limit, true);
    }

    public function getFilmsByCountry(string $countryName): array {
        return filterFilmsByCountry($this->films, $countryName);
    }

    public function getCountriesWithMostFilms(int $minFilms = 5): array {
        $stats = getCountriesStatistics($this->films);
        return array_filter($stats, fn($count) => $count >= $minFilms);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО СТРАНАМ ПРОИЗВОДСТВА ===\n\n";

        $stats = getCountriesStatistics($this->films);
        $totalFilms = count($this->films);

        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего фильмов: {$totalFilms}\n";
        $report .= "Уникальных стран: " . count($stats) . "\n\n";

        $report .= "🏆 ТОП-10 СТРАН:\n";
        foreach (array_slice($stats, 0, 10, true) as $country => $count) {
            $percentage = round(($count / $totalFilms) * 100, 1);
            $report .= "• {$country}: {$count} фильмов ({$percentage}%)\n";
        }

        $report .= "\n📈 СТРАНЫ С БОЛЬШИМ КОЛИЧЕСТВОМ ФИЛЬМОВ:\n";
        $majorCountries = $this->getCountriesWithMostFilms(10);
        foreach ($majorCountries as $country => $count) {
            $report .= "• {$country}: {$count} фильмов\n";
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1000px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".stats { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }\n";
        $html .= ".country-item { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".country-name { font-weight: bold; font-size: 16px; }\n";
        $html .= ".country-count { color: #666; }\n";
        $html .= ".country-percentage { color: #28a745; font-weight: bold; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = getCountriesStatistics($this->films);
        $totalFilms = count($this->films);

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего фильмов:</strong> {$totalFilms}</p>\n";
        $html .= "<p><strong>Уникальных стран:</strong> " . count($stats) . "</p>\n";
        $html .= "</div>\n";

        // Топ стран
        $html .= "<h2>Топ стран по количеству фильмов</h2>\n";
        foreach (array_slice($stats, 0, 20, true) as $country => $count) {
            $percentage = round(($count / $totalFilms) * 100, 1);
            $html .= "<div class='country-item'>\n";
            $html .= "<div class='country-name'>{$country}</div>\n";
            $html .= "<div class='country-count'>{$count} фильмов</div>\n";
            $html .= "<div class='country-percentage'>{$percentage}%</div>\n";
            $html .= "</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$films = $filmService->getTopFilms();
$report = new CountriesReport($films);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Статистика по странам производства');
file_put_contents('countries_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в countries_report.html\n";
```

## Анализ географического распределения

```php
function analyzeGeographicDistribution(array $films): array {
    $analysis = [
        'total' => count($films),
        'countries' => [],
        'continents' => [],
        'mostDiverse' => [],
        'singleCountry' => 0,
        'multiCountry' => 0
    ];

    // Определение континентов
    $continentMap = [
        'США' => 'Северная Америка',
        'Канада' => 'Северная Америка',
        'Мексика' => 'Северная Америка',
        'Великобритания' => 'Европа',
        'Франция' => 'Европа',
        'Германия' => 'Европа',
        'Италия' => 'Европа',
        'Испания' => 'Европа',
        'Россия' => 'Европа',
        'Китай' => 'Азия',
        'Япония' => 'Азия',
        'Южная Корея' => 'Азия',
        'Индия' => 'Азия',
        'Австралия' => 'Океания',
        'Бразилия' => 'Южная Америка',
        'Аргентина' => 'Южная Америка'
    ];

    $countryStats = [];
    $continentStats = [];

    foreach ($films as $film) {
        $filmCountries = $film->countries;
        $countryCount = count($filmCountries);

        // Подсчет фильмов по количеству стран
        if ($countryCount === 1) {
            $analysis['singleCountry']++;
        } else {
            $analysis['multiCountry']++;
        }

        // Статистика по странам
        foreach ($filmCountries as $country) {
            $countryName = $country->country;

            if (!isset($countryStats[$countryName])) {
                $countryStats[$countryName] = 0;
            }
            $countryStats[$countryName]++;

            // Статистика по континентам
            $continent = $continentMap[$countryName] ?? 'Другое';
            if (!isset($continentStats[$continent])) {
                $continentStats[$continent] = 0;
            }
            $continentStats[$continent]++;
        }

        // Фильмы с наибольшим разнообразием стран
        if ($countryCount > 3) {
            $analysis['mostDiverse'][] = [
                'film' => $film,
                'countries' => $filmCountries,
                'count' => $countryCount
            ];
        }
    }

    $analysis['countries'] = $countryStats;
    $analysis['continents'] = $continentStats;

    // Сортировка
    arsort($analysis['countries']);
    arsort($analysis['continents']);

    return $analysis;
}

// Использование
$films = $filmService->getTopFilms();
$analysis = analyzeGeographicDistribution($films);

echo "=== Анализ географического распределения ===\n";
echo "Всего фильмов: {$analysis['total']}\n";
echo "Фильмов из одной страны: {$analysis['singleCountry']}\n";
echo "Международных фильмов: {$analysis['multiCountry']}\n";

echo "\nТоп континентов:\n";
foreach (array_slice($analysis['continents'], 0, 5, true) as $continent => $count) {
    echo "- {$continent}: {$count} упоминаний\n";
}

echo "\nФильмы с наибольшим разнообразием стран:\n";
foreach (array_slice($analysis['mostDiverse'], 0, 5) as $item) {
    $film = $item['film'];
    $countries = array_map(fn($c) => $c->country, $item['countries']);
    echo "- {$film->nameRu}: " . implode(', ', $countries) . " ({$item['count']} стран)\n";
}
```

## Связанные классы

- [`Film`](film.md) - Модель фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
