# Инструкции по установке NotKinopoisk PHP Wrapper

## Требования

- PHP 8.3 или выше
- Composer
- Git

## Установка

### 1. Клонирование репозитория

```bash
git clone https://github.com/your-username/NotKinopoiskPHP.git
cd NotKinopoiskPHP
```

### 2. Установка зависимостей

```bash
composer install
```

### 3. Получение API ключа

1. Перейдите на [Kinopoisk Unofficial API](https://kinopoiskapiunofficial.tech/)
2. Зарегистрируйтесь и получите API ключ
3. Выберите один из способов настройки ключа:

#### Способ A: Переменная окружения

```bash
export KINOPOISK_API_KEY=ваш_ключ_здесь
```

#### Способ B: .env файл (рекомендуется для разработки)

Скопируйте пример файла:

```bash
cp env.example .env
```

Отредактируйте `.env` файл:

```env
KINOPOISK_API_KEY=ваш_ключ_здесь
```

#### Способ C: Передача в код (не рекомендуется для продакшена)

```php
$client = new NotKinopoisk\Client('ваш_ключ_здесь');
```

### 4. Проверка установки

Запустите тесты:

```bash
composer test
```

Или запустите пример:

```bash
php examples/basic_usage.php
```

## Настройка окружения

### Для разработки

1. Создайте `.env` файл в корне проекта
2. Добавьте ваш API ключ:
   ```env
   KINOPOISK_API_KEY=ваш_ключ_здесь
   ```
3. Добавьте `.env` в `.gitignore` (уже добавлен)

### Для продакшена

Используйте переменные окружения сервера:

```bash
# Linux/macOS
export KINOPOISK_API_KEY=ваш_ключ_здесь

# Windows
set KINOPOISK_API_KEY=ваш_ключ_здесь
```

### Для Docker

```dockerfile
ENV KINOPOISK_API_KEY=ваш_ключ_здесь
```

Или через docker-compose:

```yaml
environment:
  - KINOPOISK_API_KEY=ваш_ключ_здесь
```

## Использование

### Базовый пример

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;

$client = new Client('ваш-api-ключ');

// Получаем информацию о фильме
$film = $client->films->getById(301);
echo $film->getDisplayName(); // "Матрица"
```

### Запуск примеров

```bash
# Базовый пример
php examples/basic_usage.php
```

## Тестирование

### Запуск тестов

```bash
composer test
```

### Проверка качества кода

```bash
# PHPStan статический анализ
composer phpstan

# PHP CodeSniffer проверка стиля
composer cs

# Автоисправление стиля
composer cs-fix
```

## Структура проекта

```
NotKinopoiskPHP/
├── src/                    # Исходный код
│   ├── Client.php         # Основной клиент
│   ├── Exception/         # Исключения
│   ├── Models/            # Модели данных
│   └── Services/          # Сервисы API
├── tests/                 # Тесты
├── examples/              # Примеры использования
├── composer.json          # Зависимости
├── README.md              # Документация
└── INSTALL.md             # Этот файл
```

## Возможные проблемы

### Ошибка "Class not found"

Убедитесь, что:

1. Composer установлен
2. Выполнена команда `composer install`
3. Подключен автозагрузчик: `require_once 'vendor/autoload.php'`

### Ошибка "Invalid API key"

1. Проверьте правильность API ключа
2. Убедитесь, что ключ активен на сайте [https://kinopoiskapiunofficial.tech](https://kinopoiskapiunofficial.tech)

### Ошибка "Rate limit exceeded"

API имеет ограничения:

- 20 запросов в секунду для большинства эндпоинтов
- 5 запросов в секунду для некоторых эндпоинтов
- 2 запроса в секунду для пользовательских данных

Добавьте задержки между запросами:

```php
sleep(1); // Задержка 1 секунда
```

## Поддержка

Если у вас возникли проблемы:

1. Проверьте [README.md](README.md) для полной документации
2. Посмотрите примеры в папке `examples/`
3. Создайте issue в репозитории проекта

## Лицензия

MIT License - см. файл [LICENSE](LICENSE)
