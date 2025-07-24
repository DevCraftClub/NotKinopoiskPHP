# KeywordSearchResponse

Ответ на поиск фильмов по ключевому слову.

## Описание

`KeywordSearchResponse` представляет результат поиска фильмов в Kinopoisk API по заданному ключевому слову. Наследует функциональность пагинации от `PaginatedResponse` и добавляет специфичные для поиска поля: ключевое слово, количество страниц и общее количество найденных фильмов.

### Основные возможности

- Хранение результатов поиска с пагинацией
- Валидация входных параметров поиска
- Создание объекта из данных API
- Проверка логической согласованности данных

## Свойства

### Специфичные для поиска

- `$keyword` (string) - Ключевое слово для поиска
- `$pagesCount` (int) - Общее количество страниц результатов
- `$searchFilmsCountResult` (int) - Общее количество найденных фильмов
- `$films` (array) - Массив найденных фильмов

### Наследуемые от PaginatedResponse

- `$total` (int) - Общее количество элементов (наследуется)
- `$items` (array) - Массив элементов (наследуется)
- `$currentPage` (int) - Текущая страница (всегда 1 для поиска)
- `$totalPages` (int) - Общее количество страниц (наследуется)

## Конструктор

```php
public function __construct(
    public string $keyword,
    public int $pagesCount,
    public int $searchFilmsCountResult,
    public array $films = []
)
```

### Параметры

- `$keyword` (string) - Ключевое слово для поиска (не может быть пустым)
- `$pagesCount` (int) - Общее количество страниц результатов (неотрицательное)
- `$searchFilmsCountResult` (int) - Общее количество найденных фильмов (неотрицательное)
- `$films` (array) - Массив найденных фильмов (по умолчанию пустой)

### Исключения

- `\NotKinopoisk\Exception\KpValidationException` - При некорректных параметрах поиска

### Пример использования

```php
$response = new KeywordSearchResponse(
    keyword: 'драма',
    pagesCount: 5,
    searchFilmsCountResult: 250,
    films: $searchResults
);
```

## Методы

### fromArray()

Создает экземпляр ответа поиска из массива данных API.

```php
public static function fromArray(array $data, string $cls): self
```

#### Параметры

- `$data` (array) - Массив данных от API, содержащий результаты поиска
- `$cls` (string) - Имя класса для создания объектов фильмов (обычно Film::class)

#### Возвращаемое значение

- `self` - Новый экземпляр ответа поиска

#### Исключения

- `KpValidationException` - При ошибках типизации или некорректных данных

#### Пример использования

```php
$apiResponse = [
    'keyword' => 'комедия',
    'pagesCount' => 10,
    'searchFilmsCountResult' => 500,
    'films' => [
        ['kinopoiskId' => 123, 'nameRu' => 'Комедия 1'],
        ['kinopoiskId' => 456, 'nameRu' => 'Комедия 2']
    ]
];

$response = KeywordSearchResponse::fromArray($apiResponse, Film::class);
```

### toArray()

Преобразует ответ поиска в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Ассоциативный массив с данными ответа поиска

#### Описание

Конвертирует все свойства объекта в ассоциативный массив, включая наследованные поля от PaginatedResponse. Преобразует объекты фильмов в массивы через их метод toArray().

#### Пример использования

```php
$responseArray = $searchResponse->toArray();

// Результат:
// [
//     'keyword' => 'драма',
//     'pagesCount' => 5,
//     'searchFilmsCountResult' => 250,
//     'films' => [...], // массив фильмов
//     'total' => 250,
//     'currentPage' => 1,
//     'totalPages' => 5
// ]
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Поиск фильмов по ключевому слову
$filmService = $client->films;
$response = $filmService->searchByKeyword('драма');

echo "=== Работа с KeywordSearchResponse ===\n";

// Основная информация о поиске
echo "Ключевое слово: '{$response->keyword}'\n";
echo "Найдено фильмов: {$response->searchFilmsCountResult}\n";
echo "Количество страниц: {$response->pagesCount}\n";
echo "Текущая страница: {$response->currentPage}\n";
echo "Всего страниц: {$response->totalPages}\n\n";

// Информация о пагинации
echo "Информация о пагинации:\n";
$paginationInfo = $response->getPaginationInfo();
echo "Текущая страница: {$paginationInfo['current']}\n";
echo "Всего страниц: {$paginationInfo['total']}\n";
echo "Есть следующая страница: " . ($paginationInfo['hasNext'] ? 'Да' : 'Нет') . "\n";
echo "Есть предыдущая страница: " . ($paginationInfo['hasPrevious'] ? 'Да' : 'Нет') . "\n\n";

// Вывод найденных фильмов
if (!$response->isEmpty()) {
    echo "Найденные фильмы:\n";
    foreach ($response->films as $index => $film) {
        echo ($index + 1) . ". {$film->getDisplayName()}\n";
        if ($film->rating) {
            echo "   Рейтинг: {$film->rating}\n";
        }
        if ($film->year) {
            echo "   Год: {$film->year}\n";
        }
        if ($film->description) {
            echo "   Описание: " . substr($film->description, 0, 100) . "...\n";
        }
        echo "\n";
    }
} else {
    echo "По запросу '{$response->keyword}' ничего не найдено.\n";
}

// Преобразование в массив
$arrayData = $response->toArray();
echo "Данные в виде массива:\n";
echo "keyword: {$arrayData['keyword']}\n";
echo "pagesCount: {$arrayData['pagesCount']}\n";
echo "searchFilmsCountResult: {$arrayData['searchFilmsCountResult']}\n";
echo "films count: " . count($arrayData['films']) . "\n";
```

## Работа с поиском по ключевым словам

```php
// Функция для анализа результатов поиска
function analyzeSearchResults(KeywordSearchResponse $response): array {
    return [
        'searchInfo' => [
            'keyword' => $response->keyword,
            'totalFound' => $response->searchFilmsCountResult,
            'pagesCount' => $response->pagesCount,
            'currentPage' => $response->currentPage,
            'hasMorePages' => $response->hasNextPage()
        ],
        'filmsAnalysis' => [
            'totalFilms' => count($response->films),
            'filmsWithRating' => count(array_filter($response->films, fn($f) => $f->rating !== null)),
            'filmsWithYear' => count(array_filter($response->films, fn($f) => $f->year !== null)),
            'averageRating' => 0,
            'yearRange' => ['min' => null, 'max' => null]
        ]
    ];
}

// Функция для фильтрации результатов поиска
function filterSearchResults(KeywordSearchResponse $response, callable $filter): array {
    return array_filter($response->films, $filter);
}

// Функция для сортировки результатов поиска
function sortSearchResults(KeywordSearchResponse $response, callable $comparator): array {
    $films = $response->films;
    usort($films, $comparator);
    return $films;
}

// Функция для получения статистики по годам
function getYearStats(KeywordSearchResponse $response): array {
    $years = array_filter(array_map(fn($film) => $film->year, $response->films));

    if (empty($years)) {
        return ['count' => 0, 'distribution' => []];
    }

    return [
        'count' => count($years),
        'min' => min($years),
        'max' => max($years),
        'average' => round(array_sum($years) / count($years)),
        'distribution' => array_count_values($years)
    ];
}

// Функция для получения статистики по рейтингам
function getRatingStats(KeywordSearchResponse $response): array {
    $ratings = array_filter(array_map(fn($film) => $film->rating, $response->films));

    if (empty($ratings)) {
        return ['count' => 0, 'average' => 0, 'distribution' => []];
    }

    return [
        'count' => count($ratings),
        'average' => round(array_sum($ratings) / count($ratings), 2),
        'min' => min($ratings),
        'max' => max($ratings),
        'distribution' => array_count_values(array_map(fn($r) => floor($r), $ratings))
    ];
}

// Функция для поиска похожих ключевых слов
function findSimilarKeywords(KeywordSearchResponse $response): array {
    $similarKeywords = [];
    $baseKeyword = strtolower(trim($response->keyword));

    // Простая логика поиска похожих слов
    $variations = [
        $baseKeyword,
        $baseKeyword . 'ы',
        $baseKeyword . 'и',
        $baseKeyword . 'а',
        $baseKeyword . 'я'
    ];

    foreach ($variations as $variation) {
        if ($variation !== $baseKeyword) {
            $similarKeywords[] = $variation;
        }
    }

    return $similarKeywords;
}

// Функция для получения топ результатов
function getTopSearchResults(KeywordSearchResponse $response, int $limit = 10): array {
    $sortedFilms = sortSearchResults($response, function($a, $b) {
        $ratingA = $a->rating ?? 0;
        $ratingB = $b->rating ?? 0;
        return $ratingB <=> $ratingA;
    });

    return array_slice($sortedFilms, 0, $limit);
}

// Использование
$response = $filmService->searchByKeyword('комедия');

// Анализ результатов
$analysis = analyzeSearchResults($response);
echo "=== Анализ результатов поиска ===\n";
echo "Ключевое слово: '{$analysis['searchInfo']['keyword']}'\n";
echo "Всего найдено: {$analysis['searchInfo']['totalFound']}\n";
echo "Страниц результатов: {$analysis['searchInfo']['pagesCount']}\n";
echo "Есть еще страницы: " . ($analysis['searchInfo']['hasMorePages'] ? 'Да' : 'Нет') . "\n";

// Статистика фильмов
$filmsAnalysis = $analysis['filmsAnalysis'];
echo "\nСтатистика фильмов:\n";
echo "Всего фильмов на странице: {$filmsAnalysis['totalFilms']}\n";
echo "Фильмов с рейтингом: {$filmsAnalysis['filmsWithRating']}\n";
echo "Фильмов с годом: {$filmsAnalysis['filmsWithYear']}\n";

// Статистика по годам
$yearStats = getYearStats($response);
if ($yearStats['count'] > 0) {
    echo "\nСтатистика по годам:\n";
    echo "Диапазон: {$yearStats['min']} - {$yearStats['max']}\n";
    echo "Средний год: {$yearStats['average']}\n";

    echo "\nРаспределение по годам:\n";
    arsort($yearStats['distribution']);
    foreach (array_slice($yearStats['distribution'], 0, 10, true) as $year => $count) {
        echo "- {$year}: {$count} фильмов\n";
    }
}

// Статистика по рейтингам
$ratingStats = getRatingStats($response);
if ($ratingStats['count'] > 0) {
    echo "\nСтатистика по рейтингам:\n";
    echo "Средний рейтинг: {$ratingStats['average']}\n";
    echo "Диапазон: {$ratingStats['min']} - {$ratingStats['max']}\n";

    echo "\nРаспределение по рейтингам:\n";
    krsort($ratingStats['distribution']);
    foreach ($ratingStats['distribution'] as $rating => $count) {
        echo "- {$rating}+: {$count} фильмов\n";
    }
}

// Топ результатов
$topResults = getTopSearchResults($response, 5);
echo "\nТоп-5 результатов:\n";
foreach ($topResults as $index => $film) {
    echo ($index + 1) . ". {$film->getDisplayName()}";
    if ($film->rating) {
        echo " (рейтинг: {$film->rating})";
    }
    echo "\n";
}

// Похожие ключевые слова
$similarKeywords = findSimilarKeywords($response);
echo "\nПохожие ключевые слова:\n";
foreach ($similarKeywords as $keyword) {
    echo "- {$keyword}\n";
}
```

## Создание отчета по поиску

```php
class SearchReport {
    private KeywordSearchResponse $response;

    public function __construct(KeywordSearchResponse $response) {
        $this->response = $response;
    }

    public function getResponse(): KeywordSearchResponse {
        return $this->response;
    }

    public function getAnalysis(): array {
        return analyzeSearchResults($this->response);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ПОИСКУ ===\n\n";

        $analysis = $this->getAnalysis();
        $searchInfo = $analysis['searchInfo'];
        $filmsAnalysis = $analysis['filmsAnalysis'];

        // Информация о поиске
        $report .= "🔍 ИНФОРМАЦИЯ О ПОИСКЕ:\n";
        $report .= "Ключевое слово: '{$searchInfo['keyword']}'\n";
        $report .= "Всего найдено: {$searchInfo['totalFound']}\n";
        $report .= "Страниц результатов: {$searchInfo['pagesCount']}\n";
        $report .= "Текущая страница: {$searchInfo['currentPage']}\n";
        $report .= "Есть еще страницы: " . ($searchInfo['hasMorePages'] ? 'Да' : 'Нет') . "\n\n";

        // Статистика фильмов
        $report .= "📊 СТАТИСТИКА ФИЛЬМОВ:\n";
        $report .= "Всего фильмов на странице: {$filmsAnalysis['totalFilms']}\n";
        $report .= "Фильмов с рейтингом: {$filmsAnalysis['filmsWithRating']}\n";
        $report .= "Фильмов с годом: {$filmsAnalysis['filmsWithYear']}\n\n";

        // Статистика по годам
        $yearStats = getYearStats($this->response);
        if ($yearStats['count'] > 0) {
            $report .= "📅 СТАТИСТИКА ПО ГОДАМ:\n";
            $report .= "Диапазон: {$yearStats['min']} - {$yearStats['max']}\n";
            $report .= "Средний год: {$yearStats['average']}\n";

            $report .= "\nРаспределение по годам:\n";
            arsort($yearStats['distribution']);
            foreach (array_slice($yearStats['distribution'], 0, 10, true) as $year => $count) {
                $report .= "• {$year}: {$count} фильмов\n";
            }
            $report .= "\n";
        }

        // Статистика по рейтингам
        $ratingStats = getRatingStats($this->response);
        if ($ratingStats['count'] > 0) {
            $report .= "⭐ СТАТИСТИКА ПО РЕЙТИНГАМ:\n";
            $report .= "Средний рейтинг: {$ratingStats['average']}\n";
            $report .= "Диапазон: {$ratingStats['min']} - {$ratingStats['max']}\n";

            $report .= "\nРаспределение по рейтингам:\n";
            krsort($ratingStats['distribution']);
            foreach ($ratingStats['distribution'] as $rating => $count) {
                $report .= "• {$rating}+: {$count} фильмов\n";
            }
            $report .= "\n";
        }

        // Топ результатов
        $topResults = getTopSearchResults($this->response, 10);
        $report .= "🏆 ТОП-10 РЕЗУЛЬТАТОВ:\n";
        foreach ($topResults as $index => $film) {
            $report .= ($index + 1) . ". {$film->getDisplayName()}";
            if ($film->rating) {
                $report .= " (рейтинг: {$film->rating})";
            }
            if ($film->year) {
                $report .= " ({$film->year})";
            }
            $report .= "\n";
        }
        $report .= "\n";

        // Похожие ключевые слова
        $similarKeywords = findSimilarKeywords($this->response);
        if (!empty($similarKeywords)) {
            $report .= "🔗 ПОХОЖИЕ КЛЮЧЕВЫЕ СЛОВА:\n";
            foreach ($similarKeywords as $keyword) {
                $report .= "• {$keyword}\n";
            }
            $report .= "\n";
        }

        // Список всех фильмов
        if (!$this->response->isEmpty()) {
            $report .= "📋 ВСЕ НАЙДЕННЫЕ ФИЛЬМЫ:\n";
            foreach ($this->response->films as $index => $film) {
                $report .= ($index + 1) . ". {$film->getDisplayName()}\n";
                if ($film->rating) {
                    $report .= "   Рейтинг: {$film->rating}\n";
                }
                if ($film->year) {
                    $report .= "   Год: {$film->year}\n";
                }
                if ($film->description) {
                    $report .= "   Описание: " . substr($film->description, 0, 150) . "...\n";
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
        $html .= ".keyword { background-color: #007bff; color: white; padding: 5px 10px; border-radius: 3px; display: inline-block; margin: 5px; }\n";
        $html .= ".rating-high { color: #28a745; font-weight: bold; }\n";
        $html .= ".rating-medium { color: #ffc107; font-weight: bold; }\n";
        $html .= ".rating-low { color: #dc3545; font-weight: bold; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $analysis = $this->getAnalysis();
        $searchInfo = $analysis['searchInfo'];

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Информация о поиске</h2>\n";
        $html .= "<p><strong>Ключевое слово:</strong> <span class='keyword'>{$searchInfo['keyword']}</span></p>\n";
        $html .= "<p><strong>Всего найдено:</strong> {$searchInfo['totalFound']}</p>\n";
        $html .= "<p><strong>Страниц результатов:</strong> {$searchInfo['pagesCount']}</p>\n";
        $html .= "<p><strong>Текущая страница:</strong> {$searchInfo['currentPage']}</p>\n";
        $html .= "<p><strong>Есть еще страницы:</strong> " . ($searchInfo['hasMorePages'] ? 'Да' : 'Нет') . "</p>\n";
        $html .= "</div>\n";

        // Топ результатов
        $topResults = getTopSearchResults($this->response, 10);
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🏆 Топ-10 результатов</div>\n";
        $html .= "<div class='item-grid'>\n";

        foreach ($topResults as $item) {
            $html .= "<div class='item'>\n";
            $html .= "<div class='item-title'>{$item->getDisplayName()}</div>\n";
            if ($item->rating) {
                $ratingClass = $item->rating >= 8 ? 'rating-high' : ($item->rating >= 6 ? 'rating-medium' : 'rating-low');
                $html .= "<div class='item-meta {$ratingClass}'>Рейтинг: {$item->rating}</div>\n";
            }
            if ($item->year) {
                $html .= "<div class='item-meta'>Год: {$item->year}</div>\n";
            }
            if ($item->description) {
                $html .= "<div class='item-meta'>Описание: " . substr($item->description, 0, 100) . "...</div>\n";
            }
            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n";

        // Все фильмы
        if (!$this->response->isEmpty()) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>📋 Все найденные фильмы</div>\n";
            $html .= "<div class='item-grid'>\n";

            foreach ($this->response->films as $item) {
                $html .= "<div class='item'>\n";
                $html .= "<div class='item-title'>{$item->getDisplayName()}</div>\n";
                if ($item->rating) {
                    $ratingClass = $item->rating >= 8 ? 'rating-high' : ($item->rating >= 6 ? 'rating-medium' : 'rating-low');
                    $html .= "<div class='item-meta {$ratingClass}'>Рейтинг: {$item->rating}</div>\n";
                }
                if ($item->year) {
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
$response = $filmService->searchByKeyword('комедия');
$report = new SearchReport($response);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по поиску: ' . $response->keyword);
file_put_contents('search_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в search_report.html\n";
```

## Анализ поисковых запросов

```php
function analyzeSearchQuery(KeywordSearchResponse $response): array {
    $analysis = [
        'queryInfo' => [
            'keyword' => $response->keyword,
            'keywordLength' => strlen($response->keyword),
            'isSingleWord' => !str_contains($response->keyword, ' '),
            'wordCount' => count(explode(' ', trim($response->keyword))),
            'hasSpecialChars' => preg_match('/[^a-zA-Zа-яА-Я0-9\s]/', $response->keyword)
        ],
        'resultsInfo' => [
            'totalFound' => $response->searchFilmsCountResult,
            'pagesCount' => $response->pagesCount,
            'currentPage' => $response->currentPage,
            'hasMorePages' => $response->hasNextPage(),
            'resultsPerPage' => count($response->films),
            'averageResultsPerPage' => $response->pagesCount > 0 ? round($response->searchFilmsCountResult / $response->pagesCount, 1) : 0
        ],
        'contentAnalysis' => [
            'filmsWithRating' => count(array_filter($response->films, fn($f) => $f->rating !== null)),
            'filmsWithYear' => count(array_filter($response->films, fn($f) => $f->year !== null)),
            'filmsWithDescription' => count(array_filter($response->films, fn($f) => !empty($f->description))),
            'averageRating' => 0,
            'yearRange' => ['min' => null, 'max' => null]
        ]
    ];

    // Вычисляем средний рейтинг
    $ratings = array_filter(array_map(fn($film) => $film->rating, $response->films));
    if (!empty($ratings)) {
        $analysis['contentAnalysis']['averageRating'] = round(array_sum($ratings) / count($ratings), 2);
    }

    // Вычисляем диапазон годов
    $years = array_filter(array_map(fn($film) => $film->year, $response->films));
    if (!empty($years)) {
        $analysis['contentAnalysis']['yearRange'] = ['min' => min($years), 'max' => max($years)];
    }

    return $analysis;
}

// Использование
$response = $filmService->searchByKeyword('комедия');
$analysis = analyzeSearchQuery($response);

echo "=== Анализ поискового запроса ===\n";

// Информация о запросе
$queryInfo = $analysis['queryInfo'];
echo "Информация о запросе:\n";
echo "- Ключевое слово: '{$queryInfo['keyword']}'\n";
echo "- Длина ключевого слова: {$queryInfo['keywordLength']} символов\n";
echo "- Одно слово: " . ($queryInfo['isSingleWord'] ? 'Да' : 'Нет') . "\n";
echo "- Количество слов: {$queryInfo['wordCount']}\n";
echo "- Содержит спецсимволы: " . ($queryInfo['hasSpecialChars'] ? 'Да' : 'Нет') . "\n";

// Информация о результатах
$resultsInfo = $analysis['resultsInfo'];
echo "\nИнформация о результатах:\n";
echo "- Всего найдено: {$resultsInfo['totalFound']}\n";
echo "- Страниц результатов: {$resultsInfo['pagesCount']}\n";
echo "- Результатов на странице: {$resultsInfo['resultsPerPage']}\n";
echo "- Среднее результатов на страницу: {$resultsInfo['averageResultsPerPage']}\n";
echo "- Есть еще страницы: " . ($resultsInfo['hasMorePages'] ? 'Да' : 'Нет') . "\n";

// Анализ контента
$contentAnalysis = $analysis['contentAnalysis'];
echo "\nАнализ контента:\n";
echo "- Фильмов с рейтингом: {$contentAnalysis['filmsWithRating']}\n";
echo "- Фильмов с годом: {$contentAnalysis['filmsWithYear']}\n";
echo "- Фильмов с описанием: {$contentAnalysis['filmsWithDescription']}\n";

if ($contentAnalysis['averageRating'] > 0) {
    echo "- Средний рейтинг: {$contentAnalysis['averageRating']}\n";
}

if ($contentAnalysis['yearRange']['min'] !== null) {
    echo "- Диапазон годов: {$contentAnalysis['yearRange']['min']} - {$contentAnalysis['yearRange']['max']}\n";
}
```

## Связанные классы

- [`PaginatedResponse`](paginated-response.md) - Базовый класс для пагинированных ответов
- [`KpValidationException`](../exceptions/kp-validation-exception.md) - Исключение валидации
- [`Film`](../models/film.md) - Модель фильма
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
