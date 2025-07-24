# ReviewType

Типы рецензий в Kinopoisk API.

## Описание

`ReviewType` определяет различные типы рецензий, которые могут быть оставлены пользователями: положительные, отрицательные, нейтральные.

## Значения enum

### Типы рецензий

- `POSITIVE` - Положительная рецензия
- `NEGATIVE` - Отрицательная рецензия
- `NEUTRAL` - Нейтральная рецензия

## Методы

### isPositive()

Проверяет, является ли рецензия положительной.

```php
public function isPositive(): bool
```

#### Возвращаемое значение

- `bool` - `true` если рецензия положительная, `false` в противном случае

#### Пример использования

```php
if (ReviewType::POSITIVE->isPositive()) {
    echo "Положительная рецензия";
}
```

### isNegative()

Проверяет, является ли рецензия отрицательной.

```php
public function isNegative(): bool
```

#### Возвращаемое значение

- `bool` - `true` если рецензия отрицательная, `false` в противном случае

#### Пример использования

```php
if (ReviewType::NEGATIVE->isNegative()) {
    echo "Отрицательная рецензия";
}
```

### getDisplayName()

Получает человекочитаемое название типа рецензии.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа рецензии на русском языке

#### Пример использования

```php
echo ReviewType::POSITIVE->getDisplayName(); // "Положительная"
echo ReviewType::NEGATIVE->getDisplayName(); // "Отрицательная"
echo ReviewType::NEUTRAL->getDisplayName();  // "Нейтральная"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ReviewType;

// Создание клиента
$client = new Client('your-api-key');

// Получение рецензий на фильм
$filmService = $client->films;
$reviews = $filmService->getReviews(301); // Матрица

echo "=== Анализ рецензий ===\n";

// Подсчет по типам
$stats = [
    'positive' => 0,
    'negative' => 0,
    'neutral' => 0
];

foreach ($reviews as $review) {
    if ($review->type->isPositive()) {
        $stats['positive']++;
    } elseif ($review->type->isNegative()) {
        $stats['negative']++;
    } else {
        $stats['neutral']++;
    }
}

echo "📊 Статистика рецензий:\n";
echo "👍 Положительных: {$stats['positive']}\n";
echo "👎 Отрицательных: {$stats['negative']}\n";
echo "😐 Нейтральных: {$stats['neutral']}\n";

// Вывод примеров каждого типа
foreach ([ReviewType::POSITIVE, ReviewType::NEGATIVE, ReviewType::NEUTRAL] as $type) {
    $typeReviews = array_filter($reviews, fn($r) => $r->type === $type);

    if (!empty($typeReviews)) {
        echo "\n📝 {$type->getDisplayName()} рецензии:\n";
        $example = array_values($typeReviews)[0];
        echo "- {$example->title}\n";
        echo "- Автор: {$example->author}\n";
        echo "- " . substr($example->description, 0, 100) . "...\n";
    }
}
```

## Работа с типами рецензий

```php
// Функция для фильтрации рецензий по типу
function filterReviewsByType(array $reviews, ReviewType $type): array {
    return array_filter($reviews, fn($review) => $review->type === $type);
}

// Функция для получения статистики по типам
function getReviewTypeStats(array $reviews): array {
    $stats = [];

    foreach (ReviewType::cases() as $type) {
        $stats[$type->value] = [
            'type' => $type,
            'displayName' => $type->getDisplayName(),
            'count' => count(filterReviewsByType($reviews, $type)),
            'percentage' => 0
        ];
    }

    $total = count($reviews);
    if ($total > 0) {
        foreach ($stats as &$stat) {
            $stat['percentage'] = round(($stat['count'] / $total) * 100, 1);
        }
    }

    return $stats;
}

// Использование
$reviews = $filmService->getReviews(301);
$stats = getReviewTypeStats($reviews);

echo "=== Детальная статистика ===\n";
foreach ($stats as $typeKey => $stat) {
    echo "{$stat['displayName']}: {$stat['count']} ({$stat['percentage']}%)\n";
}
```

## Анализ настроений

```php
function analyzeSentiment(array $reviews): array {
    $sentiment = [
        'overall' => 'neutral',
        'positiveRatio' => 0,
        'negativeRatio' => 0,
        'neutralRatio' => 0
    ];

    $total = count($reviews);
    if ($total === 0) {
        return $sentiment;
    }

    $positive = count(filterReviewsByType($reviews, ReviewType::POSITIVE));
    $negative = count(filterReviewsByType($reviews, ReviewType::NEGATIVE));
    $neutral = count(filterReviewsByType($reviews, ReviewType::NEUTRAL));

    $sentiment['positiveRatio'] = round(($positive / $total) * 100, 1);
    $sentiment['negativeRatio'] = round(($negative / $total) * 100, 1);
    $sentiment['neutralRatio'] = round(($neutral / $total) * 100, 1);

    // Определение общего настроения
    if ($sentiment['positiveRatio'] > 50) {
        $sentiment['overall'] = 'positive';
    } elseif ($sentiment['negativeRatio'] > 50) {
        $sentiment['overall'] = 'negative';
    }

    return $sentiment;
}

// Использование
$reviews = $filmService->getReviews(301);
$sentiment = analyzeSentiment($reviews);

echo "=== Анализ настроений ===\n";
echo "Общее настроение: {$sentiment['overall']}\n";
echo "Положительных: {$sentiment['positiveRatio']}%\n";
echo "Отрицательных: {$sentiment['negativeRatio']}%\n";
echo "Нейтральных: {$sentiment['neutralRatio']}%\n";

// Эмодзи для настроения
$emoji = match($sentiment['overall']) {
    'positive' => '😊',
    'negative' => '😞',
    default => '😐'
};
echo "Настроение: {$emoji}\n";
```

## Сравнение рецензий

```php
function compareReviewTypes(array $reviews): array {
    $comparison = [];

    foreach (ReviewType::cases() as $type) {
        $typeReviews = filterReviewsByType($reviews, $type);

        $avgPositiveRating = 0;
        $avgNegativeRating = 0;

        if (!empty($typeReviews)) {
            $totalPositive = array_sum(array_column($typeReviews, 'positiveRating'));
            $totalNegative = array_sum(array_column($typeReviews, 'negativeRating'));

            $avgPositiveRating = round($totalPositive / count($typeReviews), 1);
            $avgNegativeRating = round($totalNegative / count($typeReviews), 1);
        }

        $comparison[$type->value] = [
            'type' => $type->getDisplayName(),
            'count' => count($typeReviews),
            'avgPositiveRating' => $avgPositiveRating,
            'avgNegativeRating' => $avgNegativeRating
        ];
    }

    return $comparison;
}

// Использование
$reviews = $filmService->getReviews(301);
$comparison = compareReviewTypes($reviews);

echo "=== Сравнение типов рецензий ===\n";
foreach ($comparison as $typeKey => $data) {
    echo "{$data['type']} ({$data['count']} рецензий):\n";
    echo "  Средний положительный рейтинг: {$data['avgPositiveRating']}\n";
    echo "  Средний отрицательный рейтинг: {$data['avgNegativeRating']}\n";
    echo "\n";
}
```

## Связанные классы

- [`Review`](../models/review.md) - Модель рецензии
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](../models/film.md) - Модель фильма
