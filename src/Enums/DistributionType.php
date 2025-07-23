<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы проката в Kinopoisk API
 *
 * Определяет различные типы проката фильмов:
 * кинотеатральный прокат, DVD, Blu-ray и другие форматы.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($distribution->type === DistributionType::CINEMA) {
 *     echo "Кинотеатральный прокат";
 * } elseif ($distribution->type === DistributionType::DVD) {
 *     echo "DVD прокат";
 * }
 * ```
 */
enum DistributionType: string {

	/** Кинотеатральный прокат */
	case CINEMA = 'CINEMA';

	/** DVD прокат */
	case DVD = 'DVD';

	/** Blu-ray прокат */
	case BLURAY = 'BLURAY';

	/** Цифровой прокат */
	case DIGITAL = 'DIGITAL';

	/** Телевизионный прокат */
	case TV = 'TV';

	/** Стриминговый прокат */
	case STREAMING = 'STREAMING';

	/**
	 * Проверяет, является ли прокат кинотеатральным
	 *
	 * @return bool true если это кинотеатральный прокат, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (DistributionType::CINEMA->isCinema()) {
	 *     echo "Кинотеатральный прокат";
	 * }
	 * ```
	 */
	public function isCinema(): bool {
		return $this === self::CINEMA;
	}

	/**
	 * Проверяет, является ли прокат домашним видео
	 *
	 * @return bool true если это домашнее видео, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (DistributionType::DVD->isHomeVideo()) {
	 *     echo "Домашнее видео";
	 * }
	 * ```
	 */
	public function isHomeVideo(): bool {
		return in_array($this, [self::DVD, self::BLURAY]);
	}

	/**
	 * Проверяет, является ли прокат цифровым
	 *
	 * @return bool true если это цифровой прокат, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (DistributionType::DIGITAL->isDigital()) {
	 *     echo "Цифровой прокат";
	 * }
	 * ```
	 */
	public function isDigital(): bool {
		return in_array($this, [self::DIGITAL, self::STREAMING]);
	}

	/**
	 * Получает человекочитаемое название типа проката
	 *
	 * @return string Название типа проката на русском языке
	 *
	 * @example
	 * ```php
	 * echo DistributionType::CINEMA->getDisplayName(); // "Кинотеатральный прокат"
	 * echo DistributionType::DVD->getDisplayName(); // "DVD прокат"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::CINEMA    => 'Кинотеатральный прокат',
			self::DVD       => 'DVD прокат',
			self::BLURAY    => 'Blu-ray прокат',
			self::DIGITAL   => 'Цифровой прокат',
			self::TV        => 'Телевизионный прокат',
			self::STREAMING => 'Стриминговый прокат',
		};
	}

}