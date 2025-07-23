<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель оценки пользователя из Kinopoisk API
 * 
 * Представляет информацию о фильме с оценкой пользователя,
 * включая метаданные фильма и пользовательский рейтинг.
 * 
 * Основные возможности:
 * - Хранение информации о фильме с оценкой в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого названия фильма
 * - Доступ к рейтингам и метаданным
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\UserService
 * @see \NotKinopoisk\Models\Country
 * @see \NotKinopoisk\Models\Genre
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $userVote = UserVote::fromArray($apiData);
 * 
 * // Использование
 * echo "Фильм: {$userVote->getDisplayName()}\n";
 * echo "Ваша оценка: {$userVote->userRating}/10\n";
 * echo "Рейтинг Кинопоиска: {$userVote->ratingKinopoisk}";
 * ```
 */
class UserVote
{
    /**
     * Конструктор модели оценки пользователя
     * 
     * Создает новый экземпляр оценки пользователя со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $kinopoiskId Уникальный идентификатор фильма в Кинопоиске
     * @param string|null $nameRu Название фильма на русском языке
     * @param string|null $nameEn Название фильма на английском языке
     * @param string|null $nameOriginal Оригинальное название фильма
     * @param array $countries Массив стран производства
     * @param array $genres Массив жанров фильма
     * @param float|null $ratingKinopoisk Рейтинг на Кинопоиске
     * @param float|null $ratingImbd Рейтинг на IMDb
     * @param string|null $year Год выпуска фильма
     * @param string $type Тип контента (FILM, SERIES и т.д.)
     * @param string $posterUrl URL постера фильма
     * @param string $posterUrlPreview URL превью постера фильма
     * @param int $userRating Оценка пользователя (1-10)
     * 
     * @example
     * ```php
     * $userVote = new UserVote(
     *     kinopoiskId: 12345,
     *     nameRu: 'Матрица',
     *     nameEn: 'The Matrix',
     *     nameOriginal: 'The Matrix',
     *     countries: [$country1, $country2],
     *     genres: [$genre1, $genre2],
     *     ratingKinopoisk: 8.7,
     *     ratingImbd: 8.7,
     *     year: '1999',
     *     type: 'FILM',
     *     posterUrl: 'https://...',
     *     posterUrlPreview: 'https://...',
     *     userRating: 9
     * );
     * ```
     */
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?float $ratingKinopoisk,
        public readonly ?float $ratingImbd,
        public readonly ?string $year,
        public readonly string $type,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly int $userRating
    ) {
    }

    /**
     * Создает экземпляр оценки пользователя из массива данных API
     * 
     * Статический метод для удобного создания объекта UserVote из данных,
     * полученных от Kinopoisk API. Автоматически создает объекты Country и Genre
     * для каждого элемента в соответствующих массивах.
     * 
     * @param array $data Массив данных оценки пользователя от API
     * 
     * @return self Новый экземпляр оценки пользователя
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'kinopoiskId' => 12345,
     *     'nameRu' => 'Матрица',
     *     'nameEn' => 'The Matrix',
     *     'nameOriginal' => 'The Matrix',
     *     'countries' => [['country' => 'США']],
     *     'genres' => [['genre' => 'Боевик']],
     *     'ratingKinopoisk' => 8.7,
     *     'ratingImbd' => 8.7,
     *     'year' => '1999',
     *     'type' => 'FILM',
     *     'posterUrl' => 'https://...',
     *     'posterUrlPreview' => 'https://...',
     *     'userRating' => 9
     * ];
     * 
     * $userVote = UserVote::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            countries: array_map(fn($country) => Country::fromArray($country), $data['countries']),
            genres: array_map(fn($genre) => Genre::fromArray($genre), $data['genres']),
            ratingKinopoisk: $data['ratingKinopoisk'] ?? null,
            ratingImbd: $data['ratingImbd'] ?? null,
            year: $data['year'] ?? null,
            type: $data['type'],
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            userRating: $data['userRating']
        );
    }

    /**
     * Получает отображаемое название фильма
     * 
     * Возвращает наиболее подходящее название для отображения пользователю.
     * Приоритет: русское название → английское название → оригинальное название → "Без названия"
     * 
     * @return string Отображаемое название фильма
     * 
     * @example
     * ```php
     * echo $userVote->getDisplayName(); // "Матрица" или "The Matrix" или "Без названия"
     * ```
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
    }
}

/**
 * Модель информации об API ключе из Kinopoisk API
 * 
 * Представляет информацию о лимитах и квотах API ключа,
 * включая общие и дневные лимиты запросов и тип аккаунта.
 * 
 * Основные возможности:
 * - Хранение информации о квотах API в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для работы с квотами
 * - Проверка типа аккаунта
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\UserService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $apiKeyInfo = ApiKeyInfo::fromArray($apiData);
 * 
 * // Использование
 * echo "Использовано запросов: {$apiKeyInfo->getTotalQuotaUsed()}\n";
 * echo "Всего доступно: {$apiKeyInfo->getTotalQuotaValue()}\n";
 * echo "Дневной лимит: {$apiKeyInfo->getDailyQuotaValue()}\n";
 * echo "Безлимитный аккаунт: " . ($apiKeyInfo->isUnlimited() ? 'Да' : 'Нет');
 * ```
 */
class ApiKeyInfo
{
    /**
     * Конструктор модели информации об API ключе
     * 
     * Создает новый экземпляр информации об API ключе со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param array $totalQuota Массив с общей квотой (used, value)
     * @param array $dailyQuota Массив с дневной квотой (used, value)
     * @param string $accountType Тип аккаунта (FREE, PAID, UNLIMITED)
     * 
     * @example
     * ```php
     * $apiKeyInfo = new ApiKeyInfo(
     *     totalQuota: ['used' => 100, 'value' => 1000],
     *     dailyQuota: ['used' => 10, 'value' => 100],
     *     accountType: 'PAID'
     * );
     * ```
     */
    public function __construct(
        public readonly array $totalQuota,
        public readonly array $dailyQuota,
        public readonly string $accountType
    ) {
    }

    /**
     * Создает экземпляр информации об API ключе из массива данных API
     * 
     * Статический метод для удобного создания объекта ApiKeyInfo из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных информации об API ключе от API
     * 
     * @return self Новый экземпляр информации об API ключе
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'totalQuota' => ['used' => 100, 'value' => 1000],
     *     'dailyQuota' => ['used' => 10, 'value' => 100],
     *     'accountType' => 'PAID'
     * ];
     * 
     * $apiKeyInfo = ApiKeyInfo::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            totalQuota: $data['totalQuota'],
            dailyQuota: $data['dailyQuota'],
            accountType: $data['accountType']
        );
    }

    /**
     * Получает количество использованных запросов из общей квоты
     * 
     * Возвращает количество запросов, которые уже были использованы
     * из общей квоты API ключа.
     * 
     * @return int Количество использованных запросов
     * 
     * @example
     * ```php
     * echo "Использовано: {$apiKeyInfo->getTotalQuotaUsed()} запросов";
     * ```
     */
    public function getTotalQuotaUsed(): int
    {
        return $this->totalQuota['used'] ?? 0;
    }

    /**
     * Получает общее количество доступных запросов
     * 
     * Возвращает общее количество запросов, доступных для использования
     * с данным API ключом.
     * 
     * @return int Общее количество доступных запросов
     * 
     * @example
     * ```php
     * echo "Доступно: {$apiKeyInfo->getTotalQuotaValue()} запросов";
     * ```
     */
    public function getTotalQuotaValue(): int
    {
        return $this->totalQuota['value'] ?? 0;
    }

    /**
     * Получает количество использованных запросов из дневной квоты
     * 
     * Возвращает количество запросов, которые уже были использованы
     * из дневной квоты API ключа.
     * 
     * @return int Количество использованных запросов за день
     * 
     * @example
     * ```php
     * echo "Использовано сегодня: {$apiKeyInfo->getDailyQuotaUsed()} запросов";
     * ```
     */
    public function getDailyQuotaUsed(): int
    {
        return $this->dailyQuota['used'] ?? 0;
    }

    /**
     * Получает дневной лимит запросов
     * 
     * Возвращает максимальное количество запросов, доступных
     * для использования в течение одного дня.
     * 
     * @return int Дневной лимит запросов
     * 
     * @example
     * ```php
     * echo "Дневной лимит: {$apiKeyInfo->getDailyQuotaValue()} запросов";
     * ```
     */
    public function getDailyQuotaValue(): int
    {
        return $this->dailyQuota['value'] ?? 0;
    }

    /**
     * Проверяет, является ли аккаунт безлимитным
     * 
     * Определяет, имеет ли API ключ безлимитный доступ к API
     * без ограничений по количеству запросов.
     * 
     * @return bool true если аккаунт безлимитный, false в противном случае
     * 
     * @example
     * ```php
     * if ($apiKeyInfo->isUnlimited()) {
     *     echo "Безлимитный аккаунт - можно делать неограниченное количество запросов";
     * } else {
     *     echo "Лимитированный аккаунт - есть ограничения по запросам";
     * }
     * ```
     */
    public function isUnlimited(): bool
    {
        return $this->accountType === 'UNLIMITED';
    }
}

/**
 * Модель результата поиска персон
 */
class PersonSearchResult
{
    public function __construct(
        public readonly array $items,
        public readonly int $total
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            items: array_map(fn($personData) => Person::fromArray($personData), $data['items']),
            total: $data['total']
        );
    }

    public function getCount(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}

/**
 * Модель медиа поста
 */
class MediaPost
{
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly string $imageUrl,
        public readonly string $title,
        public readonly string $description,
        public readonly string $url,
        public readonly string $publishedAt
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            imageUrl: $data['imageUrl'],
            title: $data['title'],
            description: $data['description'],
            url: $data['url'],
            publishedAt: $data['publishedAt']
        );
    }
} 