<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы фактов в Kinopoisk API
 *
 * Определяет различные типы фактов, которые могут быть
 * связаны с фильмом: интересные факты, ошибки (блуперы) и т.д.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($fact->type === FactType::FACT) {
 *     echo "Интересный факт";
 * } elseif ($fact->type === FactType::BLOOPER) {
 *     echo "Ошибка в фильме";
 * }
 * ```
 */
enum FactType: string {

	/** Интересный факт */
	case FACT = 'FACT';

	/** Ошибка в фильме (блупер) */
	case BLOOPER = 'BLOOPER';

	/**
	 * Проверяет, является ли факт ошибкой в фильме
	 *
	 * @return bool true если это ошибка в фильме, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (FactType::BLOOPER->isBlooper()) {
	 *     echo "Это ошибка в фильме";
	 * }
	 * ```
	 */
	public function isBlooper(): bool {
		return $this === self::BLOOPER;
	}

	/**
	 * Проверяет, является ли факт интересным фактом
	 *
	 * @return bool true если это интересный факт, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (FactType::FACT->isFact()) {
	 *     echo "Это интересный факт";
	 * }
	 * ```
	 */
	public function isFact(): bool {
		return $this === self::FACT;
	}

	/**
	 * Получает человекочитаемое название типа факта
	 *
	 * @return string Название типа факта на русском языке
	 *
	 * @example
	 * ```php
	 * echo FactType::FACT->getDisplayName(); // "Интересный факт"
	 * echo FactType::BLOOPER->getDisplayName(); // "Ошибка в фильме"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::FACT    => 'Интересный факт',
			self::BLOOPER => 'Ошибка в фильме',
		};
	}

}