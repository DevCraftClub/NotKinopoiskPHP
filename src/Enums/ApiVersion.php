<?php

namespace NotKinopoisk\Enums;

/**
 * Перечисление версий API Kinopoisk Unofficial
 *
 * Определяет доступные версии API для работы с Kinopoisk Unofficial.
 * Каждая версия API может иметь различные эндпоинты и возможности.
 *
 * Основные возможности:
 * - Определение поддерживаемых версий API
 * - Получение всех доступных версий в виде массива
 * - Статическое кэширование для повышения производительности
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\AbstractService
 *
 * @example
 * ```php
 * // Использование версии API
 * $version = ApiVersion::V22;
 * echo $version->value; // "v2.2"
 *
 * // Получение всех версий
 * $versions = ApiVersion::getApiVersions();
 * foreach ($versions as $version) {
 *     echo "Версия: {$version->value}\n";
 * }
 * ```
 */
enum ApiVersion: string {

	case V1  = 'v1.0';
	case V21 = 'v2.1';
	case V22 = 'v2.2';

	/**
	 * Получает массив всех доступных версий API
	 *
	 * Возвращает статически кэшированный массив всех версий API,
	 * определенных в данном перечислении. Использует ленивую инициализацию
	 * для оптимизации производительности при повторных вызовах.
	 *
	 * @return ApiVersion[] Массив всех доступных версий API
	 *
	 * @example
	 * ```php
	 * $versions = ApiVersion::getApiVersions();
	 *
	 * // Вывод всех версий
	 * foreach ($versions as $version) {
	 *     echo "API версия: {$version->value}\n";
	 * }
	 *
	 * // Получение количества версий
	 * $count = count(ApiVersion::getApiVersions());
	 * echo "Доступно версий: {$count}";
	 *
	 * // Проверка поддержки версии
	 * $supportedVersions = ApiVersion::getApiVersions();
	 * $isSupported = in_array(ApiVersion::V22, $supportedVersions, true);
	 * ```
	 */
	public static function getApiVersions(): array {
		static $values = NULL;

		if ($values === NULL) {
			$values = [
				self::V1,
				self::V21,
				self::V22,
			];
		}

		return $values;
	}

}
