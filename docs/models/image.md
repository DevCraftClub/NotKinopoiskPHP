# Image

Модель изображения из Kinopoisk API.

## Описание

`Image` представляет информацию об изображении, связанном с фильмом, включая URL полного изображения и превью.

### Основные возможности

- Хранение информации об изображении в неизменяемом виде
- Создание объекта из массива данных API
- Доступ к полному и превью изображениям

**API Endpoint:** `/api/v2.2/films/{id}/images`

## Свойства

### Основная информация

- `$imageUrl` (string) - URL полного изображения
- `$previewUrl` (string) - URL превью изображения

## Конструктор

```php
public function __construct(
    public readonly string $imageUrl,
    public readonly string $previewUrl,
)
```

### Пример создания

```php
$image = new Image(
    imageUrl: 'https://kinopoisk.ru/images/full.jpg',
    previewUrl: 'https://kinopoisk.ru/images/preview.jpg'
);
```

## Методы

### fromArray()

Создает экземпляр изображения из массива данных API.

```php
public static function fromArray(array $data): self
```

#### Параметры

- `$data` (array) - Массив данных изображения от API

#### Возвращаемое значение

- `self` - Новый экземпляр изображения

#### Исключения

- `\InvalidArgumentException` - Если данные имеют неверный формат

#### Пример использования

```php
$apiData = [
    'imageUrl' => 'https://kinopoisk.ru/images/full.jpg',
    'previewUrl' => 'https://kinopoisk.ru/images/preview.jpg'
];

$image = Image::fromArray($apiData);
```

### toArray()

Преобразует объект изображения в массив.

```php
public function toArray(): array
```

#### Возвращаемое значение

- `array` - Массив с данными изображения

#### Пример использования

```php
$imageArray = $image->toArray();
echo json_encode($imageArray); // JSON представление изображения
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Models\Image;
use NotKinopoisk\Enums\ImageType;

// Создание клиента
$client = new Client('your-api-key');

// Получение изображений фильма
$filmService = $client->films;
$filmId = 301; // Матрица

echo "=== Изображения фильма 'Матрица' ===\n";

// Получение различных типов изображений
$imageTypes = [
    ImageType::POSTER => 'Постеры',
    ImageType::STILL => 'Кадры из фильма',
    ImageType::FAN_ART => 'Фан-арты',
    ImageType::CONCEPT => 'Концепт-арты'
];

foreach ($imageTypes as $type => $description) {
    try {
        $images = $filmService->getImages($filmId, $type);

        echo "\n🖼️  {$description} (" . count($images) . "):\n";

        // Вывод первых 3 изображений
        foreach (array_slice($images, 0, 3) as $index => $image) {
            echo ($index + 1) . ". Полное: {$image->imageUrl}\n";
            echo "   Превью: {$image->previewUrl}\n";
            echo "   ---\n";
        }

    } catch (\Exception $e) {
        echo "❌ Ошибка при получении {$description}: {$e->getMessage()}\n";
    }
}
```

## Работа с изображениями

```php
// Функция для получения всех изображений фильма
function getAllFilmImages(Client $client, int $filmId): array {
    $allImages = [];
    $imageTypes = [
        ImageType::POSTER,
        ImageType::STILL,
        ImageType::FAN_ART,
        ImageType::CONCEPT,
        ImageType::PROMO,
        ImageType::WALLPAPER
    ];

    foreach ($imageTypes as $type) {
        try {
            $images = $client->films->getImages($filmId, $type);
            $allImages[$type->value] = [
                'type' => $type->getDisplayName(),
                'count' => count($images),
                'images' => $images
            ];
        } catch (\Exception $e) {
            echo "Ошибка при получении {$type->getDisplayName()}: {$e->getMessage()}\n";
        }
    }

    return $allImages;
}

// Функция для создания галереи изображений
function createImageGallery(array $allImages): array {
    $gallery = [
        'total' => 0,
        'types' => [],
        'preview' => []
    ];

    foreach ($allImages as $typeKey => $typeData) {
        $gallery['total'] += $typeData['count'];
        $gallery['types'][$typeKey] = $typeData;

        // Добавляем превью для каждого типа
        if (!empty($typeData['images'])) {
            $gallery['preview'][$typeKey] = $typeData['images'][0];
        }
    }

    return $gallery;
}

// Использование
$client = new Client('your-api-key');
$allImages = getAllFilmImages($client, 301);
$gallery = createImageGallery($allImages);

echo "=== Галерея изображений ===\n";
echo "Всего изображений: {$gallery['total']}\n\n";

foreach ($gallery['types'] as $typeKey => $typeData) {
    echo "📁 {$typeData['type']}: {$typeData['count']} изображений\n";

    if (isset($gallery['preview'][$typeKey])) {
        $preview = $gallery['preview'][$typeKey];
        echo "   Превью: {$preview->previewUrl}\n";
    }
    echo "\n";
}
```

## Скачивание изображений

```php
class ImageDownloader {
    private string $downloadDir;

    public function __construct(string $downloadDir = 'downloads') {
        $this->downloadDir = $downloadDir;

        if (!is_dir($this->downloadDir)) {
            mkdir($this->downloadDir, 0755, true);
        }
    }

    public function downloadImage(Image $image, string $filename): bool {
        try {
            $fullPath = $this->downloadDir . '/' . $filename;

            // Скачивание полного изображения
            $imageData = file_get_contents($image->imageUrl);
            if ($imageData === false) {
                throw new \Exception("Не удалось скачать изображение");
            }

            if (file_put_contents($fullPath, $imageData) === false) {
                throw new \Exception("Не удалось сохранить файл");
            }

            echo "✅ Скачано: {$filename}\n";
            return true;

        } catch (\Exception $e) {
            echo "❌ Ошибка при скачивании {$filename}: {$e->getMessage()}\n";
            return false;
        }
    }

    public function downloadImagesByType(array $images, string $type, int $limit = 5): int {
        $downloaded = 0;

        foreach (array_slice($images, 0, $limit) as $index => $image) {
            $filename = "{$type}_{" . ($index + 1) . "}.jpg";

            if ($this->downloadImage($image, $filename)) {
                $downloaded++;
            }
        }

        return $downloaded;
    }
}

// Использование
$downloader = new ImageDownloader('matrix_images');

// Скачивание постеров
$posters = $filmService->getImages(301, ImageType::POSTER);
$downloadedPosters = $downloader->downloadImagesByType($posters, 'poster', 3);
echo "Скачано постеров: {$downloadedPosters}\n";

// Скачивание кадров из фильма
$stills = $filmService->getImages(301, ImageType::STILL);
$downloadedStills = $downloader->downloadImagesByType($stills, 'still', 5);
echo "Скачано кадров: {$downloadedStills}\n";
```

## Создание HTML галереи

```php
function createHtmlGallery(array $images, string $title): string {
    $html = "<!DOCTYPE html>\n<html>\n<head>\n";
    $html .= "<title>{$title}</title>\n";
    $html .= "<style>\n";
    $html .= ".gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px; }\n";
    $html .= ".image-item { text-align: center; }\n";
    $html .= ".image-item img { max-width: 100%; height: auto; border-radius: 8px; }\n";
    $html .= ".image-item a { color: #333; text-decoration: none; }\n";
    $html .= "</style>\n</head>\n<body>\n";
    $html .= "<h1>{$title}</h1>\n";
    $html .= "<div class='gallery'>\n";

    foreach ($images as $index => $image) {
        $html .= "<div class='image-item'>\n";
        $html .= "<a href='{$image->imageUrl}' target='_blank'>\n";
        $html .= "<img src='{$image->previewUrl}' alt='Изображение " . ($index + 1) . "'>\n";
        $html .= "</a>\n";
        $html .= "<p>Изображение " . ($index + 1) . "</p>\n";
        $html .= "</div>\n";
    }

    $html .= "</div>\n</body>\n</html>";

    return $html;
}

// Использование
$posters = $filmService->getImages(301, ImageType::POSTER);
$html = createHtmlGallery($posters, 'Постеры фильма "Матрица"');

// Сохранение HTML файла
file_put_contents('matrix_posters.html', $html);
echo "✅ HTML галерея сохранена в matrix_posters.html\n";
```

## Валидация изображений

```php
function validateImage(Image $image): array {
    $validation = [
        'valid' => true,
        'errors' => [],
        'warnings' => []
    ];

    // Проверка URL полного изображения
    if (empty($image->imageUrl)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'URL полного изображения пустой';
    } elseif (!filter_var($image->imageUrl, FILTER_VALIDATE_URL)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'Некорректный URL полного изображения';
    }

    // Проверка URL превью
    if (empty($image->previewUrl)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'URL превью пустой';
    } elseif (!filter_var($image->previewUrl, FILTER_VALIDATE_URL)) {
        $validation['valid'] = false;
        $validation['errors'][] = 'Некорректный URL превью';
    }

    // Проверка доступности изображений
    if ($validation['valid']) {
        $headers = @get_headers($image->imageUrl);
        if (!$headers || strpos($headers[0], '200') === false) {
            $validation['warnings'][] = 'Полное изображение недоступно';
        }

        $headers = @get_headers($image->previewUrl);
        if (!$headers || strpos($headers[0], '200') === false) {
            $validation['warnings'][] = 'Превью недоступно';
        }
    }

    return $validation;
}

// Использование
$images = $filmService->getImages(301, ImageType::POSTER);

foreach ($images as $index => $image) {
    $validation = validateImage($image);

    if (!$validation['valid']) {
        echo "❌ Изображение " . ($index + 1) . " невалидно:\n";
        foreach ($validation['errors'] as $error) {
            echo "  - {$error}\n";
        }
    } elseif (!empty($validation['warnings'])) {
        echo "⚠️  Изображение " . ($index + 1) . " имеет предупреждения:\n";
        foreach ($validation['warnings'] as $warning) {
            echo "  - {$warning}\n";
        }
    } else {
        echo "✅ Изображение " . ($index + 1) . " валидно\n";
    }
}
```

## Связанные классы

- [`ImageType`](../enums/image-type.md) - Типы изображений
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](film.md) - Модель фильма
