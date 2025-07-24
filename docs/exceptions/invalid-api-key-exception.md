# InvalidApiKeyException

Исключение для неверного или отсутствующего API ключа.

## Описание

`InvalidApiKeyException` выбрасывается при попытке обращения к API с неверным, отсутствующим или заблокированным API ключом. Соответствует HTTP статус коду 401 (Unauthorized).

## Наследование

```php
InvalidApiKeyException extends ApiException
```

## Конструктор

```php
public function __construct(string $message = 'Неверный или отсутствующий API ключ', int $code = 401)
```

### Параметры

- `$message` (string) - Сообщение об ошибке (по умолчанию стандартное)
- `$code` (int) - Код ошибки (по умолчанию 401 - Unauthorized)

## Примеры использования

### Базовое использование

```php
try {
    $client = new Client('invalid-key');
    $client->films->getById(301);
} catch (InvalidApiKeyException $e) {
    echo "Проблема с API ключом: " . $e->getMessage();
    // Выведет: "Проблема с API ключом: Неверный или отсутствующий API ключ"
}
```

### Создание исключения

```php
throw new InvalidApiKeyException('API ключ заблокирован администратором');
```

### Обработка в приложении

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\ApiException;

function checkApiKey(string $apiKey): bool {
    try {
        $client = new Client($apiKey);
        $userService = $client->users;

        // Попытка получить информацию об API ключе
        $keyInfo = $userService->getApiKeyInfo($apiKey);

        echo "✅ API ключ действителен\n";
        echo "Тип аккаунта: {$keyInfo->accountType}\n";
        echo "Осталось запросов: {$keyInfo->getRemainingQuota()}\n";

        return true;

    } catch (InvalidApiKeyException $e) {
        echo "❌ Ошибка API ключа: {$e->getMessage()}\n";
        echo "Код ошибки: {$e->getCode()}\n";

        // Рекомендации пользователю
        echo "\n💡 Рекомендации:\n";
        echo "- Проверьте правильность API ключа\n";
        echo "- Убедитесь, что ключ не заблокирован\n";
        echo "- Получите новый ключ на https://kinopoiskapiunofficial.tech\n";

        return false;

    } catch (ApiException $e) {
        echo "❌ Другая ошибка API: {$e->getMessage()}\n";
        return false;
    }
}

// Использование
$apiKey = $_ENV['KINOPOISK_API_KEY'] ?? '';
if (empty($apiKey)) {
    echo "❌ API ключ не указан в переменных окружения\n";
    exit(1);
}

if (!checkApiKey($apiKey)) {
    exit(1);
}
```

### Валидация API ключа

```php
class ApiKeyValidator {
    private Client $client;

    public function __construct(string $apiKey) {
        $this->client = new Client($apiKey);
    }

    public function validate(): array {
        try {
            $userService = $this->client->users;
            $keyInfo = $userService->getApiKeyInfo($this->client->getApiKey());

            return [
                'valid' => true,
                'accountType' => $keyInfo->accountType,
                'remainingQuota' => $keyInfo->getRemainingQuota(),
                'dailyQuota' => $keyInfo->getRemainingDailyQuota(),
                'message' => 'API ключ действителен'
            ];

        } catch (InvalidApiKeyException $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'suggestions' => [
                    'Проверьте правильность ключа',
                    'Убедитесь, что ключ активен',
                    'Получите новый ключ при необходимости'
                ]
            ];
        }
    }
}

// Использование
$validator = new ApiKeyValidator('your-api-key');
$result = $validator->validate();

if ($result['valid']) {
    echo "✅ {$result['message']}\n";
    echo "Тип аккаунта: {$result['accountType']}\n";
    echo "Осталось запросов: {$result['remainingQuota']}\n";
} else {
    echo "❌ {$result['error']}\n";
    echo "Код: {$result['code']}\n";
    echo "Рекомендации:\n";
    foreach ($result['suggestions'] as $suggestion) {
        echo "- {$suggestion}\n";
    }
}
```

### Обработка в middleware

```php
class ApiKeyMiddleware {
    private string $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function handle(callable $operation) {
        try {
            return $operation();

        } catch (InvalidApiKeyException $e) {
            // Логирование ошибки
            error_log("API Key Error: {$e->getMessage()}");

            // Возврат ошибки пользователю
            return [
                'success' => false,
                'error' => 'authentication_failed',
                'message' => 'Проблема с аутентификацией API',
                'details' => $e->getMessage()
            ];

        } catch (ApiException $e) {
            return [
                'success' => false,
                'error' => 'api_error',
                'message' => 'Ошибка API',
                'details' => $e->getMessage()
            ];
        }
    }
}

// Использование
$middleware = new ApiKeyMiddleware('your-api-key');

$result = $middleware->handle(function() {
    $client = new Client('your-api-key');
    return $client->films->getById(301);
});

if (!$result['success']) {
    echo "Ошибка: {$result['message']}\n";
    echo "Детали: {$result['details']}\n";
} else {
    echo "Фильм: " . $result->getDisplayName() . "\n";
}
```

## HTTP статус коды

- **401 Unauthorized** - Неверный или отсутствующий API ключ
- **403 Forbidden** - API ключ заблокирован или превышены лимиты

## Рекомендации по обработке

1. **Проверка ключа** - Всегда валидируйте API ключ перед началом работы
2. **Логирование** - Логируйте ошибки аутентификации для отладки
3. **Пользовательские сообщения** - Предоставляйте понятные сообщения пользователям
4. **Резервные планы** - Имейте план действий при проблемах с API ключом

## Связанные исключения

- [`ApiException`](api-exception.md) - Базовое исключение API
- [`RateLimitException`](rate-limit-exception.md) - Превышение лимитов запросов
- [`ResourceNotFoundException`](resource-not-found-exception.md) - Ресурс не найден
