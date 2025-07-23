<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Исключение для превышения лимитов запросов к API
 *
 * Выбрасывается при превышении дневного, месячного лимита запросов или
 * лимита запросов в секунду. Соответствует HTTP статус кодам 402 (Payment Required)
 * и 429 (Too Many Requests).
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
 *     // Множественные запросы в цикле
 *     for ($i = 0; $i < 1000; $i++) {
 *         $client->films->getById($i);
 *     }
 * } catch (RateLimitException $e) {
 *     echo "Превышен лимит запросов: " . $e->getMessage();
 *     // Рекомендуется добавить задержку перед повторными запросами
 * }
 * ```
 */
class RateLimitException extends ApiException {

	/**
	 * Конструктор исключения превышения лимита запросов
	 *
	 * Создает исключение с предустановленным сообщением об ошибке и кодом 429.
	 *
	 * @param   string  $message  Сообщение об ошибке (по умолчанию стандартное)
	 * @param   int     $code     Код ошибки (по умолчанию 429 - Too Many Requests)
	 *
	 * @example
	 * ```php
	 * throw new RateLimitException('Превышен дневной лимит запросов', 402);
	 * ```
	 */
	public function __construct(string $message = 'Превышен лимит запросов', int $code = 429) {
		parent::__construct($message, $code);
	}

}