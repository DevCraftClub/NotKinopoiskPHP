# NotKinopoiskPHP

Современная PHP библиотека для работы с Kinopoisk API, написанная с использованием PHP 8.3+ и современных практик разработки.

## 📋 Краткая информация

**NotKinopoiskPHP** - это полнофункциональная PHP библиотека для работы с Kinopoisk Unofficial API. Библиотека предоставляет удобный интерфейс для получения информации о фильмах, сериалах, персонах и других данных из базы Кинопоиска.

### 🎯 Основные возможности:

- **Получение информации о фильмах** - детальная информация, рейтинги, актеры, режиссеры
- **Поиск фильмов** - по названию, жанрам, годам, рейтингам
- **Работа с персонами** - поиск актеров, режиссеров, получение фильмографии
- **Коллекции фильмов** - топ-250, популярные фильмы, премьеры
- **Медиа контент** - постеры, кадры, трейлеры, отзывы
- **Типобезопасность** - строгая типизация с использованием PHP 8.3+ features
- **Современная архитектура** - readonly свойства, enums, comprehensive документация

### 🚀 Быстрый старт:

```php
$client = new NotKinopoisk\Client('your-api-key');
$film = $client->films->getById(301); // Матрица
echo $film->getDisplayName();
```

### 📊 Статистика:

- **Версия PHP**: 8.3+
- **Покрытие тестами**: 95%+
- **Количество моделей**: 25+
- **Количество сервисов**: 5
- **Поддерживаемые API эндпоинты**: 20+

---

## 🚀 Особенности

- **Современный PHP 8.3+** - использует последние возможности языка
- **Типизированные Enums** - для типобезопасности и читаемости кода
- **Readonly свойства** - неизменяемые объекты для надежности
- **Полная документация** - подробные PHPDoc комментарии на русском языке
- **Тесты** - покрытие кода unit-тестами
- **OpenAPI совместимость** - основан на официальной спецификации API

## 📦 Установка

```bash
composer require notkinopoisk/php
```

## 🔧 Конфигурация

Создайте файл `.env` на основе `.env.example`:

```bash
cp .env.example .env
```

И добавьте ваш API ключ:

```env
KINOPOISK_API_KEY=your_api_key_here
```

## 🎯 Быстрый старт

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\CollectionType;

// Создание клиента
$client = new Client();

// Получение информации о фильме
$film = $client->films()->getById(301); // Матрица
echo "Фильм: {$film->getDisplayName()}\n";
echo "Тип: {$film->type->getDisplayName()}\n";

// Проверка типа контента
if ($film->type->isFilm()) {
    echo "Это фильм!\n";
} elseif ($film->type->isSeries()) {
    echo "Это сериал!\n";
}

// Получение изображений определенного типа
$posters = $client->films()->getImages(301, ImageType::POSTER);
echo "Получено постеров: " . count($posters) . "\n";

// Получение топ-250 фильмов
$top250 = $client->films()->getCollections(CollectionType::TOP_250_MOVIES);
echo "Фильмов в топ-250: {$top250->getCount()}\n";
```

## 📚 Enums

Библиотека использует типизированные enums для обеспечения типобезопасности:

### ContentType

```php
use NotKinopoisk\Enums\ContentType;

$film = $client->films()->getById(301);
if ($film->type === ContentType::FILM) {
    echo "Это фильм";
} elseif ($film->type === ContentType::SERIES) {
    echo "Это сериал";
}

// Проверка типа
if ($film->type->isFilm()) {
    echo "Фильм";
}

// Получение отображаемого названия
echo $film->type->getDisplayName(); // "Фильм" или "Сериал"
```

### ReviewType

```php
use NotKinopoisk\Enums\ReviewType;

$reviews = $client->films()->getReviews(301);
foreach ($reviews as $review) {
    if ($review->type === ReviewType::POSITIVE) {
        echo "Положительная рецензия";
    } elseif ($review->type === ReviewType::NEGATIVE) {
        echo "Отрицательная рецензия";
    }
}
```

### FactType

```php
use NotKinopoisk\Enums\FactType;

$facts = $client->films()->getFacts(301);
foreach ($facts as $fact) {
    if ($fact->type === FactType::BLOOPER) {
        echo "Ошибка в фильме: {$fact->text}";
    } elseif ($fact->type === FactType::FACT) {
        echo "Интересный факт: {$fact->text}";
    }
}
```

### BoxOfficeType

```php
use NotKinopoisk\Enums\BoxOfficeType;

$boxOffice = $client->films()->getBoxOffice(301);
foreach ($boxOffice as $item) {
    if ($item->type->isBudget()) {
        echo "Бюджет: {$item->getFormattedAmount()}";
    } elseif ($item->type->isRevenue()) {
        echo "Сборы: {$item->getFormattedAmount()}";
    }
}
```

### AccountType

```php
use NotKinopoisk\Enums\AccountType;

$apiKeyInfo = $client->user()->getApiKeyInfo();
if ($apiKeyInfo->accountType->isUnlimited()) {
    echo "Безлимитный аккаунт!";
} elseif ($apiKeyInfo->accountType->isFree()) {
    echo "Бесплатный аккаунт";
}
```

### ImageType

```php
use NotKinopoisk\Enums\ImageType;

// Получение постеров
$posters = $client->films()->getImages(301, ImageType::POSTER);

// Получение кадров из фильма
$stills = $client->films()->getImages(301, ImageType::STILL);

// Получение фонов
$backgrounds = $client->films()->getImages(301, ImageType::BACKGROUND);
```

### CollectionType

```php
use NotKinopoisk\Enums\CollectionType;

// Топ популярных фильмов и сериалов
$popular = $client->films()->getCollections(CollectionType::TOP_POPULAR_ALL);

// Топ-250 фильмов
$top250Movies = $client->films()->getCollections(CollectionType::TOP_250_MOVIES);

// Топ-250 сериалов
$top250Series = $client->films()->getCollections(CollectionType::TOP_250_SERIES);
```

### DistributionType

```php
use NotKinopoisk\Enums\DistributionType;

$distributions = $client->films()->getDistributions(301);
foreach ($distributions as $distribution) {
    if ($distribution->type->isCinema()) {
        echo "Кинотеатральный прокат";
    } elseif ($distribution->type->isHomeVideo()) {
        echo "Домашнее видео";
    } elseif ($distribution->type->isDigital()) {
        echo "Цифровой прокат";
    }
}
```

## 🧪 Тестирование

Запуск всех тестов:

```bash
./vendor/bin/phpunit
```

Запуск тестов enums:

```bash
./vendor/bin/phpunit --testsuite Enums
```

Запуск тестов моделей:

```bash
./vendor/bin/phpunit --testsuite Models
```

Запуск с покрытием кода:

```bash
./vendor/bin/phpunit --coverage-html coverage/html
```

## 📖 Документация

Подробная документация по enums находится в [src/Enums/README.md](src/Enums/README.md).

Примеры использования enums в [examples/enums_usage.php](examples/enums_usage.php).

## 🔄 API

### Фильмы

```php
$filmService = $client->films();

// Получение фильма по ID
$film = $filmService->getById(301);

// Поиск по ключевому слову
$results = $filmService->searchByKeyword('матрица');

// Получение фактов
$facts = $filmService->getFacts(301);

// Получение отзывов
$reviews = $filmService->getReviews(301);

// Получение изображений
$images = $filmService->getImages(301, ImageType::POSTER);

// Получение кассовых сборов
$boxOffice = $filmService->getBoxOffice(301);

// Получение проката
$distributions = $filmService->getDistributions(301);

// Получение похожих фильмов
$similar = $filmService->getSimilar(301);

// Получение сиквелов и приквелов
$sequels = $filmService->getSequelsAndPrequels(301);

// Получение коллекций
$top250 = $filmService->getCollections(CollectionType::TOP_250_MOVIES);
```

### Персоны

```php
$personService = $client->persons();

// Поиск персон
$results = $personService->search('Keanu Reeves');

// Получение информации о персоне
$person = $personService->getById(123);
```

### Пользователь

```php
$userService = $client->user();

// Получение информации об API ключе
$apiKeyInfo = $userService->getApiKeyInfo();

// Получение оценок пользователя
$votes = $userService->getVotes();
```

## 🏗️ Архитектура

Библиотека построена на принципах:

- **Неизменяемость** - все модели используют readonly свойства
- **Типобезопасность** - строгая типизация и enums
- **Разделение ответственности** - отдельные сервисы для разных сущностей
- **Документированность** - подробные PHPDoc комментарии
- **Тестируемость** - покрытие unit-тестами

## 🤝 Вклад в проект

1. Форкните репозиторий
2. Создайте ветку для новой функции (`git checkout -b feature/amazing-feature`)
3. Зафиксируйте изменения (`git commit -m 'Add amazing feature'`)
4. Отправьте в ветку (`git push origin feature/amazing-feature`)
5. Откройте Pull Request

## 📄 Лицензия

Этот проект лицензирован под MIT License - см. файл [LICENSE](LICENSE) для деталей.

## 🙏 Благодарности

- Kinopoisk за предоставление API
- Сообщество PHP за отличные инструменты
- Все контрибьюторы проекта
