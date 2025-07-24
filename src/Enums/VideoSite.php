<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы видео-сайтов в Kinopoisk API
 *
 * Определяет различные платформы, на которых размещены видео:
 * YouTube, Vimeo, Kinopoisk и другие.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * $video = new Video(
 *     url: 'https://youtube.com/watch?v=...',
 *     name: 'Трейлер',
 *     site: VideoSite::YOUTUBE
 * );
 * ```
 */
enum VideoSite: string {

	/** YouTube */
	case YOUTUBE = 'YOUTUBE';

	/** Vimeo */
	case VIMEO = 'VIMEO';

	/** Kinopoisk */
	case KINOPOISK = 'KINOPOISK';

	/** Kinopoisk Widget */
	case KINOPOISK_WIDGET = 'KINOPOISK_WIDGET';

	/** Неизвестный сайт */
	case UNKNOWN = 'UNKNOWN';

	/**
	 * Получает человекочитаемое название сайта
	 *
	 * @return string Название сайта на русском языке
	 *
	 * @example
	 * ```php
	 * echo VideoSite::YOUTUBE->getDisplayName(); // "YouTube"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::YOUTUBE          => 'YouTube',
			self::VIMEO            => 'Vimeo',
			self::KINOPOISK        => 'Кинопоиск',
			self::KINOPOISK_WIDGET => 'Кинопоиск-виджет',
			self::UNKNOWN          => 'Неизвестно',
		};
	}

	/**
	 * Проверяет, является ли сайт внешним (не Kinopoisk)
	 *
	 * @return bool true если внешний сайт, false если Kinopoisk
	 *
	 * @example
	 * ```php
	 * if (VideoSite::YOUTUBE->isExternal()) {
	 *     echo "Внешний сайт";
	 * }
	 * ```
	 */
	public function isExternal(): bool {
		return !in_array($this, [self::KINOPOISK, self::KINOPOISK_WIDGET]);
	}

}