# Интерфейсы

Интерфейсы библиотеки NotKinopoisk PHP Wrapper.

## ModelInterface

Интерфейс для моделей с возможностью преобразования в/из массива.

### Описание

Определяет базовый контракт для всех моделей данных в системе. Обеспечивает единообразную работу с преобразованием объектов в массивы и создания объектов из массивов данных API.

### Основные возможности

- Унификация процесса создания моделей из данных API
- Стандартизация сериализации объектов в массивы
- Обеспечение совместимости с системами кэширования
- Упрощение работы с JSON API responses

### Методы

#### `fromArray(array $data): object`

Создает экземпляр модели из массива данных API.

**Параметры:**

- `$data` (array) - Массив данных от API, содержащий все необходимые поля для модели

**Возвращает:**

- `object` - Новый экземпляр класса-реализации с данными из массива

**Исключения:**

- `InvalidArgumentException` - Если обязательные поля отсутствуют в массиве данных
- `InvalidArgumentException` - Если данные имеют неверный тип или формат

**Пример:**

```php
$apiData = [
    'id' => 123,
    'name' => 'Название',
    'description' => 'Описание'
];
$model = ConcreteModel::fromArray($apiData);
```

#### `toArray(): array`

Преобразует объект модели в массив.

**Возвращает:**

- `array` - Ассоциативный массив со всеми данными объекта

**Пример:**

```php
$model = new ConcreteModel('value1', 'value2');
$array = $model->toArray();
// Результат: ['field1' => 'value1', 'field2' => 'value2']
```

### Пример реализации

```php
class ExampleModel implements ModelInterface {
    public function __construct(
        private string $field1,
        private string $field2
    ) {}

    public static function fromArray(array $data): object {
        return new static($data['field1'], $data['field2']);
    }

    public function toArray(): array {
        return [
            'field1' => $this->field1,
            'field2' => $this->field2
        ];
    }
}

// Использование
$model = ExampleModel::fromArray($apiData);
$serialized = $model->toArray();
```

## ResponseInterface

Интерфейс для объектов ответов Kinopoisk API.

### Описание

Определяет общий контракт для всех типов ответов API, включая методы для создания объектов из массивов данных и валидации целевых классов. Обеспечивает единообразие в обработке различных типов ответов.

### Основные возможности

- Создание экземпляров ответов из данных API
- Валидация классов для преобразования элементов
- Унификация работы с различными типами ответов

### Методы

#### `fromArray(array $data, string $cls): object`

Создает экземпляр ответа из массива данных API.

**Параметры:**

- `$data` (array) - Массив данных от API, содержащий структуру ответа
- `$cls` (string) - Полное имя класса для преобразования элементов массива

**Возвращает:**

- `object` - Новый экземпляр класса-реализации с преобразованными данными

**Исключения:**

- `KpValidationException` - Если указанный класс не существует
- `KpValidationException` - Если класс не имеет метода fromArray
- `KpValidationException` - Если данные имеют неверный формат

#### `checkClass(string $cls): void`

Проверяет существование и совместимость класса.

**Параметры:**

- `$cls` (string) - Полное имя класса для проверки

**Исключения:**

- `KpValidationException` - Если указанный класс не существует
- `KpValidationException` - Если класс не имеет статического метода fromArray

#### `toArray(): array`

Преобразует объект в массив данных.

**Возвращает:**

- `array` - Массив данных, представляющий объект ответа

**Пример:**

```php
$response = SomeResponse::fromArray($apiData);
$array = $response->toArray();

// Использование для сериализации
$json = json_encode($array);

// Использование для логирования
$logger->info('Ответ API', $array);
```

### Пример использования

```php
// Создание ответа из данных API
$apiData = [
    'total' => 100,
    'items' => [
        ['id' => 1, 'name' => 'Фильм 1'],
        ['id' => 2, 'name' => 'Фильм 2']
    ]
];

$response = DefaultResponse::fromArray($apiData, Film::class);

// Преобразование в массив
$array = $response->toArray();
```

## Связанные классы

- `\NotKinopoisk\Models\Film` - Реализация ModelInterface
- `\NotKinopoisk\Models\Person` - Реализация ModelInterface
- `\NotKinopoisk\Models\Review` - Реализация ModelInterface
- `\NotKinopoisk\Responses\DefaultResponse` - Реализация ResponseInterface
- `\NotKinopoisk\Responses\PaginatedResponse` - Реализация ResponseInterface
- `\NotKinopoisk\Responses\SimpleResponse` - Реализация ResponseInterface

## Информация о пакете

- **Пакет:** NotKinopoisk\Models, NotKinopoisk\Responses
- **Версия:** 1.0.0
- **Автор:** Maxim Harder <dev@devcraft.club>
