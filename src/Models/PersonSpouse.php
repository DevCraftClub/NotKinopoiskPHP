<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель супруга персоны из Kinopoisk API
 *
 * Представляет информацию о супруге персоны, полученную из Kinopoisk API.
 * Содержит данные о браке, детях, причинах развода и других аспектах
 * семейной жизни персоны.
 *
 * Основные возможности:
 * - Хранение информации о супруге в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Удобные методы для работы с семейным статусом
 * - Поддержка информации о детях и браке
 *
 * @package NotKinopoisk\Models
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Models\Person
 *
 * @example
 * ```php
 * // Создание из данных API
 * $spouse = PersonSpouse::fromArray($apiData);
 *
 * // Проверка семейного статуса
 * if ($spouse->isMarried()) {
 *     echo "В браке";
 * }
 *
 * // Информация о детях
 * echo $spouse->getChildrenInfo();
 * ```
 */
class PersonSpouse implements ModelInterface {

	/**
	 * Конструктор модели супруга
	 *
	 * Создает новый экземпляр супруга со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int          $personId        Уникальный идентификатор супруга в Кинопоиске
	 * @param   string|null  $name            Имя супруга
	 * @param   bool         $divorced        Статус развода
	 * @param   string|null  $divorcedReason  Причина развода (если применимо)
	 * @param   string       $sex             Пол супруга
	 * @param   int          $children        Количество детей
	 * @param   string       $webUrl          URL страницы супруга на Кинопоиске
	 * @param   string       $relation        Тип отношений (супруга, супруг и т.д.)
	 *
	 * @example
	 * ```php
	 * $spouse = new PersonSpouse(
	 *     personId: 32169,
	 *     name: 'Сьюзан Дауни',
	 *     divorced: false,
	 *     divorcedReason: null,
	 *     sex: 'FEMALE',
	 *     children: 2,
	 *     webUrl: 'https://www.kinopoisk.ru/name/32169/',
	 *     relation: 'супруга'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int     $personId,
		public readonly ?string $name,
		public readonly bool    $divorced,
		public readonly ?string $divorcedReason,
		public readonly string  $sex,
		public readonly int     $children,
		public readonly string  $webUrl,
		public readonly string  $relation,
	) {}

	/**
	 * Создает экземпляр супруга из массива данных API
	 *
	 * Статический метод для удобного создания объекта PersonSpouse из данных,
	 * полученных от Kinopoisk API. Автоматически обрабатывает nullable поля
	 * и устанавливает значения по умолчанию.
	 *
	 * @param   array  $data  Массив данных супруга от API
	 *
	 * @return self Новый экземпляр супруга
	 *
	 * @throws \InvalidArgumentException Если данные имеют неверный формат
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'personId' => 32169,
	 *     'name' => 'Сьюзан Дауни',
	 *     'divorced' => false,
	 *     'divorcedReason' => null,
	 *     'sex' => 'FEMALE',
	 *     'children' => 2,
	 *     'webUrl' => 'https://www.kinopoisk.ru/name/32169/',
	 *     'relation' => 'супруга'
	 * ];
	 *
	 * $spouse = PersonSpouse::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): static {
		return new self(
			personId      : $data['personId'],
			name          : $data['name'] ?? NULL,
			divorced      : $data['divorced'] ?? FALSE,
			divorcedReason: $data['divorcedReason'] ?? NULL,
			sex           : $data['sex'],
			children      : $data['children'] ?? 0,
			webUrl        : $data['webUrl'],
			relation      : $data['relation'],
		);
	}

	/**
	 * Проверяет, разведен ли супруг
	 *
	 * Возвращает true, если супруг разведен, и false в противном случае.
	 *
	 * @return bool true если разведен, false если в браке
	 *
	 * @example
	 * ```php
	 * if ($spouse->isDivorced()) {
	 *     echo "Разведен";
	 *     if ($spouse->divorcedReason) {
	 *         echo "Причина: {$spouse->divorcedReason}";
	 *     }
	 * } else {
	 *     echo "В браке";
	 * }
	 * ```
	 */
	public function isDivorced(): bool {
		return $this->divorced;
	}

	/**
	 * Проверяет, является ли супруг мужчиной
	 *
	 * @return bool true если мужчина, false если женщина
	 *
	 * @example
	 * ```php
	 * if ($spouse->isMale()) {
	 *     echo "Супруг";
	 * } else {
	 *     echo "Супруга";
	 * }
	 * ```
	 */
	public function isMale(): bool {
		return $this->sex === 'MALE';
	}

	/**
	 * Проверяет, является ли супруг женщиной
	 *
	 * @return bool true если женщина, false если мужчина
	 *
	 * @example
	 * ```php
	 * if ($spouse->isFemale()) {
	 *     echo "Супруга";
	 * } else {
	 *     echo "Супруг";
	 * }
	 * ```
	 */
	public function isFemale(): bool {
		return $this->sex === 'FEMALE';
	}

	/**
	 * Получает отображаемое имя супруга
	 *
	 * Возвращает имя супруга или "Неизвестно", если имя не указано.
	 *
	 * @return string Отображаемое имя супруга
	 *
	 * @example
	 * ```php
	 * echo "Супруг: {$spouse->getDisplayName()}";
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->name ?? 'Неизвестно';
	}

	/**
	 * Получает полную информацию о браке
	 *
	 * Возвращает строку с полной информацией о браке, включая статус,
	 * причину развода (если применимо) и количество детей.
	 *
	 * @return string Полная информация о браке
	 *
	 * @example
	 * ```php
	 * echo $spouse->getMarriageInfo();
	 * // "В браке, 2 ребенка" или "Разведен (причина), 1 ребенок"
	 * ```
	 */
	public function getMarriageInfo(): string {
		$status   = $this->isMarried() ? 'В браке' : 'Разведен';
		$reason   = $this->divorcedReason ? " ({$this->divorcedReason})" : '';
		$children = $this->getChildrenInfo();

		return "{$status}{$reason}, {$children}";
	}

	/**
	 * Проверяет, в браке ли супруг
	 *
	 * Возвращает true, если супруг в браке, и false если разведен.
	 *
	 * @return bool true если в браке, false если разведен
	 *
	 * @example
	 * ```php
	 * if ($spouse->isMarried()) {
	 *     echo "В браке";
	 * } else {
	 *     echo "Разведен";
	 * }
	 * ```
	 */
	public function isMarried(): bool {
		return !$this->divorced;
	}

	/**
	 * Получает информацию о детях в виде строки
	 *
	 * Возвращает строку с количеством детей или "Нет детей".
	 *
	 * @return string Информация о детях
	 *
	 * @example
	 * ```php
	 * echo "Дети: {$spouse->getChildrenInfo()}"; // "2 ребенка" или "Нет детей"
	 * ```
	 */
	public function getChildrenInfo(): string {
		if ($this->children === 0) {
			return 'Нет детей';
		}

		$word = $this->children === 1 ? 'ребенок' : ($this->children < 5 ? 'ребенка' : 'детей');

		return "{$this->children} {$word}";
	}

	/**
	 * Преобразует объект супруга в массив
	 *
	 * Возвращает все свойства объекта в виде ассоциативного массива.
	 * Полезно для сериализации, логирования или передачи данных.
	 *
	 * @return array Массив с данными супруга
	 *
	 * @example
	 * ```php
	 * $spouseArray = $spouse->toArray();
	 * echo json_encode($spouseArray); // JSON представление супруга
	 * ```
	 */
	public function toArray(): array {
		return [
			'personId'       => $this->personId,
			'name'           => $this->name,
			'divorced'       => $this->divorced,
			'divorcedReason' => $this->divorcedReason,
			'sex'            => $this->sex,
			'children'       => $this->children,
			'webUrl'         => $this->webUrl,
			'relation'       => $this->relation,
		];
	}

} 