# RateLimitException

Исключение для превышения лимитов запросов к API.

## Описание

`RateLimitException` выбрасывается при превышении дневного, месячного лимита запросов или лимита запросов в секунду. Соответствует HTTP статус кодам 402 (Payment Required) и 429 (Too Many Requests).

## Наследование

```php
RateLimitException extends ApiException
```

## Конструктор

```php
public function __construct(string $message = 'Превышен лимит запросов', int $code = 429)
```

### Параметры

- `$message` (string) - Сообщение об ошибке (по умолчанию стандартное)
- `$code` (int) - Код ошибки (по умолчанию 429 - Too Many Requests)

## Примеры использования

### Базовое использование

```php
try {
    // Множественные запросы в цикле
    for ($i = 0; $i < 1000; $i++) {
        $client->films->getById($i);
    }
} catch (RateLimitException $e) {
    echo "Превышен лимит запросов: " . $e->getMessage();
    // Рекомендуется добавить задержку перед повторными запросами
}
```

### Создание исключения

```php
throw new RateLimitException('Превышен дневной лимит запросов', 402);
```

### Обработка в приложении

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ApiException;

function makeApiRequest(Client $client, callable $request, int $maxRetries = 3): mixed {
    $retryCount = 0;

    while ($retryCount < $maxRetries) {
        try {
            return $request();

        } catch (RateLimitException $e) {
            $retryCount++;

            echo "⚠️  Превышен лимит запросов (попытка {$retryCount}/{$maxRetries})\n";
            echo "Ошибка: {$e->getMessage()}\n";
            echo "Код: {$e->getCode()}\n";

            if ($retryCount < $maxRetries) {
                $delay = pow(2, $retryCount) * 1000; // Экспоненциальная задержка
                echo "⏳ Ожидание {$delay}мс перед повторной попыткой...\n";
                usleep($delay * 1000);
            } else {
                echo "❌ Превышено максимальное количество попыток\n";
                throw $e;
            }

        } catch (ApiException $e) {
            echo "❌ Ошибка API: {$e->getMessage()}\n";
            throw $e;
        }
    }
}

// Использование
$client = new Client('your-api-key');

try {
    $result = makeApiRequest($client, function() use ($client) {
        return $client->films->getById(301);
    });

    echo "✅ Запрос выполнен успешно\n";

} catch (RateLimitException $e) {
    echo "❌ Не удалось выполнить запрос из-за лимитов\n";
}
```

### Мониторинг лимитов

```php
class RateLimitMonitor {
    private Client $client;
    private array $requestCounts = [];

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function checkLimits(): array {
        try {
            $userService = $this->client->users;
            $keyInfo = $userService->getApiKeyInfo($this->client->getApiKey());

            return [
                'remainingTotal' => $keyInfo->getRemainingQuota(),
                'remainingDaily' => $keyInfo->getRemainingDailyQuota(),
                'usedTotal' => $keyInfo->getTotalQuotaUsed(),
                'usedDaily' => $keyInfo->dailyQuota->used,
                'accountType' => $keyInfo->accountType->value
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'remainingTotal' => 0,
                'remainingDaily' => 0
            ];
        }
    }

    public function isNearLimit(int $threshold = 10): bool {
        $limits = $this->checkLimits();

        return $limits['remainingDaily'] <= $threshold ||
               $limits['remainingTotal'] <= $threshold;
    }

    public function makeRequest(callable $request): mixed {
        // Проверка лимитов перед запросом
        if ($this->isNearLimit()) {
            echo "⚠️  Внимание: Осталось мало запросов!\n";
            $limits = $this->checkLimits();
            echo "Осталось сегодня: {$limits['remainingDaily']}\n";
            echo "Осталось всего: {$limits['remainingTotal']}\n";
        }

        try {
            $result = $request();

            // Обновление счетчика запросов
            $this->incrementRequestCount();

            return $result;

        } catch (RateLimitException $e) {
            echo "❌ Превышен лимит запросов\n";
            $this->handleRateLimit($e);
            throw $e;
        }
    }

    private function incrementRequestCount(): void {
        $today = date('Y-m-d');
        if (!isset($this->requestCounts[$today])) {
            $this->requestCounts[$today] = 0;
        }
        $this->requestCounts[$today]++;
    }

    private function handleRateLimit(RateLimitException $e): void {
        echo "🔄 Стратегия обработки превышения лимитов:\n";

        switch ($e->getCode()) {
            case 402:
                echo "- Превышен месячный лимит\n";
                echo "- Рекомендуется обновить план подписки\n";
                break;
            case 429:
                echo "- Превышен лимит запросов в секунду\n";
                echo "- Рекомендуется добавить задержку между запросами\n";
                break;
            default:
                echo "- Неизвестный тип превышения лимита\n";
        }
    }
}

// Использование
$monitor = new RateLimitMonitor($client);

// Проверка текущих лимитов
$limits = $monitor->checkLimits();
echo "=== Текущие лимиты ===\n";
echo "Осталось запросов (всего): {$limits['remainingTotal']}\n";
echo "Осталось запросов (сегодня): {$limits['remainingDaily']}\n";
echo "Тип аккаунта: {$limits['accountType']}\n";

// Выполнение запроса с мониторингом
try {
    $film = $monitor->makeRequest(function() use ($client) {
        return $client->films->getById(301);
    });

    echo "✅ Фильм получен: {$film->getDisplayName()}\n";

} catch (RateLimitException $e) {
    echo "❌ Не удалось получить фильм\n";
}
```

### Стратегии обработки лимитов

```php
class RateLimitStrategy {
    private Client $client;
    private int $requestDelay = 100; // миллисекунды

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function setRequestDelay(int $delay): void {
        $this->requestDelay = $delay;
    }

    public function makeBatchRequests(array $requests, int $batchSize = 10): array {
        $results = [];
        $batches = array_chunk($requests, $batchSize);

        foreach ($batches as $batchIndex => $batch) {
            echo "Обработка батча " . ($batchIndex + 1) . " из " . count($batches) . "\n";

            foreach ($batch as $request) {
                try {
                    $result = $this->makeRequestWithRetry($request);
                    $results[] = $result;

                    // Задержка между запросами
                    usleep($this->requestDelay * 1000);

                } catch (RateLimitException $e) {
                    echo "Превышен лимит в батче " . ($batchIndex + 1) . "\n";
                    echo "Ожидание 60 секунд...\n";
                    sleep(60);

                    // Повторная попытка для текущего батча
                    $batchIndex--;
                    break;
                }
            }
        }

        return $results;
    }

    private function makeRequestWithRetry(callable $request, int $maxRetries = 3): mixed {
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                return $request();

            } catch (RateLimitException $e) {
                $retryCount++;

                if ($retryCount < $maxRetries) {
                    $delay = pow(2, $retryCount) * 5; // 5, 10, 20 секунд
                    echo "Повторная попытка через {$delay} секунд...\n";
                    sleep($delay);
                } else {
                    throw $e;
                }
            }
        }
    }
}

// Использование
$strategy = new RateLimitStrategy($client);
$strategy->setRequestDelay(200); // 200мс между запросами

// Создание массива запросов
$requests = [];
for ($i = 1; $i <= 50; $i++) {
    $requests[] = function() use ($client, $i) {
        return $client->films->getById($i);
    };
}

// Выполнение батчевых запросов
try {
    $results = $strategy->makeBatchRequests($requests, 5);
    echo "✅ Успешно обработано " . count($results) . " запросов\n";

} catch (RateLimitException $e) {
    echo "❌ Не удалось обработать все запросы\n";
}
```

## HTTP статус коды

- **402 Payment Required** - Превышен месячный лимит запросов
- **429 Too Many Requests** - Превышен лимит запросов в секунду

## Рекомендации по обработке

1. **Мониторинг лимитов** - Регулярно проверяйте оставшиеся запросы
2. **Экспоненциальная задержка** - Увеличивайте время ожидания при повторных попытках
3. **Батчевая обработка** - Группируйте запросы и добавляйте задержки между батчами
4. **Кэширование** - Сохраняйте результаты запросов для уменьшения нагрузки
5. **Планирование** - Распределяйте запросы равномерно в течение дня

## Связанные исключения

- [`ApiException`](api-exception.md) - Базовое исключение API
- [`InvalidApiKeyException`](invalid-api-key-exception.md) - Неверный API ключ
- [`ResourceNotFoundException`](resource-not-found-exception.md) - Ресурс не найден
