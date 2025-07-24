# PersonFilm

Модель фильма персоны из Kinopoisk API.

## Описание

`PersonFilm` представляет информацию о фильме, в котором участвовала персона (актер, режиссер, сценарист и т.д.), полученную из Kinopoisk API. Содержит данные о роли персоны в фильме.

### Основные возможности

- Хранение информации о фильме в неизменяемом виде
- Создание объекта из массива данных API
- Удобные методы для работы с названиями фильмов

**API Endpoint:** `/api/v1/persons/{id}`

## Свойства

### Основная информация

- `$filmId` (int) - Уникальный идентификатор фильма в Кинопоиске
- `$nameRu` (string|null) - Название фильма на русском языке
- `$nameEn` (string|null) - Название фильма на английском языке
- `$rating` (string|null) - Рейтинг фильма
- `$general` (bool) - Является ли фильм общим (не специфичным для персоны)
- `$description` (string|null) - Описание роли персоны в фильме
- `$professionKey` (ProfessionKey) - Ключ профессии персоны в фильме

## Конструктор

```php
public function __construct(
    public readonly int $filmId,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?string $rating,
    public readonly bool $general,
    public readonly ?string $description,
    public readonly ProfessionKey $professionKey,
)
```

### Пример создания

```php
$film = new PersonFilm(
    filmId: 32169,
    nameRu: 'Солист',
    nameEn: 'The Soloist',
    rating: '7.2',
    general: false,
    description: 'Steve Lopez',
    professionKey: ProfessionKey::ACTOR
);
```

## Методы

### fromArray()

Создает экземпляр фильма персоны из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных фильма от API

#### Возвращаемое значение

- `self` - Новый экземпляр фильма персоны

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'filmId' => 32169,
    'nameRu' => 'Солист',
    'nameEn' => 'The Soloist',
    'rating' => '7.2',
    'general' => false,
    'description' => 'Steve Lopez',
    'professionKey' => 'ACTOR'
];

$film = PersonFilm::fromArray($apiData);
```

### isActor()

Проверяет, является ли персона актером в этом фильме.

```php
public function isActor(): bool
```

#### Возвращаемое значение

- `bool` - `true` если актер, `false` в противном случае

#### Пример использования

```php
if ($film->isActor()) {
    echo "Актерская роль";
}
```

### isDirector()

Проверяет, является ли персона режиссером в этом фильме.

```php
public function isDirector(): bool
```

#### Возвращаемое значение

- `bool` - `true` если режиссер, `false` в противном случае

#### Пример использования

```php
if ($film->isDirector()) {
    echo "Режиссерская работа";
}
```

### isWriter()

Проверяет, является ли персона сценаристом в этом фильме.

```php
public function isWriter(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сценарист, `false` в противном случае

#### Пример использования

```php
if ($film->isWriter()) {
    echo "Сценарная работа";
}
```

### isProducer()

Проверяет, является ли персона продюсером в этом фильме.

```php
public function isProducer(): bool
```

#### Возвращаемое значение

- `bool` - `true` если продюсер, `false` в противном случае

#### Пример использования

```php
if ($film->isProducer()) {
    echo "Продюсерская работа";
}
```

### isGeneral()

Проверяет, является ли фильм общим.

```php
public function isGeneral(): bool
```

#### Возвращаемое значение

- `bool` - `true` если общий фильм, `false` если специфичный

#### Описание

Общие фильмы - это те, которые не специфичны для конкретной персоны, а показывают общую фильмографию.

#### Пример использования

```php
if ($film->isGeneral()) {
    echo "Общий фильм";
} else {
    echo "Специфичный для персоны";
}
```

### getFullInfo()

Получает полную информацию о фильме.

```php
public function getFullInfo(): string
```

#### Возвращаемое значение

- `string` - Полная информация о фильме

#### Описание

Возвращает строку с полной информацией о фильме, включая название, профессию, рейтинг и описание роли.

#### Пример использования

```php
echo $film->getFullInfo();
// "Солист (Актер, 7.2) - Steve Lopez"
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
echo $film->getDisplayName(); // "Солист" или "The Soloist" или "Без названия"
```

### getProfessionName()

Получает человекочитаемое название профессии.

```php
public function getProfessionName(): string
```

#### Возвращаемое значение

- `string` - Название профессии

#### Описание

Возвращает название профессии на русском языке.

#### Пример использования

```php
echo $film->getProfessionName(); // "Актер", "Режиссер", "Сценарист"
```

### getRatingInfo()

Получает информацию о рейтинге в виде строки.

```php
public function getRatingInfo(): string
```

#### Возвращаемое значение

- `string` - Информация о рейтинге

#### Описание

Возвращает рейтинг или "Нет рейтинга", если рейтинг отсутствует.

#### Пример использования

```php
echo "Рейтинг: {$film->getRatingInfo()}"; // "7.2" или "Нет рейтинга"
```

### isCreativeProfession()

Проверяет, является ли профессия творческой.

```php
public function isCreativeProfession(): bool
```

#### Возвращаемое значение

- `bool` - `true` если творческая профессия, `false` в противном случае

#### Пример использования

```php
if ($film->isCreativeProfession()) {
    echo "Творческая работа";
}
```

### isTechnicalProfession()

Проверяет, является ли профессия технической.

```php
public function isTechnicalProfession(): bool
```

#### Возвращаемое значение

- `bool` - `true` если техническая профессия, `false` в противном случае

#### Пример использования

```php
if ($film->isTechnicalProfession()) {
    echo "Техническая работа";
}
```

### isManagementProfession()

Проверяет, является ли профессия управленческой.

```php
public function isManagementProfession(): bool
```

#### Возвращаемое значение

- `bool` - `true` если управленческая профессия, `false` в противном случае

#### Пример использования

```php
if ($film->isManagementProfession()) {
    echo "Управленческая работа";
}
```

### isSpecialProfession()

Проверяет, является ли профессия специальной.

```php
public function isSpecialProfession(): bool
```

#### Возвращаемое значение

- `bool` - `true` если специальная профессия, `false` в противном случае

#### Пример использования

```php
if ($film->isSpecialProfession()) {
    echo "Специальная роль";
}
```

### getProfessionCategory()

Получает категорию профессии.

```php
public function getProfessionCategory(): string
```

#### Возвращаемое значение

- `string` - Категория профессии

#### Пример использования

```php
echo $film->getProfessionCategory(); // "Творческая", "Техническая", etc.
```

### toArray()

Преобразует объект фильма персоны в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными фильма персоны

#### Описание

Возвращает все свойства объекта в виде ассоциативного массива. Полезно для сериализации, логирования или передачи данных.

#### Пример использования

```php
$filmArray = $film->toArray();
echo json_encode($filmArray); // JSON представление фильма персоны
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о персоне
$personService = $client->persons;
$person = $personService->getPerson(1); // ID персоны

echo "=== Фильмография персоны ===\n";

if (!empty($person->films)) {
    echo "Всего фильмов: " . count($person->films) . "\n\n";

    foreach ($person->films as $index => $film) {
        echo ($index + 1) . ". {$film->getFullInfo()}\n";
        echo "   ID фильма: {$film->filmId}\n";
        echo "   Название: {$film->getDisplayName()}\n";
        echo "   Профессия: {$film->getProfessionName()}\n";
        echo "   Рейтинг: {$film->getRatingInfo()}\n";

        if ($film->description) {
            echo "   Описание роли: {$film->description}\n";
        }

        echo "   Тип: " . ($film->isGeneral() ? 'Общий' : 'Специфичный') . "\n";
        echo "   Категория: {$film->getProfessionCategory()}\n";
        echo "\n";
    }
} else {
    echo "Фильмография не найдена\n";
}
```

## Работа с фильмами персоны

```php
// Функция для фильтрации фильмов по профессии
function filterFilmsByProfession(array $films, string $profession): array {
    return array_filter($films, function($film) use ($profession) {
        return match ($profession) {
            'actor' => $film->isActor(),
            'director' => $film->isDirector(),
            'writer' => $film->isWriter(),
            'producer' => $film->isProducer(),
            default => false
        };
    });
}

// Функция для фильтрации фильмов по категории профессии
function filterFilmsByProfessionCategory(array $films, string $category): array {
    return array_filter($films, fn($film) => $film->getProfessionCategory() === $category);
}

// Функция для получения фильмов с рейтингом выше определенного
function getFilmsWithRatingAbove(array $films, float $minRating): array {
    return array_filter($films, function($film) use ($minRating) {
        if (!$film->rating) return false;
        return (float)$film->rating >= $minRating;
    });
}

// Функция для получения статистики по фильмам
function getFilmStats(array $films): array {
    $stats = [
        'total' => count($films),
        'actors' => 0,
        'directors' => 0,
        'writers' => 0,
        'producers' => 0,
        'general' => 0,
        'specific' => 0,
        'withRating' => 0,
        'averageRating' => 0,
        'professionCategories' => [
            'creative' => 0,
            'technical' => 0,
            'management' => 0,
            'special' => 0
        ]
    ];

    $totalRating = 0;
    $ratedFilms = 0;

    foreach ($films as $film) {
        // Профессии
        if ($film->isActor()) $stats['actors']++;
        if ($film->isDirector()) $stats['directors']++;
        if ($film->isWriter()) $stats['writers']++;
        if ($film->isProducer()) $stats['producers']++;

        // Тип фильма
        if ($film->isGeneral()) {
            $stats['general']++;
        } else {
            $stats['specific']++;
        }

        // Рейтинг
        if ($film->rating) {
            $stats['withRating']++;
            $totalRating += (float)$film->rating;
            $ratedFilms++;
        }

        // Категории профессий
        $category = strtolower($film->getProfessionCategory());
        if (isset($stats['professionCategories'][$category])) {
            $stats['professionCategories'][$category]++;
        }
    }

    // Средний рейтинг
    if ($ratedFilms > 0) {
        $stats['averageRating'] = round($totalRating / $ratedFilms, 2);
    }

    return $stats;
}

// Функция для получения топ фильмов по рейтингу
function getTopRatedFilms(array $films, int $limit = 10): array {
    $ratedFilms = array_filter($films, fn($film) => $film->rating !== null);

    usort($ratedFilms, function($a, $b) {
        return (float)$b->rating <=> (float)$a->rating;
    });

    return array_slice($ratedFilms, 0, $limit);
}

// Функция для получения фильмов по году (если доступно)
function getFilmsByYear(array $films, int $year): array {
    // Примечание: год не доступен в текущей модели PersonFilm
    // Это пример для возможного расширения
    return array_filter($films, function($film) use ($year) {
        // Здесь можно добавить логику фильтрации по году
        return true; // Пока возвращаем все фильмы
    });
}

// Использование
$films = $person->films ?? [];

// Фильтрация по профессиям
$actorFilms = filterFilmsByProfession($films, 'actor');
$directorFilms = filterFilmsByProfession($films, 'director');
$writerFilms = filterFilmsByProfession($films, 'writer');
$producerFilms = filterFilmsByProfession($films, 'producer');

echo "Фильтрация по профессиям:\n";
echo "Актерские роли: " . count($actorFilms) . "\n";
echo "Режиссерские работы: " . count($directorFilms) . "\n";
echo "Сценарные работы: " . count($writerFilms) . "\n";
echo "Продюсерские работы: " . count($producerFilms) . "\n";

// Фильтрация по категориям
$creativeFilms = filterFilmsByProfessionCategory($films, 'Творческая');
$technicalFilms = filterFilmsByProfessionCategory($films, 'Техническая');

echo "\nФильтрация по категориям:\n";
echo "Творческие работы: " . count($creativeFilms) . "\n";
echo "Технические работы: " . count($technicalFilms) . "\n";

// Фильмы с высоким рейтингом
$highRatedFilms = getFilmsWithRatingAbove($films, 7.0);
echo "\nФильмы с рейтингом выше 7.0: " . count($highRatedFilms) . "\n";

// Статистика
$stats = getFilmStats($films);
echo "\nСтатистика:\n";
echo "Всего фильмов: {$stats['total']}\n";
echo "Актерских ролей: {$stats['actors']}\n";
echo "Режиссерских работ: {$stats['directors']}\n";
echo "Сценарных работ: {$stats['writers']}\n";
echo "Продюсерских работ: {$stats['producers']}\n";
echo "Общих фильмов: {$stats['general']}\n";
echo "Специфичных фильмов: {$stats['specific']}\n";
echo "Фильмов с рейтингом: {$stats['withRating']}\n";
echo "Средний рейтинг: {$stats['averageRating']}\n";

// Топ фильмов
$topFilms = getTopRatedFilms($films, 5);
echo "\nТоп-5 фильмов по рейтингу:\n";
foreach ($topFilms as $index => $film) {
    echo ($index + 1) . ". {$film->getDisplayName()} ({$film->getRatingInfo()})\n";
}
```

## Создание отчета по фильмографии

```php
class PersonFilmReport {
    private array $films;

    public function __construct(array $films) {
        $this->films = $films;
    }

    public function getFilms(): array {
        return $this->films;
    }

    public function getFilmsByProfession(string $profession): array {
        return filterFilmsByProfession($this->films, $profession);
    }

    public function getFilmsByCategory(string $category): array {
        return filterFilmsByProfessionCategory($this->films, $category);
    }

    public function getFilmStats(): array {
        return getFilmStats($this->films);
    }

    public function getTopRatedFilms(int $limit = 10): array {
        return getTopRatedFilms($this->films, $limit);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ФИЛЬМОГРАФИИ ===\n\n";

        $stats = $this->getFilmStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего фильмов: {$stats['total']}\n";
        $report .= "Актерских ролей: {$stats['actors']}\n";
        $report .= "Режиссерских работ: {$stats['directors']}\n";
        $report .= "Сценарных работ: {$stats['writers']}\n";
        $report .= "Продюсерских работ: {$stats['producers']}\n";
        $report .= "Общих фильмов: {$stats['general']}\n";
        $report .= "Специфичных фильмов: {$stats['specific']}\n";
        $report .= "Фильмов с рейтингом: {$stats['withRating']}\n";
        $report .= "Средний рейтинг: {$stats['averageRating']}\n\n";

        // Распределение по категориям профессий
        $report .= "📈 РАСПРЕДЕЛЕНИЕ ПО КАТЕГОРИЯМ:\n";
        foreach ($stats['professionCategories'] as $category => $count) {
            $report .= "• {$category}: {$count}\n";
        }
        $report .= "\n";

        // Актерские роли
        $actorFilms = $this->getFilmsByProfession('actor');
        if (!empty($actorFilms)) {
            $report .= "🎭 АКТЕРСКИЕ РОЛИ (" . count($actorFilms) . " фильмов):\n";
            foreach (array_slice($actorFilms, 0, 10) as $film) {
                $report .= "• {$film->getFullInfo()}\n";
            }
            if (count($actorFilms) > 10) {
                $report .= "... и еще " . (count($actorFilms) - 10) . " фильмов\n";
            }
            $report .= "\n";
        }

        // Режиссерские работы
        $directorFilms = $this->getFilmsByProfession('director');
        if (!empty($directorFilms)) {
            $report .= "🎬 РЕЖИССЕРСКИЕ РАБОТЫ (" . count($directorFilms) . " фильмов):\n";
            foreach (array_slice($directorFilms, 0, 10) as $film) {
                $report .= "• {$film->getFullInfo()}\n";
            }
            if (count($directorFilms) > 10) {
                $report .= "... и еще " . (count($directorFilms) - 10) . " фильмов\n";
            }
            $report .= "\n";
        }

        // Сценарные работы
        $writerFilms = $this->getFilmsByProfession('writer');
        if (!empty($writerFilms)) {
            $report .= "✍️ СЦЕНАРНЫЕ РАБОТЫ (" . count($writerFilms) . " фильмов):\n";
            foreach (array_slice($writerFilms, 0, 10) as $film) {
                $report .= "• {$film->getFullInfo()}\n";
            }
            if (count($writerFilms) > 10) {
                $report .= "... и еще " . (count($writerFilms) - 10) . " фильмов\n";
            }
            $report .= "\n";
        }

        // Продюсерские работы
        $producerFilms = $this->getFilmsByProfession('producer');
        if (!empty($producerFilms)) {
            $report .= "💰 ПРОДЮСЕРСКИЕ РАБОТЫ (" . count($producerFilms) . " фильмов):\n";
            foreach (array_slice($producerFilms, 0, 10) as $film) {
                $report .= "• {$film->getFullInfo()}\n";
            }
            if (count($producerFilms) > 10) {
                $report .= "... и еще " . (count($producerFilms) - 10) . " фильмов\n";
            }
            $report .= "\n";
        }

        // Топ фильмов по рейтингу
        $topFilms = $this->getTopRatedFilms(10);
        if (!empty($topFilms)) {
            $report .= "🏆 ТОП-10 ФИЛЬМОВ ПО РЕЙТИНГУ:\n";
            foreach ($topFilms as $index => $film) {
                $report .= ($index + 1) . ". {$film->getFullInfo()}\n";
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
        $html .= ".film-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".film-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".film-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".film-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 15px; }\n";
        $html .= ".actor { border-left: 5px solid #007bff; }\n";
        $html .= ".director { border-left: 5px solid #28a745; }\n";
        $html .= ".writer { border-left: 5px solid #ffc107; }\n";
        $html .= ".producer { border-left: 5px solid #dc3545; }\n";
        $html .= ".rating { font-weight: bold; color: #28a745; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; transition: width 0.3s ease; }\n";
        $html .= ".actor-fill { background-color: #007bff; }\n";
        $html .= ".director-fill { background-color: #28a745; }\n";
        $html .= ".writer-fill { background-color: #ffc107; }\n";
        $html .= ".producer-fill { background-color: #dc3545; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getFilmStats();
        $actorFilms = $this->getFilmsByProfession('actor');
        $directorFilms = $this->getFilmsByProfession('director');
        $writerFilms = $this->getFilmsByProfession('writer');
        $producerFilms = $this->getFilmsByProfession('producer');
        $topFilms = $this->getTopRatedFilms(10);

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего фильмов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Актерских ролей:</strong> {$stats['actors']}</p>\n";
        $html .= "<p><strong>Режиссерских работ:</strong> {$stats['directors']}</p>\n";
        $html .= "<p><strong>Сценарных работ:</strong> {$stats['writers']}</p>\n";
        $html .= "<p><strong>Продюсерских работ:</strong> {$stats['producers']}</p>\n";
        $html .= "<p><strong>Средний рейтинг:</strong> {$stats['averageRating']}</p>\n";

        // Прогресс-бары
        if ($stats['total'] > 0) {
            $actorPercent = round(($stats['actors'] / $stats['total']) * 100, 1);
            $directorPercent = round(($stats['directors'] / $stats['total']) * 100, 1);
            $writerPercent = round(($stats['writers'] / $stats['total']) * 100, 1);
            $producerPercent = round(($stats['producers'] / $stats['total']) * 100, 1);

            $html .= "<h3>Распределение по профессиям</h3>\n";
            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill actor-fill' style='width: {$actorPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Актерские роли: {$actorPercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill director-fill' style='width: {$directorPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Режиссерские работы: {$directorPercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill writer-fill' style='width: {$writerPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Сценарные работы: {$writerPercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill producer-fill' style='width: {$producerPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Продюсерские работы: {$producerPercent}%</p>\n";
        }

        $html .= "</div>\n";

        // Актерские роли
        if (!empty($actorFilms)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🎭 Актерские роли (" . count($actorFilms) . " фильмов)</div>\n";
            $html .= "<div class='film-grid'>\n";

            foreach (array_slice($actorFilms, 0, 12) as $film) {
                $html .= "<div class='film-item actor'>\n";
                $html .= "<div class='film-title'>{$film->getDisplayName()}</div>\n";
                $html .= "<div class='film-meta'>Профессия: {$film->getProfessionName()}</div>\n";
                $html .= "<div class='film-meta rating'>Рейтинг: {$film->getRatingInfo()}</div>\n";
                if ($film->description) {
                    $html .= "<div class='film-meta'>Роль: {$film->description}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Режиссерские работы
        if (!empty($directorFilms)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🎬 Режиссерские работы (" . count($directorFilms) . " фильмов)</div>\n";
            $html .= "<div class='film-grid'>\n";

            foreach (array_slice($directorFilms, 0, 12) as $film) {
                $html .= "<div class='film-item director'>\n";
                $html .= "<div class='film-title'>{$film->getDisplayName()}</div>\n";
                $html .= "<div class='film-meta'>Профессия: {$film->getProfessionName()}</div>\n";
                $html .= "<div class='film-meta rating'>Рейтинг: {$film->getRatingInfo()}</div>\n";
                if ($film->description) {
                    $html .= "<div class='film-meta'>Описание: {$film->description}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Сценарные работы
        if (!empty($writerFilms)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>✍️ Сценарные работы (" . count($writerFilms) . " фильмов)</div>\n";
            $html .= "<div class='film-grid'>\n";

            foreach (array_slice($writerFilms, 0, 12) as $film) {
                $html .= "<div class='film-item writer'>\n";
                $html .= "<div class='film-title'>{$film->getDisplayName()}</div>\n";
                $html .= "<div class='film-meta'>Профессия: {$film->getProfessionName()}</div>\n";
                $html .= "<div class='film-meta rating'>Рейтинг: {$film->getRatingInfo()}</div>\n";
                if ($film->description) {
                    $html .= "<div class='film-meta'>Описание: {$film->description}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Продюсерские работы
        if (!empty($producerFilms)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>💰 Продюсерские работы (" . count($producerFilms) . " фильмов)</div>\n";
            $html .= "<div class='film-grid'>\n";

            foreach (array_slice($producerFilms, 0, 12) as $film) {
                $html .= "<div class='film-item producer'>\n";
                $html .= "<div class='film-title'>{$film->getDisplayName()}</div>\n";
                $html .= "<div class='film-meta'>Профессия: {$film->getProfessionName()}</div>\n";
                $html .= "<div class='film-meta rating'>Рейтинг: {$film->getRatingInfo()}</div>\n";
                if ($film->description) {
                    $html .= "<div class='film-meta'>Описание: {$film->description}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Топ фильмов
        if (!empty($topFilms)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🏆 Топ-10 фильмов по рейтингу</div>\n";
            $html .= "<div class='film-grid'>\n";

            foreach ($topFilms as $index => $film) {
                $cssClass = $film->isActor() ? 'actor' : ($film->isDirector() ? 'director' : ($film->isWriter() ? 'writer' : 'producer'));
                $html .= "<div class='film-item {$cssClass}'>\n";
                $html .= "<div class='film-title'>" . ($index + 1) . ". {$film->getDisplayName()}</div>\n";
                $html .= "<div class='film-meta'>Профессия: {$film->getProfessionName()}</div>\n";
                $html .= "<div class='film-meta rating'>Рейтинг: {$film->getRatingInfo()}</div>\n";
                if ($film->description) {
                    $html .= "<div class='film-meta'>Описание: {$film->description}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$films = $person->films ?? [];
$report = new PersonFilmReport($films);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по фильмографии');
file_put_contents('person_films_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в person_films_report.html\n";
```

## Анализ фильмографии

```php
function analyzePersonFilms(array $films): array {
    $analysis = [
        'totalFilms' => count($films),
        'professionDistribution' => [
            'actors' => 0,
            'directors' => 0,
            'writers' => 0,
            'producers' => 0
        ],
        'professionPercentages' => [
            'actors' => 0,
            'directors' => 0,
            'writers' => 0,
            'producers' => 0
        ],
        'categoryDistribution' => [
            'creative' => 0,
            'technical' => 0,
            'management' => 0,
            'special' => 0
        ],
        'ratingAnalysis' => [
            'withRating' => 0,
            'averageRating' => 0,
            'highestRating' => 0,
            'lowestRating' => 0
        ],
        'filmTypes' => [
            'general' => 0,
            'specific' => 0
        ]
    ];

    $totalRating = 0;
    $ratedFilms = 0;
    $ratings = [];

    foreach ($films as $film) {
        // Распределение по профессиям
        if ($film->isActor()) $analysis['professionDistribution']['actors']++;
        if ($film->isDirector()) $analysis['professionDistribution']['directors']++;
        if ($film->isWriter()) $analysis['professionDistribution']['writers']++;
        if ($film->isProducer()) $analysis['professionDistribution']['producers']++;

        // Распределение по категориям
        $category = strtolower($film->getProfessionCategory());
        if (isset($analysis['categoryDistribution'][$category])) {
            $analysis['categoryDistribution'][$category]++;
        }

        // Анализ рейтингов
        if ($film->rating) {
            $analysis['ratingAnalysis']['withRating']++;
            $rating = (float)$film->rating;
            $totalRating += $rating;
            $ratings[] = $rating;
        }

        // Типы фильмов
        if ($film->isGeneral()) {
            $analysis['filmTypes']['general']++;
        } else {
            $analysis['filmTypes']['specific']++;
        }
    }

    // Вычисление процентов
    if ($analysis['totalFilms'] > 0) {
        $analysis['professionPercentages']['actors'] = round(($analysis['professionDistribution']['actors'] / $analysis['totalFilms']) * 100, 1);
        $analysis['professionPercentages']['directors'] = round(($analysis['professionDistribution']['directors'] / $analysis['totalFilms']) * 100, 1);
        $analysis['professionPercentages']['writers'] = round(($analysis['professionDistribution']['writers'] / $analysis['totalFilms']) * 100, 1);
        $analysis['professionPercentages']['producers'] = round(($analysis['professionDistribution']['producers'] / $analysis['totalFilms']) * 100, 1);
    }

    // Анализ рейтингов
    if (!empty($ratings)) {
        $analysis['ratingAnalysis']['averageRating'] = round($totalRating / count($ratings), 2);
        $analysis['ratingAnalysis']['highestRating'] = max($ratings);
        $analysis['ratingAnalysis']['lowestRating'] = min($ratings);
    }

    return $analysis;
}

// Использование
$films = $person->films ?? [];
$analysis = analyzePersonFilms($films);

echo "=== Анализ фильмографии ===\n";
echo "Всего фильмов: {$analysis['totalFilms']}\n";

echo "\nРаспределение по профессиям:\n";
echo "- Актерские роли: {$analysis['professionDistribution']['actors']} ({$analysis['professionPercentages']['actors']}%)\n";
echo "- Режиссерские работы: {$analysis['professionDistribution']['directors']} ({$analysis['professionPercentages']['directors']}%)\n";
echo "- Сценарные работы: {$analysis['professionDistribution']['writers']} ({$analysis['professionPercentages']['writers']}%)\n";
echo "- Продюсерские работы: {$analysis['professionDistribution']['producers']} ({$analysis['professionPercentages']['producers']}%)\n";

echo "\nРаспределение по категориям:\n";
foreach ($analysis['categoryDistribution'] as $category => $count) {
    echo "- {$category}: {$count}\n";
}

echo "\nАнализ рейтингов:\n";
echo "- Фильмов с рейтингом: {$analysis['ratingAnalysis']['withRating']}\n";
echo "- Средний рейтинг: {$analysis['ratingAnalysis']['averageRating']}\n";
echo "- Высший рейтинг: {$analysis['ratingAnalysis']['highestRating']}\n";
echo "- Низший рейтинг: {$analysis['ratingAnalysis']['lowestRating']}\n";

echo "\nТипы фильмов:\n";
echo "- Общих фильмов: {$analysis['filmTypes']['general']}\n";
echo "- Специфичных фильмов: {$analysis['filmTypes']['specific']}\n";
```

## Связанные классы

- [`Person`](person.md) - Модель персоны
- [`ProfessionKey`](../enums/profession-key.md) - Enum профессий
- [`PersonService`](../services/person-service.md) - Сервис для работы с персонами
