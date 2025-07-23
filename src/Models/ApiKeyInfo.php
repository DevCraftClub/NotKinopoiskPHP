<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель информации об API ключе из Kinopoisk API
 *
 * Представляет детальную информацию об API ключе пользователя, включая
 * статистику использования, лимиты запросов и тип аккаунта.
 *
 * Основные возможности:
 * - Хранение информации об использовании API ключа
 * - Предоставление удобных методов для работы с квотами
 * - Информация о типе аккаунта и лимитах
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\UserService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $keyInfo = ApiKeyInfo::fromArray($apiData);
 *
 * // Проверка лимитов
 * echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}\n";
 * echo "Лимит запросов: {$keyInfo->getTotalQuotaValue()}\n";
 * echo "Осталось запросов: {$keyInfo->getRemainingQuota()}\n";
 * ```
 */
class ApiKeyInfo {

	/**
	 * Конструктор модели информации об API ключе
	 *
	 * Создает новый экземпляр с информацией об API ключе.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string  $accountType        Тип аккаунта (FREE, PAID и т.д.)
	 * @param   int     $totalQuotaUsed     Общее количество использованных запросов
	 * @param   int     $totalQuotaValue    Общий лимит запросов
	 * @param   int     $dailyQuotaUsed     Количество использованных запросов за день
	 * @param   int     $dailyQuotaValue    Дневной лимит запросов
	 * @param   int     $monthlyQuotaUsed   Количество использованных запросов за месяц
	 * @param   int     $monthlyQuotaValue  Месячный лимит запросов
	 *
	 * @example
	 * ```php
	 * $keyInfo = new ApiKeyInfo(
	 *     accountType: 'FREE',
	 *     totalQuotaUsed: 150,
	 *     totalQuotaValue: 1000,
	 *     dailyQuotaUsed: 10,
	 *     dailyQuotaValue: 100,
	 *     monthlyQuotaUsed: 150,
	 *     monthlyQuotaValue: 1000
	 * );
	 * ```
	 */
	public function __construct(
		public readonly string $accountType,
		public readonly int    $totalQuotaUsed,
		public readonly int    $totalQuotaValue,
		public readonly int    $dailyQuotaUsed,
		public readonly int    $dailyQuotaValue,
		public readonly int    $monthlyQuotaUsed,
		public readonly int    $monthlyQuotaValue,
	) {}

	/**
	 * Создает экземпляр информации об API ключе из массива данных API
	 *
	 * Статический метод для удобного создания объекта ApiKeyInfo из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных об API ключе от API
	 *
	 * @return self Новый экземпляр информации об API ключе
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'accountType' => 'FREE',
	 *     'totalQuotaUsed' => 150,
	 *     'totalQuotaValue' => 1000,
	 *     'dailyQuotaUsed' => 10,
	 *     'dailyQuotaValue' => 100,
	 *     'monthlyQuotaUsed' => 150,
	 *     'monthlyQuotaValue' => 1000
	 * ];
	 *
	 * $keyInfo = ApiKeyInfo::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			accountType      : $data['accountType'],
			totalQuotaUsed   : $data['totalQuotaUsed'],
			totalQuotaValue  : $data['totalQuotaValue'],
			dailyQuotaUsed   : $data['dailyQuotaUsed'],
			dailyQuotaValue  : $data['dailyQuotaValue'],
			monthlyQuotaUsed : $data['monthlyQuotaUsed'],
			monthlyQuotaValue: $data['monthlyQuotaValue'],
		);
	}

	/**
	 * Получает общее количество использованных запросов
	 *
	 * Возвращает количество запросов, которые были использованы
	 * с момента создания API ключа.
	 *
	 * @return int Количество использованных запросов
	 *
	 * @example
	 * ```php
	 * echo "Использовано запросов: {$keyInfo->getTotalQuotaUsed()}";
	 * ```
	 */
	public function getTotalQuotaUsed(): int {
		return $this->totalQuotaUsed;
	}

	/**
	 * Получает общий лимит запросов
	 *
	 * Возвращает максимальное количество запросов, доступных
	 * для данного типа аккаунта.
	 *
	 * @return int Общий лимит запросов
	 *
	 * @example
	 * ```php
	 * echo "Лимит запросов: {$keyInfo->getTotalQuotaValue()}";
	 * ```
	 */
	public function getTotalQuotaValue(): int {
		return $this->totalQuotaValue;
	}

	/**
	 * Получает количество оставшихся запросов
	 *
	 * Вычисляет разность между общим лимитом и использованными запросами.
	 *
	 * @return int Количество оставшихся запросов
	 *
	 * @example
	 * ```php
	 * $remaining = $keyInfo->getRemainingQuota();
	 * if ($remaining > 0) {
	 *     echo "Осталось запросов: {$remaining}";
	 * } else {
	 *     echo "Лимит запросов исчерпан";
	 * }
	 * ```
	 */
	public function getRemainingQuota(): int {
		return max(0, $this->totalQuotaValue - $this->totalQuotaUsed);
	}

}