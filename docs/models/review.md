# Review

Модель рецензии из Kinopoisk API.

## Описание

`Review` представляет информацию о рецензии на фильм, включая автора, дату, рейтинг и содержание рецензии.

### Основные возможности

- Хранение информации о рецензии в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к рейтингу и метаданным рецензии

**API Endpoint:** `/api/v2.2/films/{id}/reviews`

## Свойства

### Основная информация

- `$kinopoiskId` (int) - Уникальный идентификатор рецензии в Кинопоиске
- `$type` (ReviewType) - Тип рецензии (POSITIVE, NEGATIVE, NEUTRAL)
- `$date` (string) - Дата публикации рецензии
- `$author` (string) - Автор рецензии

### Содержание рецензии

- `$title` (string|null) - Заголовок рецензии
- `$description` (string) - Содержание рецензии

### Рейтинги

- `$positiveRating` (int) - Количество положительных оценок
- `$negativeRating` (int) - Количество отрицательных оценок

## Конструктор

```php
public function __construct(
    public readonly int $kinopoiskId,
    public readonly ReviewType $type,
    public readonly string $date,
    public readonly int $positiveRating,
    public readonly int $negativeRating,
    public readonly string $author,
    public readonly ?string $title,
    public readonly string $description,
)
```

### Пример создания

```php
$review = new Review(
    kinopoiskId: 12345,
    type: ReviewType::POSITIVE,
    date: '2023-01-15',
    positiveRating: 85,
    negativeRating: 15,
    author: 'Кинокритик',
    title: 'Отличный фильм',
    description: 'Подробный анализ фильма...'
);
```

## Методы

### fromArray()

Создает экземпляр рецензии из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных рецензии от API

#### Возвращаемое значение

- `self` - Новый экземпляр рецензии

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'kinopoiskId' => 12345,
    'type' => 'POSITIVE',
    'date' => '2023-01-15',
    'positiveRating' => 85,
    'negativeRating' => 15,
    'author' => 'Кинокритик',
    'title' => 'Отличный фильм',
    'description' => 'Подробный анализ фильма...'
];

$review = Review::fromArray($apiData);
```

### toArray()

Преобразует объект рецензии в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными рецензии

#### Пример использования

```php
$reviewArray = $review->toArray();
echo json_encode($reviewArray); // JSON представление рецензии
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Review;
use NotKinopoisk\Enums\ReviewType;

// Создание клиента
$client = new Client('your-api-key');

// Получение рецензий на фильм
$filmService = $client->films;
$reviews = $filmService->getReviews(301); // Матрица

echo "=== Рецензии на фильм 'Матрица' ===\n";

// Группировка по типам
$positiveReviews = [];
$negativeReviews = [];
$neutralReviews = [];

foreach ($reviews as $review) {
    switch ($review->type) {
        case ReviewType::POSITIVE:
            $positiveReviews[] = $review;
            break;
        case ReviewType::NEGATIVE:
            $negativeReviews[] = $review;
            break;
        case ReviewType::NEUTRAL:
            $neutralReviews[] = $review;
            break;
    }
}

// Вывод положительных рецензий
if (!empty($positiveReviews)) {
    echo "\n👍 Положительные рецензии (" . count($positiveReviews) . "):\n";
    foreach (array_slice($positiveReviews, 0, 3) as $review) {
        echo "📝 {$review->title}\n";
        echo "👤 Автор: {$review->author}\n";
        echo "📅 Дата: {$review->date}\n";
        echo "📊 Рейтинг: {$review->positiveRating}/{$review->negativeRating}\n";
        echo "📄 " . substr($review->description, 0, 200) . "...\n";
        echo "---\n";
    }
}

// Вывод отрицательных рецензий
if (!empty($negativeReviews)) {
    echo "\n👎 Отрицательные рецензии (" . count($negativeReviews) . "):\n";
    foreach (array_slice($negativeReviews, 0, 2) as $review) {
        echo "📝 {$review->title}\n";
        echo "👤 Автор: {$review->author}\n";
        echo "📅 Дата: {$review->date}\n";
        echo "📊 Рейтинг: {$review->positiveRating}/{$review->negativeRating}\n";
        echo "📄 " . substr($review->description, 0, 200) . "...\n";
        echo "---\n";
    }
}

// Вывод нейтральных рецензий
if (!empty($neutralReviews)) {
    echo "\n😐 Нейтральные рецензии (" . count($neutralReviews) . "):\n";
    foreach (array_slice($neutralReviews, 0, 2) as $review) {
        echo "📝 {$review->title}\n";
        echo "👤 Автор: {$review->author}\n";
        echo "📅 Дата: {$review->date}\n";
        echo "📊 Рейтинг: {$review->positiveRating}/{$review->negativeRating}\n";
        echo "📄 " . substr($review->description, 0, 200) . "...\n";
        echo "---\n";
    }
}
```

## Анализ рецензий

```php
function analyzeReviews(array $reviews): array {
    $analysis = [
        'total' => count($reviews),
        'positive' => 0,
        'negative' => 0,
        'neutral' => 0,
        'avgPositiveRating' => 0,
        'avgNegativeRating' => 0,
        'topAuthors' => []
    ];

    $authorStats = [];
    $totalPositive = 0;
    $totalNegative = 0;

    foreach ($reviews as $review) {
        // Подсчет типов
        switch ($review->type) {
            case ReviewType::POSITIVE:
                $analysis['positive']++;
                break;
            case ReviewType::NEGATIVE:
                $analysis['negative']++;
                break;
            case ReviewType::NEUTRAL:
                $analysis['neutral']++;
                break;
        }

        // Подсчет рейтингов
        $totalPositive += $review->positiveRating;
        $totalNegative += $review->negativeRating;

        // Статистика авторов
        if (!isset($authorStats[$review->author])) {
            $authorStats[$review->author] = 0;
        }
        $authorStats[$review->author]++;
    }

    // Средние рейтинги
    if ($analysis['total'] > 0) {
        $analysis['avgPositiveRating'] = round($totalPositive / $analysis['total'], 1);
        $analysis['avgNegativeRating'] = round($totalNegative / $analysis['total'], 1);
    }

    // Топ авторов
    arsort($authorStats);
    $analysis['topAuthors'] = array_slice($authorStats, 0, 5, true);

    return $analysis;
}

// Использование
$reviews = $filmService->getReviews(301);
$analysis = analyzeReviews($reviews);

echo "=== Анализ рецензий ===\n";
echo "Всего рецензий: {$analysis['total']}\n";
echo "Положительных: {$analysis['positive']}\n";
echo "Отрицательных: {$analysis['negative']}\n";
echo "Нейтральных: {$analysis['neutral']}\n";
echo "Средний положительный рейтинг: {$analysis['avgPositiveRating']}\n";
echo "Средний отрицательный рейтинг: {$analysis['avgNegativeRating']}\n";

echo "\nТоп авторов:\n";
foreach ($analysis['topAuthors'] as $author => $count) {
    echo "- {$author}: {$count} рецензий\n";
}
```

## Фильтрация и поиск рецензий

```php
function filterReviews(array $reviews, ReviewType $type = null, string $author = null): array {
    return array_filter($reviews, function($review) use ($type, $author) {
        if ($type && $review->type !== $type) {
            return false;
        }

        if ($author && stripos($review->author, $author) === false) {
            return false;
        }

        return true;
    });
}

function searchReviewsByContent(array $reviews, string $keyword): array {
    $keyword = strtolower($keyword);

    return array_filter($reviews, function($review) use ($keyword) {
        return strpos(strtolower($review->description), $keyword) !== false ||
               strpos(strtolower($review->title ?? ''), $keyword) !== false;
    });
}

// Использование
$reviews = $filmService->getReviews(301);

// Фильтрация по типу
$positiveOnly = filterReviews($reviews, ReviewType::POSITIVE);
echo "Только положительные рецензии: " . count($positiveOnly) . "\n";

// Фильтрация по автору
$criticReviews = filterReviews($reviews, null, 'Критик');
echo "Рецензии критиков: " . count($criticReviews) . "\n";

// Поиск по содержанию
$matrixReviews = searchReviewsByContent($reviews, 'матрица');
echo "Рецензии со словом 'матрица': " . count($matrixReviews) . "\n";
```

## Связанные классы

- [`ReviewType`](../enums/review-type.md) - Типы рецензий
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](film.md) - Модель фильма
