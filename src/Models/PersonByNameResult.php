<?php

declare(strict_types=1);

namespace NotKinopoisk\Models;

use NotKinopoisk\Enums\Sex;
use NotKinopoisk\Interfaces\ModelInterface;

/**
 * Модель результата поиска персоны по имени из Kinopoisk API
 *
 * Представляет краткую информацию о персоне в результатах поиска по имени.
 * Содержит основные данные: идентификатор, имена, пол и постер.
 *
 * Основные возможности:
 * - Хранение информации о персоне в неизменяемом виде
 * - Создание объекта из массива данных API
 * - Получение отображаемого имени персоны
 * - Доступ к метаданным поискового результата
 *
 * @package NotKinopoisk\Models
 * @api     /api/v1/persons
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\PersonService
 * @link    https://kinopoiskapiunofficial.tech/documentation/api/#/persons/get_api_v1_persons
 *
 * @example
 * ```php
 * // Создание из данных API
 * $personResult = PersonByNameResult::fromArray($apiData);
 *
 * // Использование
 * echo "Персона: {$personResult->getDisplayName()}\n";
 * echo "ID: {$personResult->kinopoiskId}\n";
 * echo "Пол: {$personResult->sex?->getDisplayName()}";
 * ```
 */
class PersonByNameResult implements ModelInterface {

	/**
	 * Конструктор модели результата поиска персоны по имени
	 *
	 * Создает новый экземпляр результата поиска со всеми необходимыми данными.
	 * Все свойства являются readonly для обеспечения неизменяемости объекта.
	 *
	 * @param   int           $kinopoiskId   Уникальный идентификатор персоны в Кинопоиске
	 * @param   string        $webUrl        URL веб-страницы персоны
	 * @param   string|null   $nameRu        Имя персоны на русском языке
	 * @param   string|null   $nameEn        Имя персоны на английском языке
	 * @param   Sex|null      $sex           Пол персоны (MALE, FEMALE, UNKNOWN)
	 * @param   string        $posterUrl     URL постера персоны
	 *
	 * @example
	 * ```php
	 * $personResult = new PersonByNameResult(
	 *     kinopoiskId: 66539,
	 *     webUrl: '10096',
	 *     nameRu: 'Винс Гиллиган',
	 *     nameEn: 'Vince Gilligan',
	 *     sex: Sex::MALE,
	 *     posterUrl: 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
	 * );
	 * ```
	 */
	public function __construct(
		public readonly int      $kinopoiskId,
		public readonly string   $webUrl,
		public readonly ?string  $nameRu,
		public readonly ?string  $nameEn,
		public readonly ?Sex     $sex,
		public readonly string   $posterUrl,
	) {}

	/**
	 * Создает экземпляр модели из массива данных API
	 *
	 * Преобразует сырые данные API в типизированный объект модели.
	 * Обрабатывает nullable поля и преобразует enum значения.
	 *
	 * @param   array  $data  Массив данных из API ответа
	 *
	 * @return  static  Новый экземпляр модели
	 *
	 * @throws  \ValueError  Если неверное значение для enum Sex
	 *
	 * @example
	 * ```php
	 * $apiData = [
	 *     'kinopoiskId' => 66539,
	 *     'webUrl' => '10096',
	 *     'nameRu' => 'Винс Гиллиган',
	 *     'nameEn' => 'Vince Gilligan',
	 *     'sex' => 'MALE',
	 *     'posterUrl' => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
	 * ];
	 * $personResult = PersonByNameResult::fromArray($apiData);
	 * ```
	 */
	public static function fromArray(array $data): self {
		return new self(
			kinopoiskId: $data['kinopoiskId'],
			webUrl      : $data['webUrl'],
			nameRu      : $data['nameRu'] ?? NULL,
			nameEn      : $data['nameEn'] ?? NULL,
			sex         : isset($data['sex']) ? Sex::from($data['sex']) : NULL,
			posterUrl   : $data['posterUrl'],
		);
	}

	/**
	 * Возвращает отображаемое имя персоны
	 *
	 * Приоритет отдается русскому имени, затем английскому.
	 * Если оба имени отсутствуют, возвращает строку "Неизвестно".
	 *
	 * @return  string  Отображаемое имя персоны
	 *
	 * @example
	 * ```php
	 * echo $personResult->getDisplayName(); // "Винс Гиллиган"
	 * ```
	 */
	public function getDisplayName(): string {
		return $this->nameRu ?? $this->nameEn ?? 'Неизвестно';
	}

	/**
	 * Проверяет, является ли персона мужчиной
	 *
	 * @return  bool  true если персона мужского пола
	 *
	 * @example
	 * ```php
	 * if ($personResult->isMale()) {
	 *     echo "Мужчина";
	 * }
	 * ```
	 */
	public function isMale(): bool {
		return $this->sex === Sex::MALE;
	}

	/**
	 * Проверяет, является ли персона женщиной
	 *
	 * @return  bool  true если персона женского пола
	 *
	 * @example
	 * ```php
	 * if ($personResult->isFemale()) {
	 *     echo "Женщина";
	 * }
	 * ```
	 */
	public function isFemale(): bool {
		return $this->sex === Sex::FEMALE;
	}

	/**
	 * Проверяет, известен ли пол персоны
	 *
	 * @return  bool  true если пол персоны неизвестен
	 *
	 * @example
	 * ```php
	 * if ($personResult->isSexUnknown()) {
	 *     echo "Пол неизвестен";
	 * }
	 * ```
	 */
	public function isSexUnknown(): bool {
		return $this->sex === Sex::UNKNOWN || $this->sex === NULL;
	}

	/**
	 * Возвращает полное имя персоны (русское + английское)
	 *
	 * Если есть оба имени, возвращает их через разделитель.
	 * Если есть только одно имя, возвращает его.
	 *
	 * @param   string  $separator  Разделитель между именами (по умолчанию " / ")
	 *
	 * @return  string  Полное имя персоны
	 *
	 * @example
	 * ```php
	 * echo $personResult->getFullName(); // "Винс Гиллиган / Vince Gilligan"
	 * echo $personResult->getFullName(' | '); // "Винс Гиллиган | Vince Gilligan"
	 * ```
	 */
	public function getFullName(string $separator = ' / '): string {
		if ($this->nameRu && $this->nameEn) {
			return $this->nameRu . $separator . $this->nameEn;
		}

		return $this->getDisplayName();
	}

	/**
	 * Преобразует модель в массив
	 *
	 * Возвращает массив с данными модели в том же формате, что и API.
	 * Enum значения преобразуются в строки.
	 *
	 * @return  array  Массив данных модели
	 *
	 * @example
	 * ```php
	 * $array = $personResult->toArray();
	 * // [
	 * //     'kinopoiskId' => 66539,
	 * //     'webUrl' => '10096',
	 * //     'nameRu' => 'Винс Гиллиган',
	 * //     'nameEn' => 'Vince Gilligan',
	 * //     'sex' => 'MALE',
	 * //     'posterUrl' => 'https://kinopoiskapiunofficial.tech/images/actor_posters/kp/10096.jpg'
	 * // ]
	 * ```
	 */
	public function toArray(): array {
		return [
			'kinopoiskId' => $this->kinopoiskId,
			'webUrl'      => $this->webUrl,
			'nameRu'      => $this->nameRu,
			'nameEn'      => $this->nameEn,
			'sex'         => $this->sex?->value,
			'posterUrl'   => $this->posterUrl,
		];
	}

} 