<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы рецензий в Kinopoisk API
 *
 * Определяет различные типы рецензий, которые могут быть
 * оставлены пользователями: положительные, отрицательные, нейтральные.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($review->type === ReviewType::POSITIVE) {
 *     echo "Положительная рецензия";
 * } elseif ($review->type === ReviewType::NEGATIVE) {
 *     echo "Отрицательная рецензия";
 * }
 * ```
 */
enum ReviewType: string {

	/** Положительная рецензия */
	case POSITIVE = 'POSITIVE';

	/** Отрицательная рецензия */
	case NEGATIVE = 'NEGATIVE';

	/** Нейтральная рецензия */
	case NEUTRAL = 'NEUTRAL';

	/**
	 * Проверяет, является ли рецензия положительной
	 *
	 * @return bool true если рецензия положительная, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewType::POSITIVE->isPositive()) {
	 *     echo "Положительная рецензия";
	 * }
	 * ```
	 */
	public function isPositive(): bool {
		return $this === self::POSITIVE;
	}

	/**
	 * Проверяет, является ли рецензия отрицательной
	 *
	 * @return bool true если рецензия отрицательная, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewType::NEGATIVE->isNegative()) {
	 *     echo "Отрицательная рецензия";
	 * }
	 * ```
	 */
	public function isNegative(): bool {
		return $this === self::NEGATIVE;
	}

	/**
	 * Получает человекочитаемое название типа рецензии
	 *
	 * @return string Название типа рецензии на русском языке
	 *
	 * @example
	 * ```php
	 * echo ReviewType::POSITIVE->getDisplayName(); // "Положительная"
	 * echo ReviewType::NEGATIVE->getDisplayName(); // "Отрицательная"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::POSITIVE => 'Положительная',
			self::NEGATIVE => 'Отрицательная',
			self::NEUTRAL  => 'Нейтральная',
		};
	}

}