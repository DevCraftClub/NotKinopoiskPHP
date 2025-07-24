<?php

/**
 * Пример работы с персонами и медиа-контентом
 * 
 * Демонстрирует возможности получения информации о персонах,
 * их фильмографии, изображениях и видео
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Установите переменную окружения KINOPOISK_API_KEY
 * 4. Запустите: php examples/persons_and_media.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Exception\ApiException;

$apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'YOUR_API_KEY';

try {
    $client = new Client($apiKey);
    
    echo "=== Работа с персонами и медиа-контентом ===\n\n";
    
    // 1. Поиск персоны
    echo "1. Поиск персоны 'Том Круз':\n";
    $persons = $client->persons->searchByName('Том Круз', 1);
    
    if (!empty($persons->items)) {
        $tomCruise = $persons->items[0];
        echo "   Найдена персона: " . $tomCruise->getDisplayName() . "\n";
        echo "   ID: " . ($tomCruise->personId ?? 'Не указан') . "\n";
        echo "   Профессия: " . ($tomCruise->profession ?? 'Не указана') . "\n";
        echo "   Возраст: " . ($tomCruise->age ?? 'Не указан') . "\n";
        echo "   Место рождения: " . ($tomCruise->birthplace ?? 'Не указано') . "\n";
        echo "\n";
        
        // 2. Детальная информация о персоне
        if ($tomCruise->personId) {
            echo "2. Детальная информация о Томе Крузе:\n";
            $personDetails = $client->persons->getById($tomCruise->personId);
            
            echo "   Полное имя: " . $personDetails->getDisplayName() . "\n";
            echo "   Дата рождения: " . ($personDetails->birthday ?? 'Не указана') . "\n";
            echo "   Рост: " . ($personDetails->growth ?? 'Не указан') . " см\n";
            echo "   Биография: " . substr($personDetails->biography ?? 'Не указана', 0, 150) . "...\n";
            
            // Фильмография
            if (!empty($personDetails->films)) {
                echo "   Фильмография (первые 5 фильмов):\n";
                foreach (array_slice($personDetails->films, 0, 5) as $film) {
                    echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . $film->professionText . "\n";
                }
            }
            echo "\n";
        }
    }
    
    // 3. Персонал фильма
    echo "3. Персонал фильма 'Матрица':\n";
    $staff = $client->persons->getFilmStaff(301);
    
    // Группируем по профессиям
    $actors = array_filter($staff->items, fn($member) => $member->isActor());
    $directors = array_filter($staff->items, fn($member) => $member->isDirector());
    $producers = array_filter($staff->items, fn($member) => $member->isProducer());
    
    echo "   Актеры (" . count($actors) . "):\n";
    foreach (array_slice($actors, 0, 3) as $actor) {
        echo "   - " . $actor->getDisplayName() . " (" . $actor->professionText . ")\n";
    }
    
    echo "   Режиссеры (" . count($directors) . "):\n";
    foreach (array_slice($directors, 0, 2) as $director) {
        echo "   - " . $director->getDisplayName() . "\n";
    }
    
    echo "   Продюсеры (" . count($producers) . "):\n";
    foreach (array_slice($producers, 0, 2) as $producer) {
        echo "   - " . $producer->getDisplayName() . "\n";
    }
    echo "\n";
    
    // 4. Изображения фильма
    echo "4. Изображения фильма 'Матрица':\n";
    
    // Постеры
    $posters = $client->media->getImages(301, ImageType::POSTER, 1);
    echo "   Постеры (" . $posters->total . "):\n";
    foreach (array_slice($posters->items, 0, 2) as $poster) {
        echo "   - " . $poster->imageUrl . "\n";
    }
    
    // Кадры из фильма
    $stills = $client->media->getImages(301, ImageType::STILL, 1);
    echo "   Кадры из фильма (" . $stills->total . "):\n";
    foreach (array_slice($stills->items, 0, 2) as $still) {
        echo "   - " . $still->imageUrl . "\n";
    }
    
    // Фан-арты
    $fanArts = $client->media->getImages(301, ImageType::FAN_ART, 1);
    echo "   Фан-арты (" . $fanArts->total . "):\n";
    foreach (array_slice($fanArts->items, 0, 2) as $fanArt) {
        echo "   - " . $fanArt->imageUrl . "\n";
    }
    echo "\n";
    
    // 5. Видео контент
    echo "5. Видео контент фильма 'Матрица':\n";
    $videos = $client->films->getVideos(301);
    
    echo "   Всего видео: " . $videos->total . "\n";
    
    // Группируем по сайтам
    $youtubeVideos = array_filter($videos->items, fn($video) => $video->site === 'YOUTUBE');
    $kinopoiskVideos = array_filter($videos->items, fn($video) => $video->site === 'KINOPOISK_WIDGET');
    
    echo "   YouTube видео (" . count($youtubeVideos) . "):\n";
    foreach (array_slice($youtubeVideos, 0, 2) as $video) {
        echo "   - " . $video->name . " (" . $video->url . ")\n";
    }
    
    echo "   Виджеты Кинопоиска (" . count($kinopoiskVideos) . "):\n";
    foreach (array_slice($kinopoiskVideos, 0, 2) as $video) {
        echo "   - " . $video->name . " (" . $video->url . ")\n";
    }
    echo "\n";
    
    // 6. Факты о фильме
    echo "6. Интересные факты о фильме 'Матрица':\n";
    $facts = $client->films->getFacts(301);
    
    echo "   Всего фактов: " . $facts->total . "\n";
    
    foreach (array_slice($facts->items, 0, 3) as $fact) {
        echo "   - " . substr($fact->text, 0, 100) . "...\n";
        echo "     Тип: " . ($fact->isFact() ? 'Факт' : 'Ошибка') . "\n";
    }
    echo "\n";
    
    // 7. Отзывы о фильме
    echo "7. Отзывы о фильме 'Матрица':\n";
    $reviews = $client->films->getReviews(301, 1);
    
    echo "   Всего отзывов: " . $reviews->total . "\n";
    
    foreach (array_slice($reviews->items, 0, 2) as $review) {
        echo "   - " . substr($review->title, 0, 50) . "...\n";
        echo "     Автор: " . $review->author . "\n";
        echo "     Рейтинг: " . $review->userRating . "\n";
        echo "     Дата: " . $review->date . "\n";
    }
    echo "\n";
    
    // 8. Внешние источники
    echo "8. Внешние источники фильма 'Матрица':\n";
    $externalSources = $client->films->getExternalSources(301, 1);
    
    echo "   Всего источников: " . $externalSources->total . "\n";
    
    foreach (array_slice($externalSources->items, 0, 3) as $source) {
        echo "   - " . $source->url . " (" . $source->platform . ")\n";
    }
    echo "\n";
    
    // 9. Статистика медиа-контента
    echo "9. Статистика медиа-контента:\n";
    echo "   Изображения:\n";
    echo "   - Постеры: " . $posters->total . "\n";
    echo "   - Кадры: " . $stills->total . "\n";
    echo "   - Фан-арты: " . $fanArts->total . "\n";
    echo "   Видео: " . $videos->total . "\n";
    echo "   Факты: " . $facts->total . "\n";
    echo "   Отзывы: " . $reviews->total . "\n";
    echo "   Внешние источники: " . $externalSources->total . "\n";
    
    echo "\n=== Работа с персонами и медиа завершена! ===\n";
    
} catch (ApiException $e) {
    echo "Ошибка API: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Неожиданная ошибка: " . $e->getMessage() . "\n";
} 