# SimpleResponse

## Описание

`SimpleResponse` - это базовый класс для простых ответов API, содержащих только массив элементов без дополнительной метаинформации. Реализует интерфейс `ResponseInterface` и предоставляет базовую функциональность для работы с коллекциями объектов.

## Основные возможности

- Хранение массива элементов в неизменяемом виде
- Создание объекта из массива данных API с валидацией класса
- Преобразование элементов в указанный тип через статический метод `fromArray`
- Преобразование в массив для сериализации
- Валидация целевого класса для преобразования элементов

## Наследование

```php
NotKinopoisk\Interfaces\ResponseInterface
└── NotKinopoisk\Responses\SimpleResponse
```

## Конструктор

```php
public function __construct(public array $items)
```

### Параметры

- `$items` (array) - Массив элементов данных

## Свойства

### items

```php
public array $items
```

Массив элементов ответа. Доступен только для чтения.

## Статические методы

### fromArray()

```php
public static function fromArray(array $data, string $cls): object
```

Создает экземпляр `SimpleResponse` из массива данных API.

#### Параметры

- `$data` (array) - Массив данных от API
- `$cls` (string) - Имя класса для преобразования элементов

#### Возвращает

- `object` - Новый экземпляр с преобразованными данными

#### Исключения

- `KpValidationException` - Если указанный класс не существует или не имеет метода `fromArray`

#### Пример использования

```php
$apiData = [
    ['id' => 1, 'name' => 'Item 1'],
    ['id' => 2, 'name' => 'Item 2']
];

$response = SimpleResponse::fromArray($apiData, MyModel::class);
```

### checkClass()

```php
public static function checkClass(string $cls): void
```

Валидирует целевой класс для преобразования элементов.

#### Параметры

- `$cls` (string) - Полное имя класса

#### Исключения

- `KpValidationException` - Если класс не существует или не имеет статического метода `fromArray`

#### Пример использования

```php
SimpleResponse::checkClass(MyModel::class);
```

## Методы экземпляра

### toArray()

```php
public function toArray(): array
```

Преобразует объект в массив.

#### Возвращает

- `array` - Массив с преобразованными элементами

#### Пример использования

```php
$response = SimpleResponse::fromArray($apiData, MyModel::class);
$array = $response->toArray();
```

## Требования к целевым классам

Для корректной работы с `SimpleResponse` целевые классы должны:

1. **Существовать** - Класс должен быть доступен в системе
2. **Иметь статический метод `fromArray`** - Метод должен принимать массив и возвращать объект
3. **Иметь метод `toArray`** - Для корректного преобразования в массив

### Пример корректного класса

```php
class MyModel
{
    public function __construct(
        public int $id,
        public string $name
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
```

## Обработка ошибок

Класс включает комплексную обработку ошибок:

- **Проверка существования класса** - Убеждается, что указанный класс существует
- **Валидация метода `fromArray`** - Проверяет наличие и статичность метода
- **Обработка ошибок типизации** - Безопасное преобразование данных

## Пример полного использования

```php
use NotKinopoisk\Responses\SimpleResponse;
use NotKinopoisk\Models\MyModel;

// Получение данных от API
$apiData = [
    ['id' => 1, 'name' => 'Первый элемент'],
    ['id' => 2, 'name' => 'Второй элемент'],
    ['id' => 3, 'name' => 'Третий элемент']
];

// Создание объекта ответа
$response = SimpleResponse::fromArray($apiData, MyModel::class);

// Доступ к элементам
$items = $response->items;
echo "Количество элементов: " . count($items) . "\n";

// Обработка элементов
foreach ($items as $item) {
    echo "ID: {$item->id}, Имя: {$item->name}\n";
}

// Преобразование в массив
$array = $response->toArray();
echo "Преобразовано в массив: " . json_encode($array, JSON_UNESCAPED_UNICODE) . "\n";
```

## Наследование

`SimpleResponse` является базовым классом для более специализированных ответов:

- `MovieStaffResponse` - Для работы со съемочной командой
- `SequelPrequelResponse` - Для работы с сиквелами и приквелами

## Связанные классы

- `ResponseInterface` - Интерфейс для всех ответов API
- `KpValidationException` - Исключение для ошибок валидации
- `DefaultResponse` - Расширенный базовый класс с метаинформацией
- `PaginatedResponse` - Класс для пагинированных ответов
