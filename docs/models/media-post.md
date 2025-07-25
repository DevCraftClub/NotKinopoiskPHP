# MediaPost

## Описание

`MediaPost` - это модель медиа-поста в Kinopoisk API. Представляет медиа-пост, содержащий информацию о изображении, заголовке, описании и ссылке.

## Основные возможности

- Хранение информации о медиа-посте в неизменяемом виде
- Создание объекта из массива данных API
- Преобразование в массив для сериализации
- Совместимость с Kinopoisk API

## Наследование

```php
NotKinopoisk\Interfaces\ModelInterface
└── NotKinopoisk\Models\MediaPost
```

## Конструктор

```php
public function __construct(
    public readonly int $kinopoiskId,
    public readonly string $imageUrl,
    public readonly string $title,
    public readonly string $description,
    public readonly string $url,
    public readonly string $publishedAt,
)
```

### Параметры

- `$kinopoiskId` (int) - Уникальный идентификатор в Кинопоиске
- `$imageUrl` (string) - URL изображения
- `$title` (string) - Заголовок поста
- `$description` (string) - Описание поста
- `$url` (string) - URL поста
- `$publishedAt` (string) - Дата публикации

## Свойства

### kinopoiskId

```php
public readonly int $kinopoiskId
```

**Описание:** Уникальный идентификатор в Кинопоиске

**Тип:** `int`

**Доступ:** Только для чтения

**Пример:**

```php
$id = $mediaPost->kinopoiskId;
echo "ID поста: {$id}";
```

### imageUrl

```php
public readonly string $imageUrl
```

**Описание:** URL изображения

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$imageUrl = $mediaPost->imageUrl;
echo "Изображение: {$imageUrl}";
```

### title

```php
public readonly string $title
```

**Описание:** Заголовок поста

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$title = $mediaPost->title;
echo "Заголовок: {$title}";
```

### description

```php
public readonly string $description
```

**Описание:** Описание поста

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$description = $mediaPost->description;
echo "Описание: {$description}";
```

### url

```php
public readonly string $url
```

**Описание:** URL поста

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$url = $mediaPost->url;
echo "Ссылка на пост: {$url}";
```

### publishedAt

```php
public readonly string $publishedAt
```

**Описание:** Дата публикации

**Тип:** `string`

**Доступ:** Только для чтения

**Пример:**

```php
$publishedAt = $mediaPost->publishedAt;
echo "Дата публикации: {$publishedAt}";
```

## Статические методы

### fromArray()

```php
public static function fromArray(array $data): self
```

Создает экземпляр модели из массива данных API.

#### Параметры

- `$data` (array) - Массив данных от API

#### Возвращает

- `self` - Экземпляр модели

#### Исключения

- `\InvalidArgumentException` - При некорректных данных

#### Пример использования

```php
use NotKinopoisk\Models\MediaPost;

$postData = [
    'kinopoiskId' => 301,
    'imageUrl' => 'https://example.com/image.jpg',
    'title' => 'Новости о фильме',
    'description' => 'Описание новости',
    'url' => 'https://example.com/post',
    'publishedAt' => '2024-01-01T12:00:00'
];

$post = MediaPost::fromArray($postData);
```

## Методы экземпляра

### toArray()

```php
public function toArray(): array
```

Преобразует объект медиа-поста в массив.

#### Возвращает

- `array` - Массив с данными медиа-поста

#### Пример использования

```php
use NotKinopoisk\Models\MediaPost;

$postArray = $post->toArray();
echo json_encode($postArray); // JSON представление медиа-поста
```

## Примеры использования

### Создание объекта

```php
use NotKinopoisk\Models\MediaPost;

$mediaPost = new MediaPost(
    kinopoiskId: 301,
    imageUrl: 'https://example.com/image.jpg',
    title: 'Новости о фильме',
    description: 'Описание новости',
    url: 'https://example.com/post',
    publishedAt: '2024-01-01T12:00:00'
);
```

### Создание из данных API

```php
use NotKinopoisk\Models\MediaPost;

// Данные от API
$apiData = [
    'kinopoiskId' => 301,
    'imageUrl' => 'https://example.com/image.jpg',
    'title' => 'Новости о фильме',
    'description' => 'Описание новости',
    'url' => 'https://example.com/post',
    'publishedAt' => '2024-01-01T12:00:00'
];

$mediaPost = MediaPost::fromArray($apiData);
```

### Работа с медиа-постом

```php
use NotKinopoisk\Models\MediaPost;

// Получение информации
echo "ID: {$mediaPost->kinopoiskId}\n";
echo "Заголовок: {$mediaPost->title}\n";
echo "Описание: {$mediaPost->description}\n";
echo "Изображение: {$mediaPost->imageUrl}\n";
echo "Ссылка: {$mediaPost->url}\n";
echo "Дата публикации: {$mediaPost->publishedAt}\n";
```

### Использование в сервисах

```php
use NotKinopoisk\Models\MediaPost;
use NotKinopoisk\Services\MediaService;

class MediaService extends AbstractService
{
    public function getMediaPosts(): array
    {
        $response = $this->client->get('/api/v1/media_posts');

        return array_map(
            fn(array $postData) => MediaPost::fromArray($postData),
            $response['items'] ?? []
        );
    }

    public function getMediaPostById(int $id): MediaPost
    {
        $response = $this->client->get("/api/v1/media_posts/{$id}");
        return MediaPost::fromArray($response);
    }
}
```

### Отображение медиа-постов

```php
use NotKinopoisk\Models\MediaPost;

function displayMediaPost(MediaPost $post): void
{
    echo "=== Медиа-пост ===\n";
    echo "ID: {$post->kinopoiskId}\n";
    echo "Заголовок: {$post->title}\n";
    echo "Описание: {$post->description}\n";
    echo "Изображение: {$post->imageUrl}\n";
    echo "Ссылка: {$post->url}\n";
    echo "Дата публикации: {$post->publishedAt}\n";
    echo "==================\n";
}

// Использование
$mediaPost = MediaPost::fromArray($apiData);
displayMediaPost($mediaPost);
```

### Фильтрация медиа-постов

```php
use NotKinopoisk\Models\MediaPost;

function filterMediaPosts(array $posts, string $keyword): array
{
    return array_filter($posts, function(MediaPost $post) use ($keyword) {
        return stripos($post->title, $keyword) !== false ||
               stripos($post->description, $keyword) !== false;
    });
}

// Использование
$allPosts = [/* массив MediaPost объектов */];
$filteredPosts = filterMediaPosts($allPosts, 'фильм');
```

### Сортировка медиа-постов

```php
use NotKinopoisk\Models\MediaPost;

function sortMediaPostsByDate(array $posts, bool $ascending = true): array
{
    usort($posts, function(MediaPost $a, MediaPost $b) use ($ascending) {
        $comparison = strcmp($a->publishedAt, $b->publishedAt);
        return $ascending ? $comparison : -$comparison;
    });

    return $posts;
}

// Использование
$sortedPosts = sortMediaPostsByDate($allPosts, false); // По убыванию даты
```

### Сериализация и десериализация

```php
use NotKinopoisk\Models\MediaPost;

// Преобразование в массив
$array = $mediaPost->toArray();

// Сохранение в JSON
$json = json_encode($array, JSON_PRETTY_PRINT);
file_put_contents('media_post.json', $json);

// Загрузка из JSON
$loadedArray = json_decode(file_get_contents('media_post.json'), true);
$loadedMediaPost = MediaPost::fromArray($loadedArray);
```

### Валидация данных

```php
use NotKinopoisk\Models\MediaPost;

function validateMediaPostData(array $data): bool
{
    $requiredFields = ['kinopoiskId', 'imageUrl', 'title', 'description', 'url', 'publishedAt'];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new \InvalidArgumentException("Отсутствует обязательное поле: {$field}");
        }
    }

    if (!is_int($data['kinopoiskId']) || $data['kinopoiskId'] <= 0) {
        throw new \InvalidArgumentException('kinopoiskId должен быть положительным целым числом');
    }

    if (!filter_var($data['imageUrl'], FILTER_VALIDATE_URL)) {
        throw new \InvalidArgumentException('imageUrl должен быть валидным URL');
    }

    if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
        throw new \InvalidArgumentException('url должен быть валидным URL');
    }

    return true;
}

// Использование
try {
    validateMediaPostData($apiData);
    $mediaPost = MediaPost::fromArray($apiData);
} catch (\InvalidArgumentException $e) {
    echo "Ошибка валидации: " . $e->getMessage();
}
```

### Работа с датами

```php
use NotKinopoisk\Models\MediaPost;

function formatPublishedDate(MediaPost $post): string
{
    $date = new DateTime($post->publishedAt);
    return $date->format('d.m.Y H:i');
}

function isRecentPost(MediaPost $post, int $days = 7): bool
{
    $postDate = new DateTime($post->publishedAt);
    $now = new DateTime();
    $diff = $now->diff($postDate);

    return $diff->days <= $days;
}

// Использование
$formattedDate = formatPublishedDate($mediaPost);
echo "Дата публикации: {$formattedDate}";

if (isRecentPost($mediaPost)) {
    echo "Это недавний пост";
}
```

## Связанные классы

- `MediaService` - Сервис для работы с медиа-контентом
- `ModelInterface` - Интерфейс модели

## API Endpoints

Медиа-посты используются в следующих API endpoints:

- `/api/v1/media_posts` - Список медиа-постов
- `/api/v1/media_posts/{id}` - Детальная информация о медиа-посте
