# ApiException

Базовое исключение для всех ошибок, связанных с Kinopoisk API.

## Описание

`ApiException` является базовым классом для всех специфичных исключений API. Используется для обработки общих ошибок, которые не подходят под более специфичные категории исключений.

### Основные возможности

- Базовый класс для всех исключений API
- Поддержка цепочки исключений
- Коды ошибок (обычно HTTP статус коды)
- Детальные сообщения об ошибках

## Наследование

Наследует от стандартного класса `Exception` PHP.

## Конструктор

```php
public function __construct(
    string $message = '',
    int $code = 0,
    ?Throwable $previous = null
)
```

### Параметры

- `$message` (string) - Сообщение об ошибке, описывающее проблему с API
- `$code` (int) - Код ошибки (обычно HTTP статус код)
- `$previous` (Throwable|null) - Предыдущее исключение в цепочке

### Пример использования

```php
throw new ApiException('Ошибка сети при обращении к API', 500, $previousException);
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;

// Создание клиента
$client = new Client('your-api-key');

echo "=== Работа с ApiException ===\n";

// Пример обработки общих ошибок API
try {
    // Попытка получить несуществующий фильм
    $film = $client->films->getById(999999);
    echo "Фильм получен: {$film->getDisplayName()}\n";
} catch (ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
    echo "Код ошибки: {$e->getCode()}\n";
    echo "Файл: {$e->getFile()}\n";
    echo "Строка: {$e->getLine()}\n";

    if ($e->getPrevious()) {
        echo "Предыдущее исключение: " . $e->getPrevious()->getMessage() . "\n";
    }
}

// Пример создания пользовательского исключения
try {
    // Симуляция ошибки сети
    throw new ApiException('Ошибка сети при обращении к API', 500);
} catch (ApiException $e) {
    echo "\nПоймано пользовательское исключение:\n";
    echo "Сообщение: {$e->getMessage()}\n";
    echo "Код: {$e->getCode()}\n";
}

// Пример с цепочкой исключений
try {
    $previousException = new Exception('Ошибка сети');
    throw new ApiException('Не удалось получить данные фильма', 404, $previousException);
} catch (ApiException $e) {
    echo "\nИсключение с цепочкой:\n";
    echo "Сообщение: {$e->getMessage()}\n";
    echo "Код: {$e->getCode()}\n";

    if ($e->getPrevious()) {
        echo "Причина: " . $e->getPrevious()->getMessage() . "\n";
    }
}
```

## Обработка исключений API

```php
// Функция для безопасного выполнения API запросов
function safeApiCall(callable $apiCall, string $operation = 'API операция'): mixed {
    try {
        return $apiCall();
    } catch (ApiException $e) {
        logApiError($e, $operation);
        return null;
    }
}

// Функция для логирования ошибок API
function logApiError(ApiException $e, string $operation): void {
    $logMessage = sprintf(
        "[%s] API Error in %s: %s (Code: %d) in %s:%d",
        date('Y-m-d H:i:s'),
        $operation,
        $e->getMessage(),
        $e->getCode(),
        $e->getFile(),
        $e->getLine()
    );

    error_log($logMessage);

    if ($e->getPrevious()) {
        error_log("Previous exception: " . $e->getPrevious()->getMessage());
    }
}

// Функция для получения детальной информации об ошибке
function getApiErrorDetails(ApiException $e): array {
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

// Функция для определения типа ошибки по коду
function getApiErrorType(int $code): string {
    return match (true) {
        $code >= 400 && $code < 500 => 'Client Error',
        $code >= 500 && $code < 600 => 'Server Error',
        $code === 0 => 'Network Error',
        default => 'Unknown Error'
    };
}

// Функция для создания пользовательского сообщения об ошибке
function createUserFriendlyMessage(ApiException $e): string {
    $errorType = getApiErrorType($e->getCode());

    return match ($errorType) {
        'Client Error' => 'Ошибка запроса. Проверьте параметры и попробуйте снова.',
        'Server Error' => 'Ошибка сервера. Попробуйте позже.',
        'Network Error' => 'Ошибка сети. Проверьте подключение к интернету.',
        default => 'Произошла неизвестная ошибка: ' . $e->getMessage()
    };
}

// Использование
$client = new Client('your-api-key');

// Безопасный вызов API
$film = safeApiCall(
    fn() => $client->films->getById(123),
    'получение фильма по ID'
);

if ($film === null) {
    echo "Не удалось получить фильм\n";
}

// Детальная обработка ошибки
try {
    $client->films->getById(999999);
} catch (ApiException $e) {
    $errorDetails = getApiErrorDetails($e);
    $userMessage = createUserFriendlyMessage($e);

    echo "Пользовательское сообщение: {$userMessage}\n";
    echo "Тип ошибки: " . getApiErrorType($e->getCode()) . "\n";
    echo "Детали ошибки:\n";
    print_r($errorDetails);
}
```

## Создание отчета об ошибках

```php
class ApiErrorReport {
    private ApiException $exception;
    private string $operation;

    public function __construct(ApiException $exception, string $operation = 'Unknown') {
        $this->exception = $exception;
        $this->operation = $operation;
    }

    public function getException(): ApiException {
        return $this->exception;
    }

    public function getOperation(): string {
        return $this->operation;
    }

    public function getErrorDetails(): array {
        return getApiErrorDetails($this->exception);
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ОБ ОШИБКЕ API ===\n\n";

        $errorDetails = $this->getErrorDetails();

        // Основная информация
        $report .= "🔍 ОСНОВНАЯ ИНФОРМАЦИЯ:\n";
        $report .= "Операция: {$this->operation}\n";
        $report .= "Сообщение: {$errorDetails['message']}\n";
        $report .= "Код ошибки: {$errorDetails['code']}\n";
        $report .= "Тип ошибки: " . getApiErrorType($errorDetails['code']) . "\n";
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
        $report .= createUserFriendlyMessage($this->exception) . "\n\n";

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
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $errorDetails = $this->getErrorDetails();

        // Основная информация об ошибке
        $html .= "<div class='error-info'>\n";
        $html .= "<h2>Основная информация</h2>\n";
        $html .= "<p><strong>Операция:</strong> {$this->operation}</p>\n";
        $html .= "<p><strong>Сообщение:</strong> <span class='error-message'>{$errorDetails['message']}</span></p>\n";
        $html .= "<p><strong>Код ошибки:</strong> <span class='error-code'>{$errorDetails['code']}</span></p>\n";
        $html .= "<p><strong>Тип ошибки:</strong> " . getApiErrorType($errorDetails['code']) . "</p>\n";
        $html .= "<p><strong>Файл:</strong> {$errorDetails['file']}</p>\n";
        $html .= "<p><strong>Строка:</strong> {$errorDetails['line']}</p>\n";
        $html .= "</div>\n";

        // Пользовательское сообщение
        $html .= "<div class='user-message'>\n";
        $html .= "<h3>💡 Пользовательское сообщение</h3>\n";
        $html .= "<p>" . createUserFriendlyMessage($this->exception) . "</p>\n";
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
} catch (ApiException $e) {
    $report = new ApiErrorReport($e, 'получение фильма по ID');

    // Создание текстового отчета
    $textReport = $report->createDetailedReport();
    echo $textReport;

    // Создание HTML отчета
    $htmlReport = $report->createHtmlReport('Отчет об ошибке API');
    file_put_contents('api_error_report.html', $htmlReport);
    echo "\n✅ HTML отчет сохранен в api_error_report.html\n";
}
```

## Анализ ошибок API

```php
function analyzeApiErrors(array $exceptions): array {
    $analysis = [
        'totalErrors' => count($exceptions),
        'errorTypes' => [],
        'errorCodes' => [],
        'operations' => [],
        'mostCommonErrors' => [],
        'errorTimeline' => []
    ];

    foreach ($exceptions as $exception) {
        if ($exception instanceof ApiException) {
            $errorType = getApiErrorType($exception->getCode());
            $operation = 'Unknown'; // Можно добавить логику определения операции

            // Подсчет типов ошибок
            $analysis['errorTypes'][$errorType] = ($analysis['errorTypes'][$errorType] ?? 0) + 1;

            // Подсчет кодов ошибок
            $analysis['errorCodes'][$exception->getCode()] = ($analysis['errorCodes'][$exception->getCode()] ?? 0) + 1;

            // Подсчет операций
            $analysis['operations'][$operation] = ($analysis['operations'][$operation] ?? 0) + 1;

            // Подсчет сообщений об ошибках
            $message = $exception->getMessage();
            $analysis['mostCommonErrors'][$message] = ($analysis['mostCommonErrors'][$message] ?? 0) + 1;
        }
    }

    // Сортировка по частоте
    arsort($analysis['errorTypes']);
    arsort($analysis['errorCodes']);
    arsort($analysis['operations']);
    arsort($analysis['mostCommonErrors']);

    return $analysis;
}

// Использование
$exceptions = [
    new ApiException('Ошибка сети', 0),
    new ApiException('Не найдено', 404),
    new ApiException('Ошибка сервера', 500),
    new ApiException('Не найдено', 404),
    new ApiException('Ошибка сети', 0)
];

$analysis = analyzeApiErrors($exceptions);

echo "=== Анализ ошибок API ===\n";
echo "Всего ошибок: {$analysis['totalErrors']}\n\n";

echo "Типы ошибок:\n";
foreach ($analysis['errorTypes'] as $type => $count) {
    echo "- {$type}: {$count}\n";
}

echo "\nКоды ошибок:\n";
foreach ($analysis['errorCodes'] as $code => $count) {
    echo "- {$code}: {$count}\n";
}

echo "\nЧастые ошибки:\n";
foreach (array_slice($analysis['mostCommonErrors'], 0, 5, true) as $message => $count) {
    echo "- '{$message}': {$count}\n";
}
```

## Связанные классы

- [`InvalidApiKeyException`](invalid-api-key-exception.md) - Исключение для неверного API ключа
- [`RateLimitException`](rate-limit-exception.md) - Исключение для превышения лимита запросов
- [`ResourceNotFoundException`](resource-not-found-exception.md) - Исключение для не найденных ресурсов
- [`KpValidationException`](kp-validation-exception.md) - Исключение для ошибок валидации
