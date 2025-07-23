<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель страны из Kinopoisk API
 * 
 * Представляет информацию о стране производства фильма или сериала.
 * Простая модель для хранения названия страны.
 * 
 * Основные возможности:
 * - Хранение названия страны в неизменяемом виде
 * - Создание объекта из массива данных API
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $country = Country::fromArray(['country' => 'США']);
 * 
 * // Использование
 * echo "Страна: {$country->country}";
 * ```
 */
class Country
{
    /**
     * Конструктор модели страны
     * 
     * Создает новый экземпляр страны с указанным названием.
     * Свойство является readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $country Название страны
     * 
     * @example
     * ```php
     * $country = new Country('США');
     * ```
     */
    public function __construct(
        public readonly string $country
    ) {
    }

    /**
     * Создает экземпляр страны из массива данных API
     * 
     * Статический метод для удобного создания объекта Country из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных страны от API
     * 
     * @return self Новый экземпляр страны
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = ['country' => 'США'];
     * $country = Country::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self($data['country']);
    }
}

/**
 * Модель жанра из Kinopoisk API
 * 
 * Представляет информацию о жанре фильма или сериала.
 * Простая модель для хранения названия жанра.
 * 
 * Основные возможности:
 * - Хранение названия жанра в неизменяемом виде
 * - Создание объекта из массива данных API
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $genre = Genre::fromArray(['genre' => 'Боевик']);
 * 
 * // Использование
 * echo "Жанр: {$genre->genre}";
 * ```
 */
class Genre
{
    /**
     * Конструктор модели жанра
     * 
     * Создает новый экземпляр жанра с указанным названием.
     * Свойство является readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $genre Название жанра
     * 
     * @example
     * ```php
     * $genre = new Genre('Боевик');
     * ```
     */
    public function __construct(
        public readonly string $genre
    ) {
    }

    /**
     * Создает экземпляр жанра из массива данных API
     * 
     * Статический метод для удобного создания объекта Genre из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных жанра от API
     * 
     * @return self Новый экземпляр жанра
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = ['genre' => 'Боевик'];
     * $genre = Genre::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self($data['genre']);
    }
}

/**
 * Модель видео из Kinopoisk API
 * 
 * Представляет информацию о видео, связанном с фильмом:
 * трейлеры, клипы, закулисные материалы и другие видеофайлы.
 * 
 * Основные возможности:
 * - Хранение информации о видео в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к URL и метаданным видео
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\MediaService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $video = Video::fromArray($apiData);
 * 
 * // Использование
 * echo "Видео: {$video->name}\n";
 * echo "URL: {$video->url}\n";
 * echo "Платформа: {$video->site}";
 * ```
 */
class Video
{
    /**
     * Конструктор модели видео
     * 
     * Создает новый экземпляр видео со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $url URL видеофайла
     * @param string $name Название видео
     * @param string $site Платформа или сайт, где размещено видео
     * 
     * @example
     * ```php
     * $video = new Video(
     *     url: 'https://youtube.com/watch?v=...',
     *     name: 'Трейлер фильма',
     *     site: 'YouTube'
     * );
     * ```
     */
    public function __construct(
        public readonly string $url,
        public readonly string $name,
        public readonly string $site
    ) {
    }

    /**
     * Создает экземпляр видео из массива данных API
     * 
     * Статический метод для удобного создания объекта Video из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных видео от API
     * 
     * @return self Новый экземпляр видео
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'url' => 'https://youtube.com/watch?v=...',
     *     'name' => 'Трейлер фильма',
     *     'site' => 'YouTube'
     * ];
     * 
     * $video = Video::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            name: $data['name'],
            site: $data['site']
        );
    }
}

/**
 * Модель изображения из Kinopoisk API
 * 
 * Представляет информацию об изображении, связанном с фильмом:
 * постеры, кадры из фильма, фотографии со съемок и другие изображения.
 * 
 * Основные возможности:
 * - Хранение информации об изображении в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к полному и превью изображениям
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\MediaService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $image = Image::fromArray($apiData);
 * 
 * // Использование
 * echo "Полное изображение: {$image->imageUrl}\n";
 * echo "Превью: {$image->previewUrl}";
 * ```
 */
class Image
{
    /**
     * Конструктор модели изображения
     * 
     * Создает новый экземпляр изображения со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $imageUrl URL полного изображения
     * @param string $previewUrl URL превью изображения
     * 
     * @example
     * ```php
     * $image = new Image(
     *     imageUrl: 'https://kinopoisk.ru/images/full.jpg',
     *     previewUrl: 'https://kinopoisk.ru/images/preview.jpg'
     * );
     * ```
     */
    public function __construct(
        public readonly string $imageUrl,
        public readonly string $previewUrl
    ) {
    }

    /**
     * Создает экземпляр изображения из массива данных API
     * 
     * Статический метод для удобного создания объекта Image из данных,
     * полученных от Kinopoisk API.
     * 
     * @param array $data Массив данных изображения от API
     * 
     * @return self Новый экземпляр изображения
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'imageUrl' => 'https://kinopoisk.ru/images/full.jpg',
     *     'previewUrl' => 'https://kinopoisk.ru/images/preview.jpg'
     * ];
     * 
     * $image = Image::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            imageUrl: $data['imageUrl'],
            previewUrl: $data['previewUrl']
        );
    }
}

/**
 * Модель похожего фильма из Kinopoisk API
 * 
 * Представляет информацию о фильме, связанном с основным фильмом:
 * сиквелы, приквелы, ремейки, фильмы в той же вселенной и т.д.
 * 
 * Основные возможности:
 * - Хранение информации о связанном фильме в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого названия фильма
 * - Определение типа связи между фильмами
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $relatedFilm = RelatedFilm::fromArray($apiData);
 * 
 * // Использование
 * echo "Связанный фильм: {$relatedFilm->getDisplayName()}\n";
 * echo "Тип связи: {$relatedFilm->relationType}\n";
 * echo "ID фильма: {$relatedFilm->filmId}";
 * ```
 */
class RelatedFilm
{
    /**
     * Конструктор модели связанного фильма
     * 
     * Создает новый экземпляр связанного фильма со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $filmId Уникальный идентификатор фильма в Кинопоиске
     * @param string|null $nameRu Название фильма на русском языке
     * @param string|null $nameEn Название фильма на английском языке
     * @param string|null $nameOriginal Оригинальное название фильма
     * @param string $posterUrl URL постера фильма
     * @param string $posterUrlPreview URL превью постера фильма
     * @param string $relationType Тип связи с основным фильмом
     * 
     * @example
     * ```php
     * $relatedFilm = new RelatedFilm(
     *     filmId: 12345,
     *     nameRu: 'Матрица: Перезагрузка',
     *     nameEn: 'The Matrix Reloaded',
     *     nameOriginal: 'The Matrix Reloaded',
     *     posterUrl: 'https://...',
     *     posterUrlPreview: 'https://...',
     *     relationType: 'SEQUEL'
     * );
     * ```
     */
    public function __construct(
        public readonly int $filmId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $nameOriginal,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly string $relationType
    ) {
    }

    /**
     * Создает экземпляр связанного фильма из массива данных API
     * 
     * Статический метод для удобного создания объекта RelatedFilm из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
     * и поддерживает различные варианты ключей для ID фильма.
     * 
     * @param array $data Массив данных связанного фильма от API
     * 
     * @return self Новый экземпляр связанного фильма
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'filmId' => 12345,
     *     'nameRu' => 'Матрица: Перезагрузка',
     *     'nameEn' => 'The Matrix Reloaded',
     *     'nameOriginal' => 'The Matrix Reloaded',
     *     'posterUrl' => 'https://...',
     *     'posterUrlPreview' => 'https://...',
     *     'relationType' => 'SEQUEL'
     * ];
     * 
     * $relatedFilm = RelatedFilm::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            filmId: $data['filmId'] ?? $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            nameOriginal: $data['nameOriginal'] ?? null,
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            relationType: $data['relationType']
        );
    }

    /**
     * Получает отображаемое название связанного фильма
     * 
     * Возвращает наиболее подходящее название для отображения пользователю.
     * Приоритет: русское название → английское название → оригинальное название → "Без названия"
     * 
     * @return string Отображаемое название связанного фильма
     * 
     * @example
     * ```php
     * echo $relatedFilm->getDisplayName(); // "Матрица: Перезагрузка" или "The Matrix Reloaded" или "Без названия"
     * ```
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
    }
}

/**
 * Модель рецензии из Kinopoisk API
 * 
 * Представляет информацию о рецензии на фильм, включая
 * автора, дату, рейтинг и содержание рецензии.
 * 
 * Основные возможности:
 * - Хранение информации о рецензии в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к рейтингу и метаданным рецензии
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $review = Review::fromArray($apiData);
 * 
 * // Использование
 * echo "Автор: {$review->author}\n";
 * echo "Дата: {$review->date}\n";
 * echo "Рейтинг: {$review->positiveRating}/{$review->negativeRating}";
 * ```
 */
class Review
{
    /**
     * Конструктор модели рецензии
     * 
     * Создает новый экземпляр рецензии со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $kinopoiskId Уникальный идентификатор рецензии в Кинопоиске
     * @param string $type Тип рецензии
     * @param string $date Дата публикации рецензии
     * @param int $positiveRating Количество положительных оценок
     * @param int $negativeRating Количество отрицательных оценок
     * @param string $author Автор рецензии
     * @param string|null $title Заголовок рецензии
     * @param string $description Содержание рецензии
     * 
     * @example
     * ```php
     * $review = new Review(
     *     kinopoiskId: 12345,
     *     type: 'POSITIVE',
     *     date: '2023-01-15',
     *     positiveRating: 85,
     *     negativeRating: 15,
     *     author: 'Кинокритик',
     *     title: 'Отличный фильм',
     *     description: 'Подробный анализ фильма...'
     * );
     * ```
     */
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly string $type,
        public readonly string $date,
        public readonly int $positiveRating,
        public readonly int $negativeRating,
        public readonly string $author,
        public readonly ?string $title,
        public readonly string $description
    ) {
    }

    /**
     * Создает экземпляр рецензии из массива данных API
     * 
     * Статический метод для удобного создания объекта Review из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля.
     * 
     * @param array $data Массив данных рецензии от API
     * 
     * @return self Новый экземпляр рецензии
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'kinopoiskId' => 12345,
     *     'type' => 'POSITIVE',
     *     'date' => '2023-01-15',
     *     'positiveRating' => 85,
     *     'negativeRating' => 15,
     *     'author' => 'Кинокритик',
     *     'title' => 'Отличный фильм',
     *     'description' => 'Подробный анализ фильма...'
     * ];
     * 
     * $review = Review::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            type: $data['type'],
            date: $data['date'],
            positiveRating: $data['positiveRating'],
            negativeRating: $data['negativeRating'],
            author: $data['author'],
            title: $data['title'] ?? null,
            description: $data['description']
        );
    }
}

/**
 * Модель внешнего источника из Kinopoisk API
 * 
 * Представляет информацию о рецензии или отзыве с внешней платформы,
 * включая данные о платформе, рейтинге и содержании.
 * 
 * Основные возможности:
 * - Хранение информации о внешнем источнике в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к данным платформы и рейтингу
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $externalSource = ExternalSource::fromArray($apiData);
 * 
 * // Использование
 * echo "Платформа: {$externalSource->platform}\n";
 * echo "Автор: {$externalSource->author}\n";
 * echo "Рейтинг: {$externalSource->positiveRating}/{$externalSource->negativeRating}";
 * ```
 */
class ExternalSource
{
    /**
     * Конструктор модели внешнего источника
     * 
     * Создает новый экземпляр внешнего источника со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param string $url URL источника
     * @param string $platform Название платформы
     * @param string $logoUrl URL логотипа платформы
     * @param int $positiveRating Количество положительных оценок
     * @param int $negativeRating Количество отрицательных оценок
     * @param string $author Автор отзыва
     * @param string|null $title Заголовок отзыва
     * @param string $description Содержание отзыва
     * 
     * @example
     * ```php
     * $externalSource = new ExternalSource(
     *     url: 'https://example.com/review',
     *     platform: 'IMDb',
     *     logoUrl: 'https://...',
     *     positiveRating: 90,
     *     negativeRating: 10,
     *     author: 'Пользователь',
     *     title: 'Отличный фильм',
     *     description: 'Подробный отзыв...'
     * );
     * ```
     */
    public function __construct(
        public readonly string $url,
        public readonly string $platform,
        public readonly string $logoUrl,
        public readonly int $positiveRating,
        public readonly int $negativeRating,
        public readonly string $author,
        public readonly ?string $title,
        public readonly string $description
    ) {
    }

    /**
     * Создает экземпляр внешнего источника из массива данных API
     * 
     * Статический метод для удобного создания объекта ExternalSource из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля.
     * 
     * @param array $data Массив данных внешнего источника от API
     * 
     * @return self Новый экземпляр внешнего источника
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'url' => 'https://example.com/review',
     *     'platform' => 'IMDb',
     *     'logoUrl' => 'https://...',
     *     'positiveRating' => 90,
     *     'negativeRating' => 10,
     *     'author' => 'Пользователь',
     *     'title' => 'Отличный фильм',
     *     'description' => 'Подробный отзыв...'
     * ];
     * 
     * $externalSource = ExternalSource::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            platform: $data['platform'],
            logoUrl: $data['logoUrl'],
            positiveRating: $data['positiveRating'],
            negativeRating: $data['negativeRating'],
            author: $data['author'],
            title: $data['title'] ?? null,
            description: $data['description']
        );
    }
}

/**
 * Модель премьеры из Kinopoisk API
 * 
 * Представляет информацию о премьере фильма, включая
 * название, год, страны, жанры и дату премьеры в России.
 * 
 * Основные возможности:
 * - Хранение информации о премьере в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого названия фильма
 * - Доступ к метаданным премьеры
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * @see \NotKinopoisk\Models\Country
 * @see \NotKinopoisk\Models\Genre
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $premiere = Premiere::fromArray($apiData);
 * 
 * // Использование
 * echo "Премьера: {$premiere->getDisplayName()}\n";
 * echo "Год: {$premiere->year}\n";
 * echo "Премьера в России: {$premiere->premiereRu}";
 * ```
 */
class Premiere
{
    /**
     * Конструктор модели премьеры
     * 
     * Создает новый экземпляр премьеры со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $kinopoiskId Уникальный идентификатор фильма в Кинопоиске
     * @param string|null $nameRu Название фильма на русском языке
     * @param string|null $nameEn Название фильма на английском языке
     * @param int $year Год выпуска фильма
     * @param string $posterUrl URL постера фильма
     * @param string $posterUrlPreview URL превью постера фильма
     * @param array $countries Массив стран производства
     * @param array $genres Массив жанров фильма
     * @param int|null $duration Продолжительность фильма в минутах
     * @param string $premiereRu Дата премьеры в России
     * 
     * @example
     * ```php
     * $premiere = new Premiere(
     *     kinopoiskId: 12345,
     *     nameRu: 'Новый фильм',
     *     nameEn: 'New Movie',
     *     year: 2023,
     *     posterUrl: 'https://...',
     *     posterUrlPreview: 'https://...',
     *     countries: [$country1, $country2],
     *     genres: [$genre1, $genre2],
     *     duration: 120,
     *     premiereRu: '2023-12-01'
     * );
     * ```
     */
    public function __construct(
        public readonly int $kinopoiskId,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly int $year,
        public readonly string $posterUrl,
        public readonly string $posterUrlPreview,
        public readonly array $countries,
        public readonly array $genres,
        public readonly ?int $duration,
        public readonly string $premiereRu
    ) {
    }

    /**
     * Создает экземпляр премьеры из массива данных API
     * 
     * Статический метод для удобного создания объекта Premiere из данных,
     * полученных от Kinopoisk API. Автоматически создает объекты Country и Genre
     * для каждого элемента в соответствующих массивах.
     * 
     * @param array $data Массив данных премьеры от API
     * 
     * @return self Новый экземпляр премьеры
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'kinopoiskId' => 12345,
     *     'nameRu' => 'Новый фильм',
     *     'nameEn' => 'New Movie',
     *     'year' => 2023,
     *     'posterUrl' => 'https://...',
     *     'posterUrlPreview' => 'https://...',
     *     'countries' => [['country' => 'США'], ['country' => 'Великобритания']],
     *     'genres' => [['genre' => 'Боевик'], ['genre' => 'Драма']],
     *     'duration' => 120,
     *     'premiereRu' => '2023-12-01'
     * ];
     * 
     * $premiere = Premiere::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            kinopoiskId: $data['kinopoiskId'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            year: $data['year'],
            posterUrl: $data['posterUrl'],
            posterUrlPreview: $data['posterUrlPreview'],
            countries: array_map(fn($country) => Country::fromArray($country), $data['countries']),
            genres: array_map(fn($genre) => Genre::fromArray($genre), $data['genres']),
            duration: $data['duration'] ?? null,
            premiereRu: $data['premiereRu']
        );
    }

    /**
     * Получает отображаемое название премьеры
     * 
     * Возвращает наиболее подходящее название для отображения пользователю.
     * Приоритет: русское название → английское название → "Без названия"
     * 
     * @return string Отображаемое название премьеры
     * 
     * @example
     * ```php
     * echo $premiere->getDisplayName(); // "Новый фильм" или "New Movie" или "Без названия"
     * ```
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? 'Без названия';
    }
}

/**
 * Модель фильтров из Kinopoisk API
 * 
 * Представляет доступные фильтры для поиска фильмов,
 * включая списки жанров и стран для фильтрации.
 * 
 * Основные возможности:
 * - Хранение списков доступных фильтров в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к спискам жанров и стран
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Services\FilmService
 * @see \NotKinopoisk\Models\Genre
 * @see \NotKinopoisk\Models\Country
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $filters = Filters::fromArray($apiData);
 * 
 * // Использование
 * echo "Доступно жанров: " . count($filters->genres) . "\n";
 * echo "Доступно стран: " . count($filters->countries) . "\n";
 * 
 * foreach ($filters->genres as $genre) {
 *     echo "- {$genre->genre}\n";
 * }
 * ```
 */
class Filters
{
    /**
     * Конструктор модели фильтров
     * 
     * Создает новый экземпляр фильтров со списками доступных жанров и стран.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param array $genres Массив доступных жанров
     * @param array $countries Массив доступных стран
     * 
     * @example
     * ```php
     * $filters = new Filters(
     *     genres: [$genre1, $genre2, $genre3],
     *     countries: [$country1, $country2]
     * );
     * ```
     */
    public function __construct(
        public readonly array $genres,
        public readonly array $countries
    ) {
    }

    /**
     * Создает экземпляр фильтров из массива данных API
     * 
     * Статический метод для удобного создания объекта Filters из данных,
     * полученных от Kinopoisk API. Автоматически создает объекты Genre и Country
     * для каждого элемента в соответствующих массивах.
     * 
     * @param array $data Массив данных фильтров от API
     * 
     * @return self Новый экземпляр фильтров
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'genres' => [
     *         ['genre' => 'Боевик'],
     *         ['genre' => 'Драма'],
     *         ['genre' => 'Комедия']
     *     ],
     *     'countries' => [
     *         ['country' => 'США'],
     *         ['country' => 'Россия'],
     *         ['country' => 'Великобритания']
     *     ]
     * ];
     * 
     * $filters = Filters::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            genres: array_map(fn($genre) => Genre::fromArray($genre), $data['genres']),
            countries: array_map(fn($country) => Country::fromArray($country), $data['countries'])
        );
    }
} 