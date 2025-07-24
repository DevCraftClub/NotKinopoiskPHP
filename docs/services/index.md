# Сервисы

Сервисы для работы с различными компонентами Kinopoisk API.

---

**📚 Навигация:** [Главная](../index.md) → Сервисы

---

## 📋 Список сервисов

### 🎬 [FilmService](film-service.md)

Сервис для работы с фильмами, сериалами и связанным контентом.

**Основные возможности:**

- Получение информации о фильмах
- Поиск фильмов по ключевым словам
- Получение топ фильмов
- Работа со съемочной группой
- Получение отзывов и фактов
- Работа с медиа контентом

**Связанные модели:**

- [Film](../models/film.md)
- [Staff](../models/staff.md)
- [Review](../models/review.md)
- [Fact](../models/fact.md)
- [Image](../models/image.md)
- [Video](../models/video.md)

### 👥 [PersonService](person-service.md)

Сервис для работы с персонами (актеры, режиссеры, сценаристы).

**Основные возможности:**

- Получение информации о персонах
- Поиск персон по имени
- Получение фильмографии
- Работа с биографией и фактами

**Связанные модели:**

- [Person](../models/person.md)
- [PersonFilm](../models/person-film.md)
- [PersonSpouse](../models/person-spouse.md)

### 🎥 [MediaService](media-service.md)

Сервис для работы с медиа контентом (изображения, видео).

**Основные возможности:**

- Получение изображений фильмов
- Работа с видео контентом
- Фильтрация по типам медиа

**Связанные модели:**

- [Image](../models/image.md)
- [Video](../models/video.md)
- [ImageType](../enums/image-type.md)
- [VideoSite](../enums/video-site.md)

### 👤 [UserService](user-service.md)

Сервис для работы с пользовательскими данными.

**Основные возможности:**

- Получение информации об API ключе
- Работа с квотами запросов
- Получение пользовательских голосов

**Связанные модели:**

- [ApiKeyInfo](../models/api-key-info.md)
- [ApiKeyQouta](../models/api-key-qouta.md)
- [UserVote](../models/user-vote.md)

## 🔗 Общие компоненты

Все сервисы используют общие компоненты:

### Исключения

- [ApiException](../exceptions/api-exception.md) - Базовое исключение API
- [InvalidApiKeyException](../exceptions/invalid-api-key-exception.md) - Неверный API ключ
- [RateLimitException](../exceptions/rate-limit-exception.md) - Превышение лимита запросов
- [ResourceNotFoundException](../exceptions/resource-not-found-exception.md) - Ресурс не найден
- [KpValidationException](../exceptions/kp-validation-exception.md) - Ошибка валидации

### Ответы

- [DefaultResponse](../responses/default-response.md) - Базовый ответ
- [PaginatedResponse](../responses/paginated-response.md) - Пагинированный ответ
- [KeywordSearchResponse](../responses/keyword-search-response.md) - Ответ поиска

## 🚀 Быстрый старт

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Работа с фильмами
$film = $client->films->getById(301);
$searchResults = $client->films->searchByKeyword('матрица');

// Работа с персонами
$person = $client->persons->getById(123);
$persons = $client->persons->searchByName('Том Круз');

// Работа с медиа
$images = $client->media->getImages(301);
$videos = $client->media->getVideos(301);

// Работа с пользователем
$apiInfo = $client->user->getApiKeyInfo();
```

## 📖 Примеры использования

### Получение полной информации о фильме

```php
$filmId = 301;

// Основная информация
$film = $client->films->getById($filmId);

// Съемочная группа
$staff = $client->films->getStaff($filmId);

// Отзывы
$reviews = $client->films->getReviews($filmId);

// Факты
$facts = $client->films->getFacts($filmId);

// Изображения
$posters = $client->media->getImages($filmId, ImageType::POSTER);
$screenshots = $client->media->getImages($filmId, ImageType::SCREENSHOT);

// Видео
$videos = $client->media->getVideos($filmId);
```

### Поиск и анализ

```php
// Поиск фильмов
$searchResults = $client->films->searchByKeyword('драма');

// Получение топ фильмов
$topFilms = $client->films->getTop();

// Поиск персон
$persons = $client->persons->searchByName('Кристофер Нолан');

// Получение фильмографии
$films = $client->persons->getFilms(123);
```

## 🔧 Обработка ошибок

```php
try {
    $film = $client->films->getById(999999);
} catch (ResourceNotFoundException $e) {
    echo "Фильм не найден: {$e->getMessage()}\n";
} catch (RateLimitException $e) {
    echo "Превышен лимит запросов\n";
    // Ждем и повторяем
    sleep(1);
} catch (InvalidApiKeyException $e) {
    echo "Неверный API ключ\n";
} catch (ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
}
```

## 📊 Статистика использования

### FilmService

- **Методы:** 15+
- **Модели:** 10+
- **Перечисления:** 8+

### PersonService

- **Методы:** 8+
- **Модели:** 3+
- **Перечисления:** 2+

### MediaService

- **Методы:** 6+
- **Модели:** 2+
- **Перечисления:** 2+

### UserService

- **Методы:** 4+
- **Модели:** 3+
- **Перечисления:** 1+

---

**📚 Навигация:** [Главная](../index.md) → Сервисы
