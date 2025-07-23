<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель жанра из Kinopoisk API
 *
 * Представляет информацию о жанре фильма или сериала.
 * Простая модель для хранения названия жанра.
 *
 * Основные возможности:
 * - Хранение названия жанра в неизменяемом виде
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
 * $genre = Genre::fromArray(['genre' => 'Боевик']);
 *
 * // Использование
 * echo "Жанр: {$genre->genre}";
 * ```
 */
class Genre {

	/**
	 * Конструктор модели жанра
	 *
	 * Создает новый экземпляр жанра с указанным названием.
	 * Свойство является readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string  $genre  Название жанра
	 *
	 * @example
	 * ```php
	 * $genre = new Genre('Боевик');
	 * ```
	 */
	public function __construct(
		public readonly string $genre,
	) {}

	/**
	 * Создает экземпляр жанра из массива данных API
	 *
	 * Статический метод для удобного создания объекта Genre из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных жанра от API
	 *
	 * @return self Новый экземпляр жанра
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = ['genre' => 'Боевик'];
	 * $genre = Genre::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			genre: $data['genre'],
		);
	}

	/**
	 * Преобразует объект жанра в строку
	 *
	 * Магический метод, который автоматически вызывается при попытке
	 * преобразования объекта Genre в строковое представление.
	 * Возвращает название жанра.
	 *
	 * @return string Название жанра
	 *
	 * @example
	 * ```php
	 * $genre = new Genre('Боевик');
	 * echo $genre; // Выведет: Боевик
	 * echo (string) $genre; // Выведет: Боевик
	 *
	 * // Использование в строковом контексте
	 * $message = "Жанр фильма: {$genre}";
	 * echo $message; // Выведет: Жанр фильма: Боевик
	 *
	 * // Использование в массивах и сравнениях
	 * $genres = [$genre1, $genre2];
	 * $genreNames = array_map('strval', $genres);
	 * ```
	 */
	public function __toString(): string {
		return $this->genre;
	}

}