<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Исключение для неверного или отсутствующего API ключа
 *
 * Выбрасывается при попытке обращения к API с неверным, отсутствующим или
 * заблокированным API ключом. Соответствует HTTP статус коду 401 (Unauthorized).
 *
 * @package NotKinopoisk\Exception
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Exception\ApiException
 *
 * @example
 * ```php
 * try {
 *     $client = new Client('invalid-key');
 *     $client->films->getById(301);
 * } catch (InvalidApiKeyException $e) {
 *     echo "Проблема с API ключом: " . $e->getMessage();
 *     // Выведет: "Проблема с API ключом: Неверный или отсутствующий API ключ"
 * }
 * ```
 */
class InvalidApiKeyException extends ApiException {

	/**
	 * Конструктор исключения неверного API ключа
	 *
	 * Создает исключение с предустановленным сообщением об ошибке и кодом 401.
	 *
	 * @param   string  $message  Сообщение об ошибке (по умолчанию стандартное)
	 * @param   int     $code     Код ошибки (по умолчанию 401 - Unauthorized)
	 *
	 * @example
	 * ```php
	 * throw new InvalidApiKeyException('API ключ заблокирован администратором');
	 * ```
	 */
	public function __construct(string $message = 'Неверный или отсутствующий API ключ', int $code = 401) {
		parent::__construct($message, $code);
	}

}