<?php

/**
 * Примеры различных способов конфигурации NotKinopoisk PHP Wrapper
 * 
 * Демонстрирует различные способы настройки клиента и обработки конфигурации
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Запустите: php examples/configuration_examples.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;

echo "=== Примеры различных способов конфигурации ===\n\n";

// Способ 1: Передача ключа напрямую в конструктор
echo "1. Передача ключа напрямую:\n";
try {
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'your_api_key_here'; // Замените на ваш ключ
    $client = new Client($apiKey);
    echo "✓ Клиент создан с переданным ключом\n";
    
    // Проверяем работоспособность
    $film = $client->films->getById(301);
    echo "✓ API работает, получен фильм: " . $film->getDisplayName() . "\n";
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка: Неверный API ключ - {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

// Способ 2: Использование переменной окружения
echo "\n2. Использование переменной окружения:\n";
try {
    // Устанавливаем переменную окружения (если не установлена)
    if (!isset($_ENV['KINOPOISK_API_KEY'])) {
        putenv('KINOPOISK_API_KEY=your_api_key_here'); // Замените на ваш ключ
    }
    
    $client = new Client(); // Без параметров - использует переменную окружения
    echo "✓ Клиент создан с ключом из переменной окружения\n";
    
    // Проверяем работоспособность
    $apiInfo = $client->users->getApiKeyInfo();
    echo "✓ API работает, тип аккаунта: " . $apiInfo->accountType->value . "\n";
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка: Неверный API ключ - {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

// Способ 3: Использование .env файла
echo "\n3. Использование .env файла:\n";
echo "Создайте файл .env в корне проекта со следующим содержимым:\n";
echo "KINOPOISK_API_KEY=your_api_key_here\n\n";

try {
    // Загружаем .env файл (если используется vlucas/phpdotenv)
    if (file_exists(__DIR__ . '/../.env')) {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
    }
    
    $client = new Client();
    echo "✓ Клиент создан с ключом из .env файла\n";
    
    // Проверяем работоспособность
    $filters = $client->films->getFilters();
    echo "✓ API работает, доступно жанров: " . count($filters->genres) . "\n";
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка: Неверный API ключ - {$e->getMessage()}\n";
    echo "   Убедитесь, что файл .env существует и содержит KINOPOISK_API_KEY\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

// Способ 4: Дополнительная конфигурация HTTP клиента
echo "\n4. Дополнительная конфигурация HTTP клиента:\n";
try {
    $config = [
        'timeout' => 60, // Увеличиваем таймаут до 60 секунд
        'headers' => [
            'User-Agent' => 'NotKinopoiskClient/2.0',
            'Accept' => 'application/json',
        ],
        'verify' => true, // Проверка SSL сертификатов
        'allow_redirects' => [
            'max' => 5,
            'strict' => true,
        ],
    ];
    
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'your_api_key_here';
    $client = new Client($apiKey, $config);
    echo "✓ Клиент создан с дополнительной конфигурацией\n";
    
    // Проверяем работоспособность
    $popular = $client->films->getPopular(1);
    echo "✓ API работает, получено популярных фильмов: " . count($popular->items) . "\n";
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка: Неверный API ключ - {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

// Способ 5: Проверка доступности API
echo "\n5. Проверка доступности API:\n";
try {
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'your_api_key_here';
    $client = new Client($apiKey);
    
    // Пробуем получить информацию о фильме
    $film = $client->films->getById(301);
    echo "✓ API доступен, получена информация о фильме: {$film->getDisplayName()}\n";
    
    // Проверяем информацию об API ключе
    $apiInfo = $client->users->getApiKeyInfo();
    echo "✓ Информация об API ключе получена:\n";
    echo "  - Тип аккаунта: " . $apiInfo->accountType->value . "\n";
    echo "  - Общий лимит: " . $apiInfo->totalQuota->total . "\n";
    echo "  - Использовано: " . $apiInfo->totalQuota->used . "\n";
    echo "  - Осталось: " . $apiInfo->getRemainingTotalQuota() . "\n";
    
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка конфигурации: Неверный API ключ - {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

// Способ 6: Конфигурация для продакшена
echo "\n6. Конфигурация для продакшена:\n";
try {
    $productionConfig = [
        'timeout' => 30,
        'connect_timeout' => 10,
        'headers' => [
            'User-Agent' => 'NotKinopoiskClient/2.0-Production',
        ],
        'verify' => true,
        'allow_redirects' => [
            'max' => 3,
            'strict' => true,
        ],
        'http_errors' => false, // Не выбрасывать исключения для HTTP ошибок
    ];
    
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'your_api_key_here';
    $client = new Client($apiKey, $productionConfig);
    echo "✓ Продакшен клиент создан\n";
    
    // Проверяем работоспособность
    $top250 = $client->films->getTop250(1);
    echo "✓ API работает, получено топ фильмов: " . count($top250->items) . "\n";
    
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка: Неверный API ключ - {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

// Способ 7: Конфигурация с логированием
echo "\n7. Конфигурация с логированием:\n";
try {
    $loggingConfig = [
        'timeout' => 30,
        'headers' => [
            'User-Agent' => 'NotKinopoiskClient/2.0-Logging',
        ],
        'on_stats' => function ($stats) {
            echo "   📊 Статистика запроса:\n";
            echo "   - URL: " . $stats->getEffectiveUri() . "\n";
            echo "   - Время выполнения: " . $stats->getTransferTime() . " сек\n";
            echo "   - Размер ответа: " . $stats->getResponseSize() . " байт\n";
        },
    ];
    
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'your_api_key_here';
    $client = new Client($apiKey, $loggingConfig);
    echo "✓ Клиент с логированием создан\n";
    
    // Делаем тестовый запрос
    $searchResults = $client->films->searchByKeyword('тест', 1);
    echo "✓ Запрос выполнен, найдено: " . $searchResults->total . " результатов\n";
    
} catch (InvalidApiKeyException $e) {
    echo "✗ Ошибка: Неверный API ключ - {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
}

echo "\n=== Рекомендации по конфигурации ===\n";
echo "🔧 Для разработки:\n";
echo "  - Используйте .env файл для хранения API ключа\n";
echo "  - Установите таймаут 30-60 секунд\n";
echo "  - Включите подробное логирование\n";
echo "\n🚀 Для продакшена:\n";
echo "  - Используйте переменные окружения сервера\n";
echo "  - Установите таймаут 10-30 секунд\n";
echo "  - Включите проверку SSL сертификатов\n";
echo "  - Настройте retry логику\n";
echo "\n🔒 Безопасность:\n";
echo "  - Никогда не коммитьте .env файлы в репозиторий\n";
echo "  - Добавьте .env в .gitignore\n";
echo "  - Используйте разные API ключи для разработки и продакшена\n";
echo "  - Регулярно ротируйте API ключи\n";
echo "\n📊 Мониторинг:\n";
echo "  - Отслеживайте использование лимитов API\n";
echo "  - Логируйте ошибки и время выполнения запросов\n";
echo "  - Настройте алерты при превышении лимитов\n"; 