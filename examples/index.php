<?php

/**
 * Индексный файл примеров NotKinopoisk PHP Wrapper
 * 
 * Запускает интерактивное меню для выбора и запуска примеров
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Установите переменную окружения KINOPOISK_API_KEY
 * 4. Запустите: php examples/index.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\InvalidApiKeyException;

// Функция для очистки экрана
function clearScreen() {
    if (PHP_OS_FAMILY === 'Windows') {
        system('cls');
    } else {
        system('clear');
    }
}

// Функция для отображения меню
function showMenu() {
    echo "=== NotKinopoisk PHP Wrapper - Примеры использования ===\n\n";
    echo "Доступные примеры:\n";
    echo "1.  Базовое использование (basic_usage.php)\n";
    echo "2.  Продвинутый поиск (advanced_search.php)\n";
    echo "3.  Работа с персонами и медиа (persons_and_media.php)\n";
    echo "4.  Обработка ошибок (error_handling.php)\n";
    echo "5.  Конфигурация (configuration_examples.php)\n";
    echo "6.  Использование перечислений (enums_usage.php)\n";
    echo "7.  Работа с .env файлами (with_dotenv.php)\n";
    echo "8.  Проверить API ключ\n";
    echo "9.  Информация о библиотеке\n";
    echo "0.  Выход\n\n";
}

// Функция для проверки API ключа
function checkApiKey() {
    echo "=== Проверка API ключа ===\n\n";
    
    $apiKey = $_ENV['KINOPOISK_API_KEY'] ?? null;
    
    if (!$apiKey) {
        echo "❌ API ключ не найден!\n";
        echo "Установите переменную окружения KINOPOISK_API_KEY\n";
        echo "Или получите ключ на https://kinopoiskapiunofficial.tech\n\n";
        return false;
    }
    
    echo "✅ API ключ найден: " . substr($apiKey, 0, 8) . "...\n\n";
    
    try {
        $client = new Client($apiKey);
        $apiInfo = $client->users->getApiKeyInfo();
        
        echo "✅ API ключ работает!\n";
        echo "Тип аккаунта: " . $apiInfo->accountType->value . "\n";
        echo "Общий лимит: " . $apiInfo->totalQuota->total . "\n";
        echo "Использовано: " . $apiInfo->totalQuota->used . "\n";
        echo "Осталось: " . $apiInfo->getRemainingTotalQuota() . "\n";
        echo "Дневной лимит: " . $apiInfo->dailyQuota->total . "\n";
        echo "Использовано сегодня: " . $apiInfo->dailyQuota->used . "\n";
        echo "Осталось сегодня: " . $apiInfo->getRemainingDailyQuota() . "\n\n";
        
        echo "⚠️  Напоминание:\n";
        echo "• API структура обновлялась 16.10.2023\n";
        echo "• Некоторые методы могут не возвращать актуальные данные\n";
        echo "• НЕ путать с kinopoisk.dev\n\n";
        
        return true;
        
    } catch (InvalidApiKeyException $e) {
        echo "❌ Неверный API ключ!\n";
        echo "Получите новый ключ на https://kinopoiskapiunofficial.tech\n\n";
        return false;
    } catch (Exception $e) {
        echo "❌ Ошибка при проверке API ключа: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Функция для отображения информации о библиотеке
function showLibraryInfo() {
    echo "=== Информация о библиотеке ===\n\n";
    
    echo "📚 NotKinopoisk PHP Wrapper\n";
    echo "Версия: 2.0.0\n";
    echo "Автор: Maxim Harder <dev@devcraft.club>\n";
    echo "Лицензия: MIT\n\n";
    
    echo "🔧 Возможности:\n";
    echo "• Полный доступ к Kinopoisk API\n";
    echo "• Типизированные модели данных\n";
    echo "• Обработка ошибок и исключений\n";
    echo "• Поддержка пагинации\n";
    echo "• Работа с изображениями и видео\n";
    echo "• Поиск и фильтрация\n";
    echo "• Информация о персонах\n";
    echo "• Отзывы и рейтинги\n\n";
    
    echo "⚠️  Важные предостережения:\n";
    echo "• API структура обновлялась 16.10.2023\n";
    echo "• Некоторые методы могут не возвращать актуальные данные\n";
    echo "• НЕ путать с kinopoisk.dev\n";
    echo "• Работает с kinopoiskapiunofficial.tech\n\n";
    
    echo "🌐 Документация:\n";
    echo "• GitHub: https://github.com/your-repo\n";
    echo "• API: https://kinopoiskapiunofficial.tech\n";
    echo "• Примеры: examples/README.md\n\n";
}

// Функция для запуска примера
function runExample($filename) {
    if (!file_exists($filename)) {
        echo "❌ Файл примера не найден: {$filename}\n\n";
        return;
    }
    
    echo "🚀 Запуск примера: {$filename}\n";
    echo "Нажмите Enter для продолжения...\n";
    fgets(STDIN);
    
    clearScreen();
    
    // Запускаем пример
    include $filename;
    
    echo "\nНажмите Enter для возврата в меню...\n";
    fgets(STDIN);
}

// Основной цикл
while (true) {
    clearScreen();
    showMenu();
    
    echo "Выберите пример (0-9): ";
    $choice = trim(fgets(STDIN));
    
    switch ($choice) {
        case '1':
            runExample(__DIR__ . '/basic_usage.php');
            break;
            
        case '2':
            runExample(__DIR__ . '/advanced_search.php');
            break;
            
        case '3':
            runExample(__DIR__ . '/persons_and_media.php');
            break;
            
        case '4':
            runExample(__DIR__ . '/error_handling.php');
            break;
            
        case '5':
            runExample(__DIR__ . '/configuration_examples.php');
            break;
            
        case '6':
            runExample(__DIR__ . '/enums_usage.php');
            break;
            
        case '7':
            runExample(__DIR__ . '/with_dotenv.php');
            break;
            
        case '8':
            clearScreen();
            checkApiKey();
            echo "Нажмите Enter для возврата в меню...\n";
            fgets(STDIN);
            break;
            
        case '9':
            clearScreen();
            showLibraryInfo();
            echo "Нажмите Enter для возврата в меню...\n";
            fgets(STDIN);
            break;
            
        case '0':
            echo "👋 До свидания!\n";
            exit(0);
            
        default:
            echo "❌ Неверный выбор. Попробуйте снова.\n";
            sleep(1);
            break;
    }
} 