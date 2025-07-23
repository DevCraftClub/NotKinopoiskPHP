<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы контента в Kinopoisk API
 *
 * Определяет различные типы контента, которые могут быть
 * возвращены API: фильмы, сериалы, мини-сериалы и т.д.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($film->type === ContentType::FILM) {
 *     echo "Это фильм";
 * } elseif ($film->type === ContentType::SERIES) {
 *     echo "Это сериал";
 * }
 * ```
 */
enum ContentType: string {

	/** Фильм */
	case FILM = 'FILM';

	/** Сериал */
	case SERIES = 'SERIES';

	/** Мини-сериал */
	case MINI_SERIES = 'MINI_SERIES';

	/** Телешоу */
	case TV_SHOW = 'TV_SHOW';

	/** ТВ-фильм */
	case TV_MOVIE = 'TV_MOVIE';

	/** Видео */
	case VIDEO = 'VIDEO';

	/** Короткометражка */
	case SHORT = 'SHORT';

	/** Документальный фильм */
	case DOCUMENTARY = 'DOCUMENTARY';

	/** ТВ-сериал (альтернативное название) */
	case TV_SERIES = 'TV_SERIES';

	/** Неизвестный тип */
	case UNKNOWN = 'UNKNOWN';

	/** Все типы (для фильтрации) */
	case ALL = 'ALL';

	/**
	 * Проверяет, является ли контент фильмом
	 *
	 * @return bool true если это фильм, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ContentType::FILM->isFilm()) {
	 *     echo "Это фильм";
	 * }
	 * ```
	 */
	public function isFilm(): bool {
		return $this === self::FILM;
	}

	/**
	 * Проверяет, является ли контент сериалом
	 *
	 * @return bool true если это сериал, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ContentType::SERIES->isSeries()) {
	 *     echo "Это сериал";
	 * }
	 * ```
	 */
	public function isSeries(): bool {
		return in_array($this, [self::SERIES, self::MINI_SERIES, self::TV_SHOW, self::TV_SERIES]);
	}

	/**
	 * Получает человекочитаемое название типа контента
	 *
	 * @return string Название типа контента на русском языке
	 *
	 * @example
	 * ```php
	 * echo ContentType::FILM->getDisplayName(); // "Фильм"
	 * echo ContentType::SERIES->getDisplayName(); // "Сериал"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::FILM        => 'Фильм',
			self::SERIES      => 'Сериал',
			self::MINI_SERIES => 'Мини-сериал',
			self::TV_SHOW     => 'Телешоу',
			self::TV_MOVIE    => 'ТВ-фильм',
			self::VIDEO       => 'Видео',
			self::SHORT       => 'Короткометражка',
			self::DOCUMENTARY => 'Документальный фильм',
			self::TV_SERIES   => 'ТВ-сериал',
			self::UNKNOWN     => 'Неизвестно',
			self::ALL         => 'Все типы',
		};
	}

}