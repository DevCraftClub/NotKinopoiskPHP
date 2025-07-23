<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель награды из Kinopoisk API
 *
 * Представляет информацию о награде или номинации фильма,
 * включая название награды, год, номинацию и связанных персон.
 *
 * Основные возможности:
 * - Хранение информации о награде в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к метаданным награды и связанным персонам
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
 * $award = Award::fromArray($apiData);
 *
 * // Использование
 * echo "Награда: {$award->name}\n";
 * echo "Номинация: {$award->nominationName}\n";
 * echo "Год: {$award->year}\n";
 * echo "Победитель: " . ($award->win ? 'Да' : 'Нет');
 * ```
 */
class Award {

	/**
	 * Конструктор модели награды
	 *
	 * Создает новый экземпляр награды со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string       $name            Название награды
	 * @param   bool         $win             Флаг победы в номинации
	 * @param   string|null  $imageUrl        URL изображения награды
	 * @param   string       $nominationName  Название номинации
	 * @param   int          $year            Год вручения награды
	 * @param   array        $persons         Массив связанных персон
	 *
	 * @example
	 * ```php
	 * $award = new Award(
	 *     name: 'Оскар',
	 *     win: true,
	 *     imageUrl: 'https://...',
	 *     nominationName: 'Лучший фильм',
	 *     year: 2023,
	 *     persons: [$person1, $person2]
	 * );
	 * ```
	 */
	public function __construct(
		public readonly string  $name,
		public readonly bool    $win,
		public readonly ?string $imageUrl,
		public readonly string  $nominationName,
		public readonly int     $year,
		public readonly array   $persons,
	) {}

	/**
	 * Создает экземпляр награды из массива данных API
	 *
	 * Статический метод для удобного создания объекта Award из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля.
	 *
	 * @param   array  $data  Массив данных награды от API
	 *
	 * @return self Новый экземпляр награды
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'name' => 'Оскар',
	 *     'win' => true,
	 *     'imageUrl' => 'https://...',
	 *     'nominationName' => 'Лучший фильм',
	 *     'year' => 2023,
	 *     'persons' => [$personData1, $personData2]
	 * ];
	 *
	 * $award = Award::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			name          : $data['name'],
			win           : $data['win'],
			imageUrl      : $data['imageUrl'] ?? null,
			nominationName: $data['nominationName'],
			year          : $data['year'],
			persons       : $data['persons'] ?? [],
		);
	}

}