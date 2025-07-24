# PersonSpouse

Модель супруга персоны из Kinopoisk API.

## Описание

`PersonSpouse` представляет информацию о супруге персоны, полученную из Kinopoisk API. Содержит данные о браке, детях, причинах развода и других аспектах семейной жизни персоны.

### Основные возможности

- Хранение информации о супруге в неизменяемом виде
- Создание объекта из массива данных API
- Удобные методы для работы с семейным статусом
- Поддержка информации о детях и браке

## Свойства

### Основная информация

- `$personId` (int) - Уникальный идентификатор супруга в Кинопоиске
- `$name` (string|null) - Имя супруга
- `$divorced` (bool) - Статус развода
- `$divorcedReason` (string|null) - Причина развода (если применимо)
- `$sex` (string) - Пол супруга
- `$children` (int) - Количество детей
- `$webUrl` (string) - URL страницы супруга на Кинопоиске
- `$relation` (string) - Тип отношений (супруга, супруг и т.д.)

## Конструктор

```php
public function __construct(
    public readonly int $personId,
    public readonly ?string $name,
    public readonly bool $divorced,
    public readonly ?string $divorcedReason,
    public readonly string $sex,
    public readonly int $children,
    public readonly string $webUrl,
    public readonly string $relation,
)
```

### Пример создания

```php
$spouse = new PersonSpouse(
    personId: 32169,
    name: 'Сьюзан Дауни',
    divorced: false,
    divorcedReason: null,
    sex: 'FEMALE',
    children: 2,
    webUrl: 'https://www.kinopoisk.ru/name/32169/',
    relation: 'супруга'
);
```

## Методы

### fromArray()

Создает экземпляр супруга из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных супруга от API

#### Возвращаемое значение

- `self` - Новый экземпляр супруга

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'personId' => 32169,
    'name' => 'Сьюзан Дауни',
    'divorced' => false,
    'divorcedReason' => null,
    'sex' => 'FEMALE',
    'children' => 2,
    'webUrl' => 'https://www.kinopoisk.ru/name/32169/',
    'relation' => 'супруга'
];

$spouse = PersonSpouse::fromArray($apiData);
```

### isDivorced()

Проверяет, разведен ли супруг.

```php
public function isDivorced(): bool
```

#### Возвращаемое значение

- `bool` - `true` если разведен, `false` если в браке

#### Пример использования

```php
if ($spouse->isDivorced()) {
    echo "Разведен";
    if ($spouse->divorcedReason) {
        echo "Причина: {$spouse->divorcedReason}";
    }
} else {
    echo "В браке";
}
```

### isMale()

Проверяет, является ли супруг мужчиной.

```php
public function isMale(): bool
```

#### Возвращаемое значение

- `bool` - `true` если мужчина, `false` если женщина

#### Пример использования

```php
if ($spouse->isMale()) {
    echo "Супруг";
} else {
    echo "Супруга";
}
```

### isFemale()

Проверяет, является ли супруг женщиной.

```php
public function isFemale(): bool
```

#### Возвращаемое значение

- `bool` - `true` если женщина, `false` если мужчина

#### Пример использования

```php
if ($spouse->isFemale()) {
    echo "Супруга";
} else {
    echo "Супруг";
}
```

### getDisplayName()

Получает отображаемое имя супруга.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Отображаемое имя супруга

#### Описание

Возвращает имя супруга или "Неизвестно", если имя не указано.

#### Пример использования

```php
echo "Супруг: {$spouse->getDisplayName()}";
```

### getMarriageInfo()

Получает полную информацию о браке.

```php
public function getMarriageInfo(): string
```

#### Возвращаемое значение

- `string` - Полная информация о браке

#### Описание

Возвращает строку с полной информацией о браке, включая статус, причину развода (если применимо) и количество детей.

#### Пример использования

```php
echo $spouse->getMarriageInfo();
// "В браке, 2 ребенка" или "Разведен (причина), 1 ребенок"
```

### isMarried()

Проверяет, в браке ли супруг.

```php
public function isMarried(): bool
```

#### Возвращаемое значение

- `bool` - `true` если в браке, `false` если разведен

#### Пример использования

```php
if ($spouse->isMarried()) {
    echo "В браке";
} else {
    echo "Разведен";
}
```

### getChildrenInfo()

Получает информацию о детях в виде строки.

```php
public function getChildrenInfo(): string
```

#### Возвращаемое значение

- `string` - Информация о детях

#### Описание

Возвращает строку с количеством детей или "Нет детей".

#### Пример использования

```php
echo "Дети: {$spouse->getChildrenInfo()}"; // "2 ребенка" или "Нет детей"
```

### toArray()

Преобразует объект супруга в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными супруга

#### Описание

Возвращает все свойства объекта в виде ассоциативного массива. Полезно для сериализации, логирования или передачи данных.

#### Пример использования

```php
$spouseArray = $spouse->toArray();
echo json_encode($spouseArray); // JSON представление супруга
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о персоне
$personService = $client->persons;
$person = $personService->getPerson(1); // ID персоны

echo "=== Информация о супругах ===\n";

if (!empty($person->spouses)) {
    echo "Количество супругов: " . count($person->spouses) . "\n\n";

    foreach ($person->spouses as $index => $spouse) {
        echo ($index + 1) . ". {$spouse->getDisplayName()}\n";
        echo "   ID: {$spouse->personId}\n";
        echo "   Пол: " . ($spouse->isMale() ? 'Мужской' : 'Женский') . "\n";
        echo "   Статус: " . ($spouse->isMarried() ? 'В браке' : 'Разведен') . "\n";

        if ($spouse->isDivorced() && $spouse->divorcedReason) {
            echo "   Причина развода: {$spouse->divorcedReason}\n";
        }

        echo "   Дети: {$spouse->getChildrenInfo()}\n";
        echo "   Отношения: {$spouse->relation}\n";
        echo "   URL: {$spouse->webUrl}\n";
        echo "\n";
    }
} else {
    echo "Информация о супругах не найдена\n";
}
```

## Работа с супругами

```php
// Функция для фильтрации супругов по статусу брака
function filterSpousesByMaritalStatus(array $spouses, bool $married): array {
    return array_filter($spouses, fn($spouse) => $spouse->isMarried() === $married);
}

// Функция для фильтрации супругов по полу
function filterSpousesBySex(array $spouses, string $sex): array {
    return array_filter($spouses, function($spouse) use ($sex) {
        return $sex === 'MALE' ? $spouse->isMale() : $spouse->isFemale();
    });
}

// Функция для получения супругов с детьми
function getSpousesWithChildren(array $spouses): array {
    return array_filter($spouses, fn($spouse) => $spouse->children > 0);
}

// Функция для получения статистики по супругам
function getSpouseStats(array $spouses): array {
    $stats = [
        'total' => count($spouses),
        'married' => 0,
        'divorced' => 0,
        'male' => 0,
        'female' => 0,
        'withChildren' => 0,
        'totalChildren' => 0
    ];

    foreach ($spouses as $spouse) {
        if ($spouse->isMarried()) {
            $stats['married']++;
        } else {
            $stats['divorced']++;
        }

        if ($spouse->isMale()) {
            $stats['male']++;
        } else {
            $stats['female']++;
        }

        if ($spouse->children > 0) {
            $stats['withChildren']++;
            $stats['totalChildren'] += $spouse->children;
        }
    }

    return $stats;
}

// Функция для получения причин развода
function getDivorceReasons(array $spouses): array {
    $reasons = [];

    foreach ($spouses as $spouse) {
        if ($spouse->isDivorced() && $spouse->divorcedReason) {
            if (!isset($reasons[$spouse->divorcedReason])) {
                $reasons[$spouse->divorcedReason] = 0;
            }
            $reasons[$spouse->divorcedReason]++;
        }
    }

    return $reasons;
}

// Использование
$spouses = $person->spouses ?? [];

// Фильтрация
$marriedSpouses = filterSpousesByMaritalStatus($spouses, true);
$divorcedSpouses = filterSpousesByMaritalStatus($spouses, false);
$maleSpouses = filterSpousesBySex($spouses, 'MALE');
$femaleSpouses = filterSpousesBySex($spouses, 'FEMALE');
$spousesWithChildren = getSpousesWithChildren($spouses);

echo "Статистика по супругам:\n";
echo "В браке: " . count($marriedSpouses) . "\n";
echo "Разведены: " . count($divorcedSpouses) . "\n";
echo "Мужчин: " . count($maleSpouses) . "\n";
echo "Женщин: " . count($femaleSpouses) . "\n";
echo "С детьми: " . count($spousesWithChildren) . "\n";

// Статистика
$stats = getSpouseStats($spouses);
echo "\nПодробная статистика:\n";
echo "Всего супругов: {$stats['total']}\n";
echo "В браке: {$stats['married']}\n";
echo "Разведены: {$stats['divorced']}\n";
echo "Мужчин: {$stats['male']}\n";
echo "Женщин: {$stats['female']}\n";
echo "С детьми: {$stats['withChildren']}\n";
echo "Общее количество детей: {$stats['totalChildren']}\n";

// Причины развода
$divorceReasons = getDivorceReasons($spouses);
if (!empty($divorceReasons)) {
    echo "\nПричины развода:\n";
    foreach ($divorceReasons as $reason => $count) {
        echo "- {$reason}: {$count} раз\n";
    }
}
```

## Создание отчета по супругам

```php
class SpouseReport {
    private array $spouses;

    public function __construct(array $spouses) {
        $this->spouses = $spouses;
    }

    public function getSpouses(): array {
        return $this->spouses;
    }

    public function getMarriedSpouses(): array {
        return filterSpousesByMaritalStatus($this->spouses, true);
    }

    public function getDivorcedSpouses(): array {
        return filterSpousesByMaritalStatus($this->spouses, false);
    }

    public function getMaleSpouses(): array {
        return filterSpousesBySex($this->spouses, 'MALE');
    }

    public function getFemaleSpouses(): array {
        return filterSpousesBySex($this->spouses, 'FEMALE');
    }

    public function getSpousesWithChildren(): array {
        return getSpousesWithChildren($this->spouses);
    }

    public function getSpouseStats(): array {
        return getSpouseStats($this->spouses);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО СУПРУГАМ ===\n\n";

        $stats = $this->getSpouseStats();

        // Общая статистика
        $report .= "📊 ОБЩАЯ СТАТИСТИКА:\n";
        $report .= "Всего супругов: {$stats['total']}\n";
        $report .= "В браке: {$stats['married']}\n";
        $report .= "Разведены: {$stats['divorced']}\n";
        $report .= "Мужчин: {$stats['male']}\n";
        $report .= "Женщин: {$stats['female']}\n";
        $report .= "С детьми: {$stats['withChildren']}\n";
        $report .= "Общее количество детей: {$stats['totalChildren']}\n\n";

        // Супруги в браке
        $marriedSpouses = $this->getMarriedSpouses();
        if (!empty($marriedSpouses)) {
            $report .= "💍 В БРАКЕ (" . count($marriedSpouses) . " человек):\n";
            foreach ($marriedSpouses as $spouse) {
                $report .= "• {$spouse->getDisplayName()}\n";
                $report .= "  Пол: " . ($spouse->isMale() ? 'Мужской' : 'Женский') . "\n";
                $report .= "  Дети: {$spouse->getChildrenInfo()}\n";
                $report .= "  Отношения: {$spouse->relation}\n";
            }
            $report .= "\n";
        }

        // Разведенные супруги
        $divorcedSpouses = $this->getDivorcedSpouses();
        if (!empty($divorcedSpouses)) {
            $report .= "💔 РАЗВЕДЕНЫ (" . count($divorcedSpouses) . " человек):\n";
            foreach ($divorcedSpouses as $spouse) {
                $report .= "• {$spouse->getDisplayName()}\n";
                $report .= "  Пол: " . ($spouse->isMale() ? 'Мужской' : 'Женский') . "\n";
                if ($spouse->divorcedReason) {
                    $report .= "  Причина развода: {$spouse->divorcedReason}\n";
                }
                $report .= "  Дети: {$spouse->getChildrenInfo()}\n";
                $report .= "  Отношения: {$spouse->relation}\n";
            }
            $report .= "\n";
        }

        // Супруги с детьми
        $spousesWithChildren = $this->getSpousesWithChildren();
        if (!empty($spousesWithChildren)) {
            $report .= "👶 СУПРУГИ С ДЕТЬМИ (" . count($spousesWithChildren) . " человек):\n";
            foreach ($spousesWithChildren as $spouse) {
                $report .= "• {$spouse->getDisplayName()}\n";
                $report .= "  Статус: " . ($spouse->isMarried() ? 'В браке' : 'Разведен') . "\n";
                $report .= "  Дети: {$spouse->getChildrenInfo()}\n";
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
        $html .= ".spouse-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".spouse-name { font-weight: bold; font-size: 16px; margin-bottom: 10px; }\n";
        $html .= ".spouse-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".spouse-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".married { border-left: 5px solid #28a745; }\n";
        $html .= ".divorced { border-left: 5px solid #dc3545; }\n";
        $html .= ".male { background-color: #f8f9fa; }\n";
        $html .= ".female { background-color: #fff5f5; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $stats = $this->getSpouseStats();
        $marriedSpouses = $this->getMarriedSpouses();
        $divorcedSpouses = $this->getDivorcedSpouses();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая статистика</h2>\n";
        $html .= "<p><strong>Всего супругов:</strong> {$stats['total']}</p>\n";
        $html .= "<p><strong>В браке:</strong> {$stats['married']}</p>\n";
        $html .= "<p><strong>Разведены:</strong> {$stats['divorced']}</p>\n";
        $html .= "<p><strong>Мужчин:</strong> {$stats['male']}</p>\n";
        $html .= "<p><strong>Женщин:</strong> {$stats['female']}</p>\n";
        $html .= "<p><strong>С детьми:</strong> {$stats['withChildren']}</p>\n";
        $html .= "<p><strong>Общее количество детей:</strong> {$stats['totalChildren']}</p>\n";
        $html .= "</div>\n";

        // Супруги в браке
        if (!empty($marriedSpouses)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>💍 В браке (" . count($marriedSpouses) . " человек)</div>\n";
            $html .= "<div class='spouse-grid'>\n";

            foreach ($marriedSpouses as $spouse) {
                $cssClass = $spouse->isMale() ? 'male' : 'female';
                $html .= "<div class='spouse-item married {$cssClass}'>\n";
                $html .= "<div class='spouse-name'>{$spouse->getDisplayName()}</div>\n";
                $html .= "<div class='spouse-meta'>Пол: " . ($spouse->isMale() ? 'Мужской' : 'Женский') . "</div>\n";
                $html .= "<div class='spouse-meta'>Дети: {$spouse->getChildrenInfo()}</div>\n";
                $html .= "<div class='spouse-meta'>Отношения: {$spouse->relation}</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        // Разведенные супруги
        if (!empty($divorcedSpouses)) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>💔 Разведены (" . count($divorcedSpouses) . " человек)</div>\n";
            $html .= "<div class='spouse-grid'>\n";

            foreach ($divorcedSpouses as $spouse) {
                $cssClass = $spouse->isMale() ? 'male' : 'female';
                $html .= "<div class='spouse-item divorced {$cssClass}'>\n";
                $html .= "<div class='spouse-name'>{$spouse->getDisplayName()}</div>\n";
                $html .= "<div class='spouse-meta'>Пол: " . ($spouse->isMale() ? 'Мужской' : 'Женский') . "</div>\n";
                if ($spouse->divorcedReason) {
                    $html .= "<div class='spouse-meta'>Причина развода: {$spouse->divorcedReason}</div>\n";
                }
                $html .= "<div class='spouse-meta'>Дети: {$spouse->getChildrenInfo()}</div>\n";
                $html .= "<div class='spouse-meta'>Отношения: {$spouse->relation}</div>\n";
                $html .= "</div>\n";
            }

            $html .= "</div>\n</div>\n";
        }

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$spouses = $person->spouses ?? [];
$report = new SpouseReport($spouses);

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по супругам');
file_put_contents('spouse_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в spouse_report.html\n";
```

## Анализ супругов

```php
function analyzeSpouses(array $spouses): array {
    $analysis = [
        'totalSpouses' => count($spouses),
        'maritalStatus' => [
            'married' => 0,
            'divorced' => 0
        ],
        'genderDistribution' => [
            'male' => 0,
            'female' => 0
        ],
        'childrenAnalysis' => [
            'withChildren' => 0,
            'withoutChildren' => 0,
            'totalChildren' => 0,
            'averageChildren' => 0
        ],
        'divorceReasons' => [],
        'marriageDuration' => []
    ];

    foreach ($spouses as $spouse) {
        // Статус брака
        if ($spouse->isMarried()) {
            $analysis['maritalStatus']['married']++;
        } else {
            $analysis['maritalStatus']['divorced']++;
        }

        // Пол
        if ($spouse->isMale()) {
            $analysis['genderDistribution']['male']++;
        } else {
            $analysis['genderDistribution']['female']++;
        }

        // Дети
        if ($spouse->children > 0) {
            $analysis['childrenAnalysis']['withChildren']++;
            $analysis['childrenAnalysis']['totalChildren'] += $spouse->children;
        } else {
            $analysis['childrenAnalysis']['withoutChildren']++;
        }

        // Причины развода
        if ($spouse->isDivorced() && $spouse->divorcedReason) {
            if (!isset($analysis['divorceReasons'][$spouse->divorcedReason])) {
                $analysis['divorceReasons'][$spouse->divorcedReason] = 0;
            }
            $analysis['divorceReasons'][$spouse->divorcedReason]++;
        }
    }

    // Среднее количество детей
    if ($analysis['childrenAnalysis']['withChildren'] > 0) {
        $analysis['childrenAnalysis']['averageChildren'] = round(
            $analysis['childrenAnalysis']['totalChildren'] / $analysis['childrenAnalysis']['withChildren'],
            1
        );
    }

    return $analysis;
}

// Использование
$spouses = $person->spouses ?? [];
$analysis = analyzeSpouses($spouses);

echo "=== Анализ супругов ===\n";
echo "Всего супругов: {$analysis['totalSpouses']}\n";

echo "\nСтатус брака:\n";
echo "- В браке: {$analysis['maritalStatus']['married']}\n";
echo "- Разведены: {$analysis['maritalStatus']['divorced']}\n";

echo "\nРаспределение по полу:\n";
echo "- Мужчин: {$analysis['genderDistribution']['male']}\n";
echo "- Женщин: {$analysis['genderDistribution']['female']}\n";

echo "\nАнализ детей:\n";
echo "- С детьми: {$analysis['childrenAnalysis']['withChildren']}\n";
echo "- Без детей: {$analysis['childrenAnalysis']['withoutChildren']}\n";
echo "- Общее количество детей: {$analysis['childrenAnalysis']['totalChildren']}\n";
echo "- Среднее количество детей: {$analysis['childrenAnalysis']['averageChildren']}\n";

if (!empty($analysis['divorceReasons'])) {
    echo "\nПричины развода:\n";
    arsort($analysis['divorceReasons']);
    foreach ($analysis['divorceReasons'] as $reason => $count) {
        echo "- {$reason}: {$count} раз\n";
    }
}
```

## Связанные классы

- [`Person`](person.md) - Модель персоны
- [`PersonService`](../services/person-service.md) - Сервис для работы с персонами
