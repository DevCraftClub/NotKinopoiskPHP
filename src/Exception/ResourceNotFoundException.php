<?php

declare(strict_types=1);

namespace NotKinopoisk\Exception;

/**
 * Исключение для ненайденных ресурсов в API
 *
 * Выбрасывается при попытке обращения к несуществующему ресурсу (фильму,
 * персоне, сериалу и т.д.). Соответствует HTTP статус коду 404 (Not Found).
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
 *     $film = $client->films->getById(999999999);
 * } catch (ResourceNotFoundException $e) {
 *     echo "Фильм не найден: " . $e->getMessage();
 *     // Выведет: "Фильм не найден: Запрашиваемый ресурс не найден"
 * }
 * ```
 */
class ResourceNotFoundException extends ApiException {

	/**
	 * Конструктор исключения ненайденного ресурса
	 *
	 * Создает исключение с предустановленным сообщением об ошибке и кодом 404.
	 *
	 * @param   string  $message  Сообщение об ошибке (по умолчанию стандартное)
	 * @param   int     $code     Код ошибки (по умолчанию 404 - Not Found)
	 *
	 * @example
	 * ```php
	 * throw new ResourceNotFoundException('Фильм с ID 12345 не существует');
	 * ```
	 */
	public function __construct(string $message = 'Запрашиваемый ресурс не найден', int $code = 404) {
		parent::__construct($message, $code);
	}

}