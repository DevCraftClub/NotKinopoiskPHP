# ImageType

Типы изображений в Kinopoisk API.

## Описание

`ImageType` определяет различные типы изображений, которые могут быть связаны с фильмом: кадры из фильма, постеры, фоны и т.д.

## Значения enum

### Основные типы изображений

- `STILL` - Кадр из фильма
- `SHOOTING` - Изображения со съемок
- `POSTER` - Постер
- `FAN_ART` - Фан-арты
- `PROMO` - Промо
- `CONCEPT` - Концепт-арты
- `WALLPAPER` - Обои
- `COVER` - Обложки
- `SCREENSHOT` - Скриншоты
- `BACKGROUND` - Фон
- `PREVIEW` - Превью

## Методы

### getDisplayName()

Получает человекочитаемое название типа изображения.

```php
public function getDisplayName(): string
```

#### Возвращаемое значение

- `string` - Название типа изображения на русском языке

#### Пример использования

```php
echo ImageType::STILL->getDisplayName(); // "Кадр из фильма"
echo ImageType::POSTER->getDisplayName(); // "Постер"
echo ImageType::FAN_ART->getDisplayName(); // "Фан-арты"
```

### isMain()

Проверяет, является ли тип изображения основным.

```php
public function isMain(): bool
```

#### Возвращаемое значение

- `bool` - `true` если основной тип, `false` в противном случае

#### Описание

Основные типы изображений - это те, которые обычно используются для отображения в галереях и каталогах.

#### Пример использования

```php
if (ImageType::POSTER->isMain()) {
    echo "Основной тип изображения";
}

// Основные типы: POSTER, STILL, FAN_ART, CONCEPT
```

### isPromo()

Проверяет, является ли тип изображения промо-материалом.

```php
public function isPromo(): bool
```

#### Возвращаемое значение

- `bool` - `true` если промо-материал, `false` в противном случае

#### Описание

Промо-материалы используются для рекламы и продвижения фильма.

#### Пример использования

```php
if (ImageType::PROMO->isPromo()) {
    echo "Промо-материал";
}

// Промо-типы: PROMO, POSTER, COVER, WALLPAPER
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ImageType;

// Создание клиента
$client = new Client('your-api-key');

// Получение различных типов изображений для фильма
$filmId = 301; // Матрица

echo "=== Изображения фильма ===\n";

// Постеры
$posters = $client->films->getImages($filmId, ImageType::POSTER);
echo "📰 Постеров: " . count($posters) . "\n";

// Кадры из фильма
$stills = $client->films->getImages($filmId, ImageType::STILL);
echo "🎬 Кадров из фильма: " . count($stills) . "\n";

// Фан-арты
$fanArts = $client->films->getImages($filmId, ImageType::FAN_ART);
echo "🎨 Фан-артов: " . count($fanArts) . "\n";

// Промо-материалы
$promos = $client->films->getImages($filmId, ImageType::PROMO);
echo "📢 Промо-материалов: " . count($promos) . "\n";

// Концепт-арты
$concepts = $client->films->getImages($filmId, ImageType::CONCEPT);
echo "🎭 Концепт-артов: " . count($concepts) . "\n";

// Обои
$wallpapers = $client->films->getImages($filmId, ImageType::WALLPAPER);
echo "🖼️  Обоев: " . count($wallpapers) . "\n";
```

## Работа с типами изображений

```php
// Получение всех типов изображений
$allImageTypes = [
    ImageType::STILL,
    ImageType::SHOOTING,
    ImageType::POSTER,
    ImageType::FAN_ART,
    ImageType::PROMO,
    ImageType::CONCEPT,
    ImageType::WALLPAPER,
    ImageType::COVER,
    ImageType::SCREENSHOT,
    ImageType::BACKGROUND,
    ImageType::PREVIEW
];

echo "=== Все типы изображений ===\n";
foreach ($allImageTypes as $type) {
    echo "- {$type->value}: {$type->getDisplayName()}\n";
}

// Фильтрация основных типов
$mainTypes = array_filter($allImageTypes, fn($type) => $type->isMain());
echo "\n=== Основные типы ===\n";
foreach ($mainTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}

// Фильтрация промо-материалов
$promoTypes = array_filter($allImageTypes, fn($type) => $type->isPromo());
echo "\n=== Промо-материалы ===\n";
foreach ($promoTypes as $type) {
    echo "- {$type->getDisplayName()}\n";
}
```

## Получение изображений по типам

```php
function getImagesByType(Client $client, int $filmId, ImageType $imageType): array {
    try {
        return $client->films->getImages($filmId, $imageType);
    } catch (\Exception $e) {
        echo "Ошибка при получении {$imageType->getDisplayName()}: {$e->getMessage()}\n";
        return [];
    }
}

// Получение всех изображений для фильма
$filmId = 301;
$imageTypes = [ImageType::POSTER, ImageType::STILL, ImageType::FAN_ART];

echo "=== Изображения фильма ===\n";
foreach ($imageTypes as $type) {
    $images = getImagesByType($client, $filmId, $type);
    echo "{$type->getDisplayName()}: " . count($images) . " изображений\n";

    // Вывод первых 3 изображений
    foreach (array_slice($images, 0, 3) as $image) {
        echo "  - {$image->imageUrl}\n";
    }
    echo "\n";
}
```

## Создание галереи изображений

```php
function createImageGallery(Client $client, int $filmId): array {
    $gallery = [];

    // Получение основных типов изображений
    $mainTypes = [ImageType::POSTER, ImageType::STILL, ImageType::FAN_ART, ImageType::CONCEPT];

    foreach ($mainTypes as $type) {
        $images = getImagesByType($client, $filmId, $type);
        if (!empty($images)) {
            $gallery[$type->value] = [
                'type' => $type->getDisplayName(),
                'count' => count($images),
                'images' => $images
            ];
        }
    }

    return $gallery;
}

// Использование
$gallery = createImageGallery($client, 301);

echo "=== Галерея изображений ===\n";
foreach ($gallery as $typeKey => $typeData) {
    echo "📁 {$typeData['type']} ({$typeData['count']} изображений)\n";

    foreach (array_slice($typeData['images'], 0, 2) as $image) {
        echo "  🖼️  {$image->imageUrl}\n";
    }
    echo "\n";
}
```

## Связанные классы

- [`Image`](../models/image.md) - Модель изображения
- [`FilmService`](../services/film-service.md) - Сервис для работы с фильмами
- [`Film`](../models/film.md) - Модель фильма
