# FilmOrder

Порядок сортировки фильмов в Kinopoisk API.

## Описание

`FilmOrder` определяет различные способы сортировки фильмов при получении списков и коллекций.

## Значения enum

### Типы сортировки

- `RATING` - Сортировка по рейтингу
- `NUM_VOTE` - Сортировка по количеству голосов
- `YEAR` - Сортировка по году выпуска

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\FilmOrder;

// Создание клиента
$client = new Client('your-api-key');

// Получение фильмов с различной сортировкой
$filmService = $client->films;

echo "=== Работа с сортировкой фильмов ===\n";

// Получение топ фильмов по рейтингу
$topByRating = $filmService->getTopFilms(order: FilmOrder::RATING);
echo "Топ фильмов по рейтингу: " . count($topByRating) . " элементов\n";

// Получение фильмов по количеству голосов
$topByVotes = $filmService->getTopFilms(order: FilmOrder::NUM_VOTE);
echo "Топ фильмов по голосам: " . count($topByVotes) . " элементов\n";

// Получение фильмов по году
$topByYear = $filmService->getTopFilms(order: FilmOrder::YEAR);
echo "Топ фильмов по году: " . count($topByYear) . " элементов\n";

// Вывод первых 5 фильмов по рейтингу
echo "\n🏆 ТОП-5 ПО РЕЙТИНГУ:\n";
foreach (array_slice($topByRating, 0, 5) as $index => $film) {
    echo ($index + 1) . ". {$film->getDisplayName()}\n";
    if ($film->rating) {
        echo "   Рейтинг: {$film->rating}\n";
    }
    if ($film->year) {
        echo "   Год: {$film->year}\n";
    }
}
```

## Работа с порядком сортировки

```php
// Функция для получения всех типов сортировки
function getAllFilmOrders(): array {
    return [
        FilmOrder::RATING,
        FilmOrder::NUM_VOTE,
        FilmOrder::YEAR
    ];
}

// Функция для получения отображаемого названия сортировки
function getOrderDisplayName(FilmOrder $order): string {
    return match ($order) {
        FilmOrder::RATING => 'По рейтингу',
        FilmOrder::NUM_VOTE => 'По количеству голосов',
        FilmOrder::YEAR => 'По году выпуска'
    };
}

// Функция для получения описания сортировки
function getOrderDescription(FilmOrder $order): string {
    return match ($order) {
        FilmOrder::RATING => 'Фильмы отсортированы по убыванию рейтинга',
        FilmOrder::NUM_VOTE => 'Фильмы отсортированы по количеству голосов',
        FilmOrder::YEAR => 'Фильмы отсортированы по году выпуска (новые первыми)'
    };
}

// Функция для получения иконки сортировки
function getOrderIcon(FilmOrder $order): string {
    return match ($order) {
        FilmOrder::RATING => '🏆',
        FilmOrder::NUM_VOTE => '👥',
        FilmOrder::YEAR => '📅'
    };
}

// Использование
$allOrders = getAllFilmOrders();

echo "Доступные типы сортировки:\n";
foreach ($allOrders as $order) {
    $icon = getOrderIcon($order);
    $name = getOrderDisplayName($order);
    $description = getOrderDescription($order);
    echo "{$icon} {$name}: {$description}\n";
}
```

## Создание отчета по сортировке

```php
class FilmOrderReport {
    private array $filmsByRating;
    private array $filmsByVotes;
    private array $filmsByYear;

    public function __construct(array $filmsByRating, array $filmsByVotes, array $filmsByYear) {
        $this->filmsByRating = $filmsByRating;
        $this->filmsByVotes = $filmsByVotes;
        $this->filmsByYear = $filmsByYear;
    }

    public function getFilmsByRating(): array {
        return $this->filmsByRating;
    }

    public function getFilmsByVotes(): array {
        return $this->filmsByVotes;
    }

    public function getFilmsByYear(): array {
        return $this->filmsByYear;
    }

    public function getTopByRating(int $limit = 10): array {
        return array_slice($this->filmsByRating, 0, $limit);
    }

    public function getTopByVotes(int $limit = 10): array {
        return array_slice($this->filmsByVotes, 0, $limit);
    }

    public function getTopByYear(int $limit = 10): array {
        return array_slice($this->filmsByYear, 0, $limit);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО СОРТИРОВКЕ ФИЛЬМОВ ===\n\n";

        // Статистика
        $report .= "📊 СТАТИСТИКА:\n";
        $report .= "Фильмов по рейтингу: " . count($this->filmsByRating) . "\n";
        $report .= "Фильмов по голосам: " . count($this->filmsByVotes) . "\n";
        $report .= "Фильмов по году: " . count($this->filmsByYear) . "\n\n";

        // Топ по рейтингу
        $report .= "🏆 ТОП-10 ПО РЕЙТИНГУ:\n";
        foreach ($this->getTopByRating(10) as $index => $film) {
            $report .= ($index + 1) . ". {$film->getDisplayName()}";
            if ($film->rating) {
                $report .= " (★ {$film->rating})";
            }
            if ($film->year) {
                $report .= " ({$film->year})";
            }
            $report .= "\n";
        }

        $report .= "\n";

        // Топ по голосам
        $report .= "👥 ТОП-10 ПО КОЛИЧЕСТВУ ГОЛОСОВ:\n";
        foreach ($this->getTopByVotes(10) as $index => $film) {
            $report .= ($index + 1) . ". {$film->getDisplayName()}";
            if ($film->rating) {
                $report .= " (★ {$film->rating})";
            }
            if ($film->year) {
                $report .= " ({$film->year})";
            }
            $report .= "\n";
        }

        $report .= "\n";

        // Топ по году
        $report .= "📅 ТОП-10 ПО ГОДУ ВЫПУСКА:\n";
        foreach ($this->getTopByYear(10) as $index => $film) {
            $report .= ($index + 1) . ". {$film->getDisplayName()}";
            if ($film->year) {
                $report .= " ({$film->year})";
            }
            if ($film->rating) {
                $report .= " (★ {$film->rating})";
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
        $html .= ".film-item { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".film-title { font-weight: bold; font-size: 16px; margin-bottom: 5px; }\n";
        $html .= ".film-meta { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".rating { border-left: 5px solid #28a745; }\n";
        $html .= ".votes { border-left: 5px solid #007bff; }\n";
        $html .= ".year { border-left: 5px solid #ffc107; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Статистика</h2>\n";
        $html .= "<p><strong>Фильмов по рейтингу:</strong> " . count($this->filmsByRating) . "</p>\n";
        $html .= "<p><strong>Фильмов по голосам:</strong> " . count($this->filmsByVotes) . "</p>\n";
        $html .= "<p><strong>Фильмов по году:</strong> " . count($this->filmsByYear) . "</p>\n";
        $html .= "</div>\n";

        // Топ по рейтингу
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>🏆 Топ-10 по рейтингу</div>\n";

        foreach ($this->getTopByRating(10) as $index => $film) {
            $html .= "<div class='film-item rating'>\n";
            $html .= "<div class='film-title'>" . ($index + 1) . ". {$film->getDisplayName()}</div>\n";
            $html .= "<div class='film-meta'>\n";
            if ($film->rating) {
                $html .= "Рейтинг: ★ {$film->rating} ";
            }
            if ($film->year) {
                $html .= "Год: {$film->year}";
            }
            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n";

        // Топ по голосам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>👥 Топ-10 по количеству голосов</div>\n";

        foreach ($this->getTopByVotes(10) as $index => $film) {
            $html .= "<div class='film-item votes'>\n";
            $html .= "<div class='film-title'>" . ($index + 1) . ". {$film->getDisplayName()}</div>\n";
            $html .= "<div class='film-meta'>\n";
            if ($film->rating) {
                $html .= "Рейтинг: ★ {$film->rating} ";
            }
            if ($film->year) {
                $html .= "Год: {$film->year}";
            }
            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n";

        // Топ по году
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📅 Топ-10 по году выпуска</div>\n";

        foreach ($this->getTopByYear(10) as $index => $film) {
            $html .= "<div class='film-item year'>\n";
            $html .= "<div class='film-title'>" . ($index + 1) . ". {$film->getDisplayName()}</div>\n";
            $html .= "<div class='film-meta'>\n";
            if ($film->year) {
                $html .= "Год: {$film->year} ";
            }
            if ($film->rating) {
                $html .= "Рейтинг: ★ {$film->rating}";
            }
            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$filmsByRating = $filmService->getTopFilms(order: FilmOrder::RATING);
$filmsByVotes = $filmService->getTopFilms(order: FilmOrder::NUM_VOTE);
$filmsByYear = $filmService->getTopFilms(order: FilmOrder::YEAR);

$report = new FilmOrderReport($filmsByRating, $filmsByVotes, $filmsByYear);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по сортировке фильмов');
file_put_contents('film_order_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в film_order_report.html\n";
```

## Анализ сортировки

```php
function analyzeFilmOrders(array $filmsByRating, array $filmsByVotes, array $filmsByYear): array {
    $analysis = [
        'totalFilms' => [
            'rating' => count($filmsByRating),
            'votes' => count($filmsByVotes),
            'year' => count($filmsByYear)
        ],
        'averageRating' => 0,
        'averageYear' => 0,
        'ratingDistribution' => [
            'excellent' => 0, // 9+
            'good' => 0,      // 7-8.9
            'average' => 0,   // 5-6.9
            'poor' => 0       // <5
        ],
        'yearDistribution' => [
            'recent' => 0,    // 2020+
            'modern' => 0,    // 2010-2019
            'classic' => 0,   // 2000-2009
            'old' => 0        // <2000
        ]
    ];

    $totalRating = 0;
    $ratedCount = 0;
    $totalYear = 0;
    $yearCount = 0;

    // Анализ фильмов по рейтингу
    foreach ($filmsByRating as $film) {
        if ($film->rating) {
            $rating = (float) $film->rating;
            $totalRating += $rating;
            $ratedCount++;

            if ($rating >= 9) {
                $analysis['ratingDistribution']['excellent']++;
            } elseif ($rating >= 7) {
                $analysis['ratingDistribution']['good']++;
            } elseif ($rating >= 5) {
                $analysis['ratingDistribution']['average']++;
            } else {
                $analysis['ratingDistribution']['poor']++;
            }
        }

        if ($film->year) {
            $year = (int) $film->year;
            $totalYear += $year;
            $yearCount++;

            if ($year >= 2020) {
                $analysis['yearDistribution']['recent']++;
            } elseif ($year >= 2010) {
                $analysis['yearDistribution']['modern']++;
            } elseif ($year >= 2000) {
                $analysis['yearDistribution']['classic']++;
            } else {
                $analysis['yearDistribution']['old']++;
            }
        }
    }

    // Средние значения
    if ($ratedCount > 0) {
        $analysis['averageRating'] = round($totalRating / $ratedCount, 2);
    }

    if ($yearCount > 0) {
        $analysis['averageYear'] = round($totalYear / $yearCount);
    }

    return $analysis;
}

// Использование
$filmsByRating = $filmService->getTopFilms(order: FilmOrder::RATING);
$filmsByVotes = $filmService->getTopFilms(order: FilmOrder::NUM_VOTE);
$filmsByYear = $filmService->getTopFilms(order: FilmOrder::YEAR);

$analysis = analyzeFilmOrders($filmsByRating, $filmsByVotes, $filmsByYear);

echo "=== Анализ сортировки фильмов ===\n";
echo "Фильмов по рейтингу: {$analysis['totalFilms']['rating']}\n";
echo "Фильмов по голосам: {$analysis['totalFilms']['votes']}\n";
echo "Фильмов по году: {$analysis['totalFilms']['year']}\n";
echo "Средний рейтинг: {$analysis['averageRating']}\n";
echo "Средний год: {$analysis['averageYear']}\n";

echo "\nРаспределение по рейтингам:\n";
echo "- Отличные (9+): {$analysis['ratingDistribution']['excellent']}\n";
echo "- Хорошие (7-8.9): {$analysis['ratingDistribution']['good']}\n";
echo "- Средние (5-6.9): {$analysis['ratingDistribution']['average']}\n";
echo "- Плохие (<5): {$analysis['ratingDistribution']['poor']}\n";

echo "\nРаспределение по годам:\n";
echo "- Современные (2020+): {$analysis['yearDistribution']['recent']}\n";
echo "- Новые (2010-2019): {$analysis['yearDistribution']['modern']}\n";
echo "- Классические (2000-2009): {$analysis['yearDistribution']['classic']}\n";
echo "- Старые (<2000): {$analysis['yearDistribution']['old']}\n";
```

## Связанные классы

- [`FilmCollection`](../models/film-collection.md) - Модель элемента коллекции
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
