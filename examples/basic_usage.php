<?php

/**
 * Пример базового использования NotKinopoisk PHP Wrapper
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Замените 'YOUR_API_KEY' на ваш ключ или используйте переменную окружения
 * 4. Запустите: php examples/basic_usage.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\ReviewOrder;
use NotKinopoisk\Enums\Month;
use NotKinopoisk\Exception\ApiException;
use NotKinopoisk\Exception\InvalidApiKeyException;
use NotKinopoisk\Exception\RateLimitException;
use NotKinopoisk\Exception\ResourceNotFoundException;

// Замените на ваш API ключ или используйте переменную окружения
$apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'YOUR_API_KEY';

try {
    // Создаем клиент
    $client = new Client($apiKey);
    
    echo "=== NotKinopoisk PHP Wrapper - Расширенный пример использования ===\n\n";
    
    // 1. Получаем информацию о фильме (Матрица)
    echo "1. Информация о фильме 'Матрица':\n";
    $film = $client->films->getById(301);
    echo "   Название: " . $film->getDisplayName() . "\n";
    echo "   Год: " . $film->year . "\n";
    echo "   Рейтинг Кинопоиска: " . ($film->ratingKinopoisk ?? 'Нет данных') . "\n";
    echo "   Рейтинг IMDB: " . ($film->ratingImdb ?? 'Нет данных') . "\n";
    echo "   Длительность: " . ($film->filmLength ?? 'Не указана') . " мин\n";
    echo "   Страны: " . $film->getCountriesString() . "\n";
    echo "   Жанры: " . $film->getGenresString() . "\n";
    echo "   Описание: " . substr($film->description ?? 'Нет описания', 0, 100) . "...\n\n";
    
    // 2. Получаем персонал фильма
    echo "2. Персонал фильма:\n";
    $staff = $client->persons->getFilmStaff(301);
    
    // Фильтруем актеров
    $actors = array_filter($staff->items, fn($member) => $member->isActor());
    echo "   Актеры:\n";
    foreach (array_slice($actors, 0, 3) as $actor) {
        echo "   - " . $actor->getDisplayName() . " (" . $actor->professionText . ")\n";
    }
    
    // Фильтруем режиссеров
    $directors = array_filter($staff->items, fn($member) => $member->isDirector());
    echo "   Режиссеры:\n";
    foreach (array_slice($directors, 0, 2) as $director) {
        echo "   - " . $director->getDisplayName() . "\n";
    }
    echo "\n";
    
    // 3. Поиск фильмов по ключевому слову
    echo "3. Поиск фильмов по слову 'мстители':\n";
    $searchResults = $client->films->searchByKeyword('мстители', 1);
    echo "   Найдено фильмов: " . $searchResults->total . "\n";
    
    foreach (array_slice($searchResults->items, 0, 3) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->rating ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 4. Получаем популярные фильмы
    echo "4. Популярные фильмы:\n";
    $popularFilms = $client->films->getPopular(1);
    
    foreach (array_slice($popularFilms->items, 0, 3) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 5. Получаем факты о фильме
    echo "5. Интересные факты о фильме:\n";
    $facts = $client->films->getFacts(301);
    
    foreach (array_slice($facts->items, 0, 2) as $fact) {
        echo "   - " . substr($fact->text, 0, 80) . "...\n";
        echo "     Тип: " . ($fact->isFact() ? 'Факт' : 'Ошибка') . "\n";
    }
    echo "\n";
    
    // 6. Получаем трейлеры
    echo "6. Трейлеры фильма:\n";
    $videos = $client->films->getVideos(301);
    
    foreach (array_slice($videos->items, 0, 2) as $video) {
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
    
    // 8. Получаем отзывы
    echo "8. Отзывы о фильме:\n";
    $reviews = $client->films->getReviews(301, 1, ReviewOrder::DATE_DESC);
    
    foreach (array_slice($reviews->items, 0, 2) as $review) {
        echo "   - " . substr($review->title, 0, 50) . "...\n";
        echo "     Автор: " . $review->author . "\n";
        echo "     Рейтинг: " . $review->userRating . "\n";
    }
    echo "\n";
    
    // 9. Получаем изображения
    echo "9. Постеры фильма:\n";
    $images = $client->media->getImages(301, ImageType::POSTER, 1);
    
    foreach (array_slice($images->items, 0, 2) as $image) {
        echo "   - " . $image->imageUrl . "\n";
    }
    echo "\n";
    
    // 10. Получаем сиквелы и приквелы
    echo "10. Сиквелы и приквелы:\n";
    $related = $client->films->getSequelsAndPrequels(301);
    
    $sequels = $related->getSequels();
    if (!empty($sequels)) {
        echo "   Сиквелы:\n";
        foreach (array_slice($sequels, 0, 2) as $sequel) {
            echo "   - " . $sequel->getDisplayName() . "\n";
        }
    }
    
    $prequels = $related->getPrequels();
    if (!empty($prequels)) {
        echo "   Приквелы:\n";
        foreach (array_slice($prequels, 0, 2) as $prequel) {
            echo "   - " . $prequel->getDisplayName() . "\n";
        }
    }
    echo "\n";
    
    // 11. Получаем премьеры
    echo "11. Премьеры в июне 2024:\n";
    $premieres = $client->films->getPremieres(2024, Month::JUNE);
    
    foreach (array_slice($premieres->items, 0, 2) as $premiere) {
        echo "   - " . $premiere->getDisplayName() . " (" . $premiere->premiereRu . ")\n";
    }
    echo "\n";
    
    // 12. Информация об API ключе
    echo "12. Информация об API ключе:\n";
    $apiInfo = $client->users->getApiKeyInfo();
    echo "   Тип аккаунта: " . $apiInfo->accountType->value . "\n";
    echo "   Общий лимит: " . $apiInfo->totalQuota->total . "\n";
    echo "   Использовано: " . $apiInfo->totalQuota->used . "\n";
    echo "   Осталось: " . $apiInfo->getRemainingTotalQuota() . "\n";
    echo "   Дневной лимит: " . $apiInfo->dailyQuota->total . "\n";
    echo "   Использовано сегодня: " . $apiInfo->dailyQuota->used . "\n";
    echo "   Осталось сегодня: " . $apiInfo->getRemainingDailyQuota() . "\n";
    
    echo "\n=== Пример завершен успешно! ===\n";
    
} catch (InvalidApiKeyException $e) {
    echo "Ошибка: Неверный API ключ. " . $e->getMessage() . "\n";
    echo "Получите API ключ на https://kinopoiskapiunofficial.tech\n";
    echo "Или установите переменную окружения KINOPOISK_API_KEY\n";
} catch (RateLimitException $e) {
    echo "Ошибка: Превышен лимит запросов. " . $e->getMessage() . "\n";
} catch (ResourceNotFoundException $e) {
    echo "Ошибка: Ресурс не найден. " . $e->getMessage() . "\n";
} catch (ApiException $e) {
    echo "Ошибка API: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Неожиданная ошибка: " . $e->getMessage() . "\n";
} 