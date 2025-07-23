<?php

namespace NotKinopoisk\Models;

/**
 * Модель квоты API ключа из Kinopoisk API
 *
 * Представляет информацию о лимитах использования API ключа,
 * включая общий лимит запросов и количество уже использованных запросов.
 *
 * Основные возможности:
 * - Хранение информации о квоте в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Вычисление оставшихся лимитов запросов
 * - Проверка превышения квоты
 * - Поддержка безлимитных аккаунтов (значение -1)
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\ApiKeyInfo
 * @see     \NotKinopoisk\Services\UserService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $quota = ApiKeyQouta::fromArray($apiData);
 *
 * // Работа с квотой
 * echo "Общий лимит: {$quota->value}\n";
 * echo "Использовано: {$quota->used}\n";
 * echo "Осталось: {$quota->getRemainingQuota()}\n";
 *
 * if ($quota->isQuotaExceeded()) {
 *     echo "Квота превышена!";
 * }
 *
 * // Для безлимитного аккаунта
 * $unlimitedQuota = new ApiKeyQouta(-1, 150);
 * echo $unlimitedQuota->isQuotaExceeded(); // false
 * ```
 */
class ApiKeyQouta {

	/**
	 * Конструктор модели квоты API ключа
	 *
	 * Создает новый экземпляр квоты со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int  $value  Общий лимит запросов (-1 для безлимитного аккаунта)
	 * @param   int  $used   Количество уже использованных запросов
	 *
	 * @example
	 * ```php
	 * // Обычная квота
	 * $quota = new ApiKeyQouta(1000, 250);
	 *
	 * // Безлимитная квота
	 * $unlimitedQuota = new ApiKeyQouta(-1, 1500);
	 * ```
	 */
	public function __construct(
		public readonly int $value,
		public readonly int $used,
	) {}

	/**
	 * Создает экземпляр квоты из массива данных API
	 *
	 * Статический метод для удобного создания объекта ApiKeyQouta из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных квоты от API, должен содержать ключи 'value' и 'used'
	 *
	 * @return self Новый экземпляр квоты API ключа
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат или отсутствуют обязательные поля
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'value' => 1000,
	 *     'used' => 250
	 * ];
	 *
	 * $quota = ApiKeyQouta::fromArray($apiData);
	 *
	 * // Для безлимитного аккаунта
	 * $unlimitedData = [
	 *     'value' => -1,
	 *     'used' => 1500
	 * ];
	 *
	 * $unlimitedQuota = ApiKeyQouta::fromArray($unlimitedData);
	 * ```
	 */
	public static function fromArray(array $data): ApiKeyQouta {
		return new self(
			value: $data['value'],
			used : $data['used'],
		);
	}

	/**
	 * Проверяет, превышена ли квота запросов
	 *
	 * Определяет, исчерпан ли лимит запросов для данного API ключа.
	 * Безлимитные аккаунты (value = -1) никогда не превышают квоту.
	 *
	 * @return bool true если квота превышена, false в противном случае
	 *
	 * @example
	 * ```php
	 * // Обычная квота в пределах лимита
	 * $quota = new ApiKeyQouta(1000, 250);
	 * echo $quota->isQuotaExceeded(); // false
	 *
	 * // Превышенная квота
	 * $exceededQuota = new ApiKeyQouta(100, 150);
	 * echo $exceededQuota->isQuotaExceeded(); // true
	 *
	 * // Исчерпанная квота
	 * $emptyQuota = new ApiKeyQouta(100, 100);
	 * echo $emptyQuota->isQuotaExceeded(); // true
	 *
	 * // Безлимитный аккаунт
	 * $unlimitedQuota = new ApiKeyQouta(-1, 99999);
	 * echo $unlimitedQuota->isQuotaExceeded(); // false
	 * ```
	 */
	public function isQuotaExceeded(): bool {
		if ($this->value === -1) {
			return FALSE;
		}

		return $this->getRemainingQuota() <= 0;
	}

	/**
	 * Получает количество оставшихся запросов в квоте
	 *
	 * Вычисляет разность между общим лимитом и уже использованными запросами.
	 * Для безлимитных аккаунтов (value = -1) всегда возвращает положительное значение.
	 *
	 * @return int Количество оставшихся запросов. Может быть отрицательным при превышении квоты
	 *
	 * @example
	 * ```php
	 * $quota = new ApiKeyQouta(1000, 250);
	 * echo $quota->getRemainingQuota(); // 750
	 *
	 * $exceededQuota = new ApiKeyQouta(100, 150);
	 * echo $exceededQuota->getRemainingQuota(); // -50
	 *
	 * $unlimitedQuota = new ApiKeyQouta(-1, 1500);
	 * echo $unlimitedQuota->getRemainingQuota(); // -1501 (но квота не превышена)
	 * ```
	 */
	public function getRemainingQuota(): int {
		return $this->value - $this->used;
	}

}