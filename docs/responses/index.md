# Ответы API

Классы для представления ответов от Kinopoisk API.

---

**📚 Навигация:** [Главная](../index.md) → Ответы

---

## 📋 Список ответов

### 📄 [DefaultResponse](default-response.md)

Базовый класс для всех ответов API.

**Основные возможности:**

- Хранение общего количества элементов
- Хранение массива элементов
- Валидация данных
- Создание из массива API
- Вспомогательные методы для работы с элементами

**Свойства:**

- `$total` (int) - Общее количество элементов
- `$items` (array) - Массив элементов

**Методы:**

- `first()` - Получение первого элемента
- `last()` - Получение последнего элемента
- `isEmpty()` - Проверка на пустоту
- `getCount()` - Получение количества элементов

### 📄 [PaginatedResponse](paginated-response.md)

Ответ с поддержкой пагинации.

**Основные возможности:**

- Наследует функциональность DefaultResponse
- Добавляет информацию о пагинации
- Методы для навигации по страницам
- Валидация параметров пагинации

**Дополнительные свойства:**

- `$currentPage` (int) - Текущая страница
- `$totalPages` (int) - Общее количество страниц

**Дополнительные методы:**

- `getNextPage()` - Получение следующей страницы
- `hasNextPage()` - Проверка наличия следующей страницы
- `getPreviousPage()` - Получение предыдущей страницы
- `hasPreviousPage()` - Проверка наличия предыдущей страницы
- `getPaginationInfo()` - Получение информации о пагинации

### 🔍 [KeywordSearchResponse](keyword-search-response.md)

Ответ на поиск фильмов по ключевому слову.

**Основные возможности:**

- Наследует функциональность PaginatedResponse
- Специфичные поля для поиска
- Валидация параметров поиска
- Создание из данных API

**Специфичные свойства:**

- `$keyword` (string) - Ключевое слово для поиска
- `$pagesCount` (int) - Общее количество страниц результатов
- `$searchFilmsCountResult` (int) - Общее количество найденных фильмов
- `$films` (array) - Массив найденных фильмов

### 💰 [BudgetResponse](budget-response.md)

Ответ с данными о бюджете фильма.

**Основные возможности:**

- Наследует функциональность DefaultResponse
- Вычисление общего дохода от всех источников
- Детализированная разбивка доходов по типам
- Подсчет количества доходных статей

**Специфичные методы:**

- `getTotalRevenue()` - Вычисление общего дохода
- `getRevenueBreakdown()` - Разбивка доходов по типам
- `getRevenueItemsCount()` - Количество доходных статей

### 🎬 [SequelPrequelResponse](sequel-prequel-response.md)

Ответ с сиквелами, приквелами и связанными фильмами.

**Основные возможности:**

- Наследует функциональность SimpleResponse
- Фильтрация фильмов по типу отношения
- Статистика по типам отношений
- Группировка фильмов по типам связей

**Специфичные методы:**

- `getSequels()` - Получение сиквелов
- `getPrequels()` - Получение приквелов
- `getRemakes()` - Получение римейков
- `getSimilar()` - Получение похожих фильмов
- `getStatistics()` - Статистика по типам отношений

### 👥 [MovieStaffResponse](movie-staff-response.md)

Ответ со съемочной командой фильма.

**Основные возможности:**

- Наследует функциональность SimpleResponse
- Фильтрация персонала по профессиональным ролям
- Получение различных групп персонала

**Специфичные методы:**

- `getActors()` - Получение актеров
- `getDirectors()` - Получение режиссеров
- `getWriters()` - Получение сценаристов
- `getProducers()` - Получение продюсеров
- `getCompositors()` - Получение композиторов
- `getEditors()` - Получение монтажеров
- `getDesigners()` - Получение художников

### 📝 [ReviewResponse](review-response.md)

Ответ с отзывами на фильм.

**Основные возможности:**

- Наследует функциональность PaginatedResponse
- Статистика отзывов по типам
- Поддержка всех методов пагинации

**Специфичные свойства:**

- `$totalPositiveReviews` (int) - Количество положительных отзывов
- `$totalNegativeReviews` (int) - Количество отрицательных отзывов
- `$totalNeutralReviews` (int) - Количество нейтральных отзывов

### 📦 [SimpleResponse](simple-response.md)

Базовый класс для простых ответов API.

**Основные возможности:**

- Хранение массива элементов без метаинформации
- Создание из данных API с валидацией класса
- Преобразование в массив для сериализации

**Свойства:**

- `$items` (array) - Массив элементов данных

**Методы:**

- `fromArray()` - Создание из массива данных
- `toArray()` - Преобразование в массив

## 🔗 Связанные компоненты

### Модели

- [Film](../models/film.md) - Используется в KeywordSearchResponse
- [FilmSearchResult](../models/film-search-result.md) - Результаты поиска
- [Staff](../models/staff.md) - Съемочная группа
- [Review](../models/review.md) - Отзывы
- [Fact](../models/fact.md) - Факты
- [Image](../models/image.md) - Изображения
- [Video](../models/video.md) - Видео
- [Person](../models/person.md) - Персоны
- [BoxOffice](../models/box-office.md) - Данные о кассовых сборах
- [RelatedFilm](../models/related-film.md) - Связанные фильмы

### Сервисы

- [FilmService](../services/film-service.md) - Возвращает ответы с фильмами
- [PersonService](../services/person-service.md) - Возвращает ответы с персонами
- [MediaService](../services/media-service.md) - Возвращает ответы с медиа
- [UserService](../services/user-service.md) - Возвращает ответы с пользовательскими данными

### Исключения

- [KpValidationException](../exceptions/kp-validation-exception.md) - Ошибки валидации ответов

### Интерфейсы

- [ResponseInterface](../interfaces/response-interface.md) - Базовый интерфейс ответов

## 🚀 Быстрый старт

### Работа с базовым ответом

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Models\Film;

// Создание ответа
$films = [
    Film::fromArray(['kinopoiskId' => 301, 'nameRu' => 'Матрица']),
    Film::fromArray(['kinopoiskId' => 302, 'nameRu' => 'Матрица: Перезагрузка'])
];

$response = new DefaultResponse(2, $films);

// Работа с ответом
echo "Всего элементов: {$response->total}\n";
echo "Количество элементов: {$response->getCount()}\n";
echo "Пустой ответ: " . ($response->isEmpty() ? 'Да' : 'Нет') . "\n";

// Получение элементов
$firstFilm = $response->first();
if ($firstFilm) {
    echo "Первый фильм: {$firstFilm->getDisplayName()}\n";
}

$lastFilm = $response->last();
if ($lastFilm) {
    echo "Последний фильм: {$lastFilm->getDisplayName()}\n";
}

// Перебор элементов
foreach ($response->items as $index => $film) {
    echo ($index + 1) . ". {$film->getDisplayName()}\n";
}
```

### Работа с пагинированным ответом

```php
use NotKinopoisk\Responses\PaginatedResponse;

// Создание пагинированного ответа
$response = new PaginatedResponse(100, $films, 1, 10);

// Информация о пагинации
echo "Текущая страница: {$response->currentPage}\n";
echo "Всего страниц: {$response->totalPages}\n";
echo "Есть следующая страница: " . ($response->hasNextPage() ? 'Да' : 'Нет') . "\n";
echo "Есть предыдущая страница: " . ($response->hasPreviousPage() ? 'Да' : 'Нет') . "\n";

// Навигация
if ($response->hasNextPage()) {
    $nextPage = $response->getNextPage();
    echo "Следующая страница: {$nextPage}\n";
}

if ($response->hasPreviousPage()) {
    $previousPage = $response->getPreviousPage();
    echo "Предыдущая страница: {$previousPage}\n";
}

// Полная информация о пагинации
$paginationInfo = $response->getPaginationInfo();
print_r($paginationInfo);
```

### Работа с ответом поиска

```php
use NotKinopoisk\Responses\KeywordSearchResponse;

// Создание ответа поиска
$response = new KeywordSearchResponse(
    keyword: 'матрица',
    pagesCount: 5,
    searchFilmsCountResult: 50,
    films: $films
);

// Информация о поиске
echo "Ключевое слово: '{$response->keyword}'\n";
echo "Найдено фильмов: {$response->searchFilmsCountResult}\n";
echo "Количество страниц: {$response->pagesCount}\n";

// Работа с пагинацией (наследуется от PaginatedResponse)
echo "Текущая страница: {$response->currentPage}\n";
echo "Всего страниц: {$response->totalPages}\n";

// Работа с фильмами
foreach ($response->films as $film) {
    echo "- {$film->getDisplayName()}\n";
}
```

## 📖 Примеры использования

### Создание ответа из данных API

```php
// Данные от API
$apiData = [
    'total' => 2,
    'items' => [
        ['kinopoiskId' => 301, 'nameRu' => 'Матрица'],
        ['kinopoiskId' => 302, 'nameRu' => 'Матрица: Перезагрузка']
    ]
];

// Создание ответа
$response = DefaultResponse::fromArray($apiData, Film::class);

echo "Создан ответ с {$response->total} элементами\n";
```

### Создание пагинированного ответа из данных API

```php
// Данные от API с пагинацией
$apiData = [
    'total' => 100,
    'items' => $films,
    'currentPage' => 1,
    'totalPages' => 10
];

// Создание пагинированного ответа
$response = PaginatedResponse::fromArray($apiData, Film::class);

echo "Страница {$response->currentPage} из {$response->totalPages}\n";
```

### Создание ответа поиска из данных API

```php
// Данные от API поиска
$apiData = [
    'keyword' => 'матрица',
    'pagesCount' => 5,
    'searchFilmsCountResult' => 50,
    'films' => $films
];

// Создание ответа поиска
$response = KeywordSearchResponse::fromArray($apiData, Film::class);

echo "По запросу '{$response->keyword}' найдено {$response->searchFilmsCountResult} фильмов\n";
```

### Работа с бюджетом фильма

```php
use NotKinopoisk\Responses\BudgetResponse;
use NotKinopoisk\Models\BoxOffice;

// Данные о бюджете от API
$budgetData = [
    'total' => 3,
    'items' => [
        ['type' => 'RUS', 'amount' => 1000000],
        ['type' => 'USA', 'amount' => 5000000],
        ['type' => 'WORLD', 'amount' => 15000000]
    ]
];

$budgetResponse = BudgetResponse::fromArray($budgetData, BoxOffice::class);

// Анализ финансовых показателей
$totalRevenue = $budgetResponse->getTotalRevenue();
$breakdown = $budgetResponse->getRevenueBreakdown();
$revenueCount = $budgetResponse->getRevenueItemsCount();

echo "Общий доход: {$totalRevenue}\n";
echo "Количество источников дохода: {$revenueCount}\n";

foreach ($breakdown as $source => $amount) {
    echo "Доход от {$source}: {$amount}\n";
}
```

### Работа с сиквелами и приквелами

```php
use NotKinopoisk\Responses\SequelPrequelResponse;
use NotKinopoisk\Models\RelatedFilm;

// Данные о связанных фильмах
$relatedData = [
    ['kinopoiskId' => 1, 'relationType' => 'SEQUEL'],
    ['kinopoiskId' => 2, 'relationType' => 'PREQUEL'],
    ['kinopoiskId' => 3, 'relationType' => 'SIMILAR']
];

$response = SequelPrequelResponse::fromArray($relatedData, RelatedFilm::class);

// Получение различных типов связанных фильмов
$sequels = $response->getSequels();
$prequels = $response->getPrequels();
$similar = $response->getSimilar();

$stats = $response->getStatistics();

echo "Статистика связанных фильмов:\n";
foreach ($stats as $type => $count) {
    echo "- {$type}: {$count}\n";
}
```

### Работа со съемочной командой

```php
use NotKinopoisk\Responses\MovieStaffResponse;
use NotKinopoisk\Models\Staff;

// Данные о съемочной команде
$staffData = [
    ['staffId' => 1, 'nameRu' => 'Иван Иванов', 'professionKey' => 'ACTOR'],
    ['staffId' => 2, 'nameRu' => 'Петр Петров', 'professionKey' => 'DIRECTOR'],
    ['staffId' => 3, 'nameRu' => 'Сидор Сидоров', 'professionKey' => 'WRITER']
];

$staffResponse = MovieStaffResponse::fromArray($staffData, Staff::class);

// Получение различных групп персонала
$actors = $staffResponse->getActors();
$directors = $staffResponse->getDirectors();
$writers = $staffResponse->getWriters();

echo "Актеры (" . count($actors) . "):\n";
foreach ($actors as $actor) {
    echo "- {$actor->nameRu}\n";
}

echo "Режиссеры (" . count($directors) . "):\n";
foreach ($directors as $director) {
    echo "- {$director->nameRu}\n";
}
```

### Работа с отзывами

```php
use NotKinopoisk\Responses\ReviewResponse;
use NotKinopoisk\Models\Review;

// Данные об отзывах
$reviewData = [
    'total' => 150,
    'items' => [
        ['reviewId' => 1, 'reviewType' => 'POSITIVE', 'reviewText' => 'Отличный фильм!'],
        ['reviewId' => 2, 'reviewType' => 'NEGATIVE', 'reviewText' => 'Не понравилось']
    ],
    'current_page' => 1,
    'total_pages' => 5,
    'totalPositiveReviews' => 100,
    'totalNegativeReviews' => 30,
    'totalNeutralReviews' => 20
];

$reviewResponse = ReviewResponse::fromArray($reviewData, Review::class);

// Анализ статистики отзывов
$positiveCount = $reviewResponse->totalPositiveReviews;
$negativeCount = $reviewResponse->totalNegativeReviews;
$neutralCount = $reviewResponse->totalNeutralReviews;

$totalReviews = $positiveCount + $negativeCount + $neutralCount;

echo "Статистика отзывов:\n";
echo "- Положительных: {$positiveCount} (" . round(($positiveCount / $totalReviews) * 100, 1) . "%)\n";
echo "- Отрицательных: {$negativeCount} (" . round(($negativeCount / $totalReviews) * 100, 1) . "%)\n";
echo "- Нейтральных: {$neutralCount} (" . round(($neutralCount / $totalReviews) * 100, 1) . "%)\n";
```

### Работа с простыми ответами

```php
use NotKinopoisk\Responses\SimpleResponse;
use NotKinopoisk\Models\MyModel;

// Простые данные от API
$simpleData = [
    ['id' => 1, 'name' => 'Первый элемент'],
    ['id' => 2, 'name' => 'Второй элемент'],
    ['id' => 3, 'name' => 'Третий элемент']
];

$response = SimpleResponse::fromArray($simpleData, MyModel::class);

// Доступ к элементам
$items = $response->items;
echo "Количество элементов: " . count($items) . "\n";

// Обработка элементов
foreach ($items as $item) {
    echo "ID: {$item->id}, Имя: {$item->name}\n";
}

// Преобразование в массив
$array = $response->toArray();
```

### Обработка пустых ответов

```php
// Пустой ответ
$emptyResponse = new DefaultResponse(0, []);

if ($emptyResponse->isEmpty()) {
    echo "Ответ пустой\n";
} else {
    echo "В ответе есть элементы\n";
}

// Проверка первого элемента
$first = $emptyResponse->first();
if ($first === null) {
    echo "Первый элемент отсутствует\n";
}
```

### Работа с большими ответами

```php
// Симуляция большого ответа
$largeResponse = new PaginatedResponse(1000, $films, 1, 100);

// Получение статистики
echo "Всего элементов: {$largeResponse->total}\n";
echo "Элементов на странице: " . count($largeResponse->items) . "\n";
echo "Всего страниц: {$largeResponse->totalPages}\n";

// Навигация по страницам
if ($largeResponse->hasNextPage()) {
    echo "Можно перейти на следующую страницу\n";
}

if ($largeResponse->isLastPage()) {
    echo "Это последняя страница\n";
}

if ($largeResponse->isFirstPage()) {
    echo "Это первая страница\n";
}
```

## 🔧 Общие методы

### fromArray()

```php
public static function fromArray(array $data, string $cls): self
```

Создает экземпляр ответа из массива данных API.

### toArray()

```php
public function toArray(): array
```

Преобразует ответ в массив.

### getCount()

```php
public function getCount(): int
```

Возвращает количество элементов в ответе.

### isEmpty()

```php
public function isEmpty(): bool
```

Проверяет, пуст ли ответ.

## 📊 Статистика ответов

### DefaultResponse

- **Свойства:** 2
- **Методы:** 8+
- **Использование:** Базовый класс для всех ответов

### PaginatedResponse

- **Свойства:** 4 (наследует 2 от DefaultResponse)
- **Методы:** 15+ (наследует 8+ от DefaultResponse)
- **Использование:** Все ответы с пагинацией

### KeywordSearchResponse

- **Свойства:** 7 (наследует 4 от PaginatedResponse)
- **Методы:** 20+ (наследует 15+ от PaginatedResponse)
- **Использование:** Ответы поиска фильмов

## 🔗 Связанные разделы

- [Модели](../models/index.md) - Элементы ответов
- [Сервисы](../services/index.md) - Возвращают ответы
- [Перечисления](../enums/index.md) - Используются в ответах
- [Исключения](../exceptions/index.md) - Обработка ошибок
- [Интерфейсы](../interfaces/index.md) - Базовые интерфейсы

---

**📚 Навигация:** [Главная](../index.md) → Ответы
