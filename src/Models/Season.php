<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель сезона сериала из Kinopoisk API
 *
 * Представляет информацию о сезоне сериала, включая номер сезона
 * и массив эпизодов, входящих в этот сезон.
 *
 * Основные возможности:
 * - Хранение информации о сезоне в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Доступ к эпизодам сезона
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Episode
 * @see     \NotKinopoisk\Services\FilmService
 *
 * @example
 * ```php
 * // Создание из данных API
 * $season = Season::fromArray($apiData);
 *
 * // Работа с сезоном
 * echo "Сезон {$season->number}\n";
 * echo "Количество эпизодов: " . count($season->episodes) . "\n";
 *
 * foreach ($season->episodes as $episode) {
 *     echo "- Эпизод {$episode->number}: {$episode->name}\n";
 * }
 * ```
 */
class Season {

	/**
	 * Конструктор модели сезона
	 *
	 * Создает новый экземпляр сезона с указанными данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int    $number    Номер сезона
	 * @param   array  $episodes  Массив эпизодов сезона
	 *
	 * @example
	 * ```php
	 * $season = new Season(
	 *     number: 1,
	 *     episodes: [$episode1, $episode2, $episode3]
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int   $number,
		public readonly array $episodes,
	) {}

	/**
	 * Создает экземпляр сезона из массива данных API
	 *
	 * Статический метод для удобного создания объекта Season из данных,
	 * полученных от Kinopoisk API. Автоматически создает объекты Episode
	 * для каждого эпизода в сезоне.
	 *
	 * @param   array  $data  Массив данных сезона от API
	 *
	 * @return self Новый экземпляр сезона
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'number' => 1,
	 *     'episodes' => [
	 *         ['number' => 1, 'name' => 'Пилот', ...],
	 *         ['number' => 2, 'name' => 'Второй эпизод', ...]
	 *     ]
	 * ];
	 *
	 * $season = Season::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			number  : $data['number'],
			episodes: array_map(fn ($episodeData) => Episode::fromArray($episodeData), $data['episodes']),
		);
	}

}