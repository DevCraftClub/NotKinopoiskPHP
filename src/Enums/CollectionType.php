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
	case TOP_250_TV_SHOWS = 'TOP_250_TV_SHOWS';

	/** Тематическая коллекция: вампиры */
	case VAMPIRE_THEME = 'VAMPIRE_THEME';

	/** Тематическая коллекция: комиксы */
	case COMICS_THEME = 'COMICS_THEME';

	/** Скоро выходящие фильмы */
	case CLOSES_RELEASES = 'CLOSES_RELEASES';

	/** Семейные фильмы */
	case FAMILY = 'FAMILY';

	/** Победители Оскара 2021 */
	case OSKAR_WINNERS_2021 = 'OSKAR_WINNERS_2021';

	/** Тематическая коллекция: любовь */
	case LOVE_THEME = 'LOVE_THEME';

	/** Тематическая коллекция: зомби */
	case ZOMBIE_THEME = 'ZOMBIE_THEME';

	/** Тематическая коллекция: катастрофы */
	case CATASTROPHE_THEME = 'CATASTROPHE_THEME';

	/** Тематическая коллекция: детская анимация */
	case KIDS_ANIMATION_THEME = 'KIDS_ANIMATION_THEME';

	/** Популярные сериалы */
	case POPULAR_SERIES = 'POPULAR_SERIES';

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
			self::TOP_POPULAR_ALL         => 'Топ популярных фильмов и сериалов',
			self::TOP_POPULAR_MOVIES      => 'Топ популярных фильмов',
			self::TOP_POPULAR_SERIES      => 'Топ популярных сериалов',
			self::TOP_250_MOVIES          => 'Топ-250 фильмов',
			self::TOP_250_TV_SHOWS        => 'Топ-250 сериалов',
			self::VAMPIRE_THEME           => 'Вампиры',
			self::COMICS_THEME            => 'Комиксы',
			self::CLOSES_RELEASES         => 'Скоро выходящие',
			self::FAMILY                  => 'Семейные фильмы',
			self::OSKAR_WINNERS_2021      => 'Победители Оскара 2021',
			self::LOVE_THEME              => 'Любовь',
			self::ZOMBIE_THEME            => 'Зомби',
			self::CATASTROPHE_THEME       => 'Катастрофы',
			self::KIDS_ANIMATION_THEME    => 'Детская анимация',
			self::POPULAR_SERIES          => 'Популярные сериалы',
		};
	}

}