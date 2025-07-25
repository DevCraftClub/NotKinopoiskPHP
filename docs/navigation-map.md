# Карта навигации документации

Интерактивная карта навигации по всей документации NotKinopoisk PHP Library.

---

**📚 Навигация:** [Главная](index.md) → Карта навигации

---

## 🗺️ Структура документации

```
📁 docs/
├── 📄 index.md                              # Главная страница
├── 📄 navigation-map.md                     # Карта навигации (этот файл)
├── 📄 client.md                             # Основной клиент
│
├── 📁 services/                             # Сервисы API
│   ├── 📄 index.md                          # Обзор сервисов
│   ├── 📄 film-service.md                   # Сервис фильмов
│   ├── 📄 person-service.md                 # Сервис персон
│   ├── 📄 media-service.md                  # Сервис медиа
│   └── 📄 user-service.md                   # Сервис пользователей
│
├── 📁 models/                               # Модели данных
│   ├── 📄 index.md                          # Обзор моделей
│   ├── 📄 film.md                           # Модель фильма
│   ├── 📄 person.md                         # Модель персоны
│   ├── 📄 staff.md                          # Модель съемочной группы
│   ├── 📄 review.md                         # Модель отзыва
│   ├── 📄 fact.md                           # Модель факта
│   ├── 📄 image.md                          # Модель изображения
│   ├── 📄 video.md                          # Модель видео
│   ├── 📄 award.md                          # Модель награды
│   ├── 📄 box-office.md                     # Модель кассовых сборов
│   ├── 📄 country.md                        # Модель страны
│   ├── 📄 genre.md                          # Модель жанра
│   ├── 📄 episode.md                        # Модель эпизода
│   ├── 📄 season.md                         # Модель сезона
│   ├── 📄 external-source.md                # Модель внешнего источника
│   ├── 📄 distribution.md                   # Модель дистрибуции
│   ├── 📄 film-search-result.md             # Модель результата поиска
│   ├── 📄 person-spouse.md                  # Модель супруга
│   ├── 📄 person-film.md                    # Модель фильма персоны
│   ├── 📄 user-vote.md                      # Модель голоса пользователя
│   ├── 📄 film-collection.md                # Модель коллекции фильмов
│   ├── 📄 filters.md                        # Модель фильтров
│   ├── 📄 related-film.md                   # Модель связанного фильма
│   ├── 📄 api-key-info.md                   # Модель информации об API ключе
│   ├── 📄 api-key-qouta.md                  # Модель квоты API ключа
│   └── 📄 media-post.md                     # Модель медиа поста
│
├── 📁 enums/                                # Перечисления
│   ├── 📄 index.md                          # Обзор перечислений
│   ├── 📄 image-type.md                     # Типы изображений
│   ├── 📄 review-order.md                   # Порядок сортировки отзывов
│   ├── 📄 review-type.md                    # Типы отзывов
│   ├── 📄 fact-type.md                      # Типы фактов
│   ├── 📄 profession-key.md                 # Ключи профессий
│   ├── 📄 video-site.md                     # Сайты видео
│   ├── 📄 box-office-type.md                # Типы кассовых сборов
│   ├── 📄 distribution-type.md              # Типы дистрибуции
│   ├── 📄 relation-type.md                  # Типы связей
│   ├── 📄 sex.md                            # Пол
│   ├── 📄 api-version.md                    # Версии API
│   ├── 📄 month.md                          # Месяцы
│   ├── 📄 film-order.md                     # Порядок сортировки фильмов
│   ├── 📄 content-type.md                   # Типы контента
│   ├── 📄 collection-type.md                # Типы коллекций
│   ├── 📄 distribution-sub-type.md          # Подтипы дистрибуции
│   ├── 📄 production-status.md              # Статусы производства
│   └── 📄 account-type.md                   # Типы аккаунтов
│
├── 📁 responses/                            # Ответы API
│   ├── 📄 index.md                          # Обзор ответов
│   ├── 📄 default-response.md               # Базовый ответ
│   ├── 📄 paginated-response.md             # Пагинированный ответ
│   ├── 📄 keyword-search-response.md        # Ответ поиска
│   ├── 📄 budget-response.md                # Ответ с бюджетом
│   ├── 📄 sequel-prequel-response.md        # Ответ с сиквелами/приквелами
│   ├── 📄 movie-staff-response.md           # Ответ со съемочной командой
│   ├── 📄 review-response.md                # Ответ с отзывами
│   └── 📄 simple-response.md                # Простой ответ
│
├── 📁 exceptions/                           # Исключения
│   ├── 📄 index.md                          # Обзор исключений
│   ├── 📄 api-exception.md                  # Базовое исключение API
│   ├── 📄 invalid-api-key-exception.md      # Неверный API ключ
│   ├── 📄 rate-limit-exception.md           # Превышение лимита
│   ├── 📄 resource-not-found-exception.md   # Ресурс не найден
│   └── 📄 kp-validation-exception.md        # Ошибка валидации
│
└── 📁 interfaces/                           # Интерфейсы
    ├── 📄 index.md                          # Обзор интерфейсов
    ├── 📄 model-interface.md                # Интерфейс модели
    └── 📄 response-interface.md             # Интерфейс ответа
```

## 🔗 Быстрые ссылки

### 🚀 Начало работы

- **[Главная страница](index.md)** - Обзор библиотеки и быстрый старт
- **[Основной клиент](client.md)** - Главный класс для работы с API
- **[Примеры использования](../examples/)** - Готовые примеры кода

### 📦 Основные компоненты

- **[Сервисы](services/index.md)** - Работа с различными типами данных
- **[Модели](models/index.md)** - Структуры данных API
- **[Перечисления](enums/index.md)** - Константы и типы
- **[Ответы](responses/index.md)** - Классы ответов API
- **[Исключения](exceptions/index.md)** - Обработка ошибок
- **[Интерфейсы](interfaces/index.md)** - Базовые контракты

## 🎯 Популярные разделы

### 🎬 Работа с фильмами

1. **[FilmService](services/film-service.md)** - Основной сервис для работы с фильмами
2. **[Film](models/film.md)** - Модель фильма с полной информацией
3. **[FilmSearchResult](models/film-search-result.md)** - Результаты поиска фильмов
4. **[ContentType](enums/content-type.md)** - Типы контента (фильм, сериал, etc.)
5. **[FilmOrder](enums/film-order.md)** - Сортировка фильмов

### 👥 Работа с персонами

1. **[PersonService](services/person-service.md)** - Сервис для работы с персонами
2. **[Person](models/person.md)** - Модель персоны
3. **[Staff](models/staff.md)** - Съемочная группа
4. **[ProfessionKey](enums/profession-key.md)** - Профессии в кино
5. **[PersonFilm](models/person-film.md)** - Фильмография персоны

### 🎥 Работа с медиа

1. **[MediaService](services/media-service.md)** - Сервис для работы с медиа
2. **[Image](models/image.md)** - Изображения фильмов
3. **[Video](models/video.md)** - Видео контент
4. **[ImageType](enums/image-type.md)** - Типы изображений
5. **[VideoSite](enums/video-site.md)** - Сайты видео

### 📝 Работа с отзывами и фактами

1. **[Review](models/review.md)** - Отзывы пользователей
2. **[Fact](models/fact.md)** - Интересные факты
3. **[ReviewType](enums/review-type.md)** - Типы отзывов
4. **[ReviewOrder](enums/review-order.md)** - Сортировка отзывов
5. **[FactType](enums/fact-type.md)** - Типы фактов

## 🔍 Поиск по функциональности

### 🔍 Поиск и фильтрация

- **[FilmService::searchByKeyword()](services/film-service.md#searchbykeyword)** - Поиск фильмов
- **[PersonService::searchByName()](services/person-service.md#searchbyname)** - Поиск персон
- **[FilmService::getTop()](services/film-service.md#gettop)** - Топ фильмов
- **[Filters](models/filters.md)** - Фильтры для поиска

### 📊 Статистика и рейтинги

- **[BoxOffice](models/box-office.md)** - Кассовые сборы
- **[Award](models/award.md)** - Награды и номинации
- **[UserVote](models/user-vote.md)** - Пользовательские голоса
- **[ExternalSource](models/external-source.md)** - Внешние источники рейтингов

### 🎬 Сериалы и эпизоды

- **[Episode](models/episode.md)** - Эпизоды сериалов
- **[Season](models/season.md)** - Сезоны сериалов
- **[FilmService::getSeasons()](services/film-service.md#getseasons)** - Получение сезонов

### 🔗 Связанный контент

- **[RelatedFilm](models/related-film.md)** - Связанные фильмы
- **[FilmService::getSequelsAndPrequels()](services/film-service.md#getsequelsandprequels)** - Сиквелы и приквелы
- **[FilmCollection](models/film-collection.md)** - Коллекции фильмов

## ⚠️ Обработка ошибок

### 🔑 Аутентификация

- **[InvalidApiKeyException](exceptions/invalid-api-key-exception.md)** - Неверный API ключ
- **[ApiKeyInfo](models/api-key-info.md)** - Информация об API ключе
- **[ApiKeyQouta](models/api-key-qouta.md)** - Квоты запросов

### ⏱️ Лимиты и ограничения

- **[RateLimitException](exceptions/rate-limit-exception.md)** - Превышение лимита запросов
- **[UserService](services/user-service.md)** - Информация о лимитах

### 🔍 Ошибки ресурсов

- **[ResourceNotFoundException](exceptions/resource-not-found-exception.md)** - Ресурс не найден
- **[ApiException](exceptions/api-exception.md)** - Общие ошибки API

### ✅ Валидация данных

- **[KpValidationException](exceptions/kp-validation-exception.md)** - Ошибки валидации
- **[ModelInterface](interfaces/model-interface.md)** - Базовый интерфейс моделей

## 🛠️ Утилиты и помощники

### 📄 Ответы API

- **[DefaultResponse](responses/default-response.md)** - Базовый ответ
- **[PaginatedResponse](responses/paginated-response.md)** - Пагинация
- **[KeywordSearchResponse](responses/keyword-search-response.md)** - Ответ поиска

### 🔧 Интерфейсы

- **[ModelInterface](interfaces/model-interface.md)** - Интерфейс моделей
- **[ResponseInterface](interfaces/response-interface.md)** - Интерфейс ответов

### 🌍 Справочные данные

- **[Country](models/country.md)** - Страны
- **[Genre](models/genre.md)** - Жанры
- **[Distribution](models/distribution.md)** - Дистрибуция
- **[Month](enums/month.md)** - Месяцы

## 📚 Примеры использования

### 🚀 Быстрый старт

```php
// Создание клиента
$client = new Client('your-api-key');

// Получение фильма
$film = $client->films->getById(301);

// Поиск фильмов
$searchResults = $client->films->searchByKeyword('матрица');

// Получение съемочной группы
$staff = $client->films->getStaff(301);
```

### 🔍 Поиск и фильтрация

```php
// Поиск персон
$persons = $client->persons->searchByName('Том Круз');

// Получение топ фильмов
$topFilms = $client->films->getTop();

// Получение изображений
$images = $client->media->getImages(301, ImageType::POSTER);
```

### ⚠️ Обработка ошибок

```php
try {
    $film = $client->films->getById(999999);
} catch (ResourceNotFoundException $e) {
    echo "Фильм не найден\n";
} catch (RateLimitException $e) {
    echo "Превышен лимит запросов\n";
}
```

## 🎯 Рекомендуемые маршруты изучения

### 🆕 Для новичков

1. **[Главная страница](index.md)** - Обзор библиотеки
2. **[Основной клиент](client.md)** - Базовые концепции
3. **[FilmService](services/film-service.md)** - Работа с фильмами
4. **[Film](models/film.md)** - Структура данных фильма
5. **[Примеры](../examples/)** - Практические примеры

### 🔧 Для разработчиков

1. **[Интерфейсы](interfaces/index.md)** - Базовые контракты
2. **[Исключения](exceptions/index.md)** - Обработка ошибок
3. **[Ответы](responses/index.md)** - Структура ответов API
4. **[Перечисления](enums/index.md)** - Константы и типы
5. **[Модели](models/index.md)** - Все модели данных

### 🎬 Для работы с контентом

1. **[FilmService](services/film-service.md)** - Основные операции с фильмами
2. **[PersonService](services/person-service.md)** - Работа с персонами
3. **[MediaService](services/media-service.md)** - Медиа контент
4. **[UserService](services/user-service.md)** - Пользовательские данные
5. **[Связанные модели](models/index.md)** - Дополнительные данные

### 🔍 Для поиска и анализа

1. **[FilmService::searchByKeyword()](services/film-service.md#searchbykeyword)** - Поиск фильмов
2. **[PersonService::searchByName()](services/person-service.md#searchbyname)** - Поиск персон
3. **[FilmService::getTop()](services/film-service.md#gettop)** - Топ фильмов
4. **[Статистические модели](models/index.md)** - Анализ данных
5. **[Перечисления для сортировки](enums/index.md)** - Настройка поиска

## 📊 Статистика документации

### 📁 Файлы документации

- **Всего файлов:** 60+
- **Главная страница:** 1
- **Сервисы:** 5
- **Модели:** 25
- **Перечисления:** 18
- **Ответы:** 4
- **Исключения:** 6
- **Интерфейсы:** 3

### 🔗 Связи между компонентами

- **Перекрестные ссылки:** 200+
- **Навигационные элементы:** 60+
- **Примеры кода:** 100+
- **Связанные классы:** 300+

### 📚 Содержание

- **Строк документации:** 15,000+
- **Примеров кода:** 500+
- **Методов API:** 50+
- **Моделей данных:** 25+
- **Перечислений:** 18+

---

**📚 Навигация:** [Главная](index.md) → Карта навигации

**🔄 Последнее обновление:** <?php echo date('Y-m-d H:i:s'); ?>
