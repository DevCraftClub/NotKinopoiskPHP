# Genre

Модель жанра из Kinopoisk API.

## Описание

`Genre` представляет информацию о жанре фильма или сериала. Простая модель для хранения названия жанра.

### Основные возможности

- Хранение названия жанра в неизменяемом виде
- Создание объекта из массива данных API
- Автоматическое преобразование в строку

## Свойства

### Основная информация

- `$genre` (string) - Название жанра
- `$id` (int|null) - Уникальный идентификатор жанра в Кинопоиске

## Конструктор

```php
public function __construct(
    public readonly string $genre,
    public readonly ?int $id = NULL,
)
```

### Пример создания

```php
$genre = new Genre('Боевик');
$genreWithId = new Genre('Драма', 1);
```

## Методы

### \_\_toString()

Преобразует объект жанра в строку.

```php
public function __toString(): string
```

#### Возвращаемое значение

- `string` - Название жанра

#### Описание

Магический метод, который автоматически вызывается при попытке преобразования объекта Genre в строковое представление.

#### Пример использования

```php
$genre = new Genre('Боевик');
echo $genre; // Выведет: Боевик
echo (string) $genre; // Выведет: Боевик

// Использование в строковом контексте
$message = "Жанр фильма: {$genre}";
echo $message; // Выведет: Жанр фильма: Боевик

// Использование в массивах и сравнениях
$genres = [$genre1, $genre2];
$genreNames = array_map('strval', $genres);
```

### fromArray()

Создает экземпляр жанра из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных жанра от API

#### Возвращаемое значение

- `self` - Новый экземпляр жанра

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = ['genre' => 'Боевик'];
$genre = Genre::fromArray($apiData);
```

### toArray()

Преобразует объект жанра в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными жанра

#### Пример использования

```php
$genreArray = $genre->toArray();
echo json_encode($genreArray); // JSON представление жанра
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Genre;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о фильме
$filmService = $client->films;
$film = $filmService->getById(301); // Матрица

echo "=== Жанры фильма 'Матрица' ===\n";

// Вывод жанров фильма
if (!empty($film->genres)) {
    echo "Жанры фильма:\n";
    foreach ($film->genres as $index => $genre) {
        echo ($index + 1) . ". {$genre}\n";
    }
} else {
    echo "Информация о жанрах отсутствует\n";
}

// Создание объектов жанров
$genres = [
    new Genre('Боевик'),
    new Genre('Фантастика'),
    new Genre('Триллер')
];

echo "\nСозданные жанры:\n";
foreach ($genres as $genre) {
    echo "- {$genre}\n";
}
```

## Работа с жанрами

```php
// Функция для фильтрации фильмов по жанру
function filterFilmsByGenre(array $films, string $genreName): array {
    return array_filter($films, function($film) use ($genreName) {
        foreach ($film->genres as $genre) {
            if (strcasecmp($genre->genre, $genreName) === 0) {
                return true;
            }
        }
        return false;
    });
}

// Функция для получения статистики по жанрам
function getGenresStatistics(array $films): array {
    $stats = [];

    foreach ($films as $film) {
        foreach ($film->genres as $genre) {
            $genreName = $genre->genre;
            if (!isset($stats[$genreName])) {
                $stats[$genreName] = 0;
            }
            $stats[$genreName]++;
        }
    }

    arsort($stats);
    return $stats;
}

// Функция для поиска фильмов по нескольким жанрам
function findFilmsByMultipleGenres(array $films, array $genreNames): array {
    return array_filter($films, function($film) use ($genreNames) {
        $filmGenres = array_map(fn($genre) => $genre->genre, $film->genres);

        foreach ($genreNames as $genreName) {
            if (in_array($genreName, $filmGenres)) {
                return true;
            }
        }
        return false;
    });
}

// Функция для анализа жанровых комбинаций
function analyzeGenreCombinations(array $films): array {
    $combinations = [];

    foreach ($films as $film) {
        if (count($film->genres) > 1) {
            $genreNames = array_map(fn($genre) => $genre->genre, $film->genres);
            sort($genreNames); // Сортировка для единообразия
            $combination = implode(' + ', $genreNames);

            if (!isset($combinations[$combination])) {
                $combinations[$combination] = 0;
            }
            $combinations[$combination]++;
        }
    }

    arsort($combinations);
    return $combinations;
}

// Использование
$films = $filmService->getTopFilms();

// Фильтрация по жанру
$actionFilms = filterFilmsByGenre($films, 'Боевик');
echo "Боевиков: " . count($actionFilms) . "\n";

// Получение статистики
$stats = getGenresStatistics($films);
echo "Топ жанров по количеству фильмов:\n";
foreach (array_slice($stats, 0, 10, true) as $genre => $count) {
    echo "- {$genre}: {$count} фильмов\n";
}

// Поиск по нескольким жанрам
$actionThrillers = findFilmsByMultipleGenres($films, ['Боевик', 'Триллер']);
echo "Боевиков-триллеров: " . count($actionThrillers) . "\n";

// Анализ комбинаций
$combinations = analyzeGenreCombinations($films);
echo "Популярные жанровые комбинации:\n";
foreach (array_slice($combinations, 0, 10, true) as $combination => $count) {
    echo "- {$combination}: {$count} фильмов\n";
}
```

## Создание отчета по жанрам

```php
class GenresReport {
    private array $films;

    public function __construct(array $films) {
        $this->films = $films;
    }

    public function getTopGenres(int $limit = 10): array {
        $stats = getGenresStatistics($this->films);
        return array_slice($stats, 0, $limit, true);
    }

    public function getFilmsByGenre(string $genreName): array {
        return filterFilmsByGenre($this->films, $genreName);
    }

    public function getGenreCombinations(int $minFilms = 3): array {
        $combinations = analyzeGenreCombinations($this->films);
        return array_filter($combinations, fn($count) => $count >= $minFilms);
    }

    public function getFilmsWithMultipleGenres(): array {
        return array_filter($this->films, fn($film) => count($film->genres) > 1);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ЖАНРАМ ===\n\n";

        $stats = getGenresStatistics($this->films);
        $totalFilms = count($this->films);
        $multiGenreFilms = count($this->getFilmsWithMultipleGenres());

        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего фильмов: {$totalFilms}\n";
        $report .= "Уникальных жанров: " . count($stats) . "\n";
        $report .= "Фильмов с несколькими жанрами: {$multiGenreFilms}\n\n";

        $report .= "🏆 ТОП-15 ЖАНРОВ:\n";
        foreach (array_slice($stats, 0, 15, true) as $genre => $count) {
            $percentage = round(($count / $totalFilms) * 100, 1);
            $report .= "• {$genre}: {$count} фильмов ({$percentage}%)\n";
        }

        $report .= "\n🎭 ПОПУЛЯРНЫЕ ЖАНРОВЫЕ КОМБИНАЦИИ:\n";
        $combinations = $this->getGenreCombinations(5);
        foreach (array_slice($combinations, 0, 10, true) as $combination => $count) {
            $report .= "• {$combination}: {$count} фильмов\n";
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".stats { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }\n";
        $html .= ".genre-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }\n";
        $html .= ".genre-name { font-weight: bold; font-size: 18px; margin-bottom: 5px; }\n";
        $html .= ".genre-count { color: #666; font-size: 14px; }\n";
        $html .= ".genre-percentage { color: #28a745; font-weight: bold; }\n";
        $html .= ".combination-item { background-color: #fff3cd; border-left: 5px solid #ffc107; }\n";
        $html .= ".grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = getGenresStatistics($this->films);
        $totalFilms = count($this->films);
        $multiGenreFilms = count($this->getFilmsWithMultipleGenres());

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего фильмов:</strong> {$totalFilms}</p>\n";
        $html .= "<p><strong>Уникальных жанров:</strong> " . count($stats) . "</p>\n";
        $html .= "<p><strong>Фильмов с несколькими жанрами:</strong> {$multiGenreFilms}</p>\n";
        $html .= "</div>\n";

        $html .= "<div class='grid'>\n";

        // Топ жанров
        $html .= "<div>\n";
        $html .= "<h2>Топ жанров</h2>\n";
        foreach (array_slice($stats, 0, 20, true) as $genre => $count) {
            $percentage = round(($count / $totalFilms) * 100, 1);
            $html .= "<div class='genre-item'>\n";
            $html .= "<div class='genre-name'>{$genre}</div>\n";
            $html .= "<div class='genre-count'>{$count} фильмов</div>\n";
            $html .= "<div class='genre-percentage'>{$percentage}%</div>\n";
            $html .= "</div>\n";
        }
        $html .= "</div>\n";

        // Жанровые комбинации
        $html .= "<div>\n";
        $html .= "<h2>Популярные комбинации</h2>\n";
        $combinations = $this->getGenreCombinations(3);
        foreach (array_slice($combinations, 0, 15, true) as $combination => $count) {
            $html .= "<div class='genre-item combination-item'>\n";
            $html .= "<div class='genre-name'>{$combination}</div>\n";
            $html .= "<div class='genre-count'>{$count} фильмов</div>\n";
            $html .= "</div>\n";
        }
        $html .= "</div>\n";

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$films = $filmService->getTopFilms();
$report = new GenresReport($films);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Статистика по жанрам');
file_put_contents('genres_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в genres_report.html\n";
```

## Анализ жанрового разнообразия

```php
function analyzeGenreDiversity(array $films): array {
    $analysis = [
        'total' => count($films),
        'genres' => [],
        'singleGenre' => 0,
        'multiGenre' => 0,
        'avgGenresPerFilm' => 0,
        'mostDiverse' => [],
        'genreCategories' => []
    ];

    // Категории жанров
    $genreCategories = [
        'Драма' => 'Драматические',
        'Комедия' => 'Комедийные',
        'Боевик' => 'Экшн',
        'Триллер' => 'Триллеры',
        'Ужасы' => 'Хоррор',
        'Фантастика' => 'Научная фантастика',
        'Фэнтези' => 'Фэнтези',
        'Детектив' => 'Детективы',
        'Мелодрама' => 'Романтические',
        'Приключения' => 'Приключенческие',
        'Семейный' => 'Семейные',
        'Документальный' => 'Документальные',
        'Анимация' => 'Анимационные',
        'Вестерн' => 'Вестерны',
        'Военный' => 'Военные',
        'Исторический' => 'Исторические',
        'Мюзикл' => 'Мюзиклы',
        'Спорт' => 'Спортивные'
    ];

    $genreStats = [];
    $categoryStats = [];
    $totalGenres = 0;

    foreach ($films as $film) {
        $filmGenres = $film->genres;
        $genreCount = count($filmGenres);
        $totalGenres += $genreCount;

        // Подсчет фильмов по количеству жанров
        if ($genreCount === 1) {
            $analysis['singleGenre']++;
        } else {
            $analysis['multiGenre']++;
        }

        // Статистика по жанрам
        foreach ($filmGenres as $genre) {
            $genreName = $genre->genre;

            if (!isset($genreStats[$genreName])) {
                $genreStats[$genreName] = 0;
            }
            $genreStats[$genreName]++;

            // Статистика по категориям
            $category = $genreCategories[$genreName] ?? 'Другое';
            if (!isset($categoryStats[$category])) {
                $categoryStats[$category] = 0;
            }
            $categoryStats[$category]++;
        }

        // Фильмы с наибольшим количеством жанров
        if ($genreCount > 4) {
            $analysis['mostDiverse'][] = [
                'film' => $film,
                'genres' => $filmGenres,
                'count' => $genreCount
            ];
        }
    }

    $analysis['genres'] = $genreStats;
    $analysis['genreCategories'] = $categoryStats;
    $analysis['avgGenresPerFilm'] = $totalGenres / count($films);

    // Сортировка
    arsort($analysis['genres']);
    arsort($analysis['genreCategories']);

    return $analysis;
}

// Использование
$films = $filmService->getTopFilms();
$analysis = analyzeGenreDiversity($films);

echo "=== Анализ жанрового разнообразия ===\n";
echo "Всего фильмов: {$analysis['total']}\n";
echo "Фильмов с одним жанром: {$analysis['singleGenre']}\n";
echo "Фильмов с несколькими жанрами: {$analysis['multiGenre']}\n";
echo "Среднее количество жанров на фильм: " . round($analysis['avgGenresPerFilm'], 1) . "\n";

echo "\nТоп категорий жанров:\n";
foreach (array_slice($analysis['genreCategories'], 0, 10, true) as $category => $count) {
    echo "- {$category}: {$count} упоминаний\n";
}

echo "\nФильмы с наибольшим жанровым разнообразием:\n";
foreach (array_slice($analysis['mostDiverse'], 0, 5) as $item) {
    $film = $item['film'];
    $genres = array_map(fn($g) => $g->genre, $item['genres']);
    echo "- {$film->nameRu}: " . implode(', ', $genres) . " ({$item['count']} жанров)\n";
}
```

## Связанные классы

- [`Film`](film.md) - Модель фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
