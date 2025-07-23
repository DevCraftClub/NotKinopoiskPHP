<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\RelationType;

/**
 * Модель связанного фильма
 *
 * Представляет информацию о фильме, связанном с основным фильмом
 * (похожие фильмы, сиквелы, приквелы и т.д.).
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 * @author  Maxim Harder
 * @version 1.0.0
 */
class RelatedFilm {

	/**
	 * Уникальный идентификатор фильма в Кинопоиске
	 *
	 * @var integer
	 */
	public readonly int $filmId;

	/**
	 * Название фильма на русском языке
	 *
	 * @var string|null
	 */
	public readonly ?string $nameRu;

	/**
	 * Название фильма на английском языке
	 *
	 * @var string|null
	 */
	public readonly ?string $nameEn;

	/**
	 * Оригинальное название фильма
	 *
	 * @var string|null
	 */
	public readonly ?string $nameOriginal;

	/**
	 * URL постера фильма
	 *
	 * @var string
	 */
	public readonly string $posterUrl;

	/**
	 * URL превью постера фильма
	 *
	 * @var string
	 */
	public readonly string $posterUrlPreview;

	/**
	 * Тип связи с основным фильмом
	 *
	 * @var RelationType
	 */
	public readonly RelationType $relationType;

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
		int          $filmId,
		?string      $nameRu,
		?string      $nameEn,
		?string      $nameOriginal,
		string       $posterUrl,
		string       $posterUrlPreview,
		RelationType $relationType,
	) {
		$this->filmId           = $filmId;
		$this->nameRu           = $nameRu;
		$this->nameEn           = $nameEn;
		$this->nameOriginal     = $nameOriginal;
		$this->posterUrl        = $posterUrl;
		$this->posterUrlPreview = $posterUrlPreview;
		$this->relationType     = $relationType;
	}

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
	public static function fromArray(array $data): self {
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