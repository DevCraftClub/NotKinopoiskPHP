<?php

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Exception\ApiException;

// Пример использования с .env файлом
// Создайте файл .env в корне проекта с содержимым:
// KINOPOISK_API_KEY=ваш_ключ_здесь

try {
    // Клиент автоматически загрузит .env файл
    $client = new Client();
    
    echo "=== Пример использования с .env файлом ===\n\n";
    
    // Получаем информацию о фильме
    $film = $client->films->getById(301); // Матрица
    echo "Фильм: {$film->getDisplayName()}\n";
    echo "Год: {$film->year}\n";
    echo "Рейтинг Кинопоиск: " . ($film->ratingKinopoisk ?? 'Нет данных') . "\n";
    echo "Описание: " . mb_substr($film->description ?? 'Нет описания', 0, 100) . "...\n\n";
    
    // Поиск фильмов
    $searchResults = $client->films->searchByKeyword('мстители');
    echo "Найдено фильмов: {$searchResults->getCount()}\n";
    if (!$searchResults->isEmpty()) {
        echo "Первый результат: {$searchResults->items[0]->getDisplayName()}\n\n";
    }
    
    // Получаем популярные фильмы
    $popular = $client->films->getPopular();
    echo "Популярных фильмов: {$popular->getCount()}\n";
    if (!$popular->isEmpty()) {
        echo "Топ популярный: {$popular->items[0]->getDisplayName()}\n\n";
    }
    
    // Получаем информацию о персоне
    $personSearch = $client->persons->searchByName('Том Круз');
    if (!$personSearch->isEmpty() && $personSearch->items[0]->personId !== null) {
        $person = $client->persons->getById($personSearch->items[0]->personId);
        echo "Персона: {$person->getDisplayName()}\n";
        echo "Профессия: " . ($person->profession ?? 'Нет данных') . "\n\n";
    }
    
    // Получаем информацию об API ключе
    $apiKey = getenv('KINOPOISK_API_KEY');
    $keyInfo = $client->users->getApiKeyInfo($apiKey);
    echo "Тип аккаунта: {$keyInfo->accountType}\n";
    echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}\n";
    echo "Лимит запросов: {$keyInfo->getTotalQuotaValue()}\n";
    
} catch (ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
} catch (\InvalidArgumentException $e) {
    echo "Ошибка конфигурации: {$e->getMessage()}\n";
    echo "Убедитесь, что создан файл .env с переменной KINOPOISK_API_KEY\n";
} catch (\Exception $e) {
    echo "Неожиданная ошибка: {$e->getMessage()}\n";
} 