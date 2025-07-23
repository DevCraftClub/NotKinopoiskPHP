<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель эпизода сериала из Kinopoisk API
 * 
 * Представляет информацию об отдельном эпизоде сериала, включая
 * номер сезона, номер эпизода, название, синопсис и дату выхода.
 * 
 * Основные возможности:
 * - Хранение информации об эпизоде в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого названия эпизода
 * - Поддержка многоязычных названий
 * 
 * @package NotKinopoisk\Models
 * @author Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @see \NotKinopoisk\Models\Season
 * @see \NotKinopoisk\Services\FilmService
 * 
 * @example
 * ```php
 * // Создание из данных API
 * $episode = Episode::fromArray($apiData);
 * 
 * // Работа с эпизодом
 * echo "Сезон {$episode->seasonNumber}, Эпизод {$episode->episodeNumber}\n";
 * echo "Название: {$episode->getDisplayName()}\n";
 * 
 * if ($episode->synopsis) {
 *     echo "Синопсис: {$episode->synopsis}\n";
 * }
 * ```
 */
class Episode
{
    /**
     * Конструктор модели эпизода
     * 
     * Создает новый экземпляр эпизода со всеми необходимыми данными.
     * Все свойства являются readonly для обеспечения неизменяемости объекта.
     * 
     * @param int $seasonNumber Номер сезона
     * @param int $episodeNumber Номер эпизода в сезоне
     * @param string|null $nameRu Название эпизода на русском языке
     * @param string|null $nameEn Название эпизода на английском языке
     * @param string|null $synopsis Краткое описание сюжета эпизода
     * @param string|null $releaseDate Дата выхода эпизода
     * 
     * @example
     * ```php
     * $episode = new Episode(
     *     seasonNumber: 1,
     *     episodeNumber: 1,
     *     nameRu: 'Пилот',
     *     nameEn: 'Pilot',
     *     synopsis: 'Первый эпизод сериала...',
     *     releaseDate: '2023-01-15'
     * );
     * ```
     */
    public function __construct(
        public readonly int $seasonNumber,
        public readonly int $episodeNumber,
        public readonly ?string $nameRu,
        public readonly ?string $nameEn,
        public readonly ?string $synopsis,
        public readonly ?string $releaseDate
    ) {
    }

    /**
     * Создает экземпляр эпизода из массива данных API
     * 
     * Статический метод для удобного создания объекта Episode из данных,
     * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
     * и устанавливает значения по умолчанию.
     * 
     * @param array $data Массив данных эпизода от API
     * 
     * @return self Новый экземпляр эпизода
     * 
     * @throws \InvalidArgumentException Если данные имеют неверный формат
     * 
     * @example
     * ```php
     * $apiData = [
     *     'seasonNumber' => 1,
     *     'episodeNumber' => 1,
     *     'nameRu' => 'Пилот',
     *     'nameEn' => 'Pilot',
     *     'synopsis' => 'Первый эпизод сериала...',
     *     'releaseDate' => '2023-01-15'
     * ];
     * 
     * $episode = Episode::fromArray($apiData);
     * ```
     */
    public static function fromArray(array $data): self
    {
        return new self(
            seasonNumber: $data['seasonNumber'],
            episodeNumber: $data['episodeNumber'],
            nameRu: $data['nameRu'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            synopsis: $data['synopsis'] ?? null,
            releaseDate: $data['releaseDate'] ?? null
        );
    }

    /**
     * Получает отображаемое название эпизода
     * 
     * Возвращает наиболее подходящее название для отображения пользователю.
     * Приоритет: русское название → английское название → "Эпизод N"
     * 
     * @return string Отображаемое название эпизода
     * 
     * @example
     * ```php
     * echo $episode->getDisplayName(); // "Пилот" или "Pilot" или "Эпизод 1"
     * ```
     */
    public function getDisplayName(): string
    {
        return $this->nameRu ?? $this->nameEn ?? "Эпизод {$this->episodeNumber}";
    }
} 