# Enums в NotKinopoiskPHP

Этот каталог содержит типизированные enums для улучшения типобезопасности и читаемости кода в проекте NotKinopoiskPHP.

## 📋 Список Enums

### 1. ContentType

**Файл:** `ContentType.php`

Определяет типы контента в Kinopoisk API:

- `FILM` - Фильм
- `SERIES` - Сериал
- `MINI_SERIES` - Мини-сериал
- `TV_SHOW` - Телешоу
- `TV_MOVIE` - ТВ-фильм
- `VIDEO` - Видео
- `SHORT` - Короткометражка
- `DOCUMENTARY` - Документальный фильм

**Методы:**

- `isFilm()` - Проверяет, является ли контент фильмом
- `isSeries()` - Проверяет, является ли контент сериалом
- `getDisplayName()` - Получает человекочитаемое название

### 2. ReviewType

**Файл:** `ReviewType.php`

Определяет типы рецензий:

- `POSITIVE` - Положительная рецензия
- `NEGATIVE` - Отрицательная рецензия
- `NEUTRAL` - Нейтральная рецензия

**Методы:**

- `isPositive()` - Проверяет, является ли рецензия положительной
- `isNegative()` - Проверяет, является ли рецензия отрицательной
- `getDisplayName()` - Получает человекочитаемое название

### 3. FactType

**Файл:** `FactType.php`

Определяет типы фактов:

- `FACT` - Интересный факт
- `BLOOPER` - Ошибка в фильме (блупер)

**Методы:**

- `isFact()` - Проверяет, является ли факт интересным фактом
- `isBlooper()` - Проверяет, является ли факт ошибкой в фильме
- `getDisplayName()` - Получает человекочитаемое название

### 4. BoxOfficeType

**Файл:** `BoxOfficeType.php`

Определяет типы кассовых сборов:

- `BUDGET` - Бюджет фильма
- `RUS` - Сборы в России
- `USA` - Сборы в США
- `WORLD` - Мировые сборы

**Методы:**

- `isBudget()` - Проверяет, является ли тип бюджетом
- `isRevenue()` - Проверяет, является ли тип сборами
- `getDisplayName()` - Получает человекочитаемое название

### 5. AccountType

**Файл:** `AccountType.php`

Определяет типы аккаунтов API:

- `FREE` - Бесплатный аккаунт
- `PAID` - Платный аккаунт
- `UNLIMITED` - Безлимитный аккаунт

**Методы:**

- `isFree()` - Проверяет, является ли аккаунт бесплатным
- `isUnlimited()` - Проверяет, является ли аккаунт безлимитным
- `getDisplayName()` - Получает человекочитаемое название

### 6. ImageType

**Файл:** `ImageType.php`

Определяет типы изображений:

- `STILL` - Кадр из фильма
- `POSTER` - Постер
- `BACKGROUND` - Фон
- `PREVIEW` - Превью

**Методы:**

- `getDisplayName()` - Получает человекочитаемое название

### 7. CollectionType

**Файл:** `CollectionType.php`

Определяет типы коллекций фильмов:

- `TOP_POPULAR_ALL` - Топ популярных фильмов и сериалов
- `TOP_POPULAR_MOVIES` - Топ популярных фильмов
- `TOP_POPULAR_SERIES` - Топ популярных сериалов
- `TOP_250_MOVIES` - Топ-250 фильмов
- `TOP_250_SERIES` - Топ-250 сериалов

**Методы:**

- `getDisplayName()` - Получает человекочитаемое название

### 8. DistributionSubType

**Файл:** `DistributionSubType.php`

Подтипы проката:

- `CINEMA` — Кинотеатры
- `DVD` — DVD
- `DIGITAL` — Цифровой релиз
- `BLURAY` — Blu-ray

### 9. RelationType

**Файл:** `RelationType.php`

Типы связей между фильмами:

- `SEQUEL` — Сиквел
- `PREQUEL` — Приквел
- `REMAKE` — Ремейк
- `UNKNOWN` — Неизвестно

### 10. ProfessionKey

**Файл:** `ProfessionKey.php`

Ключи профессий:

- `WRITER`, `OPERATOR`, `EDITOR`, `COMPOSER`, `PRODUCER_USSR`, `TRANSLATOR`, `DIRECTOR`, `DESIGN`, `PRODUCER`, `ACTOR`, `VOICE_DIRECTOR`, `UNKNOWN`, `HIMSELF`, `HERSELF`, `HRONO_TITR_MALE`, `HRONO_TITR_FEMALE`

### 11. Sex

**Файл:** `Sex.php`

Пол:

- `MALE` — Мужской
- `FEMALE` — Женский
- `UNKNOWN` — Неизвестно

### 12. VideoSite

**Файл:** `VideoSite.php`

Платформы видео:

- `YOUTUBE` — YouTube
- `KINOPOISK_WIDGET` — Кинопоиск-виджет
- `YANDEX_DISK` — Яндекс.Диск
- `UNKNOWN` — Неизвестно

## 🚀 Использование

### В моделях

```php
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\ReviewType;

// Создание объекта с enum
$film = new Film(
    // ... другие параметры
    type: ContentType::FILM
);

// Проверка типа
if ($film->type->isFilm()) {
    echo "Это фильм!";
}

// Получение отображаемого названия
echo $film->type->getDisplayName(); // "Фильм"
```

### В сервисах

```php
use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\CollectionType;

// Получение изображений определенного типа
$posters = $filmService->getImages(301, ImageType::POSTER);

// Получение коллекции определенного типа
$top250 = $filmService->getCollections(CollectionType::TOP_250_MOVIES);
```

### Создание из строки

```php
// Создание enum из строки (например, из API)
$contentType = ContentType::from('FILM');
$reviewType = ReviewType::from('POSITIVE');
```

## ✅ Преимущества

1. **Типобезопасность** - компилятор проверяет корректность значений
2. **Автодополнение** - IDE показывает доступные значения
3. **Рефакторинг** - изменение значений в одном месте
4. **Документация** - значения и их смысл видны в коде
5. **Методы** - удобные методы для проверки и отображения
6. **Валидация** - автоматическая проверка входных данных

## 🔧 Создание нового Enum

Для создания нового enum следуйте этому шаблону:

```php
<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Описание enum
 *
 * @package NotKinopoisk\Enums
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 */
enum YourEnum: string
{
    /** Описание значения */
    case VALUE_1 = 'VALUE_1';

    /** Описание значения */
    case VALUE_2 = 'VALUE_2';

    /**
     * Метод для проверки
     *
     * @return bool
     */
    public function isValue1(): bool
    {
        return $this === self::VALUE_1;
    }

    /**
     * Получает человекочитаемое название
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return match($this) {
            self::VALUE_1 => 'Человекочитаемое название 1',
            self::VALUE_2 => 'Человекочитаемое название 2',
        };
    }
}
```

## 📝 Примеры

Смотрите файл `examples/enums_usage.php` для подробных примеров использования всех enums в проекте.

// Пример использования новых enum:
use NotKinopoisk\Enums\DistributionSubType;
use NotKinopoisk\Enums\RelationType;
use NotKinopoisk\Enums\ProfessionKey;
use NotKinopoisk\Enums\Sex;
use NotKinopoisk\Enums\VideoSite;

$subType = DistributionSubType::CINEMA;
echo $subType->getDisplayName(); // "Кинотеатры"

$relation = RelationType::SEQUEL;
echo $relation->getDisplayName(); // "Сиквел"

$profession = ProfessionKey::ACTOR;
echo $profession->getDisplayName(); // "Актер"

$sex = Sex::FEMALE;
echo $sex->getDisplayName(); // "Женский"

$site = VideoSite::YOUTUBE;
echo $site->getDisplayName(); // "YouTube"
