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

	/** Изображения со съемок */
	case SHOOTING = 'SHOOTING';

	/** Постер */
	case POSTER = 'POSTER';

	/** Фан-арты */
	case FAN_ART = 'FAN_ART';

	/** Промо */
	case PROMO = 'PROMO';

	/** Концепт-арты */
	case CONCEPT = 'CONCEPT';

	/** Обои */
	case WALLPAPER = 'WALLPAPER';

	/** Обложки */
	case COVER = 'COVER';

	/** Скриншоты */
	case SCREENSHOT = 'SCREENSHOT';

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
			self::SHOOTING   => 'Изображения со съемок',
			self::POSTER     => 'Постер',
			self::FAN_ART    => 'Фан-арты',
			self::PROMO      => 'Промо',
			self::CONCEPT    => 'Концепт-арты',
			self::WALLPAPER  => 'Обои',
			self::COVER      => 'Обложки',
			self::SCREENSHOT => 'Скриншоты',
			self::BACKGROUND => 'Фон',
			self::PREVIEW    => 'Превью',
		};
	}

	/**
	 * Проверяет, является ли тип изображения основным
	 *
	 * Основные типы изображений - это те, которые обычно
	 * используются для отображения в галереях и каталогах.
	 *
	 * @return bool true если основной тип, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ImageType::POSTER->isMain()) {
	 *     echo "Основной тип изображения";
	 * }
	 * ```
	 */
	public function isMain(): bool {
		return in_array($this, [
			self::POSTER,
			self::STILL,
			self::FAN_ART,
			self::CONCEPT
		]);
	}

	/**
	 * Проверяет, является ли тип изображения промо-материалом
	 *
	 * Промо-материалы используются для рекламы и продвижения фильма.
	 *
	 * @return bool true если промо-материал, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ImageType::PROMO->isPromo()) {
	 *     echo "Промо-материал";
	 * }
	 * ```
	 */
	public function isPromo(): bool {
		return in_array($this, [
			self::PROMO,
			self::POSTER,
			self::COVER,
			self::WALLPAPER
		]);
	}

}