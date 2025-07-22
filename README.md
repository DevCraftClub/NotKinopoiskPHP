# NotKinopoisk PHP Wrapper

PHP wrapper для Kinopoisk Unofficial API, написанный на PHP 8.3 с использованием лучших практик OOP программирования.

## Особенности

- ✅ Полная поддержка PHP 8.3
- ✅ Строгая типизация
- ✅ Следование принципам SOLID
- ✅ Реализация CRUD операций
- ✅ Принцип DRY (Don't Repeat Yourself)
- ✅ Логичная и простая структура проекта
- ✅ Исключительно на русском языке
- ✅ Обработка ошибок API
- ✅ Автозагрузка через Composer

## Установка

```bash
composer require notkinopoisk/php-wrapper
```

## Быстрый старт

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создаем клиент с вашим API ключом
$client = new Client('ваш-api-ключ');

// Получаем информацию о фильме
$film = $client->films->getById(301); // Матрица
echo $film->getDisplayName(); // "Матрица"

// Поиск фильмов
$searchResults = $client->films->searchByKeyword('мстители');
foreach ($searchResults->items as $film) {
    echo $film->getDisplayName() . "\n";
}

// Получаем популярные фильмы
$popularFilms = $client->films->getPopular();
```

## Структура проекта

```
src/
├── Client.php                 # Основной клиент
├── Exception/                 # Исключения
│   ├── ApiException.php
│   ├── InvalidApiKeyException.php
│   ├── RateLimitException.php
│   └── ResourceNotFoundException.php
├── Models/                    # Модели данных
│   ├── Film.php
│   ├── Person.php
│   ├── Staff.php
│   ├── CommonModels.php
│   ├── UserModels.php
│   └── ...
└── Services/                  # Сервисы для работы с API
    ├── AbstractService.php
    ├── FilmService.php
    ├── PersonService.php
    ├── StaffService.php
    ├── UserService.php
    └── MediaService.php
```

## API Документация

### Основной клиент

```php
$client = new NotKinopoisk\Client($apiKey, $config);
```

#### Параметры:

- `$apiKey` (string) - Ваш API ключ от Kinopoisk API
- `$config` (array) - Дополнительная конфигурация HTTP клиента

### Работа с фильмами

#### Получение информации о фильме

```php
$film = $client->films->getById(301);
echo $film->nameRu; // "Матрица"
echo $film->ratingKinopoisk; // 8.5
echo $film->getDisplayName(); // "Матрица"
```

#### Поиск фильмов по ключевым словам

```php
$results = $client->films->searchByKeyword('мстители', 1);
foreach ($results->items as $film) {
    echo $film->getDisplayName() . "\n";
}
```

#### Поиск фильмов по фильтрам

```php
$filters = [
    'genres' => [1], // ID жанра
    'countries' => [1], // ID страны
    'yearFrom' => 2020,
    'yearTo' => 2024,
    'ratingFrom' => 7.0,
    'type' => 'FILM'
];
$results = $client->films->searchByFilters($filters);
```

#### Получение коллекций фильмов

```php
// Популярные фильмы
$popular = $client->films->getPopular();

// Топ-250 фильмов
$top250 = $client->films->getTop250();

// Топ-250 сериалов
$top250Series = $client->films->getTop250Series();
```

#### Дополнительная информация о фильме

```php
$filmId = 301;

// Сезоны сериала
$seasons = $client->films->getSeasons($filmId);

// Факты и ошибки
$facts = $client->films->getFacts($filmId);

// Бюджет и сборы
$boxOffice = $client->films->getBoxOffice($filmId);

// Награды
$awards = $client->films->getAwards($filmId);

// Видео (трейлеры, тизеры)
$videos = $client->films->getVideos($filmId);

// Похожие фильмы
$similar = $client->films->getSimilar($filmId);

// Изображения
$images = $client->films->getImages($filmId, 'POSTER');

// Рецензии
$reviews = $client->films->getReviews($filmId);

// Внешние источники для просмотра
$sources = $client->films->getExternalSources($filmId);
```

### Работа с персонами

#### Поиск персон по имени

```php
$persons = $client->persons->searchByName('Том Круз');
foreach ($persons->items as $person) {
    echo $person->getDisplayName() . "\n";
}
```

#### Получение информации о персоне

```php
$person = $client->persons->getById(12345);
echo $person->getDisplayName();
echo $person->profession;
```

### Работа с персоналом фильма

#### Получение актеров и режиссеров фильма

```php
$staff = $client->staff->getByFilmId(301);
foreach ($staff as $member) {
    if ($member->isActor()) {
        echo "Актер: " . $member->getDisplayName() . "\n";
    } elseif ($member->isDirector()) {
        echo "Режиссер: " . $member->getDisplayName() . "\n";
    }
}
```

### Работа с пользователями

#### Получение оценок пользователя

```php
$votes = $client->users->getVotes(12345);
foreach ($votes as $vote) {
    echo $vote->getDisplayName() . " - " . $vote->userRating . "\n";
}
```

#### Информация об API ключе

```php
$apiInfo = $client->users->getApiKeyInfo($apiKey);
echo "Использовано запросов: " . $apiInfo->getTotalQuotaUsed() . "\n";
echo "Лимит: " . $apiInfo->getTotalQuotaValue() . "\n";
```

### Работа с медиа

#### Получение новостей Кинопоиска

```php
$posts = $client->media->getPosts();
foreach ($posts as $post) {
    echo $post->title . "\n";
    echo $post->description . "\n";
}
```

## Обработка ошибок

Wrapper предоставляет специфичные исключения для различных типов ошибок:

```php
try {
    $film = $client->films->getById(999999);
} catch (NotKinopoisk\Exception\ResourceNotFoundException $e) {
    echo "Фильм не найден: " . $e->getMessage();
} catch (NotKinopoisk\Exception\InvalidApiKeyException $e) {
    echo "Неверный API ключ: " . $e->getMessage();
} catch (NotKinopoisk\Exception\RateLimitException $e) {
    echo "Превышен лимит запросов: " . $e->getMessage();
} catch (NotKinopoisk\Exception\ApiException $e) {
    echo "Ошибка API: " . $e->getMessage();
}
```

## Примеры использования

### Полный пример работы с фильмом

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

$client = new Client('ваш-api-ключ');

try {
    // Получаем информацию о фильме
    $film = $client->films->getById(301);

    echo "Название: " . $film->getDisplayName() . "\n";
    echo "Год: " . $film->year . "\n";
    echo "Рейтинг Кинопоиска: " . $film->ratingKinopoisk . "\n";
    echo "Описание: " . $film->description . "\n";

    // Получаем актеров
    $staff = $client->staff->getByFilmId(301);
    $actors = array_filter($staff, fn($member) => $member->isActor());

    echo "Актеры:\n";
    foreach (array_slice($actors, 0, 5) as $actor) {
        echo "- " . $actor->getDisplayName() . "\n";
    }

    // Получаем трейлеры
    $videos = $client->films->getVideos(301);
    echo "Трейлеры:\n";
    foreach ($videos as $video) {
        echo "- " . $video->name . " (" . $video->site . ")\n";
    }

} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
```

### Поиск и фильтрация

```php
<?php

// Поиск фильмов по жанру и году
$filters = [
    'genres' => [1], // боевик
    'yearFrom' => 2020,
    'yearTo' => 2024,
    'ratingFrom' => 7.0,
    'order' => 'RATING'
];

$results = $client->films->searchByFilters($filters);

echo "Найдено фильмов: " . $results->total . "\n";
foreach ($results->items as $film) {
    echo $film->getDisplayName() . " (" . $film->year . ") - " . $film->ratingKinopoisk . "\n";
}
```

## Требования

- PHP 8.3 или выше
- Composer
- Guzzle HTTP Client

## Лицензия

MIT License

## Поддержка

Если у вас есть вопросы или предложения, создайте issue в репозитории проекта.
