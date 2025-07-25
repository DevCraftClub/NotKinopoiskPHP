# SequelPrequelResponse

## Описание

`SequelPrequelResponse` - это специализированный класс ответа для работы с сиквелами, приквелами и связанными фильмами от Kinopoisk API. Наследует функциональность `SimpleResponse` и предоставляет расширенные методы для фильтрации и анализа связанных фильмов по типам отношений.

## Основные возможности

- Фильтрация фильмов по типу отношения (сиквелы, приквелы, римейки, похожие)
- Объединение и сортировка связанных фильмов
- Статистика по типам отношений
- Группировка фильмов по типам связей
- Проверка наличия фильмов определенного типа
- Получение уникальных типов отношений

## Наследование

```php
NotKinopoisk\Responses\SimpleResponse
└── NotKinopoisk\Responses\SequelPrequelResponse
```

## Конструктор

```php
public function __construct(public array $items)
```

### Параметры

- `$items` (array) - Массив связанных фильмов

## Статические методы

### fromArray()

```php
public static function fromArray(array $data, string $cls): self
```

Создает экземпляр `SequelPrequelResponse` из массива данных API.

#### Параметры

- `$data` (array) - Массив данных от API
- `$cls` (string) - Имя класса для элементов коллекции

#### Возвращает

- `SequelPrequelResponse` - Новый экземпляр с данными о связанных фильмах

#### Исключения

- `KpValidationException` - Если данные имеют некорректную структуру

## Методы экземпляра

### getPrequelsAndSequels()

```php
public function getPrequelsAndSequels(): array
```

Получает объединённый и отсортированный список приквелов и сиквелов.

#### Возвращает

- `array<int, RelatedFilm>` - Отсортированный массив приквелов и сиквелов

#### Исключения

- `KpValidationException` - При ошибках во время обработки данных

#### Пример использования

```php
$combined = $response->getPrequelsAndSequels();
foreach ($combined as $film) {
    echo "{$film->getDisplayName()} - {$film->relationType->getDescription()}\n";
}
```

### getSequels()

```php
public function getSequels(): array
```

Получает все фильмы-сиквелы.

#### Возвращает

- `array<int, RelatedFilm>` - Массив фильмов-сиквелов

#### Исключения

- `KpValidationException` - При некорректной структуре данных

#### Пример использования

```php
$sequels = $response->getSequels();
echo "Найдено сиквелов: " . count($sequels) . "\n";
foreach ($sequels as $sequel) {
    echo "- {$sequel->getDisplayName()}\n";
}
```

### getPrequels()

```php
public function getPrequels(): array
```

Получает все фильмы-приквелы.

#### Возвращает

- `array<int, RelatedFilm>` - Массив фильмов-приквелов

#### Исключения

- `KpValidationException` - При некорректной структуре данных

#### Пример использования

```php
$prequels = $response->getPrequels();
echo "Найдено приквелов: " . count($prequels) . "\n";
foreach ($prequels as $prequel) {
    echo "- {$prequel->getDisplayName()}\n";
}
```

### hasFilmsOfType()

```php
public function hasFilmsOfType(RelationType $type): bool
```

Проверяет наличие фильмов указанного типа.

#### Параметры

- `$type` (RelationType) - Тип отношения для проверки

#### Возвращает

- `bool` - True если фильмы присутствуют, false в противном случае

#### Пример использования

```php
if ($response->hasFilmsOfType(RelationType::SEQUEL)) {
    echo "У фильма есть сиквелы\n";
}

if ($response->hasFilmsOfType(RelationType::PREQUEL)) {
    echo "У фильма есть приквелы\n";
}
```

### getByRelationType()

```php
public function getByRelationType(RelationType $type): array
```

Получает все фильмы указанного типа отношения.

#### Параметры

- `$type` (RelationType) - Тип отношения между фильмами

#### Возвращает

- `array<int, RelatedFilm>` - Массив фильмов указанного типа

#### Исключения

- `KpValidationException` - При некорректных параметрах или данных

#### Пример использования

```php
// Получение фильмов по типу
$sequels = $response->getByRelationType(RelationType::SEQUEL);
$prequels = $response->getByRelationType(RelationType::PREQUEL);
$remakes = $response->getByRelationType(RelationType::REMAKE);
$similar = $response->getByRelationType(RelationType::SIMILAR);
```

### getRemakes()

```php
public function getRemakes(): array
```

Получает все римейки.

#### Возвращает

- `array<int, RelatedFilm>` - Массив римейков

#### Исключения

- `KpValidationException` - При некорректной структуре данных

#### Пример использования

```php
$remakes = $response->getRemakes();
echo "Найдено римейков: " . count($remakes) . "\n";
foreach ($remakes as $remake) {
    echo "- {$remake->getDisplayName()}\n";
}
```

### getSimilar()

```php
public function getSimilar(): array
```

Получает все похожие фильмы.

#### Возвращает

- `array<int, RelatedFilm>` - Массив похожих фильмов

#### Исключения

- `KpValidationException` - При некорректной структуре данных

#### Пример использования

```php
$similar = $response->getSimilar();
echo "Найдено похожих фильмов: " . count($similar) . "\n";
foreach ($similar as $film) {
    echo "- {$film->getDisplayName()}\n";
}
```

### getStatistics()

```php
public function getStatistics(): array
```

Получает статистику по всем типам отношений.

#### Возвращает

- `array<string, int>` - Статистика по типам отношений

#### Пример использования

```php
$stats = $response->getStatistics();
echo "Общая статистика:\n";
echo "- Сиквелов: {$stats['sequels']}\n";
echo "- Приквелов: {$stats['prequels']}\n";
echo "- Римейков: {$stats['remakes']}\n";
echo "- Похожих: {$stats['similar']}\n";
echo "- Всего: {$stats['total']}\n";
```

### countByType()

```php
public function countByType(RelationType $type): int
```

Возвращает количество фильмов указанного типа.

#### Параметры

- `$type` (RelationType) - Тип отношения для подсчёта

#### Возвращает

- `int` - Количество фильмов указанного типа

#### Пример использования

```php
$sequelCount = $response->countByType(RelationType::SEQUEL);
echo "Количество сиквелов: $sequelCount\n";

$prequelCount = $response->countByType(RelationType::PREQUEL);
echo "Количество приквелов: $prequelCount\n";
```

### hasRelatedFilms()

```php
public function hasRelatedFilms(): bool
```

Проверяет, есть ли связанные фильмы.

#### Возвращает

- `bool` - True если есть хотя бы один связанный фильм

#### Пример использования

```php
if ($response->hasRelatedFilms()) {
    echo "У фильма есть связанные фильмы\n";
} else {
    echo "У фильма нет связанных фильмов\n";
}
```

### getAvailableRelationTypes()

```php
public function getAvailableRelationTypes(): array
```

Получает все уникальные типы отношений в текущем наборе данных.

#### Возвращает

- `array<RelationType>` - Массив уникальных типов отношений

#### Пример использования

```php
$types = $response->getAvailableRelationTypes();
echo "Доступные типы отношений:\n";
foreach ($types as $type) {
    echo "- {$type->getDescription()}\n";
}
```

### groupByRelationType()

```php
public function groupByRelationType(): array
```

Группирует связанные фильмы по типу отношения.

#### Возвращает

- `array<string, array<RelatedFilm>>` - Ассоциативный массив, где ключи - строковые значения типов связей, а значения - массивы объектов RelatedFilm

#### Пример использования

```php
// Группировка связанных фильмов
$groups = $response->groupByRelationType();

// Обработка сгруппированных данных
foreach ($groups as $relationType => $films) {
    echo "Тип связи: {$relationType}\n";
    echo "Количество фильмов: " . count($films) . "\n";

    foreach ($films as $film) {
        echo "  - {$film->getDisplayName()}\n";
    }
    echo "\n";
}

// Получение конкретного типа связи
$sequels = $groups['SEQUEL'] ?? [];
$similars = $groups['SIMILAR'] ?? [];

// Проверка наличия определённого типа
if (isset($groups['PREQUEL'])) {
    echo "Найдены приквелы: " . count($groups['PREQUEL']) . " шт.\n";
}
```

## Типы отношений

Класс поддерживает следующие типы отношений:

- **SEQUEL** - Сиквелы (продолжения)
- **PREQUEL** - Приквелы (предыстории)
- **REMAKE** - Римейки (новые версии)
- **SIMILAR** - Похожие фильмы
- **UNKNOWN** - Неизвестный тип связи

## Обработка ошибок

Класс включает комплексную обработку ошибок:

- Валидация структуры данных API
- Проверка типизации элементов
- Обработка некорректных типов отношений
- Безопасная фильтрация и группировка

## Пример полного использования

```php
use NotKinopoisk\Responses\SequelPrequelResponse;
use NotKinopoisk\Models\RelatedFilm;
use NotKinopoisk\Enums\RelationType;

// Получение данных от API
$apiData = [
    ['kinopoiskId' => 1, 'relationType' => 'SEQUEL'],
    ['kinopoiskId' => 2, 'relationType' => 'PREQUEL'],
    ['kinopoiskId' => 3, 'relationType' => 'SIMILAR']
];

// Создание объекта ответа
$response = SequelPrequelResponse::fromArray($apiData, RelatedFilm::class);

// Анализ связанных фильмов
$sequels = $response->getSequels();
$prequels = $response->getPrequels();
$similar = $response->getSimilar();

$stats = $response->getStatistics();
$groups = $response->groupByRelationType();

echo "Статистика связанных фильмов:\n";
foreach ($stats as $type => $count) {
    echo "- {$type}: {$count}\n";
}

// Проверка наличия определенных типов
if ($response->hasFilmsOfType(RelationType::SEQUEL)) {
    echo "Найдены сиквелы: " . count($sequels) . "\n";
}

if ($response->hasRelatedFilms()) {
    echo "Всего связанных фильмов: " . count($response->items) . "\n";
}
```

## Связанные классы

- `SimpleResponse` - Базовый класс для простых ответов
- `RelatedFilm` - Модель связанного фильма
- `RelationType` - Перечисление типов отношений
- `KpValidationException` - Исключение для ошибок валидации
