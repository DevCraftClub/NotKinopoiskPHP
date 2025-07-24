<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\RelationType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель связанного фильма
 *
 * Представляет информацию о фильме, связанном с основным фильмом
 * (похожие фильмы, сиквелы, приквелы и т.д.).
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}/similars
 * @since   1.0.0
 * @author  Maxim Harder
 * @version 1.0.0
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__similars
 */
class RelatedFilm implements ModelInterface {

	/**
	 * Конструктор модели связанного фильма
	 *
	 * @param   int           $filmId            Уникальный идентификатор фильма
	 * @param   string|null   $nameRu            Название на русском
	 * @param   string|null   $nameEn            Название на английском
	 * @param   string|null   $nameOriginal      Оригинальное название
	 * @param   string        $posterUrl         URL постера
	 * @param   string        $posterUrlPreview  URL превью постера
	 * @param   RelationType  $relationType      Тип связи
	 */
	public function __construct(
		public readonly int          $filmId,
		public readonly ?string      $nameRu,
		public readonly ?string      $nameEn,
		public readonly ?string      $nameOriginal,
		public readonly string       $posterUrl,
		public readonly string       $posterUrlPreview,
		public readonly RelationType $relationType,
	) {}

	/**
	 * Создает экземпляр модели из массива данных API
	 *
	 * @param   array  $data  Массив данных от API
	 *
	 * @return self Экземпляр модели
	 *
	 * @throws \InvalidArgumentException При некорректных данных
	 *
	 * @example
	 * ```php
	 * $filmData = [
	 *     'filmId' => 301,
	 *     'nameRu' => 'Матрица',
	 *     'nameEn' => 'The Matrix',
	 *     'nameOriginal' => 'The Matrix',
	 *     'posterUrl' => 'https://example.com/poster.jpg',
	 *     'posterUrlPreview' => 'https://example.com/poster_small.jpg',
	 *     'relationType' => 'SIMILAR'
	 * ];
	 * $film = RelatedFilm::fromArray($filmData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			filmId          : $data['filmId'],
			nameRu          : $data['nameRu'] ?? NULL,
			nameEn          : $data['nameEn'] ?? NULL,
			nameOriginal    : $data['nameOriginal'] ?? NULL,
			posterUrl       : $data['posterUrl'],
			posterUrlPreview: $data['posterUrlPreview'],
			relationType    : RelationType::from($data['relationType']),
		);
	}

	/**
	 * Получает отображаемое название фильма
	 *
	 * Возвращает название фильма в приоритетном порядке:
	 * русское название -> английское название -> оригинальное название
	 *
	 * @return string Отображаемое название фильма
	 *
	 * @example
	 * ```php
	 * $displayName = $film->getDisplayName();
	 * echo "Название: $displayName";
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? $this->nameOriginal ?? 'Без названия';
	}

	/**
	 * Преобразует модель в массив
	 *
	 * @return array Массив с данными модели
	 */
	public function toArray(): array {
		return [
			'filmId'           => $this->filmId,
			'nameRu'           => $this->nameRu,
			'nameEn'           => $this->nameEn,
			'nameOriginal'     => $this->nameOriginal,
			'posterUrl'        => $this->posterUrl,
			'posterUrlPreview' => $this->posterUrlPreview,
			'relationType'     => $this->relationType->value,
		];
	}

}