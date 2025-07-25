# BudgetResponse

## Описание

`BudgetResponse` - это специализированный класс ответа для работы с данными о бюджете фильма от Kinopoisk API. Наследует функциональность `DefaultResponse` и добавляет специфичные методы для анализа финансовых показателей.

## Основные возможности

- Создание объекта из данных API с валидацией
- Вычисление общего дохода от всех источников
- Детализированная разбивка доходов по типам
- Подсчет количества доходных статей
- Безопасная обработка ошибок типизации

## Наследование

```php
NotKinopoisk\Responses\DefaultResponse
└── NotKinopoisk\Responses\BudgetResponse
```

## Конструктор

```php
public function __construct(
    public int $total,
    public array $items
)
```

### Параметры

- `$total` (int) - Общее количество элементов в коллекции
- `$items` (array) - Массив элементов данных о бюджете

## Статические методы

### fromArray()

```php
public static function fromArray(array $data, string $cls): self
```

Создает экземпляр `BudgetResponse` из массива данных API.

#### Параметры

- `$data` (array) - Массив данных от API, содержащий информацию о бюджете
- `$cls` (string) - Имя класса для элементов коллекции (обычно `BoxOffice::class`)

#### Возвращает

- `BudgetResponse` - Новый экземпляр с данными о бюджете

#### Исключения

- `KpValidationException` - Если данные имеют некорректную структуру

#### Пример использования

```php
$apiData = ['total' => 5, 'items' => [...]];
$budgetResponse = BudgetResponse::fromArray($apiData, BoxOffice::class);
```

## Методы экземпляра

### getTotalRevenue()

```php
public function getTotalRevenue(): int
```

Вычисляет общий доход от всех источников поступлений.

#### Возвращает

- `int` - Общая сумма дохода в указанной валюте

#### Исключения

- `KpValidationException` - Если элементы не содержат корректных данных

#### Пример использования

```php
$budgetResponse = BudgetResponse::fromArray($apiData, BoxOffice::class);
$totalRevenue = $budgetResponse->getTotalRevenue();
echo "Общий доход: {$totalRevenue}";
```

### getRevenueBreakdown()

```php
public function getRevenueBreakdown(): array
```

Получает детализированную информацию о доходах по типам.

#### Возвращает

- `array<string, int>` - Ассоциативный массив типов доходов и их сумм

#### Пример использования

```php
$revenueBreakdown = $budgetResponse->getRevenueBreakdown();
foreach ($revenueBreakdown as $type => $amount) {
    echo "Доход от {$type}: {$amount}";
}
```

### getRevenueItemsCount()

```php
public function getRevenueItemsCount(): int
```

Получает количество доходных статей.

#### Возвращает

- `int` - Количество элементов с доходными статьями

## Типы доходов

Класс поддерживает следующие типы доходов:

- **Россия** (`BoxOfficeType::RUS`) - Доходы от российского проката
- **США** (`BoxOfficeType::USA`) - Доходы от американского проката
- **Мировые сборы** (`BoxOfficeType::WORLD`) - Общемировые доходы

## Обработка ошибок

Класс включает комплексную обработку ошибок:

- Валидация структуры данных API
- Проверка типизации элементов
- Обработка некорректных финансовых данных
- Безопасное вычисление сумм

## Пример полного использования

```php
use NotKinopoisk\Responses\BudgetResponse;
use NotKinopoisk\Models\BoxOffice;

// Получение данных от API
$apiData = [
    'total' => 3,
    'items' => [
        ['type' => 'RUS', 'amount' => 1000000],
        ['type' => 'USA', 'amount' => 5000000],
        ['type' => 'WORLD', 'amount' => 15000000]
    ]
];

// Создание объекта ответа
$budgetResponse = BudgetResponse::fromArray($apiData, BoxOffice::class);

// Анализ финансовых показателей
$totalRevenue = $budgetResponse->getTotalRevenue();
$breakdown = $budgetResponse->getRevenueBreakdown();
$revenueCount = $budgetResponse->getRevenueItemsCount();

echo "Общий доход: {$totalRevenue}\n";
echo "Количество источников дохода: {$revenueCount}\n";

foreach ($breakdown as $source => $amount) {
    echo "Доход от {$source}: {$amount}\n";
}
```

## Связанные классы

- `DefaultResponse` - Базовый класс для всех ответов API
- `BoxOffice` - Модель данных о кассовых сборах
- `BoxOfficeType` - Перечисление типов кассовых сборов
- `KpValidationException` - Исключение для ошибок валидации
