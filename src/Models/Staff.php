<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

/**
 * Модель персонала фильма из Kinopoisk API
 *
 * Представляет информацию об участнике съемочной группы фильма:
 * актерах, режиссерах, сценаристах, операторах и других специалистах.
 * Содержит данные о роли, профессии и описании работы в фильме.
 *
 * Основные возможности:
 * - Хранение информации о персонале в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для определения профессии
 * - Получение отображаемого имени
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\StaffService
 * @see     \NotKinopoisk\Models\Person
 *
 * @example
 * ```php
 * // Создание из данных API
 * $staff = Staff::fromArray($apiData);
 *
 * // Получение отображаемого имени
 * echo $staff->getDisplayName();
 *
 * // Проверка профессии
 * if ($staff->isActor()) {
 *     echo "Актер: {$staff->description}";
 * } elseif ($staff->isDirector()) {
 *     echo "Режиссер";
 * }
 * ```
 */
class Staff {

	/**
	 * Конструктор модели персонала
	 *
	 * Создает новый экземпляр персонала со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int          $staffId         Уникальный идентификатор персонала в Кинопоиске
	 * @param   string|null  $nameRu          Имя персонала на русском языке
	 * @param   string|null  $nameEn          Имя персонала на английском языке
	 * @param   string|null  $description     Описание роли или работы в фильме
	 * @param   string       $posterUrl       URL фотографии персонала
	 * @param   string       $professionText  Текстовое описание профессии
	 * @param   string       $professionKey   Ключ профессии (ACTOR, DIRECTOR, WRITER и т.д.)
	 *
	 * @example
	 * ```php
	 * $staff = new Staff(
	 *     staffId: 12345,
	 *     nameRu: 'Том Круз',
	 *     nameEn: 'Tom Cruise',
	 *     description: 'Нео',
	 *     posterUrl: 'https://...',
	 *     professionText: 'Актер',
	 *     professionKey: 'ACTOR'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int     $staffId,
		public readonly ?string $nameRu,
		public readonly ?string $nameEn,
		public readonly ?string $description,
		public readonly string  $posterUrl,
		public readonly string  $professionText,
		public readonly string  $professionKey,
	) {}

	/**
	 * Создает экземпляр персонала из массива данных API
	 *
	 * Статический метод для удобного создания объекта Staff из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и устанавливает значения по умолчанию.
	 *
	 * @param   array  $data  Массив данных персонала от API
	 *
	 * @return self Новый экземпляр персонала
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'staffId' => 12345,
	 *     'nameRu' => 'Том Круз',
	 *     'nameEn' => 'Tom Cruise',
	 *     'description' => 'Нео',
	 *     'posterUrl' => 'https://...',
	 *     'professionText' => 'Актер',
	 *     'professionKey' => 'ACTOR'
	 * ];
	 *
	 * $staff = Staff::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			staffId       : $data['staffId'],
			nameRu        : $data['nameRu'] ?? NULL,
			nameEn        : $data['nameEn'] ?? NULL,
			description   : $data['description'] ?? NULL,
			posterUrl     : $data['posterUrl'],
			professionText: $data['professionText'],
			professionKey : $data['professionKey'],
		);
	}

	/**
	 * Получает отображаемое имя персонала
	 *
	 * Возвращает наиболее подходящее имя для отображения пользователю.
	 * Приоритет: русское имя → английское имя → "Без имени"
	 *
	 * @return string Отображаемое имя персонала
	 *
	 * @example
	 * ```php
	 * echo $staff->getDisplayName(); // "Том Круз" или "Tom Cruise" или "Без имени"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? 'Без имени';
	}

	/**
	 * Проверяет, является ли персонал актером
	 *
	 * Определяет, является ли участник съемочной группы актером
	 * на основе ключа профессии.
	 *
	 * @return bool true если это актер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($staff->isActor()) {
	 *     echo "Роль: {$staff->description}";
	 * }
	 * ```
	 */
	public function isActor(): bool {
		return $this->professionKey === 'ACTOR';
	}

	/**
	 * Проверяет, является ли персонал режиссером
	 *
	 * Определяет, является ли участник съемочной группы режиссером
	 * на основе ключа профессии.
	 *
	 * @return bool true если это режиссер, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($staff->isDirector()) {
	 *     echo "Режиссер: {$staff->getDisplayName()}";
	 * }
	 * ```
	 */
	public function isDirector(): bool {
		return $this->professionKey === 'DIRECTOR';
	}

	/**
	 * Проверяет, является ли персонал сценаристом
	 *
	 * Определяет, является ли участник съемочной группы сценаристом
	 * на основе ключа профессии.
	 *
	 * @return bool true если это сценарист, false в противном случае
	 *
	 * @example
	 * ```php
	 * if ($staff->isWriter()) {
	 *     echo "Сценарист: {$staff->getDisplayName()}";
	 * }
	 * ```
	 */
	public function isWriter(): bool {
		return $this->professionKey === 'WRITER';
	}

}