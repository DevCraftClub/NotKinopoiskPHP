# PaginatedResponse

Пагинированный ответ API с поддержкой навигации по страницам.

## Описание

`PaginatedResponse` расширяет `DefaultResponse`, добавляя функциональность пагинации с безопасной навигацией по страницам и валидацией границ.

### Основные возможности

- Безопасная навигация по страницам с валидацией границ
- Promoted constructor properties для краткости кода
- Typed properties для строгой типизации
- Улучшенная обработка ошибок

## Свойства

### Наследуемые от DefaultResponse

- `$total` (int) - Общее количество элементов в коллекции
- `$items` (array) - Массив элементов данных в текущем ответе

### Специфичные для пагинации

- `$currentPage` (int) - Номер текущей страницы (начиная с 1)
- `$totalPages` (int) - Общее количество страниц

## Конструктор

```php
public function __construct(
    int $total,
    array $items,
    public int $currentPage,
    public int $totalPages
)
```

### Параметры

- `$total` (int) - Общее количество элементов
- `$items` (array) - Массив элементов текущей страницы
- `$currentPage` (int) - Номер текущей страницы (начиная с 1)
- `$totalPages` (int) - Общее количество страниц

### Исключения

- `\NotKinopoisk\Exception\KpValidationException` - При некорректных значениях пагинации

### Пример использования

```php
$response = new PaginatedResponse(
    total: 1000,
    items: $filmsArray,
    currentPage: 5,
    totalPages: 50
);
```

## Методы

### fromArray()

Создает экземпляр из массива данных API.

```php
public static function fromArray(array $data, string $cls): self
```

#### Параметры

- `$data` (array) - Данные API с обязательными ключами
- `$cls` (string) - Класс для преобразования элементов

#### Возвращаемое значение

- `self` - Новый экземпляр с преобразованными данными

#### Исключения

- `KpValidationException` - При ошибках валидации или преобразования

#### Пример использования

```php
$apiData = [
    'total' => 1000,
    'total_pages' => 50,
    'current_page' => 5,
    'items' => [...]
];

$response = PaginatedResponse::fromArray($apiData, Film::class);
```

### getNextPage()

Получает номер следующей страницы.

```php
public function getNextPage(): int
```

#### Возвращаемое значение

- `int` - Номер следующей страницы

#### Исключения

- `\OutOfBoundsException` - Если следующая страница недоступна

#### Пример использования

```php
if ($response->hasNextPage()) {
    $nextPage = $response->getNextPage();
    echo "Следующая страница: {$nextPage}";
}
```

### hasNextPage()

Проверяет наличие следующей страницы.

```php
public function hasNextPage(): bool
```

#### Возвращаемое значение

- `bool` - Доступна ли следующая страница

#### Пример использования

```php
if ($response->hasNextPage()) {
    echo "Есть следующая страница";
} else {
    echo "Это последняя страница";
}
```

### getPreviousPage()

Получает номер предыдущей страницы.

```php
public function getPreviousPage(): int
```

#### Возвращаемое значение

- `int` - Номер предыдущей страницы

#### Исключения

- `\OutOfBoundsException` - Если предыдущая страница недоступна

#### Пример использования

```php
if ($response->hasPreviousPage()) {
    $prevPage = $response->getPreviousPage();
    echo "Предыдущая страница: {$prevPage}";
}
```

### hasPreviousPage()

Проверяет наличие предыдущей страницы.

```php
public function hasPreviousPage(): bool
```

#### Возвращаемое значение

- `bool` - Доступна ли предыдущая страница

#### Пример использования

```php
if ($response->hasPreviousPage()) {
    echo "Есть предыдущая страница";
} else {
    echo "Это первая страница";
}
```

### getFirstPage()

Получает номер первой страницы.

```php
public function getFirstPage(): int
```

#### Возвращаемое значение

- `int` - Всегда возвращает 1

#### Пример использования

```php
$firstPage = $response->getFirstPage(); // Всегда 1
```

### getLastPage()

Получает номер последней страницы.

```php
public function getLastPage(): int
```

#### Возвращаемое значение

- `int` - Номер последней страницы

#### Пример использования

```php
$lastPage = $response->getLastPage();
echo "Последняя страница: {$lastPage}";
```

### isFirstPage()

Проверяет, является ли текущая страница первой.

```php
public function isFirstPage(): bool
```

#### Возвращаемое значение

- `bool` - Является ли первой страницей

#### Пример использования

```php
if ($response->isFirstPage()) {
    echo "Это первая страница";
}
```

### isLastPage()

Проверяет, является ли текущая страница последней.

```php
public function isLastPage(): bool
```

#### Возвращаемое значение

- `bool` - Является ли последней страницей

#### Пример использования

```php
if ($response->isLastPage()) {
    echo "Это последняя страница";
}
```

### getPaginationInfo()

Получает информацию о пагинации в виде массива.

```php
public function getPaginationInfo(): array
```

#### Возвращаемое значение

- `array{current: int, total: int, hasNext: bool, hasPrevious: bool}` - Информация о пагинации

#### Пример использования

```php
$paginationInfo = $response->getPaginationInfo();
echo "Страница {$paginationInfo['current']} из {$paginationInfo['total']}";
echo "Следующая страница: " . ($paginationInfo['hasNext'] ? 'Да' : 'Нет');
```

### toArray()

Преобразует объект пагинированного ответа в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными пагинации

#### Описание

Создает массив со всеми данными о пагинации и элементами результата. Каждый элемент в массиве items также преобразуется в массив с помощью вызова его метода toArray().

#### Пример использования

```php
$paginatedResponse = new PaginatedResponse(
    items: [$film1, $film2],
    total: 150,
    currentPage: 1,
    totalPages: 15
);

$array = $paginatedResponse->toArray();
// Результат:
// [
//     'total' => 150,
//     'items' => [
//         ['id' => 1, 'name' => 'Фильм 1', ...],
//         ['id' => 2, 'name' => 'Фильм 2', ...]
//     ],
//     'current_page' => 1,
//     'total_pages' => 15
// ]
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение пагинированных фильмов
$filmService = $client->films;
$response = $filmService->getTopFilms(page: 1);

echo "=== Работа с PaginatedResponse ===\n";

// Основная информация
echo "Общее количество фильмов: {$response->total}\n";
echo "Загружено на текущей странице: " . $response->getCount() . "\n";
echo "Текущая страница: {$response->currentPage}\n";
echo "Всего страниц: {$response->totalPages}\n\n";

// Навигация по страницам
echo "Навигация:\n";
echo "Первая страница: {$response->getFirstPage()}\n";
echo "Последняя страница: {$response->getLastPage()}\n";

if ($response->hasPreviousPage()) {
    echo "Предыдущая страница: {$response->getPreviousPage()}\n";
} else {
    echo "Предыдущая страница: недоступна\n";
}

if ($response->hasNextPage()) {
    echo "Следующая страница: {$response->getNextPage()}\n";
} else {
    echo "Следующая страница: недоступна\n";
}

echo "\n";

// Проверки страниц
echo "Проверки:\n";
echo "Это первая страница: " . ($response->isFirstPage() ? 'Да' : 'Нет') . "\n";
echo "Это последняя страница: " . ($response->isLastPage() ? 'Да' : 'Нет') . "\n\n";

// Информация о пагинации
$paginationInfo = $response->getPaginationInfo();
echo "Информация о пагинации:\n";
echo "Текущая страница: {$paginationInfo['current']}\n";
echo "Всего страниц: {$paginationInfo['total']}\n";
echo "Есть следующая страница: " . ($paginationInfo['hasNext'] ? 'Да' : 'Нет') . "\n";
echo "Есть предыдущая страница: " . ($paginationInfo['hasPrevious'] ? 'Да' : 'Нет') . "\n\n";

// Вывод фильмов
if (!$response->isEmpty()) {
    echo "Фильмы на текущей странице:\n";
    foreach ($response->items as $index => $film) {
        echo ($index + 1) . ". {$film->getDisplayName()}\n";
        if ($film->rating) {
            echo "   Рейтинг: {$film->rating}\n";
        }
        if ($film->year) {
            echo "   Год: {$film->year}\n";
        }
        echo "\n";
    }
}

// Преобразование в массив
$arrayData = $response->toArray();
echo "Данные в виде массива:\n";
echo "total: {$arrayData['total']}\n";
echo "current_page: {$arrayData['current_page']}\n";
echo "total_pages: {$arrayData['total_pages']}\n";
echo "items count: " . count($arrayData['items']) . "\n";
```

## Работа с пагинацией

```php
// Функция для навигации по всем страницам
function navigateAllPages(PaginatedResponse $response, callable $pageCallback): void {
    $currentPage = $response->currentPage;
    $totalPages = $response->totalPages;

    echo "Начинаем навигацию с страницы {$currentPage} из {$totalPages}\n";

    // Обрабатываем текущую страницу
    $pageCallback($response);

    // Переходим к следующим страницам
    while ($response->hasNextPage()) {
        $nextPage = $response->getNextPage();
        echo "Переходим к странице {$nextPage}\n";

        // Здесь должен быть запрос к API для получения следующей страницы
        // $nextResponse = $filmService->getTopFilms(page: $nextPage);
        // $pageCallback($nextResponse);
    }
}

// Функция для получения диапазона страниц
function getPageRange(PaginatedResponse $response, int $startPage, int $endPage): array {
    $range = [];

    for ($page = $startPage; $page <= $endPage; $page++) {
        if ($page >= 1 && $page <= $response->totalPages) {
            $range[] = $page;
        }
    }

    return $range;
}

// Функция для получения статистики пагинации
function getPaginationStats(PaginatedResponse $response): array {
    return [
        'total' => $response->total,
        'totalPages' => $response->totalPages,
        'currentPage' => $response->currentPage,
        'itemsPerPage' => $response->getCount(),
        'averageItemsPerPage' => $response->totalPages > 0 ? round($response->total / $response->totalPages, 1) : 0,
        'progress' => $response->totalPages > 0 ? round(($response->currentPage / $response->totalPages) * 100, 1) : 0,
        'hasNext' => $response->hasNextPage(),
        'hasPrevious' => $response->hasPreviousPage(),
        'isFirst' => $response->isFirstPage(),
        'isLast' => $response->isLastPage()
    ];
}

// Функция для создания навигационного меню
function createPaginationMenu(PaginatedResponse $response, int $maxVisiblePages = 5): array {
    $menu = [];
    $currentPage = $response->currentPage;
    $totalPages = $response->totalPages;

    // Добавляем кнопку "Первая"
    if (!$response->isFirstPage()) {
        $menu[] = ['type' => 'first', 'page' => 1, 'text' => '« Первая'];
    }

    // Добавляем кнопку "Предыдущая"
    if ($response->hasPreviousPage()) {
        $menu[] = ['type' => 'previous', 'page' => $response->getPreviousPage(), 'text' => '‹ Предыдущая'];
    }

    // Вычисляем диапазон страниц для отображения
    $startPage = max(1, $currentPage - floor($maxVisiblePages / 2));
    $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);

    // Корректируем начальную страницу, если нужно
    if ($endPage - $startPage + 1 < $maxVisiblePages) {
        $startPage = max(1, $endPage - $maxVisiblePages + 1);
    }

    // Добавляем номера страниц
    for ($page = $startPage; $page <= $endPage; $page++) {
        $menu[] = [
            'type' => $page === $currentPage ? 'current' : 'page',
            'page' => $page,
            'text' => (string)$page
        ];
    }

    // Добавляем кнопку "Следующая"
    if ($response->hasNextPage()) {
        $menu[] = ['type' => 'next', 'page' => $response->getNextPage(), 'text' => 'Следующая ›'];
    }

    // Добавляем кнопку "Последняя"
    if (!$response->isLastPage()) {
        $menu[] = ['type' => 'last', 'page' => $totalPages, 'text' => 'Последняя »'];
    }

    return $menu;
}

// Функция для получения элементов с нескольких страниц
function getItemsFromMultiplePages(PaginatedResponse $response, array $pageNumbers): array {
    $allItems = [];

    foreach ($pageNumbers as $pageNumber) {
        if ($pageNumber >= 1 && $pageNumber <= $response->totalPages) {
            // Здесь должен быть запрос к API для получения страницы
            // $pageResponse = $filmService->getTopFilms(page: $pageNumber);
            // $allItems = array_merge($allItems, $pageResponse->items);
        }
    }

    return $allItems;
}

// Использование
$response = $filmService->getTopFilms(page: 1);

// Статистика пагинации
$stats = getPaginationStats($response);
echo "Статистика пагинации:\n";
echo "- Всего элементов: {$stats['total']}\n";
echo "- Всего страниц: {$stats['totalPages']}\n";
echo "- Текущая страница: {$stats['currentPage']}\n";
echo "- Элементов на странице: {$stats['itemsPerPage']}\n";
echo "- Среднее элементов на странице: {$stats['averageItemsPerPage']}\n";
echo "- Прогресс: {$stats['progress']}%\n";
echo "- Есть следующая страница: " . ($stats['hasNext'] ? 'Да' : 'Нет') . "\n";
echo "- Есть предыдущая страница: " . ($stats['hasPrevious'] ? 'Да' : 'Нет') . "\n";

// Навигационное меню
$menu = createPaginationMenu($response, 7);
echo "\nНавигационное меню:\n";
foreach ($menu as $item) {
    $type = $item['type'];
    $text = $item['text'];

    switch ($type) {
        case 'current':
            echo "[{$text}] ";
            break;
        case 'page':
            echo "{$text} ";
            break;
        default:
            echo "{$text} ";
    }
}
echo "\n";

// Диапазон страниц
$pageRange = getPageRange($response, 1, 5);
echo "\nДиапазон страниц 1-5: " . implode(', ', $pageRange) . "\n";
```

## Создание отчета по пагинации

```php
class PaginationReport {
    private PaginatedResponse $response;

    public function __construct(PaginatedResponse $response) {
        $this->response = $response;
    }

    public function getResponse(): PaginatedResponse {
        return $this->response;
    }

    public function getStats(): array {
        return getPaginationStats($this->response);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ПАГИНАЦИИ ===\n\n";

        $stats = $this->getStats();

        // Основная информация
        $report .= "📊 ОСНОВНАЯ ИНФОРМАЦИЯ:\n";
        $report .= "Общее количество элементов: {$stats['total']}\n";
        $report .= "Всего страниц: {$stats['totalPages']}\n";
        $report .= "Текущая страница: {$stats['currentPage']}\n";
        $report .= "Элементов на текущей странице: {$stats['itemsPerPage']}\n";
        $report .= "Среднее элементов на странице: {$stats['averageItemsPerPage']}\n";
        $report .= "Прогресс: {$stats['progress']}%\n\n";

        // Навигация
        $report .= "🧭 НАВИГАЦИЯ:\n";
        $report .= "Первая страница: {$this->response->getFirstPage()}\n";
        $report .= "Последняя страница: {$this->response->getLastPage()}\n";

        if ($this->response->hasPreviousPage()) {
            $report .= "Предыдущая страница: {$this->response->getPreviousPage()}\n";
        } else {
            $report .= "Предыдущая страница: недоступна\n";
        }

        if ($this->response->hasNextPage()) {
            $report .= "Следующая страница: {$this->response->getNextPage()}\n";
        } else {
            $report .= "Следующая страница: недоступна\n";
        }
        $report .= "\n";

        // Статус страниц
        $report .= "📄 СТАТУС СТРАНИЦ:\n";
        $report .= "Это первая страница: " . ($stats['isFirst'] ? 'Да' : 'Нет') . "\n";
        $report .= "Это последняя страница: " . ($stats['isLast'] ? 'Да' : 'Нет') . "\n";
        $report .= "Есть предыдущая страница: " . ($stats['hasPrevious'] ? 'Да' : 'Нет') . "\n";
        $report .= "Есть следующая страница: " . ($stats['hasNext'] ? 'Да' : 'Нет') . "\n\n";

        // Навигационное меню
        $menu = createPaginationMenu($this->response, 7);
        $report .= "🔗 НАВИГАЦИОННОЕ МЕНЮ:\n";
        foreach ($menu as $item) {
            $type = $item['type'];
            $text = $item['text'];

            switch ($type) {
                case 'current':
                    $report .= "[{$text}] ";
                    break;
                case 'page':
                    $report .= "{$text} ";
                    break;
                default:
                    $report .= "{$text} ";
            }
        }
        $report .= "\n\n";

        // Элементы текущей страницы
        if (!$this->response->isEmpty()) {
            $report .= "📋 ЭЛЕМЕНТЫ ТЕКУЩЕЙ СТРАНИЦЫ:\n";
            foreach ($this->response->items as $index => $item) {
                $report .= ($index + 1) . ". {$item->getDisplayName()}\n";
                if (method_exists($item, 'rating') && $item->rating) {
                    $report .= "   Рейтинг: {$item->rating}\n";
                }
                if (method_exists($item, 'year') && $item->year) {
                    $report .= "   Год: {$item->year}\n";
                }
            }
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
        $html .= ".item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".item-title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".item-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".item-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".pagination { display: flex; justify-content: center; align-items: center; gap: 10px; margin: 20px 0; }\n";
        $html .= ".pagination-item { padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; text-decoration: none; color: #333; }\n";
        $html .= ".pagination-item.current { background-color: #007bff; color: white; border-color: #007bff; }\n";
        $html .= ".pagination-item:hover { background-color: #f8f9fa; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; background-color: #007bff; transition: width 0.3s ease; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getStats();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Основная информация</h2>\n";
        $html .= "<p><strong>Общее количество элементов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Всего страниц:</strong> {$stats['totalPages']}</p>\n";
        $html .= "<p><strong>Текущая страница:</strong> {$stats['currentPage']}</p>\n";
        $html .= "<p><strong>Элементов на странице:</strong> {$stats['itemsPerPage']}</p>\n";
        $html .= "<p><strong>Среднее элементов на странице:</strong> {$stats['averageItemsPerPage']}</p>\n";

        // Прогресс-бар
        $html .= "<h3>Прогресс</h3>\n";
        $html .= "<div class='progress-bar'>\n";
        $html .= "<div class='progress-fill' style='width: {$stats['progress']}%'></div>\n";
        $html .= "</div>\n";
        $html .= "<p>{$stats['progress']}% завершено</p>\n";

        $html .= "</div>\n";

        // Навигация
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🧭 Навигация</div>\n";

        $menu = createPaginationMenu($this->response, 7);
        $html .= "<div class='pagination'>\n";
        foreach ($menu as $item) {
            $type = $item['type'];
            $text = $item['text'];
            $page = $item['page'];

            $cssClass = 'pagination-item';
            if ($type === 'current') {
                $cssClass .= ' current';
            }

            $html .= "<a href='#' class='{$cssClass}' data-page='{$page}'>{$text}</a>\n";
        }
        $html .= "</div>\n";
        $html .= "</div>\n";

        // Элементы
        if (!$this->response->isEmpty()) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📋 Элементы текущей страницы</div>\n";
            $html .= "<div class='item-grid'>\n";

            foreach ($this->response->items as $item) {
                $html .= "<div class='item'>\n";
                $html .= "<div class='item-title'>{$item->getDisplayName()}</div>\n";
                if (method_exists($item, 'rating') && $item->rating) {
                    $html .= "<div class='item-meta'>Рейтинг: {$item->rating}</div>\n";
                }
                if (method_exists($item, 'year') && $item->year) {
                    $html .= "<div class='item-meta'>Год: {$item->year}</div>\n";
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
$response = $filmService->getTopFilms(page: 1);
$report = new PaginationReport($response);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по пагинации');
file_put_contents('pagination_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в pagination_report.html\n";
```

## Анализ пагинации

```php
function analyzePagination(PaginatedResponse $response): array {
    $analysis = [
        'basicStats' => [
            'total' => $response->total,
            'totalPages' => $response->totalPages,
            'currentPage' => $response->currentPage,
            'itemsPerPage' => $response->getCount(),
            'averageItemsPerPage' => $response->totalPages > 0 ? round($response->total / $response->totalPages, 1) : 0,
            'progress' => $response->totalPages > 0 ? round(($response->currentPage / $response->totalPages) * 100, 1) : 0
        ],
        'navigation' => [
            'hasNext' => $response->hasNextPage(),
            'hasPrevious' => $response->hasPreviousPage(),
            'isFirst' => $response->isFirstPage(),
            'isLast' => $response->isLastPage(),
            'nextPage' => $response->hasNextPage() ? $response->getNextPage() : null,
            'previousPage' => $response->hasPreviousPage() ? $response->getPreviousPage() : null,
            'firstPage' => $response->getFirstPage(),
            'lastPage' => $response->getLastPage()
        ],
        'pageDistribution' => [
            'pagesBeforeCurrent' => $response->currentPage - 1,
            'pagesAfterCurrent' => $response->totalPages - $response->currentPage,
            'totalPagesInRange' => $response->totalPages,
            'currentPagePosition' => $response->totalPages > 0 ? round(($response->currentPage / $response->totalPages) * 100, 1) : 0
        ]
    ];

    return $analysis;
}

// Использование
$response = $filmService->getTopFilms(page: 1);
$analysis = analyzePagination($response);

echo "=== Анализ пагинации ===\n";

// Основная статистика
$basicStats = $analysis['basicStats'];
echo "Основная статистика:\n";
echo "- Всего элементов: {$basicStats['total']}\n";
echo "- Всего страниц: {$basicStats['totalPages']}\n";
echo "- Текущая страница: {$basicStats['currentPage']}\n";
echo "- Элементов на странице: {$basicStats['itemsPerPage']}\n";
echo "- Среднее элементов на странице: {$basicStats['averageItemsPerPage']}\n";
echo "- Прогресс: {$basicStats['progress']}%\n";

// Навигация
$navigation = $analysis['navigation'];
echo "\nНавигация:\n";
echo "- Есть следующая страница: " . ($navigation['hasNext'] ? 'Да' : 'Нет') . "\n";
echo "- Есть предыдущая страница: " . ($navigation['hasPrevious'] ? 'Да' : 'Нет') . "\n";
echo "- Это первая страница: " . ($navigation['isFirst'] ? 'Да' : 'Нет') . "\n";
echo "- Это последняя страница: " . ($navigation['isLast'] ? 'Да' : 'Нет') . "\n";

if ($navigation['nextPage']) {
    echo "- Следующая страница: {$navigation['nextPage']}\n";
}
if ($navigation['previousPage']) {
    echo "- Предыдущая страница: {$navigation['previousPage']}\n";
}

// Распределение страниц
$pageDistribution = $analysis['pageDistribution'];
echo "\nРаспределение страниц:\n";
echo "- Страниц до текущей: {$pageDistribution['pagesBeforeCurrent']}\n";
echo "- Страниц после текущей: {$pageDistribution['pagesAfterCurrent']}\n";
echo "- Всего страниц в диапазоне: {$pageDistribution['totalPagesInRange']}\n";
echo "- Позиция текущей страницы: {$pageDistribution['currentPagePosition']}%\n";
```

## Связанные классы

- [`DefaultResponse`](default-response.md) - Базовый класс для ответов API
- [`KpValidationException`](../exceptions/kp-validation-exception.md) - Исключение валидации
- [`Film`](../models/film.md) - Модель фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
