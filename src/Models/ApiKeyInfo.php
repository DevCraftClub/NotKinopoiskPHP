<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\AccountType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель информации об API ключе из Kinopoisk API
 *
 * Представляет информацию о текущем API ключе, включая
 * лимиты запросов, тип аккаунта и доступные возможности.
 *
 * Основные возможности:
 * - Хранение информации об API ключе в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Проверка лимитов и типа аккаунта
 * - Получение информации о доступных запросах
 *
 * @package NotKinopoisk\Models
 * @api     /api/v1/api_keys/{apiKey}
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Enums\AccountType
 * @see     \NotKinopoisk\Services\UserService
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/api_keys/get_api_v1_api_keys__apiKey_
 *
 * @example
 * ```php
 * // Создание из данных API
 * $apiKeyInfo = ApiKeyInfo::fromArray($apiData);
 *
 * // Работа с информацией
 * echo "Тип аккаунта: {$apiKeyInfo->accountType->getDisplayName()}\n";
 * echo "Всего запросов: {$apiKeyInfo->totalQuota['total']}\n";
 * echo "Использовано: {$apiKeyInfo->totalQuota['used']}\n";
 *
 * if ($apiKeyInfo->accountType->isUnlimited()) {
 *     echo "Безлимитный аккаунт!";
 * }
 * ```
 */
class ApiKeyInfo implements ModelInterface{

	/**
	 * Конструктор модели информации об API ключе
	 *
	 * Создает новый экземпляр информации об API ключе со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   \NotKinopoisk\Models\ApiKeyQouta  $totalQuota   Информация об общих лимитах запросов
	 * @param   \NotKinopoisk\Models\ApiKeyQouta  $dailyQuota   Информация о дневных лимитах запросов
	 * @param   AccountType                       $accountType  Тип аккаунта (FREE, PAID, UNLIMITED)
	 *
	 * @example
	 * ```php
	 * $apiKeyInfo = new ApiKeyInfo(
	 *     totalQuota: ['total' => 1000, 'used' => 150],
	 *     dailyQuota: ['total' => 100, 'used' => 25],
	 *     accountType: AccountType::FREE
	 * );
	 * ```
	 */
	public function __construct(
		public readonly ApiKeyQouta $totalQuota,
		public readonly ApiKeyQouta $dailyQuota,
		public readonly AccountType $accountType,
	) {}

	/**
	 * Создает экземпляр информации об API ключе из массива данных API
	 *
	 * Статический метод для удобного создания объекта ApiKeyInfo из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных информации об API ключе от API
	 *
	 * @return self Новый экземпляр информации об API ключе
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'totalQuota' => ['total' => 1000, 'used' => 150],
	 *     'dailyQuota' => ['total' => 100, 'used' => 25],
	 *     'accountType' => 'FREE'
	 * ];
	 *
	 * $apiKeyInfo = ApiKeyInfo::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			totalQuota : ApiKeyQouta::fromArray($data['totalQuota']),
			dailyQuota : ApiKeyQouta::fromArray($data['dailyQuota']),
			accountType: AccountType::from($data['accountType']),
		);
	}

	/**
	 * Проверяет, является ли аккаунт безлимитным
	 *
	 * Определяет, имеет ли аккаунт безлимитные возможности
	 * без ограничений на количество запросов.
	 *
	 * @return bool true если аккаунт безлимитный, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($apiKeyInfo->isUnlimited()) {
	 *     echo "Безлимитный аккаунт - можно делать неограниченное количество запросов";
	 * }
	 * ```
	 */
	public function isUnlimited(): bool {
		return $this->accountType->isUnlimited();
	}

	/**
	 * Получает количество оставшихся общих запросов
	 *
	 * Вычисляет количество запросов, которые еще можно сделать
	 * в рамках общего лимита аккаунта.
	 *
	 * @return int Количество оставшихся запросов
	 *
	 * @example
	 * ```php
	 * $remaining = $apiKeyInfo->getRemainingTotalQuota();
	 * echo "Осталось запросов: {$remaining}";
	 * ```
	 */
	public function getRemainingTotalQuota(): int {
		return $this->totalQuota->getRemainingQuota();
	}

	/**
	 * Получает количество оставшихся дневных запросов
	 *
	 * Вычисляет количество запросов, которые еще можно сделать
	 * в рамках дневного лимита аккаунта.
	 *
	 * @return int Количество оставшихся дневных запросов
	 *
	 * @example
	 * ```php
	 * $remaining = $apiKeyInfo->getRemainingDailyQuota();
	 * echo "Осталось дневных запросов: {$remaining}";
	 * ```
	 */
	public function getRemainingDailyQuota(): int {
		return $this->dailyQuota->getRemainingQuota();
	}

	/**
	 * Преобразует объект информации об API ключе в массив
	 *
	 * Возвращает массив со всеми свойствами информации об API ключе, включая
	 * преобразованные в массивы объекты квот.
	 *
	 * @return array Массив данных информации об API ключе
	 *
	 * @example
	 * ```php
	 * $array = $apiKeyInfo->toArray();
	 * // [
	 * //     'totalQuota' => ['total' => 1000, 'used' => 150],
	 * //     'dailyQuota' => ['total' => 100, 'used' => 25],
	 * //     'accountType' => 'FREE'
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return [
			'totalQuota'  => $this->totalQuota->toArray(),
			'dailyQuota'  => $this->dailyQuota->toArray(),
			'accountType' => $this->accountType->value,
		];
	}

}