# Sex

Пол из Kinopoisk API.

## Описание

`Sex` представляет пол человека в API Кинопоиска.

## Значения enum

### Типы пола

- `MALE` - Мужской пол
- `FEMALE` - Женский пол
- `UNKNOWN` - Неизвестный пол

## Методы

### getDisplayName()

Получает отображаемое название пола.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое название пола на русском языке

#### Пример использования

```php
echo Sex::MALE->getDisplayName(); // "Мужской"
echo Sex::FEMALE->getDisplayName(); // "Женский"
echo Sex::UNKNOWN->getDisplayName(); // "Неизвестно"
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\Sex;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о персоне
$personService = $client->persons;
$person = $personService->getPerson(1); // ID персоны

echo "=== Работа с полом ===\n";

// Вывод информации о поле
if ($person->sex) {
    echo "Пол: {$person->sex->getDisplayName()}\n";
    echo "Значение: {$person->sex->value}\n";
} else {
    echo "Пол не указан\n";
}

// Вывод всех возможных значений
echo "\nДоступные значения пола:\n";
foreach ([Sex::MALE, Sex::FEMALE, Sex::UNKNOWN] as $sex) {
    $icon = getSexIcon($sex);
    $displayName = $sex->getDisplayName();
    echo "{$icon} {$displayName} ({$sex->value})\n";
}
```

## Работа с полом

```php
// Функция для получения иконки пола
function getSexIcon(Sex $sex): string {
    return match ($sex) {
        Sex::MALE => '👨',
        Sex::FEMALE => '👩',
        Sex::UNKNOWN => '❓'
    };
}

// Функция для получения цвета пола
function getSexColor(Sex $sex): string {
    return match ($sex) {
        Sex::MALE => '#007bff',   // Синий
        Sex::FEMALE => '#e83e8c', // Розовый
        Sex::UNKNOWN => '#6c757d' // Серый
    };
}

// Функция для проверки, является ли пол известным
function isKnownSex(Sex $sex): bool {
    return $sex !== Sex::UNKNOWN;
}

// Функция для получения статистики по полу
function getSexStats(array $persons): array {
    $stats = [
        'male' => 0,
        'female' => 0,
        'unknown' => 0,
        'total' => count($persons)
    ];

    foreach ($persons as $person) {
        if ($person->sex) {
            switch ($person->sex) {
                case Sex::MALE:
                    $stats['male']++;
                    break;
                case Sex::FEMALE:
                    $stats['female']++;
                    break;
                case Sex::UNKNOWN:
                    $stats['unknown']++;
                    break;
            }
        } else {
            $stats['unknown']++;
        }
    }

    return $stats;
}

// Функция для фильтрации персон по полу
function filterPersonsBySex(array $persons, Sex $sex): array {
    return array_filter($persons, fn($person) => $person->sex === $sex);
}

// Использование
$allSexes = [Sex::MALE, Sex::FEMALE, Sex::UNKNOWN];

echo "Работа с полом:\n";
foreach ($allSexes as $sex) {
    $icon = getSexIcon($sex);
    $displayName = $sex->getDisplayName();
    $color = getSexColor($sex);
    $isKnown = isKnownSex($sex) ? 'Да' : 'Нет';

    echo "{$icon} {$displayName} (Цвет: {$color}, Известный: {$isKnown})\n";
}
```

## Создание отчета по полу

```php
class SexReport {
    private array $persons;

    public function __construct(array $persons) {
        $this->persons = $persons;
    }

    public function getPersons(): array {
        return $this->persons;
    }

    public function getPersonsBySex(Sex $sex): array {
        return filterPersonsBySex($this->persons, $sex);
    }

    public function getSexStats(): array {
        return getSexStats($this->persons);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ПОЛУ ===\n\n";

        $stats = $this->getSexStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего персон: {$stats['total']}\n";
        $report .= "Мужчин: {$stats['male']}\n";
        $report .= "Женщин: {$stats['female']}\n";
        $report .= "Неизвестно: {$stats['unknown']}\n\n";

        // Процентное распределение
        if ($stats['total'] > 0) {
            $malePercent = round(($stats['male'] / $stats['total']) * 100, 1);
            $femalePercent = round(($stats['female'] / $stats['total']) * 100, 1);
            $unknownPercent = round(($stats['unknown'] / $stats['total']) * 100, 1);

            $report .= "📈 ПРОЦЕНТНОЕ РАСПРЕДЕЛЕНИЕ:\n";
            $report .= "• Мужчины: {$malePercent}%\n";
            $report .= "• Женщины: {$femalePercent}%\n";
            $report .= "• Неизвестно: {$unknownPercent}%\n\n";
        }

        // Детали по каждому полу
        $malePersons = $this->getPersonsBySex(Sex::MALE);
        $femalePersons = $this->getPersonsBySex(Sex::FEMALE);
        $unknownPersons = $this->getPersonsBySex(Sex::UNKNOWN);

        if (!empty($malePersons)) {
            $report .= "👨 МУЖЧИНЫ (" . count($malePersons) . " персон):\n";
            foreach (array_slice($malePersons, 0, 10) as $person) {
                $report .= "• {$person->getDisplayName()}\n";
            }
            if (count($malePersons) > 10) {
                $report .= "... и еще " . (count($malePersons) - 10) . " человек\n";
            }
            $report .= "\n";
        }

        if (!empty($femalePersons)) {
            $report .= "👩 ЖЕНЩИНЫ (" . count($femalePersons) . " персон):\n";
            foreach (array_slice($femalePersons, 0, 10) as $person) {
                $report .= "• {$person->getDisplayName()}\n";
            }
            if (count($femalePersons) > 10) {
                $report .= "... и еще " . (count($femalePersons) - 10) . " человек\n";
            }
            $report .= "\n";
        }

        if (!empty($unknownPersons)) {
            $report .= "❓ НЕИЗВЕСТНЫЙ ПОЛ (" . count($unknownPersons) . " персон):\n";
            foreach (array_slice($unknownPersons, 0, 10) as $person) {
                $report .= "• {$person->getDisplayName()}\n";
            }
            if (count($unknownPersons) > 10) {
                $report .= "... и еще " . (count($unknownPersons) - 10) . " человек\n";
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
        $html .= ".person-item { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".person-name { font-weight: bold; font-size: 16px; margin-bottom: 5px; }\n";
        $html .= ".person-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".person-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px; }\n";
        $html .= ".male { border-left: 5px solid #007bff; }\n";
        $html .= ".female { border-left: 5px solid #e83e8c; }\n";
        $html .= ".unknown { border-left: 5px solid #6c757d; }\n";
        $html .= ".progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; margin: 5px 0; }\n";
        $html .= ".progress-fill { height: 20px; transition: width 0.3s ease; }\n";
        $html .= ".male-fill { background-color: #007bff; }\n";
        $html .= ".female-fill { background-color: #e83e8c; }\n";
        $html .= ".unknown-fill { background-color: #6c757d; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getSexStats();
        $malePersons = $this->getPersonsBySex(Sex::MALE);
        $femalePersons = $this->getPersonsBySex(Sex::FEMALE);
        $unknownPersons = $this->getPersonsBySex(Sex::UNKNOWN);

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего персон:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>Мужчин:</strong> {$stats['male']}</p>\n";
        $html .= "<p><strong>Женщин:</strong> {$stats['female']}</p>\n";
        $html .= "<p><strong>Неизвестно:</strong> {$stats['unknown']}</p>\n";

        // Прогресс-бары
        if ($stats['total'] > 0) {
            $malePercent = round(($stats['male'] / $stats['total']) * 100, 1);
            $femalePercent = round(($stats['female'] / $stats['total']) * 100, 1);
            $unknownPercent = round(($stats['unknown'] / $stats['total']) * 100, 1);

            $html .= "<h3>Распределение по полу</h3>\n";
            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill male-fill' style='width: {$malePercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Мужчины: {$malePercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill female-fill' style='width: {$femalePercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Женщины: {$femalePercent}%</p>\n";

            $html .= "<div class='progress-bar'>\n";
            $html .= "<div class='progress-fill unknown-fill' style='width: {$unknownPercent}%'></div>\n";
            $html .= "</div>\n";
            $html .= "<p>Неизвестно: {$unknownPercent}%</p>\n";
        }

        $html .= "</div>\n";

        // Мужчины
        if (!empty($malePersons)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>👨 Мужчины (" . count($malePersons) . " персон)</div>\n";
            $html .= "<div class='person-grid'>\n";

            foreach (array_slice($malePersons, 0, 12) as $person) {
                $html .= "<div class='person-item male'>\n";
                $html .= "<div class='person-name'>{$person->getDisplayName()}</div>\n";
                if ($person->profession) {
                    $html .= "<div class='person-meta'>Профессия: {$person->profession}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Женщины
        if (!empty($femalePersons)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>👩 Женщины (" . count($femalePersons) . " персон)</div>\n";
            $html .= "<div class='person-grid'>\n";

            foreach (array_slice($femalePersons, 0, 12) as $person) {
                $html .= "<div class='person-item female'>\n";
                $html .= "<div class='person-name'>{$person->getDisplayName()}</div>\n";
                if ($person->profession) {
                    $html .= "<div class='person-meta'>Профессия: {$person->profession}</div>\n";
                }
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Неизвестный пол
        if (!empty($unknownPersons)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>❓ Неизвестный пол (" . count($unknownPersons) . " персон)</div>\n";
            $html .= "<div class='person-grid'>\n";

            foreach (array_slice($unknownPersons, 0, 12) as $person) {
                $html .= "<div class='person-item unknown'>\n";
                $html .= "<div class='person-name'>{$person->getDisplayName()}</div>\n";
                if ($person->profession) {
                    $html .= "<div class='person-meta'>Профессия: {$person->profession}</div>\n";
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
$persons = $personService->getPersonsByFilm(301); // ID фильма
$report = new SexReport($persons);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по полу');
file_put_contents('sex_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в sex_report.html\n";
```

## Анализ пола

```php
function analyzeSex(array $persons): array {
    $analysis = [
        'totalPersons' => count($persons),
        'sexDistribution' => [
            'male' => 0,
            'female' => 0,
            'unknown' => 0
        ],
        'sexPercentages' => [
            'male' => 0,
            'female' => 0,
            'unknown' => 0
        ],
        'professionBySex' => [
            'male' => [],
            'female' => [],
            'unknown' => []
        ]
    ];

    foreach ($persons as $person) {
        $sex = $person->sex ?? Sex::UNKNOWN;

        switch ($sex) {
            case Sex::MALE:
                $analysis['sexDistribution']['male']++;
                break;
            case Sex::FEMALE:
                $analysis['sexDistribution']['female']++;
                break;
            case Sex::UNKNOWN:
                $analysis['sexDistribution']['unknown']++;
                break;
        }

        // Анализ профессий по полу
        if ($person->profession) {
            $profession = $person->profession;
            $sexKey = $sex === Sex::MALE ? 'male' : ($sex === Sex::FEMALE ? 'female' : 'unknown');

            if (!isset($analysis['professionBySex'][$sexKey][$profession])) {
                $analysis['professionBySex'][$sexKey][$profession] = 0;
            }
            $analysis['professionBySex'][$sexKey][$profession]++;
        }
    }

    // Вычисление процентов
    if ($analysis['totalPersons'] > 0) {
        $analysis['sexPercentages']['male'] = round(($analysis['sexDistribution']['male'] / $analysis['totalPersons']) * 100, 1);
        $analysis['sexPercentages']['female'] = round(($analysis['sexDistribution']['female'] / $analysis['totalPersons']) * 100, 1);
        $analysis['sexPercentages']['unknown'] = round(($analysis['sexDistribution']['unknown'] / $analysis['totalPersons']) * 100, 1);
    }

    return $analysis;
}

// Использование
$persons = $personService->getPersonsByFilm(301);
$analysis = analyzeSex($persons);

echo "=== Анализ пола ===\n";
echo "Всего персон: {$analysis['totalPersons']}\n";

echo "\nРаспределение по полу:\n";
echo "- Мужчины: {$analysis['sexDistribution']['male']} ({$analysis['sexPercentages']['male']}%)\n";
echo "- Женщины: {$analysis['sexDistribution']['female']} ({$analysis['sexPercentages']['female']}%)\n";
echo "- Неизвестно: {$analysis['sexDistribution']['unknown']} ({$analysis['sexPercentages']['unknown']}%)\n";

echo "\nТоп профессий мужчин:\n";
arsort($analysis['professionBySex']['male']);
foreach (array_slice($analysis['professionBySex']['male'], 0, 5) as $profession => $count) {
    echo "- {$profession}: {$count} человек\n";
}

echo "\nТоп профессий женщин:\n";
arsort($analysis['professionBySex']['female']);
foreach (array_slice($analysis['professionBySex']['female'], 0, 5) as $profession => $count) {
    echo "- {$profession}: {$count} человек\n";
}
```

## Связанные классы

- [`Person`](../models/person.md) - Модель персоны
- [`PersonService`](../services/person-service.md) - Сервис для работы с персонами
