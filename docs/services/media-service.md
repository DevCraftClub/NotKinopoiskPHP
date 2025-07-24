# MediaService

Сервис для работы с медиа контентом в Kinopoisk API.

## Описание

`MediaService` предоставляет методы для получения медиа материалов, связанных с фильмами: новости, статьи, интервью и другие публикации.

### Основные возможности

- Получение медиа постов о фильмах
- Наследование функциональности от AbstractService
- Использование API версии v1 по умолчанию
- Доступ к HTTP-клиенту для выполнения запросов

## Конструктор

```php
public function __construct(\NotKinopoisk\Client $client, string $apiVersion = 'v1.4')
```

### Параметры

- `$client` - Экземпляр клиента для выполнения HTTP запросов
- `$apiVersion` - Версия API (по умолчанию 'v1.4')

## Методы

### getPosts()

Получает список медиа-постов с поддержкой пагинации.

```php
public function getPosts(int $page = 1): PaginatedResponse
```

**API Endpoint:** `/api/v1/media_posts`

#### Параметры

- `$page` (int) - Номер страницы для загрузки (начиная с 1, по умолчанию первая страница)

#### Возвращаемое значение

- `PaginatedResponse` - Пагинированный ответ содержащий коллекцию медиа-постов с метаданными навигации

#### Исключения

- `\NotKinopoisk\Exception\ApiException` - При общих ошибках API или проблемах сети
- `\NotKinopoisk\Exception\InvalidApiKeyException` - Если API ключ недействителен, заблокирован или отсутствует
- `\NotKinopoisk\Exception\KpValidationException` - При некорректном номере страницы или других параметрах валидации
- `\NotKinopoisk\Exception\RateLimitException` - При превышении лимитов запросов (дневных или общих)
- `\NotKinopoisk\Exception\ResourceNotFoundException` - Если запрашиваемая страница или ресурс не найден

#### Пример использования

```php
// Получение первой страницы медиа-постов
$posts = $mediaService->getPosts();

foreach ($posts->items as $post) {
    echo "Заголовок: {$post->title}\n";
    echo "Описание: {$post->description}\n";
    echo "URL: {$post->url}\n";
    echo "Дата публикации: {$post->publishedAt}\n";
    echo "Изображение: {$post->imageUrl}\n";
    echo "---\n";
}

// Пагинация
if ($posts->hasNextPage()) {
    $nextPagePosts = $mediaService->getPosts($posts->getNextPage());
}

// Получение конкретной страницы
$secondPagePosts = $mediaService->getPosts(2);
echo "Страница {$secondPagePosts->currentPage} из {$secondPagePosts->totalPages}";
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Services\MediaService;

// Создание клиента
$client = new Client('your-api-key');

// Получение сервиса медиа
$mediaService = $client->media;

// Получение медиа постов
try {
    $posts = $mediaService->getPosts(1);

    echo "=== Медиа посты ===\n";
    echo "Всего постов: {$posts->total}\n";
    echo "Страница {$posts->currentPage} из {$posts->totalPages}\n\n";

    foreach ($posts->items as $post) {
        echo "📰 {$post->title}\n";
        echo "📝 {$post->description}\n";
        echo "🔗 {$post->url}\n";
        echo "📅 {$post->publishedAt}\n";

        if ($post->imageUrl) {
            echo "🖼️  {$post->imageUrl}\n";
        }
        echo "---\n";
    }

    // Навигация по страницам
    if ($posts->hasNextPage()) {
        echo "Следующая страница: {$posts->getNextPage()}\n";
    }

    if ($posts->hasPreviousPage()) {
        echo "Предыдущая страница: {$posts->getPreviousPage()}\n";
    }

} catch (\NotKinopoisk\Exception\ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
} catch (\NotKinopoisk\Exception\RateLimitException $e) {
    echo "Превышен лимит запросов\n";
} catch (\Exception $e) {
    echo "Ошибка: {$e->getMessage()}\n";
}
```

## Навигация по страницам

```php
// Функция для получения всех медиа постов
function getAllMediaPosts(MediaService $mediaService): array {
    $allPosts = [];
    $page = 1;

    do {
        try {
            $posts = $mediaService->getPosts($page);
            $allPosts = array_merge($allPosts, $posts->items);

            echo "Загружена страница {$page} из {$posts->totalPages}\n";

            $page++;

        } catch (\Exception $e) {
            echo "Ошибка при загрузке страницы {$page}: {$e->getMessage()}\n";
            break;
        }

    } while ($page <= $posts->totalPages);

    return $allPosts;
}

// Использование
$allPosts = getAllMediaPosts($mediaService);
echo "Всего загружено постов: " . count($allPosts) . "\n";
```

## Фильтрация и поиск

```php
// Функция для поиска постов по ключевому слову
function searchMediaPosts(array $posts, string $keyword): array {
    $keyword = strtolower($keyword);

    return array_filter($posts, function($post) use ($keyword) {
        return strpos(strtolower($post->title), $keyword) !== false ||
               strpos(strtolower($post->description), $keyword) !== false;
    });
}

// Использование
$posts = $mediaService->getPosts(1);
$filteredPosts = searchMediaPosts($posts->items, 'интервью');

echo "Найдено постов с 'интервью': " . count($filteredPosts) . "\n";
```

## Связанные классы

- [`MediaPost`](../models/media-post.md) - Модель медиа поста
- [`PaginatedResponse`](../responses/paginated-response.md) - Пагинированный ответ
