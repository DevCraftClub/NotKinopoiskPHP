# NotKinopoisk PHP Library - Документация

Полная документация PHP библиотеки для работы с Kinopoisk Unofficial API.

## 📚 Содержание

### 🚀 Быстрый старт

- [Основной клиент](client.md) - Главный класс для работы с API
- [Примеры использования](../examples/) - Готовые примеры кода
- [Карта навигации](navigation-map.md) - Интерактивная карта всей документации

### 📦 Основные компоненты

#### 🔧 Сервисы

- [FilmService](services/film-service.md) - Работа с фильмами
- [PersonService](services/person-service.md) - Работа с персонами
- [MediaService](services/media-service.md) - Работа с медиа
- [UserService](services/user-service.md) - Работа с пользователями

#### 📊 Модели данных

- [Film](models/film.md) - Модель фильма
- [Person](models/person.md) - Модель персоны
- [Staff](models/staff.md) - Модель съемочной группы
- [Review](models/review.md) - Модель отзыва
- [Image](models/image.md) - Модель изображения
- [Video](models/video.md) - Модель видео
- [Fact](models/fact.md) - Модель факта
- [Award](models/award.md) - Модель награды
- [BoxOffice](models/box-office.md) - Модель кассовых сборов
- [Country](models/country.md) - Модель страны
- [Genre](models/genre.md) - Модель жанра
- [Episode](models/episode.md) - Модель эпизода
- [Season](models/season.md) - Модель сезона
- [ExternalSource](models/external-source.md) - Модель внешнего источника
- [Distribution](models/distribution.md) - Модель дистрибуции
- [FilmSearchResult](models/film-search-result.md) - Модель результата поиска
- [PersonSpouse](models/person-spouse.md) - Модель супруга
- [PersonFilm](models/person-film.md) - Модель фильма персоны
- [UserVote](models/user-vote.md) - Модель голоса пользователя
- [FilmCollection](models/film-collection.md) - Модель коллекции фильмов
- [Filters](models/filters.md) - Модель фильтров
- [RelatedFilm](models/related-film.md) - Модель связанного фильма
- [ApiKeyInfo](models/api-key-info.md) - Модель информации об API ключе
- [ApiKeyQouta](models/api-key-qouta.md) - Модель квоты API ключа
- [MediaPost](models/media-post.md) - Модель медиа поста

#### 🔢 Перечисления (Enums)

- [ImageType](enums/image-type.md) - Типы изображений
- [ReviewOrder](enums/review-order.md) - Порядок сортировки отзывов
- [ReviewType](enums/review-type.md) - Типы отзывов
- [FactType](enums/fact-type.md) - Типы фактов
- [ProfessionKey](enums/profession-key.md) - Ключи профессий
- [VideoSite](enums/video-site.md) - Сайты видео
- [BoxOfficeType](enums/box-office-type.md) - Типы кассовых сборов
- [DistributionType](enums/distribution-type.md) - Типы дистрибуции
- [RelationType](enums/relation-type.md) - Типы связей
- [Sex](enums/sex.md) - Пол
- [ApiVersion](enums/api-version.md) - Версии API
- [Month](enums/month.md) - Месяцы
- [FilmOrder](enums/film-order.md) - Порядок сортировки фильмов
- [ContentType](enums/content-type.md) - Типы контента
- [CollectionType](enums/collection-type.md) - Типы коллекций
- [DistributionSubType](enums/distribution-sub-type.md) - Подтипы дистрибуции
- [AccountType](enums/account-type.md) - Типы аккаунтов

#### 📤 Ответы API

- [DefaultResponse](responses/default-response.md) - Базовый ответ
- [PaginatedResponse](responses/paginated-response.md) - Пагинированный ответ
- [KeywordSearchResponse](responses/keyword-search-response.md) - Ответ поиска по ключевым словам

#### ⚠️ Исключения

- [ApiException](exceptions/api-exception.md) - Базовое исключение API
- [InvalidApiKeyException](exceptions/invalid-api-key-exception.md) - Неверный API ключ
- [RateLimitException](exceptions/rate-limit-exception.md) - Превышение лимита запросов
- [ResourceNotFoundException](exceptions/resource-not-found-exception.md) - Ресурс не найден
- [KpValidationException](exceptions/kp-validation-exception.md) - Ошибка валидации

#### 🔗 Интерфейсы

- [ModelInterface](interfaces/model-interface.md) - Интерфейс модели
- [ResponseInterface](interfaces/response-interface.md) - Интерфейс ответа

## 🚀 Быстрый старт

### Установка

```bash
composer require notkinopoisk/php-client
```

### Базовое использование

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение фильма по ID
$film = $client->films->getById(301);

echo "Фильм: {$film->getDisplayName()}\n";
echo "Рейтинг: {$film->rating}\n";
echo "Год: {$film->year}\n";
```

## 📖 Подробные примеры

### Работа с фильмами

```php
// Поиск фильмов
$searchResults = $client->films->searchByKeyword('матрица');

// Получение топ фильмов
$topFilms = $client->films->getTop();

// Получение информации о фильме
$film = $client->films->getById(301);
$staff = $client->films->getStaff(301);
$facts = $client->films->getFacts(301);
```

### Работа с персонами

```php
// Поиск персон
$persons = $client->persons->searchByName('Том Круз');

// Получение информации о персоне
$person = $client->persons->getById(123);
$films = $client->persons->getFilms(123);
```

### Работа с медиа

```php
// Получение изображений
$images = $client->media->getImages(301, ImageType::POSTER);

// Получение видео
$videos = $client->media->getVideos(301);
```

## 🔧 Конфигурация

### Настройка клиента

```php
$client = new Client('your-api-key', [
    'timeout' => 30,
    'retry_attempts' => 3,
    'api_version' => ApiVersion::V2_1
]);
```

### Обработка ошибок

```php
try {
    $film = $client->films->getById(999999);
} catch (ResourceNotFoundException $e) {
    echo "Фильм не найден: {$e->getMessage()}\n";
} catch (RateLimitException $e) {
    echo "Превышен лимит запросов\n";
} catch (ApiException $e) {
    echo "Ошибка API: {$e->getMessage()}\n";
}
```

## 📊 Структура проекта

```
src/
├── Client.php              # Основной клиент
├── Services/               # Сервисы для работы с API
│   ├── FilmService.php
│   ├── PersonService.php
│   ├── MediaService.php
│   └── UserService.php
├── Models/                 # Модели данных
│   ├── Film.php
│   ├── Person.php
│   ├── Staff.php
│   └── ...
├── Enums/                  # Перечисления
│   ├── ImageType.php
│   ├── ReviewOrder.php
│   └── ...
├── Responses/              # Классы ответов
│   ├── DefaultResponse.php
│   ├── PaginatedResponse.php
│   └── ...
├── Exceptions/             # Исключения
│   ├── ApiException.php
│   ├── InvalidApiKeyException.php
│   └── ...
└── Interfaces/             # Интерфейсы
    ├── ModelInterface.php
    └── ResponseInterface.php
```

## 🧪 Тестирование

### Запуск тестов

```bash
# Все тесты
XDEBUG_MODE=coverage KINOPOISK_API_KEY=your-key composer test

# Только unit тесты
composer test -- --testsuite=Unit

# Только интеграционные тесты
composer test -- --testsuite=Integration
```

### Проверка качества кода

```bash
# PHPStan анализ
XDEBUG_MODE=coverage KINOPOISK_API_KEY=your-key composer phpstan

# PHP CodeSniffer
composer phpcs
```

## 📝 Примеры использования

Смотрите папку [`examples/`](../examples/) для готовых примеров:

- [Базовое использование](../examples/basic_usage.php)
- [Примеры конфигурации](../examples/configuration_examples.php)
- [Работа с перечислениями](../examples/enums_usage.php)
- [Использование с .env](../examples/with_dotenv.php)

## 🔗 Полезные ссылки

- [GitHub репозиторий](https://github.com/your-username/NotKinopoiskPHP)
- [Kinopoisk Unofficial API](https://kinopoiskapiunofficial.tech/)
- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/)

## 📄 Лицензия

Этот проект распространяется под лицензией MIT. См. файл [LICENSE](../LICENSE) для подробностей.

## 🤝 Поддержка

Если у вас есть вопросы или предложения:

1. Создайте [Issue](https://github.com/your-username/NotKinopoiskPHP/issues)
2. Напишите на email: dev@devcraft.club
3. Обратитесь к [документации](https://kinopoiskapiunofficial.tech/)

---

**Версия документации:** 1.0.0  
**Последнее обновление:** <?php echo date('Y-m-d'); ?>
