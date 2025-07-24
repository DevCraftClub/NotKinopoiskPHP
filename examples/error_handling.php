<?php

/**
 * Пример обработки ошибок и исключений
 * 
 * Демонстрирует различные типы ошибок и способы их обработки
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Установите переменную окружения KINOPOISK_API_KEY
 * 4. Запустите: php examples/error_handling.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;
use NotKinopoisk\Exception\KpValidationException;

echo "=== Обработка ошибок и исключений ===\n\n";

// Функция для демонстрации обработки ошибок
function demonstrateErrorHandling(Client $client) {
    
    // 1. Попытка получить несуществующий фильм
    echo "1. Попытка получить несуществующий фильм (ID: 999999999):\n";
    try {
        $film = $client->films->getById(999999999);
        echo "   ✓ Фильм найден: " . $film->getDisplayName() . "\n";
    } catch (ResourceNotFoundException $e) {
        echo "   ✗ Ресурс не найден: " . $e->getMessage() . "\n";
        echo "   Код ошибки: " . $e->getCode() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 2. Попытка получить фильм с некорректным ID
    echo "2. Попытка получить фильм с некорректным ID (ID: -1):\n";
    try {
        $film = $client->films->getById(-1);
        echo "   ✓ Фильм найден: " . $film->getDisplayName() . "\n";
    } catch (ResourceNotFoundException $e) {
        echo "   ✗ Ресурс не найден: " . $e->getMessage() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 3. Попытка получить персонал несуществующего фильма
    echo "3. Попытка получить персонал несуществующего фильма:\n";
    try {
        $staff = $client->persons->getFilmStaff(999999999);
        echo "   ✓ Персонал получен: " . count($staff->items) . " человек\n";
    } catch (ResourceNotFoundException $e) {
        echo "   ✗ Ресурс не найден: " . $e->getMessage() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 4. Поиск с некорректными параметрами
    echo "4. Поиск с некорректными параметрами (отрицательная страница):\n";
    try {
        $results = $client->films->searchByKeyword('тест', -1);
        echo "   ✓ Результаты получены: " . $results->total . " фильмов\n";
    } catch (KpValidationException $e) {
        echo "   ✗ Ошибка валидации: " . $e->getMessage() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 5. Попытка получить изображения несуществующего фильма
    echo "5. Попытка получить изображения несуществующего фильма:\n";
    try {
        $images = $client->media->getImages(999999999, \NotKinopoisk\Enums\ImageType::POSTER, 1);
        echo "   ✓ Изображения получены: " . $images->total . " изображений\n";
    } catch (ResourceNotFoundException $e) {
        echo "   ✗ Ресурс не найден: " . $e->getMessage() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 6. Демонстрация успешного запроса
    echo "6. Успешный запрос (для сравнения):\n";
    try {
        $film = $client->films->getById(301); // Матрица
        echo "   ✓ Фильм найден: " . $film->getDisplayName() . "\n";
        echo "   ✓ Год: " . $film->year . "\n";
        echo "   ✓ Рейтинг: " . ($film->ratingKinopoisk ?? 'Нет данных') . "\n";
    } catch (ResourceNotFoundException $e) {
        echo "   ✗ Ресурс не найден: " . $e->getMessage() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Функция для демонстрации обработки лимитов
function demonstrateRateLimitHandling(Client $client) {
    echo "7. Демонстрация обработки лимитов запросов:\n";
    
    try {
        // Получаем информацию об API ключе
        $apiInfo = $client->users->getApiKeyInfo();
        
        echo "   Тип аккаунта: " . $apiInfo->accountType->value . "\n";
        echo "   Общий лимит: " . $apiInfo->totalQuota->total . "\n";
        echo "   Использовано: " . $apiInfo->totalQuota->used . "\n";
        echo "   Осталось: " . $apiInfo->getRemainingTotalQuota() . "\n";
        echo "   Дневной лимит: " . $apiInfo->dailyQuota->total . "\n";
        echo "   Использовано сегодня: " . $apiInfo->dailyQuota->used . "\n";
        echo "   Осталось сегодня: " . $apiInfo->getRemainingDailyQuota() . "\n";
        
        // Проверяем, близки ли мы к лимиту
        if ($apiInfo->getRemainingTotalQuota() < 10) {
            echo "   ⚠️  Внимание: Осталось мало запросов!\n";
        }
        
        if ($apiInfo->getRemainingDailyQuota() < 5) {
            echo "   ⚠️  Внимание: Осталось мало дневных запросов!\n";
        }
        
    } catch (RateLimitException $e) {
        echo "   ✗ Превышен лимит запросов: " . $e->getMessage() . "\n";
    } catch (ApiException $e) {
        echo "   ✗ Ошибка API: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Функция для демонстрации общей обработки ошибок
function demonstrateGeneralErrorHandling(Client $client) {
    echo "8. Общая обработка ошибок:\n";
    
    $operations = [
        'getFilm' => fn() => $client->films->getById(301),
        'getStaff' => fn() => $client->persons->getFilmStaff(301),
        'getImages' => fn() => $client->media->getImages(301, \NotKinopoisk\Enums\ImageType::POSTER, 1),
        'getVideos' => fn() => $client->films->getVideos(301),
        'getFacts' => fn() => $client->films->getFacts(301),
    ];
    
    foreach ($operations as $operation => $callback) {
        try {
            $result = $callback();
            echo "   ✓ {$operation}: Успешно\n";
        } catch (ResourceNotFoundException $e) {
            echo "   ✗ {$operation}: Ресурс не найден\n";
        } catch (RateLimitException $e) {
            echo "   ✗ {$operation}: Превышен лимит\n";
        } catch (KpValidationException $e) {
            echo "   ✗ {$operation}: Ошибка валидации\n";
        } catch (ApiException $e) {
            echo "   ✗ {$operation}: Ошибка API (" . $e->getCode() . ")\n";
        } catch (Exception $e) {
            echo "   ✗ {$operation}: Неожиданная ошибка\n";
        }
    }
    echo "\n";
}

// Основная логика
try {
    // Проверяем наличие API ключа
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? null;
    
    if (!$apiKey) {
        echo "❌ Ошибка: API ключ не найден!\n";
        echo "Установите переменную окружения KINOPOISK_API_KEY\n";
        echo "Или получите ключ на https://kinopoiskapiunofficial.tech\n";
        exit(1);
    }
    
    // Создаем клиент
    $client = new Client($apiKey);
    
    // Демонстрируем обработку ошибок
    demonstrateErrorHandling($client);
    demonstrateRateLimitHandling($client);
    demonstrateGeneralErrorHandling($client);
    
    echo "=== Рекомендации по обработке ошибок ===\n";
    echo "1. Всегда обрабатывайте исключения в try-catch блоках\n";
    echo "2. Используйте специфичные типы исключений для точной обработки\n";
    echo "3. Проверяйте лимиты API перед массовыми запросами\n";
    echo "4. Логируйте ошибки для отладки\n";
    echo "5. Предоставляйте пользователю понятные сообщения об ошибках\n";
    echo "6. Используйте retry логику для временных ошибок\n";
    
    echo "\n=== Демонстрация обработки ошибок завершена! ===\n";
    
} catch (InvalidApiKeyException $e) {
    echo "❌ Ошибка: Неверный API ключ!\n";
    echo "Получите новый ключ на https://kinopoiskapiunofficial.tech\n";
    echo "Детали: " . $e->getMessage() . "\n";
} catch (RateLimitException $e) {
    echo "❌ Ошибка: Превышен лимит запросов!\n";
    echo "Попробуйте позже или обновите план подписки\n";
    echo "Детали: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Неожиданная ошибка: " . $e->getMessage() . "\n";
    echo "Проверьте подключение к интернету и настройки\n";
} 