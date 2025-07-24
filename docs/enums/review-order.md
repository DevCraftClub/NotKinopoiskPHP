# ReviewOrder

Enum типов сортировки отзывов в Kinopoisk API.

## Описание

`ReviewOrder` представляет различные способы сортировки отзывов к фильмам в API запросах Kinopoisk.

**API Endpoint:** `/api/v2.2/films/{id}/reviews`

## Значения enum

### Типы сортировки

- `DATE_DESC` - По дате (новые сначала)
- `DATE_ASC` - По дате (старые сначала)
- `USER_POSITIVE_RATING_ASC` - По положительным оценкам (низкие сначала)
- `USER_POSITIVE_RATING_DESC` - По положительным оценкам (высокие сначала)
- `USER_NEGATIVE_RATING_ASC` - По отрицательным оценкам (низкие сначала)
- `USER_NEGATIVE_RATING_DESC` - По отрицательным оценкам (высокие сначала)

## Методы

### getDisplayName()

Получает отображаемое название типа сортировки.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название типа сортировки

#### Пример использования

```php
echo ReviewOrder::DATE_DESC->getDisplayName(); // "По дате (новые сначала)"
echo ReviewOrder::USER_POSITIVE_RATING_DESC->getDisplayName(); // "По положительным оценкам (высокие сначала)"
```

### getShortName()

Получает краткое название типа сортировки.

```php
public function getShortName(): string
```

#### Возвращаемое значение

- `string` - Краткое название типа сортировки

#### Пример использования

```php
echo ReviewOrder::DATE_DESC->getShortName(); // "Дата ↓"
echo ReviewOrder::USER_POSITIVE_RATING_DESC->getShortName(); // "Положительные ↓"
```

### isDateSort()

Проверяет, является ли сортировка по дате.

```php
public function isDateSort(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сортировка по дате, `false` в противном случае

#### Пример использования

```php
if (ReviewOrder::DATE_DESC->isDateSort()) {
    echo "Сортировка по дате";
}
```

### isPositiveRatingSort()

Проверяет, является ли сортировка по положительным оценкам.

```php
public function isPositiveRatingSort(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сортировка по положительным оценкам, `false` в противном случае

#### Пример использования

```php
if (ReviewOrder::USER_POSITIVE_RATING_DESC->isPositiveRatingSort()) {
    echo "Сортировка по положительным оценкам";
}
```

### isNegativeRatingSort()

Проверяет, является ли сортировка по отрицательным оценкам.

```php
public function isNegativeRatingSort(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сортировка по отрицательным оценкам, `false` в противном случае

#### Пример использования

```php
if (ReviewOrder::USER_NEGATIVE_RATING_DESC->isNegativeRatingSort()) {
    echo "Сортировка по отрицательным оценкам";
}
```

### isAscending()

Проверяет, является ли сортировка по возрастанию.

```php
public function isAscending(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сортировка по возрастанию, `false` в противном случае

#### Пример использования

```php
if (ReviewOrder::DATE_ASC->isAscending()) {
    echo "Сортировка по возрастанию";
}
```

### isDescending()

Проверяет, является ли сортировка по убыванию.

```php
public function isDescending(): bool
```

#### Возвращаемое значение

- `bool` - `true` если сортировка по убыванию, `false` в противном случае

#### Пример использования

```php
if (ReviewOrder::DATE_DESC->isDescending()) {
    echo "Сортировка по убыванию";
}
```

### getDirection()

Получает направление сортировки.

```php
public function getDirection(): string
```

#### Возвращаемое значение

- `string` - Направление сортировки ("asc" или "desc")

#### Пример использования

```php
echo ReviewOrder::DATE_ASC->getDirection(); // "asc"
echo ReviewOrder::DATE_DESC->getDirection(); // "desc"
```

### getFieldType()

Получает тип поля для сортировки.

```php
public function getFieldType(): string
```

#### Возвращаемое значение

- `string` - Тип поля для сортировки

#### Пример использования

```php
echo ReviewOrder::DATE_DESC->getFieldType(); // "date"
echo ReviewOrder::USER_POSITIVE_RATING_DESC->getFieldType(); // "positive_rating"
```

### getDefault()

Получает значение по умолчанию.

```php
public static function getDefault(): self
```

#### Возвращаемое значение

- `self` - Значение по умолчанию (DATE_DESC)

#### Пример использования

```php
$defaultOrder = ReviewOrder::getDefault();
echo $defaultOrder->value; // "DATE_DESC"
```

### getAll()

Получает все доступные типы сортировки.

```php
public static function getAll(): array
```

#### Возвращаемое значение

- `self[]` - Массив всех доступных типов сортировки

#### Пример использования

```php
$allOrders = ReviewOrder::getAll();
foreach ($allOrders as $order) {
    echo $order->getDisplayName() . "\n";
}
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ReviewOrder;

// Создание клиента
$client = new Client('your-api-key');

// Получение отзывов с различной сортировкой
$filmService = $client->films;

echo "=== Работа с сортировкой отзывов ===\n";

// Получение отзывов, отсортированных по дате (новые сначала)
$reviewsByDate = $filmService->getReviews(301, 1, ReviewOrder::DATE_DESC);
echo "Отзывы по дате (новые): " . count($reviewsByDate) . " элементов\n";

// Получение отзывов, отсортированных по положительным оценкам
$reviewsByPositive = $filmService->getReviews(301, 1, ReviewOrder::USER_POSITIVE_RATING_DESC);
echo "Отзывы по положительным оценкам: " . count($reviewsByPositive) . " элементов\n";

// Получение отзывов, отсортированных по отрицательным оценкам
$reviewsByNegative = $filmService->getReviews(301, 1, ReviewOrder::USER_NEGATIVE_RATING_DESC);
echo "Отзывы по отрицательным оценкам: " . count($reviewsByNegative) . " элементов\n";

// Вывод всех доступных типов сортировки
echo "\nДоступные типы сортировки:\n";
foreach (ReviewOrder::getAll() as $order) {
    $icon = getReviewOrderIcon($order);
    $displayName = $order->getDisplayName();
    $shortName = $order->getShortName();
    $direction = $order->getDirection();
    $fieldType = $order->getFieldType();

    echo "{$icon} {$displayName}\n";
    echo "   Краткое название: {$shortName}\n";
    echo "   Направление: {$direction}\n";
    echo "   Тип поля: {$fieldType}\n\n";
}
```

## Работа с сортировкой отзывов

```php
// Функция для получения иконки типа сортировки
function getReviewOrderIcon(ReviewOrder $order): string {
    return match ($order) {
        ReviewOrder::DATE_DESC => '📅',
        ReviewOrder::DATE_ASC => '📅',
        ReviewOrder::USER_POSITIVE_RATING_ASC => '👍',
        ReviewOrder::USER_POSITIVE_RATING_DESC => '👍',
        ReviewOrder::USER_NEGATIVE_RATING_ASC => '👎',
        ReviewOrder::USER_NEGATIVE_RATING_DESC => '👎'
    };
}

// Функция для получения цвета типа сортировки
function getReviewOrderColor(ReviewOrder $order): string {
    return match ($order) {
        ReviewOrder::DATE_DESC, ReviewOrder::DATE_ASC => '#6c757d', // Серый
        ReviewOrder::USER_POSITIVE_RATING_ASC, ReviewOrder::USER_POSITIVE_RATING_DESC => '#28a745', // Зеленый
        ReviewOrder::USER_NEGATIVE_RATING_ASC, ReviewOrder::USER_NEGATIVE_RATING_DESC => '#dc3545' // Красный
    };
}

// Функция для получения описания типа сортировки
function getReviewOrderDescription(ReviewOrder $order): string {
    return match ($order) {
        ReviewOrder::DATE_DESC => 'Отзывы отсортированы по дате публикации (новые первыми)',
        ReviewOrder::DATE_ASC => 'Отзывы отсортированы по дате публикации (старые первыми)',
        ReviewOrder::USER_POSITIVE_RATING_ASC => 'Отзывы отсортированы по количеству положительных оценок (по возрастанию)',
        ReviewOrder::USER_POSITIVE_RATING_DESC => 'Отзывы отсортированы по количеству положительных оценок (по убыванию)',
        ReviewOrder::USER_NEGATIVE_RATING_ASC => 'Отзывы отсортированы по количеству отрицательных оценок (по возрастанию)',
        ReviewOrder::USER_NEGATIVE_RATING_DESC => 'Отзывы отсортированы по количеству отрицательных оценок (по убыванию)'
    };
}

// Функция для группировки типов сортировки
function groupReviewOrders(): array {
    $groups = [
        'date' => [],
        'positive_rating' => [],
        'negative_rating' => []
    ];

    foreach (ReviewOrder::getAll() as $order) {
        $fieldType = $order->getFieldType();
        $groups[$fieldType][] = $order;
    }

    return $groups;
}

// Функция для получения рекомендуемого типа сортировки
function getRecommendedReviewOrder(string $purpose): ReviewOrder {
    return match ($purpose) {
        'latest' => ReviewOrder::DATE_DESC,
        'oldest' => ReviewOrder::DATE_ASC,
        'best' => ReviewOrder::USER_POSITIVE_RATING_DESC,
        'worst' => ReviewOrder::USER_NEGATIVE_RATING_DESC,
        'controversial' => ReviewOrder::USER_NEGATIVE_RATING_ASC,
        default => ReviewOrder::getDefault()
    };
}

// Использование
$allOrders = ReviewOrder::getAll();

echo "Все типы сортировки отзывов:\n";
foreach ($allOrders as $order) {
    $icon = getReviewOrderIcon($order);
    $displayName = $order->getDisplayName();
    $shortName = $order->getShortName();
    $description = getReviewOrderDescription($order);
    $color = getReviewOrderColor($order);

    echo "{$icon} {$displayName} ({$shortName})\n";
    echo "   Цвет: {$color}\n";
    echo "   Описание: {$description}\n\n";
}

// Группировка
$groups = groupReviewOrders();
echo "Группировка по типам:\n";
foreach ($groups as $type => $orders) {
    echo "• {$type}: " . count($orders) . " типов\n";
    foreach ($orders as $order) {
        echo "  - {$order->getDisplayName()}\n";
    }
}

// Рекомендации
echo "\nРекомендуемые типы сортировки:\n";
$recommendations = [
    'latest' => 'Последние отзывы',
    'best' => 'Лучшие отзывы',
    'worst' => 'Худшие отзывы',
    'controversial' => 'Спорные отзывы'
];

foreach ($recommendations as $purpose => $description) {
    $order = getRecommendedReviewOrder($purpose);
    echo "• {$description}: {$order->getDisplayName()}\n";
}
```

## Создание отчета по сортировке отзывов

```php
class ReviewOrderReport {
    private array $reviewsByDate;
    private array $reviewsByPositive;
    private array $reviewsByNegative;

    public function __construct(array $reviewsByDate, array $reviewsByPositive, array $reviewsByNegative) {
        $this->reviewsByDate = $reviewsByDate;
        $this->reviewsByPositive = $reviewsByPositive;
        $this->reviewsByNegative = $reviewsByNegative;
    }

    public function getReviewsByDate(): array {
        return $this->reviewsByDate;
    }

    public function getReviewsByPositive(): array {
        return $this->reviewsByPositive;
    }

    public function getReviewsByNegative(): array {
        return $this->reviewsByNegative;
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО СОРТИРОВКЕ ОТЗЫВОВ ===\n\n";

        // Статистика
        $report .= "📊 СТАТИСТИКА:\n";
        $report .= "Отзывов по дате: " . count($this->reviewsByDate) . "\n";
        $report .= "Отзывов по положительным оценкам: " . count($this->reviewsByPositive) . "\n";
        $report .= "Отзывов по отрицательным оценкам: " . count($this->reviewsByNegative) . "\n\n";

        // Сравнение первых отзывов
        $report .= "🔍 СРАВНЕНИЕ ПЕРВЫХ ОТЗЫВОВ:\n";

        if (!empty($this->reviewsByDate)) {
            $firstByDate = $this->reviewsByDate[0];
            $report .= "• По дате: {$firstByDate->getDisplayName()}\n";
            $report .= "  Дата: {$firstByDate->date}\n";
            $report .= "  Положительных оценок: {$firstByDate->positiveRating}\n";
            $report .= "  Отрицательных оценок: {$firstByDate->negativeRating}\n\n";
        }

        if (!empty($this->reviewsByPositive)) {
            $firstByPositive = $this->reviewsByPositive[0];
            $report .= "• По положительным оценкам: {$firstByPositive->getDisplayName()}\n";
            $report .= "  Дата: {$firstByPositive->date}\n";
            $report .= "  Положительных оценок: {$firstByPositive->positiveRating}\n";
            $report .= "  Отрицательных оценок: {$firstByPositive->negativeRating}\n\n";
        }

        if (!empty($this->reviewsByNegative)) {
            $firstByNegative = $this->reviewsByNegative[0];
            $report .= "• По отрицательным оценкам: {$firstByNegative->getDisplayName()}\n";
            $report .= "  Дата: {$firstByNegative->date}\n";
            $report .= "  Положительных оценок: {$firstByNegative->positiveRating}\n";
            $report .= "  Отрицательных оценок: {$firstByNegative->negativeRating}\n\n";
        }

        // Анализ эффективности сортировки
        $report .= "📈 АНАЛИЗ ЭФФЕКТИВНОСТИ СОРТИРОВКИ:\n";

        $avgPositiveByDate = $this->calculateAveragePositiveRating($this->reviewsByDate);
        $avgPositiveByPositive = $this->calculateAveragePositiveRating($this->reviewsByPositive);
        $avgPositiveByNegative = $this->calculateAveragePositiveRating($this->reviewsByNegative);

        $report .= "• Среднее количество положительных оценок:\n";
        $report .= "  - По дате: {$avgPositiveByDate}\n";
        $report .= "  - По положительным оценкам: {$avgPositiveByPositive}\n";
        $report .= "  - По отрицательным оценкам: {$avgPositiveByNegative}\n\n";

        return $report;
    }

    private function calculateAveragePositiveRating(array $reviews): float {
        if (empty($reviews)) {
            return 0;
        }

        $total = 0;
        foreach ($reviews as $review) {
            $total += $review->positiveRating;
        }

        return round($total / count($reviews), 2);
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".review-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".review-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".review-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".review-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".date-sort { border-left: 5px solid #6c757d; }\n";
        $html .= ".positive-sort { border-left: 5px solid #28a745; }\n";
        $html .= ".negative-sort { border-left: 5px solid #dc3545; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Статистика</h2>\n";
        $html .= "<p><strong>Отзывов по дате:</strong> " . count($this->reviewsByDate) . "</p>\n";
        $html .= "<p><strong>Отзывов по положительным оценкам:</strong> " . count($this->reviewsByPositive) . "</p>\n";
        $html .= "<p><strong>Отзывов по отрицательным оценкам:</strong> " . count($this->reviewsByNegative) . "</p>\n";
        $html .= "</div>\n";

        // Отзывы по дате
        if (!empty($this->reviewsByDate)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📅 Отзывы по дате (новые сначала)</div>\n";
            $html .= "<div class='review-grid'>\n";

            foreach (array_slice($this->reviewsByDate, 0, 6) as $review) {
                $html .= "<div class='review-item date-sort'>\n";
                $html .= "<div class='review-title'>{$review->getDisplayName()}</div>\n";
                $html .= "<div class='review-meta'>Дата: {$review->date}</div>\n";
                $html .= "<div class='review-meta'>Положительных: {$review->positiveRating}</div>\n";
                $html .= "<div class='review-meta'>Отрицательных: {$review->negativeRating}</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Отзывы по положительным оценкам
        if (!empty($this->reviewsByPositive)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>👍 Отзывы по положительным оценкам</div>\n";
            $html .= "<div class='review-grid'>\n";

            foreach (array_slice($this->reviewsByPositive, 0, 6) as $review) {
                $html .= "<div class='review-item positive-sort'>\n";
                $html .= "<div class='review-title'>{$review->getDisplayName()}</div>\n";
                $html .= "<div class='review-meta'>Дата: {$review->date}</div>\n";
                $html .= "<div class='review-meta'>Положительных: {$review->positiveRating}</div>\n";
                $html .= "<div class='review-meta'>Отрицательных: {$review->negativeRating}</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Отзывы по отрицательным оценкам
        if (!empty($this->reviewsByNegative)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>👎 Отзывы по отрицательным оценкам</div>\n";
            $html .= "<div class='review-grid'>\n";

            foreach (array_slice($this->reviewsByNegative, 0, 6) as $review) {
                $html .= "<div class='review-item negative-sort'>\n";
                $html .= "<div class='review-title'>{$review->getDisplayName()}</div>\n";
                $html .= "<div class='review-meta'>Дата: {$review->date}</div>\n";
                $html .= "<div class='review-meta'>Положительных: {$review->positiveRating}</div>\n";
                $html .= "<div class='review-meta'>Отрицательных: {$review->negativeRating}</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$reviewsByDate = $filmService->getReviews(301, 1, ReviewOrder::DATE_DESC);
$reviewsByPositive = $filmService->getReviews(301, 1, ReviewOrder::USER_POSITIVE_RATING_DESC);
$reviewsByNegative = $filmService->getReviews(301, 1, ReviewOrder::USER_NEGATIVE_RATING_DESC);

$report = new ReviewOrderReport($reviewsByDate, $reviewsByPositive, $reviewsByNegative);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по сортировке отзывов');
file_put_contents('review_order_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в review_order_report.html\n";
```

## Анализ сортировки отзывов

```php
function analyzeReviewOrders(array $reviewsByDate, array $reviewsByPositive, array $reviewsByNegative): array {
    $analysis = [
        'totalReviews' => [
            'date' => count($reviewsByDate),
            'positive' => count($reviewsByPositive),
            'negative' => count($reviewsByNegative)
        ],
        'averagePositiveRating' => [
            'date' => 0,
            'positive' => 0,
            'negative' => 0
        ],
        'averageNegativeRating' => [
            'date' => 0,
            'positive' => 0,
            'negative' => 0
        ],
        'effectiveness' => [
            'date' => 0,
            'positive' => 0,
            'negative' => 0
        ]
    ];

    // Анализ отзывов по дате
    if (!empty($reviewsByDate)) {
        $totalPositive = 0;
        $totalNegative = 0;
        foreach ($reviewsByDate as $review) {
            $totalPositive += $review->positiveRating;
            $totalNegative += $review->negativeRating;
        }
        $analysis['averagePositiveRating']['date'] = round($totalPositive / count($reviewsByDate), 2);
        $analysis['averageNegativeRating']['date'] = round($totalNegative / count($reviewsByDate), 2);
        $analysis['effectiveness']['date'] = round(($totalPositive / ($totalPositive + $totalNegative)) * 100, 1);
    }

    // Анализ отзывов по положительным оценкам
    if (!empty($reviewsByPositive)) {
        $totalPositive = 0;
        $totalNegative = 0;
        foreach ($reviewsByPositive as $review) {
            $totalPositive += $review->positiveRating;
            $totalNegative += $review->negativeRating;
        }
        $analysis['averagePositiveRating']['positive'] = round($totalPositive / count($reviewsByPositive), 2);
        $analysis['averageNegativeRating']['positive'] = round($totalNegative / count($reviewsByPositive), 2);
        $analysis['effectiveness']['positive'] = round(($totalPositive / ($totalPositive + $totalNegative)) * 100, 1);
    }

    // Анализ отзывов по отрицательным оценкам
    if (!empty($reviewsByNegative)) {
        $totalPositive = 0;
        $totalNegative = 0;
        foreach ($reviewsByNegative as $review) {
            $totalPositive += $review->positiveRating;
            $totalNegative += $review->negativeRating;
        }
        $analysis['averagePositiveRating']['negative'] = round($totalPositive / count($reviewsByNegative), 2);
        $analysis['averageNegativeRating']['negative'] = round($totalNegative / count($reviewsByNegative), 2);
        $analysis['effectiveness']['negative'] = round(($totalPositive / ($totalPositive + $totalNegative)) * 100, 1);
    }

    return $analysis;
}

// Использование
$reviewsByDate = $filmService->getReviews(301, 1, ReviewOrder::DATE_DESC);
$reviewsByPositive = $filmService->getReviews(301, 1, ReviewOrder::USER_POSITIVE_RATING_DESC);
$reviewsByNegative = $filmService->getReviews(301, 1, ReviewOrder::USER_NEGATIVE_RATING_DESC);

$analysis = analyzeReviewOrders($reviewsByDate, $reviewsByPositive, $reviewsByNegative);

echo "=== Анализ сортировки отзывов ===\n";
echo "Всего отзывов:\n";
echo "- По дате: {$analysis['totalReviews']['date']}\n";
echo "- По положительным оценкам: {$analysis['totalReviews']['positive']}\n";
echo "- По отрицательным оценкам: {$analysis['totalReviews']['negative']}\n";

echo "\nСреднее количество положительных оценок:\n";
echo "- По дате: {$analysis['averagePositiveRating']['date']}\n";
echo "- По положительным оценкам: {$analysis['averagePositiveRating']['positive']}\n";
echo "- По отрицательным оценкам: {$analysis['averagePositiveRating']['negative']}\n";

echo "\nСреднее количество отрицательных оценок:\n";
echo "- По дате: {$analysis['averageNegativeRating']['date']}\n";
echo "- По положительным оценкам: {$analysis['averageNegativeRating']['positive']}\n";
echo "- По отрицательным оценкам: {$analysis['averageNegativeRating']['negative']}\n";

echo "\nЭффективность сортировки (% положительных оценок):\n";
echo "- По дате: {$analysis['effectiveness']['date']}%\n";
echo "- По положительным оценкам: {$analysis['effectiveness']['positive']}%\n";
echo "- По отрицательным оценкам: {$analysis['effectiveness']['negative']}%\n";
```

## Связанные классы

- [`Review`](../models/review.md) - Модель отзыва
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
