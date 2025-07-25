# AccountType

## Описание

`AccountType` - это перечисление типов аккаунтов в Kinopoisk API. Определяет различные типы аккаунтов API, которые влияют на лимиты и возможности использования API.

## Основные возможности

- Определение типов аккаунтов API
- Проверка бесплатности аккаунта
- Проверка безлимитности аккаунта
- Получение человекочитаемых названий типов
- Совместимость с Kinopoisk API

## Значения перечисления

### FREE

```php
case FREE = 'FREE';
```

**Описание:** Бесплатный аккаунт

**Использование:** Базовый аккаунт с ограниченными возможностями

**Пример:**

```php
$accountType = AccountType::FREE;
echo $accountType->getDisplayName(); // "Бесплатный"
echo $accountType->isFree(); // true
echo $accountType->isUnlimited(); // false
```

### PAID

```php
case PAID = 'PAID';
```

**Описание:** Платный аккаунт

**Использование:** Аккаунт с расширенными возможностями за плату

**Пример:**

```php
$accountType = AccountType::PAID;
echo $accountType->getDisplayName(); // "Платный"
echo $accountType->isFree(); // false
echo $accountType->isUnlimited(); // false
```

### UNLIMITED

```php
case UNLIMITED = 'UNLIMITED';
```

**Описание:** Безлимитный аккаунт

**Использование:** Аккаунт с максимальными возможностями без ограничений

**Пример:**

```php
$accountType = AccountType::UNLIMITED;
echo $accountType->getDisplayName(); // "Безлимитный"
echo $accountType->isFree(); // false
echo $accountType->isUnlimited(); // true
```

## Методы

### isFree()

```php
public function isFree(): bool
```

Проверяет, является ли аккаунт бесплатным.

#### Возвращает

- `bool` - true если аккаунт бесплатный, false в противном случае

#### Пример использования

```php
use NotKinopoisk\Enums\AccountType;

$accountType = AccountType::FREE;

if ($accountType->isFree()) {
    echo "Бесплатный аккаунт";
} else {
    echo "Платный аккаунт";
}
```

### isUnlimited()

```php
public function isUnlimited(): bool
```

Проверяет, является ли аккаунт безлимитным.

#### Возвращает

- `bool` - true если аккаунт безлимитный, false в противном случае

#### Пример использования

```php
use NotKinopoisk\Enums\AccountType;

$accountType = AccountType::UNLIMITED;

if ($accountType->isUnlimited()) {
    echo "Безлимитный аккаунт";
} else {
    echo "Аккаунт с ограничениями";
}
```

### getDisplayName()

```php
public function getDisplayName(): string
```

Получает человекочитаемое название типа аккаунта.

#### Возвращает

- `string` - Название типа аккаунта на русском языке

#### Пример использования

```php
use NotKinopoisk\Enums\AccountType;

echo AccountType::FREE->getDisplayName(); // "Бесплатный"
echo AccountType::PAID->getDisplayName(); // "Платный"
echo AccountType::UNLIMITED->getDisplayName(); // "Безлимитный"
```

## Сравнение типов аккаунтов

```php
use NotKinopoisk\Enums\AccountType;

// Проверка типа аккаунта
$accountType = AccountType::FREE;

if ($accountType === AccountType::FREE) {
    echo "Это бесплатный аккаунт";
}

// Использование в switch/match
$message = match ($accountType) {
    AccountType::FREE => 'Бесплатный аккаунт с ограничениями',
    AccountType::PAID => 'Платный аккаунт с расширенными возможностями',
    AccountType::UNLIMITED => 'Безлимитный аккаунт без ограничений',
};
```

## Использование в моделях

Типы аккаунтов используются в моделях, связанных с API ключами:

```php
use NotKinopoisk\Enums\AccountType;
use NotKinopoisk\Models\ApiKeyInfo;

// Создание информации об API ключе
$apiKeyInfo = new ApiKeyInfo(
    type: AccountType::PAID,
    current: 100,
    limit: 1000
);

// Проверка типа аккаунта
if ($apiKeyInfo->type->isFree()) {
    echo "Используется бесплатный аккаунт";
} elseif ($apiKeyInfo->type->isUnlimited()) {
    echo "Используется безлимитный аккаунт";
} else {
    echo "Используется платный аккаунт";
}
```

## Логика работы методов

### isFree()

Метод `isFree()` возвращает `true` только для значения `FREE`:

```php
AccountType::FREE->isFree()      // true
AccountType::PAID->isFree()      // false
AccountType::UNLIMITED->isFree() // false
```

### isUnlimited()

Метод `isUnlimited()` возвращает `true` только для значения `UNLIMITED`:

```php
AccountType::FREE->isUnlimited()      // false
AccountType::PAID->isUnlimited()      // false
AccountType::UNLIMITED->isUnlimited() // true
```

## Валидация значений

```php
use NotKinopoisk\Enums\AccountType;

// Проверка существования значения
$value = 'FREE';
if (AccountType::tryFrom($value)) {
    $accountType = AccountType::from($value);
    echo "Тип аккаунта: {$accountType->getDisplayName()}\n";
} else {
    echo "Неизвестный тип аккаунта: {$value}\n";
}
```

## Получение всех значений

```php
use NotKinopoisk\Enums\AccountType;

// Получение всех типов аккаунтов
$allTypes = AccountType::cases();

foreach ($allTypes as $type) {
    echo "{$type->value} => {$type->getDisplayName()}\n";
    echo "  Бесплатный: " . ($type->isFree() ? 'Да' : 'Нет') . "\n";
    echo "  Безлимитный: " . ($type->isUnlimited() ? 'Да' : 'Нет') . "\n";
    echo "\n";
}
```

## Обработка ошибок

```php
use NotKinopoisk\Enums\AccountType;

try {
    // Попытка создания из несуществующего значения
    $accountType = AccountType::from('INVALID');
} catch (\ValueError $e) {
    echo "Ошибка: Неизвестный тип аккаунта\n";
}

// Безопасное получение значения
$accountType = AccountType::tryFrom('FREE');
if ($accountType) {
    echo "Тип аккаунта найден: {$accountType->getDisplayName()}\n";
} else {
    echo "Тип аккаунта не найден\n";
}
```

## Пример полного использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Enums\AccountType;

// Создание типа аккаунта
$accountType = AccountType::PAID;

// Получение информации
echo "Значение: {$accountType->value}\n";
echo "Название: {$accountType->getDisplayName()}\n";
echo "Бесплатный: " . ($accountType->isFree() ? 'Да' : 'Нет') . "\n";
echo "Безлимитный: " . ($accountType->isUnlimited() ? 'Да' : 'Нет') . "\n";

// Сравнение
if ($accountType === AccountType::PAID) {
    echo "Это платный аккаунт\n";
}

// Использование в массиве
$accountTypes = [
    AccountType::FREE,
    AccountType::PAID,
    AccountType::UNLIMITED
];

echo "Типы аккаунтов:\n";
foreach ($accountTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}

// Фильтрация по типам
$freeAccounts = array_filter($accountTypes, fn($t) => $t->isFree());
$unlimitedAccounts = array_filter($accountTypes, fn($t) => $t->isUnlimited());

echo "Бесплатные аккаунты: " . count($freeAccounts) . "\n";
echo "Безлимитные аккаунты: " . count($unlimitedAccounts) . "\n";

// Проверка типа
$checkType = 'UNLIMITED';
$foundType = AccountType::tryFrom($checkType);

if ($foundType) {
    echo "Найден тип аккаунта: {$foundType->getDisplayName()}\n";
} else {
    echo "Тип аккаунта '{$checkType}' не найден\n";
}
```

## Связанные классы

- `ApiKeyInfo` - Модель информации об API ключе
- `ApiKeyQouta` - Модель квоты API ключа
- `UserService` - Сервис для работы с пользовательскими данными

## API Endpoints

Типы аккаунтов используются в следующих API endpoints:

- `/api/v1/user/info` - Информация о пользователе
- `/api/v1/user/quota` - Квота пользователя
- `/api/v1/user/limits` - Лимиты пользователя
