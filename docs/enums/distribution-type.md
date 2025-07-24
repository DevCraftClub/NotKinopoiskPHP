# DistributionType

Типы проката в Kinopoisk API.

## Описание

`DistributionType` определяет различные типы проката фильмов: кинотеатральный прокат, DVD, Blu-ray и другие форматы.

## Значения enum

### Типы проката

- `ALL` - Все типы проката
- `CINEMA` - Кинотеатральный прокат
- `DVD` - DVD прокат
- `BLURAY` - Blu-ray прокат
- `DIGITAL` - Цифровой прокат
- `TV` - Телевизионный прокат
- `STREAMING` - Стриминговый прокат
- `COUNTRY_SPECIFIC` - Страновой прокат
- `PREMIERE` - Премьерный прокат
- `WORLD_PREMIER` - Мировая премьера

## Методы

### isCinema()

Проверяет, является ли прокат кинотеатральным.

```php
public function isCinema(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это кинотеатральный прокат, `false` в противном случае

#### Пример использования

```php
if (DistributionType::CINEMA->isCinema()) {
    echo "Кинотеатральный прокат";
}
```

### isHomeVideo()

Проверяет, является ли прокат домашним видео.

```php
public function isHomeVideo(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это домашнее видео, `false` в противном случае

#### Описание

Возвращает `true` для типов `DVD` и `BLURAY`.

#### Пример использования

```php
if (DistributionType::DVD->isHomeVideo()) {
    echo "Домашнее видео";
}
```

### isDigital()

Проверяет, является ли прокат цифровым.

```php
public function isDigital(): bool
```

#### Возвращаемое значение

- `bool` - `true` если это цифровой прокат, `false` в противном случае

#### Описание

Возвращает `true` для типов `DIGITAL` и `STREAMING`.

#### Пример использования

```php
if (DistributionType::DIGITAL->isDigital()) {
    echo "Цифровой прокат";
}
```

### getDisplayName()

Получает человекочитаемое название типа проката.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа проката на русском языке

#### Пример использования

```php
echo DistributionType::CINEMA->getDisplayName(); // "Кинотеатральный прокат"
echo DistributionType::DVD->getDisplayName(); // "DVD прокат"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\DistributionType;

// Создание клиента
$client = new Client('your-api-key');

// Получение данных о прокате фильма
$filmService = $client->films;
$distributions = $filmService->getDistributions(301); // Матрица

echo "=== Анализ типов проката фильма 'Матрица' ===\n";

// Группировка по типам проката
$cinemaDistributions = [];
$homeVideoDistributions = [];
$digitalDistributions = [];
$otherDistributions = [];

foreach ($distributions as $distribution) {
    if ($distribution->type->isCinema()) {
        $cinemaDistributions[] = $distribution;
    } elseif ($distribution->type->isHomeVideo()) {
        $homeVideoDistributions[] = $distribution;
    } elseif ($distribution->type->isDigital()) {
        $digitalDistributions[] = $distribution;
    } else {
        $otherDistributions[] = $distribution;
    }
}

// Вывод статистики
echo "\n🎬 Кинотеатральный прокат: " . count($cinemaDistributions) . " записей\n";
echo "📀 Домашнее видео: " . count($homeVideoDistributions) . " записей\n";
echo "💻 Цифровой прокат: " . count($digitalDistributions) . " записей\n";
echo "📺 Прочие типы: " . count($otherDistributions) . " записей\n";

// Детали по типам
echo "\n📋 ДЕТАЛИ ПО ТИПАМ:\n";
foreach ($distributions as $distribution) {
    echo "• {$distribution->type->getDisplayName()}\n";
    if ($distribution->country) {
        echo "  Страна: {$distribution->country->country}\n";
    }
    if ($distribution->date) {
        echo "  Дата: {$distribution->date}\n";
    }
}
```

## Работа с типами проката

```php
// Функция для получения всех типов проката
function getAllDistributionTypes(): array {
    return [
        DistributionType::ALL,
        DistributionType::CINEMA,
        DistributionType::DVD,
        DistributionType::BLURAY,
        DistributionType::DIGITAL,
        DistributionType::TV,
        DistributionType::STREAMING,
        DistributionType::COUNTRY_SPECIFIC,
        DistributionType::PREMIERE,
        DistributionType::WORLD_PREMIER
    ];
}

// Функция для получения типов домашнего видео
function getHomeVideoTypes(): array {
    return array_filter(getAllDistributionTypes(), fn($type) => $type->isHomeVideo());
}

// Функция для получения цифровых типов
function getDigitalTypes(): array {
    return array_filter(getAllDistributionTypes(), fn($type) => $type->isDigital());
}

// Функция для получения кинотеатральных типов
function getCinemaTypes(): array {
    return array_filter(getAllDistributionTypes(), fn($type) => $type->isCinema());
}

// Функция для группировки прокатов по категориям
function groupDistributionsByCategory(array $distributions): array {
    $categories = [
        'cinema' => [],
        'homeVideo' => [],
        'digital' => [],
        'other' => []
    ];

    foreach ($distributions as $distribution) {
        if ($distribution->type->isCinema()) {
            $categories['cinema'][] = $distribution;
        } elseif ($distribution->type->isHomeVideo()) {
            $categories['homeVideo'][] = $distribution;
        } elseif ($distribution->type->isDigital()) {
            $categories['digital'][] = $distribution;
        } else {
            $categories['other'][] = $distribution;
        }
    }

    return $categories;
}

// Функция для получения статистики по типам
function getDistributionTypeStats(array $distributions): array {
    $stats = [];

    foreach (getAllDistributionTypes() as $type) {
        $stats[$type->value] = [
            'type' => $type,
            'displayName' => $type->getDisplayName(),
            'count' => 0,
            'isCinema' => $type->isCinema(),
            'isHomeVideo' => $type->isHomeVideo(),
            'isDigital' => $type->isDigital()
        ];
    }

    foreach ($distributions as $distribution) {
        $typeKey = $distribution->type->value;
        $stats[$typeKey]['count']++;
    }

    return $stats;
}

// Использование
$distributions = $filmService->getDistributions(301);

// Получение типов
$allTypes = getAllDistributionTypes();
$homeVideoTypes = getHomeVideoTypes();
$digitalTypes = getDigitalTypes();
$cinemaTypes = getCinemaTypes();

echo "Всего типов проката: " . count($allTypes) . "\n";
echo "Типов домашнего видео: " . count($homeVideoTypes) . "\n";
echo "Цифровых типов: " . count($digitalTypes) . "\n";
echo "Кинотеатральных типов: " . count($cinemaTypes) . "\n";

// Группировка по категориям
$categories = groupDistributionsByCategory($distributions);
echo "Кинотеатральных прокатов: " . count($categories['cinema']) . "\n";
echo "Домашнего видео: " . count($categories['homeVideo']) . "\n";
echo "Цифровых прокатов: " . count($categories['digital']) . "\n";
echo "Прочих типов: " . count($categories['other']) . "\n";

// Статистика по типам
$stats = getDistributionTypeStats($distributions);
echo "\nСтатистика по типам:\n";
foreach ($stats as $typeKey => $data) {
    if ($data['count'] > 0) {
        echo "- {$data['displayName']}: {$data['count']} записей\n";
    }
}
```

## Создание отчета по типам проката

```php
class DistributionTypeReport {
    private array $distributions;

    public function __construct(array $distributions) {
        $this->distributions = $distributions;
    }

    public function getDistributionsByType(DistributionType $type): array {
        return array_filter($this->distributions, fn($dist) => $dist->type === $type);
    }

    public function getCategoryBreakdown(): array {
        return groupDistributionsByCategory($this->distributions);
    }

    public function getTypeStats(): array {
        return getDistributionTypeStats($this->distributions);
    }

    public function getCinemaDistributions(): array {
        return array_filter($this->distributions, fn($dist) => $dist->type->isCinema());
    }

    public function getHomeVideoDistributions(): array {
        return array_filter($this->distributions, fn($dist) => $dist->type->isHomeVideo());
    }

    public function getDigitalDistributions(): array {
        return array_filter($this->distributions, fn($dist) => $dist->type->isDigital());
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ТИПАМ ПРОКАТА ===\n\n";

        $categories = $this->getCategoryBreakdown();
        $stats = $this->getTypeStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего записей о прокате: " . count($this->distributions) . "\n";
        $report .= "Кинотеатральных прокатов: " . count($categories['cinema']) . "\n";
        $report .= "Домашнего видео: " . count($categories['homeVideo']) . "\n";
        $report .= "Цифровых прокатов: " . count($categories['digital']) . "\n";
        $report .= "Прочих типов: " . count($categories['other']) . "\n\n";

        // Статистика по категориям
        $report .= "🎬 СТАТИСТИКА ПО КАТЕГОРИЯМ:\n";
        $report .= "• Кинотеатральный прокат: " . count($categories['cinema']) . " записей\n";
        $report .= "• Домашнее видео: " . count($categories['homeVideo']) . " записей\n";
        $report .= "• Цифровой прокат: " . count($categories['digital']) . " записей\n";
        $report .= "• Прочие типы: " . count($categories['other']) . " записей\n\n";

        // Детали по типам
        $report .= "📋 ДЕТАЛИ ПО ТИПАМ:\n";
        foreach ($stats as $typeKey => $data) {
            if ($data['count'] > 0) {
                $report .= "• {$data['displayName']}: {$data['count']} записей\n";

                // Дополнительная информация
                if ($data['isCinema']) {
                    $report .= "  🎬 Кинотеатральный тип\n";
                }
                if ($data['isHomeVideo']) {
                    $report .= "  📀 Домашнее видео\n";
                }
                if ($data['isDigital']) {
                    $report .= "  💻 Цифровой тип\n";
                }
            }
        }

        // Примеры по категориям
        $report .= "\n🎬 ПРИМЕРЫ КИНОТЕАТРАЛЬНЫХ ПРОКАТОВ:\n";
        foreach (array_slice($categories['cinema'], 0, 3) as $distribution) {
            $report .= "• {$distribution->type->getDisplayName()}\n";
            if ($distribution->country) {
                $report .= "  Страна: {$distribution->country->country}\n";
            }
            if ($distribution->date) {
                $report .= "  Дата: {$distribution->date}\n";
            }
        }

        if (count($categories['cinema']) > 3) {
            $report .= "  ... и еще " . (count($categories['cinema']) - 3) . " записей\n";
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
        $html .= ".type-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".type-name { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".type-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; }\n";
        $html .= ".type-detail { font-size: 14px; color: #666; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".cinema { border-left: 5px solid #28a745; }\n";
        $html .= ".home-video { border-left: 5px solid #007bff; }\n";
        $html .= ".digital { border-left: 5px solid #17a2b8; }\n";
        $html .= ".other { border-left: 5px solid #6c757d; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $categories = $this->getCategoryBreakdown();
        $stats = $this->getTypeStats();

        // Общая статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего записей о прокате:</strong> " . count($this->distributions) . "</p>\n";
        $html .= "<p><strong>Кинотеатральных прокатов:</strong> " . count($categories['cinema']) . "</p>\n";
        $html .= "<p><strong>Домашнего видео:</strong> " . count($categories['homeVideo']) . "</p>\n";
        $html .= "<p><strong>Цифровых прокатов:</strong> " . count($categories['digital']) . "</p>\n";
        $html .= "<p><strong>Прочих типов:</strong> " . count($categories['other']) . "</p>\n";
        $html .= "</div>\n";

        // Детали по типам
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>Детали по типам</div>\n";

        foreach ($stats as $typeKey => $data) {
            if ($data['count'] > 0) {
                $cssClass = '';
                if ($data['isCinema']) {
                    $cssClass = 'cinema';
                } elseif ($data['isHomeVideo']) {
                    $cssClass = 'home-video';
                } elseif ($data['isDigital']) {
                    $cssClass = 'digital';
                } else {
                    $cssClass = 'other';
                }

                $html .= "<div class='type-item {$cssClass}'>\n";
                $html .= "<div class='type-name'>{$data['displayName']}</div>\n";
                $html .= "<div class='type-details'>\n";
                $html .= "<div class='type-detail'>Количество записей: {$data['count']}</div>\n";

                if ($data['isCinema']) {
                    $html .= "<div class='type-detail'>🎬 Кинотеатральный тип</div>\n";
                }
                if ($data['isHomeVideo']) {
                    $html .= "<div class='type-detail'>📀 Домашнее видео</div>\n";
                }
                if ($data['isDigital']) {
                    $html .= "<div class='type-detail'>💻 Цифровой тип</div>\n";
                }

                $html .= "</div>\n</div>\n";
            }
        }

        $html .= "</div>\n</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$distributions = $filmService->getDistributions(301);
$report = new DistributionTypeReport($distributions);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по типам проката');
file_put_contents('distribution_types_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в distribution_types_report.html\n";
```

## Анализ эффективности типов

```php
function analyzeDistributionTypeEffectiveness(array $distributions): array {
    $analysis = [
        'totalDistributions' => count($distributions),
        'typeDistribution' => [],
        'categoryDistribution' => [],
        'countryByType' => [],
        'dateByType' => [],
        'mostPopularType' => null,
        'leastPopularType' => null
    ];

    $typeStats = [];
    $categoryStats = [
        'cinema' => 0,
        'homeVideo' => 0,
        'digital' => 0,
        'other' => 0
    ];

    foreach ($distributions as $distribution) {
        $typeKey = $distribution->type->value;

        // Статистика по типам
        if (!isset($typeStats[$typeKey])) {
            $typeStats[$typeKey] = [
                'count' => 0,
                'countries' => [],
                'dates' => []
            ];
        }
        $typeStats[$typeKey]['count']++;

        // Статистика по категориям
        if ($distribution->type->isCinema()) {
            $categoryStats['cinema']++;
        } elseif ($distribution->type->isHomeVideo()) {
            $categoryStats['homeVideo']++;
        } elseif ($distribution->type->isDigital()) {
            $categoryStats['digital']++;
        } else {
            $categoryStats['other']++;
        }

        // Статистика по странам
        if ($distribution->country) {
            $countryName = $distribution->country->country;
            if (!isset($typeStats[$typeKey]['countries'][$countryName])) {
                $typeStats[$typeKey]['countries'][$countryName] = 0;
            }
            $typeStats[$typeKey]['countries'][$countryName]++;
        }

        // Статистика по датам
        if ($distribution->date) {
            $typeStats[$typeKey]['dates'][] = $distribution->date;
        }
    }

    $analysis['typeDistribution'] = $typeStats;
    $analysis['categoryDistribution'] = $categoryStats;

    // Самый популярный и непопулярный тип
    if (!empty($typeStats)) {
        $counts = array_column($typeStats, 'count');
        $maxCount = max($counts);
        $minCount = min($counts);

        foreach ($typeStats as $typeKey => $data) {
            if ($data['count'] === $maxCount) {
                $analysis['mostPopularType'] = DistributionType::from($typeKey);
            }
            if ($data['count'] === $minCount) {
                $analysis['leastPopularType'] = DistributionType::from($typeKey);
            }
        }
    }

    return $analysis;
}

// Использование
$distributions = $filmService->getDistributions(301);
$analysis = analyzeDistributionTypeEffectiveness($distributions);

echo "=== Анализ эффективности типов проката ===\n";
echo "Всего записей о прокате: {$analysis['totalDistributions']}\n";

echo "\nРаспределение по категориям:\n";
foreach ($analysis['categoryDistribution'] as $category => $count) {
    $percentage = round(($count / $analysis['totalDistributions']) * 100, 1);
    echo "- {$category}: {$count} записей ({$percentage}%)\n";
}

echo "\nРаспределение по типам:\n";
foreach ($analysis['typeDistribution'] as $typeKey => $data) {
    $type = DistributionType::from($typeKey);
    $percentage = round(($data['count'] / $analysis['totalDistributions']) * 100, 1);
    echo "- {$type->getDisplayName()}: {$data['count']} записей ({$percentage}%)\n";

    if (!empty($data['countries'])) {
        echo "  Топ стран: " . implode(', ', array_keys(array_slice($data['countries'], 0, 3, true))) . "\n";
    }
}

if ($analysis['mostPopularType']) {
    echo "\nСамый популярный тип: {$analysis['mostPopularType']->getDisplayName()}\n";
}

if ($analysis['leastPopularType']) {
    echo "Самый непопулярный тип: {$analysis['leastPopularType']->getDisplayName()}\n";
}
```

## Связанные классы

- [`Distribution`](../models/distribution.md) - Модель проката
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
