# UserService

Сервис для работы с пользовательскими данными в Kinopoisk API.

## Описание

`UserService` предоставляет методы для получения информации о пользователе и его API ключе, включая статистику использования и лимиты запросов.

### Основные возможности

- Получение информации об API ключе
- Просмотр статистики использования
- Проверка лимитов и квот
- Получение оценок пользователя

## Конструктор

```php
public function __construct(\NotKinopoisk\Client $client, string $apiVersion = 'v1.4')
```

### Параметры

- `$client` - Экземпляр клиента для выполнения HTTP запросов
- `$apiVersion` - Версия API (по умолчанию 'v1.4')

## Методы

### getApiKeyInfo()

Получает информацию об API ключе.

```php
public function getApiKeyInfo(string $apiKey): ApiKeyInfo
```

**API Endpoint:** `/api/v1/api_keys/{apiKey}`

#### Параметры

- `$apiKey` (string) - API ключ для проверки

#### Возвращаемое значение

- `ApiKeyInfo` - Информация об API ключе

#### Исключения

- `\NotKinopoisk\Exception\InvalidApiKeyException` - Если API ключ неверный
- `\NotKinopoisk\Exception\ApiException` - При других ошибках API

#### Пример использования

```php
$keyInfo = $userService->getApiKeyInfo('your-api-key');

echo "Тип аккаунта: {$keyInfo->accountType}\n";
echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}\n";
echo "Лимит запросов: {$keyInfo->getTotalQuotaValue()}\n";
echo "Осталось запросов: {$keyInfo->getRemainingQuota()}\n";
```

### getVotes()

Получает оценки пользователя.

```php
public function getVotes(int $userId, int $page = 1): PaginatedResponse
```

**API Endpoint:** `/api/v1/kp_users/{userId}/votes`

#### Параметры

- `$userId` (int) - ID пользователя
- `$page` (int) - Номер страницы для пагинации (по умолчанию 1)

#### Возвращаемое значение

- `PaginatedResponse` - Пагинированный список оценок пользователя

#### Исключения

- `\NotKinopoisk\Exception\ApiException` - При ошибках API
- `\NotKinopoisk\Exception\ResourceNotFoundException` - Если пользователь не найден

#### Пример использования

```php
$votes = $userService->getVotes(12345, 1);
echo "Всего оценок: {$votes->total}\n";

foreach ($votes->items as $vote) {
    echo "Фильм: {$vote->filmName}, Оценка: {$vote->rating}\n";
}
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Services\UserService;

// Создание клиента
$client = new Client('your-api-key');

// Получение сервиса пользователя
$userService = $client->users;

// Получение информации об API ключе
try {
    $keyInfo = $userService->getApiKeyInfo('your-api-key');

    echo "=== Информация об API ключе ===\n";
    echo "Тип аккаунта: {$keyInfo->accountType}\n";
    echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}\n";
    echo "Лимит запросов: {$keyInfo->getTotalQuotaValue()}\n";
    echo "Осталось запросов: {$keyInfo->getRemainingQuota()}\n";
    echo "Использовано сегодня: {$keyInfo->dailyQuota->used}\n";
    echo "Осталось сегодня: {$keyInfo->getRemainingDailyQuota()}\n";

} catch (\NotKinopoisk\Exception\InvalidApiKeyException $e) {
    echo "Ошибка: Неверный API ключ\n";
} catch (\NotKinopoisk\Exception\ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
}

// Получение оценок пользователя (если известен ID)
try {
    $votes = $userService->getVotes(12345, 1);

    echo "\n=== Оценки пользователя ===\n";
    echo "Всего оценок: {$votes->total}\n";
    echo "Страница {$votes->currentPage} из {$votes->totalPages}\n\n";

    foreach ($votes->items as $vote) {
        echo "Фильм: {$vote->filmName}\n";
        echo "Оценка: {$vote->rating}/10\n";
        echo "Дата: {$vote->date}\n";
        echo "---\n";
    }

} catch (\NotKinopoisk\Exception\ResourceNotFoundException $e) {
    echo "Пользователь не найден\n";
} catch (\NotKinopoisk\Exception\ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
}
```

## Мониторинг использования API

```php
// Функция для проверки лимитов API
function checkApiLimits(UserService $userService, string $apiKey): void {
    try {
        $keyInfo = $userService->getApiKeyInfo($apiKey);

        $remainingTotal = $keyInfo->getRemainingQuota();
        $remainingDaily = $keyInfo->getRemainingDailyQuota();

        echo "Осталось запросов (всего): {$remainingTotal}\n";
        echo "Осталось запросов (сегодня): {$remainingDaily}\n";

        if ($remainingDaily < 10) {
            echo "⚠️  Внимание: Осталось мало запросов на сегодня!\n";
        }

        if ($remainingTotal < 100) {
            echo "⚠️  Внимание: Осталось мало запросов в общем!\n";
        }

    } catch (\Exception $e) {
        echo "Ошибка при проверке лимитов: {$e->getMessage()}\n";
    }
}

// Использование
checkApiLimits($userService, 'your-api-key');
```

## Связанные классы

- [`ApiKeyInfo`](../models/api-key-info.md) - Информация об API ключе
- [`UserVote`](../models/user-vote.md) - Оценка пользователя
- [`PaginatedResponse`](../responses/paginated-response.md) - Пагинированный ответ
- [`AccountType`](../enums/account-type.md) - Типы аккаунтов
