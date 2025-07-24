<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\FactType;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель факта из Kinopoisk API
 *
 * Представляет информацию о факте или ошибке, связанной с фильмом:
 * интересные факты о съемках, ошибки в фильме (блуперы) и т.д.
 *
 * Основные возможности:
 * - Хранение информации о факте в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Определение типа факта (ошибка или интересный факт)
 * - Проверка на спойлеры
 *
 * @package NotKinopoisk\Models
 * @api     /api/v2.2/films/{id}/external_sources
 * @since   1.0.0
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\FilmService
 * @see     \NotKinopoisk\Enums\FactType
 *
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/films/get_api_v2_2_films__id__facts
 *
 * @example
 * ```php
 * // Создание из данных API
 * $fact = Fact::fromArray($apiData);
 *
 * // Работа с фактом
 * if ($fact->type === FactType::BLOOPER) {
 *     echo "Ошибка в фильме: {$fact->text}\n";
 * } elseif ($fact->type === FactType::FACT) {
 *     echo "Интересный факт: {$fact->text}\n";
 * }
 *
 * if ($fact->spoiler) {
 *     echo "⚠️ Внимание: спойлер!\n";
 * }
 * ```
 */
class Fact implements ModelInterface {

	/**
	 * Конструктор модели факта
	 *
	 * Создает новый экземпляр факта со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   string    $text     Текст факта или описания ошибки
	 * @param   FactType  $type     Тип факта (FACT, BLOOPER и т.д.)
	 * @param   bool      $spoiler  Флаг, указывающий на наличие спойлера
	 *
	 * @example
	 * ```php
	 * $fact = new Fact(
	 *     text: 'В сцене драки видно, что актер использует дублера',
	 *     type: FactType::BLOOPER,
	 *     spoiler: false
	 * );
	 * ```
	 */
	public function __construct(
		public readonly string   $text,
		public readonly FactType $type,
		public readonly bool     $spoiler,
	) {}

	/**
	 * Создает экземпляр факта из массива данных API
	 *
	 * Статический метод для удобного создания объекта Fact из данных,
	 * полученных от Kinopoisk API.
	 *
	 * @param   array  $data  Массив данных факта от API
	 *
	 * @return self Новый экземпляр факта
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'text' => 'Интересный факт о съемках...',
	 *     'type' => 'FACT',
	 *     'spoiler' => false
	 * ];
	 *
	 * $fact = Fact::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			text   : $data['text'],
			type   : FactType::from($data['type']),
			spoiler: $data['spoiler'],
		);
	}

	/**
	 * Проверяет, является ли факт ошибкой в фильме
	 *
	 * Определяет, относится ли факт к категории ошибок (блуперов),
	 * которые были допущены во время съемок фильма.
	 *
	 * @return bool true если это ошибка в фильме, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($fact->isBlooper()) {
	 *     echo "Найдена ошибка: {$fact->text}";
	 * }
	 * ```
	 */
	public function isBlooper(): bool {
		return $this->type->isBlooper();
	}

	/**
	 * Проверяет, является ли факт интересным фактом
	 *
	 * Определяет, относится ли факт к категории интересных фактов
	 * о съемках, актерах или других аспектах создания фильма.
	 *
	 * @return bool true если это интересный факт, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($fact->isFact()) {
	 *     echo "Интересный факт: {$fact->text}";
	 * }
	 * ```
	 */
	public function isFact(): bool {
		return $this->type->isFact();
	}

	/**
	 * Преобразует объект факта в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными факта
	 *
	 * @example
	 * ```php
	 * $factArray = $fact->toArray();
	 * echo json_encode($factArray); // JSON представление факта
	 * ```
	 */
	public function toArray(): array {
		return [
			'text'    => $this->text,
			'type'    => $this->type->value,
			'spoiler' => $this->spoiler,
		];
	}

}