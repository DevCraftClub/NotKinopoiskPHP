# Award

Модель награды из Kinopoisk API.

## Описание

`Award` представляет информацию о награде или номинации фильма, включая название награды, год, номинацию и связанных персон.

### Основные возможности

- Хранение информации о награде в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к метаданным награды и связанным персонам

**API Endpoint:** `/api/v2.2/films/{id}/awards`

## Свойства

### Основная информация

- `$name` (string) - Название награды
- `$win` (bool) - Флаг победы в номинации
- `$imageUrl` (string|null) - URL изображения награды
- `$nominationName` (string) - Название номинации
- `$year` (int) - Год вручения награды
- `$persons` (array) - Массив связанных персон

## Конструктор

```php
public function __construct(
    public readonly string $name,
    public readonly bool $win,
    public readonly ?string $imageUrl,
    public readonly string $nominationName,
    public readonly int $year,
    public readonly array $persons,
)
```

### Пример создания

```php
$award = new Award(
    name: 'Оскар',
    win: true,
    imageUrl: 'https://...',
    nominationName: 'Лучший фильм',
    year: 2023,
    persons: [$person1, $person2]
);
```

## Методы

### fromArray()

Создает экземпляр награды из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных награды от API

#### Возвращаемое значение

- `self` - Новый экземпляр награды

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'name' => 'Оскар',
    'win' => true,
    'imageUrl' => 'https://...',
    'nominationName' => 'Лучший фильм',
    'year' => 2023,
    'persons' => [$personData1, $personData2]
];

$award = Award::fromArray($apiData);
```

### toArray()

Преобразует объект награды в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными награды

#### Пример использования

```php
$awardArray = $award->toArray();
echo json_encode($awardArray); // JSON представление награды
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Award;

// Создание клиента
$client = new Client('your-api-key');

// Получение наград фильма
$filmService = $client->films;
$awards = $filmService->getAwards(301); // Матрица

echo "=== Награды фильма 'Матрица' ===\n";

// Группировка по статусу
$wins = [];
$nominations = [];

foreach ($awards as $award) {
    if ($award->win) {
        $wins[] = $award;
    } else {
        $nominations[] = $award;
    }
}

// Вывод побед
if (!empty($wins)) {
    echo "\n🏆 Победы (" . count($wins) . "):\n";
    foreach ($wins as $award) {
        echo "• {$award->name} ({$award->year})\n";
        echo "  Номинация: {$award->nominationName}\n";
        if (!empty($award->persons)) {
            echo "  Участники: " . implode(', ', array_column($award->persons, 'name')) . "\n";
        }
        echo "  ---\n";
    }
}

// Вывод номинаций
if (!empty($nominations)) {
    echo "\n🎭 Номинации (" . count($nominations) . "):\n";
    foreach ($nominations as $award) {
        echo "• {$award->name} ({$award->year})\n";
        echo "  Номинация: {$award->nominationName}\n";
        if (!empty($award->persons)) {
            echo "  Участники: " . implode(', ', array_column($award->persons, 'name')) . "\n";
        }
        echo "  ---\n";
    }
}
```

## Работа с наградами

```php
// Функция для фильтрации наград по статусу
function filterAwardsByStatus(array $awards, bool $win): array {
    return array_filter($awards, fn($award) => $award->win === $win);
}

// Функция для фильтрации наград по году
function filterAwardsByYear(array $awards, int $year): array {
    return array_filter($awards, fn($award) => $award->year === $year);
}

// Функция для поиска наград по названию
function searchAwardsByName(array $awards, string $keyword): array {
    $keyword = strtolower($keyword);

    return array_filter($awards, function($award) use ($keyword) {
        return strpos(strtolower($award->name), $keyword) !== false ||
               strpos(strtolower($award->nominationName), $keyword) !== false;
    });
}

// Функция для получения статистики наград
function getAwardsStatistics(array $awards): array {
    $stats = [
        'total' => count($awards),
        'wins' => 0,
        'nominations' => 0,
        'years' => [],
        'awards' => [],
        'nominations' => []
    ];

    foreach ($awards as $award) {
        // Подсчет побед и номинаций
        if ($award->win) {
            $stats['wins']++;
        } else {
            $stats['nominations']++;
        }

        // Статистика по годам
        if (!isset($stats['years'][$award->year])) {
            $stats['years'][$award->year] = ['wins' => 0, 'nominations' => 0];
        }
        if ($award->win) {
            $stats['years'][$award->year]['wins']++;
        } else {
            $stats['years'][$award->year]['nominations']++;
        }

        // Статистика по наградам
        if (!isset($stats['awards'][$award->name])) {
            $stats['awards'][$award->name] = ['wins' => 0, 'nominations' => 0];
        }
        if ($award->win) {
            $stats['awards'][$award->name]['wins']++;
        } else {
            $stats['awards'][$award->name]['nominations']++;
        }

        // Статистика по номинациям
        if (!isset($stats['nominations'][$award->nominationName])) {
            $stats['nominations'][$award->nominationName] = 0;
        }
        $stats['nominations'][$award->nominationName]++;
    }

    return $stats;
}

// Использование
$awards = $filmService->getAwards(301);

// Фильтрация по статусу
$wins = filterAwardsByStatus($awards, true);
$nominations = filterAwardsByStatus($awards, false);
echo "Побед: " . count($wins) . "\n";
echo "Номинаций: " . count($nominations) . "\n";

// Фильтрация по году
$recentAwards = filterAwardsByYear($awards, 2023);
echo "Наград в 2023 году: " . count($recentAwards) . "\n";

// Поиск по названию
$oscarAwards = searchAwardsByName($awards, 'оскар');
echo "Наград с 'Оскар': " . count($oscarAwards) . "\n";

// Получение статистики
$stats = getAwardsStatistics($awards);
echo "Всего наград: {$stats['total']}\n";
echo "Побед: {$stats['wins']}\n";
echo "Номинаций: {$stats['nominations']}\n";
```

## Создание отчета о наградах

```php
class AwardsReport {
    private array $awards;

    public function __construct(array $awards) {
        $this->awards = $awards;
    }

    public function getWins(): array {
        return filterAwardsByStatus($this->awards, true);
    }

    public function getNominations(): array {
        return filterAwardsByStatus($this->awards, false);
    }

    public function getByYear(int $year): array {
        return filterAwardsByYear($this->awards, $year);
    }

    public function getByAwardName(string $awardName): array {
        return searchAwardsByName($this->awards, $awardName);
    }

    public function createDetailedReport(): string {
        $report = "=== ДЕТАЛЬНЫЙ ОТЧЕТ О НАГРАДАХ ===\n\n";

        $stats = getAwardsStatistics($this->awards);

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего наград: {$stats['total']}\n";
        $report .= "Побед: {$stats['wins']}\n";
        $report .= "Номинаций: {$stats['nominations']}\n";
        $report .= "Процент побед: " . round(($stats['wins'] / $stats['total']) * 100, 1) . "%\n\n";

        // Статистика по годам
        $report .= "📅 ПО ГОДАМ:\n";
        krsort($stats['years']); // Сортировка по убыванию года
        foreach ($stats['years'] as $year => $yearStats) {
            $total = $yearStats['wins'] + $yearStats['nominations'];
            $report .= "{$year}: {$yearStats['wins']} побед, {$yearStats['nominations']} номинаций (всего: {$total})\n";
        }
        $report .= "\n";

        // Статистика по наградам
        $report .= "🏆 ПО НАГРАДАМ:\n";
        foreach ($stats['awards'] as $awardName => $awardStats) {
            $total = $awardStats['wins'] + $awardStats['nominations'];
            $report .= "{$awardName}: {$awardStats['wins']} побед, {$awardStats['nominations']} номинаций (всего: {$total})\n";
        }
        $report .= "\n";

        // Самые частые номинации
        $report .= "🎭 САМЫЕ ЧАСТЫЕ НОМИНАЦИИ:\n";
        arsort($stats['nominations']);
        foreach (array_slice($stats['nominations'], 0, 5, true) as $nomination => $count) {
            $report .= "{$nomination}: {$count} раз\n";
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1000px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".award-item { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }\n";
        $html .= ".award-win { border-left: 5px solid #28a745; background-color: #f8fff9; }\n";
        $html .= ".award-nomination { border-left: 5px solid #ffc107; background-color: #fffdf8; }\n";
        $html .= ".award-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }\n";
        $html .= ".award-nomination-name { color: #666; font-size: 14px; }\n";
        $html .= ".award-year { color: #999; font-size: 12px; }\n";
        $html .= ".award-persons { margin-top: 10px; font-size: 14px; }\n";
        $html .= ".stats { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        // Статистика
        $stats = getAwardsStatistics($this->awards);
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Статистика</h2>\n";
        $html .= "<p><strong>Всего наград:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Побед:</strong> {$stats['wins']}</p>\n";
        $html .= "<p><strong>Номинаций:</strong> {$stats['nominations']}</p>\n";
        $html .= "<p><strong>Процент побед:</strong> " . round(($stats['wins'] / $stats['total']) * 100, 1) . "%</p>\n";
        $html .= "</div>\n";

        // Список наград
        $html .= "<h2>Все награды</h2>\n";
        foreach ($this->awards as $award) {
            $cssClass = $award->win ? 'award-win' : 'award-nomination';
            $status = $award->win ? '🏆 ПОБЕДА' : '🎭 НОМИНАЦИЯ';

            $html .= "<div class='award-item {$cssClass}'>\n";
            $html .= "<div class='award-name'>{$award->name} - {$status}</div>\n";
            $html .= "<div class='award-nomination-name'>{$award->nominationName}</div>\n";
            $html .= "<div class='award-year'>{$award->year}</div>\n";

            if (!empty($award->persons)) {
                $html .= "<div class='award-persons'><strong>Участники:</strong> " . implode(', ', array_column($award->persons, 'name')) . "</div>\n";
            }

            $html .= "</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$awards = $filmService->getAwards(301);
$report = new AwardsReport($awards);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Награды фильма "Матрица"');
file_put_contents('matrix_awards.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в matrix_awards.html\n";
```

## Анализ наград

```php
function analyzeAwards(array $awards): array {
    $analysis = [
        'total' => count($awards),
        'wins' => 0,
        'nominations' => 0,
        'winRate' => 0,
        'years' => [],
        'awards' => [],
        'nominations' => [],
        'persons' => []
    ];

    $personStats = [];

    foreach ($awards as $award) {
        // Подсчет побед и номинаций
        if ($award->win) {
            $analysis['wins']++;
        } else {
            $analysis['nominations']++;
        }

        // Статистика по годам
        if (!isset($analysis['years'][$award->year])) {
            $analysis['years'][$award->year] = 0;
        }
        $analysis['years'][$award->year]++;

        // Статистика по наградам
        if (!isset($analysis['awards'][$award->name])) {
            $analysis['awards'][$award->name] = 0;
        }
        $analysis['awards'][$award->name]++;

        // Статистика по номинациям
        if (!isset($analysis['nominations'][$award->nominationName])) {
            $analysis['nominations'][$award->nominationName] = 0;
        }
        $analysis['nominations'][$award->nominationName]++;

        // Статистика по персонам
        foreach ($award->persons as $person) {
            $personName = $person['name'] ?? 'Неизвестно';
            if (!isset($personStats[$personName])) {
                $personStats[$personName] = ['wins' => 0, 'nominations' => 0];
            }
            if ($award->win) {
                $personStats[$personName]['wins']++;
            } else {
                $personStats[$personName]['nominations']++;
            }
        }
    }

    // Процент побед
    if ($analysis['total'] > 0) {
        $analysis['winRate'] = round(($analysis['wins'] / $analysis['total']) * 100, 1);
    }

    // Сортировка статистики
    arsort($analysis['years']);
    arsort($analysis['awards']);
    arsort($analysis['nominations']);

    // Статистика по персонам
    foreach ($personStats as $person => $stats) {
        $analysis['persons'][$person] = $stats;
    }

    return $analysis;
}

// Использование
$awards = $filmService->getAwards(301);
$analysis = analyzeAwards($awards);

echo "=== Анализ наград ===\n";
echo "Всего наград: {$analysis['total']}\n";
echo "Побед: {$analysis['wins']}\n";
echo "Номинаций: {$analysis['nominations']}\n";
echo "Процент побед: {$analysis['winRate']}%\n";

echo "\nТоп наград:\n";
foreach (array_slice($analysis['awards'], 0, 5, true) as $award => $count) {
    echo "- {$award}: {$count} наград\n";
}

echo "\nТоп номинаций:\n";
foreach (array_slice($analysis['nominations'], 0, 5, true) as $nomination => $count) {
    echo "- {$nomination}: {$count} раз\n";
}

echo "\nТоп персон:\n";
foreach (array_slice($analysis['persons'], 0, 5, true) as $person => $stats) {
    $total = $stats['wins'] + $stats['nominations'];
    echo "- {$person}: {$stats['wins']} побед, {$stats['nominations']} номинаций (всего: {$total})\n";
}
```

## Связанные классы

- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](film.md) - Модель фильма
