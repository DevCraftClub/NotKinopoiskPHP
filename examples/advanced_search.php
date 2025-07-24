<?php

/**
 * Пример продвинутого поиска и фильтрации с NotKinopoisk PHP Wrapper
 * 
 * Демонстрирует возможности поиска по фильтрам, пагинации и сортировки
 * 
 * Для запуска:
 * 1. Установите зависимости: composer install
 * 2. Получите API ключ на https://kinopoiskapiunofficial.tech
 * 3. Установите переменную окружения KINOPOISK_API_KEY
 * 4. Запустите: php examples/advanced_search.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\FilmOrder;
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\CollectionType;
use NotKinopoisk\Exception\ApiException;

$apiKey = $_ENV['KINOPOISK_API_KEY'] ?? 'YOUR_API_KEY';

try {
    $client = new Client($apiKey);
    
    echo "=== Продвинутый поиск и фильтрация ===\n\n";
    
    // 1. Получаем доступные фильтры
    echo "1. Получение доступных фильтров:\n";
    $filters = $client->films->getFilters();
    
    echo "   Доступно жанров: " . count($filters->genres) . "\n";
    echo "   Доступно стран: " . count($filters->countries) . "\n";
    
    // Показываем несколько жанров
    echo "   Примеры жанров:\n";
    foreach (array_slice($filters->genres, 0, 5) as $genre) {
        echo "   - " . $genre->genre . "\n";
    }
    
    // Показываем несколько стран
    echo "   Примеры стран:\n";
    foreach (array_slice($filters->countries, 0, 5) as $country) {
        echo "   - " . $country->country . "\n";
    }
    echo "\n";
    
    // 2. Поиск фильмов с фильтрами
    echo "2. Поиск боевиков из США с рейтингом выше 7:\n";
    $actionMovies = $client->films->searchFilmsByFilter(
        country: ['США'],
        genre: ['боевик'],
        order: FilmOrder::RATING,
        type: ContentType::FILM,
        ratingFrom: 7.0,
        ratingTo: 10.0,
        yearFrom: 2020,
        yearTo: 2024,
        page: 1
    );
    
    echo "   Найдено фильмов: " . $actionMovies->total . "\n";
    echo "   Страница: " . $actionMovies->currentPage . " из " . $actionMovies->totalPages . "\n";
    
    foreach (array_slice($actionMovies->items, 0, 5) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 3. Поиск сериалов
    echo "3. Поиск сериалов-драм:\n";
    $dramaSeries = $client->films->searchFilmsByFilter(
        genre: ['драма'],
        order: FilmOrder::RATING,
        type: ContentType::TV_SERIES,
        ratingFrom: 6.0,
        yearFrom: 2020,
        page: 1
    );
    
    echo "   Найдено сериалов: " . $dramaSeries->total . "\n";
    
    foreach (array_slice($dramaSeries->items, 0, 3) as $series) {
        echo "   - " . $series->getDisplayName() . " (" . $series->year . ") - " . ($series->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 4. Топ-250 фильмов
    echo "4. Топ-250 фильмов (первые 10):\n";
    $top250 = $client->films->getTop250(1);
    
    foreach (array_slice($top250->items, 0, 10) as $index => $film) {
        $position = $index + 1;
        echo "   {$position}. " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 5. Популярные фильмы
    echo "5. Популярные фильмы:\n";
    $popular = $client->films->getPopular(1);
    
    foreach (array_slice($popular->items, 0, 5) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 6. Коллекции фильмов
    echo "6. Лучшие фильмы по жанрам:\n";
    
    // Лучшие боевики
    $bestAction = $client->films->getCollections(CollectionType::TOP_POPULAR_MOVIES, 1);
    echo "   Лучшие фильмы:\n";
    foreach (array_slice($bestAction->items, 0, 3) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 7. Поиск по ключевому слову с пагинацией
    echo "7. Поиск по ключевому слову 'фантастика' (страница 1):\n";
    $fantasySearch = $client->films->searchByKeyword('фантастика', 1);
    
    echo "   Найдено: " . $fantasySearch->total . " результатов\n";
    echo "   Показано: " . count($fantasySearch->items) . " на странице\n";
    
    foreach (array_slice($fantasySearch->items, 0, 5) as $film) {
        echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->rating ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 8. Демонстрация пагинации
    echo "8. Демонстрация пагинации (страница 2):\n";
    if ($fantasySearch->total > 20) { // Если есть вторая страница
        $fantasySearchPage2 = $client->films->searchByKeyword('фантастика', 2);
        
        foreach (array_slice($fantasySearchPage2->items, 0, 3) as $film) {
            echo "   - " . $film->getDisplayName() . " (" . $film->year . ") - " . ($film->rating ?? 'Нет рейтинга') . "\n";
        }
    } else {
        echo "   Вторая страница недоступна (мало результатов)\n";
    }
    echo "\n";
    
    // 9. Поиск по году
    echo "9. Фильмы 2023 года с высоким рейтингом:\n";
    $movies2023 = $client->films->searchFilmsByFilter(
        order: FilmOrder::RATING,
        type: ContentType::FILM,
        ratingFrom: 7.5,
        yearFrom: 2023,
        yearTo: 2023,
        page: 1
    );
    
    echo "   Найдено фильмов 2023 года с рейтингом 7.5+: " . $movies2023->total . "\n";
    
    foreach (array_slice($movies2023->items, 0, 5) as $film) {
        echo "   - " . $film->getDisplayName() . " - " . ($film->ratingKinopoisk ?? 'Нет рейтинга') . "\n";
    }
    echo "\n";
    
    // 10. Статистика поиска
    echo "10. Статистика поиска:\n";
    echo "   Всего фильмов найдено в примерах:\n";
    echo "   - Боевики США: " . $actionMovies->total . "\n";
    echo "   - Сериалы-драмы: " . $dramaSeries->total . "\n";
    echo "   - По ключевому слову 'фантастика': " . $fantasySearch->total . "\n";
    echo "   - Фильмы 2023 года: " . $movies2023->total . "\n";
    
    echo "\n=== Продвинутый поиск завершен! ===\n";
    
} catch (ApiException $e) {
    echo "Ошибка API: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Неожиданная ошибка: " . $e->getMessage() . "\n";
} 