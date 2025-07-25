# ProductionStatus

## Описание

`ProductionStatus` - это перечисление статусов производства контента в Kinopoisk API. Определяет различные этапы производственного процесса фильмов и сериалов, от анонса до завершения.

## Основные возможности

- Определение статусов производства контента
- Проверка завершенности производства
- Проверка активной фазы производства
- Получение человекочитаемых названий статусов
- Совместимость с Kinopoisk API

## Значения перечисления

### FILMING

```php
case FILMING = 'FILMING';
```

**Описание:** Статус съемки фильма - активная фаза производства

**Использование:** Фильм находится в процессе съемки

**Пример:**

```php
$status = ProductionStatus::FILMING;
echo $status->getDisplayName(); // "В производстве"
echo $status->inProduction(); // true
```

### PRE_PRODUCTION

```php
case PRE_PRODUCTION = 'PRE_PRODUCTION';
```

**Описание:** Статус пре-производства - подготовительная фаза

**Использование:** Подготовка к съемкам (кастинг, сценарий, планирование)

**Пример:**

```php
$status = ProductionStatus::PRE_PRODUCTION;
echo $status->getDisplayName(); // "Пре-производство"
echo $status->inProduction(); // true
```

### POST_PRODUCTION

```php
case POST_PRODUCTION = 'POST_PRODUCTION';
```

**Описание:** Статус пост-производства - монтаж и обработка

**Использование:** Монтаж, озвучивание, цветокоррекция

**Пример:**

```php
$status = ProductionStatus::POST_PRODUCTION;
echo $status->getDisplayName(); // "Пост-производство"
echo $status->inProduction(); // true
```

### COMPLETED

```php
case COMPLETED = 'COMPLETED';
```

**Описание:** Статус завершенного производства

**Использование:** Фильм полностью готов к показу

**Пример:**

```php
$status = ProductionStatus::COMPLETED;
echo $status->getDisplayName(); // "Завершено"
echo $status->isFinished(); // true
echo $status->inProduction(); // false
```

### ANNOUNCED

```php
case ANNOUNCED = 'ANNOUNCED';
```

**Описание:** Статус объявленного проекта - только анонс

**Использование:** Проект анонсирован, но производство еще не началось

**Пример:**

```php
$status = ProductionStatus::ANNOUNCED;
echo $status->getDisplayName(); // "Объявлено"
echo $status->isFinished(); // false
echo $status->inProduction(); // false
```

### UNKNOWN

```php
case UNKNOWN = 'UNKNOWN';
```

**Описание:** Неизвестный статус производства

**Использование:** Статус производства не определен

**Пример:**

```php
$status = ProductionStatus::UNKNOWN;
echo $status->getDisplayName(); // "Неизвестно"
echo $status->isFinished(); // false
echo $status->inProduction(); // false
```

## Методы

### isFinished()

```php
public function isFinished(): bool
```

Проверяет, завершено ли производство контента.

#### Возвращает

- `bool` - true если производство завершено, false в противном случае

#### Пример использования

```php
use NotKinopoisk\Enums\ProductionStatus;

$status = ProductionStatus::COMPLETED;

if ($status->isFinished()) {
    echo "Производство завершено, можно смотреть";
} else {
    echo "Производство еще не завершено";
}
```

### inProduction()

```php
public function inProduction(): bool
```

Проверяет, находится ли контент в активной фазе производства.

#### Возвращает

- `bool` - true если контент находится в производстве, false в противном случае

#### Пример использования

```php
use NotKinopoisk\Enums\ProductionStatus;

$status = ProductionStatus::FILMING;

if ($status->inProduction()) {
    echo "Проект активно разрабатывается";
} else {
    echo "Проект либо завершен, либо только анонсирован";
}
```

### getDisplayName()

```php
public function getDisplayName(): string
```

Получает человекочитаемое название статуса производства.

#### Возвращает

- `string` - Название статуса производства на русском языке

#### Пример использования

```php
use NotKinopoisk\Enums\ProductionStatus;

echo ProductionStatus::FILMING->getDisplayName(); // "В производстве"
echo ProductionStatus::COMPLETED->getDisplayName(); // "Завершено"
echo ProductionStatus::ANNOUNCED->getDisplayName(); // "Объявлено"
```

## Логика работы методов

### isFinished()

Метод `isFinished()` использует строковое сравнение значений enum для определения порядка:

```php
// Значения в порядке возрастания
ANNOUNCED < UNKNOWN < PRE_PRODUCTION < FILMING < POST_PRODUCTION < COMPLETED

// Метод возвращает true для COMPLETED и всех статусов "выше"
```

### inProduction()

Метод `inProduction()` возвращает `true` только для активных стадий:

- `PRE_PRODUCTION` - Пре-производство
- `FILMING` - Съемки
- `POST_PRODUCTION` - Пост-производство

## Сравнение статусов

```php
use NotKinopoisk\Enums\ProductionStatus;

// Проверка конкретного статуса
$status = ProductionStatus::FILMING;

if ($status === ProductionStatus::FILMING) {
    echo "Фильм находится в съемках";
}

// Использование в switch/match
$message = match ($status) {
    ProductionStatus::ANNOUNCED => 'Проект анонсирован',
    ProductionStatus::PRE_PRODUCTION => 'Идет подготовка к съемкам',
    ProductionStatus::FILMING => 'Идут съемки',
    ProductionStatus::POST_PRODUCTION => 'Идет монтаж',
    ProductionStatus::COMPLETED => 'Фильм готов',
    ProductionStatus::UNKNOWN => 'Статус неизвестен',
};
```

## Использование в моделях

Статусы производства используются в моделях фильмов:

```php
use NotKinopoisk\Enums\ProductionStatus;
use NotKinopoisk\Models\Film;

// Создание фильма с определенным статусом
$film = new Film(
    kinopoiskId: 123,
    nameRu: 'Новый фильм',
    productionStatus: ProductionStatus::FILMING
);

// Проверка статуса
if ($film->productionStatus->inProduction()) {
    echo "Фильм '{$film->nameRu}' находится в производстве";
}

if ($film->productionStatus->isFinished()) {
    echo "Фильм '{$film->nameRu}' готов к показу";
}
```

## Валидация значений

```php
use NotKinopoisk\Enums\ProductionStatus;

// Проверка существования значения
$value = 'FILMING';
if (ProductionStatus::tryFrom($value)) {
    $status = ProductionStatus::from($value);
    echo "Статус производства: {$status->getDisplayName()}\n";
} else {
    echo "Неизвестный статус производства: {$value}\n";
}
```

## Получение всех значений

```php
use NotKinopoisk\Enums\ProductionStatus;

// Получение всех статусов производства
$allStatuses = ProductionStatus::cases();

foreach ($allStatuses as $status) {
    echo "{$status->value} => {$status->getDisplayName()}\n";
    echo "  В производстве: " . ($status->inProduction() ? 'Да' : 'Нет') . "\n";
    echo "  Завершено: " . ($status->isFinished() ? 'Да' : 'Нет') . "\n";
    echo "\n";
}
```

## Обработка ошибок

```php
use NotKinopoisk\Enums\ProductionStatus;

try {
    // Попытка создания из несуществующего значения
    $status = ProductionStatus::from('INVALID');
} catch (\ValueError $e) {
    echo "Ошибка: Неизвестный статус производства\n";
}

// Безопасное получение значения
$status = ProductionStatus::tryFrom('FILMING');
if ($status) {
    echo "Статус найден: {$status->getDisplayName()}\n";
} else {
    echo "Статус не найден\n";
}
```

## Пример полного использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Enums\ProductionStatus;

// Создание статуса производства
$status = ProductionStatus::FILMING;

// Получение информации
echo "Значение: {$status->value}\n";
echo "Название: {$status->getDisplayName()}\n";
echo "В производстве: " . ($status->inProduction() ? 'Да' : 'Нет') . "\n";
echo "Завершено: " . ($status->isFinished() ? 'Да' : 'Нет') . "\n";

// Сравнение
if ($status === ProductionStatus::FILMING) {
    echo "Это статус съемок\n";
}

// Использование в массиве
$statuses = [
    ProductionStatus::ANNOUNCED,
    ProductionStatus::FILMING,
    ProductionStatus::COMPLETED
];

echo "Статусы производства:\n";
foreach ($statuses as $s) {
    echo "- {$s->getDisplayName()}\n";
}

// Фильтрация по статусам
$activeProjects = array_filter($statuses, fn($s) => $s->inProduction());
$finishedProjects = array_filter($statuses, fn($s) => $s->isFinished());

echo "Активные проекты: " . count($activeProjects) . "\n";
echo "Завершенные проекты: " . count($finishedProjects) . "\n";

// Проверка статуса
$checkStatus = 'COMPLETED';
$foundStatus = ProductionStatus::tryFrom($checkStatus);

if ($foundStatus) {
    echo "Найден статус: {$foundStatus->getDisplayName()}\n";
} else {
    echo "Статус '{$checkStatus}' не найден\n";
}
```

## Связанные классы

- `Film` - Модель фильма, использующая данное перечисление
- `Distribution` - Модель дистрибуции (может зависеть от статуса производства)

## API Endpoints

Статусы производства используются в следующих API endpoints:

- `/api/v1/films/{id}` - Детальная информация о фильме
- `/api/v1/films` - Список фильмов с фильтрацией по статусу
- `/api/v1/films/premieres` - Премьеры фильмов
