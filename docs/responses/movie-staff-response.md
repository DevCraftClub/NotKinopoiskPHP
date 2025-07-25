# MovieStaffResponse

## Описание

`MovieStaffResponse` - это специализированный класс ответа для работы со съемочной командой фильма от Kinopoisk API. Наследует функциональность `SimpleResponse` и предоставляет методы для фильтрации участников съемочного процесса по их профессиональным ролям.

## Основные возможности

- Хранение списка сотрудников в неизменяемом виде
- Создание объекта из массива данных API с валидацией класса
- Фильтрация персонала по профессиональным ролям
- Преобразование в массив для сериализации
- Получение различных групп персонала (актеры, режиссеры, сценаристы и др.)

## Наследование

```php
NotKinopoisk\Responses\SimpleResponse
└── NotKinopoisk\Responses\MovieStaffResponse
```

## Конструктор

```php
public function __construct(public array $items)
```

### Параметры

- `$items` (array) - Массив сотрудников съемочной команды

## Статические методы

### fromArray()

```php
public static function fromArray(array $data, string $cls): self
```

Создает экземпляр `MovieStaffResponse` из массива данных API.

#### Параметры

- `$data` (array) - Массив данных от API
- `$cls` (string) - Имя класса для элементов коллекции

#### Возвращает

- `MovieStaffResponse` - Новый экземпляр с данными о съемочной команде

#### Исключения

- `KpValidationException` - Если данные имеют некорректную структуру

## Методы экземпляра

### getActors()

```php
public function getActors(): array
```

Получает список актеров из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов актеров

#### Пример использования

```php
$actors = $staffResponse->getActors();
foreach ($actors as $actor) {
    echo "Актер: " . $actor->nameRu . "\n";
}
```

### getWriters()

```php
public function getWriters(): array
```

Получает список сценаристов из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов сценаристов

#### Пример использования

```php
$writers = $staffResponse->getWriters();
foreach ($writers as $writer) {
    echo "Сценарист: " . $writer->nameRu . "\n";
}
```

### getDirectors()

```php
public function getDirectors(): array
```

Получает список режиссеров из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов режиссеров

#### Пример использования

```php
$directors = $staffResponse->getDirectors();
foreach ($directors as $director) {
    echo "Режиссер: " . $director->nameRu . "\n";
}
```

### getProducers()

```php
public function getProducers(): array
```

Получает список продюсеров из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов продюсеров

#### Пример использования

```php
$producers = $staffResponse->getProducers();
foreach ($producers as $producer) {
    echo "Продюсер: " . $producer->nameRu . "\n";
}
```

### getCompositors()

```php
public function getCompositors(): array
```

Получает список композиторов из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов композиторов

#### Пример использования

```php
$composers = $staffResponse->getCompositors();
foreach ($composers as $composer) {
    echo "Композитор: " . $composer->nameRu . "\n";
}
```

### getEditors()

```php
public function getEditors(): array
```

Получает список монтажеров из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов монтажеров

#### Пример использования

```php
$editors = $staffResponse->getEditors();
foreach ($editors as $editor) {
    echo "Монтажер: " . $editor->nameRu . "\n";
}
```

### getDesigners()

```php
public function getDesigners(): array
```

Получает список художников из съемочной команды.

#### Возвращает

- `Staff[]` - Массив объектов художников

#### Пример использования

```php
$designers = $staffResponse->getDesigners();
foreach ($designers as $designer) {
    echo "Художник: " . $designer->nameRu . "\n";
}
```

## Профессиональные роли

Класс поддерживает фильтрацию по следующим профессиональным ролям:

- **Актеры** - Участники актерского состава
- **Режиссеры** - Режиссеры фильма
- **Сценаристы** - Авторы сценария
- **Продюсеры** - Продюсеры проекта
- **Композиторы** - Авторы музыки
- **Монтажеры** - Редакторы фильма
- **Художники** - Художники-постановщики

## API Endpoint

Класс используется для работы с API endpoint:

- `/api/v1/staff` - Получение информации о съемочной команде

## Обработка ошибок

Класс включает обработку ошибок:

- Валидация структуры данных API
- Проверка существования и совместимости целевого класса
- Обработка некорректных данных персонала

## Пример полного использования

```php
use NotKinopoisk\Responses\MovieStaffResponse;
use NotKinopoisk\Models\Staff;

// Получение данных от API
$apiData = [
    [
        'staffId' => 1,
        'nameRu' => 'Иван Иванов',
        'professionKey' => 'ACTOR'
    ],
    [
        'staffId' => 2,
        'nameRu' => 'Петр Петров',
        'professionKey' => 'DIRECTOR'
    ],
    [
        'staffId' => 3,
        'nameRu' => 'Сидор Сидоров',
        'professionKey' => 'WRITER'
    ]
];

// Создание объекта ответа
$staffResponse = MovieStaffResponse::fromArray($apiData, Staff::class);

// Получение различных групп персонала
$actors = $staffResponse->getActors();
$directors = $staffResponse->getDirectors();
$writers = $staffResponse->getWriters();
$producers = $staffResponse->getProducers();
$composers = $staffResponse->getCompositors();
$editors = $staffResponse->getEditors();
$designers = $staffResponse->getDesigners();

// Вывод информации о персонале
echo "Актеры (" . count($actors) . "):\n";
foreach ($actors as $actor) {
    echo "- {$actor->nameRu}\n";
}

echo "\nРежиссеры (" . count($directors) . "):\n";
foreach ($directors as $director) {
    echo "- {$director->nameRu}\n";
}

echo "\nСценаристы (" . count($writers) . "):\n";
foreach ($writers as $writer) {
    echo "- {$writer->nameRu}\n";
}

// Преобразование в массив
$staffArray = $staffResponse->toArray();
```

## Связанные классы

- `SimpleResponse` - Базовый класс для простых ответов
- `Staff` - Модель сотрудника съемочной команды
- `ProfessionKey` - Перечисление профессиональных ролей
- `KpValidationException` - Исключение для ошибок валидации
