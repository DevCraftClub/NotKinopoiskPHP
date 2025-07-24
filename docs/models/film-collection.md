# FilmCollection

Модель элемента коллекции фильмов из Kinopoisk API.

## Описание

`FilmCollection` представляет упрощенную информацию о фильме в коллекциях, содержащую только основные поля без детальной информации. Используется в ответах API для коллекций фильмов.

### Основные возможности

- Хранение базовой информации о фильме в неизменяемом виде
- Создание объекта из массива данных API
- Удобные методы для работы с названиями фильмов

## Свойства

### Основная информация

- `$kinopoiskId` (int) - Уникальный идентификатор фильма в Кинопоиске
- `$imdbId` (string|null) - Идентификатор фильма в IMDb
- `$nameRu` (string|null) - Название фильма на русском языке
- `$nameEn` (string|null) - Название фильма на английском языке
- `$nameOriginal` (string|null) - Оригинальное название фильма
- `$countries` (array<Country>) - Массив стран производства
- `$genres` (array<Genre>) - Массив жанров фильма
- `$ratingKinopoisk` (float|null) - Рейтинг на Кинопоиске
- `$ratingImbd` (float|null) - Рейтинг на IMDb
- `$year` (int|null) - Год выпуска фильма
- `$type` (ContentType) - Тип контента (фильм, сериал и т.д.)
- `$posterUrl` (string) - URL постера фильма
- `$posterUrlPreview` (string) - URL превью постера фильма

## Конструктор

```php
public function __construct(
    public readonly int $kinopoiskId,
    public readonly ?string $imdbId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $nameOriginal,
    public readonly array $countries,
    public readonly array $genres,
    public readonly ?float $ratingKinopoisk,
    public readonly ?float $ratingImbd,
    public readonly ?int $year,
    public readonly ContentType $type,
    public readonly string $posterUrl,
    public readonly string $posterUrlPreview,
)
```

### Пример создания

```php
$item = new FilmCollection(
    kinopoiskId: 301,
    nameRu: 'Матрица',
    nameEn: 'The Matrix',
    nameOriginal: 'The Matrix',
    countries: [new Country('США')],
    genres: [new Genre('боевик')],
    ratingKinopoisk: 8.7,
    ratingImbd: 8.7,
    year: 1999,
    type: ContentType::FILM,
    posterUrl: 'https://example.com/poster.jpg',
    posterUrlPreview: 'https://example.com/poster_small.jpg'
);
```

## Методы

### fromArray()

Создает экземпляр элемента коллекции из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных элемента коллекции от API

#### Возвращаемое значение

- `self` - Новый экземпляр элемента коллекции

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Описание

Статический метод для удобного создания объекта FilmCollection из данных, полученных от Kinopoisk API. Автоматически обрабатывает nullable поля и устанавливает значения по умолчанию.

#### Пример использования

```php
$apiData = [
    'kinopoiskId' => 301,
    'nameRu' => 'Матрица',
    'nameEn' => 'The Matrix',
    'nameOriginal' => 'The Matrix',
    'countries' => [['country' => 'США']],
    'genres' => [['genre' => 'боевик']],
    'ratingKinopoisk' => 8.7,
    'ratingImbd' => 8.7,
    'year' => 1999,
    'type' => 'FILM',
    'posterUrl' => 'https://example.com/poster.jpg',
    'posterUrlPreview' => 'https://example.com/poster_small.jpg'
];

$item = FilmCollection::fromArray($apiData);
```

### getDisplayName()

Получает отображаемое название фильма.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название фильма

#### Описание

Возвращает наиболее подходящее название для отображения пользователю. Приоритет: русское название → английское название → оригинальное название → "Без названия"

#### Пример использования

```php
echo $item->getDisplayName(); // "Матрица" или "The Matrix" или "Без названия"
```

### getMainRating()

Получает основной рейтинг фильма.

```php
public function getMainRating(): ?float
```

#### Возвращаемое значение

- `float|null` - Основной рейтинг или null если рейтинги отсутствуют

#### Описание

Возвращает наиболее значимый рейтинг из доступных. Приоритет: рейтинг Кинопоиска → рейтинг IMDb

#### Пример использования

```php
$rating = $item->getMainRating();
if ($rating !== null) {
    echo "Рейтинг: {$rating}";
} else {
    echo "Рейтинг не указан";
}
```

### isSerial()

Проверяет, является ли контент сериалом.

```php
public function isSerial(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это сериал, `false` если фильм

#### Описание

Определяет тип контента на основе поля type. Возвращает true для сериалов и телешоу.

#### Пример использования

```php
if ($item->isSerial()) {
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
echo "Страны: {$item->getCountriesString()}"; // "США, Великобритания"
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
echo "Жанры: {$item->getGenresString()}"; // "боевик, фантастика"
```

### toArray()

Преобразует объект элемента коллекции фильмов в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными элемента коллекции

#### Пример использования

```php
$itemArray = $item->toArray();
echo json_encode($itemArray); // JSON представление элемента коллекции
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ContentType;

// Создание клиента
$client = new Client('your-api-key');

// Получение коллекции фильмов
$filmService = $client->films;
$collection = $filmService->getTopFilms();

echo "=== Коллекция топ фильмов ===\n";

// Обработка элементов коллекции
foreach ($collection as $index => $item) {
    echo "\n" . ($index + 1) . ". {$item->getDisplayName()}\n";
    echo "   ID: {$item->kinopoiskId}\n";
    echo "   Тип: {$item->type->getDisplayName()}\n";

    if ($item->year) {
        echo "   Год: {$item->year}\n";
    }

    $rating = $item->getMainRating();
    if ($rating !== null) {
        echo "   Рейтинг: {$rating}\n";
    }

    $countries = $item->getCountriesString();
    if ($countries) {
        echo "   Страны: {$countries}\n";
    }

    $genres = $item->getGenresString();
    if ($genres) {
        echo "   Жанры: {$genres}\n";
    }

    echo "   Постер: {$item->posterUrl}\n";
}
```

## Работа с коллекциями фильмов

```php
// Функция для фильтрации по типу контента
function filterByContentType(array $collection, ContentType $type): array {
    return array_filter($collection, fn($item) => $item->type === $type);
}

// Функция для фильтрации по году
function filterByYear(array $collection, int $year): array {
    return array_filter($collection, fn($item) => $item->year == $year);
}

// Функция для фильтрации по рейтингу
function filterByRating(array $collection, float $minRating): array {
    return array_filter($collection, function($item) use ($minRating) {
        $rating = $item->getMainRating();
        return $rating !== null && $rating >= $minRating;
    });
}

// Функция для фильтрации по жанру
function filterByGenre(array $collection, string $genreName): array {
    return array_filter($collection, function($item) use ($genreName) {
        foreach ($item->genres as $genre) {
            if (stripos($genre->genre, $genreName) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Функция для фильтрации по стране
function filterByCountry(array $collection, string $countryName): array {
    return array_filter($collection, function($item) use ($countryName) {
        foreach ($item->countries as $country) {
            if (stripos($country->country, $countryName) !== false) {
                return true;
            }
        }
        return false;
    });
}

// Функция для сортировки по рейтингу
function sortByRating(array $collection, bool $descending = true): array {
    usort($collection, function($a, $b) use ($descending) {
        $ratingA = $a->getMainRating() ?? 0;
        $ratingB = $b->getMainRating() ?? 0;

        if ($descending) {
            return $ratingB <=> $ratingA;
        }
        return $ratingA <=> $ratingB;
    });

    return $collection;
}

// Функция для сортировки по году
function sortByYear(array $collection, bool $descending = true): array {
    usort($collection, function($a, $b) use ($descending) {
        $yearA = $a->year ?? 0;
        $yearB = $b->year ?? 0;

        if ($descending) {
            return $yearB <=> $yearA;
        }
        return $yearA <=> $yearB;
    });

    return $collection;
}

// Использование
$collection = $filmService->getTopFilms();

// Фильтрация
$films = filterByContentType($collection, ContentType::FILM);
$series = filterByContentType($collection, ContentType::SERIES);
$highRated = filterByRating($collection, 8.0);
$recent = filterByYear($collection, 2020);
$action = filterByGenre($collection, 'боевик');
$usFilms = filterByCountry($collection, 'США');

echo "Фильмов: " . count($films) . "\n";
echo "Сериалов: " . count($series) . "\n";
echo "С высоким рейтингом: " . count($highRated) . "\n";
echo "Современных: " . count($recent) . "\n";
echo "Боевиков: " . count($action) . "\n";
echo "Американских: " . count($usFilms) . "\n";

// Сортировка
$topRated = sortByRating($collection, true);
$newest = sortByYear($collection, true);
```

## Создание галереи коллекции

```php
class FilmCollectionGallery {
    private array $collection;

    public function __construct(array $collection) {
        $this->collection = $collection;
    }

    public function getCollection(): array {
        return $this->collection;
    }

    public function getFilms(): array {
        return filterByContentType($this->collection, ContentType::FILM);
    }

    public function getSeries(): array {
        return filterByContentType($this->collection, ContentType::SERIES);
    }

    public function getHighRated(float $minRating = 8.0): array {
        return filterByRating($this->collection, $minRating);
    }

    public function getByGenre(string $genreName): array {
        return filterByGenre($this->collection, $genreName);
    }

    public function getByCountry(string $countryName): array {
        return filterByCountry($this->collection, $countryName);
    }

    public function getTopRated(int $limit = 10): array {
        $sorted = sortByRating($this->collection, true);
        return array_slice($sorted, 0, $limit);
    }

    public function getNewest(int $limit = 10): array {
        $sorted = sortByYear($this->collection, true);
        return array_slice($sorted, 0, $limit);
    }

    public function createHtmlGallery(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".gallery { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".gallery-title { text-align: center; margin-bottom: 30px; }\n";
        $html .= ".collection-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }\n";
        $html .= ".film-card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: transform 0.2s; }\n";
        $html .= ".film-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }\n";
        $html .= ".film-poster { width: 100%; height: 200px; object-fit: cover; }\n";
        $html .= ".film-info { padding: 15px; }\n";
        $html .= ".film-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }\n";
        $html .= ".film-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".film-rating { font-weight: bold; color: #28a745; }\n";
        $html .= ".stats { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".film { border-left: 5px solid #28a745; }\n";
        $html .= ".series { border-left: 5px solid #007bff; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='gallery'>\n";
        $html .= "<h1 class='gallery-title'>{$title}</h1>\n";

        // Статистика
        $films = $this->getFilms();
        $series = $this->getSeries();
        $highRated = $this->getHighRated();

        $html .= "<div class='stats'>\n";
        $html .= "<h2>Статистика коллекции</h2>\n";
        $html .= "<p><strong>Всего элементов:</strong> " . count($this->collection) . "</p>\n";
        $html .= "<p><strong>Фильмов:</strong> " . count($films) . "</p>\n";
        $html .= "<p><strong>Сериалов:</strong> " . count($series) . "</p>\n";
        $html .= "<p><strong>С рейтингом 8+:</strong> " . count($highRated) . "</p>\n";
        $html .= "</div>\n";

        // Сетка фильмов
        $html .= "<div class='collection-grid'>\n";

        foreach ($this->collection as $item) {
            $cssClass = $item->isSerial() ? 'series' : 'film';

            $html .= "<div class='film-card {$cssClass}'>\n";
            $html .= "<img src='{$item->posterUrlPreview}' alt='{$item->getDisplayName()}' class='film-poster'>\n";
            $html .= "<div class='film-info'>\n";
            $html .= "<div class='film-title'>{$item->getDisplayName()}</div>\n";

            if ($item->year) {
                $html .= "<div class='film-meta'>Год: {$item->year}</div>\n";
            }

            $rating = $item->getMainRating();
            if ($rating !== null) {
                $html .= "<div class='film-rating'>★ {$rating}</div>\n";
            }

            $countries = $item->getCountriesString();
            if ($countries) {
                $html .= "<div class='film-meta'>Страны: {$countries}</div>\n";
            }

            $genres = $item->getGenresString();
            if ($genres) {
                $html .= "<div class='film-meta'>Жанры: {$genres}</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО КОЛЛЕКЦИИ ФИЛЬМОВ ===\n\n";

        $films = $this->getFilms();
        $series = $this->getSeries();
        $highRated = $this->getHighRated();
        $topRated = $this->getTopRated(5);
        $newest = $this->getNewest(5);

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего элементов: " . count($this->collection) . "\n";
        $report .= "Фильмов: " . count($films) . "\n";
        $report .= "Сериалов: " . count($series) . "\n";
        $report .= "С рейтингом 8+: " . count($highRated) . "\n\n";

        // Топ рейтинг
        $report .= "🏆 ТОП ПО РЕЙТИНГУ:\n";
        foreach ($topRated as $index => $item) {
            $report .= ($index + 1) . ". {$item->getDisplayName()}";
            $rating = $item->getMainRating();
            if ($rating !== null) {
                $report .= " (★ {$rating})";
            }
            if ($item->year) {
                $report .= " ({$item->year})";
            }
            $report .= "\n";
        }

        $report .= "\n";

        // Новейшие
        $report .= "🆕 НОВЕЙШИЕ:\n";
        foreach ($newest as $index => $item) {
            $report .= ($index + 1) . ". {$item->getDisplayName()}";
            if ($item->year) {
                $report .= " ({$item->year})";
            }
            $rating = $item->getMainRating();
            if ($rating !== null) {
                $report .= " (★ {$rating})";
            }
            $report .= "\n";
        }

        $report .= "\n";

        // Детали по всем элементам
        $report .= "📋 ВСЕ ЭЛЕМЕНТЫ КОЛЛЕКЦИИ:\n";
        foreach ($this->collection as $index => $item) {
            $report .= "\n" . ($index + 1) . ". {$item->getDisplayName()}\n";
            $report .= "   ID: {$item->kinopoiskId}\n";
            $report .= "   Тип: {$item->type->getDisplayName()}\n";

            if ($item->year) {
                $report .= "   Год: {$item->year}\n";
            }

            $rating = $item->getMainRating();
            if ($rating !== null) {
                $report .= "   Рейтинг: {$rating}\n";
            }

            $countries = $item->getCountriesString();
            if ($countries) {
                $report .= "   Страны: {$countries}\n";
            }

            $genres = $item->getGenresString();
            if ($genres) {
                $report .= "   Жанры: {$genres}\n";
            }
        }

        return $report;
    }
}

// Использование
$collection = $filmService->getTopFilms();
$gallery = new FilmCollectionGallery($collection);

// Создание HTML галереи
$htmlGallery = $gallery->createHtmlGallery('Коллекция топ фильмов');
file_put_contents('film_collection_gallery.html', $htmlGallery);
echo "✅ HTML галерея сохранена в film_collection_gallery.html\n";

// Создание текстового отчета
$textReport = $gallery->createDetailedReport();
echo $textReport;
```

## Анализ коллекции

```php
function analyzeFilmCollection(array $collection): array {
    $analysis = [
        'totalItems' => count($collection),
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

    foreach ($collection as $item) {
        // Статистика по типам контента
        $typeKey = $item->type->value;
        if (!isset($analysis['contentTypes'][$typeKey])) {
            $analysis['contentTypes'][$typeKey] = 0;
        }
        $analysis['contentTypes'][$typeKey]++;

        // Статистика по годам
        if ($item->year) {
            $year = (int) $item->year;
            if (!isset($analysis['years'][$year])) {
                $analysis['years'][$year] = 0;
            }
            $analysis['years'][$year]++;
        }

        // Статистика по рейтингам
        $rating = $item->getMainRating();
        if ($rating !== null) {
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
        foreach ($item->countries as $country) {
            $countryName = $country->country;
            if (!isset($analysis['countries'][$countryName])) {
                $analysis['countries'][$countryName] = 0;
            }
            $analysis['countries'][$countryName]++;
        }

        // Статистика по жанрам
        foreach ($item->genres as $genre) {
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
$collection = $filmService->getTopFilms();
$analysis = analyzeFilmCollection($collection);

echo "=== Анализ коллекции фильмов ===\n";
echo "Всего элементов: {$analysis['totalItems']}\n";
echo "Средний рейтинг: {$analysis['averageRating']}\n";

echo "\nРаспределение по типам контента:\n";
foreach ($analysis['contentTypes'] as $typeKey => $count) {
    $type = ContentType::from($typeKey);
    $percentage = round(($count / $analysis['totalItems']) * 100, 1);
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
    echo "- {$country}: {$count} фильмов\n";
}

echo "\nТоп жанров:\n";
arsort($analysis['genres']);
$topGenres = array_slice($analysis['genres'], 0, 5, true);
foreach ($topGenres as $genre => $count) {
    echo "- {$genre}: {$count} фильмов\n";
}
```

## Связанные классы

- [`ContentType`](../enums/content-type.md) - Типы контента
- [`Country`](country.md) - Модель страны
- [`Genre`](genre.md) - Модель жанра
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
