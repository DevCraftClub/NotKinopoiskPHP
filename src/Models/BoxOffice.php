<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\BoxOfficeType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель кассовых сборов из Kinopoisk API
 *
 * Представляет финансовую информацию о фильме: бюджет,
 * сборы в различных странах и мировые сборы.
 *
 * Основные возможности:
 * - Хранение финансовой информации в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Форматирование сумм для отображения
 * - Определение типа данных (бюджет или сборы)
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}/box_office
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Enums\BoxOfficeType
 * @see     \NotKinopoisk\Services\FilmService
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__box_office
 *
 * @example
 * ```php
 * // Создание из данных API
 * $boxOffice = BoxOffice::fromArray($apiData);
 *
 * // Работа с данными
 * echo "Тип: {$boxOffice->type->getDisplayName()}\n";
 * echo "Сумма: {$boxOffice->getFormattedAmount()}\n";
 *
 * if ($boxOffice->isBudget()) {
 *     echo "Это бюджет фильма";
 * } elseif ($boxOffice->isRevenue()) {
 *     echo "Это сборы фильма";
 * }
 * ```
 */
class BoxOffice implements ModelInterface {

	/**
	 * Конструктор модели кассовых сборов
	 *
	 * Создает новый экземпляр кассовых сборов со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   BoxOfficeType  $type      Тип данных (BUDGET, RUS, USA, WORLD)
	 * @param   int            $amount    Сумма в долларах США
	 * @param   string         $currency  Валюта (обычно USD)
	 * @param   string         $symbol    Символ валюты ($)
	 *
	 * @example
	 * ```php
	 * $boxOffice = new BoxOffice(
	 *     type: BoxOfficeType::BUDGET,
	 *     amount: 100000000,
	 *     currency: 'USD',
	 *     symbol: '$'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly BoxOfficeType $type,
		public readonly int           $amount,
		public readonly string        $currency,
		public readonly string        $symbol,
	) {}

	/**
	 * Создает экземпляр кассовых сборов из массива данных API
	 *
	 * Статический метод для удобного создания объекта BoxOffice из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных кассовых сборов от API
	 *
	 * @return self Новый экземпляр кассовых сборов
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'type' => 'BUDGET',
	 *     'amount' => 100000000,
	 *     'currency' => 'USD',
	 *     'symbol' => '$'
	 * ];
	 *
	 * $boxOffice = BoxOffice::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			type    : BoxOfficeType::from($data['type']),
			amount  : $data['amount'],
			currency: $data['currency'],
			symbol  : $data['symbol'],
		);
	}

	/**
	 * Получает отформатированную сумму для отображения
	 *
	 * Возвращает сумму в удобном для чтения формате с символом валюты
	 * и разделителями тысяч.
	 *
	 * @return string Отформатированная сумма
	 *
	 * @example
	 * ```php
	 * echo $boxOffice->getFormattedAmount(); // "$100,000,000"
	 * ```
	 */
	public function getFormattedAmount(): string {
		return $this->symbol . number_format($this->amount);
	}

	/**
	 * Проверяет, является ли тип бюджетом
	 *
	 * Определяет, относятся ли данные к бюджету фильма.
	 *
	 * @return bool true если это бюджет, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($boxOffice->isBudget()) {
	 *     echo "Бюджет фильма: {$boxOffice->getFormattedAmount()}";
	 * }
	 * ```
	 */
	public function isBudget(): bool {
		return $this->type->isBudget();
	}

	/**
	 * Проверяет, является ли тип сборами
	 *
	 * Определяет, относятся ли данные к сборам фильма
	 * (в России, США или мировым сборам).
	 *
	 * @return bool true если это сборы, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($boxOffice->isRevenue()) {
	 *     echo "Сборы: {$boxOffice->getFormattedAmount()}";
	 * }
	 * ```
	 */
	public function isRevenue(): bool {
		return $this->type->isRevenue();
	}

	/**
	 * Преобразует объект кассовых сборов в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными кассовых сборов
	 *
	 * @example
	 * ```php
	 * $boxOfficeArray = $boxOffice->toArray();
	 * echo json_encode($boxOfficeArray); // JSON представление кассовых сборов
	 * ```
	 */
	public function toArray(): array {
		return [
			'type'     => $this->type->value,
			'amount'   => $this->amount,
			'currency' => $this->currency,
			'symbol'   => $this->symbol,
		];
	}

}