<?php

namespace NotKinopoisk\Interfaces;

/**
 * Интерфейс для объектов ответов Kinopoisk API
 *
 * Определяет общий контракт для всех типов ответов API, включая методы
 * для создания объектов из массивов данных и валидации целевых классов.
 * Обеспечивает единообразие в обработке различных типов ответов.
 *
 * Основные возможности:
 * - Создание экземпляров ответов из данных API
 * - Валидация классов для преобразования элементов
 * - Унификация работы с различными типами ответов
 *
 * @package NotKinopoisk\Responses
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 */
interface ResponseInterface {

	/**
	 * Создает экземпляр ответа из массива данных API
	 *
	 * Статический метод-фабрика для создания объекта ответа из данных,
	 * полученных от Kinopoisk API. Должен выполнять валидацию целевого класса
	 * и автоматически преобразовывать элементы в указанный тип.
	 *
	 * @param   array   $data  Массив данных от API, содержащий структуру ответа
	 * @param   string  $cls   Полное имя класса для преобразования элементов массива
	 *
	 * @return self Новый экземпляр класса-реализации с преобразованными данными
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если указанный класс не существует
	 * @throws \NotKinopoisk\Exception\KpValidationException Если класс не имеет метода fromArray
	 * @throws \NotKinopoisk\Exception\KpValidationException Если данные имеют неверный формат
	 *
	 */
	public static function fromArray(array $data, string $cls): object;

	/**
	 * Проверяет существование и совместимость класса
	 *
	 * Выполняет валидацию указанного класса для использования в преобразовании
	 * элементов ответа. Проверяет существование класса и наличие необходимых
	 * методов для корректной работы с API.
	 *
	 * @param   string  $cls  Полное имя класса для проверки
	 *
	 * @return void
	 *
	 * @throws \NotKinopoisk\Exception\KpValidationException Если указанный класс не существует
	 * @throws \NotKinopoisk\Exception\KpValidationException Если класс не имеет статического метода fromArray
	 */
	public static function checkClass(string $cls): void;
	/**
	 * Преобразует объект в массив данных
	 *
	 * Метод возвращает массив, представляющий структуру объекта ответа,
	 * который может быть использован для сериализации или передачи данных.
	 * Полезен для отладки, логирования или создания JSON-представления ответа.
	 *
	 * @return array Массив данных, представляющий объект ответа
	 *
	 * @example
	 * ```php
	 * $response = SomeResponse::fromArray($apiData);
	 * $array = $response->toArray();
	 *
	 * // Использование для сериализации
	 * $json = json_encode($array);
	 *
	 * // Использование для логирования
	 * $logger->info('Ответ API', $array);
	 * ```
	 */
	public function toArray(): array;

}