<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель страны из Kinopoisk API
 *
 * Представляет информацию о стране производства фильма или сериала.
 * Простая модель для хранения названия страны.
 *
 * Основные возможности:
 * - Хранение названия страны в неизменяемом виде
 * - Создание объекта из массива данных API
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $country = Country::fromArray(['country' => 'США']);
 *
 * // Использование
 * echo "Страна: {$country->country}";
 * ```
 */
class Country {

	/**
	 * Конструктор модели страны
	 *
	 * Создает новый экземпляр страны с указанным названием.
	 * Свойство является readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string  $country  Название страны
	 *
	 * @example
	 * ```php
	 * $country = new Country('США');
	 * ```
	 */
	public function __construct(
		public readonly string $country,
	) {}

	/**
	 * Создает экземпляр страны из массива данных API
	 *
	 * Статический метод для удобного создания объекта Country из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных страны от API
	 *
	 * @return self Новый экземпляр страны
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = ['country' => 'США'];
	 * $country = Country::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			country: $data['country'],
		);
	}

	/**
	 * Преобразует объект страны в строку
	 *
	 * Магический метод, который автоматически вызывается при попытке
	 * преобразования объекта Country в строковое представление.
	 * Возвращает название страны.
	 *
	 * @return string Название страны
	 *
	 * @example
	 * ```php
	 * $country = new Country('США');
	 * echo $country; // Выведет: США
	 * echo (string) $country; // Выведет: США
	 *
	 * // Использование в строковом контексте
	 * $message = "Фильм снят в стране: {$country}";
	 * ```
	 */
	public function __toString(): string {
		return $this->country;
	}

}