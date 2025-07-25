# DistributionSubType

## Описание

`DistributionSubType` - это перечисление подтипов проката фильмов в Kinopoisk API. Определяет различные способы распространения и показа фильмов, включая традиционные кинотеатры и современные цифровые форматы.

## Основные возможности

- Определение подтипов проката фильмов
- Получение человекочитаемых названий
- Поддержка различных форматов распространения
- Совместимость с Kinopoisk API

## Значения перечисления

### CINEMA

```php
case CINEMA = 'CINEMA';
```

**Описание:** Кинотеатры

**Использование:** Традиционный прокат в кинотеатрах

**Пример:**

```php
$subType = DistributionSubType::CINEMA;
echo $subType->getDisplayName(); // "Кинотеатры"
```

### DVD

```php
case DVD = 'DVD';
```

**Описание:** DVD

**Использование:** Распространение на DVD-носителях

**Пример:**

```php
$subType = DistributionSubType::DVD;
echo $subType->getDisplayName(); // "DVD"
```

### DIGITAL

```php
case DIGITAL = 'DIGITAL';
```

**Описание:** Цифровой релиз

**Использование:** Цифровое распространение (стриминг, загрузка)

**Пример:**

```php
$subType = DistributionSubType::DIGITAL;
echo $subType->getDisplayName(); // "Цифровой релиз"
```

### BLURAY

```php
case BLURAY = 'BLURAY';
```

**Описание:** Blu-ray

**Использование:** Распространение на Blu-ray-носителях

**Пример:**

```php
$subType = DistributionSubType::BLURAY;
echo $subType->getDisplayName(); // "Blu-ray"
```

## Методы

### getDisplayName()

```php
public function getDisplayName(): string
```

Возвращает человекочитаемое название подтипа проката.

#### Возвращает

- `string` - Человекочитаемое название подтипа

#### Пример использования

```php
use NotKinopoisk\Enums\DistributionSubType;

$subType = DistributionSubType::CINEMA;
echo $subType->getDisplayName(); // "Кинотеатры"

$subType = DistributionSubType::DIGITAL;
echo $subType->getDisplayName(); // "Цифровой релиз"
```

## Сравнение значений

```php
use NotKinopoisk\Enums\DistributionSubType;

// Проверка типа проката
$subType = DistributionSubType::CINEMA;

if ($subType === DistributionSubType::CINEMA) {
    echo "Это прокат в кинотеатрах";
}

// Использование в switch/match
$displayName = match ($subType) {
    DistributionSubType::CINEMA => 'Кинотеатры',
    DistributionSubType::DVD => 'DVD',
    DistributionSubType::DIGITAL => 'Цифровой релиз',
    DistributionSubType::BLURAY => 'Blu-ray',
};
```

## Связь с DistributionType

`DistributionSubType` является подтипом основного типа проката `DistributionType`. Подтипы уточняют конкретный способ распространения в рамках общего типа проката.

### Пример использования вместе с DistributionType

```php
use NotKinopoisk\Enums\DistributionType;
use NotKinopoisk\Enums\DistributionSubType;

// Основной тип проката
$distributionType = DistributionType::WORLDWIDE;

// Подтип проката
$subType = DistributionSubType::CINEMA;

echo "Тип проката: {$distributionType->getDisplayName()}\n";
echo "Подтип проката: {$subType->getDisplayName()}\n";
// Выведет:
// Тип проката: Мировой прокат
// Подтип проката: Кинотеатры
```

## Использование в моделях

Подтипы проката используются в моделях, связанных с дистрибуцией фильмов:

```php
use NotKinopoisk\Enums\DistributionSubType;
use NotKinopoisk\Models\Distribution;

// Создание объекта дистрибуции
$distribution = new Distribution(
    type: DistributionType::WORLDWIDE,
    subType: DistributionSubType::CINEMA,
    date: '2024-01-15'
);

// Получение информации о подтипе
echo "Способ проката: {$distribution->subType->getDisplayName()}\n";
```

## Валидация значений

```php
use NotKinopoisk\Enums\DistributionSubType;

// Проверка существования значения
$value = 'CINEMA';
if (DistributionSubType::tryFrom($value)) {
    $subType = DistributionSubType::from($value);
    echo "Подтип проката: {$subType->getDisplayName()}\n";
} else {
    echo "Неизвестный подтип проката: {$value}\n";
}
```

## Получение всех значений

```php
use NotKinopoisk\Enums\DistributionSubType;

// Получение всех подтипов проката
$allSubTypes = DistributionSubType::cases();

foreach ($allSubTypes as $subType) {
    echo "{$subType->value} => {$subType->getDisplayName()}\n";
}

// Выведет:
// CINEMA => Кинотеатры
// DVD => DVD
// DIGITAL => Цифровой релиз
// BLURAY => Blu-ray
```

## Обработка ошибок

```php
use NotKinopoisk\Enums\DistributionSubType;

try {
    // Попытка создания из несуществующего значения
    $subType = DistributionSubType::from('INVALID');
} catch (\ValueError $e) {
    echo "Ошибка: Неизвестный подтип проката\n";
}

// Безопасное получение значения
$subType = DistributionSubType::tryFrom('CINEMA');
if ($subType) {
    echo "Подтип найден: {$subType->getDisplayName()}\n";
} else {
    echo "Подтип не найден\n";
}
```

## Пример полного использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Enums\DistributionSubType;

// Создание подтипа проката
$subType = DistributionSubType::DIGITAL;

// Получение информации
echo "Значение: {$subType->value}\n";
echo "Название: {$subType->getDisplayName()}\n";

// Сравнение
if ($subType === DistributionSubType::DIGITAL) {
    echo "Это цифровой релиз\n";
}

// Использование в массиве
$subTypes = [
    DistributionSubType::CINEMA,
    DistributionSubType::DIGITAL,
    DistributionSubType::BLURAY
];

echo "Доступные подтипы проката:\n";
foreach ($subTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}

// Проверка типа
$checkType = 'DVD';
$foundType = DistributionSubType::tryFrom($checkType);

if ($foundType) {
    echo "Найден подтип: {$foundType->getDisplayName()}\n";
} else {
    echo "Подтип '{$checkType}' не найден\n";
}
```

## Связанные классы

- `DistributionType` - Основные типы проката
- `Distribution` - Модель дистрибуции фильма
- `Film` - Модель фильма (может содержать информацию о прокате)

## API Endpoints

Подтипы проката используются в следующих API endpoints:

- `/api/v1/films/{id}/distributions` - Информация о прокате фильма
- `/api/v1/films/{id}` - Детальная информация о фильме (включая прокат)
