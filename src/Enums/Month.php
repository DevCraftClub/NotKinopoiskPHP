<?php

declare(strict_types=1);

namespace NotKinopoisk\Enums;

/**
 * Enum месяцев года для API запросов
 *
 * Представляет месяцы года, используемые в различных API запросах
 * Kinopoisk API, например для получения премьер фильмов.
 *
 * @package NotKinopoisk\Enums
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @api     /api/v2.2/films/premieres
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films_premieres
 *
 * @example
 * ```php
 * // Получение премьер за январь 2024
 * $premieres = $filmService->getPremieres(2024, Month::JANUARY);
 *
 * // Проверка месяца
 * if ($month === Month::DECEMBER) {
 *     echo "Новогодние премьеры";
 * }
 * ```
 */
enum Month: string
{
	case JANUARY = 'JANUARY';
	case FEBRUARY = 'FEBRUARY';
	case MARCH = 'MARCH';
	case APRIL = 'APRIL';
	case MAY = 'MAY';
	case JUNE = 'JUNE';
	case JULY = 'JULY';
	case AUGUST = 'AUGUST';
	case SEPTEMBER = 'SEPTEMBER';
	case OCTOBER = 'OCTOBER';
	case NOVEMBER = 'NOVEMBER';
	case DECEMBER = 'DECEMBER';

	/**
	 * Получает отображаемое название месяца на русском языке
	 *
	 * Возвращает человекочитаемое название месяца для отображения пользователю.
	 *
	 * @return string Отображаемое название месяца
	 *
	 * @example
	 * ```php
	 * echo Month::JANUARY->getDisplayName(); // "Январь"
	 * echo Month::DECEMBER->getDisplayName(); // "Декабрь"
	 * ```
	 */
	public function getDisplayName(): string
	{
		return match ($this) {
			self::JANUARY   => 'Январь',
			self::FEBRUARY  => 'Февраль',
			self::MARCH     => 'Март',
			self::APRIL     => 'Апрель',
			self::MAY       => 'Май',
			self::JUNE      => 'Июнь',
			self::JULY      => 'Июль',
			self::AUGUST    => 'Август',
			self::SEPTEMBER => 'Сентябрь',
			self::OCTOBER   => 'Октябрь',
			self::NOVEMBER  => 'Ноябрь',
			self::DECEMBER  => 'Декабрь',
		};
	}

	/**
	 * Получает краткое название месяца (3 буквы)
	 *
	 * Возвращает краткое название месяца из трех букв.
	 *
	 * @return string Краткое название месяца
	 *
	 * @example
	 * ```php
	 * echo Month::JANUARY->getShortName(); // "Янв"
	 * echo Month::DECEMBER->getShortName(); // "Дек"
	 * ```
	 */
	public function getShortName(): string
	{
		return match ($this) {
			self::JANUARY   => 'Янв',
			self::FEBRUARY  => 'Фев',
			self::MARCH     => 'Мар',
			self::APRIL     => 'Апр',
			self::MAY       => 'Май',
			self::JUNE      => 'Июн',
			self::JULY      => 'Июл',
			self::AUGUST    => 'Авг',
			self::SEPTEMBER => 'Сен',
			self::OCTOBER   => 'Окт',
			self::NOVEMBER  => 'Ноя',
			self::DECEMBER  => 'Дек',
		};
	}

	/**
	 * Получает номер месяца (1-12)
	 *
	 * Возвращает порядковый номер месяца в году.
	 *
	 * @return int Номер месяца (1-12)
	 *
	 * @example
	 * ```php
	 * echo Month::JANUARY->getNumber(); // 1
	 * echo Month::DECEMBER->getNumber(); // 12
	 * ```
	 */
	public function getNumber(): int
	{
		return match ($this) {
			self::JANUARY   => 1,
			self::FEBRUARY  => 2,
			self::MARCH     => 3,
			self::APRIL     => 4,
			self::MAY       => 5,
			self::JUNE      => 6,
			self::JULY      => 7,
			self::AUGUST    => 8,
			self::SEPTEMBER => 9,
			self::OCTOBER   => 10,
			self::NOVEMBER  => 11,
			self::DECEMBER  => 12,
		};
	}

	/**
	 * Проверяет, является ли месяц зимним
	 *
	 * К зимним месяцам относятся декабрь, январь, февраль.
	 *
	 * @return bool true если зимний месяц, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::DECEMBER->isWinter()) {
	 *     echo "Зимний месяц";
	 * }
	 * ```
	 */
	public function isWinter(): bool
	{
		return in_array($this, [self::DECEMBER, self::JANUARY, self::FEBRUARY]);
	}

	/**
	 * Проверяет, является ли месяц весенним
	 *
	 * К весенним месяцам относятся март, апрель, май.
	 *
	 * @return bool true если весенний месяц, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::MARCH->isSpring()) {
	 *     echo "Весенний месяц";
	 * }
	 * ```
	 */
	public function isSpring(): bool
	{
		return in_array($this, [self::MARCH, self::APRIL, self::MAY]);
	}

	/**
	 * Проверяет, является ли месяц летним
	 *
	 * К летним месяцам относятся июнь, июль, август.
	 *
	 * @return bool true если летний месяц, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::JULY->isSummer()) {
	 *     echo "Летний месяц";
	 * }
	 * ```
	 */
	public function isSummer(): bool
	{
		return in_array($this, [self::JUNE, self::JULY, self::AUGUST]);
	}

	/**
	 * Проверяет, является ли месяц осенним
	 *
	 * К осенним месяцам относятся сентябрь, октябрь, ноябрь.
	 *
	 * @return bool true если осенний месяц, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::SEPTEMBER->isAutumn()) {
	 *     echo "Осенний месяц";
	 * }
	 * ```
	 */
	public function isAutumn(): bool
	{
		return in_array($this, [self::SEPTEMBER, self::OCTOBER, self::NOVEMBER]);
	}

	/**
	 * Получает сезон месяца
	 *
	 * Возвращает название сезона, к которому относится месяц.
	 *
	 * @return string Название сезона
	 *
	 * @example
	 * ```php
	 * echo Month::JANUARY->getSeason(); // "Зима"
	 * echo Month::JULY->getSeason(); // "Лето"
	 * ```
	 */
	public function getSeason(): string
	{
		if ($this->isWinter()) {
			return 'Зима';
		}
		if ($this->isSpring()) {
			return 'Весна';
		}
		if ($this->isSummer()) {
			return 'Лето';
		}
		if ($this->isAutumn()) {
			return 'Осень';
		}
		return 'Неизвестно';
	}

	/**
	 * Проверяет, является ли месяц первым в квартале
	 *
	 * @return bool true если первый месяц квартала, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::JANUARY->isQuarterStart()) {
	 *     echo "Начало квартала";
	 * }
	 * ```
	 */
	public function isQuarterStart(): bool
	{
		return in_array($this, [self::JANUARY, self::APRIL, self::JULY, self::OCTOBER]);
	}

	/**
	 * Получает номер квартала (1-4)
	 *
	 * @return int Номер квартала (1-4)
	 *
	 * @example
	 * ```php
	 * echo Month::JANUARY->getQuarter(); // 1
	 * echo Month::OCTOBER->getQuarter(); // 4
	 * ```
	 */
	public function getQuarter(): int
	{
		return match ($this) {
			self::JANUARY, self::FEBRUARY, self::MARCH     => 1,
			self::APRIL, self::MAY, self::JUNE             => 2,
			self::JULY, self::AUGUST, self::SEPTEMBER      => 3,
			self::OCTOBER, self::NOVEMBER, self::DECEMBER  => 4,
		};
	}

	/**
	 * Проверяет, является ли месяц последним в году
	 *
	 * @return bool true если декабрь, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::DECEMBER->isYearEnd()) {
	 *     echo "Конец года";
	 * }
	 * ```
	 */
	public function isYearEnd(): bool
	{
		return $this === self::DECEMBER;
	}

	/**
	 * Проверяет, является ли месяц первым в году
	 *
	 * @return bool true если январь, false в противном случае
	 *
	 * @example
	 * ```php
	 * if (Month::JANUARY->isYearStart()) {
	 *     echo "Начало года";
	 * }
	 * ```
	 */
	public function isYearStart(): bool
	{
		return $this === self::JANUARY;
	}
} 