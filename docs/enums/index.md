# Перечисления (Enums)

Перечисления для определения констант и типов в Kinopoisk API.

---

**📚 Навигация:** [Главная](../index.md) → Перечисления

---

## 📋 Категории перечислений

### 🖼️ Медиа и контент

- [ImageType](image-type.md) - Типы изображений
- [VideoSite](video-site.md) - Сайты видео
- [ContentType](content-type.md) - Типы контента
- [CollectionType](collection-type.md) - Типы коллекций

### 📝 Отзывы и факты

- [ReviewType](review-type.md) - Типы отзывов
- [ReviewOrder](review-order.md) - Порядок сортировки отзывов
- [FactType](fact-type.md) - Типы фактов

### 👥 Персоны и профессии

- [ProfessionKey](profession-key.md) - Ключи профессий
- [Sex](sex.md) - Пол
- [RelationType](relation-type.md) - Типы связей

### 🎬 Фильмы и сериалы

- [FilmOrder](film-order.md) - Порядок сортировки фильмов
- [DistributionType](distribution-type.md) - Типы дистрибуции
- [DistributionSubType](distribution-sub-type.md) - Подтипы дистрибуции

### 📊 Статистика и награды

- [BoxOfficeType](box-office-type.md) - Типы кассовых сборов
- [Month](month.md) - Месяцы

### 🔧 API и система

- [ApiVersion](api-version.md) - Версии API
- [AccountType](account-type.md) - Типы аккаунтов

## 🔗 Связанные компоненты

### Модели

- [Film](../models/film.md) - Использует ContentType, FilmOrder
- [Image](../models/image.md) - Использует ImageType
- [Video](../models/video.md) - Использует VideoSite
- [Review](../models/review.md) - Использует ReviewType, ReviewOrder
- [Fact](../models/fact.md) - Использует FactType
- [Staff](../models/staff.md) - Использует ProfessionKey
- [Person](../models/person.md) - Использует Sex
- [PersonSpouse](../models/person-spouse.md) - Использует Sex, RelationType
- [BoxOffice](../models/box-office.md) - Использует BoxOfficeType
- [Distribution](../models/distribution.md) - Использует DistributionType

### Сервисы

- [FilmService](../services/film-service.md) - Использует все перечисления фильмов
- [PersonService](../services/person-service.md) - Использует ProfessionKey, Sex
- [MediaService](../services/media-service.md) - Использует ImageType, VideoSite
- [UserService](../services/user-service.md) - Использует ApiVersion, AccountType

## 🚀 Быстрый старт

### Использование перечислений

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Enums\ImageType;
use NotKinopoisk\Enums\ReviewType;
use NotKinopoisk\Enums\ProfessionKey;
use NotKinopoisk\Enums\ContentType;
use NotKinopoisk\Enums\Sex;

// Работа с типами изображений
$posterType = ImageType::POSTER;
$screenshotType = ImageType::SCREENSHOT;

echo "Тип постера: {$posterType->value}\n"; // "POSTER"
echo "Отображаемое имя: {$posterType->getDisplayName()}\n"; // "Постер"

// Проверка типов
if ($posterType->isMain()) {
    echo "Это основной тип изображения\n";
}

// Работа с типами отзывов
$positiveReview = ReviewType::POSITIVE;
$negativeReview = ReviewType::NEGATIVE;

echo "Тип отзыва: {$positiveReview->getDisplayName()}\n"; // "Положительный"

// Работа с профессиями
$director = ProfessionKey::DIRECTOR;
$actor = ProfessionKey::ACTOR;

echo "Профессия: {$director->getDisplayName()}\n"; // "Режиссер"

if ($director->isCreativeProfession()) {
    echo "Это творческая профессия\n";
}

// Работа с типами контента
$film = ContentType::FILM;
$series = ContentType::SERIES;

echo "Тип контента: {$film->getDisplayName()}\n"; // "Фильм"

if ($film->isFilm()) {
    echo "Это фильм\n";
}

// Работа с полом
$male = Sex::MALE;
$female = Sex::FEMALE;

echo "Пол: {$male->getDisplayName()}\n"; // "Мужской"
```

## 📊 Статистика перечислений

### Медиа и контент (4)

- **ImageType** - 8 типов изображений
- **VideoSite** - 6 сайтов видео
- **ContentType** - 11 типов контента
- **CollectionType** - 4 типа коллекций

### Отзывы и факты (3)

- **ReviewType** - 3 типа отзывов
- **ReviewOrder** - 4 порядка сортировки
- **FactType** - 2 типа фактов

### Персоны и профессии (3)

- **ProfessionKey** - 15+ ключей профессий
- **Sex** - 3 значения пола
- **RelationType** - 4 типа связей

### Фильмы и сериалы (3)

- **FilmOrder** - 8 порядков сортировки
- **DistributionType** - 3 типа дистрибуции
- **DistributionSubType** - 6 подтипов дистрибуции

### Статистика и награды (2)

- **BoxOfficeType** - 3 типа кассовых сборов
- **Month** - 12 месяцев

### API и система (2)

- **ApiVersion** - 3 версии API
- **AccountType** - 2 типа аккаунтов

## 🔧 Общие методы

Большинство перечислений имеют общие методы:

### getDisplayName()

```php
public function getDisplayName(): string
```

Возвращает человекочитаемое название значения.

### from()

```php
public static function from(string|int $value): self
```

Создает экземпляр перечисления из значения.

### values()

```php
public static function values(): array
```

Возвращает все возможные значения перечисления.

## 📖 Примеры использования

### Работа с типами изображений

```php
use NotKinopoisk\Enums\ImageType;

// Получение всех типов изображений
$allTypes = ImageType::cases();

foreach ($allTypes as $type) {
    echo "{$type->value}: {$type->getDisplayName()}\n";
}

// Фильтрация основных типов
$mainTypes = array_filter($allTypes, fn($type) => $type->isMain());

foreach ($mainTypes as $type) {
    echo "Основной тип: {$type->getDisplayName()}\n";
}

// Создание из строки
$type = ImageType::from('POSTER');
echo "Тип: {$type->getDisplayName()}\n";
```

### Работа с профессиями

```php
use NotKinopoisk\Enums\ProfessionKey;

// Получение всех профессий
$allProfessions = ProfessionKey::cases();

// Группировка по категориям
$creativeProfessions = array_filter($allProfessions, fn($p) => $p->isCreativeProfession());
$technicalProfessions = array_filter($allProfessions, fn($p) => $p->isTechnicalProfession());

echo "Творческие профессии:\n";
foreach ($creativeProfessions as $profession) {
    echo "- {$profession->getDisplayName()}\n";
}

echo "Технические профессии:\n";
foreach ($technicalProfessions as $profession) {
    echo "- {$profession->getDisplayName()}\n";
}

// Проверка конкретных профессий
$director = ProfessionKey::DIRECTOR;
$actor = ProfessionKey::ACTOR;

if ($director->isCreativeProfession()) {
    echo "Режиссер - творческая профессия\n";
}

if ($actor->isCreativeProfession()) {
    echo "Актер - творческая профессия\n";
}
```

### Работа с типами контента

```php
use NotKinopoisk\Enums\ContentType;

// Получение всех типов контента
$allTypes = ContentType::cases();

// Фильтрация фильмов
$films = array_filter($allTypes, fn($type) => $type->isFilm());

foreach ($films as $type) {
    echo "Фильм: {$type->getDisplayName()}\n";
}

// Фильтрация сериалов
$series = array_filter($allTypes, fn($type) => $type->isSeries());

foreach ($series as $type) {
    echo "Сериал: {$type->getDisplayName()}\n";
}

// Создание из строки
$contentType = ContentType::from('FILM');
echo "Тип контента: {$contentType->getDisplayName()}\n";
```

### Работа с порядками сортировки

```php
use NotKinopoisk\Enums\FilmOrder;
use NotKinopoisk\Enums\ReviewOrder;

// Получение всех порядков сортировки фильмов
$filmOrders = FilmOrder::cases();

foreach ($filmOrders as $order) {
    echo "{$order->getDisplayName()}: {$order->value}\n";
}

// Получение всех порядков сортировки отзывов
$reviewOrders = ReviewOrder::cases();

foreach ($reviewOrders as $order) {
    echo "{$order->getDisplayName()}: {$order->value}\n";

    if ($order->isDateSort()) {
        echo "  - Сортировка по дате\n";
    }

    if ($order->isPositiveRatingSort()) {
        echo "  - Сортировка по положительным оценкам\n";
    }

    if ($order->isNegativeRatingSort()) {
        echo "  - Сортировка по отрицательным оценкам\n";
    }
}
```

## 🔗 Связанные разделы

- [Модели](../models/index.md) - Используют перечисления
- [Сервисы](../services/index.md) - Работают с перечислениями
- [Ответы](../responses/index.md) - Могут содержать перечисления
- [Исключения](../exceptions/index.md) - Обработка ошибок
- [Интерфейсы](../interfaces/index.md) - Базовые интерфейсы

---

**📚 Навигация:** [Главная](../index.md) → Перечисления
