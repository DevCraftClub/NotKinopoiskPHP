<?php

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;

echo "=== Примеры различных способов конфигурации ===\n\n";

// Способ 1: Передача ключа напрямую в конструктор
echo "1. Передача ключа напрямую:\n";
try {
    $apiKey = 'your_api_key_here'; // Замените на ваш ключ
    $client = new Client($apiKey);
    echo "✓ Клиент создан с переданным ключом\n";
} catch (\InvalidArgumentException $e) {
    echo "✗ Ошибка: {$e->getMessage()}\n";
}

// Способ 2: Использование переменной окружения
echo "\n2. Использование переменной окружения:\n";
try {
    // Устанавливаем переменную окружения
    putenv('KINOPOISK_API_KEY=your_api_key_here'); // Замените на ваш ключ
    
    $client = new Client();
    echo "✓ Клиент создан с ключом из переменной окружения\n";
} catch (\InvalidArgumentException $e) {
    echo "✗ Ошибка: {$e->getMessage()}\n";
}

// Способ 3: Использование .env файла
echo "\n3. Использование .env файла:\n";
echo "Создайте файл .env в корне проекта:\n";
echo "KINOPOISK_API_KEY=your_api_key_here\n\n";

try {
    $client = new Client();
    echo "✓ Клиент создан с ключом из .env файла\n";
} catch (\InvalidArgumentException $e) {
    echo "✗ Ошибка: {$e->getMessage()}\n";
    echo "   Убедитесь, что файл .env существует и содержит KINOPOISK_API_KEY\n";
}

// Способ 4: Дополнительная конфигурация HTTP клиента
echo "\n4. Дополнительная конфигурация HTTP клиента:\n";
try {
    $config = [
        'timeout' => 60, // Увеличиваем таймаут до 60 секунд
        'headers' => [
            'User-Agent' => 'MyApp/1.0',
        ],
    ];
    
    $client = new Client(null, $config);
    echo "✓ Клиент создан с дополнительной конфигурацией\n";
} catch (\InvalidArgumentException $e) {
    echo "✗ Ошибка: {$e->getMessage()}\n";
}

// Способ 5: Проверка доступности API
echo "\n5. Проверка доступности API:\n";
try {
    $client = new Client();
    
    // Пробуем получить информацию о фильме
    $film = $client->films->getById(301);
    echo "✓ API доступен, получена информация о фильме: {$film->getDisplayName()}\n";
    
} catch (ApiException $e) {
    echo "✗ Ошибка API: {$e->getMessage()}\n";
} catch (\InvalidArgumentException $e) {
    echo "✗ Ошибка конфигурации: {$e->getMessage()}\n";
}

echo "\n=== Рекомендации ===\n";
echo "- Для разработки используйте .env файл\n";
echo "- Для продакшена используйте переменные окружения сервера\n";
echo "- Никогда не коммитьте .env файлы в репозиторий\n";
echo "- Добавьте .env в .gitignore\n"; 