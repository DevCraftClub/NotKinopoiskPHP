# Модели данных

Модели для представления данных из Kinopoisk API.

---

**📚 Навигация:** [Главная](../index.md) → Модели

---

## 📋 Категории моделей

### 🎬 Основные модели фильмов

- [Film](film.md) - Основная модель фильма/сериала
- [FilmSearchResult](film-search-result.md) - Результат поиска фильмов
- [FilmCollection](film-collection.md) - Коллекция фильмов
- [RelatedFilm](related-film.md) - Связанный фильм
- [Episode](episode.md) - Эпизод сериала
- [Season](season.md) - Сезон сериала

### 👥 Модели персон

- [Person](person.md) - Основная модель персоны
- [Staff](staff.md) - Съемочная группа
- [PersonFilm](person-film.md) - Фильм персоны
- [PersonSpouse](person-spouse.md) - Супруг персоны

### 📊 Модели контента

- [Review](review.md) - Отзыв
- [Fact](fact.md) - Факт
- [Image](image.md) - Изображение
- [Video](video.md) - Видео
- [MediaPost](media-post.md) - Медиа пост

### 🏆 Модели наград и статистики

- [Award](award.md) - Награда
- [BoxOffice](box-office.md) - Кассовые сборы
- [UserVote](user-vote.md) - Голос пользователя
- [ExternalSource](external-source.md) - Внешний источник

### 🌍 Справочные модели

- [Country](country.md) - Страна
- [Genre](genre.md) - Жанр
- [Distribution](distribution.md) - Дистрибуция

### 🔑 Модели API

- [ApiKeyInfo](api-key-info.md) - Информация об API ключе
- [ApiKeyQouta](api-key-qouta.md) - Квота API ключа
- [Filters](filters.md) - Фильтры

### 🔍 Модели поиска

- [PersonByNameResult](person-by-name-result.md) - Результат поиска персоны по имени

## 🔗 Связанные компоненты

### Сервисы

- [FilmService](../services/film-service.md) - Работа с фильмами
- [PersonService](../services/person-service.md) - Работа с персонами
- [MediaService](../services/media-service.md) - Работа с медиа
- [UserService](../services/user-service.md) - Работа с пользователями

### Перечисления

- [ImageType](../enums/image-type.md) - Типы изображений
- [ReviewType](../enums/review-type.md) - Типы отзывов
- [FactType](../enums/fact-type.md) - Типы фактов
- [ProfessionKey](../enums/profession-key.md) - Ключи профессий
- [VideoSite](../enums/video-site.md) - Сайты видео
- [BoxOfficeType](../enums/box-office-type.md) - Типы кассовых сборов
- [ContentType](../enums/content-type.md) - Типы контента
- [Sex](../enums/sex.md) - Пол
- [RelationType](../enums/relation-type.md) - Типы связей

### Интерфейсы

- [ModelInterface](../interfaces/model-interface.md) - Базовый интерфейс модели

## 🚀 Быстрый старт

### Создание модели из массива данных

````php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Models\Film;
use NotKinopoisk\Models\Person;
use NotKinopoisk\Models\Staff;

// Создание модели фильма
$filmData = [
    'kinopoiskId' => 301,
    'nameRu' => 'Матрица',
    'nameEn' => 'The Matrix',
    'rating' => 8.7,
    'year' => 1999
];

$film = Film::fromArray($filmData);

// Создание модели персоны
$personData = [
    'kinopoiskId' => 123,
    'nameRu' => 'Киану Ривз',
    'nameEn' => 'Keanu Reeves',
    'sex' => 'MALE'
];

$person = Person::fromArray($personData);

// Создание модели съемочной группы
$staffData = [
    'kinopoiskId' => 456,
    'nameRu' => 'Лана Вачовски',
    'nameEn' => 'Lana Wachowski',
    'professionKey' => 'DIRECTOR'
];

$staff = Staff::fromArray($staffData);

// Создание модели информации об API ключе
$apiKeyData = [
    'totalQuota' => ['value' => 1000, 'used' => 150],
    'dailyQuota' => ['value' => 100, 'used' => 25],
    'accountType' => 'FREE'
];

$apiKeyInfo = \NotKinopoisk\Models\ApiKeyInfo::fromArray($apiKeyData);

// Создание модели результата поиска персоны
$personSearchData = [
    'kinopoiskId' => 66539,
    'webUrl' => '10096',
    'nameRu' => 'Винс Гиллиган',
    'nameEn' => 'Vince Gilligan',
    'sex' => 'MALE',
    'posterUrl' => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
];

$personResult = \NotKinopoisk\Models\PersonByNameResult::fromArray($personSearchData);

### Работа с моделями

```php
// Получение отображаемого имени
echo $film->getDisplayName(); // "Матрица (The Matrix)"

// Проверка свойств
if ($film->hasRating()) {
    echo "Рейтинг: {$film->rating}\n";
}

if ($person->isMale()) {
    echo "Пол: Мужской\n";
}

if ($staff->isDirector()) {
    echo "Профессия: Режиссер\n";
}

// Преобразование в массив
$filmArray = $film->toArray();

// Работа с информацией об API ключе
echo "Тип аккаунта: {$apiKeyInfo->accountType->getDisplayName()}\n";
echo "Осталось запросов: {$apiKeyInfo->getRemainingTotalQuota()}\n";

if ($apiKeyInfo->isUnlimited()) {
    echo "Безлимитный аккаунт!\n";
}

// Работа с результатом поиска персоны
echo "Персона: {$personResult->getDisplayName()}\n";
echo "Полное имя: {$personResult->getFullName()}\n";

if ($personResult->isMale()) {
    echo "Пол: Мужской\n";
}
````

## 📊 Статистика моделей

**Всего моделей: 28**

### Основные модели фильмов (6)

- **Film** - Самая сложная модель с 30+ свойствами
- **FilmSearchResult** - Упрощенная версия для поиска
- **FilmCollection** - Коллекция фильмов
- **RelatedFilm** - Связанные фильмы
- **Episode** - Эпизоды сериалов
- **Season** - Сезоны сериалов

### Модели персон (4)

- **Person** - Основная модель персоны
- **Staff** - Съемочная группа
- **PersonFilm** - Фильмография
- **PersonSpouse** - Семейные связи

### Модели контента (5)

- **Review** - Отзывы пользователей
- **Fact** - Интересные факты
- **Image** - Изображения
- **Video** - Видео контент
- **MediaPost** - Медиа посты

### Модели наград и статистики (4)

- **Award** - Награды и номинации
- **BoxOffice** - Кассовые сборы
- **UserVote** - Пользовательские голоса
- **ExternalSource** - Внешние источники

### Справочные модели (3)

- **Country** - Страны
- **Genre** - Жанры
- **Distribution** - Дистрибуция

### Модели API (3)

- **ApiKeyInfo** - Информация об API ключе
- **ApiKeyQouta** - Квоты запросов
- **Filters** - Фильтры для поиска

### Модели поиска (1)

- **PersonByNameResult** - Результаты поиска персон

## 🔧 Общие методы

Все модели реализуют общие методы:

### fromArray()

```php
public static function fromArray(array $data): self
```

Создает экземпляр модели из массива данных API.

### toArray()

```php
public function toArray(): array
```

Преобразует модель в массив.

### getDisplayName()

```php
public function getDisplayName(): string
```

Возвращает отображаемое имя объекта.

## 📖 Примеры использования

### Работа с фильмом

```php
$film = Film::fromArray($filmData);

echo "Название: {$film->getDisplayName()}\n";
echo "Год: {$film->year}\n";
echo "Рейтинг: {$film->rating}\n";
echo "Описание: " . substr($film->description, 0, 100) . "...\n";

// Проверка свойств
if ($film->isSeries()) {
    echo "Тип: Сериал\n";
    echo "Количество сезонов: {$film->seasonsCount}\n";
} else {
    echo "Тип: Фильм\n";
    echo "Длительность: {$film->filmLength} мин\n";
}

// Работа с жанрами
if (!empty($film->genres)) {
    echo "Жанры: " . implode(', ', array_map(fn($g) => $g->genre, $film->genres)) . "\n";
}

// Работа со странами
if (!empty($film->countries)) {
    echo "Страны: " . implode(', ', array_map(fn($c) => $c->country, $film->countries)) . "\n";
}
```

### Работа с персоной

```php
$person = Person::fromArray($personData);

echo "Имя: {$person->getDisplayName()}\n";
echo "Дата рождения: {$person->birthday}\n";
echo "Место рождения: {$person->birthplace}\n";

// Проверка пола
if ($person->isMale()) {
    echo "Пол: Мужской\n";
} elseif ($person->isFemale()) {
    echo "Пол: Женский\n";
} else {
    echo "Пол: Не указан\n";
}

// Работа с профессиями
if (!empty($person->profession)) {
    echo "Профессии: " . implode(', ', $person->profession) . "\n";
}
```

### Работа со съемочной группой

```php
$staff = Staff::fromArray($staffData);

echo "Имя: {$staff->getDisplayName()}\n";
echo "Профессия: {$staff->getProfessionName()}\n";

// Проверка конкретных профессий
if ($staff->isDirector()) {
    echo "Роль: Режиссер\n";
} elseif ($staff->isActor()) {
    echo "Роль: Актер\n";
} elseif ($staff->isWriter()) {
    echo "Роль: Сценарист\n";
} elseif ($staff->isProducer()) {
    echo "Роль: Продюсер\n";
}
```

### Работа с информацией об API ключе

```php
$apiKeyInfo = ApiKeyInfo::fromArray($apiKeyData);

echo "Тип аккаунта: {$apiKeyInfo->accountType->getDisplayName()}\n";
echo "Общий лимит: {$apiKeyInfo->totalQuota->value}\n";
echo "Использовано: {$apiKeyInfo->totalQuota->used}\n";
echo "Осталось: {$apiKeyInfo->getRemainingTotalQuota()}\n";

// Проверка типа аккаунта
if ($apiKeyInfo->isUnlimited()) {
    echo "Безлимитный аккаунт - ограничений нет\n";
} else {
    echo "Ограниченный аккаунт\n";

    // Проверка лимитов
    $remainingTotal = $apiKeyInfo->getRemainingTotalQuota();
    $remainingDaily = $apiKeyInfo->getRemainingDailyQuota();

    if ($remainingTotal <= 0) {
        echo "Общий лимит исчерпан!\n";
    } else {
        echo "Осталось общих запросов: {$remainingTotal}\n";
    }

    if ($remainingDaily <= 0) {
        echo "Дневной лимит исчерпан!\n";
    } else {
        echo "Осталось дневных запросов: {$remainingDaily}\n";
    }
}
```

### Работа с результатом поиска персоны

```php
$personResult = PersonByNameResult::fromArray($personSearchData);

echo "ID: {$personResult->kinopoiskId}\n";
echo "Имя: {$personResult->getDisplayName()}\n";
echo "Полное имя: {$personResult->getFullName()}\n";
echo "Постер: {$personResult->posterUrl}\n";

// Проверка пола
if ($personResult->isMale()) {
    echo "Пол: Мужской\n";
} elseif ($personResult->isFemale()) {
    echo "Пол: Женский\n";
} else {
    echo "Пол: Неизвестен\n";
}

// Проверка наличия имен
if ($personResult->nameRu) {
    echo "Имя на русском: {$personResult->nameRu}\n";
}

if ($personResult->nameEn) {
    echo "Имя на английском: {$personResult->nameEn}\n";
}
```

## 🔗 Связанные разделы

- [Сервисы](../services/index.md) - Работа с API
- [Перечисления](../enums/index.md) - Константы и типы
- [Ответы](../responses/index.md) - Классы ответов
- [Исключения](../exceptions/index.md) - Обработка ошибок
- [Интерфейсы](../interfaces/index.md) - Базовые интерфейсы

---

**📚 Навигация:** [Главная](../index.md) → Модели

