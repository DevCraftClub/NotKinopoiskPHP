<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Models\Film;
use NotKinopoisk\Models\Season;
use NotKinopoisk\Models\Fact;
use NotKinopoisk\Models\Distribution;
use NotKinopoisk\Models\BoxOffice;
use NotKinopoisk\Models\Award;
use NotKinopoisk\Models\Video;
use NotKinopoisk\Models\Image;
use NotKinopoisk\Models\Review;
use NotKinopoisk\Models\ExternalSource;
use NotKinopoisk\Models\RelatedFilm;
use NotKinopoisk\Models\FilmCollection;
use NotKinopoisk\Models\Premiere;
use NotKinopoisk\Models\Filters;

/**
 * Сервис для работы с фильмами
 * Реализует CRUD операции: Create (поиск), Read (получение), Update (не поддерживается), Delete (не поддерживается)
 */
class FilmService extends AbstractService
{
    /**
     * Получает информацию о фильме по ID
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return Film
     */
    public function getById(int $id): Film
    {
        $data = $this->get($this->buildUri("films/{$id}"));
        return Film::fromArray($data);
    }

    /**
     * Получает сезоны сериала
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return Season[]
     */
    public function getSeasons(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/seasons"));
        return array_map(fn($seasonData) => Season::fromArray($seasonData), $data['items']);
    }

    /**
     * Получает факты и ошибки фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return Fact[]
     */
    public function getFacts(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/facts"));
        return array_map(fn($factData) => Fact::fromArray($factData), $data['items']);
    }

    /**
     * Получает данные о прокате фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return Distribution[]
     */
    public function getDistributions(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/distributions"));
        return array_map(fn($distData) => Distribution::fromArray($distData), $data['items']);
    }

    /**
     * Получает данные о бюджете и сборах фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return BoxOffice[]
     */
    public function getBoxOffice(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/box_office"));
        return array_map(fn($boxData) => BoxOffice::fromArray($boxData), $data['items']);
    }

    /**
     * Получает награды фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return Award[]
     */
    public function getAwards(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/awards"));
        return array_map(fn($awardData) => Award::fromArray($awardData), $data['items']);
    }

    /**
     * Получает видео (трейлеры, тизеры) фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return Video[]
     */
    public function getVideos(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/videos"));
        return array_map(fn($videoData) => Video::fromArray($videoData), $data['items']);
    }

    /**
     * Получает похожие фильмы
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return RelatedFilm[]
     */
    public function getSimilar(int $id): array
    {
        $data = $this->get($this->buildUri("films/{$id}/similars"));
        return array_map(fn($filmData) => RelatedFilm::fromArray($filmData), $data['items']);
    }

    /**
     * Получает изображения фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @param string $type Тип изображения (STILL, SHOOTING, POSTER, FAN_ART, PROMO, CONCEPT, WALLPAPER, COVER, SCREENSHOT)
     * @param int $page Номер страницы
     * @return Image[]
     */
    public function getImages(int $id, string $type = 'STILL', int $page = 1): array
    {
        $data = $this->get($this->buildUri("films/{$id}/images"), [
            'type' => $type,
            'page' => $page
        ]);
        return array_map(fn($imageData) => Image::fromArray($imageData), $data['items']);
    }

    /**
     * Получает рецензии фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @param int $page Номер страницы
     * @param string $order Сортировка (DATE_ASC, DATE_DESC, USER_POSITIVE_RATING_ASC, USER_POSITIVE_RATING_DESC, USER_NEGATIVE_RATING_ASC, USER_NEGATIVE_RATING_DESC)
     * @return Review[]
     */
    public function getReviews(int $id, int $page = 1, string $order = 'DATE_DESC'): array
    {
        $data = $this->get($this->buildUri("films/{$id}/reviews"), [
            'page' => $page,
            'order' => $order
        ]);
        return array_map(fn($reviewData) => Review::fromArray($reviewData), $data['items']);
    }

    /**
     * Получает внешние источники для просмотра фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @param int $page Номер страницы
     * @return ExternalSource[]
     */
    public function getExternalSources(int $id, int $page = 1): array
    {
        $data = $this->get($this->buildUri("films/{$id}/external_sources"), [
            'page' => $page
        ]);
        return array_map(fn($sourceData) => ExternalSource::fromArray($sourceData), $data['items']);
    }

    /**
     * Получает сиквелы и приквелы фильма
     * READ операция
     *
     * @param int $id ID фильма в Кинопоиске
     * @return RelatedFilm[]
     */
    public function getSequelsAndPrequels(int $id): array
    {
        // Этот эндпоинт не существует в текущей версии API
        // Возвращаем пустой массив
        return [];
    }

    /**
     * Поиск фильмов по ключевым словам
     * CREATE операция (создание поискового запроса)
     *
     * @param string $keyword Ключевые слова для поиска
     * @param int $page Номер страницы
     * @return FilmCollection
     */
    public function searchByKeyword(string $keyword, int $page = 1): FilmCollection
    {
        // Используем поиск по фильтрам с ключевым словом
        $filters = [
            'keyword' => $keyword,
            'page' => $page
        ];
        return $this->searchByFilters($filters);
    }

    /**
     * Получает фильмы из коллекций и топов
     * READ операция
     *
     * @param string $type Тип коллекции
     * @param int $page Номер страницы
     * @return FilmCollection
     */
    public function getCollections(string $type = 'TOP_POPULAR_ALL', int $page = 1): FilmCollection
    {
        $data = $this->get($this->buildUri("films/collections"), [
            'type' => $type,
            'page' => $page
        ]);
        return FilmCollection::fromArray($data);
    }

    /**
     * Получает кинопремьеры
     * READ операция
     *
     * @param int $year Год
     * @param string $month Месяц
     * @return Premiere[]
     */
    public function getPremieres(int $year, string $month): array
    {
        $data = $this->get($this->buildUri("films/premieres"), [
            'year' => $year,
            'month' => $month
        ]);
        return array_map(fn($premiereData) => Premiere::fromArray($premiereData), $data['items']);
    }

    /**
     * Получает фильтры для поиска
     * READ операция
     *
     * @return Filters
     */
    public function getFilters(): Filters
    {
        $data = $this->get($this->buildUri("films/filters"));
        return Filters::fromArray($data);
    }

    /**
     * Поиск фильмов по фильтрам
     * CREATE операция (создание поискового запроса)
     *
     * @param array $filters Параметры фильтрации
     * @return FilmCollection
     */
    public function searchByFilters(array $filters = []): FilmCollection
    {
        $data = $this->get($this->buildUri("films"), $filters);
        return FilmCollection::fromArray($data);
    }

    /**
     * Получает популярные фильмы
     * READ операция
     *
     * @param int $page Номер страницы
     * @return FilmCollection
     */
    public function getPopular(int $page = 1): FilmCollection
    {
        return $this->getCollections('TOP_POPULAR_ALL', $page);
    }

    /**
     * Получает топ-250 фильмов
     * READ операция
     *
     * @param int $page Номер страницы
     * @return FilmCollection
     */
    public function getTop250(int $page = 1): FilmCollection
    {
        return $this->getCollections('TOP_250_MOVIES', $page);
    }

    /**
     * Получает топ-250 сериалов
     * READ операция
     *
     * @param int $page Номер страницы
     * @return FilmCollection
     */
    public function getTop250Series(int $page = 1): FilmCollection
    {
        return $this->getCollections('TOP_250_TV_SHOWS', $page);
    }
} 