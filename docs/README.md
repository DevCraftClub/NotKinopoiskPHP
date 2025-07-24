# NotKinopoisk PHP Wrapper - Документация

Полная документация библиотеки NotKinopoisk PHP Wrapper для работы с Kinopoisk API.

## 📚 Содержание

### 🏗️ Архитектура

- [Клиент (Client)](client.md) - Основной класс для работы с API
- [Интерфейсы (Interfaces)](interfaces.md) - Интерфейсы библиотеки
- [Абстрактные классы](abstract-classes.md) - Базовые классы

### 🎬 Сервисы

- [FilmService](services/film-service.md) - Работа с фильмами и сериалами
- [PersonService](services/person-service.md) - Работа с персонами
- [MediaService](services/media-service.md) - Работа с медиа-контентом
- [UserService](services/user-service.md) - Работа с пользователем

### 📊 Модели данных

- [Film](models/film.md) - Модель фильма/сериала
- [Person](models/person.md) - Модель персоны
- [Staff](models/staff.md) - Модель персонала фильма
- [Review](models/review.md) - Модель отзыва
- [Fact](models/fact.md) - Модель факта
- [Image](models/image.md) - Модель изображения
- [Video](models/video.md) - Модель видео
- [BoxOffice](models/box-office.md) - Модель кассовых сборов
- [Distribution](models/distribution.md) - Модель проката
- [Award](models/award.md) - Модель награды
- [Genre](models/genre.md) - Модель жанра
- [Country](models/country.md) - Модель страны
- [Season](models/season.md) - Модель сезона
- [Episode](models/episode.md) - Модель эпизода
- [PersonFilm](models/person-film.md) - Модель фильма персоны
- [PersonSpouse](models/person-spouse.md) - Модель супруга персоны
- [PersonByNameResult](models/person-by-name-result.md) - Результат поиска персоны
- [FilmSearchResult](models/film-search-result.md) - Результат поиска фильма
- [FilmCollection](models/film-collection.md) - Коллекция фильмов
- [RelatedFilm](models/related-film.md) - Связанный фильм
- [Premiere](models/premiere.md) - Модель премьеры
- [UserVote](models/user-vote.md) - Модель голоса пользователя
- [ExternalSource](models/external-source.md) - Модель внешнего источника
- [MediaPost](models/media-post.md) - Модель медиа-поста
- [ApiKeyInfo](models/api-key-info.md) - Информация об API ключе
- [ApiKeyQuota](models/api-key-quota.md) - Квоты API ключа
- [Filters](models/filters.md) - Фильтры для поиска

### 🔧 Перечисления (Enums)

- [ContentType](enums/content-type.md) - Типы контента
- [ImageType](enums/image-type.md) - Типы изображений
- [VideoSite](enums/video-site.md) - Сайты видео
- [BoxOfficeType](enums/box-office-type.md) - Типы кассовых сборов
- [DistributionType](enums/distribution-type.md) - Типы проката
- [DistributionSubType](enums/distribution-sub-type.md) - Подтипы проката
- [FactType](enums/fact-type.md) - Типы фактов
- [ReviewType](enums/review-type.md) - Типы отзывов
- [ReviewOrder](enums/review-order.md) - Порядок сортировки отзывов
- [FilmOrder](enums/film-order.md) - Порядок сортировки фильмов
- [CollectionType](enums/collection-type.md) - Типы коллекций
- [RelationType](enums/relation-type.md) - Типы связей
- [ProductionStatus](enums/production-status.md) - Статусы производства
- [ProfessionKey](enums/profession-key.md) - Ключи профессий
- [AccountType](enums/account-type.md) - Типы аккаунтов
- [Sex](enums/sex.md) - Пол
- [Month](enums/month.md) - Месяцы
- [ApiVersion](enums/api-version.md) - Версии API

### 📤 Ответы API

- [DefaultResponse](responses/default-response.md) - Базовый ответ
- [PaginatedResponse](responses/paginated-response.md) - Пагинированный ответ
- [SimpleResponse](responses/simple-response.md) - Простой ответ
- [BudgetResponse](responses/budget-response.md) - Ответ с бюджетом
- [KeywordSearchResponse](responses/keyword-search-response.md) - Ответ поиска по ключевым словам
- [MovieStaffResponse](responses/movie-staff-response.md) - Ответ с персоналом фильма
- [SequelPrequelResponse](responses/sequel-prequel-response.md) - Ответ с сиквелами и приквелами

### ⚠️ Исключения

- [ApiException](exceptions/api-exception.md) - Базовое исключение API
- [InvalidApiKeyException](exceptions/invalid-api-key-exception.md) - Неверный API ключ
- [RateLimitException](exceptions/rate-limit-exception.md) - Превышен лимит запросов
- [ResourceNotFoundException](exceptions/resource-not-found-exception.md) - Ресурс не найден
- [KpValidationException](exceptions/kp-validation-exception.md) - Ошибка валидации

## 🚀 Быстрый старт

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

// Создание клиента
$client = new Client('your-api-key');

// Получение информации о фильме
$film = $client->films->getById(301);
echo $film->getDisplayName();
```

## 📋 Требования

- PHP 8.3+
- Composer
- API ключ от Kinopoisk Unofficial API

## 🔗 Полезные ссылки

- [GitHub репозиторий](https://github.com/DevCraftClub/NotKinopoiskPHP)
- [Issues](https://github.com/DevCraftClub/NotKinopoiskPHP/issues)
- [Примеры использования](../examples/)
- [Kinopoisk Unofficial API](https://kinopoiskapiunofficial.tech)

## ⚠️ Важные предостережения

- **Структура API** в последний раз обновлялась **16.10.2023**
- **НЕ путать** с kinopoisk.dev - это другой API
- Работает с **kinopoiskapiunofficial.tech**
- Некоторые методы могут не возвращать актуальные данные
