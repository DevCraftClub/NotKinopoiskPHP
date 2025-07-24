<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

use Exception;
use Throwable;

/**
 * Базовое исключение для всех ошибок, связанных с Kinopoisk API
 *
 * Это исключение является базовым классом для всех специфичных исключений API.
 * Используется для обработки общих ошибок, которые не подходят под более
 * специфичные категории исключений.
 *
 * @package NotKinopoisk\Exception
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Exception\InvalidApiKeyException
 * @see     \NotKinopoisk\Exception\RateLimitException
 * @see     \NotKinopoisk\Exception\ResourceNotFoundException
 *
 * @example
 * ```php
 * try {
 *     $client->films->getById(999999);
 * } catch (ApiException $e) {
 *     echo "Ошибка API: " . $e->getMessage();
 * }
 * ```
 */
class ApiException extends Exception {

	/**
	 * Конструктор исключения API
	 *
	 * Создает новое исключение с указанным сообщением, кодом ошибки и
	 * предыдущим исключением для цепочки исключений.
	 *
	 * @param   string           $message   Сообщение об ошибке, описывающее проблему с API
	 * @param   int              $code      Код ошибки (обычно HTTP статус код)
	 * @param   \Throwable|null  $previous  Предыдущее исключение в цепочке
	 *
	 * @example
	 * ```php
	 * throw new ApiException('Ошибка сети при обращении к API', 500, $previousException);
	 * ```
	 */
	public function __construct(string $message = '', int $code = 0, ?Throwable $previous = NULL) {
		parent::__construct($message, $code, $previous);
	}

}