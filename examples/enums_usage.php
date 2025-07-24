<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\ReviewType;
use NotKinopoisk\Enums\FactType;
use NotKinopoisk\Enums\BoxOfficeType;
use NotKinopoisk\Enums\AccountType;
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\CollectionType;

/**
 * Пример использования Enums в NotKinopoiskPHP
 * 
 * Демонстрирует, как использовать типизированные enums для
 * улучшения типобезопасности и читаемости кода.
 */

// Инициализация клиента
$client = new Client();

echo "=== Примеры использования Enums в NotKinopoiskPHP ===\n\n";

// 1. ContentType - типы контента
echo "1. ContentType - типы контента:\n";
echo "   FILM: " . ContentType::FILM->getDisplayName() . "\n";
echo "   SERIES: " . ContentType::SERIES->getDisplayName() . "\n";
echo "   MINI_SERIES: " . ContentType::MINI_SERIES->getDisplayName() . "\n";
echo "   TV_SHOW: " . ContentType::TV_SHOW->getDisplayName() . "\n\n";

// Проверка типа контента
$contentType = ContentType::FILM;
if ($contentType->isFilm()) {
    echo "   Это фильм!\n";
}
if ($contentType->isSeries()) {
    echo "   Это сериал!\n";
}
echo "\n";

// 2. ReviewType - типы рецензий
echo "2. ReviewType - типы рецензий:\n";
echo "   POSITIVE: " . ReviewType::POSITIVE->getDisplayName() . "\n";
echo "   NEGATIVE: " . ReviewType::NEGATIVE->getDisplayName() . "\n";
echo "   NEUTRAL: " . ReviewType::NEUTRAL->getDisplayName() . "\n\n";

// Проверка типа рецензии
$reviewType = ReviewType::POSITIVE;
if ($reviewType->isPositive()) {
    echo "   Это положительная рецензия!\n";
}
echo "\n";

// 3. FactType - типы фактов
echo "3. FactType - типы фактов:\n";
echo "   FACT: " . FactType::FACT->getDisplayName() . "\n";
echo "   BLOOPER: " . FactType::BLOOPER->getDisplayName() . "\n\n";

// Проверка типа факта
$factType = FactType::BLOOPER;
if ($factType->isBlooper()) {
    echo "   Это ошибка в фильме!\n";
}
echo "\n";

// 4. BoxOfficeType - типы кассовых сборов
echo "4. BoxOfficeType - типы кассовых сборов:\n";
echo "   BUDGET: " . BoxOfficeType::BUDGET->getDisplayName() . "\n";
echo "   RUS: " . BoxOfficeType::RUS->getDisplayName() . "\n";
echo "   USA: " . BoxOfficeType::USA->getDisplayName() . "\n";
echo "   WORLD: " . BoxOfficeType::WORLD->getDisplayName() . "\n\n";

// Проверка типа кассовых сборов
$boxOfficeType = BoxOfficeType::BUDGET;
if ($boxOfficeType->isBudget()) {
    echo "   Это бюджет фильма!\n";
}
if ($boxOfficeType->isRevenue()) {
    echo "   Это сборы фильма!\n";
}
echo "\n";

// 5. AccountType - типы аккаунтов
echo "5. AccountType - типы аккаунтов:\n";
echo "   FREE: " . AccountType::FREE->getDisplayName() . "\n";
echo "   PAID: " . AccountType::PAID->getDisplayName() . "\n";
echo "   UNLIMITED: " . AccountType::UNLIMITED->getDisplayName() . "\n\n";

// Проверка типа аккаунта
$accountType = AccountType::UNLIMITED;
if ($accountType->isUnlimited()) {
    echo "   Безлимитный аккаунт!\n";
}
echo "\n";

// 6. ImageType - типы изображений
echo "6. ImageType - типы изображений:\n";
echo "   STILL: " . ImageType::STILL->getDisplayName() . "\n";
echo "   POSTER: " . ImageType::POSTER->getDisplayName() . "\n";
echo "   BACKGROUND: " . ImageType::BACKGROUND->getDisplayName() . "\n";
echo "   PREVIEW: " . ImageType::PREVIEW->getDisplayName() . "\n\n";

// 7. CollectionType - типы коллекций
echo "7. CollectionType - типы коллекций:\n";
echo "   TOP_POPULAR_ALL: " . CollectionType::TOP_POPULAR_ALL->getDisplayName() . "\n";
echo "   TOP_250_MOVIES: " . CollectionType::TOP_250_MOVIES->getDisplayName() . "\n";
echo "   TOP_250_SERIES: " . CollectionType::TOP_250_SERIES->getDisplayName() . "\n\n";

// 8. Практический пример с API
echo "8. Практический пример с API:\n";
echo "   (Раскомментируйте код ниже для реального использования)\n\n";

/*
try {
    $filmService = $client->films();
    
    // Получение фильма и проверка его типа
    $film = $filmService->getById(301); // Матрица
    echo "   Фильм: {$film->getDisplayName()}\n";
    echo "   Тип: {$film->type->getDisplayName()}\n";
    
    if ($film->type->isFilm()) {
        echo "   Это фильм!\n";
    } elseif ($film->type->isSeries()) {
        echo "   Это сериал!\n";
    }
    
    // Получение фактов с проверкой типа
    $facts = $filmService->getFacts(301);
    foreach ($facts as $fact) {
        echo "   Факт ({$fact->type->getDisplayName()}): {$fact->text}\n";
        if ($fact->type->isBlooper()) {
            echo "     ⚠️ Это ошибка в фильме!\n";
        }
    }
    
    // Получение изображений определенного типа
    $images = $filmService->getImages(301, ImageType::POSTER);
    echo "   Получено постеров: " . count($images) . "\n";
    
    // Получение коллекции определенного типа
    $top250 = $filmService->getCollections(CollectionType::TOP_250_MOVIES);
    echo "   Получено фильмов из топ-250: {$top250->getCount()}\n";
    
} catch (Exception $e) {
    echo "   Ошибка: " . $e->getMessage() . "\n";
}
*/

echo "\n=== Преимущества использования Enums ===\n";
echo "✅ Типобезопасность - компилятор проверяет корректность значений\n";
echo "✅ Автодополнение в IDE - легко найти доступные значения\n";
echo "✅ Рефакторинг - изменение значений в одном месте\n";
echo "✅ Документация - значения и их смысл видны в коде\n";
echo "✅ Методы - удобные методы для проверки и отображения\n";
echo "✅ Валидация - автоматическая проверка входных данных\n\n";

echo "=== Использование в моделях ===\n";
echo "Модели теперь используют типизированные enums вместо строк:\n";
echo "- Film::type теперь ContentType вместо string\n";
echo "- Review::type теперь ReviewType вместо string\n";
echo "- Fact::type теперь FactType вместо string\n";
echo "- BoxOffice::type теперь BoxOfficeType вместо string\n";
echo "- ApiKeyInfo::accountType теперь AccountType вместо string\n\n";

echo "=== Использование в сервисах ===\n";
echo "Сервисы принимают типизированные enums:\n";
echo "- FilmService::getImages(ImageType \$type)\n";
echo "- FilmService::getCollections(CollectionType \$type)\n";
echo "- Все методы теперь более безопасны и понятны\n\n";

echo "Enums успешно интегрированы в проект! 🎉\n"; 