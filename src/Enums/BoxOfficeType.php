<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы кассовых сборов в Kinopoisk API
 *
 * Определяет различные типы финансовых данных,
 * связанных с фильмом: бюджет, сборы по странам и т.д.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($boxOffice->type === BoxOfficeType::BUDGET) {
 *     echo "Бюджет фильма";
 * } elseif ($boxOffice->type === BoxOfficeType::RUS) {
 *     echo "Сборы в России";
 * }
 * ```
 */
enum BoxOfficeType: string {

	/** Бюджет фильма */
	case BUDGET = 'BUDGET';

	/** Сборы в России */
	case RUS = 'RUS';

	/** Сборы в США */
	case USA = 'USA';

	/** Мировые сборы */
	case WORLD = 'WORLD';

	/**
	 * Проверяет, является ли тип бюджетом
	 *
	 * @return bool true если это бюджет, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (BoxOfficeType::BUDGET->isBudget()) {
	 *     echo "Это бюджет фильма";
	 * }
	 * ```
	 */
	public function isBudget(): bool {
		return $this === self::BUDGET;
	}

	/**
	 * Проверяет, является ли тип сборами
	 *
	 * @return bool true если это сборы, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (BoxOfficeType::RUS->isRevenue()) {
	 *     echo "Это сборы";
	 * }
	 * ```
	 */
	public function isRevenue(): bool {
		return in_array($this, [self::RUS, self::USA, self::WORLD]);
	}

	/**
	 * Получает человекочитаемое название типа кассовых сборов
	 *
	 * @return string Название типа на русском языке
	 *
	 * @example
	 * ```php
	 * echo BoxOfficeType::BUDGET->getDisplayName(); // "Бюджет"
	 * echo BoxOfficeType::RUS->getDisplayName(); // "Сборы в России"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::BUDGET => 'Бюджет',
			self::RUS    => 'Сборы в России',
			self::USA    => 'Сборы в США',
			self::WORLD  => 'Мировые сборы',
		};
	}

}