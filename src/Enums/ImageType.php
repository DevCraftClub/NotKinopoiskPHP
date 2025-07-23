<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы изображений в Kinopoisk API
 *
 * Определяет различные типы изображений, которые могут быть
 * связаны с фильмом: кадры из фильма, постеры, фоны и т.д.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($imageType === ImageType::STILL) {
 *     echo "Кадр из фильма";
 * } elseif ($imageType === ImageType::POSTER) {
 *     echo "Постер";
 * }
 * ```
 */
enum ImageType: string {

	/** Кадр из фильма */
	case STILL = 'STILL';

	/** Постер */
	case POSTER = 'POSTER';

	/** Фон */
	case BACKGROUND = 'BACKGROUND';

	/** Превью */
	case PREVIEW = 'PREVIEW';

	/**
	 * Получает человекочитаемое название типа изображения
	 *
	 * @return string Название типа изображения на русском языке
	 *
	 * @example
	 * ```php
	 * echo ImageType::STILL->getDisplayName(); // "Кадр из фильма"
	 * echo ImageType::POSTER->getDisplayName(); // "Постер"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::STILL      => 'Кадр из фильма',
			self::POSTER     => 'Постер',
			self::BACKGROUND => 'Фон',
			self::PREVIEW    => 'Превью',
		};
	}

}