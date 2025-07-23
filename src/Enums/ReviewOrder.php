<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Enum типов сортировки отзывов
 *
 * Представляет различные способы сортировки отзывов к фильмам
 * в API запросах Kinopoisk.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @api     /api/v2.2/films/{id}/reviews
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__reviews
 *
 * @example
 * ```php
 * // Получение отзывов, отсортированных по дате (новые сначала)
 * $reviews = $filmService->getReviews(301, 1, ReviewOrder::DATE_DESC);
 *
 * // Получение отзывов, отсортированных по положительным оценкам
 * $reviews = $filmService->getReviews(301, 1, ReviewOrder::USER_POSITIVE_RATING_DESC);
 * ```
 */
enum ReviewOrder: string
{
	case DATE_DESC = 'DATE_DESC';
	case DATE_ASC = 'DATE_ASC';
	case USER_POSITIVE_RATING_ASC = 'USER_POSITIVE_RATING_ASC';
	case USER_POSITIVE_RATING_DESC = 'USER_POSITIVE_RATING_DESC';
	case USER_NEGATIVE_RATING_ASC = 'USER_NEGATIVE_RATING_ASC';
	case USER_NEGATIVE_RATING_DESC = 'USER_NEGATIVE_RATING_DESC';

	/**
	 * Получает отображаемое название типа сортировки
	 *
	 * Возвращает человекочитаемое описание типа сортировки.
	 *
	 * @return string Отображаемое название типа сортировки
	 *
	 * @example
	 * ```php
	 * echo ReviewOrder::DATE_DESC->getDisplayName(); // "По дате (новые сначала)"
	 * echo ReviewOrder::USER_POSITIVE_RATING_DESC->getDisplayName(); // "По положительным оценкам (высокие сначала)"
	 * ```
	 */
	public function getDisplayName(): string
	{
		return match ($this) {
			self::DATE_DESC                    => 'По дате (новые сначала)',
			self::DATE_ASC                     => 'По дате (старые сначала)',
			self::USER_POSITIVE_RATING_ASC     => 'По положительным оценкам (низкие сначала)',
			self::USER_POSITIVE_RATING_DESC    => 'По положительным оценкам (высокие сначала)',
			self::USER_NEGATIVE_RATING_ASC     => 'По отрицательным оценкам (низкие сначала)',
			self::USER_NEGATIVE_RATING_DESC    => 'По отрицательным оценкам (высокие сначала)',
		};
	}

	/**
	 * Получает краткое название типа сортировки
	 *
	 * Возвращает краткое описание типа сортировки.
	 *
	 * @return string Краткое название типа сортировки
	 *
	 * @example
	 * ```php
	 * echo ReviewOrder::DATE_DESC->getShortName(); // "Дата ↓"
	 * echo ReviewOrder::USER_POSITIVE_RATING_DESC->getShortName(); // "Положительные ↓"
	 * ```
	 */
	public function getShortName(): string
	{
		return match ($this) {
			self::DATE_DESC                    => 'Дата ↓',
			self::DATE_ASC                     => 'Дата ↑',
			self::USER_POSITIVE_RATING_ASC     => 'Положительные ↑',
			self::USER_POSITIVE_RATING_DESC    => 'Положительные ↓',
			self::USER_NEGATIVE_RATING_ASC     => 'Отрицательные ↑',
			self::USER_NEGATIVE_RATING_DESC    => 'Отрицательные ↓',
		};
	}

	/**
	 * Проверяет, является ли сортировка по дате
	 *
	 * @return bool true если сортировка по дате, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewOrder::DATE_DESC->isDateSort()) {
	 *     echo "Сортировка по дате";
	 * }
	 * ```
	 */
	public function isDateSort(): bool
	{
		return in_array($this, [self::DATE_DESC, self::DATE_ASC]);
	}

	/**
	 * Проверяет, является ли сортировка по положительным оценкам
	 *
	 * @return bool true если сортировка по положительным оценкам, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewOrder::USER_POSITIVE_RATING_DESC->isPositiveRatingSort()) {
	 *     echo "Сортировка по положительным оценкам";
	 * }
	 * ```
	 */
	public function isPositiveRatingSort(): bool
	{
		return in_array($this, [self::USER_POSITIVE_RATING_ASC, self::USER_POSITIVE_RATING_DESC]);
	}

	/**
	 * Проверяет, является ли сортировка по отрицательным оценкам
	 *
	 * @return bool true если сортировка по отрицательным оценкам, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewOrder::USER_NEGATIVE_RATING_DESC->isNegativeRatingSort()) {
	 *     echo "Сортировка по отрицательным оценкам";
	 * }
	 * ```
	 */
	public function isNegativeRatingSort(): bool
	{
		return in_array($this, [self::USER_NEGATIVE_RATING_ASC, self::USER_NEGATIVE_RATING_DESC]);
	}

	/**
	 * Проверяет, является ли сортировка по возрастанию
	 *
	 * @return bool true если сортировка по возрастанию, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewOrder::DATE_ASC->isAscending()) {
	 *     echo "Сортировка по возрастанию";
	 * }
	 * ```
	 */
	public function isAscending(): bool
	{
		return in_array($this, [self::DATE_ASC, self::USER_POSITIVE_RATING_ASC, self::USER_NEGATIVE_RATING_ASC]);
	}

	/**
	 * Проверяет, является ли сортировка по убыванию
	 *
	 * @return bool true если сортировка по убыванию, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (ReviewOrder::DATE_DESC->isDescending()) {
	 *     echo "Сортировка по убыванию";
	 * }
	 * ```
	 */
	public function isDescending(): bool
	{
		return in_array($this, [self::DATE_DESC, self::USER_POSITIVE_RATING_DESC, self::USER_NEGATIVE_RATING_DESC]);
	}

	/**
	 * Получает направление сортировки
	 *
	 * @return string Направление сортировки ("asc" или "desc")
	 *
	 * @example
	 * ```php
	 * echo ReviewOrder::DATE_ASC->getDirection(); // "asc"
	 * echo ReviewOrder::DATE_DESC->getDirection(); // "desc"
	 * ```
	 */
	public function getDirection(): string
	{
		return $this->isAscending() ? 'asc' : 'desc';
	}

	/**
	 * Получает тип поля для сортировки
	 *
	 * @return string Тип поля для сортировки
	 *
	 * @example
	 * ```php
	 * echo ReviewOrder::DATE_DESC->getFieldType(); // "date"
	 * echo ReviewOrder::USER_POSITIVE_RATING_DESC->getFieldType(); // "positive_rating"
	 * ```
	 */
	public function getFieldType(): string
	{
		return match ($this) {
			self::DATE_DESC, self::DATE_ASC                     => 'date',
			self::USER_POSITIVE_RATING_ASC, self::USER_POSITIVE_RATING_DESC => 'positive_rating',
			self::USER_NEGATIVE_RATING_ASC, self::USER_NEGATIVE_RATING_DESC => 'negative_rating',
		};
	}

	/**
	 * Получает значение по умолчанию
	 *
	 * @return self Значение по умолчанию (DATE_DESC)
	 *
	 * @example
	 * ```php
	 * $defaultOrder = ReviewOrder::getDefault();
	 * echo $defaultOrder->value; // "DATE_DESC"
	 * ```
	 */
	public static function getDefault(): self
	{
		return self::DATE_DESC;
	}

	/**
	 * Получает все доступные типы сортировки
	 *
	 * @return self[] Массив всех доступных типов сортировки
	 *
	 * @example
	 * ```php
	 * $allOrders = ReviewOrder::getAll();
	 * foreach ($allOrders as $order) {
	 *     echo $order->getDisplayName() . "\n";
	 * }
	 * ```
	 */
	public static function getAll(): array
	{
		return [
			self::DATE_DESC,
			self::DATE_ASC,
			self::USER_POSITIVE_RATING_DESC,
			self::USER_POSITIVE_RATING_ASC,
			self::USER_NEGATIVE_RATING_DESC,
			self::USER_NEGATIVE_RATING_ASC,
		];
	}
} 