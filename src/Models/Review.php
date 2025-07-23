<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\ReviewType;

/**
 * Модель рецензии из Kinopoisk API
 *
 * Представляет информацию о рецензии на фильм, включая
 * автора, дату, рейтинг и содержание рецензии.
 *
 * Основные возможности:
 * - Хранение информации о рецензии в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к рейтингу и метаданным рецензии
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}/reviews
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Enums\ReviewType
 * @see     \NotKinopoisk\Services\FilmService
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__reviews
 *
 * @example
 * ```php
 * // Создание из данных API
 * $review = Review::fromArray($apiData);
 *
 * // Использование
 * echo "Автор: {$review->author}\n";
 * echo "Дата: {$review->date}\n";
 * echo "Тип: {$review->type->getDisplayName()}\n";
 * echo "Рейтинг: {$review->positiveRating}/{$review->negativeRating}";
 * ```
 */
class Review {

	/**
	 * Конструктор модели рецензии
	 *
	 * Создает новый экземпляр рецензии со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int          $kinopoiskId     Уникальный идентификатор рецензии в Кинопоиске
	 * @param   ReviewType   $type            Тип рецензии
	 * @param   string       $date            Дата публикации рецензии
	 * @param   int          $positiveRating  Количество положительных оценок
	 * @param   int          $negativeRating  Количество отрицательных оценок
	 * @param   string       $author          Автор рецензии
	 * @param   string|null  $title           Заголовок рецензии
	 * @param   string       $description     Содержание рецензии
	 *
	 * @example
	 * ```php
	 * $review = new Review(
	 *     kinopoiskId: 12345,
	 *     type: ReviewType::POSITIVE,
	 *     date: '2023-01-15',
	 *     positiveRating: 85,
	 *     negativeRating: 15,
	 *     author: 'Кинокритик',
	 *     title: 'Отличный фильм',
	 *     description: 'Подробный анализ фильма...'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int        $kinopoiskId,
		public readonly ReviewType $type,
		public readonly string     $date,
		public readonly int        $positiveRating,
		public readonly int        $negativeRating,
		public readonly string     $author,
		public readonly ?string    $title,
		public readonly string     $description,
	) {}

	/**
	 * Создает экземпляр рецензии из массива данных API
	 *
	 * Статический метод для удобного создания объекта Review из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля.
	 *
	 * @param   array  $data  Массив данных рецензии от API
	 *
	 * @return self Новый экземпляр рецензии
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'kinopoiskId' => 12345,
	 *     'type' => 'POSITIVE',
	 *     'date' => '2023-01-15',
	 *     'positiveRating' => 85,
	 *     'negativeRating' => 15,
	 *     'author' => 'Кинокритик',
	 *     'title' => 'Отличный фильм',
	 *     'description' => 'Подробный анализ фильма...'
	 * ];
	 *
	 * $review = Review::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			kinopoiskId   : $data['kinopoiskId'],
			type          : ReviewType::from($data['type']),
			date          : $data['date'],
			positiveRating: $data['positiveRating'],
			negativeRating: $data['negativeRating'],
			author        : $data['author'],
			title         : $data['title'] ?? NULL,
			description   : $data['description'],
		);
	}

}