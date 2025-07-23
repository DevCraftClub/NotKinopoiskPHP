<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Типы аккаунтов в Kinopoisk API
 *
 * Определяет различные типы аккаунтов API,
 * которые влияют на лимиты и возможности использования API.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @example
 * ```php
 * if ($apiKeyInfo->accountType === AccountType::FREE) {
 *     echo "Бесплатный аккаунт";
 * } elseif ($apiKeyInfo->accountType === AccountType::UNLIMITED) {
 *     echo "Безлимитный аккаунт";
 * }
 * ```
 */
enum AccountType: string {

	/** Бесплатный аккаунт */
	case FREE = 'FREE';

	/** Платный аккаунт */
	case PAID = 'PAID';

	/** Безлимитный аккаунт */
	case UNLIMITED = 'UNLIMITED';

	/**
	 * Проверяет, является ли аккаунт бесплатным
	 *
	 * @return bool true если аккаунт бесплатный, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (AccountType::FREE->isFree()) {
	 *     echo "Бесплатный аккаунт";
	 * }
	 * ```
	 */
	public function isFree(): bool {
		return $this === self::FREE;
	}

	/**
	 * Проверяет, является ли аккаунт безлимитным
	 *
	 * @return bool true если аккаунт безлимитный, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (AccountType::UNLIMITED->isUnlimited()) {
	 *     echo "Безлимитный аккаунт";
	 * }
	 * ```
	 */
	public function isUnlimited(): bool {
		return $this === self::UNLIMITED;
	}

	/**
	 * Получает человекочитаемое название типа аккаунта
	 *
	 * @return string Название типа аккаунта на русском языке
	 *
	 * @example
	 * ```php
	 * echo AccountType::FREE->getDisplayName(); // "Бесплатный"
	 * echo AccountType::PAID->getDisplayName(); // "Платный"
	 * ```
	 */
	public function getDisplayName(): string {
		return match ($this) {
			self::FREE      => 'Бесплатный',
			self::PAID      => 'Платный',
			self::UNLIMITED => 'Безлимитный',
		};
	}

}