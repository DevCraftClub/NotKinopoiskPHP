<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\DistributionType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель данных о прокате фильма из Kinopoisk API
 *
 * Представляет информацию о прокате фильма в различных странах,
 * включая тип проката, дату, страну и компании-дистрибьюторы.
 *
 * Основные возможности:
 * - Хранение информации о прокате в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к метаданным проката и дистрибьюторам
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}/distributions
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @see     \NotKinopoisk\Models\Country
 * @see     \NotKinopoisk\Enums\DistributionType
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__distributions
 *
 * @example
 * ```php
 * // Создание из данных API
 * $distribution = Distribution::fromArray($apiData);
 *
 * // Использование
 * echo "Тип проката: {$distribution->type->getDisplayName()}\n";
 * echo "Дата: {$distribution->date}\n";
 * echo "Страна: {$distribution->country?->country}\n";
 * echo "Компаний: " . count($distribution->companies);
 * ```
 */
class Distribution implements ModelInterface {

	/**
	 * Конструктор модели проката
	 *
	 * Создает новый экземпляр проката со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   DistributionType  $type       Тип проката
	 * @param   string|null       $subType    Подтип проката
	 * @param   string|null       $date       Дата проката
	 * @param   bool|null         $reRelease  Флаг повторного проката
	 * @param   Country|null      $country    Страна проката
	 * @param   array             $companies  Массив компаний-дистрибьюторов
	 *
	 * @example
	 * ```php
	 * $distribution = new Distribution(
	 *     type: DistributionType::CINEMA,
	 *     subType: 'WIDE',
	 *     date: '2023-01-15',
	 *     reRelease: false,
	 *     country: $country,
	 *     companies: ['Компания 1', 'Компания 2']
	 * );
	 * ```
	 */
	public function __construct(
		public readonly DistributionType $type,
		public readonly ?string          $subType,
		public readonly ?string          $date,
		public readonly ?bool            $reRelease,
		public readonly ?Country         $country,
		public readonly array            $companies,
	) {}

	/**
	 * Создает экземпляр проката из массива данных API
	 *
	 * Статический метод для удобного создания объекта Distribution из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и создает объект Country при необходимости.
	 *
	 * @param   array  $data  Массив данных проката от API
	 *
	 * @return self Новый экземпляр проката
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'type' => 'CINEMA',
	 *     'subType' => 'WIDE',
	 *     'date' => '2023-01-15',
	 *     'reRelease' => false,
	 *     'country' => ['country' => 'США'],
	 *     'companies' => ['Компания 1', 'Компания 2']
	 * ];
	 *
	 * $distribution = Distribution::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			type     : DistributionType::from($data['type']),
			subType  : $data['subType'] ?? NULL,
			date     : $data['date'] ?? NULL,
			reRelease: $data['reRelease'] ?? NULL,
			country  : isset($data['country']) ? Country::fromArray($data['country']) : NULL,
			companies: $data['companies'] ?? [],
		);
	}

	/**
	 * Преобразует объект проката в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными проката
	 *
	 * @example
	 * ```php
	 * $distributionArray = $distribution->toArray();
	 * echo json_encode($distributionArray); // JSON представление проката
	 * ```
	 */
	public function toArray(): array {
		return [
			'type'      => $this->type->value,
			'subType'   => $this->subType,
			'date'      => $this->date,
			'reRelease' => $this->reRelease,
			'country'   => $this->country?->toArray(),
			'companies' => $this->companies,
		];
	}

}