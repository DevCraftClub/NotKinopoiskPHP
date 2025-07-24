# ResourceNotFoundException

Исключение для ненайденных ресурсов в API.

## Описание

`ResourceNotFoundException` выбрасывается при попытке обращения к несуществующему ресурсу (фильму, персоне, сериалу и т.д.). Соответствует HTTP статус коду 404 (Not Found).

### Основные возможности

- Специализированное исключение для не найденных ресурсов
- Предустановленный код ошибки 404
- Стандартное сообщение об ошибке
- Наследование от базового ApiException

## Наследование

Наследует от [`ApiException`](api-exception.md).

## Конструктор

```php
public function __construct(
    string $message = 'Запрашиваемый ресурс не найден',
    int $code = 404
)
```

### Параметры

- `$message` (string) - Сообщение об ошибке (по умолчанию стандартное)
- `$code` (int) - Код ошибки (по умолчанию 404 - Not Found)

### Пример использования

```php
throw new ResourceNotFoundException('Фильм с ID 12345 не существует');
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ResourceNotFoundException;

// Создание клиента
$client = new Client('your-api-key');

echo "=== Работа с ResourceNotFoundException ===\n";

// Пример обработки не найденных ресурсов
try {
    // Попытка получить несуществующий фильм
    $film = $client->films->getById(999999999);
    echo "Фильм получен: {$film->getDisplayName()}\n";
} catch (ResourceNotFoundException $e) {
    echo "Ресурс не найден: {$e->getMessage()}\n";
    echo "Код ошибки: {$e->getCode()}\n";
    echo "Файл: {$e->getFile()}\n";
    echo "Строка: {$e->getLine()}\n";
}

// Пример с пользовательским сообщением
try {
    // Попытка получить несуществующую персону
    $person = $client->persons->getById(999999999);
    echo "Персона получена: {$person->getDisplayName()}\n";
} catch (ResourceNotFoundException $e) {
    echo "Персона не найдена: {$e->getMessage()}\n";
}

// Пример создания пользовательского исключения
try {
    throw new ResourceNotFoundException('Фильм с ID 12345 не существует');
} catch (ResourceNotFoundException $e) {
    echo "\nПоймано пользовательское исключение:\n";
    echo "Сообщение: {$e->getMessage()}\n";
    echo "Код: {$e->getCode()}\n";
}

// Пример с цепочкой исключений
try {
    $previousException = new Exception('Ошибка сети');
    throw new ResourceNotFoundException('Фильм не найден в базе данных', 404, $previousException);
} catch (ResourceNotFoundException $e) {
    echo "\nИсключение с цепочкой:\n";
    echo "Сообщение: {$e->getMessage()}\n";
    echo "Код: {$e->getCode()}\n";

    if ($e->getPrevious()) {
        echo "Причина: " . $e->getPrevious()->getMessage() . "\n";
    }
}
```

## Обработка не найденных ресурсов

```php
// Функция для безопасного получения ресурса с обработкой ошибок
function safeGetResource(callable $apiCall, string $resourceType = 'ресурс'): mixed {
    try {
        return $apiCall();
    } catch (ResourceNotFoundException $e) {
        logResourceNotFound($e, $resourceType);
        return null;
    }
}

// Функция для логирования не найденных ресурсов
function logResourceNotFound(ResourceNotFoundException $e, string $resourceType): void {
    $logMessage = sprintf(
        "[%s] Resource Not Found: %s - %s (Code: %d) in %s:%d",
        date('Y-m-d H:i:s'),
        $resourceType,
        $e->getMessage(),
        $e->getCode(),
        $e->getFile(),
        $e->getLine()
    );

    error_log($logMessage);
}

// Функция для получения детальной информации об ошибке
function getResourceNotFoundDetails(ResourceNotFoundException $e): array {
    return [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
        'previous' => $e->getPrevious() ? [
            'message' => $e->getPrevious()->getMessage(),
            'code' => $e->getPrevious()->getCode(),
            'class' => get_class($e->getPrevious())
        ] : null
    ];
}

// Функция для создания пользовательского сообщения об ошибке
function createResourceNotFoundMessage(ResourceNotFoundException $e, string $resourceType = 'ресурс'): string {
    return "Запрашиваемый {$resourceType} не найден. Пожалуйста, проверьте правильность идентификатора.";
}

// Функция для проверки существования ресурса
function checkResourceExists(callable $apiCall): bool {
    try {
        $apiCall();
        return true;
    } catch (ResourceNotFoundException $e) {
        return false;
    }
}

// Функция для получения альтернативных ресурсов
function getAlternativeResources(callable $apiCall, callable $fallbackCall): mixed {
    try {
        return $apiCall();
    } catch (ResourceNotFoundException $e) {
        // Попытка получить альтернативный ресурс
        try {
            return $fallbackCall();
        } catch (ResourceNotFoundException $fallbackException) {
            // Если и альтернативный ресурс не найден
            throw $e; // Возвращаем оригинальное исключение
        }
    }
}

// Использование
$client = new Client('your-api-key');

// Безопасное получение фильма
$film = safeGetResource(
    fn() => $client->films->getById(123),
    'фильм'
);

if ($film === null) {
    echo "Фильм не найден\n";
}

// Проверка существования ресурса
$exists = checkResourceExists(fn() => $client->films->getById(123));
echo "Фильм существует: " . ($exists ? 'Да' : 'Нет') . "\n";

// Получение альтернативного ресурса
try {
    $film = getAlternativeResources(
        fn() => $client->films->getById(999999), // Основной запрос
        fn() => $client->films->getById(123)     // Альтернативный запрос
    );
    echo "Получен фильм: {$film->getDisplayName()}\n";
} catch (ResourceNotFoundException $e) {
    echo "Ни основной, ни альтернативный фильм не найдены\n";
}

// Детальная обработка ошибки
try {
    $client->films->getById(999999);
} catch (ResourceNotFoundException $e) {
    $errorDetails = getResourceNotFoundDetails($e);
    $userMessage = createResourceNotFoundMessage($e, 'фильм');

    echo "Пользовательское сообщение: {$userMessage}\n";
    echo "Детали ошибки:\n";
    print_r($errorDetails);
}
```

## Создание отчета о не найденных ресурсах

```php
class ResourceNotFoundReport {
    private ResourceNotFoundException $exception;
    private string $resourceType;
    private string $resourceId;

    public function __construct(ResourceNotFoundException $exception, string $resourceType = 'Unknown', string $resourceId = 'Unknown') {
        $this->exception = $exception;
        $this->resourceType = $resourceType;
        $this->resourceId = $resourceId;
    }

    public function getException(): ResourceNotFoundException {
        return $this->exception;
    }

    public function getResourceType(): string {
        return $this->resourceType;
    }

    public function getResourceId(): string {
        return $this->resourceId;
    }

    public function getErrorDetails(): array {
        return getResourceNotFoundDetails($this->exception);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ О НЕ НАЙДЕННОМ РЕСУРСЕ ===\n\n";

        $errorDetails = $this->getErrorDetails();

        // Основная информация
        $report .= "🔍 ОСНОВНАЯ ИНФОРМАЦИЯ:\n";
        $report .= "Тип ресурса: {$this->resourceType}\n";
        $report .= "ID ресурса: {$this->resourceId}\n";
        $report .= "Сообщение: {$errorDetails['message']}\n";
        $report .= "Код ошибки: {$errorDetails['code']}\n";
        $report .= "Файл: {$errorDetails['file']}\n";
        $report .= "Строка: {$errorDetails['line']}\n\n";

        // Предыдущее исключение
        if ($errorDetails['previous']) {
            $report .= "🔗 ПРЕДЫДУЩЕЕ ИСКЛЮЧЕНИЕ:\n";
            $report .= "Класс: {$errorDetails['previous']['class']}\n";
            $report .= "Сообщение: {$errorDetails['previous']['message']}\n";
            $report .= "Код: {$errorDetails['previous']['code']}\n\n";
        }

        // Пользовательское сообщение
        $report .= "💡 ПОЛЬЗОВАТЕЛЬСКОЕ СООБЩЕНИЕ:\n";
        $report .= createResourceNotFoundMessage($this->exception, $this->resourceType) . "\n\n";

        // Рекомендации
        $report .= "💡 РЕКОМЕНДАЦИИ:\n";
        $report .= "• Проверьте правильность ID ресурса\n";
        $report .= "• Убедитесь, что ресурс существует в базе данных\n";
        $report .= "• Попробуйте поиск по названию вместо ID\n";
        $report .= "• Проверьте права доступа к ресурсу\n\n";

        // Стек вызовов
        $report .= "📋 СТЕК ВЫЗОВОВ:\n";
        $report .= $errorDetails['trace'] . "\n";

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".error-info { background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; padding: 15px; margin-bottom: 20px; }\n";
        $html .= ".error-message { color: #721c24; font-weight: bold; }\n";
        $html .= ".error-code { background-color: #e2e3e5; padding: 5px 10px; border-radius: 3px; display: inline-block; margin: 5px; }\n";
        $html .= ".trace { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; font-family: monospace; white-space: pre-wrap; font-size: 12px; }\n";
        $html .= ".user-message { background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; padding: 15px; margin: 15px 0; }\n";
        $html .= ".recommendations { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 15px 0; }\n";
        $html .= ".resource-info { background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; margin: 15px 0; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $errorDetails = $this->getErrorDetails();

        // Информация о ресурсе
        $html .= "<div class='resource-info'>\n";
        $html .= "<h2>Информация о ресурсе</h2>\n";
        $html .= "<p><strong>Тип ресурса:</strong> {$this->resourceType}</p>\n";
        $html .= "<p><strong>ID ресурса:</strong> {$this->resourceId}</p>\n";
        $html .= "</div>\n";

        // Основная информация об ошибке
        $html .= "<div class='error-info'>\n";
        $html .= "<h2>Основная информация</h2>\n";
        $html .= "<p><strong>Сообщение:</strong> <span class='error-message'>{$errorDetails['message']}</span></p>\n";
        $html .= "<p><strong>Код ошибки:</strong> <span class='error-code'>{$errorDetails['code']}</span></p>\n";
        $html .= "<p><strong>Файл:</strong> {$errorDetails['file']}</p>\n";
        $html .= "<p><strong>Строка:</strong> {$errorDetails['line']}</p>\n";
        $html .= "</div>\n";

        // Пользовательское сообщение
        $html .= "<div class='user-message'>\n";
        $html .= "<h3>💡 Пользовательское сообщение</h3>\n";
        $html .= "<p>" . createResourceNotFoundMessage($this->exception, $this->resourceType) . "</p>\n";
        $html .= "</div>\n";

        // Рекомендации
        $html .= "<div class='recommendations'>\n";
        $html .= "<h3>💡 Рекомендации</h3>\n";
        $html .= "<ul>\n";
        $html .= "<li>Проверьте правильность ID ресурса</li>\n";
        $html .= "<li>Убедитесь, что ресурс существует в базе данных</li>\n";
        $html .= "<li>Попробуйте поиск по названию вместо ID</li>\n";
        $html .= "<li>Проверьте права доступа к ресурсу</li>\n";
        $html .= "</ul>\n";
        $html .= "</div>\n";

        // Предыдущее исключение
        if ($errorDetails['previous']) {
            $html .= "<div class='section'>\n";
            $html .= "<div class='section-title'>🔗 Предыдущее исключение</div>\n";
            $html .= "<p><strong>Класс:</strong> {$errorDetails['previous']['class']}</p>\n";
            $html .= "<p><strong>Сообщение:</strong> {$errorDetails['previous']['message']}</p>\n";
            $html .= "<p><strong>Код:</strong> {$errorDetails['previous']['code']}</p>\n";
            $html .= "</div>\n";
        }

        // Стек вызовов
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📋 Стек вызовов</div>\n";
        $html .= "<div class='trace'>" . htmlspecialchars($errorDetails['trace']) . "</div>\n";
        $html .= "</div>\n";

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
try {
    $client->films->getById(999999);
} catch (ResourceNotFoundException $e) {
    $report = new ResourceNotFoundReport($e, 'фильм', '999999');

    // Создание текстового отчета
    $textReport = $report->createDetailedReport();
    echo $textReport;

    // Создание HTML отчета
    $htmlReport = $report->createHtmlReport('Отчет о не найденном ресурсе');
    file_put_contents('resource_not_found_report.html', $htmlReport);
    echo "\n✅ HTML отчет сохранен в resource_not_found_report.html\n";
}
```

## Анализ не найденных ресурсов

```php
function analyzeResourceNotFoundErrors(array $exceptions): array {
    $analysis = [
        'totalErrors' => count($exceptions),
        'resourceTypes' => [],
        'resourceIds' => [],
        'mostCommonErrors' => [],
        'errorPatterns' => []
    ];

    foreach ($exceptions as $exception) {
        if ($exception instanceof ResourceNotFoundException) {
            // Извлекаем информацию о ресурсе из сообщения (если возможно)
            $message = $exception->getMessage();

            // Простая логика извлечения типа ресурса
            $resourceType = 'Unknown';
            if (strpos($message, 'фильм') !== false) {
                $resourceType = 'Film';
            } elseif (strpos($message, 'персона') !== false) {
                $resourceType = 'Person';
            } elseif (strpos($message, 'сериал') !== false) {
                $resourceType = 'Series';
            }

            // Подсчет типов ресурсов
            $analysis['resourceTypes'][$resourceType] = ($analysis['resourceTypes'][$resourceType] ?? 0) + 1;

            // Подсчет сообщений об ошибках
            $analysis['mostCommonErrors'][$message] = ($analysis['mostCommonErrors'][$message] ?? 0) + 1;

            // Анализ паттернов ошибок
            $pattern = getErrorPattern($message);
            $analysis['errorPatterns'][$pattern] = ($analysis['errorPatterns'][$pattern] ?? 0) + 1;
        }
    }

    // Сортировка по частоте
    arsort($analysis['resourceTypes']);
    arsort($analysis['mostCommonErrors']);
    arsort($analysis['errorPatterns']);

    return $analysis;
}

// Функция для определения паттерна ошибки
function getErrorPattern(string $message): string {
    if (strpos($message, 'не найден') !== false) {
        return 'Resource Not Found';
    } elseif (strpos($message, 'не существует') !== false) {
        return 'Resource Does Not Exist';
    } elseif (strpos($message, 'недоступен') !== false) {
        return 'Resource Unavailable';
    } else {
        return 'Unknown Pattern';
    }
}

// Использование
$exceptions = [
    new ResourceNotFoundException('Фильм не найден'),
    new ResourceNotFoundException('Персона не найдена'),
    new ResourceNotFoundException('Сериал не найден'),
    new ResourceNotFoundException('Фильм не найден'),
    new ResourceNotFoundException('Запрашиваемый ресурс не найден')
];

$analysis = analyzeResourceNotFoundErrors($exceptions);

echo "=== Анализ не найденных ресурсов ===\n";
echo "Всего ошибок: {$analysis['totalErrors']}\n\n";

echo "Типы ресурсов:\n";
foreach ($analysis['resourceTypes'] as $type => $count) {
    echo "- {$type}: {$count}\n";
}

echo "\nПаттерны ошибок:\n";
foreach ($analysis['errorPatterns'] as $pattern => $count) {
    echo "- {$pattern}: {$count}\n";
}

echo "\nЧастые ошибки:\n";
foreach (array_slice($analysis['mostCommonErrors'], 0, 5, true) as $message => $count) {
    echo "- '{$message}': {$count}\n";
}
```

## Связанные классы

- [`ApiException`](api-exception.md) - Базовое исключение для всех ошибок API
- [`InvalidApiKeyException`](invalid-api-key-exception.md) - Исключение для неверного API ключа
- [`RateLimitException`](rate-limit-exception.md) - Исключение для превышения лимита запросов
- [`KpValidationException`](kp-validation-exception.md) - Исключение для ошибок валидации
