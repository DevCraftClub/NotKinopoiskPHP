<?php

namespace NotKinopoisk\Exception;

/**
 * Исключение для ошибок валидации данных Kinopoisk API
 *
 * Выбрасывается при обнаружении некорректных или недостающих данных
 * в параметрах запросов к API или при валидации ответов от сервера.
 * Используется для обработки ошибок валидации входных данных.
 *
 * @package NotKinopoisk\Exception
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Exception\ApiException
 * @see     \NotKinopoisk\Exception\InvalidApiKeyException
 * @see     \NotKinopoisk\Exception\ResourceNotFoundException
 *
 * @example
 * ```php
 * try {
 *     // Некорректный ID фильма
 *     $client->films->getById(-1);
 * } catch (KpValidationException $e) {
 *     echo "Ошибка валидации: " . $e->getMessage();
 *     // Выведет: "Ошибка валидации: ID фильма должен быть положительным числом"
 * }
 * ```
 */
class KpValidationException extends \Exception {

	/**
	 * Конструктор исключения валидации
	 *
	 * Создает новое исключение валидации с указанным сообщением об ошибке,
	 * кодом ошибки и предыдущим исключением для цепочки исключений.
	 * Наследует базовое поведение от стандартного класса Exception.
	 *
	 * @param   string           $message   Сообщение об ошибке валидации
	 * @param   int              $code      Код ошибки (обычно 0 для ошибок валидации)
	 * @param   \Throwable|null  $previous  Предыдущее исключение в цепочке
	 *
	 * @example
	 * ```php
	 * throw new KpValidationException('Неверный формат даты: ожидается YYYY-MM-DD');
	 * throw new KpValidationException('ID фильма не может быть отрицательным', 0, $previousException);
	 * ```
	 */
	public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = NULL) {
		parent::__construct($message, $code, $previous);
	}

}