# PersonByNameResult

## Описание

`PersonByNameResult` - это модель результата поиска персоны по имени из Kinopoisk API. Представляет краткую информацию о персоне в результатах поиска по имени, содержащую основные данные: идентификатор, имена, пол и постер.

## Основные возможности

- Хранение информации о персоне в неизменяемом виде
- Создание объекта из массива данных API
- Получение отображаемого имени персоны
- Доступ к метаданным поискового результата
- Совместимость с Kinopoisk API

## Наследование

```php
NotKinopoisk\Interfaces\ModelInterface
└── NotKinopoisk\Models\PersonByNameResult
```

## Конструктор

```php
public function __construct(
    public readonly int $kinopoiskId,
    public readonly string $webUrl,
    public readonly ?string $nameRu,
    public readonly ?string $nameEn,
    public readonly ?Sex $sex,
    public readonly string $posterUrl,
)
```

### Параметры

- `$kinopoiskId` (int) - Уникальный идентификатор персоны в Кинопоиске
- `$webUrl` (string) - URL веб-страницы персоны
- `$nameRu` (string|null) - Имя персоны на русском языке
- `$nameEn` (string|null) - Имя персоны на английском языке
- `$sex` (Sex|null) - Пол персоны (MALE, FEMALE, UNKNOWN)
- `$posterUrl` (string) - URL постера персоны

## Свойства

### kinopoiskId

```php
public readonly int $kinopoiskId
```

**Описание:** Уникальный идентификатор персоны в Кинопоиске

**Тип:** `int`

**Доступ:** Только для чтения

**Пример:**

```php
$id = $personResult->kinopoiskId;
echo "ID персоны: {$id}";
```

### webUrl

```php
public readonly string $webUrl
```

**Описание:** URL веб-страницы персоны

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$webUrl = $personResult->webUrl;
echo "Веб-страница: {$webUrl}";
```

### nameRu

```php
public readonly ?string $nameRu
```

**Описание:** Имя персоны на русском языке

**Тип:** `string|null`

**Доступ:** Только для чтения

**Пример:**

```php
$nameRu = $personResult->nameRu;
if ($nameRu) {
    echo "Имя на русском: {$nameRu}";
}
```

### nameEn

```php
public readonly ?string $nameEn
```

**Описание:** Имя персоны на английском языке

**Тип:** `string|null`

**Доступ:** Только для чтения

**Пример:**

```php
$nameEn = $personResult->nameEn;
if ($nameEn) {
    echo "Имя на английском: {$nameEn}";
}
```

### sex

```php
public readonly ?Sex $sex
```

**Описание:** Пол персоны (MALE, FEMALE, UNKNOWN)

**Тип:** `Sex|null`

**Доступ:** Только для чтения

**Пример:**

```php
$sex = $personResult->sex;
if ($sex) {
    echo "Пол: {$sex->getDisplayName()}";
}
```

### posterUrl

```php
public readonly string $posterUrl
```

**Описание:** URL постера персоны

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$posterUrl = $personResult->posterUrl;
echo "Постер: {$posterUrl}";
```

## Статические методы

### fromArray()

```php
public static function fromArray(array $data): self
```

Создает экземпляр модели из массива данных API.

#### Параметры

- `$data` (array) - Массив данных из API ответа

#### Возвращает

- `static` - Новый экземпляр модели

#### Исключения

- `\ValueError` - Если неверное значение для enum Sex

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

$apiData = [
    'kinopoiskId' => 66539,
    'webUrl' => '10096',
    'nameRu' => 'Винс Гиллиган',
    'nameEn' => 'Vince Gilligan',
    'sex' => 'MALE',
    'posterUrl' => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
];

$personResult = PersonByNameResult::fromArray($apiData);
```

## Методы экземпляра

### getDisplayName()

```php
public function getDisplayName(): string
```

Возвращает отображаемое имя персоны.

#### Возвращает

- `string` - Отображаемое имя персоны

#### Особенности

- Приоритет отдается русскому имени, затем английскому
- Если оба имени отсутствуют, возвращает строку "Неизвестно"

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

echo $personResult->getDisplayName(); // "Винс Гиллиган"
```

### isMale()

```php
public function isMale(): bool
```

Проверяет, является ли персона мужчиной.

#### Возвращает

- `bool` - true если персона мужского пола

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

if ($personResult->isMale()) {
    echo "Мужчина";
}
```

### isFemale()

```php
public function isFemale(): bool
```

Проверяет, является ли персона женщиной.

#### Возвращает

- `bool` - true если персона женского пола

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

if ($personResult->isFemale()) {
    echo "Женщина";
}
```

### isSexUnknown()

```php
public function isSexUnknown(): bool
```

Проверяет, известен ли пол персоны.

#### Возвращает

- `bool` - true если пол персоны неизвестен

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

if ($personResult->isSexUnknown()) {
    echo "Пол неизвестен";
}
```

### getFullName()

```php
public function getFullName(string $separator = ' / '): string
```

Возвращает полное имя персоны (русское + английское).

#### Параметры

- `$separator` (string) - Разделитель между именами (по умолчанию " / ")

#### Возвращает

- `string` - Полное имя персоны

#### Особенности

- Если есть оба имени, возвращает их через разделитель
- Если есть только одно имя, возвращает его

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

echo $personResult->getFullName(); // "Винс Гиллиган / Vince Gilligan"
echo $personResult->getFullName(' | '); // "Винс Гиллиган | Vince Gilligan"
```

### toArray()

```php
public function toArray(): array
```

Преобразует модель в массив.

#### Возвращает

- `array` - Массив данных модели

#### Особенности

- Возвращает массив с данными модели в том же формате, что и API
- Enum значения преобразуются в строки

#### Пример использования

```php
use NotKinopoisk\Models\PersonByNameResult;

$array = $personResult->toArray();
// [
//     'kinopoiskId' => 66539,
//     'webUrl' => '10096',
//     'nameRu' => 'Винс Гиллиган',
//     'nameEn' => 'Vince Gilligan',
//     'sex' => 'MALE',
//     'posterUrl' => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
// ]
```

## Примеры использования

### Создание объекта

```php
use NotKinopoisk\Models\PersonByNameResult;
use NotKinopoisk\Enums\Sex;

$personResult = new PersonByNameResult(
    kinopoiskId: 66539,
    webUrl: '10096',
    nameRu: 'Винс Гиллиган',
    nameEn: 'Vince Gilligan',
    sex: Sex::MALE,
    posterUrl: 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
);
```

### Создание из данных API

```php
use NotKinopoisk\Models\PersonByNameResult;

// Данные от API
$apiData = [
    'kinopoiskId' => 66539,
    'webUrl' => '10096',
    'nameRu' => 'Винс Гиллиган',
    'nameEn' => 'Vince Gilligan',
    'sex' => 'MALE',
    'posterUrl' => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
];

$personResult = PersonByNameResult::fromArray($apiData);
```

### Работа с результатом поиска

```php
use NotKinopoisk\Models\PersonByNameResult;

// Получение информации
echo "ID: {$personResult->kinopoiskId}\n";
echo "Имя: {$personResult->getDisplayName()}\n";
echo "Полное имя: {$personResult->getFullName()}\n";
echo "Пол: {$personResult->sex?->getDisplayName()}\n";
echo "Постер: {$personResult->posterUrl}\n";
```

### Использование в сервисах

```php
use NotKinopoisk\Models\PersonByNameResult;
use NotKinopoisk\Services\PersonService;

class PersonService extends AbstractService
{
    public function searchByName(string $name): array
    {
        $response = $this->client->get('/api/v1/persons', [
            'name' => $name
        ]);

        return array_map(
            fn(array $personData) => PersonByNameResult::fromArray($personData),
            $response['items'] ?? []
        );
    }
}
```

### Отображение результатов поиска

```php
use NotKinopoisk\Models\PersonByNameResult;

function displayPersonResult(PersonByNameResult $person): void
{
    echo "=== Результат поиска ===\n";
    echo "ID: {$person->kinopoiskId}\n";
    echo "Имя: {$person->getDisplayName()}\n";
    echo "Полное имя: {$person->getFullName()}\n";

    if ($person->sex) {
        echo "Пол: {$person->sex->getDisplayName()}\n";
    } else {
        echo "Пол: Неизвестен\n";
    }

    echo "Постер: {$person->posterUrl}\n";
    echo "Веб-страница: {$person->webUrl}\n";
    echo "=======================\n";
}

// Использование
$personResult = PersonByNameResult::fromArray($apiData);
displayPersonResult($personResult);
```

### Фильтрация по полу

```php
use NotKinopoisk\Models\PersonByNameResult;

function filterBySex(array $persons, string $sex): array
{
    return array_filter($persons, function(PersonByNameResult $person) use ($sex) {
        if (!$person->sex) return false;

        return match($sex) {
            'male' => $person->isMale(),
            'female' => $person->isFemale(),
            'unknown' => $person->isSexUnknown(),
            default => false
        };
    });
}

// Использование
$allPersons = [/* массив PersonByNameResult объектов */];
$malePersons = filterBySex($allPersons, 'male');
$femalePersons = filterBySex($allPersons, 'female');
```

### Поиск по имени

```php
use NotKinopoisk\Models\PersonByNameResult;

function searchInResults(array $persons, string $query): array
{
    $query = strtolower($query);

    return array_filter($persons, function(PersonByNameResult $person) use ($query) {
        $nameRu = strtolower($person->nameRu ?? '');
        $nameEn = strtolower($person->nameEn ?? '');

        return str_contains($nameRu, $query) || str_contains($nameEn, $query);
    });
}

// Использование
$searchResults = searchInResults($allPersons, 'винс');
```

### Сортировка результатов

```php
use NotKinopoisk\Models\PersonByNameResult;

function sortByName(array $persons, bool $ascending = true): array
{
    usort($persons, function(PersonByNameResult $a, PersonByNameResult $b) use ($ascending) {
        $nameA = $a->getDisplayName();
        $nameB = $b->getDisplayName();

        $comparison = strcmp($nameA, $nameB);
        return $ascending ? $comparison : -$comparison;
    });

    return $persons;
}

// Использование
$sortedPersons = sortByName($allPersons, true); // По возрастанию
```

### Статистика результатов

```php
use NotKinopoisk\Models\PersonByNameResult;

function getSearchStatistics(array $persons): array
{
    $total = count($persons);
    $male = count(array_filter($persons, fn($p) => $p->isMale()));
    $female = count(array_filter($persons, fn($p) => $p->isFemale()));
    $unknown = count(array_filter($persons, fn($p) => $p->isSexUnknown()));

    return [
        'total' => $total,
        'male' => $male,
        'female' => $female,
        'unknown' => $unknown,
        'male_percent' => $total > 0 ? round(($male / $total) * 100, 1) : 0,
        'female_percent' => $total > 0 ? round(($female / $total) * 100, 1) : 0,
        'unknown_percent' => $total > 0 ? round(($unknown / $total) * 100, 1) : 0
    ];
}

// Использование
$stats = getSearchStatistics($allPersons);
echo "Всего найдено: {$stats['total']}\n";
echo "Мужчин: {$stats['male']} ({$stats['male_percent']}%)\n";
echo "Женщин: {$stats['female']} ({$stats['female_percent']}%)\n";
echo "Неизвестно: {$stats['unknown']} ({$stats['unknown_percent']}%)\n";
```

### Сериализация и десериализация

```php
use NotKinopoisk\Models\PersonByNameResult;

// Преобразование в массив
$array = $personResult->toArray();

// Сохранение в JSON
$json = json_encode($array, JSON_PRETTY_PRINT);
file_put_contents('person_result.json', $json);

// Загрузка из JSON
$loadedArray = json_decode(file_get_contents('person_result.json'), true);
$loadedPersonResult = PersonByNameResult::fromArray($loadedArray);
```

### Валидация данных

```php
use NotKinopoisk\Models\PersonByNameResult;

function validatePersonData(array $data): bool
{
    if (!isset($data['kinopoiskId']) || !is_int($data['kinopoiskId'])) {
        throw new \InvalidArgumentException('kinopoiskId должен быть целым числом');
    }

    if (!isset($data['webUrl']) || !is_string($data['webUrl'])) {
        throw new \InvalidArgumentException('webUrl должен быть строкой');
    }

    if (!isset($data['posterUrl']) || !is_string($data['posterUrl'])) {
        throw new \InvalidArgumentException('posterUrl должен быть строкой');
    }

    if (isset($data['sex']) && !in_array($data['sex'], ['MALE', 'FEMALE', 'UNKNOWN'])) {
        throw new \InvalidArgumentException('sex должен быть одним из: MALE, FEMALE, UNKNOWN');
    }

    return true;
}

// Использование
try {
    validatePersonData($apiData);
    $personResult = PersonByNameResult::fromArray($apiData);
} catch (\InvalidArgumentException $e) {
    echo "Ошибка валидации: " . $e->getMessage();
}
```

## Связанные классы

- `PersonService` - Сервис для работы с персонами
- `Sex` - Перечисление полов
- `Person` - Полная модель персоны
- `ModelInterface` - Интерфейс модели

## API Endpoints

Результаты поиска персон используются в следующих API endpoints:

- `/api/v1/persons` - Поиск персон по имени
- `/api/v1/persons/{id}` - Детальная информация о персоне
