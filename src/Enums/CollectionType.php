<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы коллекций фильмов в Kinopoisk API
 *
 * Определяет различные типы коллекций фильмов,
 * которые можно получить через API: топ-250, популярные и т.д.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * $collection = $filmService->getCollections(CollectionType::TOP_POPULAR_ALL);
 * ```
 */
enum CollectionType: string {

	/** Топ популярных фильмов и сериалов */
	case TOP_POPULAR_ALL = 'TOP_POPULAR_ALL';

	/** Топ популярных фильмов */
	case TOP_POPULAR_MOVIES = 'TOP_POPULAR_MOVIES';

	/** Топ популярных сериалов */
	case TOP_POPULAR_SERIES = 'TOP_POPULAR_SERIES';

	/** Топ-250 фильмов */
	case TOP_250_MOVIES = 'TOP_250_MOVIES';

	/** Топ-250 сериалов */
	case TOP_250_SERIES = 'TOP_250_SERIES';

	/**
	 * Получает человекочитаемое название типа коллекции
	 *
	 * @return string Название типа коллекции на русском языке
	 *
	 * @example
	 * ```php
	 * echo CollectionType::TOP_250_MOVIES->getDisplayName(); // "Топ-250 фильмов"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::TOP_POPULAR_ALL    => 'Топ популярных фильмов и сериалов',
			self::TOP_POPULAR_MOVIES => 'Топ популярных фильмов',
			self::TOP_POPULAR_SERIES => 'Топ популярных сериалов',
			self::TOP_250_MOVIES     => 'Топ-250 фильмов',
			self::TOP_250_SERIES     => 'Топ-250 сериалов',
		};
	}

}