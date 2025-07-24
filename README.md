[![GitHub](https://img.shields.io/badge/GitHub-DevCraftClub%2FNotKinopoiskPHP-blue?style=flat-square&logo=github)](https://github.com/DevCraftClub/NotKinopoiskPHP)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

# NotKinopoiskPHP

Современная PHP библиотека для работы с Kinopoisk API, написанная с использованием PHP 8.3+ и современных практик разработки.

## 📋 Краткая информация

**NotKinopoiskPHP** - это полнофункциональная PHP библиотека для работы с Kinopoisk Unofficial API. Библиотека предоставляет удобный интерфейс для получения информации о фильмах, сериалах, персонах и других данных из базы Кинопоиска.

> ⚠️ **Важно**: Данная библиотека работает с Kinopoisk Unofficial API ([kinopoiskapiunofficial.tech](https://kinopoiskapiunofficial.tech)), НЕ путать с kinopoisk.dev. Структура API последний раз обновлялась 16.10.2023, но данные актуальны.

## 📋 Минимальные требования

### Системные требования

- **PHP**: 8.3 или выше
- **Composer**: 2.0 или выше
- **Расширения PHP**:
  - `curl` - для HTTP запросов
  - `json` - для работы с JSON данными
  - `mbstring` - для работы с многобайтовыми строками

### Рекомендуемые расширения

- `openssl` - для HTTPS соединений
- `zlib` - для сжатия данных
- `intl` - для интернационализации

### Поддерживаемые платформы

- **Linux** (Ubuntu 20.04+, CentOS 8+, Debian 11+)
- **macOS** (10.15+)
- **Windows** (10+, с WSL2 рекомендуется)

### Проверка требований

Для проверки соответствия требованиям выполните:

```bash
# Проверка версии PHP
php --version

# Проверка расширений PHP
php -m | grep -E "(curl|json|mbstring|openssl)"

# Проверка Composer
composer --version
```

### 🎯 Основные возможности:

- **Получение информации о фильмах** - детальная информация, рейтинги, актеры, режиссеры
- **Поиск фильмов** - по названию, жанрам, годам, рейтингам
- **Работа с персонами** - поиск актеров, режиссеров, получение фильмографии
- **Коллекции фильмов** - топ-250, популярные фильмы, премьеры
- **Медиа контент** - постеры, кадры, трейлеры, отзывы
- **Типобезопасность** - строгая типизация с использованием PHP 8.3+ features
- **Современная архитектура** - readonly свойства, enums, comprehensive документация

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

---

## 📦 Установка

```bash
composer require devcraftclub/kinopoiskapiunofficialtech
```

---

## 🔧 Конфигурация

Создайте файл `.env` на основе `.env.example`:

```bash
cp .env.example .env
```

И добавьте ваш API ключ:

```env
KINOPOISK_API_KEY=your_api_key_here
```

---

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

---

## 📚 Enums

Библиотека использует типизированные enums для обеспечения типобезопасности. Подробная документация по всем перечислениям доступна в [документации по enums](src/Enums/README.md) и [примерах использования](examples/enums_usage.php).

---

## 🧪 Тестирование

Для запуска тестов используйте команду `composer test`. Подробная информация о тестировании доступна в [документации](docs/README.md).

---

## 📖 Документация

- **📚 Полная документация**: [docs/README.md](docs/README.md) - Подробное описание всех классов, методов и примеров
- **🚀 Примеры использования**: [examples/README.md](examples/README.md) - Готовые примеры для быстрого старта
- **🔧 Интерактивное меню**: [examples/index.php](examples/index.php) - Удобное меню для запуска примеров

---

## 🔄 API

Полная документация по API доступна в [документации сервисов](docs/services/) и [примерах использования](examples/README.md).

---

## 🏗️ Архитектура

Библиотека построена на принципах:

- **Неизменяемость** - все модели используют readonly свойства
- **Типобезопасность** - строгая типизация и enums
- **Разделение ответственности** - отдельные сервисы для разных сущностей
- **Документированность** - подробные PHPDoc комментарии
- **Тестируемость** - покрытие unit-тестами

---

## ⚠️ Важные предостережения

### 📅 Актуальность API

- **Структура API** в последний раз обновлялась **16.10.2023**, но данные по запросам актуальны
- **Некоторые методы запросов API** не возвращают актуальные данные, либо разработчик далее не разрабатывал, либо Кинопоиск не возвращает эти данные (не знаю, я не разрабатывал этот API)

### 🔗 Не путать с kinopoisk.dev

- Данная библиотека работает с **Kinopoisk Unofficial API** (kinopoiskapiunofficial.tech)
- **НЕ путать** с kinopoisk.dev - это другой API с другой структурой и возможностями

### 📊 Ограничения

- API имеет лимиты на количество запросов
- Некоторые эндпоинты могут быть недоступны или возвращать неполные данные
- Рекомендуется всегда обрабатывать ошибки и проверять возвращаемые данные

---

## 🤝 Вклад в проект

1. Форкните репозиторий: [https://github.com/DevCraftClub/NotKinopoiskPHP](https://github.com/DevCraftClub/NotKinopoiskPHP)
2. Создайте ветку для новой функции (`git checkout -b feature/amazing-feature`)
3. Зафиксируйте изменения (`git commit -m 'Add amazing feature'`)
4. Отправьте в ветку (`git push origin feature/amazing-feature`)
5. Откройте Pull Request

---

## 📄 Лицензия

Этот проект лицензирован под MIT License - см. файл [LICENSE](LICENSE) для деталей.

