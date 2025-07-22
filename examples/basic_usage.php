<?php

/**
 * Пример базового использования NotKinopoisk PHP Wrapper
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Замените 'YOUR_API_KEY' на ваш ключ
 * 4. Запустите: php examples/basic_usage.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;

// Замените на ваш API ключ
$apiKey = 'YOUR_API_KEY';

try {
    // Создаем клиент
    $client = new Client($apiKey);
    
    echo "=== NotKinopoisk PHP Wrapper - Пример использования ===\n\n";
    
    // 1. Получаем информацию о фильме (Матрица)
    echo "1. Информация о фильме 'Матрица':\n";
    $film = $client->films->getById(301);
    echo "   Название: " . $film->getDisplayName() . "\n";
    echo "   Год: " . $film->year . "\n";
    echo "   Рейтинг Кинопоиска: " . $film->ratingKinopoisk . "\n";
    echo "   Длительность: " . $film->filmLength . " мин\n";
    echo "   Описание: " . substr($film->description ?? 'Нет описания', 0, 100) . "...\n\n";
    
    // 2. Получаем актеров фильма
    echo "2. Актеры фильма:\n";
    $staff = $client->staff->getByFilmId(301);
    $actors = array_filter($staff, fn($member) => $member->isActor());
    
    foreach (array_slice($actors, 0, 3) as $actor) {
        echo "   - " . $actor->getDisplayName() . " (" . $actor->professionText . ")\n";
    }
    echo "\n";
    
    // 3. Поиск фильмов по ключевому слову
    echo "3. Поиск фильмов по слову 'мстители':\n";
    $searchResults = $client->films->searchByKeyword('мстители', 1);
    echo "   Найдено фильмов: " . $searchResults->total . "\n";
    
    foreach (array_slice($searchResults->items, 0, 3) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ")\n";
    }
    echo "\n";
    
    // 4. Получаем популярные фильмы
    echo "4. Популярные фильмы:\n";
    $popularFilms = $client->films->getPopular(1);
    
    foreach (array_slice($popularFilms->items, 0, 3) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . $film->ratingKinopoisk . "\n";
    }
    echo "\n";
    
    // 5. Получаем факты о фильме
    echo "5. Интересные факты о фильме:\n";
    $facts = $client->films->getFacts(301);
    
    foreach (array_slice($facts, 0, 2) as $fact) {
        echo "   - " . substr($fact->text, 0, 80) . "...\n";
        echo "     Тип: " . ($fact->isFact() ? 'Факт' : 'Ошибка') . "\n";
    }
    echo "\n";
    
    // 6. Получаем трейлеры
    echo "6. Трейлеры фильма:\n";
    $videos = $client->films->getVideos(301);
    
    foreach (array_slice($videos, 0, 2) as $video) {
        echo "   - " . $video->name . " (" . $video->site . ")\n";
    }
    echo "\n";
    
    // 7. Поиск персон
    echo "7. Поиск персон по имени 'Том Круз':\n";
    $persons = $client->persons->searchByName('Том Круз', 1);
    
    foreach (array_slice($persons->items, 0, 2) as $person) {
        echo "   - " . $person->getDisplayName() . "\n";
    }
    echo "\n";
    
    // 8. Информация об API ключе
    echo "8. Информация об API ключе:\n";
    $apiInfo = $client->users->getApiKeyInfo($apiKey);
    echo "   Тип аккаунта: " . $apiInfo->accountType . "\n";
    echo "   Использовано запросов: " . $apiInfo->getTotalQuotaUsed() . "\n";
    echo "   Лимит запросов: " . $apiInfo->getTotalQuotaValue() . "\n";
    echo "   Дневной лимит: " . $apiInfo->getDailyQuotaValue() . "\n";
    echo "   Использовано сегодня: " . $apiInfo->getDailyQuotaUsed() . "\n";
    
    echo "\n=== Пример завершен успешно! ===\n";
    
} catch (InvalidApiKeyException $e) {
    echo "Ошибка: Неверный API ключ. " . $e->getMessage() . "\n";
    echo "Получите API ключ на https://kinopoiskapiunofficial.tech\n";
} catch (RateLimitException $e) {
    echo "Ошибка: Превышен лимит запросов. " . $e->getMessage() . "\n";
} catch (ResourceNotFoundException $e) {
    echo "Ошибка: Ресурс не найден. " . $e->getMessage() . "\n";
} catch (ApiException $e) {
    echo "Ошибка API: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Неожиданная ошибка: " . $e->getMessage() . "\n";
} 