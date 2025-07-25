# ApiKeyInfo

## Описание

`ApiKeyInfo` - это модель информации об API ключе из Kinopoisk API. Представляет информацию о текущем API ключе, включая лимиты запросов, тип аккаунта и доступные возможности.

## Основные возможности

- Хранение информации об API ключе в неизменяемом виде
- Создание объекта из массива данных API
- Проверка лимитов и типа аккаунта
- Получение информации о доступных запросах
- Совместимость с Kinopoisk API

## Наследование

```php
NotKinopoisk\Interfaces\ModelInterface
└── NotKinopoisk\Models\ApiKeyInfo
```

## Конструктор

```php
public function __construct(
    public readonly ApiKeyQouta $totalQuota,
    public readonly ApiKeyQouta $dailyQuota,
    public readonly AccountType $accountType,
)
```

### Параметры

- `$totalQuota` (ApiKeyQouta) - Информация об общих лимитах запросов
- `$dailyQuota` (ApiKeyQouta) - Информация о дневных лимитах запросов
- `$accountType` (AccountType) - Тип аккаунта (FREE, PAID, UNLIMITED)

## Свойства

### totalQuota

```php
public readonly ApiKeyQouta $totalQuota
```

**Описание:** Информация об общих лимитах запросов

**Тип:** `ApiKeyQouta`

**Доступ:** Только для чтения

**Пример:**

```php
$totalQuota = $apiKeyInfo->totalQuota;
echo "Общий лимит: {$totalQuota->value}\n";
echo "Использовано: {$totalQuota->used}\n";
```

### dailyQuota

```php
public readonly ApiKeyQouta $dailyQuota
```

**Описание:** Информация о дневных лимитах запросов

**Тип:** `ApiKeyQouta`

**Доступ:** Только для чтения

**Пример:**

```php
$dailyQuota = $apiKeyInfo->dailyQuota;
echo "Дневной лимит: {$dailyQuota->value}\n";
echo "Использовано сегодня: {$dailyQuota->used}\n";
```

### accountType

```php
public readonly AccountType $accountType
```

**Описание:** Тип аккаунта (FREE, PAID, UNLIMITED)

**Тип:** `AccountType`

**Доступ:** Только для чтения

**Пример:**

```php
$accountType = $apiKeyInfo->accountType;
echo "Тип аккаунта: {$accountType->getDisplayName()}\n";
```

## Статические методы

### fromArray()

```php
public static function fromArray(array $data): self
```

Создает экземпляр информации об API ключе из массива данных API.

#### Параметры

- `$data` (array) - Массив данных информации об API ключе от API

#### Возвращает

- `self` - Новый экземпляр информации об API ключе

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
use NotKinopoisk\Models\ApiKeyInfo;

$apiData = [
    'totalQuota' => ['total' => 1000, 'used' => 150],
    'dailyQuota' => ['total' => 100, 'used' => 25],
    'accountType' => 'FREE'
];

$apiKeyInfo = ApiKeyInfo::fromArray($apiData);
```

## Методы экземпляра

### isUnlimited()

```php
public function isUnlimited(): bool
```

Проверяет, является ли аккаунт безлимитным.

#### Возвращает

- `bool` - true если аккаунт безлимитный, false в противном случае

#### Пример использования

```php
use NotKinopoisk\Models\ApiKeyInfo;

if ($apiKeyInfo->isUnlimited()) {
    echo "Безлимитный аккаунт - можно делать неограниченное количество запросов";
}
```

### getRemainingTotalQuota()

```php
public function getRemainingTotalQuota(): int
```

Получает количество оставшихся общих запросов.

#### Возвращает

- `int` - Количество оставшихся запросов

#### Пример использования

```php
use NotKinopoisk\Models\ApiKeyInfo;

$remaining = $apiKeyInfo->getRemainingTotalQuota();
echo "Осталось запросов: {$remaining}";
```

### getRemainingDailyQuota()

```php
public function getRemainingDailyQuota(): int
```

Получает количество оставшихся дневных запросов.

#### Возвращает

- `int` - Количество оставшихся дневных запросов

#### Пример использования

```php
use NotKinopoisk\Models\ApiKeyInfo;

$remaining = $apiKeyInfo->getRemainingDailyQuota();
echo "Осталось дневных запросов: {$remaining}";
```

### toArray()

```php
public function toArray(): array
```

Преобразует объект информации об API ключе в массив.

#### Возвращает

- `array` - Массив данных информации об API ключе

#### Пример использования

```php
use NotKinopoisk\Models\ApiKeyInfo;

$array = $apiKeyInfo->toArray();
// [
//     'totalQuota' => ['total' => 1000, 'used' => 150],
//     'dailyQuota' => ['total' => 100, 'used' => 25],
//     'accountType' => 'FREE'
// ]
```

## Примеры использования

### Создание объекта

```php
use NotKinopoisk\Models\ApiKeyInfo;
use NotKinopoisk\Models\ApiKeyQouta;
use NotKinopoisk\Enums\AccountType;

// Создание квот
$totalQuota = new ApiKeyQouta(1000, 150);
$dailyQuota = new ApiKeyQouta(100, 25);

// Создание информации об API ключе
$apiKeyInfo = new ApiKeyInfo(
    totalQuota: $totalQuota,
    dailyQuota: $dailyQuota,
    accountType: AccountType::FREE
);
```

### Создание из данных API

```php
use NotKinopoisk\Models\ApiKeyInfo;

// Данные от API
$apiData = [
    'totalQuota' => ['value' => 1000, 'used' => 150],
    'dailyQuota' => ['value' => 100, 'used' => 25],
    'accountType' => 'FREE'
];

$apiKeyInfo = ApiKeyInfo::fromArray($apiData);
```

### Работа с информацией

```php
use NotKinopoisk\Models\ApiKeyInfo;

// Получение информации
echo "Тип аккаунта: {$apiKeyInfo->accountType->getDisplayName()}\n";
echo "Всего запросов: {$apiKeyInfo->totalQuota->value}\n";
echo "Использовано: {$apiKeyInfo->totalQuota->used}\n";
echo "Осталось: {$apiKeyInfo->getRemainingTotalQuota()}\n";

// Проверка типа аккаунта
if ($apiKeyInfo->accountType->isUnlimited()) {
    echo "Безлимитный аккаунт!";
} elseif ($apiKeyInfo->accountType->isFree()) {
    echo "Бесплатный аккаунт";
} else {
    echo "Платный аккаунт";
}
```

### Проверка лимитов

```php
use NotKinopoisk\Models\ApiKeyInfo;

// Проверка общих лимитов
$remainingTotal = $apiKeyInfo->getRemainingTotalQuota();
if ($remainingTotal <= 0) {
    echo "Общий лимит запросов исчерпан!";
} else {
    echo "Осталось общих запросов: {$remainingTotal}";
}

// Проверка дневных лимитов
$remainingDaily = $apiKeyInfo->getRemainingDailyQuota();
if ($remainingDaily <= 0) {
    echo "Дневной лимит запросов исчерпан!";
} else {
    echo "Осталось дневных запросов: {$remainingDaily}";
}
```

### Использование в сервисах

```php
use NotKinopoisk\Models\ApiKeyInfo;
use NotKinopoisk\Services\UserService;

class UserService extends AbstractService
{
    public function getApiKeyInfo(): ApiKeyInfo
    {
        $response = $this->client->get('/api/v1/api_keys/info');
        return ApiKeyInfo::fromArray($response);
    }

    public function checkQuota(): void
    {
        $apiKeyInfo = $this->getApiKeyInfo();

        if ($apiKeyInfo->isUnlimited()) {
            return; // Безлимитный аккаунт
        }

        $remainingTotal = $apiKeyInfo->getRemainingTotalQuota();
        $remainingDaily = $apiKeyInfo->getRemainingDailyQuota();

        if ($remainingTotal <= 0) {
            throw new \Exception('Общий лимит запросов исчерпан');
        }

        if ($remainingDaily <= 0) {
            throw new \Exception('Дневной лимит запросов исчерпан');
        }
    }
}
```

### Мониторинг использования

```php
use NotKinopoisk\Models\ApiKeyInfo;

function monitorApiUsage(ApiKeyInfo $apiKeyInfo): void
{
    echo "=== Информация об API ключе ===\n";
    echo "Тип аккаунта: {$apiKeyInfo->accountType->getDisplayName()}\n";
    echo "\n";

    echo "Общие лимиты:\n";
    echo "  Всего: {$apiKeyInfo->totalQuota->value}\n";
    echo "  Использовано: {$apiKeyInfo->totalQuota->used}\n";
    echo "  Осталось: {$apiKeyInfo->getRemainingTotalQuota()}\n";
    echo "\n";

    echo "Дневные лимиты:\n";
    echo "  Всего: {$apiKeyInfo->dailyQuota->value}\n";
    echo "  Использовано: {$apiKeyInfo->dailyQuota->used}\n";
    echo "  Осталось: {$apiKeyInfo->getRemainingDailyQuota()}\n";
    echo "\n";

    // Проверка статуса
    if ($apiKeyInfo->isUnlimited()) {
        echo "✅ Безлимитный аккаунт - ограничений нет\n";
    } else {
        $totalRemaining = $apiKeyInfo->getRemainingTotalQuota();
        $dailyRemaining = $apiKeyInfo->getRemainingDailyQuota();

        if ($totalRemaining <= 0) {
            echo "❌ Общий лимит исчерпан!\n";
        } elseif ($dailyRemaining <= 0) {
            echo "❌ Дневной лимит исчерпан!\n";
        } else {
            echo "✅ Лимиты в порядке\n";
        }
    }
}
```

### Сериализация и десериализация

```php
use NotKinopoisk\Models\ApiKeyInfo;

// Преобразование в массив
$array = $apiKeyInfo->toArray();

// Сохранение в JSON
$json = json_encode($array, JSON_PRETTY_PRINT);
file_put_contents('api_key_info.json', $json);

// Загрузка из JSON
$loadedArray = json_decode(file_get_contents('api_key_info.json'), true);
$loadedApiKeyInfo = ApiKeyInfo::fromArray($loadedArray);
```

## Связанные классы

- `ApiKeyQouta` - Модель квоты API ключа
- `AccountType` - Перечисление типов аккаунтов
- `UserService` - Сервис для работы с пользовательскими данными
- `ModelInterface` - Интерфейс модели

## API Endpoints

Информация об API ключе используется в следующих API endpoints:

- `/api/v1/api_keys/{apiKey}` - Информация об API ключе
- `/api/v1/user/info` - Информация о пользователе
- `/api/v1/user/quota` - Квота пользователя
