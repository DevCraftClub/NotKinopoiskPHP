# ReviewResponse

## Описание

`ReviewResponse` - это специализированный класс ответа для работы с отзывами на фильмы от Kinopoisk API. Наследует функциональность `PaginatedResponse` и добавляет специфичные поля для статистики отзывов по типам (положительные, отрицательные, нейтральные).

## Основные возможности

- Наследование функциональности пагинации от `PaginatedResponse`
- Хранение статистики отзывов по типам
- Создание объекта из данных API с валидацией
- Преобразование в массив для сериализации
- Поддержка всех методов пагинации

## Наследование

```php
NotKinopoisk\Responses\PaginatedResponse
└── NotKinopoisk\Responses\ReviewResponse
```

## Конструктор

```php
public function __construct(
    int $total,
    array $items,
    int $currentPage,
    int $totalPages,
    public int $totalPositiveReviews,
    public int $totalNegativeReviews,
    public int $totalNeutralReviews
)
```

### Параметры

- `$total` (int) - Общее количество отзывов
- `$items` (array) - Массив отзывов текущей страницы
- `$currentPage` (int) - Номер текущей страницы
- `$totalPages` (int) - Общее количество страниц
- `$totalPositiveReviews` (int) - Общее количество положительных отзывов
- `$totalNegativeReviews` (int) - Общее количество отрицательных отзывов
- `$totalNeutralReviews` (int) - Общее количество нейтральных отзывов

## Свойства

### totalPositiveReviews

```php
public int $totalPositiveReviews
```

Общее количество положительных отзывов на фильм.

### totalNegativeReviews

```php
public int $totalNegativeReviews
```

Общее количество отрицательных отзывов на фильм.

### totalNeutralReviews

```php
public int $totalNeutralReviews
```

Общее количество нейтральных отзывов на фильм.

## Статические методы

### fromArray()

```php
public static function fromArray(array $data, string $cls): self
```

Создает экземпляр `ReviewResponse` из массива данных API.

#### Параметры

- `$data` (array) - Массив данных от API, содержащий информацию об отзывах
- `$cls` (string) - Имя класса для элементов коллекции (обычно `Review::class`)

#### Возвращает

- `ReviewResponse` - Новый экземпляр с данными об отзывах

#### Исключения

- `KpValidationException` - Если данные имеют некорректную структуру

#### Пример использования

```php
$apiData = [
    'total' => 150,
    'items' => [...],
    'current_page' => 1,
    'total_pages' => 5,
    'totalPositiveReviews' => 100,
    'totalNegativeReviews' => 30,
    'totalNeutralReviews' => 20
];

$reviewResponse = ReviewResponse::fromArray($apiData, Review::class);
```

## Методы экземпляра

### toArray()

```php
public function toArray(): array
```

Преобразует объект в массив.

#### Возвращает

- `array` - Массив с данными об отзывах и статистикой

#### Пример использования

```php
$response = ReviewResponse::fromArray($apiData, Review::class);
$array = $response->toArray();
```

## Наследованные методы

`ReviewResponse` наследует все методы от `PaginatedResponse`:

- `getNextPage()` - Получение номера следующей страницы
- `hasNextPage()` - Проверка наличия следующей страницы
- `getPreviousPage()` - Получение номера предыдущей страницы
- `hasPreviousPage()` - Проверка наличия предыдущей страницы
- `getFirstPage()` - Получение номера первой страницы
- `getLastPage()` - Получение номера последней страницы
- `isFirstPage()` - Проверка, является ли текущая страница первой
- `isLastPage()` - Проверка, является ли текущая страница последней
- `getPaginationInfo()` - Получение информации о пагинации

## Типы отзывов

Класс поддерживает следующие типы отзывов:

- **Положительные** - Отзывы с положительной оценкой
- **Отрицательные** - Отзывы с отрицательной оценкой
- **Нейтральные** - Отзывы с нейтральной оценкой

## Статистика отзывов

Класс предоставляет доступ к статистике отзывов:

```php
// Получение статистики
$positiveCount = $reviewResponse->totalPositiveReviews;
$negativeCount = $reviewResponse->totalNegativeReviews;
$neutralCount = $reviewResponse->totalNeutralReviews;

// Вычисление общего количества
$totalReviews = $positiveCount + $negativeCount + $neutralCount;

// Вычисление процентов
$positivePercent = ($positiveCount / $totalReviews) * 100;
$negativePercent = ($negativeCount / $totalReviews) * 100;
$neutralPercent = ($neutralCount / $totalReviews) * 100;
```

## Обработка ошибок

Класс включает обработку ошибок:

- Валидация структуры данных API
- Проверка типизации элементов
- Обработка некорректных данных отзывов
- Валидация параметров пагинации

## Пример полного использования

```php
use NotKinopoisk\Responses\ReviewResponse;
use NotKinopoisk\Models\Review;

// Получение данных от API
$apiData = [
    'total' => 150,
    'items' => [
        [
            'reviewId' => 1,
            'reviewType' => 'POSITIVE',
            'reviewText' => 'Отличный фильм!'
        ],
        [
            'reviewId' => 2,
            'reviewType' => 'NEGATIVE',
            'reviewText' => 'Не понравилось'
        ]
    ],
    'current_page' => 1,
    'total_pages' => 5,
    'totalPositiveReviews' => 100,
    'totalNegativeReviews' => 30,
    'totalNeutralReviews' => 20
];

// Создание объекта ответа
$reviewResponse = ReviewResponse::fromArray($apiData, Review::class);

// Анализ статистики отзывов
$positiveCount = $reviewResponse->totalPositiveReviews;
$negativeCount = $reviewResponse->totalNegativeReviews;
$neutralCount = $reviewResponse->totalNeutralReviews;

$totalReviews = $positiveCount + $negativeCount + $neutralCount;

echo "Статистика отзывов:\n";
echo "- Положительных: {$positiveCount} (" . round(($positiveCount / $totalReviews) * 100, 1) . "%)\n";
echo "- Отрицательных: {$negativeCount} (" . round(($negativeCount / $totalReviews) * 100, 1) . "%)\n";
echo "- Нейтральных: {$neutralCount} (" . round(($neutralCount / $totalReviews) * 100, 1) . "%)\n";
echo "- Всего: {$totalReviews}\n";

// Работа с пагинацией
echo "\nПагинация:\n";
echo "- Текущая страница: {$reviewResponse->currentPage}\n";
echo "- Всего страниц: {$reviewResponse->totalPages}\n";
echo "- Отзывов на странице: " . count($reviewResponse->items) . "\n";

if ($reviewResponse->hasNextPage()) {
    echo "- Следующая страница: {$reviewResponse->getNextPage()}\n";
}

if ($reviewResponse->hasPreviousPage()) {
    echo "- Предыдущая страница: {$reviewResponse->getPreviousPage()}\n";
}

// Обработка отзывов
echo "\nОтзывы на текущей странице:\n";
foreach ($reviewResponse->items as $review) {
    echo "- [{$review->reviewType}] {$review->reviewText}\n";
}

// Преобразование в массив
$array = $reviewResponse->toArray();
```

## Связанные классы

- `PaginatedResponse` - Базовый класс для пагинированных ответов
- `Review` - Модель отзыва на фильм
- `KpValidationException` - Исключение для ошибок валидации
- `DefaultResponse` - Базовый класс для всех ответов API
